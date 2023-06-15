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
	$beginBalance = $_GET['beginBalance'];
	$notify = "";

	$sql = "SELECT * FROM qrcodesCashier
		WHERE qrCashierName = '$cashierName' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$cashierId = $row['qrCashierId'];
	}

	$transactNumString = $_GET['transactNumString'];

	$getVerification = "";
	$sql = "SELECT * FROM qrcodesSales";
	$resultTransact = $db->prepare($sql);
	$resultTransact->execute();
	for($i=0; $rowTransact = $resultTransact->fetch(); $i++){
		$checkTransactString = $rowTransact['qrSalesTransactString'];
		$verify = ($checkTransactString == $transactNumString) ? "True" : "False";
		if ($verify == "True") {
				$getVerification = $verify;
		}
	}

	if ($getVerification == "True") {
		$findTransactString = "";
		$sql = "SELECT * FROM qrcodesSales
			WHERE qrSalesTransactString = '$transactNumString' ";
		$resultTransact = $db->prepare($sql);
		$resultTransact->execute();
		for($i=0; $rowTransact = $resultTransact->fetch(); $i++){
			$findTransactNum = $rowTransact['qrSalesTransactNum'];
			$findTransactString = $rowTransact['qrSalesTransactString'];
		}

		if ($findTransactString != "") {
			$transactNumString = $findTransactString;
			$transactNum = $findTransactNum;
			$notify = "";
		}

		else {
			$transactNumString = "";
			$transactNum = "";
			$notify = "<center style = 'color: Red;'><strong>Transaction Number Not Found!</strong></center>";
		}

		$staffActivity = "Cashier Placed Transaction Number " . $transactNumString . " on Cashier Return Item Page!";

		$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
			VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
		$resultAudit = $db->prepare($sql);
		$resultAudit->execute();
	}

	else {
		$transactNumString = "";
		$transactNum = "";
		$notify = "<center style = 'color: Red;'><strong>Transaction Number Not Found!</strong></center>";
	}

	header("location: ../cashierReturnItem.php?transactNum=$transactNum&transactNumString=$transactNumString&beginBalance=$beginBalance&cashierName=$cashierName&notify=$notify");
?>