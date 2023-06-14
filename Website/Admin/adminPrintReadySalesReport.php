<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Favicon -->
		<link rel="shortcut icon" href="../icon.png">

		<!-- Bootstrap CSS -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		
		<!-- Font Awesome CSS -->
		<link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		
		<!-- Custom CSS -->
		<link href="assets/css/style.css" rel="stylesheet" type="text/css" />		
		
		<!-- BEGIN CSS for this page -->

		<!-- END CSS for this page -->
<style>
// for Default Size of Paper in Printing Mode
@media print and (orientation: portrait) {
	@page {
		size: 216mm 280mm;
		page-break-inside: avoid;
		page-break-before: avoid;
		page-break-after: avoid;
	}
}

@media print and (orientation: landscape) {
	@page {
		size: 216mm 280mm;
	}
}

td.details-control {
	background: url('assets/plugins/datatables/img/details_open.png') no-repeat center center;
	cursor: pointer;
}
tr.shown td.details-control {
	background: url('assets/plugins/datatables/img/details_close.png') no-repeat center center;
}
.content-area {
    margin-top: 20px;
    padding: 20px;
    border-color: #149077;
    margin-bottom: 22px;
    background-color: #fff;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.col-md-12 {
    position: relative;
    width: 100%;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
@media (min-width: 768px)
.col-md-12 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
}
.page-title {
    color: #17a2b8;
    font-size: 25px;
    font-weight: bold;
}
hr {
    margin-top: 1rem;
    margin-bottom: 1rem;
    border: 0;
    border-top: 1px solid rgba(0,0,0,.1);
}
hr {
    box-sizing: content-box;
    height: 0;
    overflow: visible;
}
.mb-3 {
	margin-bottom: 1rem!important;
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
}
</style>
<title>Admin Dashboard</title>
</head>

<body class="adminbody" onLoad="self.print()">
<?php
	session_start();

    $adminName = $_GET['adminName'];
    if ($_SESSION['SESS_FIRST_NAME'] != $adminName) {
		header("location: ../staffSignInForm.php");
		exit();
    }

	include "../ServerConnection/configConnectRecords.php";

    $date = new DateTime();
    $fullDateFormat = $date->format('Y-m-d');

    $getDateFrom = $_GET['getDateFrom'];
    $getDateTo = $_GET['getDateTo'];

	$monthFrom = (substr($getDateFrom, 5, 2) * 1);
	$dayFrom = substr($getDateFrom, 8, 2);
	$yearFrom = substr($getDateFrom, 0, 4);
	$convertedMonthFrom = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	$dateFormatFrom = $convertedMonthFrom[$monthFrom] . " " . $dayFrom . ", " . $yearFrom;

	$monthTo = (substr($getDateTo, 5, 2) * 1);
	$dayTo = substr($getDateTo, 8, 2);
	$yearTo = substr($getDateTo, 0, 4);
	$convertedMonthTo = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	$dateFormatTo = $convertedMonthTo[$monthTo] . " " . $dayTo . ", " . $yearTo;
