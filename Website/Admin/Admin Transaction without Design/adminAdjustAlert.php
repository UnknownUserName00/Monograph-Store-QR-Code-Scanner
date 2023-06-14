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

    $adjustCritical = $_GET['criticalLevel'];

	$sql = "SELECT * FROM qrcodesAdmin
		WHERE qrAdminName = '$adminName' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$adminId = $row['qrAdminId'];
	}

    $sql = "UPDATE qrcodesStock
        SET qrStockCriticalLevelSet = '$adjustCritical'";	
    $result = $db->prepare($sql);
    $result->execute();

	$staffActivity = "Adjusted the Critical Stocks Value to " . $adjustCritical . " on Admin Crtical Management";

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../adminAlert.php?adminName=$adminName");
?>