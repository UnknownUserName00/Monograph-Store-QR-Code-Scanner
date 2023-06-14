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
		<link rel="stylesheet" type="text/css" href="assets/plugins/datatables/dataTables.bootstrap4.min.css"/>

		<!-- END CSS for this page -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Admin Dashboard</title>
</head>

<body class="adminbody">
<?php
	session_start();

    $adminName = $_GET['adminName'];
    if ($_SESSION['SESS_FIRST_NAME'] != $adminName) {
		header("location: Admin Transaction without Design/adminSignOut.php");
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
			<a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPrintAuditTrail.php&linkPage=adminDashboard.php&adminName=<?php echo $adminName; ?>" class="logo">
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
						<a class="active" href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminDashboard.php&adminName=<?php echo $adminName; ?>"><i class="fa fa-fw fa-bars"></i><span> Dashboard </span> </a>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-tv"></i> <span> Maintenance </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminProduct.php&adminName=<?php echo $adminName; ?>&notify">Product Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminSupply.php&adminName=<?php echo $adminName; ?>&notify">Supply Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminPurchaseList.php&adminName=<?php echo $adminName; ?>">Invoice Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminAlert.php&adminName=<?php echo $adminName; ?>">Critical Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminOrderSummaryList.php&adminName=<?php echo $adminName; ?>">Order Management</a></li>
							</ul>
					</li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-qrcode"></i> <span> QR Code </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminGenerateQr.php&adminName=<?php echo $adminName; ?>">Generate QR Code </a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminGenerateQr.php&adminName=<?php echo $adminName; ?>">Print QR Code</a></li>								
							</ul>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-table"></i> <span> Reports </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminPrintSalesReport.php&adminName=<?php echo $adminName; ?>">Sales Report</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminPrintSupply.php&adminName=<?php echo $adminName; ?>">Supply</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminPrintInventory.php&adminName=<?php echo $adminName; ?>">Inventory</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminPrintSupplierInvoice.php&adminName=<?php echo $adminName; ?>&notify">Order Summary</a></li>
							</ul>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-file-text-o"></i> <span> User Management </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminUserRegistration.php&adminName=<?php echo $adminName; ?>&notify">User Registration</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminUserChangePass.php&adminName=<?php echo $adminName; ?>&notify">User Change Password</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminDashboard.php&linkPage=adminPrintAuditTrail.php&adminName=<?php echo $adminName; ?>">Audit Trail</a></li>
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
													<h1 class="main-title float-left">Dashboard</h1>
													<ol class="breadcrumb float-right">
														<li class="breadcrumb-item">&nbsp;</li>
													</ol>
													<div class="clearfix"></div>
											</div>
									</div>
						</div>
						<!-- end row -->

						
							<div class="row">
								<?php
									$purchaseItems = 0;
									$sql = "SELECT SUM(qrPurchaseItemQtyAvail) FROM qrcodesPurchaseItem
                                        INNER JOIN qrcodesSales
                                        ON qrcodesPurchaseItem.qrSalesTransactNum = qrcodesSales.qrSalesTransactNum
                                        WHERE qrcodesSales.qrSalesDate = '$fullDateFormat' ";
									$result = $db->prepare($sql);
									$result->execute();
									for($i=0; $row = $result->fetch(); $i++){
										$purchaseItems = $row['SUM(qrPurchaseItemQtyAvail)'];
									}

									if ($purchaseItems == "") {
										$purchaseItems = 0;
									}
								?>
									<div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
											<div class="card-box noradius noborder bg-default">
													<i class="fa fa-file-text-o float-right text-white"></i>
													<h6 class="text-white text-uppercase m-b-20">Orders</h6>
													<h1 class="m-b-20 text-white counter"><?php echo $purchaseItems; ?></h1>
													<span class="text-white"><?php echo $purchaseItems; ?> Order(s)</span>
											</div>
									</div>
								<?php
												$totalSales = 0;
												$profit = 0;
												$transactPreviousValues = "";
                                                $sql = "SELECT *, SUM(qrSalesAmount), SUM(qrBalanceBegin) FROM qrcodesSales
													INNER JOIN qrcodesBalance
													ON qrcodesSales.qrSalesTransactNum = qrcodesBalance.qrBalanceId
                                                    WHERE qrcodesSales.qrSalesDate = '$fullDateFormat' AND qrcodesSales.qrSalesTenderCash != 0 AND qrcodesSales.qrSalesAmountChange != -qrcodesSales.qrSalesAmountChange
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
                                                            WHERE qrcodesSales.qrSalesDate = '$fullDateFormat' AND qrcodesSales.qrSalesTenderCash != 0 AND qrcodesSales.qrSalesAmountChange != -qrcodesSales.qrSalesAmountChange";
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
												}

												if ($totalSales == "") {
													$totalSales = number_format(0, 2, '.', '');
												}

												if ($profit == "") {
													$profit = number_format(0, 2, '.', '');
												}
								?>
									<div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
											<div class="card-box noradius noborder bg-warning">
													<i class="fa fa-bar-chart float-right text-white"></i>
													<h6 class="text-white text-uppercase m-b-20">Sales</h6>
													<h1 class="m-b-20 text-white counter"><?php echo number_format($totalSales, 2, '.', ''); ?></h1>
													<span class="text-white">Php <?php echo number_format($totalSales, 2, '.', ''); ?></span>
											</div>
									</div>

									<div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
											<div class="card-box noradius noborder bg-info">
													<i class="fa fa-user-o float-right text-white"></i>
													<h6 class="text-white text-uppercase m-b-20">Profit</h6>
													<h1 class="m-b-20 text-white counter"><?php echo number_format($profit, 2, '.', ''); ?></h1>
													<span class="text-white">Php <?php echo number_format($profit, 2, '.', ''); ?></span>
											</div>
									</div>
								<?php
									$alerts = 0;
									$sql = "SELECT * FROM qrcodesSupplier
										INNER JOIN qrcodesProduct
										ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrProductId
										INNER JOIN qrcodesStock
										ON qrcodesProduct.qrProductId = qrcodesStock.qrStockId
										ORDER BY qrcodesSupplier.qrSupplierId DESC";
									$result = $db->prepare($sql);
									$result->execute();
									for($i=0; $row = $result->fetch(); $i++){
										$productLeft = $row['qrStockQtyLeft'];
										$productDelivered = $row['qrSupplierProductQtyDelivered'];
										$productLimit = round( (($productLeft / $productDelivered) * 100) );
										$adminSetCritical = $row['qrStockCriticalLevelSet'];

										if ($productLimit <= $adminSetCritical) {
											$alerts++;
										}
									}

									if ($alerts == "") {
										$alerts = 0;
									}
								?>
									<div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
											<div class="card-box noradius noborder bg-danger">
													<i class="fa fa-bell-o float-right text-white"></i>
													<h6 class="text-white text-uppercase m-b-20">Alerts</h6>
													<h1 class="m-b-20 text-white counter"><?php echo $alerts; ?></h1>
													<span class="text-white"><?php echo $alerts; ?> Alert(s)</span>
											</div>
									</div>
							</div>
							<!-- end row -->


							
							<div class="row">
							
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">						
										<div class="card mb-3">
											<div class="card-header">
												<h3><i class="fa fa-line-chart"></i> Percentage Profit </h3>
											</div>
												
											<div class="card-body" style = "overflow: scroll; height: 500px;">
												<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
												<?php include("adminBarGraphTest.php"); ?>
											</div>							
										</div><!-- end card-->
									</div>

									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">						
										<div class="card mb-3">
											<div class="card-header">
												<h3><i class="fa fa-pie-chart"></i> Stock Pie Chart </h3>
											</div>
												
											<div class="card-body" style = "overflow: scroll; height: 500px;">
												<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
												<span><?php include("adminPieChartTest.php"); ?></span>
											</div>							
										</div><!-- end card-->
									</div>
							</div>

							<div class="row">

									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">						
										<div class="card mb-3">
											<div class="card-header">
												<h3><i class="fa fa-users"></i> Audit Trail </h3>
											</div>
												
											<div class="card-body" style = "overflow: scroll; height: 500px;">

												<table id="example1" class="table table-bordered table-responsive-xl table-hover display">
                                                    <tr align = "center">
                                                        <th>User Name</th>
                                                        <th>User Full Name</th>
                                                        <th>User Activity</th>
                                                        <th>Date<br/>
                                                        (Year&nbsp;-&nbsp;Month&nbsp;-&nbsp;Day)</th>
                                                        <th>Time</th>
                                                    </tr>
                                                    <?php
                                                        $sql = "SELECT * FROM qrcodesAuditTrail
                                                            INNER JOIN qrcodesAdmin
                                                            ON qrcodesAuditTrail.qrAdminId = qrcodesAdmin.qrAdminId
                                                            INNER JOIN qrcodesCashier
                                                            ON qrcodesAuditTrail.qrCashierId = qrcodesCashier.qrCashierId
                                                            ORDER BY qrAuditTrailId DESC;";
                                                        $resultAudit = $db->prepare($sql);
                                                        $resultAudit->execute();
                                                        for($i=0; $rowAudit = $resultAudit->fetch(); $i++){
                                                            $adminFullName = $rowAudit['qrAdminFirstName'] . " " . $rowAudit['qrAdminLastName'];
                                                            $cashierFullName = $rowAudit['qrCashierFirstName'] . " " . $rowAudit['qrCashierLastName'];
                                                    ?>
                                                    <tr align = "center">
                                                        <td><?php
                                                            echo $rowAudit['qrAdminName'];
                                                            echo $rowAudit['qrCashierName'];
                                                        ?></td>
                                                        <td style = "white-space: pre;"><?php
                                                            echo $adminFullName;
                                                            echo $cashierFullName;
                                                        ?></td>
                                                        <td><?php echo $rowAudit['qrUserActivity']; ?></td>
                                                        <td style = "white-space: pre;"><?php echo $rowAudit['qrAuditTrailDate']; ?></td>
                                                        <td style = "white-space: pre;"><?php echo $rowAudit['qrAuditTrailTime']; ?></td>
                                                    </tr>
                                                    <?php
                                                        }
                                                    ?>
												</table>

											</div>
										</div><!-- end card-->
									</div>

					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">						
						<div class="card mb-3">
							<div class="card-header">
								<h3><i class="fa fa-bell-o"></i> Alerts </h3>
							</div>
								
							<div class="card-body" style = "overflow: scroll; height: 500px;">

