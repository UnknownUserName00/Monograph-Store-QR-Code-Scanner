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

<body class="adminbody">
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

	$notify = $_GET['notify'];

    $sql = "SELECT * FROM qrcodesAdmin
		WHERE qrAdminName = '$adminName' ";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
		$adminFirstName = $row['qrAdminFirstName'];
		$adminId = $row['qrAdminId'];
	}
?>
<div id="main">

	<!-- top bar navigation -->
	<div class="headerbar">

		<!-- LOGO -->
        <div class="headerbar-left">
			<a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminDashboard.php&adminName=<?php echo $adminName; ?>" class="logo">
				<img alt="Logo" src="../icon.png" />
				<span>Admin</span>
			</a>
        </div>

        <nav class="navbar-custom">

                    <ul class="list-inline float-right mb-0">
						<?php
							$messageGrantCheck = "";
							$sql = "SELECT * FROM qrcodesCashier
								WHERE qrCashierChangePassRequest = 'Pending'
								ORDER BY qrCashierId DESC";
							$result = $db->prepare($sql);
							$result->execute();
							for($i=0; $row = $result->fetch(); $i++){
								$messageGrantCheck = $row['qrCashierChangePassRequest'];
								$notifyMessageGrant = "<span class='notif-bullet'></span>";
							}

							if ($messageGrantCheck == "") {
								$notifyMessageGrant = "";
							}
						?>
						<li class="list-inline-item dropdown notif">
                            <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="fa fa-fw fa-envelope-o"></i><?php echo $notifyMessageGrant; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-arrow-success dropdown-lg">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5><small>Change Password Request</small></h5>
                                </div>
                                <!-- item-->
                                <a href="#" class="dropdown-item notify-item" style = "height: 250px; overflow: scroll;">
                                    <?php include "adminChangePassMessageGrant.php"; ?>
                                </a>
                            </div>
                        </li>

						<?php
							$messageAlertCheck = "";
							$sql = "SELECT * FROM qrcodesSupplier
								INNER JOIN qrcodesProduct
								ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrProductId
								INNER JOIN qrcodesStock
								ON qrcodesProduct.qrProductId = qrcodesStock.qrStockId
								ORDER BY qrcodesSupplier.qrSupplierId DESC";
							$result = $db->prepare($sql);
							$result->execute();
							for($i=0; $row = $result->fetch(); $i++){
								$productName = $row['qrSupplierProductName'];
								$productLeft = $row['qrStockQtyLeft'];
								$productDelivered = $row['qrSupplierProductQtyDelivered'];
								$productLimit = round( (($productLeft / $productDelivered) * 100) );
								$adminSetCritical = $row['qrStockCriticalLevelSet'];

								if ($productLimit <= $adminSetCritical) {
									$messageAlertCheck = $productName;
									$notifyMessageAlert = "<span class='notif-bullet'></span>";
								}
							}

							if ($messageAlertCheck == "") {
								$notifyMessageAlert = "";
							}
						?>
						<li class="list-inline-item dropdown notif">
                            <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="fa fa-fw fa-bell-o"></i><?php echo $notifyMessageAlert; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-lg">
								<!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5><small><span class="label label-danger pull-xs-right"></span>Stock Critical Alerts</small></h5>
                                </div>
								
                                <!-- item-->
                                <a href="#" class="dropdown-item notify-item" style = "height: 250px; overflow: scroll;">
                                    <?php include "adminAlertMessage.php"; ?>
                                </a>

                                <!-- All-->
                                <a href="#" class="dropdown-item notify-item notify-all">

                                </a>

                            </div>
                        </li>

                        <li class="list-inline-item dropdown notif">
                            <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <div class="noti-title" style = "background-color: #4980b5">
                                    <h5 class="text-overflow"><small>Hello, <?php echo $adminFirstName; ?></small> </h5>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <!-- item-->
                                <a href="Admin Transaction without Design/adminSignOut.php?adminName=<?php echo $adminName; ?>" class="dropdown-item notify-item">
                                    <i class="fa fa-power-off"></i> <span>Sign Out</span>
                                </a>
                            </div>
                        </li>

                    </ul>

                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left">
								<i class="fa fa-fw fa-bars"></i>
                            </button>
                        </li>                        
                    </ul>

        </nav>

	</div>
	<!-- End Navigation -->
	
 
	<!-- Left Sidebar -->
	<div class="left main-sidebar">
	
		<div class="sidebar-inner leftscroll">

			<div id="sidebar-menu">
        
			<ul>
					<li class="submenu">
						<a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminDashboard.php&adminName=<?php echo $adminName; ?>"><i class="fa fa-fw fa-bars"></i><span> Dashboard </span> </a>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-tv"></i> <span> Maintenance </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminProduct.php&adminName=<?php echo $adminName; ?>&notify">Product Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminSupply.php&adminName=<?php echo $adminName; ?>&notify">Supply Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminPurchaseList.php&adminName=<?php echo $adminName; ?>">Invoice Management</a></li> 
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminAlert.php&adminName=<?php echo $adminName; ?>">Critical Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminOrderSummaryList.php&adminName=<?php echo $adminName; ?>">Order Management</a></li>
							</ul>
					</li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-qrcode"></i> <span> QR Code </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminGenerateQr.php&adminName=<?php echo $adminName; ?>">Generate QR Code </a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminGenerateQr.php&adminName=<?php echo $adminName; ?>">Print QR Code</a></li>								
							</ul>
                    </li>
					<li class="submenu">
                        <a href="#" class="active"><i class="fa fa-fw fa-table"></i> <span> Reports </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminPrintSalesReport.php&adminName=<?php echo $adminName; ?>">Sales Report</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminPrintSupply.php&adminName=<?php echo $adminName; ?>">Supply</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminPrintInventory.php&adminName=<?php echo $adminName; ?>">Inventory</a></li>
								<li class="active"><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminPrintSupplierInvoice.php&adminName=<?php echo $adminName; ?>&notify">Order Summary</a></li>
							</ul>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-file-text-o"></i> <span> User Management </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminUserRegistration.php&adminName=<?php echo $adminName; ?>&notify">User Registration</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminUserChangePass.php&adminName=<?php echo $adminName; ?>&notify">User Change Password</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminPrintAuditTrail.php&adminName=<?php echo $adminName; ?>">Audit Trail</a></li>
							</ul>
                    </li>
            </ul>

            <div class="clearfix"></div>

			</div>
        
			<div class="clearfix"></div>

		</div>

	</div>
	<!-- End Sidebar -->


    <div class="content-page">
	
		<!-- Start content -->
        <div class="content">
            
			<div class="container-fluid">

				<div class="row">
					<div class="col-xl-12">
						<div class="breadcrumb-holder">
							<h1 class="main-title float-left">Order Summary</h1>
							<ol class="breadcrumb float-right">