?>
<div id="main">
    <div>
	
		<!-- Start content -->
        <div class="content">
			<div class="container-fluid">
						<div class="mdl-color--white content-area ng-scope" ng-controller="SalesInvoiceCtrl">
							<div class="row">
								<div class="col-md-12">
									<div class="page-title"><i class="fa fa-table"></i> Sales Report</div>
								</div>
							</div>
							<div class="card-body">
                                <table class="table table-condensed table-hover display" id="example1">
                                        <thead>
                                                    <tr>
                                                        <td colspan = "5">
                                                            <center>
                                                                <div>
                                                                    <h2>Mon.O.Graph</h2>
                                                                </div><!--End Info-->
                                                            </center><!--End InvoiceTop-->
    
                                                            <div>
                                                                <div>
                                                                    <center><p>
                                                                        Transport Hub Lancaster New City</br>
                                                                    </p></center>
                                                                </div>
                                                            </div><!--End Invoice Mid-->
                                                        </td>
                                                    </tr>
                                        </thead>
										<thead>
                                                    <tr align = "center">
                                                        <th colspan = "5"><?php echo "From " . $dateFormatFrom . " To " . $dateFormatTo; ?></th>
                                                    </tr>
										</thead>
										<thead style="background-color:#17a2b8;color:white;">
                                                    <tr align = "center">
                                                        <th scope="col"> Month </th>
                                                        <th scope="col"> Profit </th>
                                                        <th scope="col"> Product Return </th>
                                                        <th scope="col"> UPrice </th>
                                                        <th scope="col"> Total Sales </th>
                                                    </tr>
										</thead>
										<tbody>
                                    <?php
										$supplierId = "";
                                        $sql = "SELECT * FROM qrcodesSupplier";
                                        $result = $db->prepare($sql);
                                        $result->execute();
                                        for($i=0; $row = $result->fetch(); $i++){
											$supplierId = $row['qrSupplierId'];
										}

										if ($supplierId != "") {
												$transactPreviousValues = "";
                                                $sql = "SELECT *, SUM(qrSalesAmount), SUM(qrBalanceBegin) FROM qrcodesSales
													INNER JOIN qrcodesBalance
													ON qrcodesSales.qrSalesTransactNum = qrcodesBalance.qrBalanceId
                                                    WHERE qrcodesSales.qrSalesDate BETWEEN '$getDateFrom' AND '$getDateTo' AND qrcodesSales.qrSalesTenderCash != 0 AND qrcodesSales.qrSalesAmountChange != -qrcodesSales.qrSalesAmountChange
													GROUP BY YEAR(qrcodesSales.qrSalesDate), MONTH(qrcodesSales.qrSalesDate), DATE(qrcodesSales.qrSalesDate)
                                                    ORDER BY qrcodesSales.qrSalesTransactNum ASC";
                                                $resultSummary = $db->prepare($sql);
                                                $resultSummary ->execute();
                                                for($i=0; $rowSummary  = $resultSummary ->fetch(); $i++){
                                                    $getDate = $rowSummary['qrSalesDate'];
                                                    $getDateFormat = substr($getDate, 8, 2);
                                                    $year = substr($getDate, 0, 4);
                                                    $month = (substr($getDate, 5, 2)) * 1;
                                                    $monthArray = [0, "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];    

                                                    $salesAmount = $rowSummary['SUM(qrSalesAmount)'];
                                                    $monthFormat = $monthArray[$month] . " " . $getDateFormat . ", " . $year;
													$beginBalance = $rowSummary['SUM(qrBalanceBegin)'];

														$productReturnQty = 0;
                                                        $sql = "SELECT *, SUM(qrSalesAmount), SUM(qrReturnItemQtyAvail), SUM(qrSupplierProductQtyDelivered), SUM(qrProductPrice) FROM qrcodesSales
                                                            INNER JOIN qrcodesReturnItem
                                                            ON qrcodesSales.qrSalesTransactNum = qrcodesReturnItem.qrSalesTransactNum
                                                            INNER JOIN qrcodesStock
                                                            ON qrcodesReturnItem.qrStockId = qrcodesStock.qrStockId
                                                            INNER JOIN qrcodesProduct
                                                            ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
                                                            INNER JOIN qrcodesSupplier
                                                            ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId
                                                            WHERE qrcodesSales.qrSalesDate = '$getDate' AND qrcodesSales.qrSalesTenderCash != 0 AND qrcodesSales.qrSalesAmountChange != -qrcodesSales.qrSalesAmountChange";
                                                        $resultSummary1 = $db->prepare($sql);
                                                        $resultSummary1 ->execute();
                                                        for($k=0; $rowSummary1  = $resultSummary1 ->fetch(); $k++){
                                                            $productReturnQty = $rowSummary1['SUM(qrReturnItemQtyAvail)'];
                                                            $productDelivered = $rowSummary1['SUM(qrSupplierProductQtyDelivered)'];
                                                            $productPrice = $rowSummary1['SUM(qrProductPrice)'];

                                                            $productRatio = $productReturnQty . "/" . $productDelivered;
                                                        }

														if ($productReturnQty == "") {
															$productReturnQty = 0;
															$productDelivered = 0;
															$productPrice = 0;
															$productRatio = "0/0";
														}

                                                        $totalSales = $salesAmount;

														$subTotalConvert = 0;
                                                        $sql = "SELECT * FROM qrcodesSales
                                                            INNER JOIN qrcodesPurchaseItem
                                                            ON qrcodesSales.qrSalesTransactNum = qrcodesPurchaseItem.qrSalesTransactNum
                                                            INNER JOIN qrcodesStock
                                                            ON qrcodesPurchaseItem.qrStockId = qrcodesStock.qrStockId
                                                            INNER JOIN qrcodesProduct
                                                            ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
                                                            INNER JOIN qrcodesSupplier
                                                            ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId
                                                            WHERE qrcodesSales.qrSalesDate = '$getDate' AND qrcodesSales.qrSalesTenderCash != 0 AND qrcodesSales.qrSalesAmountChange != -qrcodesSales.qrSalesAmountChange";
                                                        $resultSummary1 = $db->prepare($sql);
                                                        $resultSummary1 ->execute();
                                                        for($k=0; $rowSummary1  = $resultSummary1 ->fetch(); $k++){
															$transactNumString = $rowSummary1['qrSalesTransactString'];
                                                            $productPurchaseQty1 = $rowSummary1['qrPurchaseItemQtyAvail'];
															$productPrice1 = $rowSummary1['qrProductPrice'];
															$productCost1 = $rowSummary1['qrProductCost'];
															$convertedCost1 = ($productPrice1 - $productCost1) * $productPurchaseQty1;

															if ($transactPreviousValues != $convertedCost1) {
																$subTotalConvert = $convertedCost1 + $subTotalConvert;
															}

															else {
																$subTotalConvert = $subTotalConvert;
															}

															$transactPreviousValues = $convertedCost1;
                                                        }

														$profit = $subTotalConvert;
                                            ?>
                                                    <tr align = "center">
                                                        <td><?php echo $monthFormat; ?></td>
                                                        <td>Php <?php echo number_format($profit, 2, '.', ''); ?></td>
                                                        <td><?php echo $productRatio; ?></td>
                                                        <td>Php <?php echo number_format($productPrice, 2, '.', ''); ?></td>
                                                        <td>Php <?php echo number_format($totalSales, 2, '.', ''); ?></td>
                                            <?php
                                                }
										}
                                            ?>
                                                    </tr>
										</tbody>
								</table><br/><br/>

                                                <table style = "width: 100%;">
                                                    <tr align = "center">
                                                        <td><hr style = "border-style: solid; border-color: black; width: 100%;"/>&nbsp;&nbsp;&nbsp;&nbsp;Date and Signature Over Printed Name&nbsp;&nbsp;&nbsp;&nbsp;<br/>
                                                            (Prepared by)</td>
                                                        <td></td>
                                                    </tr>
                                                    <tr align = "center">
                                                        <td></td>
                                                        <td><hr style = "border-style: solid; border-color: black; width: 100%;"/>&nbsp;&nbsp;&nbsp;&nbsp;Date and Signature Over Printed Name&nbsp;&nbsp;&nbsp;&nbsp;<br/>
                                                            (Given to)</td>
                                                    </tr>
                                                </table>
							</div>
											<div class="clearfix"></div>
						</div>
						<!-- end row -->

            </div>
			<!-- END container-fluid -->

		</div>
		<!-- END content -->

    </div>
	<!-- END content-page -->
    
	<footer class="footer">

	</footer>

</div>
<!-- END main -->

<script src="assets/js/modernizr.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/moment.min.js"></script>

<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>

<!-- App js -->
<script src="assets/js/pikeadmin.js"></script>

<!-- BEGIN Java Script for this page -->

<!-- END Java Script for this page -->

</body>
</html>