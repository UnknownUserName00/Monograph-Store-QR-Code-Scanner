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
			<a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminDashboard.php&adminName=<?php echo $adminName; ?>" class="logo">
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
						<a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminDashboard.php&adminName=<?php echo $adminName; ?>"><i class="fa fa-fw fa-bars"></i><span> Dashboard </span> </a>
                    </li>
					<li class="submenu">
                        <a href="#" class="active"><i class="fa fa-fw fa-tv"></i> <span> Maintenance </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminProduct.php&adminName=<?php echo $adminName; ?>&notify">Product Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminSupply.php&adminName=<?php echo $adminName; ?>&notify">Supply Management</a></li>
                                <li class="active"><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminPurchaseList.php&adminName=<?php echo $adminName; ?>">Invoice Management</a></li> 
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminAlert.php&adminName=<?php echo $adminName; ?>">Critical Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminOrderSummaryList.php&adminName=<?php echo $adminName; ?>">Order Management</a></li>
							</ul>
					</li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-qrcode"></i> <span> QR Code </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminGenerateQr.php&adminName=<?php echo $adminName; ?>">Generate QR Code </a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminGenerateQr.php&adminName=<?php echo $adminName; ?>">Print QR Code</a></li>								
							</ul>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-table"></i> <span> Reports </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminPrintSalesReport.php&adminName=<?php echo $adminName; ?>">Sales Report</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminPrintSupply.php&adminName=<?php echo $adminName; ?>">Supply</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminPrintInventory.php&adminName=<?php echo $adminName; ?>">Inventory</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminPrintSupplierInvoice.php&adminName=<?php echo $adminName; ?>&notify">Order Summary</a></li>
							</ul>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-file-text-o"></i> <span> User Management </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminUserRegistration.php&adminName=<?php echo $adminName; ?>&notify">User Registration</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminUserChangePass.php&adminName=<?php echo $adminName; ?>&notify">User Change Password</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminPrintAuditTrail.php&adminName=<?php echo $adminName; ?>">Audit Trail</a></li>
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
                                    <h1 class="main-title float-left" style = "color: Black;">Invoice Management</h1>
                                    <ol class="breadcrumb float-right">
										<li class="breadcrumb-item">&nbsp;</li>
                                    </ol>
                                    <div class="clearfix"></div>
                            </div>
					</div>
			</div>
        <div class="mdl-color--white content-area ng-scope" ng-controller="SalesInvoiceCtrl">
			<div class="row">
					<div class="col-xl-12">
						<div class="page-title"><i class="fa fa-table"></i> List of Transaction</div>
						<hr>
					</div>
			</div>
			<div class="card-body">
                            <?php
                                if ( isset($_GET['dateFrom']) and isset($_GET['dateTo']) ) {
                                    $getDateFrom = $_GET['dateFrom'];
                                    $getDateTo = $_GET['dateTo'];

									$staffActivity = "Searched Dates on Invoice Management!";

									$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
										VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
									$resultAudit = $db->prepare($sql);
									$resultAudit->execute();
                                }

                                else {
                                    $getDateFrom = "";
                                    $getDateTo = "";
                                }
                            ?>
                            <form method = "get">
                                <input type = "hidden" name = "adminName" value = "<?php echo $adminName; ?>"/>
                                <span class="float-left"><strong>From:&nbsp;</strong></span>
                                <input type = "date" name = "dateFrom" class="form-control float-left" style = "width: 200px;" required value = "<?php
                                    if (isset($_GET['setDate'])) {
                                        echo $getDateFrom = $_GET['dateFrom'];
                                    }

                                    else {
                                        echo $getDateFrom;
                                    }
                                ?>">

                                <a href="#"><button type = "submit" role="button" name = "setDate" class="btn btn-success float-right"style="background-color:#17a2b8;" >Search Date</button></a>
                                <input type = "date" name = "dateTo" class="form-control float-right" style = "width: 200px;" required value = "<?php
                                    if (isset($_GET['setDate'])) {
                                        echo $getDateTo = $_GET['dateTo'];
                                    }

                                    else {
                                        echo $getDateTo;
                                    }
                                ?>">
                                <span class="float-right"><strong>To:&nbsp;</strong></span>

                            </form><br/><br/><br/>

                                <table class="table table-responsive-xl table-hover display" id="example1">
                                    <thead style="background-color:#17a2b8;color:white;">
									<tr align = "center">
                                        <th scope="col"> Transaction Number </th>
                                        <th scope="col"> Invoice Number </th>
                                        <th scope="col"> Date<br/>
										(Year&nbsp;-&nbsp;Month&nbsp;-&nbsp;Date) </th>
                                        <th scope="col"> Action </th>
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
											$sql = "SELECT * FROM qrcodesSales
												WHERE qrSalesDate BETWEEN '$getDateFrom' AND '$getDateTo' AND qrSalesTenderCash != 0 AND qrSalesAmountChange != -qrSalesAmountChange
												ORDER BY qrSalesTransactNum DESC";
											$result = $db->prepare($sql);
											$result->execute();
											for($i=0; $row = $result->fetch(); $i++){
												$salesId = $row['qrSalesTransactNum'];

                                                $amountChange = $row['qrSalesAmountChange'];

                                                if ($amountChange > 0) {
                                    ?>
                                    <tr align = "center" class="record">
                                        <td><?php echo $row['qrSalesTransactString']; ?></td>
                                        <td><?php echo $row['qrSalesInvoiceNum']; ?></td>
                                        <td><?php echo $row['qrSalesDate']; ?></td>
                                        <td style = "white-space: pre;"><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminPurchaseList.php&linkPage=adminPrintPurchaseList.php&transactNum=<?php echo $salesId; ?>&adminName=<?php echo $adminName; ?>"> View </a> | <a href="#" id="<?php echo $salesId; ?>" name = "<?php echo $adminName; ?>" class="delbutton" title="Click To Delete">Delete</a></td>
                                    </tr>
                                    <?php
                                                }
											}
										}
                                    ?>
									</tbody>
                                </table>

                                <div class="clearfix"></div>
            </div>
        </div>
            <!-- end row -->

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
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/plugins/switchery/switchery.min.js"></script>

