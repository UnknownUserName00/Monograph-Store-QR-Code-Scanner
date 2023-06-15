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

    $sql = "SELECT * FROM qrcodesCashier
       WHERE qrCashierName = '$cashierName' ";
    $resultName = $db->prepare($sql);
    $resultName->execute();
    for($i=0; $rowName = $resultName->fetch(); $i++){
		$cashierId = $rowName['qrCashierId'];
	}

	$userActivity = "Sign Out to Cashier";

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
        VALUES (1, '$cashierId', '$userActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

    unset($_SESSION['SESS_LAST_NAME']);
	unset($_SESSION['beginBalance']);

	header('location: ../../staffSignInForm.php');
?>