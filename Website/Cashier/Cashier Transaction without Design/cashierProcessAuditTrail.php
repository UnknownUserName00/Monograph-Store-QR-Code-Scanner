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

	$pageOrigin = $_GET['pageOrigin'];
	$linkPage = $_GET['linkPage'];

	$transactNum = $_GET['transactNum'];
	$sql = "SELECT * FROM qrcodesSales
		WHERE qrSalesTransactNum = '$transactNum' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$getTransactNumString = $row['qrSalesTransactString'];
	}

    $sql = "SELECT * FROM qrcodesCashier
        WHERE qrCashierName = '$cashierName' ";
    $result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$cashierId = $row['qrCashierId'];
	}

	if (
		($pageOrigin == "cashierPettycash.php" || $pageOrigin == "cashierPOS.php"
		|| $pageOrigin == "cashierSalesReceipt.php" || $pageOrigin == "cashierReturnItem.php")
	&& $linkPage == "cashierPOS.php") {
		$notify = $_GET['notify'];
        $productItems = $_GET['productItems'];
		$beginBalance = $_GET['beginBalance'];

		$staffActivity = "Opened Cashier Cash POS Page!";

		$movePage = $linkPage . "?transactNum=" . $transactNum . "&cashierName=" . $cashierName . "&beginBalance=" . $beginBalance . "&notify=" . $notify . "&productItems=" . $productItems;
	}

	elseif (
		($pageOrigin == "cashierPettycash.php" || $pageOrigin == "cashierPOS.php"
		|| $pageOrigin == "cashierSalesReceipt.php" || $pageOrigin == "cashierReturnItem.php")
	&& $linkPage == "cashierSalesReceipt.php") {
		$beginBalance = $_GET['beginBalance'];

		$staffActivity = "Opened Cashier Sales Receipt Page!";

		$movePage = $linkPage . "?transactNum=" . $transactNum . "&cashierName=" . $cashierName . "&beginBalance=" . $beginBalance;
	}

	elseif (
		($pageOrigin == "cashierPettycash.php" || $pageOrigin == "cashierPOS.php"
		|| $pageOrigin == "cashierSalesReceipt.php" || $pageOrigin == "cashierReturnItem.php")
	&& $linkPage == "cashierPettycash.php") {
		$beginBalance = $_GET['beginBalance'];

		$staffActivity = "Opened Cashier Cash Drawer Page!";

		$movePage = $linkPage . "?transactNum=" . $transactNum . "&cashierName=" . $cashierName . "&beginBalance=" . $beginBalance;
	}

	elseif (
		($pageOrigin == "cashierPettycash.php" || $pageOrigin == "cashierPOS.php"
		|| $pageOrigin == "cashierSalesReceipt.php" || $pageOrigin == "cashierReturnItem.php")
	&& $linkPage == "cashierReturnItem.php") {
		$beginBalance = $_GET['beginBalance'];
		$transactNumString = $_GET['transactNumString'];
		$notify = $_GET['notify'];

		$staffActivity = "Opened Cashier Return Item Page!";

		$movePage = $linkPage . "?transactNum=" . $transactNum . "&cashierName=" . $cashierName . "&beginBalance=" . $beginBalance . "&transactNumString=" . $transactNumString . "&notify=" . $notify;
	}

	elseif (
		($pageOrigin == "cashierPOS.php" || $pageOrigin == "cashierSalesReceipt.php"
		|| $pageOrigin == "cashierReturnItem.php")
	&& $linkPage == "cashierPrintPurchaseList.php") {
		$staffActivity = "Cashier Prints the Receipt of the Customer from Cashier POS with Transaction Number: " . $getTransactNumString . "!";

		$movePage = $linkPage . "?transactNum=" . $transactNum . "&cashierName=" . $cashierName;
	}

	if ($pageOrigin == "cashierReturnItem.php" && $linkPage == "cashierPrintReturnList.php") {
		$staffActivity = "Cashier Prints Receipt on Cashier Return Item Page with Transaction Number " . $getTransactNumString . "!";

		$movePage = $linkPage . "?transactNum=" . $transactNum . "&cashierName=" . $cashierName;
	}

	$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
		VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
	$resultAudit = $db->prepare($sql);
	$resultAudit->execute();

	header("location: ../" . $movePage);
?>