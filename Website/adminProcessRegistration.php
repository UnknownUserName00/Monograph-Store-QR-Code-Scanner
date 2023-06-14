<?php
	include "ServerConnection/configConnectRecords.php";

	$date = new DateTime();
	$date->add(new DateInterval('PT6H'));
	$fullDateFormat = $date->format('Y-m-d');
	$timeFormat = $date->format('h:i A');

    $rowSearched = "";
	$notify = "";
    if( isset($_POST['qrNameUser']) and isset($_POST['qrPassUser']) and isset($_POST['qrFirstName']) and isset($_POST['qrLastName']) and isset($_POST['qrSecurityQuestion']) and isset($_POST['qrSecurityAnswer']) ) {
        $qrNameUser=$_POST['qrNameUser'];
		$qrPassUser=hash('sha512', $_POST['qrPassUser'], false);
        $qrFirstName=$_POST['qrFirstName'];
        $qrLastName=$_POST['qrLastName'];
		$qrSecurityQuestion = $_POST['qrSecurityQuestion'];
		$qrSecurityAnswer = hash('sha512', $_POST['qrSecurityAnswer'], false);

        $findAdmin = "";
        $sql = "SELECT * FROM qrcodesAdmin
            WHERE qrAdminName = '$qrNameUser' ";
        $resultAdminFind = $db->prepare($sql);
        $resultAdminFind->execute();
        for($i=0; $row = $resultAdminFind->fetch(); $i++){
            $findAdmin = $row['qrAdminName'];
        }

        if ($findAdmin != $qrNameUser) {
            $sql = "INSERT INTO qrcodesAdmin (qrAdminName, qrAdminPassword, qrAdminFirstName, qrAdminLastName, qrAdminSecurityQuestion, qrAdminSecurityAnswer, qrAdminLogInAttempt)
                VALUES ('$qrNameUser', '$qrPassUser', '$qrFirstName', '$qrLastName', '$qrSecurityQuestion', '$qrSecurityAnswer', 0)";
            $resultAdminRegister = $db->prepare($sql);
            $resultAdminRegister->execute();

			$sql = "SELECT * FROM qrcodesAdmin
				WHERE qrAdminName = '$qrNameUser' ";
			$resultAdminFind = $db->prepare($sql);
			$resultAdminFind->execute();
			for($i=0; $row = $resultAdminFind->fetch(); $i++){
				$adminId = $row['qrAdminId'];
			}

            $staffActivity = "Succesfully Register New Admin: " . $qrNameUser;

            $sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
                VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
            $resultAudit = $db->prepare($sql);
            $resultAudit->execute();

            $notify = "<center style = 'color: Green; font-weight: Bold; font-size: 14; white-space: pre;'>Admin Register Successfully!</center>";
        }

        else {
            $notify = "<center style = 'color: Red; font-weight: Bold; font-size: 14; white-space: pre;'>Admin Username Already Used!</center>";
        }
	}

	header("location: adminRegister.php?notify=$notify");
?>
