<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
   <link rel="stylesheet" type="text/css" href="authorized.css">
    <link rel="stylesheet" type="text/css" href="authorized.js">
	<title>Cashier Dashboard</title>

<?php
    session_start();

    $cashierName = $_GET['cashierName'];
    if ($_SESSION['SESS_FIRST_NAME'] != $cashierName) {
        header("location: ../staffSignInForm.php");
        exit();
    }

	include "../ServerConnection/configConnectRecords.php";

	$date = new DateTime();
	$date->add(new DateInterval('PT6H'));
	$fullDateFormat = $date->format('Y-m-d');
	$timeFormat = $date->format('h:i A');

	$beginBalance = $_GET['beginBalance'];
	$transactNumString = $_GET['transactNumString'];
	$notify = "";

    $transactNum = $_GET['transactNum'];
	$sql = "SELECT * FROM qrcodesSales
		WHERE qrSalesTransactNum = '$transactNum' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$transactNumString = $row['qrSalesTransactString'];
	}

	$sql = "SELECT * FROM qrcodesCashier
		WHERE qrCashierName = '$cashierName' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$cashierId = $row['qrCashierId'];
	}

    $resultDisplay = '';
    $rowSearchedAdminName = '';

    if( isset($_GET['qrNameAdmin']) and isset($_GET['qrPassAdmin']) ) {
        $qrNameAdmin=$_GET['qrNameAdmin'];
        $qrPassAdmin=hash('sha512', $_GET['qrPassAdmin'], false);

        $sql = "SELECT * FROM qrcodesAdmin
            WHERE qrAdminName = '$qrNameAdmin' AND qrAdminPassword = '$qrPassAdmin' ";
        $retrieveAdminName = $db->prepare($sql);
        $retrieveAdminName->execute();
        for($i=0; $rowAdminName = $retrieveAdminName->fetch(); $i++){
            $rowSearchedAdminName = $rowAdminName['qrAdminName'];
        }

        if($rowSearchedAdminName == '') {
			$staffActivity = "The User Tries to Authorized to Delete an Item on the Customer Shopping List on Cashier POS!";

			$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
				VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
			$resultAudit = $db->prepare($sql);
			$resultAudit->execute();

            $resultDisplay = 'Invalid Either Username or Password!';
        }

        else {
			$purchaseItemId = $_GET['purchaseItemId'];

            $stockId = $_GET['stockId'];
            $sql = "SELECT * FROM qrcodesSupplier
                WHERE qrSupplierId = '$stockId' ";
            $result = $db->prepare($sql);
            $result->execute();
            for($i=0; $row = $result->fetch(); $i++){
				$productName = $row['qrSupplierProductName'];
			}

			$transactNum = $_GET['transactNum'];

            $sql = "SELECT * FROM qrcodesPurchaseItem
                INNER JOIN qrcodesSales
                ON qrcodesPurchaseItem.qrSalesTransactNum = qrcodesSales.qrSalesTransactNum
                WHERE qrcodesPurchaseItem.qrSalesTransactNum = '$transactNum' AND qrcodesPurchaseItem.qrStockId = '$stockId' ";
            $result = $db->prepare($sql);
            $result->execute();
            for($i=0; $row = $result->fetch(); $i++){
                $productQtyAvailReturn = $row['qrPurchaseItemQtyAvail'];
            }

            $sql = "UPDATE qrcodesStock 
                SET qrStockQtyLeft=qrStockQtyLeft+?
                WHERE qrStockId=?";
            $q = $db->prepare($sql);
            $q->execute(array($productQtyAvailReturn,$stockId));

			$sql = "INSERT INTO qrcodesReturnItem (qrReturnItemQtyAvail, qrStockId, qrSalesTransactNum)
				VALUES ('$productQtyAvailReturn', '$stockId', '$transactNum')";
            $result = $db->prepare($sql);
            $result->execute();

            $sql = "DELETE FROM qrcodesPurchaseItem
                WHERE qrPurchaseItemId = '$purchaseItemId' ";
            $result = $db->prepare($sql);
            $result->execute();

            $sql = "SELECT * FROM qrcodesSales
                WHERE qrSalesTransactNum = '$transactNum' ";
            $result = $db->prepare($sql);
            $result->execute();
            for($i=0; $row = $result->fetch(); $i++){
                $amountTender = $row['qrSalesTenderCash'];
            }

            $productQtyAvail = 0;
            $productPrice = 0;
			$subTotal = 0;
            $sql = "SELECT * FROM qrcodesPurchaseItem
                INNER JOIN qrcodesSales
                ON qrcodesPurchaseItem.qrSalesTransactNum = qrcodesSales.qrSalesTransactNum
                INNER JOIN qrcodesStock
                ON qrcodesPurchaseItem.qrStockId = qrcodesStock.qrStockId
                INNER JOIN qrcodesProduct
                ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
                WHERE qrcodesPurchaseItem.qrSalesTransactNum = '$transactNum' ";
            $result = $db->prepare($sql);
            $result->execute();
            for($i=0; $row = $result->fetch(); $i++){
				$productQtyAvail = $row['qrPurchaseItemQtyAvail'];
				$productPrice = $row['qrProductPrice'];

				$subTotal = ($productQtyAvail * $productPrice) + $subTotal;
            }

			if ($productQtyAvail == "" && $productPrice == "" && $subTotal == "") {
				$productQtyAvail = 0;
				$productPrice = 0;
				$subTotal = 0;				
			}

			$tax = .12;
			$taxAmount = $tax * $subTotal;
			$salesAmount = $subTotal + $taxAmount;
            $amountChange = $amountTender - $salesAmount;

            $sql = "UPDATE qrcodesSales
                SET qrSalesAmount='$salesAmount', qrSalesTaxPercentage='$tax', qrSalesTaxAmount='$taxAmount', qrSalesAmountChange='$amountChange'
                WHERE qrSalesTransactNum='$transactNum' ";
            $q = $db->prepare($sql);
            $q->execute();

			$staffActivity = "Admin Named " . $rowSearchedAdminName . " Successfully Authorized Cashier to Delete an Item Named " . $productName . " with Transaction Number " . $transactNumString . " on the Customer Shopping List on Cashier POS!";

			$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
				VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
			$resultAudit = $db->prepare($sql);
			$resultAudit->execute();

            $notify = "<span style = 'color: Green; font-weight: Bold;'><center>" . $productName . " Successfully Removed on Shopping List!</center></span>";

            header("location: cashierReturnItem.php?transactNum=$transactNum&cashierName=$cashierName&beginBalance=$beginBalance&transactNumString=$transactNumString&notify=$notify");
        }
    }
