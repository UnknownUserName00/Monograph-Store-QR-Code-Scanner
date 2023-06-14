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

	$pageOrigin = $_GET['pageOrigin'];
	$linkPage = $_GET['linkPage'];

    $sql = "SELECT * FROM qrcodesAdmin
		WHERE qrAdminName = '$adminName' ";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
		$adminId = $row['qrAdminId'];
	}

	if (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminDashboard.php") {
		$staffActivity = "Opened Admin Dashboard!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminProduct.php") {
		$notify = $_GET['notify'];

		$staffActivity = "Opened Admin Product Management!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&notify=" . $notify;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminSupply.php") {
		$notify = $_GET['notify'];

		$staffActivity = "Opened Admin Supply Management!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&notify=" . $notify;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminPurchaseList.php") {
		$staffActivity = "Opened Admin Invoice Management!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminAlert.php") {
		$staffActivity = "Opened Admin Critical Management!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminGenerateQr.php") {
		$staffActivity = "Opened Admin Generate QR Code Page!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminPrintSalesReport.php") {
		$staffActivity = "Opened Admin Sales Report!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminPrintSupply.php") {
		$staffActivity = "Opened Admin Supply Report!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminPrintInventory.php") {
		$staffActivity = "Opened Admin Inventory Report!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminPrintSupplierInvoice.php") {
		$notify = $_GET['notify'];

		$staffActivity = "Opened Admin Order Summary Report!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&notify=" . $notify;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminUserRegistration.php") {
		$notify = $_GET['notify'];

		$staffActivity = "Opened Admin User Registration Form!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&notify=" . $notify;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminUserChangePass.php") {
		$notify = $_GET['notify'];

		$staffActivity = "Opened Admin User Change Password Form!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&notify=" . $notify;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminPrintAuditTrail.php") {
		$staffActivity = "Opened Admin Audit Trail Form!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	elseif (
		($pageOrigin == "adminDashboard.php" || $pageOrigin == "adminProduct.php"
		|| $pageOrigin == "adminSupply.php" || $pageOrigin == "adminGenerateQr.php"
		|| $pageOrigin == "adminPurchaseList.php" || $pageOrigin == "adminAlert.php"
		|| $pageOrigin == "adminPrintSalesReport.php" || $pageOrigin == "adminPrintSupply.php"
		|| $pageOrigin == "adminPrintInventory.php" || $pageOrigin == "adminPrintSupplierInvoice.php"
		|| $pageOrigin == "adminUserRegistration.php" || $pageOrigin == "adminUserChangePass.php"
		|| $pageOrigin == "adminPrintAuditTrail.php" || $pageOrigin == "adminOrderSummaryList.php")
	&& $linkPage == "adminOrderSummaryList.php") {
		$staffActivity = "Opened Admin Order Management Form!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	if ($pageOrigin == "adminProduct.php" && $linkPage == "adminEditProductId.php") {
		$productId = $_GET['productId'];
		$sql = "SELECT * FROM qrcodesSupplier
			WHERE qrSupplierId = '$productId' ";
		$result = $db->prepare($sql);
		$result->execute();
		for($i=0; $row = $result->fetch(); $i++){
			$productName = $row['qrSupplierProductName'];
		}

		$notify = $_GET['notify'];

		$staffActivity = "Opened Edit Product Form to Edit Product Named " . $productName . " on Admin Product Management!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&productId=" . $productId . "&notify=" . $notify;
	}

	if ($pageOrigin == "adminEditProductId.php" && $linkPage == "adminProduct.php") {
		$notify = $_GET['notify'];

		$staffActivity = "Cancels to Edit the Product Info on Admin Product Management!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&notify=" . $notify;
	}

	if ($pageOrigin == "adminSupply.php" && $linkPage == "adminEditSupplierId.php") {
		$supplierId = $_GET['supplierId'];
		$sql = "SELECT * FROM qrcodesSupplier
			WHERE qrSupplierId = '$supplierId' ";
		$result = $db->prepare($sql);
		$result->execute();
		for($i=0; $row = $result->fetch(); $i++){
			$productName = $row['qrSupplierProductName'];
		}

		$notify = $_GET['notify'];

		$staffActivity = "Opened Edit Supply Form to Edit Product Named " . $productName . " on Admin Supply Management!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&supplierId=" . $supplierId . "&notify=" . $notify;
	}

	if ($pageOrigin == "adminEditSupplierId.php" && $linkPage == "adminSupply.php") {
		$notify = $_GET['notify'];

		$staffActivity = "Cancels to Edit the Supplier Info on Admin Supply Management!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&notify=" . $notify;
	}

	if ($pageOrigin == "adminPrintSalesReport.php" && $linkPage == "adminPrintReadySalesReport.php") {
		$getDateFrom = $_GET['getDateFrom'];
		$getDateTo = $_GET['getDateTo'];

		$staffActivity = "Prints Dales Report on Admin Sales Report Form!";

		$movePage = $linkPage . "?getDateFrom=" . $getDateFrom . "&getDateTo=" . $getDateTo . "&adminName=" . $adminName;
	}

	if ($pageOrigin == "adminPrintSupplierInvoice.php" && $linkPage == "adminPrintReadySupplierInvoice.php") {
		$supplierName = $_GET['supplierName'];
		$supplierAddress = $_GET['supplierAddress'];
		$checkTrackNum = $_GET['checkTrackNum'];

		$alterOrderId = 1;
		$orderSummaryId = 0;
		$sql = "SELECT MAX(qrOrderSummaryId) FROM qrcodesOrderSummary";
		$result = $db->prepare($sql);
		$result->execute();
		for($i=0; $row = $result->fetch(); $i++){
			$orderSummaryId = $row['MAX(qrOrderSummaryId)'] + $alterOrderId;
		}

		if ($orderSummaryId == "") {
			$orderSummaryId = $alterOrderId;
		}

		$trackNum = "OS-" . $fullDateFormat . "-" . $orderSummaryId;

		if ($checkTrackNum != $trackNum) {
			$staffActivity = "Tries to Print Order on Admin Order Summary Report!";
			$notify = "<span style = 'color: Red;'><strong>Sorry! Please Refresh the Page First and Fill-up the Form Again Before Printing!</strong></span><br/><br/>";

			$linkPage = $pageOrigin;
			$movePage = $linkPage . "?adminName=" . $adminName . "&notify=" . $notify;
		}

		elseif ($checkTrackNum == $trackNum) {
			$sql = "INSERT INTO qrcodesOrderSummary (qrOrderSummaryNumFormat, qrOrderSummaryDate, qrOrderSummaryTime, qrOrderSummarySupplierName, qrOrderSummarySupplierAddress)
				VALUES ('$trackNum', '$fullDateFormat', '$timeFormat', '$supplierName', '$supplierAddress')";
			$result = $db->prepare($sql);
			$result->execute();

			$sql = "SELECT * FROM qrcodesSupplier
				INNER JOIN qrcodesProduct
				ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrProductId
				INNER JOIN qrcodesStock
				ON qrcodesProduct.qrProductId = qrcodesStock.qrStockId
				ORDER BY qrcodesSupplier.qrSupplierId DESC";
			$result = $db->prepare($sql);
			$result->execute();
			for($i=0; $row = $result->fetch(); $i++){
				$productLeft = $row['qrStockQtyLeft'];
				$productDelivered = $row['qrSupplierProductQtyDelivered'];
				$productLimit = round( (($productLeft / $productDelivered) * 100) );
				$adminSetCritical = $row['qrStockCriticalLevelSet'];

				if ($productLimit <= $adminSetCritical) {
					$stockId = $row['qrStockId'];
					$productQtyCritical = $row['qrStockQtyLeft'];

					$sql = "INSERT INTO qrcodesOrderProduct (qrOrderProductQtyCritical, qrOrderSummaryId, qrStockId)
						VALUES ('$productQtyCritical', '$orderSummaryId', '$stockId')";
					$result1 = $db->prepare($sql);
					$result1->execute();
				}
			}

			$staffActivity = "Prints Order on Admin Order Summary Report!";

			$movePage = $linkPage . "?adminName=" . $adminName . "&supplierName=" . $supplierName . "&supplierAddress=" . $supplierAddress . "&trackNum=" . $trackNum;
		}
	}

	if ($pageOrigin == "adminPrintSupply.php" && $linkPage == "adminPrintReadySupply.php") {
		$staffActivity = "Prints Report Form on Admin Supply Report!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	if ($pageOrigin == "adminPrintInventory.php" && $linkPage == "adminPrintReadyInventory.php") {
		$staffActivity = "Prints Report Form on Admin Inventory Report!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	if ($pageOrigin == "adminPrintAuditTrail.php" && $linkPage == "adminPrintReadyAuditTrail.php") {
		$staffActivity = "Prints Report Form on Admin Audit Trail Report!";

		$movePage = $linkPage . "?adminName=" . $adminName;
	}

	if ($pageOrigin == "adminPurchaseList.php" && $linkPage == "adminPrintPurchaseList.php") {
		$transactNum = $_GET['transactNum'];

		$staffActivity = "Reprints Customer Receipt on Admin Invoice Management!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&transactNum=" . $transactNum;
	}

	if ($pageOrigin == "adminOrderSummaryList.php" && $linkPage == "adminPrintReadySupplierInvoice.php") {
		$trackNum = $_GET['trackNum'];
		$supplierName = $_GET['supplierName'];
		$supplierAddress = $_GET['supplierAddress'];

		$staffActivity = "Reprints Form on Admin Order Management!";

		$movePage = $linkPage . "?adminName=" . $adminName . "&trackNum=" . $trackNum . "&supplierName=" . $supplierName . "&supplierAddress=" . $supplierAddress;
	}

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../" . $movePage);
?>