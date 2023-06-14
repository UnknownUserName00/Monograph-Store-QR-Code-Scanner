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

	$supplierId=$_GET['supplierId'];
    $sql = "SELECT * FROM qrcodesSupplier
		WHERE qrSupplierId = '$supplierId' ";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
		$productName = $row['qrSupplierProductName'];
	}

    $sql = "SELECT * FROM qrcodesAdmin
		WHERE qrAdminName = '$adminName' ";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
		$adminId = $row['qrAdminId'];
	}

    $sql = "DELETE FROM qrcodesStock
        WHERE qrStockId = '$supplierId' ";
	$result = $db->prepare($sql);
	$result->execute();

    $sql = "DELETE FROM qrcodesProduct
        WHERE qrProductId = '$supplierId' ";
	$result = $db->prepare($sql);
	$result->execute();

    $sql = "DELETE FROM qrcodesSupplier
        WHERE qrSupplierId = '$supplierId' ";
	$result = $db->prepare($sql);
	$result->execute();

	$staffActivity = "Deleted Product Named " . $productName . " on Admin Supply Management!";

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();
?>