<?php
$supplierId = "";
$sql = "SELECT * FROM qrcodesSupplier
    INNER JOIN qrcodesProduct
    ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrProductId
    INNER JOIN qrcodesStock
    ON qrcodesProduct.qrProductId = qrcodesStock.qrStockId
    ORDER BY qrcodesSupplier.qrSupplierId DESC";
$result = $db->prepare($sql);
$result->execute();
for($i=0; $row = $result->fetch(); $i++){
	$supplierId = $row['qrSupplierId'];
    $brandName = $row['qrSupplierBrandName'];
    $productName = $row['qrSupplierProductName'];
    $productLeft = $row['qrStockQtyLeft'];
    $productDelivered = $row['qrSupplierProductQtyDelivered'];
	$productLimit = round( (($productLeft / $productDelivered) * 100) );
	$adminSetCritical = $row['qrStockCriticalLevelSet'];

    if ( ($productLimit <= 100) && ($productLimit > 80) ) {
?>
        <div class="alertMessage" style = "background-color: #d4edda; color: #155724;">
            <strong>Full Stockl!</strong><br/>
            (Brand): <?php echo $brandName; ?><br/>
			(Name): <?php echo $productName; ?><br/>
			(Qty): <?php echo $productLeft . "/" . $productDelivered; ?>
        </div>
<?php
    }

    elseif ( ($productLimit <= 80) && ($productLimit > $adminSetCritical) ) {
?>
        <div class="alertMessage" style = "background-color: #cce5ff; color: #004085;">
            <strong>Moderate Level!</strong><br/>
            (Brand): <?php echo $brandName; ?><br/>
			(Name): <?php echo $productName; ?><br/>
			(Qty): <?php echo $productLeft . "/" . $productDelivered; ?>
        </div>
<?php
    }

    elseif ($productLimit <= $adminSetCritical) {
?>
        <div class="alertMessage" style = "background-color: #f8d7da; color: #721c24;">
            <strong>Stock Critical!</strong><br/>
            (Brand): <?php echo $brandName; ?><br/>
			(Name): <?php echo $productName; ?><br/>
			(Qty): <?php echo $productLeft . "/" . $productDelivered; ?>
        </div>
<?php
    }
}