?>

<div class="login-page">
    <div class="form">
        <h2>Admin Authorization</h2>
        <form class="login-form">
			<input type = "hidden" name = "transactNumString" value = "<?php echo $transactNumString = $_GET['transactNumString']; ?>"/>
            <input type = "hidden" name = "transactNum" value = "<?php echo $transactNum = $_GET['transactNum']; ?>"/>
            <input type = "hidden" name = "cashierName" value = "<?php echo $cashierName = $_GET['cashierName']; ?>"/>
            <input type = "hidden" name = "beginBalance" value = "<?php echo $beginBalance = $_GET['beginBalance']; ?>"/>

			<input type = "hidden" name = "purchaseItemId" value = "<?php echo $purchaseItemId = $_GET['purchaseItemId']; ?>"/>
			<input type = "hidden" name = "stockId" value = "<?php echo $stockId = $_GET['stockId']; ?>"/>
			<input type = "hidden" name = "notify" value = "<?php echo $notify = $_GET['notify']; ?>"/>

            <input type="text" placeholder="username" name="qrNameAdmin" required value="<?php
                if(isset($_GET['AdminSignIn'])){
                    echo $_GET['qrNameAdmin'];
                } ?>"/>
            <input type="password" placeholder="password" name="qrPassAdmin" required value="<?php
                if(isset($_GET['AdminSignIn'])){
                    echo $_GET['qrPassAdmin'];
                } ?>"/>

            <center style = "color: Red; font-weight: Bold; font-size: 14; white-space: pre;"><?php echo $resultDisplay; ?></center>

            <button>Void</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href = "cashierReturnItem.php?transactNum=<?php echo $transactNum; ?>&cashierName=<?php echo $cashierName; ?>&transactNumString=<?php echo $transactNumString; ?>&beginBalance=<?php echo $beginBalance; ?>&notify" style = "text-decoration: none; color: White;"><button class="button" type = "button">Cancel</button></a>
        </form>
    </div>
</div>
</head>
</html>