<?php
	$alterOrderId = 1;
	$orderSummaryId = "";
	$sql = "SELECT MAX(qrOrderSummaryId) FROM qrcodesOrderSummary";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
		$orderSummaryId = $row['MAX(qrOrderSummaryId)'] + $alterOrderId;
	}

	if ($orderSummaryId == "") {
		$orderSummaryId = $alterOrderId;
	}

	$trackNum = "OS-" . $fullDateFormat . "-" . $orderSummaryId;

	if ( isset($_GET['orderedTo']) ) {
		$notify = "";
		$supplierName = $_GET['orderedTo'];

		$sql = "SELECT * FROM qrcodesSupplier
			WHERE qrSupplierName = '$supplierName' ";
		$result = $db->prepare($sql);
		$result->execute();
		for($i=0; $row = $result->fetch(); $i++){
			$supplierAddress = $row['qrSupplierAddress'];
		}

		$staffAddress = "1234 Main<br>"
			. "Apt. 4B<br>"
			. "Springfield, ST 54321";
	}

	else {
		$supplierName = "";
		$supplierAddress = "";
	}

	$checkStock = "";
    $sql = "SELECT * FROM qrcodesSupplier
        INNER JOIN qrcodesProduct
        ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrProductId
        INNER JOIN qrcodesStock
        ON qrcodesProduct.qrProductId = qrcodesStock.qrStockId
        ORDER BY qrcodesSupplier.qrSupplierId ASC";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
        $productLeft = $row['qrStockQtyLeft'];
        $productDelivered = $row['qrSupplierProductQtyDelivered'];
		$checkStock = $productLeft . "/" . $productDelivered;
	}

	if ($supplierName == "" ||  $supplierAddress == "" || $checkStock == "") {
		$printInvoiceLink = "&nbsp;";
	}

	else {
		$printInvoiceLink = "<a href = 'Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSupplierInvoice.php&linkPage=adminPrintReadySupplierInvoice.php&adminName=" . $adminName . "&supplierName=" . $supplierName . "&supplierAddress=" . $supplierAddress . "&checkTrackNum=" . $trackNum . "&notify'><button id='myBtn' style='color: #fff;background-color: #17a2b8;border-color: #17a2b8; border-radius:5px;width:100px;height:35px;float:left;'>Print</button></a>";
	}
