<?php
session_start();

$cashierName = $_GET['cashierName'];
if ($_SESSION['SESS_FIRST_NAME'] != $cashierName) {
    header("location: ../../staffSignInForm.php");
    exit();
}

include "../../ServerConnection/configConnectRecords.php";

$date = new DateTime();
$date->add(new DateInterval('PT6H'));
$fullDateFormat = $date->format('Y-m-d');
$timeFormat = $date->format('h:i A');

$transactNum = $_GET['transactNum'];

$sql = "SELECT * FROM qrcodesCashier
    WHERE qrCashierName = '$cashierName' ";
$result = $db->prepare($sql);
$result->execute();
for($i=0; $row = $result->fetch(); $i++){
	$cashierId = $row['qrCashierId'];
}

$productName = $_GET['productName'];
$productQty = $_GET['productQty'];
$beginBalance = $_GET['beginBalance'];

if ($transactNum != "") {
	$sql = "SELECT * FROM qrcodesSales
        INNER JOIN qrcodesPurchaseItem
        ON qrcodesSales.qrSalesTransactNum = qrcodesPurchaseItem.qrSalesTransactNum
        INNER JOIN qrcodesStock
        ON qrcodesPurchaseItem.qrStockId = qrcodesStock.qrStockId
		INNER JOIN qrcodesProduct
		ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
		INNER JOIN qrcodesSupplier
		ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrSupplierId
		WHERE qrcodesSupplier.qrSupplierProductName = '$productName' AND qrcodesSales.qrSalesTransactNum = '$transactNum' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
        $checkProductName = $row['qrSupplierProductName'];
	}

	$sql = "SELECT * FROM qrcodesStock
		INNER JOIN qrcodesProduct
		ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
		INNER JOIN qrcodesSupplier
		ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrSupplierId
		WHERE qrcodesSupplier.qrSupplierProductName = '$productName' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$stockId = $row['qrStockId'];
        $qtyLeft = $row['qrStockQtyLeft'];
	}

    $totalStock = $qtyLeft - $productQty;
	$notify = "";

	if ($checkProductName == $productName) {
		$notify = "<strong>" . $productName . " is Already Registered to Your Shopping List!</strong>";
		
		$staffActivity = "Cashier Tries to Add an Item on the Shopping List of the Customer on Cashier POS Page!";
	}

	elseif ($totalStock <= 0) {
		$notify = "<strong>The Stocks from the Store for " . $productName . " is in Insufficient Level to Attain Your Order! Please Choose Other Alternative Products!</strong>";

		$staffActivity = "Cashier Tries to Add an Item on the Shopping List of the Customer on Cashier POS Page!";
	}

    elseif ( ($totalStock > 0) && ($checkProductName != $productName) ) {
        $sql = "INSERT INTO qrcodesPurchaseItem (qrPurchaseItemQtyAvail, qrStockId, qrSalesTransactNum)
            VALUES ('$productQty', '$stockId', '$transactNum')";
        $q = $db->prepare($sql);
        $q->execute();

        $sql = "SELECT * FROM qrcodesSales
            WHERE qrSalesTransactNum = '$transactNum' ";
        $r = $db->prepare($sql);
        $r->execute();
        for($i=0; $rowTransactNum = $r->fetch(); $i++){
            $salesTaxPercentage = $rowTransactNum['qrSalesTaxPercentage'];
            $amountTender = $rowTransactNum['qrSalesTenderCash'];
        }

        if ($salesTaxPercentage == "") {
            $salesTaxPercentage = 0;
        }

        $subTotal = 0;
        $sql = "SELECT * FROM qrcodesPurchaseItem
            INNER JOIN qrcodesStock
            ON qrcodesPurchaseItem.qrStockId = qrcodesStock.qrStockId
            INNER JOIN qrcodesProduct
            ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
            WHERE qrcodesPurchaseItem.qrSalesTransactNum = '$transactNum' ";
        $retrieveItems = $db->prepare($sql);
        $retrieveItems->execute();
        for($i=0; $rowItems = $retrieveItems->fetch(); $i++){
            $productQtyAvail = $rowItems['qrPurchaseItemQtyAvail'];
            $productPrice = $rowItems['qrProductPrice'];

            $subTotal = ($productQtyAvail * $productPrice) + $subTotal;
        }

        if ($subTotal == "") {
            $subTotal = 0;
        }

        $salesTaxAmount = $subTotal * $salesTaxPercentage;
        $salesAmount = $salesTaxAmount + $subTotal;
        $amountChange = $amountTender - $salesAmount;

        $sql = "UPDATE qrcodesStock 
            SET qrStockQtyLeft=qrStockQtyLeft-?
            WHERE qrStockId=?";
        $q = $db->prepare($sql);
        $q->execute(array($productQtyAvail,$stockId));

        $sql = "UPDATE qrcodesSales
            SET qrSalesAmount = '$salesAmount', qrSalesTaxAmount = '$salesTaxAmount', qrSalesAmountChange = '$amountChange'
            WHERE qrSalesTransactNum = '$transactNum' ";
        $s = $db->prepare($sql);
        $s->execute();

		$notify = "";

		$staffActivity = "Cashier Successfully Add an Item on the Shopping List of the Customer on Cashier POS Page!";
    }
}

else {
    $notify = "<span style = 'color: Red; font-weight: Bold;'><center>Sorry! No Valid Shopping List Format Detected from the Camera!</center></span>";

	$staffActivity = "Cashier Tries to Add an Item on the Shopping List of the Customer on Cashier POS Page!";
}

$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
	VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
$resultAudit = $db->prepare($sql);
$resultAudit->execute();

header("location: ../cashierPOS.php?transactNum=$transactNum&notify=$notify&cashierName=$cashierName&beginBalance=$beginBalance&productItems");
?>