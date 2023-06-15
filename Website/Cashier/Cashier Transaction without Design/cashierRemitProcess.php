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

    $transactionDate = $_GET['transactionDate'];
	$salesAmount = $_GET['salesAmount'];
	$beginBalance = $_GET['beginBalance'];
	$drawerAmount = $_GET['drawerAmount'];
	$amountRemit = $_GET['amountRemit'];

    $sql = "SELECT * FROM qrcodesCashier
        WHERE qrCashierName = '$cashierName' ";
    $result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$cashierId = $row['qrCashierId'];
	}

	$staffActivity = "Cashier Remit Petty Cash Worth " . $amountRemit . " and Print on Cashier Cash Drawer Page!";
	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../cashdrawer.php?transactNum=$transactNum&cashierName=$cashierName&beginBalance=$beginBalance&drawerAmount=$drawerAmount&amountRemit=$amountRemit&transactionDate=$transactionDate&salesAmount=$salesAmount");
?>