<!-- App js -->
<script src="assets/js/pikeadmin.js"></script>

<!-- BEGIN Java Script for this page -->

<!-- END Java Script for this page -->

<!-- Admin Delete Purchase List -->
<script src="adminJQueryDeletePurchaseList.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
    $(".delbutton").click(function(){
        //Save the link in a variable called element
        var element = $(this);

        //Find the id of the link that was clicked
        var del_id = element.attr("id");
        var get_name = element.attr("name");

        //Built a url to send
        var info = 'purchaseId=' + del_id;
        var info1 = 'adminName=' + get_name;
        if(confirm("Are You Sure Do You Want to Delete This Record Permanently?")) {
            $.ajax({
            type: "GET",
            url: "Admin Transaction without Design/adminDeletePurchaseList.php?" + info + "&" + info1,
            // data: info,
            // data1: info1,
            success: function(){
            }
            });
            $(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast")
            .animate({ opacity: "hide" }, "slow");
        }
        return false;
    });
});
</script>




<!-- Table Search and Pagination -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/fastclick.js"></script>

<!-- BEGIN Java Script for this page -->
	<script src="jquery.dataTables.min.js"></script>
	<script src="dataTables.bootstrap4.min.js"></script>

	<script>
	// START CODE FOR BASIC DATA TABLE 
	$(document).ready(function() {
		$('#example1').DataTable( {
			// Modified Version of Descending Order on Table
			"order": [[ 1, "desc" ]]
		});
	} );
	// END CODE FOR BASIC DATA TABLE 
	</script>

</body>
</html>