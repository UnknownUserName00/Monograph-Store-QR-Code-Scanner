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

    $sql = "SELECT * FROM qrcodesAdmin
        WHERE qrAdminName = '$adminName' ";
    $resultName = $db->prepare($sql);
    $resultName->execute();
    for($i=0; $rowName = $resultName->fetch(); $i++){
        $adminId = $rowName['qrAdminId'];
		$adminFullName = $rowName['qrAdminFirstName'] . " " . $rowName['qrAdminMiddleInitial']. " " . $rowName['qrAdminLastName'];
	}

	$userActivity = "Sign Out to Admin";

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
        VALUES ('$adminId', 1, '$userActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

    unset($_SESSION['SESS_LAST_NAME']);

	header('location: ../../staffSignInForm.php');
?>