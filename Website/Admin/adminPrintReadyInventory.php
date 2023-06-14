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
<?php
	session_start();

    $adminName = $_GET['adminName'];
    if ($_SESSION['SESS_FIRST_NAME'] != $adminName) {
		header("location: ../staffSignInForm.php");
		exit();
    }
?>
</head>

<body class="adminbody" onLoad="self.print()">
<div id="main">
    <div>
	
		<!-- Start content -->
        <div class="content">
            
			<div class="container-fluid">
						<div class="mdl-color--white content-area ng-scope" ng-controller="SalesInvoiceCtrl">
							<div class="row">
								<div class="col-md-12">
									<div class="page-title"><i class="fa fa-table"></i> Inventory Report</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
                                    <hr/>
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
                                                <table class="table table-responsive-xl table-hover display" id="example1">
										<thead style="background-color:#17a2b8;color:white;">
                                                    <tr align = "center">
                                                        <th scope="col"> Product Brand </th>
                                                        <th scope="col"> Product Name </th>
                                                        <th scope="col"> Qty </th>
                                                        <th scope="col"> Storage Section </th>
                                                        <th scope="col"> Qty per Bundle </th>
                                                        <th scope="col"> Unit Field for Product </th>
                                                    </tr>
                                                    <?php
														include "../ServerConnection/configConnectRecords.php";

                                                        $sql = "SELECT * FROM qrcodesStock
                                                            INNER JOIN qrcodesProduct
                                                            ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
                                                            INNER JOIN qrcodesSupplier
                                                            ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId
                                                            ORDER BY qrcodesSupplier.qrSupplierId DESC";
                                                        $resultStocks = $db->prepare($sql);
                                                        $resultStocks->execute();
                                                        for($i=0; $rowStocks = $resultStocks->fetch(); $i++){
                                                            $productQty = $rowStocks['qrStockQtyLeft'] . "/" . $rowStocks['qrSupplierProductQtyDelivered'];
                                                    ?>
                                        </thead>
                                        <tbody>
                                                    <tr align = "center" class="record">
                                                        <td><?php echo $rowStocks['qrSupplierBrandName']; ?></td>
                                                        <td><?php echo $rowStocks['qrSupplierProductName']; ?></td>
                                                        <td><?php echo $productQty; ?></td>
                                                        <td><?php echo $rowStocks['qrStockStorageSection']; ?></td>
                                                        <td><?php echo $rowStocks['qrStockQtyPerBundle'] * 1; ?></td>
                                                        <td><?php echo $rowStocks['qrStockUnitField']; ?></td>
                                                    </tr>
                                                    <?php
                                                        }
                                                    ?>
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