if ($supplierId == "") {
	$notify = "<br/><br/><br/><br/><br/><br/><br/><br/><h4 align = 'center' style = 'color: #ff5d48;'>Sorry! No Any Product(s) has Yet been Registered on the System!</h4>";
}

echo $notify;
?>

							</div>							
						</div><!-- end card-->					
                    </div>

										</div><!-- end card-->					
									</div>
							</div>
							<!-- end row -->
							
							
							<div class="row">



										</div><!-- end card-->					
									</div>
									
							</div>			



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
	<script src="assets/plugins/chart.js/Chart.min.js"></script>
	<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>

	<!-- Counter-Up-->
	<script src="assets/plugins/waypoints/lib/jquery.waypoints.min.js"></script>
	<script src="assets/plugins/counterup/jquery.counterup.min.js"></script>			

	<script>
		$(document).ready(function() {
			// data-tables
			$('#example1').DataTable();
					
			// counter-up
			$('.counter').counterUp({
				delay: 10,
				time: 600
			});
		} );		
	</script>
	
	<script>
	var ctx1 = document.getElementById("lineChart").getContext('2d');
	var lineChart = new Chart(ctx1, {
		type: 'bar',
		data: {
			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			datasets: [{
					label: 'Dataset 1',
					backgroundColor: '#3EB9DC',
					data: [10, 14, 6, 7, 13, 9, 13, 16, 11, 8, 12, 9] 
				}, {
					label: 'Dataset 2',
					backgroundColor: '#EBEFF3',
					data: [12, 14, 6, 7, 13, 6, 13, 16, 10, 8, 11, 12]
				}]
				
		},
		options: {
						tooltips: {
							mode: 'index',
							intersect: false
						},
						responsive: true,
						scales: {
							xAxes: [{
								stacked: true,
							}],
							yAxes: [{
								stacked: true
							}]
						}
					}
	});


	var ctx2 = document.getElementById("pieChart").getContext('2d');
	var pieChart = new Chart(ctx2, {
		type: 'pie',
		data: {
				datasets: [{
					data: [12, 19, 3, 5, 2, 3],
					backgroundColor: [
						'rgba(255,99,132,1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)'
					],
					label: 'Dataset 1'
				}],
				labels: [
					"Red",
					"Orange",
					"Yellow",
					"Green",
					"Blue"
				]
			},
			options: {
				responsive: true
			}
	 
	});


	var ctx3 = document.getElementById("doughnutChart").getContext('2d');
	var doughnutChart = new Chart(ctx3, {
		type: 'doughnut',
		data: {
				datasets: [{
					data: [12, 19, 3, 5, 2, 3],
					backgroundColor: [
						'rgba(255,99,132,1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)'
					],
					label: 'Dataset 1'
				}],
				labels: [
					"Red",
					"Orange",
					"Yellow",
					"Green",
					"Blue"
				]
			},
			options: {
				responsive: true
			}
	 
	});
	</script>
<!-- END Java Script for this page -->

</body>
</html>