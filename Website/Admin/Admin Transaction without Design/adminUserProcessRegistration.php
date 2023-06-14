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
    $passUser = hash('sha512', $_GET['passUser'], false);
    $firstNameUser = $_GET['firstNameUser'];
    $lastNameUser = $_GET['lastNameUser'];
	$securityQuestion = $_GET['securityQuestion'];
	$securityAnswer = hash('sha512', $_GET['securityAnswer'], false);
	$positionLevelUser = $_GET['positionLevelUser'];

	$checkUserNameCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $nameUser);
	$checkUserPassCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_GET['passUser']);
	$checkFirstNameCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $firstNameUser);
	$checkLastNameCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $lastNameUser);
	$checkAnswerCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_GET['securityAnswer']);

	$findAdminName = "";
	$sql = "SELECT * FROM qrcodesAdmin
		WHERE qrAdminName != '' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$checkAdminName = $row['qrAdminName'];

		$verifyAdmin = ($checkAdminName == $nameUser) ? "True" : "False";

		if ($verifyAdmin == "True") {
			$findAdminName = $row['qrAdminName'];
			$getAdminVerification = $verifyAdmin;
		}
	}

	$findCashierName = "";
	$sql = "SELECT * FROM qrcodesCashier
		WHERE qrCashierName != '' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$checkCashierName = $row['qrCashierName'];

		$verifyCashier = ($checkCashierName == $nameUser) ? "True" : "False";

		if ($verifyCashier == "True") {
			$findCashierName = $row['qrCashierName'];
			$getCashierVerification = $verifyCashier;
		}
	}

	$sql = "SELECT * FROM qrcodesAdmin
		WHERE qrAdminName = '$adminName' ";
	$resultAdminFind = $db->prepare($sql);
	$resultAdminFind->execute();
	for($i=0; $row = $resultAdminFind->fetch(); $i++){
		$adminId = $row['qrAdminId'];
	}

    if ( ($checkUserNameCharSymbols == 0) && ($checkUserPassCharSymbols == 0) && ($checkFirstNameCharSymbols == 0) && ($checkLastNameCharSymbols == 0) && ($checkAnswerCharSymbols == 0) ) {
        if ($positionLevelUser == "Cashier") {
			if ($findCashierName != $nameUser) {
                $sql = "INSERT INTO qrcodesCashier (qrCashierName, qrCashierPassword, qrCashierFirstName, qrCashierLastName, qrCashierSecurityQuestion, qrCashierSecurityAnswer, qrCashierChangePassRequest, qrCashierLogInAttempt)
                    VALUES ('$nameUser', '$passUser', '$firstNameUser', '$lastNameUser', '$securityQuestion', '$securityAnswer', 'Granted', 0)";
                $result = $db->prepare($sql);
                $result->execute();

                $staffActivity = "Successfully Register Cashier: " . $nameUser;

                $notify = "<span style = 'color: Green;'>" . $nameUser . " Succesfully Registered!</span>";
            }

            else {
                $staffActivity = "Attempting to Register to Cashier";

                $notify = "<span style = 'color: Red;'>Cashier Username Already Used!</span>";
            }
        }

        elseif ($positionLevelUser == "Admin") {
            if ($findAdminName != $nameUser) {
                $sql = "INSERT INTO qrcodesAdmin (qrAdminName, qrAdminPassword, qrAdminFirstName, qrAdminLastName, qrAdminSecurityQuestion, qrAdminSecurityAnswer, qrAdminLogInAttempt)
                    VALUES ('$nameUser', '$passUser', '$firstNameUser', '$lastNameUser', '$securityQuestion', '$securityAnswer', 0)";
                $result = $db->prepare($sql);
                $result->execute();

                $staffActivity = "Successfully Register New Admin: " . $nameUser;

                $notify = "<span style = 'color: Green;'>" . $nameUser . " Successfully Registered!</span>";
            }

            else {
                $staffActivity = "Attempting to Register to New Admin";

                $notify = "<span style = 'color: Red;'>Admin Username Already Used!</span>";
            }
        }

        else {
            $staffActivity = "Attempting to Experiment on Admin User Registration";

            $notify = "<span style = 'color: Red;'>Invalid Username!</span>";
        }
    }

    else {
        $staffActivity = "Attempting to Experiment on Admin User Registration";

        $notify = "<span style = 'color: Red;'>Invalid Input!<br/>Please Try Again!</span>";
    }

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../adminUserRegistration.php?adminName=$adminName&notify=$notify");
?>