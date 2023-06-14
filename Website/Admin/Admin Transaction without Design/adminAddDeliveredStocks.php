<?php
	session_start();

    $adminName = $_GET['adminName'];
    if ($_SESSION['SESS_FIRST_NAME'] != $adminName) {
		header("location: ../../staffSignInForm.php");
		exit();
    }

	include "../../ServerConnection/configConnectRecords.php";

    $date = new DateTime();
    $date->add(new DateInterval('PT6H'));
    $fullDateFormat = $date->format('Y-m-d');
    $timeFormat = $date->format('h:i A');

	$notify = "";
	$productName = $_GET['productName'];
	$productQtyDelivered = $_GET['productQtyDelivered'];
	$productDateExpired = $_GET['productDateExpired'];
	$timeDelivered = $_GET['timeDelivered'];
	$dateDelivered = $_GET['dateDelivered'];

	$checkProductNameCharSymbols = preg_match('/[\'^£$%&*}{@#~?><>|=_+¬-]/', $productName);

	$sql = "SELECT * FROM qrcodesAdmin
		WHERE qrAdminName = '$adminName' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$adminId = $row['qrAdminId'];
	}

	if ($checkProductNameCharSymbols == 0) {
		$sql = "SELECT * FROM qrcodesSupplier
			WHERE qrSupplierProductName = '$productName' ";
		$validateSupplier = $db->prepare($sql);
		$validateSupplier->execute();
		for($i=0; $rowSupplier = $validateSupplier->fetch(); $i++){
			$supplierId = $rowSupplier['qrSupplierId'];
		}

		$oldStocksDelivered = 0;
		$qtyLeft = 0;
		$sql = "SELECT * FROM qrcodesStock
			INNER JOIN qrcodesProduct
			ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
			INNER JOIN qrcodesSupplier
			ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId
			WHERE qrcodesSupplier.qrSupplierProductName = '$productName' ";
		$validateStocks = $db->prepare($sql);
		$validateStocks->execute();
		for($i=0; $rowStocks = $validateStocks->fetch(); $i++){
			$oldStocksDelivered = $rowStocks['qrSupplierProductQtyDelivered'];
			$qtyLeft = $rowStocks['qrStockQtyLeft'];
		}

		$totalStocks = $productQtyDelivered + $qtyLeft;

		if ($oldStocksDelivered != "" && $qtyLeft != "") {
			$sql = "UPDATE qrcodesSupplier
				SET qrSupplierProductQtyDelivered = '$productQtyDelivered',
				qrSupplierProductDateExpired = '$productDateExpired',
				qrSupplierTimeDelivered = '$timeDelivered',
				qrSupplierDateDelivered = '$dateDelivered'
				WHERE qrSupplierProductName = '$productName' ";
			$q = $db->prepare($sql);
			$q->execute();

			$sql = "UPDATE qrcodesStock
				SET qrStockQtyLeft=qrStockQtyLeft+?
				WHERE qrStockId=? ";
			$q = $db->prepare($sql);
			$q->execute(array($totalStocks,$supplierId));

			$notify = "<span style = 'color: Green;'>Product Named " . $productName . " Successfully Registered Qty Delivered Stocks!</span>";

			$staffActivity = "Successfully Add Delivered Stock on Admin Supply Management!";
		}

		elseif ($oldStocksDelivered == "" && $qtyLeft == "") {
			$notify = "<span style = 'color: Red;'>Sorry! Your Selling Product Named " . $productName . " Info is Not Yet Set in the System to Get New Delivered Stocks!</span>";

			$staffActivity = "Tries to Add Delivered Stock on Admin Supply Management!";
		}

        else {
			$notify = "<span style = 'color: Red;'>Invalid Product Name!<br/>Please Try Again!</span>";

            $staffActivity = "Tries to Add Delivered Stock on Admin Supply Management!";
        }
	}

	else {
		$notify = "<span style = 'color: Red;'>Invalid Product Name!<br/>Please Try Again!</span>";

		$staffActivity = "Tries to Add Delivered Stock on Admin Supply Management!";
	}

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../adminSupply.php?adminName=$adminName&notify=$notify");
?>