?>
								<li class="breadcrumb-item"><?php echo $printInvoiceLink; ?></li>
							</ol>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<!-- end row -->



						<div class="mdl-color--white content-area ng-scope" ng-controller="SalesInvoiceCtrl">
							<div class="row">
								<div class="col-md-12">
									<div class="page-title"><i class="fa fa-table"></i> Order Summary</div>
									<hr>
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
														<hr>
														<form method = "get">
														<input type = "hidden" name = "adminName" value = "<?php echo $_GET['adminName']; ?>"/>
														<input type = "hidden" name = "notify" value = "<?php echo $notify; ?>"/>
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
                                                                    <select name = "orderedTo" required>
                                                                        <option></option>
                                                                        <?php
                                                                            $sql = "SELECT DISTINCT(qrSupplierName), qrSupplierAddress FROM qrcodesSupplier";
                                                                            $result = $db->prepare($sql);
                                                                            $result->execute();
                                                                            for($i=0; $row = $result->fetch(); $i++){
                                                                        ?>
                                                                        <option value = "<?php echo $row['qrSupplierName']; ?>"><?php echo $row['qrSupplierName']; ?></option>
                                                                        <?php
                                                                            }

																			$supplierName = $_GET['orderedTo'];
                                                                        ?>
                                                                    </select><br/>
																	<span><?php echo $supplierName; ?></span><br/>
																	<?php
																			$supplierAddress = "";
                                                                            $sql = "SELECT * FROM qrcodesSupplier
																				WHERE qrSupplierName = '$supplierName' ";
                                                                            $result = $db->prepare($sql);
                                                                            $result->execute();
                                                                            for($i=0; $row = $result->fetch(); $i++){
																				$supplierAddress = $row['qrSupplierAddress'];
																			}
																	?>
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
                                                                            $sql = "SELECT * FROM qrcodesSupplier
                                                                                INNER JOIN qrcodesProduct
                                                                                ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrProductId
                                                                                INNER JOIN qrcodesStock
                                                                                ON qrcodesProduct.qrProductId = qrcodesStock.qrStockId
                                                                                ORDER BY qrcodesSupplier.qrSupplierId ASC";
                                                                            $result = $db->prepare($sql);
                                                                            $result->execute();
                                                                            for($i=0; $row = $result->fetch(); $i++){
                                                                                $productCode = $row['qrProductCode'];

                                                                                $productBrand = $row['qrSupplierBrandName'];
                                                                                $productName =  $row['qrSupplierProductName'];
                                                                                $productCost = $row['qrProductCost'];
                                                                                $productLeft = $row['qrStockQtyLeft'];
                                                                                $totalPerItem = $productLeft * $productCost;

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
																				$subTotal = 0;
																			}
                                                                        ?>
                                                                        </tbody>
																	</table>
														
															<center>
																<?php echo $notify; ?>
																<button style="color: #fff;background-color: #17a2b8;border-color: #17a2b8; border-radius:5px;width:100px;height:35px;" type = "submit" value = "Update Form"> Update Form </button>
															</center>
														</form>
																</div>
															</div>
														</div>
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
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/plugins/switchery/switchery.min.js"></script>

<!-- App js -->
<script src="assets/js/pikeadmin.js"></script>

<!-- BEGIN Java Script for this page -->

<!-- END Java Script for this page -->

</body>
</html>