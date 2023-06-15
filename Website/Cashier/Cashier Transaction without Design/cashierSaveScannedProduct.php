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

	$productItems = $_GET['productItems'];
    $stringLength = strlen($productItems);
    $checkFirstString = substr($productItems, 0, 1);
    $checkLastString = substr($productItems, ($stringLength - 1), 1);
    $beginBalance = $_GET['beginBalance'];

	$sql = "SELECT * FROM qrcodesCashier
		WHERE qrCashierName = '$cashierName' ";
	$retrieveCashier = $db->prepare($sql);
	$retrieveCashier->execute();
	for($i=0; $rowCashier = $retrieveCashier->fetch(); $i++){
		$cashierId = $rowCashier['qrCashierId'];
	}

	// Convert JSON String to Array
	$transactNumArray = json_decode($productItems, true);

	// Convert JSON String to Object
	$transactNumObject = json_decode($productItems);

    json_last_error();

    $notify = "";
    if ( (json_last_error() == 0) && ($productItems != ($productItems >= 0 && $productItems < 0)) && ($checkFirstString == "[") && ($checkLastString == "]") ) {
        $salesIdStart = 1;
        $sql = "SELECT MAX(qrSalesTransactNum) FROM qrcodesSales";
        $result = $db->prepare($sql);
        $result->execute();
        for($i=0; $row = $result->fetch(); $i++){
            $salesId = $row['MAX(qrSalesTransactNum)'];
        }

        if ($salesId == "") {
            $salesId = 0;
        }

        $salesIdAlter = $salesIdStart + $salesId;
        $tax = .12;

        $transactNumString = "TN-" . $salesIdAlter . "-" . $fullDateFormat;

        $invoiceNum = $fullDateFormat . "-" . $salesIdAlter;

        $sql = "SELECT * FROM qrcodesBalance
			WHERE qrBalanceBegin = '$beginBalance' AND qrBalanceDate = '$fullDateFormat' ";
        $result = $db->prepare($sql);
        $result->execute();
        for($i=0; $row = $result->fetch(); $i++){
            $balanceId = $row['qrBalanceId'];
        }

        $sql = "INSERT INTO qrcodesSales (qrSalesTransactString, qrSalesInvoiceNum, qrSalesDate, qrSalesAmount, qrSalesTenderCash, qrSalesAmountChange, qrSalesTaxPercentage, qrSalesTaxAmount, qrCashierId, qrBalanceId)
            VALUES ('$transactNumString', '$invoiceNum', '$fullDateFormat', 0, 0, 0, '$tax', 0, '$cashierId', '$balanceId')";
        $resultSales = $db->prepare($sql);
        $resultSales->execute();

        $sql = "SELECT MAX(qrSalesTransactNum) FROM qrcodesSales";
        $result = $db->prepare($sql);
        $result->execute();
        for($i=0; $row = $result->fetch(); $i++){
            $salesId = $row['MAX(qrSalesTransactNum)'];
        }

        $salesIdAlter = $salesId;

        $transactNumCount = count($transactNumArray);

        $salesAmount = 0;
        $notifyCheckStock = "";
        $notifyCheckName = "";
        $addProductNotify = "";
        $addNameNotify = "";
        for ($j = 0; $j < $transactNumCount; $j++) {
            $searchNameArray = array_key_exists('name', $transactNumObject[$j]) ? "True" : "False";
            $searchNameQuantity = array_key_exists('quantity', $transactNumObject[$j]) ? "True" : "False";
            if ( $searchNameArray == "True" && $searchNameQuantity == "True" ) {
                $name = $transactNumObject[$j]->name;
                $quantity = $transactNumObject[$j]->quantity;

				$checkNameCharSymbols = preg_match('/[\'^£$%&*}{@#~?><>|=_+¬-]/', $name);
				$checkQuantityCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $quantity);

				if ( ($checkNameCharSymbols == 0) && ($checkQuantityCharSymbols == 0) && ($quantity == number_format($quantity)) ) {
					$checkProductName = "";
					$sql = "SELECT * FROM qrcodesSales
						INNER JOIN qrcodesPurchaseItem
						ON qrcodesSales.qrSalesTransactNum = qrcodesPurchaseItem.qrSalesTransactNum
						INNER JOIN qrcodesStock
						ON qrcodesPurchaseItem.qrStockId = qrcodesStock.qrStockId
						INNER JOIN qrcodesProduct
						ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
						INNER JOIN qrcodesSupplier
						ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrSupplierId
						WHERE qrcodesSupplier.qrSupplierProductName = '$name' AND qrcodesSales.qrSalesTransactNum = '$salesIdAlter' ";
					$result = $db->prepare($sql);
					$result->execute();
					for($i=0; $row = $result->fetch(); $i++){
						$checkProductName = $row['qrSupplierProductName'];
					}

					$stockId = "";
					$sql = "SELECT * FROM qrcodesStock
						INNER JOIN qrcodesProduct
						ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
						INNER JOIN qrcodesSupplier
						ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId
						WHERE qrcodesSupplier.qrSupplierProductName = '$name' ";
					$retrieveProduct = $db->prepare($sql);
					$retrieveProduct->execute();
					for($i=0; $rowProduct = $retrieveProduct->fetch(); $i++){
						$stockId = $rowProduct['qrStockId'];
						$qtyLeft = $rowProduct['qrStockQtyLeft'];
					}

					$sql = "SELECT * FROM qrcodesStock
						INNER JOIN qrcodesProduct
						ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
						INNER JOIN qrcodesSupplier
						ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId";
					$retrieveProduct = $db->prepare($sql);
					$retrieveProduct->execute();
					for($i=0; $rowProduct = $retrieveProduct->fetch(); $i++){
						$supplierId = $rowProduct['qrSupplierId'];
					}

					$totalStock = $qtyLeft - $quantity;

					if ( ($supplierId == "") || ($stockId == "") ) {
						$notifyCheckName = "<strong>Sorry! No Any Product(s) for " . $name . " has Yet Been Registered on the System!</strong>";
					}

					else if ($totalStock <= 0) {
						$addProductNotify = $addProductNotify . "<br/>" . $name . ",";
						$notifyCheckStock = "<strong>The Stocks from the Store for" . $addProductNotify . "<br/>is in Insufficient Level to Attain Your Order!<br/>Please Choose Other Alternative Products!</strong>";
					}

					elseif ($checkProductName == $name) {
						$addNameNotify = $addNameNotify . "<br/>" . $name . ",";
						$notifyCheckName = "<strong>" . $addNameNotify . "<br/> is Already Registered to Your Shopping List!</strong>";
					}

					elseif ( ($totalStock > 0) && ($checkProductName != $name) && ($supplierId != "")) {
						$sql = "INSERT INTO qrcodesPurchaseItem (qrPurchaseItemQtyAvail, qrStockId, qrSalesTransactNum)
							VALUES ('$quantity', '$stockId', '$salesIdAlter')";
						$saveItems = $db->prepare($sql);
						$saveItems->execute();

						$subTotal = 0;
						$sql = "SELECT * FROM qrcodesPurchaseItem
							INNER JOIN qrcodesStock
							ON qrcodesPurchaseItem.qrStockId = qrcodesStock.qrStockId
							INNER JOIN qrcodesProduct
							ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
							WHERE qrcodesPurchaseItem.qrSalesTransactNum = '$salesIdAlter' ";
						$retrieveItems = $db->prepare($sql);
						$retrieveItems->execute();
						for($i=0; $rowItems = $retrieveItems->fetch(); $i++){
							$productQtyAvail = $rowItems['qrPurchaseItemQtyAvail'];
							$productPrice = $rowItems['qrProductPrice'];

							$subTotal = ($productQtyAvail * $productPrice) + $subTotal;
						}

						$salesTaxAmount = $subTotal * $tax;
						$salesAmount = $salesTaxAmount + $subTotal;

						$sql = "UPDATE qrcodesStock 
							SET qrStockQtyLeft=qrStockQtyLeft-?
							WHERE qrStockId=?";
						$q = $db->prepare($sql);
						$q->execute(array($productQtyAvail,$stockId));

						$sql = "UPDATE qrcodesSales
							SET qrSalesAmount = '$salesAmount', qrSalesTaxAmount = '$salesTaxAmount'
							WHERE qrSalesTransactNum = '$salesIdAlter' ";
						$s = $db->prepare($sql);
						$s->execute();
					}

					if ($notifyCheckName == "") {
						$notify = $notifyCheckStock;
					}

					else if ($notifyCheckStock == "") {
						$notify = $notifyCheckName;
					}

					else {
						$notify = $notifyCheckStock . "<br/>" . $notifyCheckName;
					}
				}

				else {
					$notify = "<strong>Sorry! You Scanned a Wrong Shopping List Format!</strong>";
				}
            }

            else {
                $notify = "<strong>Sorry! You Scanned a Wrong Shopping List Format!</strong>";
            }
        }

		$staffActivity = "Cashier Scanned the Customer Shopping List on Android Phone!";
    }

    else {
        $notify = "<strong>Sorry! No Valid Shopping List Format Detected from the Camera!</strong>";

		$staffActivity = "Cashier Tries to Experiment Scanning the QR Code!";
    }

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../cashierPOS.php?transactNum=$salesIdAlter&notify=$notify&cashierName=$cashierName&beginBalance=$beginBalance&productItems");
?>