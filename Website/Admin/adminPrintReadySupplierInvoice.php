<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Favicon -->
		<link rel="shortcut icon" href="../icon.png">

		<!-- Switchery css -->
		<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
		
		<!-- Bootstrap CSS -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		
		<!-- Font Awesome CSS -->
		<link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		
		<!-- Custom CSS -->
		<link href="assets/css/style.css" rel="stylesheet" type="text/css" />

		<!-- BEGIN CSS for this page -->
		<style>
		.table > tbody > tr > .no-line {
			border-top: none;
		}
		.table > thead > tr > .no-line {
			border-bottom: none;
		}
		.table > tbody > tr > .thick-line {
			border-top: 2px solid;
		}
		</style>
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
	$date->add(new DateInterval('PT6H'));
	$fullDateFormat = $date->format('Y-m-d');
	$timeFormat = $date->format('h:i A');

	$supplierName = $_GET['supplierName'];
	$supplierAddress = $_GET['supplierAddress'];
	$trackNum = $_GET['trackNum'];
?>
<div id="main">
    <div>
	
		<!-- Start content -->
        <div class="content">
            
			<div class="container-fluid">
				<!-- end row -->



						<div class="mdl-color--white content-area ng-scope" ng-controller="SalesInvoiceCtrl">
							<div class="row">
								<div class="col-md-12">
									<div class="page-title"><i class="fa fa-table"></i> Order Management</div>
									<hr>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
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
								</div>
							</div>
										<div class="card-body">
											
											<div class="container">
												
												<div class="row">
													<div class="col-md-12">
														<div class="invoice-title text-center mb-3">
															<?php
																$month = (substr($fullDateFormat, 5, 2) * 1);
																$day = substr($fullDateFormat, 8, 2);
																$year = substr($fullDateFormat, 0, 4);
																$convertedMonth = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
																$dateFormat = $convertedMonth[$month] . " " . $day . ", " . $year;
															?>
														</div>
														<!--<hr>-->
														<div class="row">
															<div class="col-md-6">
																<h5>Ordered From:</h5>
																<a style="font-weight:bold;">Store name: </a> 
																<a > Mon.O.Graph <?php echo $trackNum; ?></a></br>
																<a style="font-weight:bold;">Address: </a>
																<a> Lancaster New City, Imus </a></br>
															</div>
															<div class="col-md-6 float-right text-right">																
																<h5>Ordered To:</h5><br>
																<address>
																	<span><?php echo $supplierName; ?></span><br/>
                                                                    <span><?php echo $supplierAddress; ?></span></span>
																</address>
															</div>
														</div>
													</div>
												</div>
												
												<div class="row">
													<div class="col-md-12">
														<div class="panel panel-default">
															<div class="panel-heading">
																<h3 class="panel-title" align = "center"><strong>Order Summary</strong></h3>
																<center><strong>As of</strong> <?php echo $dateFormat; ?></center>											
															</div>
															<div class="panel-body">
																<div class="table-responsive">
																	<table class="table table-condensed">
																		<thead>
																			<tr>
																				<td class="text-center"><strong>Brand</strong></td>
																				<td class="text-center"><strong>Name</strong></td>
																				<td class="text-center"><strong>Quantity</strong></td>
																			</tr>
																		</thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $subTotal = 0;
                                                                            $sql = "SELECT * FROM qrcodesOrderProduct
                                                                                INNER JOIN qrcodesOrderSummary
                                                                                ON qrcodesOrderProduct.qrOrderSummaryId = qrcodesOrderSummary.qrOrderSummaryId
                                                                                INNER JOIN qrcodesStock
                                                                                ON qrcodesOrderProduct.qrStockId = qrcodesStock.qrStockId
																				INNER JOIN qrcodesProduct
																				ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
																				INNER JOIN qrcodesSupplier
																				ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId
																				WHERE qrcodesOrderSummary.qrOrderSummaryNumFormat = '$trackNum'
                                                                                ORDER BY qrcodesSupplier.qrSupplierId DESC";
                                                                            $result = $db->prepare($sql);
                                                                            $result->execute();
                                                                            for($i=0; $row = $result->fetch(); $i++){
                                                                                $productCode = $row['qrProductCode'];

                                                                                $productBrand = $row['qrSupplierBrandName'];
                                                                                $productName =  $row['qrSupplierProductName'];
                                                                                $productPrice = $row['qrProductPrice'];
                                                                                $productLeft = $row['qrStockQtyLeft'];
                                                                                $totalPerItem = $productLeft * $productPrice;

                                                                                $productDelivered = $row['qrSupplierProductQtyDelivered'];
                                                                                $productLimit = round( (($productLeft / $productDelivered) * 100) );
                                                                                $adminSetCritical = $row['qrStockCriticalLevelSet'];

                                                                                if ($productLimit <= $adminSetCritical) {
                                                                                    $subTotal = $totalPerItem + $subTotal;
                                                                        ?>
                                                                            <tr>
                                                                                <td class="text-center"><?php echo $productBrand; ?></td>
                                                                                <td class="text-center"><?php echo $productName; ?></td>
                                                                                <td class="text-center"><?php echo $productLeft . "/" . $productDelivered; ?></td>
                                                                            </tr>
                                                                        <?php
                                                                                }
                                                                            }

																			if ($subTotal == "") {
																				$subtotal = 0;
																			}
                                                                        ?>
                                                                        </tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12"><br/><br/><br/><br/><br/><br/><br/><br/>
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
												</div>


											</div>
												  
											
										</div><!-- end card body -->
										<div class="clearfix"></div>										
							<!-- end card-->					
						</div>
					<!-- end col-->			

				<!-- end row-->



            </div>
			<!-- END container-fluid -->

		</div>
		<!-- END content -->

    </div>
	<!-- END content-page -->
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
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/plugins/switchery/switchery.min.js"></script>

<!-- App js -->
<script src="assets/js/pikeadmin.js"></script>

<!-- BEGIN Java Script for this page -->

<!-- END Java Script for this page -->

</body>
</html>