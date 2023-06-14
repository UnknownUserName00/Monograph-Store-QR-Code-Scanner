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
    $nameUser = $_GET['nameUser'];
	$securityQuestion = $_GET['securityQuestion'];
	$securityAnswer = hash('sha512', $_GET['securityAnswer'], false);
    $newPassUser = hash('sha512', $_GET['newPassUser'], false);

	$checkUserNameCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $nameUser);
	$checkAnswerCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_GET['securityAnswer']);
	$checkNewPassCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_GET['newPassUser']);

	$adminId = "";
    $sql = "SELECT * FROM qrcodesAdmin
        WHERE qrAdminName = '$adminName' ";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
		$adminId = $row['qrAdminId'];
	}

	$findAdminName = "";
	$findAdminPassword = "";
	$findAdminQuestion = "";
	$findAdminAnswer = "";
	$getAdminVerification = "False";
    $sql = "SELECT * FROM qrcodesAdmin
		WHERE qrAdminName != '' ";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
		$checkAdminName = $row['qrAdminName'];

		$verifyAdmin = ($checkAdminName == $nameUser) ? "True" : "False";

		if ($verifyAdmin == "True") {
			$findAdminName = $row['qrAdminName'];
			$findAdminPassword = $row['qrAdminPassword'];
			$findAdminQuestion = $row['qrAdminSecurityQuestion'];
			$findAdminAnswer = $row['qrAdminSecurityAnswer'];

			$getAdminVerification = $verifyAdmin;
		}
	}

	$findCashierName = "";
	$findCashierPassword = "";
	$findCashierQuestion = "";
	$findCashierAnswer = "";
	$getCashierVerification = "False";
    $sql = "SELECT * FROM qrcodesCashier
        WHERE qrCashierName != '' ";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
		$checkCashierName = $row['qrCashierName'];

		$verifyCashier = ($checkCashierName == $nameUser) ? "True" : "False";

		if ($verifyCashier == "True") {
			$findCashierName = $row['qrCashierName'];
			$findCashierPassword = $row['qrCashierPassword'];
			$findCashierQuestion = $row['qrCashierSecurityQuestion'];
			$findCashierAnswer = $row['qrCashierSecurityAnswer'];

			$getCashierVerification = $verifyCashier;
		}
	}

	if ( ($getAdminVerification == "True") && ($checkUserNameCharSymbols == 0) ) {
		if ($findAdminQuestion == $securityQuestion) {
			if ( ($findAdminAnswer == $securityAnswer) && ($checkAnswerCharSymbols == 0) ) {
				if ( ($findAdminPassword != $newPassUser) && ($checkNewPassCharSymbols == 0) ) {
					$sql = "UPDATE qrcodesAdmin
						SET qrAdminPassword = '$newPassUser'
						WHERE qrAdminName = '$nameUser'";
					$result = $db->prepare($sql);
					$result->execute();

					$staffActivity = "Succesfully Change Password Admin: " . $nameUser;

					$notify = "<span style = 'color: Green;'>Admin Named " . $nameUser . " Successfully Changed Password!</span>";
				}

				else {
					$notify = "<span style = 'color: Red;'>Sorry! Your Old Password Must Not the Same as New Password!</span>";

					$staffActivity = "Attempting to Change Password Admin: " . $nameUser;
				}
			}

			else {
				$notify = "<span style = 'color: Red;'>Sorry! Your Answer to Your Security Question is Invalid! Please Try Again!</span>";

				$staffActivity = "Attempting to Answer Security Question to Change Password Admin: " . $nameUser;

				$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
					VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
				$resultAudit = $db->prepare($sql);
				$resultAudit->execute();
			}
		}

		else {
			$notify = "<span style = 'color: Red;'>Sorry! Your Security Question You Chose is Invalid! Please Try Again!</span>";

			$staffActivity = "Attempting to Choose Security Question to Change Password Admin: " . $nameUser;
		}
	}

	elseif ( ($getCashierVerification == "True") && ($checkUserNameCharSymbols == 0) ) {
		if ($findCashierQuestion == $securityQuestion) {
			if ( ($findCashierAnswer == $securityAnswer) && ($checkAnswerCharSymbols == 0) ) {
				if ( ($findCashierPassword != $newPassUser) && ($checkNewPassCharSymbols == 0) ) {
					$sql = "UPDATE qrcodesCashier
						SET qrCashierPassword = '$newPassUser', qrCashierChangePassRequest = 'Granted'
						WHERE qrCashierName = '$nameUser'";
					$result = $db->prepare($sql);
					$result->execute();

					$staffActivity = "Successfully Change Password Cashier: " . $nameUser;

					$notify = "<span style = 'color: Green;'>Cashier Named " . $nameUser . " Succesfully Changed Password!</span>";
				}

				else {
					$notify = "<span style = 'color: Red;'>Sorry! Your Old Password Must Not the Same as New Password!</span>";

					$staffActivity = "Attempting to Change Password Cashier: " . $nameUser;
				}
			}

			else {
				$notify = "<span style = 'color: Red;'>Sorry! Your Answer to Your Security Question is Invalid! Please Try Again!</span>";

				$staffActivity = "Attempting to Answer Security Question to Change Password Cashier: " . $nameUser;
			}
		}

		else {
			$notify = "<span style = 'color: Red;'>Sorry! Your Security Question You Chose is Invalid! Please Try Again!</span>";

			$staffActivity = "Attempting to Choose Security Question to Change Password Cashier: " . $nameUser;
		}
	}

	else {
		$notify = "<span style = 'color: Red;'>Invalid Username!</span>";

		$staffActivity = "Tries to Experiment Usernames on Admin Change Password!";
	}

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../adminUserChangePass.php?adminName=$adminName&notify=$notify");
?>