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
			<a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminDashboard.php&adminName=<?php echo $adminName; ?>" class="logo">
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
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown">
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
						<a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminDashboard.php&adminName=<?php echo $adminName; ?>"><i class="fa fa-fw fa-bars"></i><span> Dashboard </span> </a>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-tv"></i> <span> Maintenance </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminProduct.php&adminName=<?php echo $adminName; ?>&notify">Product Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminSupply.php&adminName=<?php echo $adminName; ?>&notify">Supply Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminPurchaseList.php&adminName=<?php echo $adminName; ?>">Invoice Management</a></li> 
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminAlert.php&adminName=<?php echo $adminName; ?>">Critical Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminOrderSummaryList.php&adminName=<?php echo $adminName; ?>">Order Management</a></li>
							</ul>
					</li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-qrcode"></i> <span> QR Code </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminGenerateQr.php&adminName=<?php echo $adminName; ?>">Generate QR Code </a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminGenerateQr.php&adminName=<?php echo $adminName; ?>">Print QR Code</a></li>								
							</ul>
                    </li>
					<li class="submenu">
                        <a href="#" class="active"><i class="fa fa-fw fa-table"></i> <span> Reports </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li class="active"><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminPrintSalesReport.php&adminName=<?php echo $adminName; ?>">Sales Report</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminPrintSupply.php&adminName=<?php echo $adminName; ?>">Supply</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminPrintInventory.php&adminName=<?php echo $adminName; ?>">Inventory</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminPrintSupplierInvoice.php&adminName=<?php echo $adminName; ?>&notify">Order Summary</a></li>
							</ul>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-file-text-o"></i> <span> User Management </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminUserRegistration.php&adminName=<?php echo $adminName; ?>&notify">User Registration</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminUserChangePass.php&adminName=<?php echo $adminName; ?>&notify">User Change Password</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminPrintAuditTrail.php&adminName=<?php echo $adminName; ?>">Audit Trail</a></li>
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
												<h1 class="main-title float-left">Sales Report</h1>
												<ol class="breadcrumb float-right">
                                            <?php
                                                if ( isset($_GET['dateFrom']) and isset($_GET['dateTo']) ) {
                                                    $getDateFrom = $_GET['dateFrom'];
                                                    $getDateTo = $_GET['dateTo'];

													$staffActivity = "Searched Dates on Sales Report!";

													$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
														VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
													$resultAudit = $db->prepare($sql);
													$resultAudit->execute();

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

													$dateRange = "From " . $dateFormatFrom . " To " . $dateFormatTo;
													

													$checkSales = "";
													$sql = "SELECT * FROM qrcodesSales
														WHERE qrSalesDate BETWEEN '$getDateFrom' AND '$getDateTo' AND qrSalesTenderCash != 0 AND qrSalesAmountChange != -qrSalesAmountChange
														GROUP BY YEAR(qrSalesDate), MONTH(qrSalesDate)
														ORDER BY qrSalesTransactNum ASC";
													$resultSummary = $db->prepare($sql);
													$resultSummary ->execute();
													for($i=0; $rowSummary  = $resultSummary ->fetch(); $i++){
														$checkSales = $rowSummary['qrSalesTransactNum'];
													}

													if ($checkSales == "") {
														$printLink = "&nbsp;";
													}

													else {
														$printLink = "<a href = 'Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintSalesReport.php&linkPage=adminPrintReadySalesReport.php&getDateFrom=" . $getDateFrom . "&getDateTo=" . $getDateTo . "&adminName=" . $adminName . "'><button id='myBtn' style='color: #fff;background-color: #17a2b8;border-color: #17a2b8; border-radius:5px;width:100px;height:35px;float:left;'>Print</button></a>";
													}
                                                }

                                                else {
                                                    $getDateFrom = "";
                                                    $getDateTo = "";

													$printLink = "&nbsp;";
													$dateRange = "";
                                                }
                                            ?>
                                                    <li><?php echo $printLink; ?></li>
												</ol>
												<div class="clearfix"></div>
										</div>
								</div>
						</div>
						<div class="mdl-color--white content-area ng-scope" ng-controller="SalesInvoiceCtrl">
							<div class="row">
								<div class="col-md-12">
									<div class="page-title"><i class="fa fa-table"></i> Sales Report</div>
									<hr>
								</div>
							</div>
							<div class="card-body">
                                <form method = "get">
									<input type = "hidden" name = "adminName" value = "<?php echo $adminName = $_GET['adminName']; ?>"/>

                                    <span class="float-left"><strong>From:&nbsp;</strong></span>
                                    <input type = "date" name = "dateFrom" class="form-control float-left" style = "width: 200px;" required value = "<?php
                                                    if (isset($_GET['setDate'])) {
                                                        echo $_GET['dateFrom'];
                                                    }

                                                    else {
                                                         $fullDateFormat;
                                                    }
                                                ?>"/>

                                    <input role="button" type = "submit" name = "setDate" value = "Search Date" href="#" style="background-color:#17a2b8;" class="btn btn-success float-right"/>
                                    <input type = "date" name = "dateTo" class="form-control float-right" style = "width: 200px;" required value = "<?php
                                        if (isset($_GET['setDate'])) {
                                            echo $_GET['dateTo'];
                                        }

                                        else {
                                            $fullDateFormat;
                                        }
                                    ?>"/>
                                    <span class="float-right"><strong>To:&nbsp;</strong></span>
                                </form><br/><br/><br/>

                                <table class="table table-responsive-xl table-hover display" id="example1">
										<thead style="background-color:#17a2b8;color:white;">
                                                    <tr align = "center" style="background-color:white;color:black;">
                                                        <th colspan = "5"><?php echo $dateRange; ?></th>
                                                    </tr>
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


<!-- Table Search and Pagination -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/fastclick.js"></script>

<!-- BEGIN Java Script for this page -->
	<script src="jquery.dataTables.min.js"></script>
	<script src="dataTables.bootstrap4.min.js"></script>

	<script>
	// START CODE FOR BASIC DATA TABLE 
	$(document).ready(function() {
		$('#example1').DataTable({
			// Modified Version of Descending Order on Table
			"order": [[ 1, "desc" ]]
		});
	} );
	// END CODE FOR BASIC DATA TABLE 
	</script>

</body>
</html>