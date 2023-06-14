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

    $productId = $_POST['productId'];
    $productCost = $_POST['productCost'];
    $productPrice = $_POST['productPrice'];
    $category = $_POST['productCategory'];

    $productName  = $_POST['productName'];

    $productCode = strtoupper(substr($category, 0, 3)) . "-" . $productId;
    $profitMargin = $productPrice - $productCost;

    $checkCategoryCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $category);

    $sql = "SELECT * FROM qrcodesAdmin
        WHERE qrAdminName = '$adminName' ";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
        $adminId = $row['qrAdminId'];
    }

    if ($checkCategoryCharSymbols == 0) {
        if ($productPrice >= $productCost) {
            $sql = "UPDATE qrcodesProduct
                SET qrProductCode=?, qrProductCost=?, qrProductProfitMarginPercent=?,
                qrProductPrice=?, qrProductCategory=?
                WHERE qrProductId=?";
            $r = $db->prepare($sql);
            $r->execute(array($productCode,$productCost,$profitMargin,$productPrice,$category,$productId));

            $notify = "<span style = 'color: Green;'>Product Named " . $productName . " Info Successfully Changed!</span>";

            $staffActivity = "Successfully Edit Product Named " . $productName . " on Admin Product Management!";
        }

        else {
            $notify = "<span style = 'color: Red;'>Invalid Set of Pricing! Product Price Must Greater Than or Equal to the Product Cost!</span>";

            $staffActivity = "Tries to Enter the Product Cost that is Greater Than the Product Price on Product Named " . $productName . " But It is Invalid on Admin Product Management!";
        }
    }

    else {
        $notify = "<span style = 'color: Red;'>Invalid Input of Product Category! Please Try Again!</span>";

        $staffActivity = "Tries to Edit Product Named " . $productName . " on Admin Product Management!";
    }

    $sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
        VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
    $resultAudit = $db->prepare($sql);
    $resultAudit->execute();

    header("location: ../adminProduct.php?adminName=$adminName&notify=$notify");
?>