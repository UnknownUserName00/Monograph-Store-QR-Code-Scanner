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
			<a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminDashboard.php&adminName=<?php echo $adminName; ?>" class="logo">
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
						<a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminDashboard.php&adminName=<?php echo $adminName; ?>"><i class="fa fa-fw fa-bars"></i><span> Dashboard </span> </a>
                    </li>
					<li class="submenu">
                        <a href="#" class="active"><i class="fa fa-fw fa-tv"></i> <span> Maintenance </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li class="active"><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminProduct.php&adminName=<?php echo $adminName; ?>&notify">Product Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminSupply.php&adminName=<?php echo $adminName; ?>&notify">Supply Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminPurchaseList.php&adminName=<?php echo $adminName; ?>">Invoice Management</a></li> 
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminAlert.php&adminName=<?php echo $adminName; ?>">Critical Management</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminOrderSummaryList.php&adminName=<?php echo $adminName; ?>">Order Management</a></li>
							</ul>
					</li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-qrcode"></i> <span> QR Code </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminGenerateQr.php&adminName=<?php echo $adminName; ?>">Generate QR Code </a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminGenerateQr.php&adminName=<?php echo $adminName; ?>">Print QR Code</a></li>								
							</ul>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-table"></i> <span> Reports </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminPrintSalesReport.php&adminName=<?php echo $adminName; ?>">Sales Report</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminPrintSupply.php&adminName=<?php echo $adminName; ?>">Supply</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminPrintInventory.php&adminName=<?php echo $adminName; ?>">Inventory</a></li>
								<li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminPrintSupplierInvoice.php&adminName=<?php echo $adminName; ?>&notify">Order Summary</a></li>
							</ul>
                    </li>
					<li class="submenu">
                        <a href="#"><i class="fa fa-fw fa-file-text-o"></i> <span> User Management </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminUserRegistration.php&adminName=<?php echo $adminName; ?>&notify">User Registration</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminUserChangePass.php&adminName=<?php echo $adminName; ?>&notify">User Change Password</a></li>
                                <li><a href="Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminPrintAuditTrail.php&adminName=<?php echo $adminName; ?>">Audit Trail</a></li>
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
                                    <h1 class="main-title float-left" style = "color: Black;">Product Management</h1>
                                    <ol class="breadcrumb float-right">
										<li onclick = "openAddProductForm()"><button id="myBtn" style="color: #fff;background-color: #17a2b8;border-color: #17a2b8; border-radius:5px;width:100px;height:35px;float:left;">Add Product</button></li>
                                    </ol>
                                    <div class="clearfix"></div>
                            </div>
					</div>
			</div>
        <div class="mdl-color--white content-area ng-scope" ng-controller="SalesInvoiceCtrl">
			<div class="row">
					<div class="col-xl-12">
                        <div class="page-title"><i class="fa fa-table"></i> List of Products</div>
                        <hr/>
					</div>
			</div>
			<div class="card-body">
				<div class="dataTables_length" id="example1_length">
                        <div class="table-responsive">
							<center style = "font-weight: Bold;"><?php echo $notify; ?></center>
                            <table class="table table-responsive-xl table-hover display" id="example1">
								<thead style="background-color:#17a2b8;color:white;">
                                    <tr align = "center">
                                        <th scope="col"> Supplier Name </th>
                                        <th scope="col"> Product Brand </th>
                                        <th scope="col"> Product Code </th>
                                        <th scope="col"> Product Name </th>
                                        <th scope="col"> Product Cost </th>
                                        <th scope="col"> Profit Margin (Percentage) </th>
                                        <th scope="col"> Product Price </th>
                                        <th scope="col"> Product Category </th>
                                        <th scope="col"> Action </th>
                                    </tr>
								</thead>
								<tbody>
                                        <?php
                                            $sql = "SELECT * FROM qrcodesSupplier
                                                INNER JOIN qrcodesProduct
                                                ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrProductId
                                                ORDER BY qrcodesSupplier.qrSupplierId DESC;";
                                            $result = $db->prepare($sql);
                                            $result->execute();
                                            for($i=0; $row = $result->fetch(); $i++){
                                        ?>
                                    <tr align = "center" class="record">
                                        <td><?php echo $row['qrSupplierName']; ?></td>
                                        <td><?php echo $row['qrSupplierBrandName']; ?></td>
                                        <td style = "white-space: pre;"><?php echo $row['qrProductCode']; ?></td>
                                        <td><?php echo $row['qrSupplierProductName']; ?></td>
                                        <td style = "white-space: pre;">Php <?php echo $row['qrProductCost']; ?></td>
                                        <td><?php echo round($row['qrProductProfitMarginPercent'], 2); ?>%</td>
                                        <td style = "white-space: pre;">Php <?php echo $row['qrProductPrice']; ?></td>
                                        <td><?php echo $row['qrProductCategory']; ?></td>

                                        <input type = "hidden" name = "productId" value="<?php echo $productId = $row['qrProductId']; ?>"/>
                                        <td style = "white-space: pre;"><a href = "Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminProduct.php&linkPage=adminEditProductId.php&productId=<?php echo $productId; ?>&adminName=<?php echo $adminName; ?>&notify"> Edit </a> | <a href="#" id="<?php echo $productId; ?>" name = "<?php echo $adminName; ?>" class="delbutton" title="Click To Delete">Delete</a></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
								</tbody>
                            </table>
            </div>
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




							<!-- add item modal -->
					<div id="myModal" class="modal">
						<center><div class="modal-content" style="width:348px;margin-top:50px;">

		<div id = "addProductForm" style = "display: none;">
			<form method="get" action = "Admin Transaction without Design/adminAddProduct.php" autocomplete="off">
			</br>
			</br>
				<center><div class="page-title" style="font-size:20px;"> ADD Product </div></center>
				</br>
				</br>

				<input type = "hidden" name = "adminName" value = "<?php echo $adminName; ?>"/>

				<center>
					<span style="font-weight:bold;" >Product Name : </span>
					<select name="productName" class="form-control text-left ng-pristine ng-untouched ng-valid ng-empty" style="width:250px;" required>
						<option></option>
						<?php
							$sql = "SELECT DISTINCT(qrSupplierBrandName), qrSupplierProductName FROM qrcodesSupplier
								ORDER BY qrSupplierId DESC";
							$result = $db->prepare($sql);
							$result->execute();
							for($i=0; $row = $result->fetch(); $i++){
								$productBrand = $row['qrSupplierBrandName'];
								$addProductName = $row['qrSupplierProductName'];
						?>
						<option value = "<?php echo $addProductName; ?>"><?php echo $productBrand . " - " . $addProductName; ?></option>
						<?php
							}
						?>
					</select><br>
					<center>
						<span style="font-weight:bold;">Product Cost : </span>
						<input type="number" class="form-control text-left ng-pristine ng-untouched ng-valid ng-empty"	style="width:250px;" min = "1" placeholder = "Input Value" name="productCost" required value="<?php
							if(isset($_GET['AddProductItems'])){
								echo $_GET['productCost'];
							} ?>"/>
					</center></br>
					<center>
						<span style="font-weight:bold;" >Product Price : </span>
						<input type="number" class="form-control text-left ng-pristine ng-untouched ng-valid ng-empty"	style="width:250px;" min = "1" placeholder = "Input Value" name="productPrice" required value="<?php
							if(isset($_GET['AddProductItems'])){
								echo $_GET['productPrice'];
							} ?>"/>
					</center></br>
					<center>
						<span style="font-weight:bold;">Promo Discount : </span>
						<input type="number" class="form-control text-left ng-pristine ng-untouched ng-valid ng-empty"	style="width:250px;" min = "1" max = "100" placeholder = "Input Percentage" name="promoDiscount" required value="<?php
							if(isset($_GET['AddProductItems'])){
								echo $_GET['productPrice'];
							} ?>"/>
					</center></br>
					<center>
						<span style="font-weight:bold;">Product Category : </span>
						<input type="text" class="form-control text-left ng-pristine ng-untouched ng-valid ng-empty" style="width:250px;" placeholder = "Input Value" name="productCategory" required value="<?php
							if(isset($_GET['AddProductItems'])){
								echo $_GET['productCategory'];
							} ?>"/>
					</center></br>
					<center>
						<span style="font-weight:bold;">Quantity per Bundle : </span>
						<input type="number" class="form-control text-left ng-pristine ng-untouched ng-valid ng-empty"	style="width:250px;" min = "1" placeholder = "Input Value" name="productQtyPerBundle" required value="<?php
							if(isset($_GET['AddProductItems'])){
								echo $_GET['productQtyPerBundle'];
							} ?>"/>
					</center></br>
					<center>
						<span style="font-weight:bold;">Unit Field for Item : </span>
						<input type="text" class="form-control text-left ng-pristine ng-untouched ng-valid ng-empty" style="width:250px;" placeholder = "Input Value" name="productUnitField" required value="<?php
							if(isset($_GET['AddProductItems'])){
								echo $_GET['productUnitField'];
							} ?>"/>
					</center><br/>
					<center>
						<span style="font-weight:bold;">Storage Section : </span>
						<input type="text" class="form-control text-left ng-pristine ng-untouched ng-valid ng-empty" style="width:250px;" placeholder = "Input Value" name="stockStorageSection" required value="<?php
							if(isset($_GET['AddProductItems'])){
								echo $_GET['stockStorageSection'];
							} ?>"/>
					</center>

				</br>	<button id="btn" type="submit" class="button button1" name = "AddProductItems" style="padding: 14px 100px;">Save</button>
					<span class="close"><button class="button buttn1" onclick = "closeAddProductForm()" style="background-color:red; padding: 14px 100px;">Close</button></span> <br/><br/>
				
			</form>
		</div>

                        </div></center>
                    </div>




</div>
<style>
.modal {
	 display:none;
	 position: fixed;
	 z-index: 1;
	 paddind-top:100px;
	 left:0;
	 top:0;
	 width:100%;
	 height:100%;
	 overflow:auto;
	 background-color: rgb(0,0,0);
	 background-color: rgba(0,0,0,0.4);
 }
 .modal-content{
	 background-color:white:
	 margin: auto;
	 paddind: 20px;
	 border: 1px solid #888;
	 width: 80%;
 }
 .close{
	 color:red;
	 float: rigth;
	 font-size: 28px;
	 font-weight: bold;
	 left: -15%;
	 position: relative;
 }
 
 .close:hover,
 .close:focus{
	 color: #000;
	 text-decoration: none;
	 cursor:pointer;
 }
.form {
	Background:white;
	/* filter:alpha(opacity=60%); */
	color: black;
	border: 2px solid #17a2b8;
	width: 350px;
	font: 16px Ariel, sans-serif;
	border-radius:8px;
	height:350px;
}
.button {
	background-color:#17a2b8;
	border: none;
	text-align: center;
	color:white;
	dispaly: inline-block;
	font-size:16px;
	margin: 4px 2px;
	cursor: pointer;
	font: 16px Ariel, sans-serif;
}
.button1{
	padding: 10px;
	border-radius:4px;
	height:45px;
}

h1 {
	color:#0063cc;
	font:Ariel, sans-serif;
}
.trans{
	background:hsla(0,0%,70%,0.6)
}
</style>
<!-- script modal -->
<script>
var modal = document.getElementById('myModal');

var btn = document.getElementById("myBtn");

var span = document.getElementsByClassName("close")[0];

btn.onclick = function(){
	modal.style.display = "block";
}

span.onclick = function(){
	modal.style.display = "none";
}
window.onclick = function(event){
	if (event.targe == modal){
		modal.style.display = "none";
	}
}
</script>
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

<!-- Add Product Form -->
<script>
	function openAddProductForm() {
		document.getElementById("addProductForm").style.display = "block";
	}

	function closeAddProductForm() {
		document.getElementById("addProductForm").style.display = "none";
	}
</script>

<!-- Admin Delete Product -->
<script src="adminJQueryDeleteItem.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
    $(".delbutton").click(function(){
        //Save the link in a variable called element
        var element = $(this);

        //Find the id of the link that was clicked
        var del_id = element.attr("id");
        var get_name = element.attr("name");

        //Built a url to send
        var info = 'productId=' + del_id;
        var info1 = 'adminName=' + get_name;
        if(confirm("Are You Sure Do You Want to Delete This Item Permanently?")) {
            $.ajax({
            type: "GET",
            url: "Admin Transaction without Design/adminDeleteItem.php?" + info + "&" + info1,
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
		$('#example1').DataTable({
			// Modified Version of Descending Order on Table
			"order": [[ 1, "desc" ]]
		});
	} );
	// END CODE FOR BASIC DATA TABLE 
	</script>

</body>
</html>