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

	$purchaseId=$_GET['purchaseId'];
	$sql = "SELECT * FROM qrcodesSales
		WHERE qrSalesTransactNum = '$purchaseId' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$transactNumString = $row['qrSalesTransactString'];
	}

	$sql = "SELECT * FROM qrcodesAdmin
		WHERE qrAdminName = '$adminName' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$adminId = $row['qrAdminId'];
	}

    $sql = "DELETE FROM qrcodesReturnItem
        WHERE qrSalesTransactNum = '$purchaseId' ";
	$result = $db->prepare($sql);
	$result->execute();

    $sql = "DELETE FROM qrcodesPurchaseItem
        WHERE qrSalesTransactNum = '$purchaseId' ";
	$result = $db->prepare($sql);
	$result->execute();

    $sql = "DELETE FROM qrcodesSales
        WHERE qrSalesTransactNum = '$purchaseId' ";
	$result = $db->prepare($sql);
	$result->execute();

	$staffActivity = "Deletes Transaction Number " . $transactNumString . " on Admin Invoice Management!";

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();
?>