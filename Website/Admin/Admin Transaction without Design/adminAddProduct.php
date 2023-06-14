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

    $checkProduct = '';
	$notify = "";
    $productName = $_GET['productName'];
    $sql = "SELECT * FROM qrcodesSupplier
        INNER JOIN qrcodesProduct
        ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId
        WHERE qrSupplierProductName = '$productName' ";
    $validateProduct = $db->prepare($sql);
    $validateProduct->execute();
    for($i=0; $rowProduct = $validateProduct->fetch(); $i++){
        $checkProduct = $rowProduct['qrProductId'];
    }

    if ($checkProduct != '') {
		$notify = "<span style = 'color: Red;'>Sorry! Product Info of " . $productName . " Already Registered!</span>";

		$staffActivity = "Tries to Registered Product Named " . $productName . " on Admin Product Management!";
    }

    else if ($checkProduct == '') {
        $productName = $_GET['productName'];
        $productCost = $_GET['productCost'];
        $productPrice = $_GET['productPrice'];
		$promoDiscount = $_GET['promoDiscount'];
        $category = $_GET['productCategory'];
		$qtyPerBundle = $_GET['productQtyPerBundle'];
		$unitField = $_GET['productUnitField'];
		$storageSection = $_GET['stockStorageSection'];

        $profitPercent = $productPrice - $productCost;

		$checkCategoryCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $category);
		$checkUnitFieldCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $unitField);
		$checkSectionCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $storageSection);

		if ( ($checkCategoryCharSymbols == 0) && ($checkUnitFieldCharSymbols == 0) && ($checkSectionCharSymbols == 0) ) {
			if ($productPrice >= $productCost) {
				$sql = "SELECT * FROM qrcodesSupplier
					WHERE qrSupplierProductName = '$productName' ";
				$result1 = $db->prepare($sql);
				$result1->execute();
				for($i=0; $row1 = $result1->fetch(); $i++){
					$idSupplier = $row1['qrSupplierId'];
					$qtyDelivered = $row1['qrSupplierProductQtyDelivered'];
				}

				$sql = "SELECT DISTINCT(qrStockCriticalLevelSet) FROM qrcodesStock";
				$result2 = $db->prepare($sql);
				$result2->execute();
				for($i=0; $row2 = $result2->fetch(); $i++){
					$criticalLevel = $row2['qrStockCriticalLevelSet'];
				}

				$productCode = strtoupper(substr($category, 0, 3)) . "-" . $idSupplier;

				$sql = "INSERT INTO qrcodesProduct (qrProductId, qrProductCode, qrProductCost, qrProductProfitMarginPercent, qrProductPrice, qrProductPromoDiscount, qrProductCategory, qrImg, qrlink, qrSupplierId)
					VALUES ('$idSupplier', '$productCode', '$productCost','$profitPercent','$productPrice', '$promoDiscount', '$category', '', '', '$idSupplier')";
				$q = $db->prepare($sql);
				$q->execute();

				$sql = "INSERT INTO qrcodesStock (qrStockId, qrStockUnitField, qrStockQtyPerBundle, qrStockStorageSection, qrStockQtyLeft, qrStockCriticalLevelSet, qrProductId)
					VALUES ('$idSupplier', '$unitField', '$qtyPerBundle', '$storageSection', '$qtyDelivered', '$criticalLevel', '$idSupplier')";
				$q = $db->prepare($sql);
				$q->execute();

				$notify = "<span style = 'color: Green;'>Product Named " . $productName . " Successfully Registered!</span>";

				$staffActivity = "Successfully Registered Product Named " . $productName . " on Admin Product Management!";
			}

			else {
				$notify = "<span style = 'color: Red;'>Invalid Set of Pricing! Product Price Must Greater Than or Equal to the Product Cost!</span>";

                $staffActivity = "Tries to Enter the Product Cost that is Greater Than the Product Price on Product Named " . $productName . " But It is Invalid on Admin Product Management!";
			}
		}

		else {
			$notify = "<span style = 'color: Red;'>Invalid Input of Product Info! Please Try Again!</span>";

			$staffActivity = "Tries to Add Product Info Named " . $productName . " on Admin Product Management!";
		}
    }

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../adminProduct.php?adminName=$adminName&notify=$notify");
?>