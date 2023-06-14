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
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$adminId = $row['qrAdminId'];
	}

    $checkSupplier = '';
	$notify = "";
	$getSupplierVerification = "False";
    $supplierProductName = $_GET['productName'];
    $sql = "SELECT * FROM qrcodesSupplier";
    $validateSupplier = $db->prepare($sql);
    $validateSupplier->execute();
    for($i=0; $rowSupplier = $validateSupplier->fetch(); $i++){
        $getSupplier = $rowSupplier['qrSupplierProductName'];

		$verifySupplier = ($checkSupplier == $supplierProductName) ? "True" : "False";

		if ($verifySupplier == "True") {
			$checkSupplier = $rowSupplier['qrSupplierProductName'];

			$getSupplierVerification = $verifySupplier;
		}
    }

    if ($getSupplierVerification == "True") {
		$notify = "<span style = 'color: Red;'>Supplier " . $supplierProductName . " Already Registered!</span>";

		$staffActivity = "Tries to Registered Product Named " . $productName . " on Admin Supply Management!";
    }

    elseif ($getSupplierVerification == "False") {
        $productBrand = $_GET['productBrand'];
        $productName = $_GET['productName'];
        $productQtyDelivered = $_GET['productQtyDelivered'];
        $productDateExpired = $_GET['productDateExpired'];
        $supplierName = $_GET['supplierName'];
		$supplierAddress = $_GET['supplierAddress'];
        $contactPerson = $_GET['contactPerson'];
        $contactNum = $_GET['contactNumber'];
        $dateDelivered = $_GET['dateDelivered'];

        $timeDelivered = $_GET['timeDelivered'];
        $hourTime = substr($timeDelivered, 0, 2) * 1;
        $minuteTime = substr($timeDelivered, 3, 4);

        $hourFormat = [12, "0" . 1, "0" . 2, "0" . 3, "0" . 4, "0" . 5, "0" . 6, "0" . 7, "0" . 8, "0" . 9, 10, 11, 12, "0" . 1, "0" . 2, "0" . 3, "0" . 4, "0" . 5, "0" . 6, "0" . 7, "0" . 8, "0" . 9, 10, 11];
        $modeDayFormat = ($hourTime < 12) ? "AM" : "PM";
		$fixErrorTime = $hourFormat[$hourTime];

        $timeFormat = $fixErrorTime . ":" . $minuteTime . " " . $modeDayFormat;

		$searchProductNameChar = strpos($productName, "ñ");
		$searchBrandChar = strpos($productBrand, "ñ");
		$searchSupplierNameChar = strpos($supplierName, "ñ");
		$searchSupplierAddressChar = strpos($supplierAddress, "ñ");
		$searchContactPersonChar = strpos($contactPerson, "ñ");

		$checkProductNameCharSymbols = preg_match('/[\'^£$%&*}{@#~?><>|=_+¬-]/', $productName);
		$checkBrandCharSymbols = preg_match('/[\'^£$%&*}{@#~?><>|=_+¬-]/', $productBrand);
		$checkSupplierNameCharSymbols = preg_match('/[\'^£$%&*}{@#~?><>|=_+¬-]/', $supplierName);
		$checkSupplierAddressCharSymbols = preg_match('/[\'^£$%&*()}{~?><>|=_+¬-]/', $supplierAddress);
		$checkContactPersonCharSymbols = preg_match('/[\'^£$%&*}{@#~?><>|=_+¬-]/', $contactPerson);

		if (
			($checkProductNameCharSymbols == 0) && ($checkBrandCharSymbols == 0) && ($checkSupplierNameCharSymbols == 0) && ($checkSupplierAddressCharSymbols == 0) && ($checkContactPersonCharSymbols == 0)
			&& ($searchProductNameChar == "") && ($searchBrandChar == "") && ($searchSupplierNameChar == "") && ($searchSupplierAddressChar == "") && ($searchContactPersonChar == "")
		) {
			$sql = "INSERT INTO qrcodesSupplier (qrSupplierName,qrSupplierBrandName,qrSupplierProductName,qrSupplierProductQtyDelivered,qrSupplierProductDateExpired,qrSupplierAddress,qrSupplierContactNum,qrSupplierContactPerson,qrSupplierTimeDelivered,qrSupplierDateDelivered)
				VALUES ('$supplierName', '$productBrand', '$productName', '$productQtyDelivered', '$productDateExpired', '$supplierAddress', '$contactNum', '$contactPerson', '$timeFormat', '$dateDelivered')";
			$q = $db->prepare($sql);
			$q->execute();

			$notify = "<span style = 'color: Green;'>Product Named " . $productName . " Successfully Registered!</span>";

			$staffActivity = "Successfully Registered Product Named " . $productName . " on Admin Supply Management!";
		}

		else {
			$notify = "<span style = 'color: Red;'>Invalid Input of Supplier Info! Please Try Again!</span>";

			$staffActivity = "Tries to Experiment on Admin Supply Management!";
		}
    }

	else {
		$notify = "<span style = 'color: Red;'>Invalid Product Name! Please Try Again!</span>";

		$staffActivity = "Tries to Experiment on Admin Supply Management!";
	}

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../adminSupply.php?adminName=$adminName&notify=$notify");
?>