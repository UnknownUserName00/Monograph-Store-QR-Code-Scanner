<?php
    session_start();

    $adminName = $_POST['adminName'];
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

    $supplierId = $_POST['supplierId'];
    $productBrand = $_POST['productBrand'];
    $productName = $_POST['productName'];
    $productQtyDelivered = $_POST['productQtyDelivered'];
    $productDateExpired = $_POST['productDateExpired'];
    $supplierName = $_POST['supplierName'];
    $supplierAddress = $_POST['supplierAddress'];
    $contactPerson = $_POST['contactPerson'];
    $contactNum = $_POST['contactNum'];
    $dateDelivered = $_POST['dateDelivered'];

    $timeDelivered = $_POST['timeDelivered'];
    $hourTime = substr($timeDelivered, 0, 2) * 1;
    $minuteTime = substr($timeDelivered, 3, 4);

    $hourFormat = [12, "0" . 1, "0" . 2, "0" . 3, "0" . 4, "0" . 5, "0" . 6, "0" . 7, "0" . 8, "0" . 9, 10, 11, 12, "0" . 1, "0" . 2, "0" . 3, "0" . 4, "0" . 5, "0" . 6, "0" . 7, "0" . 8, "0" . 9, 10, 11];
    $modeDayFormat = ($hourTime < 12) ? "AM" : "PM";

    $timeFormat = $hourFormat[$hourTime] . ":" . $minuteTime . " " . $modeDayFormat;

    $searchBrandNameChar = strpos($productBrand, "ñ");
    $searchProductNameChar = strpos($productName, "ñ");
    $searchSupplierNameChar = strpos($supplierName, "ñ");
    $searchSupplierAddressChar = strpos($supplierAddress, "ñ");
    $searchContactPersonChar = strpos($contactPerson, "ñ");

    $checkBrandCharSymbols = preg_match('/[\'^£$%&*}{@#~?><>|=_+¬-]/', $productBrand);
    $checkProductNameCharSymbols = preg_match('/[\'^£$%&*}{@#~?><>|=_+¬-]/', $productName);
    $checkSupplierNameCharSymbols = preg_match('/[\'^£$%&*}{@#~?><>|=_+¬-]/', $supplierName);
    $checkSupplierAddressCharSymbols = preg_match('/[\'^£$%&*()}{~?><>|=_+¬-]/', $supplierAddress);
    $checkContactPersonCharSymbols = preg_match('/[\'^£$%&*}{@#~?><>|=_+¬-]/', $contactPerson);

    $sql = "SELECT * FROM qrcodesAdmin
        WHERE qrAdminName = '$adminName' ";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
        $adminId = $row['qrAdminId'];
    }

    if (
        ($checkBrandCharSymbols == 0) && ($checkProductNameCharSymbols == 0) && ($checkSupplierNameCharSymbols == 0) && ($checkSupplierAddressCharSymbols == 0) && ($checkContactPersonCharSymbols == 0)
        && ($searchBrandNameChar == "") && ($searchProductNameChar == "") && ($searchSupplierNameChar == "") && ($searchSupplierAddressChar == "") && ($searchContactPersonChar == "")
    ) {
        $sql = "UPDATE qrcodesSupplier 
                SET qrSupplierName=?, qrSupplierBrandName=?, qrSupplierProductName=?, qrSupplierProductQtyDelivered=?, qrSupplierProductDateExpired=?, qrSupplierAddress=?, qrSupplierContactNum=?, qrSupplierContactPerson=?, qrSupplierTimeDelivered=?, qrSupplierDateDelivered=?
                WHERE qrSupplierId=?";
        $q = $db->prepare($sql);
        $q->execute(array($supplierName,$productBrand,$productName,$productQtyDelivered,$productDateExpired,$supplierAddress,$contactNum,$contactPerson,$timeFormat,$dateDelivered,$supplierId));

        $notify = "<span style = 'color: Green;'>Product Named " . $productName . " Info Successfully Changed!</span>";

        $staffActivity = "Successfully Edit Product Named " . $productName . " on Admin Supply Management!";
    }

    else {
        $notify = "<span style = 'color: Red;'>Invalid Input of Supplier Info! Please Try Again!</span>";

        $staffActivity = "Tries to Experiment to Edit Some Product on Admin Supply Management!";
    }

    $sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
        VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
    $resultAudit = $db->prepare($sql);
    $resultAudit->execute();

    header("location: ../adminSupply.php?adminName=$adminName&notify=$notify");
?>