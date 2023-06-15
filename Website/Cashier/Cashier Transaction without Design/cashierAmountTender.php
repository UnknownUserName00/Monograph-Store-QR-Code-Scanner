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

	$sql = "SELECT * FROM qrcodesCashier
		WHERE qrCashierName = '$cashierName' ";
	$retrieveCashier = $db->prepare($sql);
	$retrieveCashier->execute();
	for($i=0; $rowCashier = $retrieveCashier->fetch(); $i++){
		$cashierId = $rowCashier['qrCashierId'];
	}

	if ($transactNum != "") {
        $amountTender = $_GET['AmountTender'];

		$sql = "SELECT * FROM qrcodesPurchaseItem
			INNER JOIN qrcodesSales
			ON qrcodesPurchaseItem.qrSalesTransactNum = qrcodesSales.qrSalesTransactNum
			WHERE qrcodesSales.qrSalesTransactNum = '$transactNum' ";
		$resultTransactNum1 = $db->prepare($sql);
		$resultTransactNum1->execute();
		for($i=0; $rowTransact1 = $resultTransactNum1->fetch(); $i++){
			$salesAmount = $rowTransact1['qrSalesAmount'];
		}
        $amountChangeDeploy = $amountTender - $salesAmount;

        $sql = "UPDATE qrcodesSales
            SET qrSalesTenderCash = '$amountTender', qrSalesAmountChange = '$amountChangeDeploy'
            WHERE qrSalesTransactNum = '$transactNum' ";
        $resultTenderCash = $db->prepare($sql);
        $resultTenderCash->execute();

		$staffActivity = "Cashier Receives Amount Tender from the Customer!";
	}

	else {
		$amountTender = number_format(0, 2, '.', '');
        $notify = "<span style = 'color: Red; font-weight: Bold;'><center>Sorry! No Valid Shopping List Format Detected from the Camera!</center></span>";
		$staffActivity = "Cashier Does Not Yet Receive Amount Tender from the Customer!";
	}

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../cashierPOS.php?transactNum=$transactNum&notify=$notify&cashierName=$cashierName&beginBalance=$beginBalance&productItems");
?>