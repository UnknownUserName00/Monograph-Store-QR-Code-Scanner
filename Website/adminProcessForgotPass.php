<?php
	session_start();

	if(!isset($_SESSION['SESS_FIRST_NAME']) || (trim($_SESSION['SESS_FIRST_NAME']) == '')) {
		header("location: staffSignInForm.php");
		exit();
	}

	include "ServerConnection/configConnectRecords.php";

    $date = new DateTime();
    $date->add(new DateInterval('PT6H'));
    $fullDateFormat = $date->format('Y-m-d');
    $timeFormat = $date->format('h:i A');

	$adminName = $_POST['adminName'];
	$sql = "SELECT * FROM qrcodesAdmin";
    $retrieve = $db->prepare($sql);
    $retrieve->execute();
    for($i=0; $row = $retrieve->fetch(); $i++){
		$adminId = $row['qrAdminId'];
	}

	$qrSecurityAnswer=hash('sha512', $_POST['qrSecurityAnswer'], false);
    $qrNewPassUser=hash('sha512', $_POST['qrNewPassUser'], false);

	$checkAnswerCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['qrSecurityAnswer']);
	$checkNewPassCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['qrNewPassUser']);

	$notify = "";

	$findAdminName = "";
	$findAdminPass = "";
	$findAdminSecurityAnswer = "";
	$getAdminVerification = "False";
	$sql = "SELECT * FROM qrcodesAdmin
		WHERE qrAdminName != '' ";
    $retrieve = $db->prepare($sql);
    $retrieve->execute();
    for($i=0; $row = $retrieve->fetch(); $i++){
        $checkAdminName = $row['qrAdminName'];
		$checkAdminPass = $row['qrAdminPassword'];
		$checkAdminSecurityAnswer = $row['qrAdminSecurityAnswer'];

		$verifyAdmin = ($checkAdminName == $adminName) ? "True" : "False";

		if ($verifyAdmin == "True") {
			$findAdminName = $row['qrAdminName'];
			$findAdminPass = $row['qrAdminPassword'];
			$findAdminSecurityAnswer = $row['qrAdminSecurityAnswer'];

			$getAdminVerification = $verifyAdmin;
		}
    }

	if ( ($getAdminVerification != "False") && ($checkNewPassCharSymbols == 0) ) {
		if ( ($findAdminSecurityAnswer == $qrSecurityAnswer) && ($checkAnswerCharSymbols == 0) ) {
			if ($findAdminPass != $qrNewPassUser) {
				$sql = "UPDATE qrcodesAdmin
					SET qrAdminPassword = '$qrNewPassUser'
					WHERE qrAdminName = '$adminName'";
				$updateAdmin = $db->prepare($sql);
				$updateAdmin->execute();

				$staffActivity = "Admin Succesfully Change His/Her Own Password!";

				$linkPage = "staffSignInForm.php";
			}

			else {
				$notify = "Sorry! Your Old Password Must Not the Same as New Password!";

				$staffActivity = "Admin Tries to Change His/Her Own Password!";

				$linkPage = "adminForgotPass.php?adminName=$adminName&notify=$notify";
			}
		}

		else {
			$notify = "Invalid Answer to Security Question!";

			$staffActivity = "Admin Tries to Change His/Her Own Password!";

			$linkPage = "adminForgotPass.php?adminName=$adminName&notify=$notify";
		}
	}

	else {
		$notify = "Invalid Password!";

		$staffActivity = "Admin Tries to Experiment Forgot Password!";
	}

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: " . $linkPage);
?>