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

	$transactNum = "";
	$beginBalance = $_GET['beginBalance'];
	$transactNumString = $_GET['transactNumString'];
	$notify = "";

	$sql = "SELECT * FROM qrcodesCashier
		WHERE qrCashierName = '$cashierName' ";
	$retrieveCashier = $db->prepare($sql);
	$retrieveCashier->execute();
	for($i=0; $rowCashier = $retrieveCashier->fetch(); $i++){
		$cashierId = $rowCashier['qrCashierId'];
	}

	$staffActivity = "Cashier Resets the Values Displayed on Cashier Return Item Page!";

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../cashierReturnItem.php?transactNum=$transactNum&cashierName=$cashierName&beginBalance=$beginBalance&notify=$notify&transactNumString");
?>