<html>
<head>
    <link rel="shortcut icon" href="../icon.png">
    <link rel="stylesheet" href="bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="ui-bootstrap-csp.css">
    <link rel="stylesheet" href="dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="POS.css">

    <link rel="stylesheet" href="daterangepicker.min.css">
    <link rel="stylesheet" type="text/css" href="sweetalert.min.css">
    <link rel="stylesheet" type="text/css" href="jquery.jgrowl.min.css">

    <?php
        session_start();

        $cashierName = $_GET['cashierName'];
        if ($_SESSION['SESS_FIRST_NAME'] != $cashierName) {
            header("location: ../staffSignInForm.php");
            exit();
        }

		if ($_GET['beginBalance'] != 0) {
			$_SESSION['beginBalance'] = $_GET['beginBalance'];
			$_GET['beginBalance'] = $_SESSION['beginBalance'];
		}

		if ($_SESSION['beginBalance'] != 0) {
			$_GET['beginBalance'] = $_SESSION['beginBalance'];
		}
    ?>

    <script src="accounting.min.js"></script>
    <script src="jquery.min.js"></script>

		<!-- Font Awesome CSS -->
		<link href="../Admin/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<style>
.wrapper {
    display: flex;
    align-items: stretch;
}
#sidebar {
    font-size: 13px;
    min-width: 250px;
    max-width: 250px;
    background: #002837;
    color: #fff;
    transition: all 0.3s;
}
nav {
	display:block;
}
.sidebar-header {
    padding: 20px;
    background: #002837;
}
*, ::after, ::before {
    box-sizing: border-box;
}
a {
    color: inherit;
    text-decoration: none;
    transition: all 0.3s;
}
ul {
    margin-top: 0;
    margin-bottom: 1rem;
}
ul, menu, dir {
    display: block;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
}
#sidebar ul.components {
    padding: 2px 0;
    border-bottom: 1px solid #3973ac;
}
#sidebar ul li.active > a, #sidebar a[aria-expanded="true"] {
    color: #fff;
    background: #3973ac;
}
#sidebar ul li a {
    padding: 10px;
    font-size: 1.1em;
    display: block;
}
.fa-fw {
    width: 1.28571429em;
    text-align: center;
}
.fa {
    display: inline-block;
    font: normal normal normal 14px/1 FontAwesome;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
}
.list-unstyled {
    padding-left: 0;
    list-style: none;
}
#sidebar ul.components .fa {
    margin-right: 10px;
}
.collapse {
    display: none;
}
#content {
    min-height: 100vh;
    transition: all 0.3s;
    width: 100% !important;
}
.container-fluid {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
#top-bar {
    background-color: #fff;
    height: 50px;
    border-radius: 2px;
    border: 0;
    box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.12), 0 1px 6px 0 rgba(0, 0, 0, 0.12);
}
.pull-left {
    float: left;
}
#sidebarCollapse {
    margin-top: 5px;
}
button, html [type=button] {
    -webkit-appearance: button;
}
.pull-right {
    float: right;
}
.btn-group {
	position: relative;
	display: inline-flex;
    vertical-align: middle;
}
.btn-group>.btn:first-child {
    margin-left: 0;
}
@media (min-width: 768px)
.col-md-12 {
    -webkit-box-flex: 0;
    flex: 0 0 100%;
    max-width: 100%;
}
.col-md-12{
    position: relative;
    width: 100%;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
.col-md-8{
    position: relative;
    width: 75%;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
body {
    margin: 0;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
}
.content-area {
    margin-top: 20px;
    padding: 20px;
    border-color: #149077;
    margin-bottom: 22px;
    background-color: #fff;
}

:root {
    --blue: #007bff;
    --indigo: #6610f2;
    --purple: #6f42c1;
    --pink: #e83e8c;
    --red: #dc3545;
    --orange: #fd7e14;
    --yellow: #ffc107;
    --green: #28a745;
    --teal: #20c997;
    --cyan: #17a2b8;
    --white: #fff;
    --gray: #6c757d;
    --gray-dark: #343a40;
    --primary: #007bff;
    --secondary: #6c757d;
    --success: #28a745;
    --info: #17a2b8;
    --warning: #ffc107;
    --danger: #dc3545;
    --light: #f8f9fa;
    --dark: #343a40;
    --breakpoint-xs: 0;
    --breakpoint-sm: 576px;
    --breakpoint-md: 768px;
    --breakpoint-lg: 992px;
    --breakpoint-xl: 1200px;
    --font-family-sans-serif: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
--font-family-monospace: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;}


.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.table tbody td {
    padding: 6px !important;
    line-height: 1.3 !important;
}
.form-group {
    margin-bottom: 1rem;
}
[uib-typeahead-popup].dropdown-menu {
    display: block;
}
dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    float: left;
    min-width: 10rem;
    padding: .5rem 0;
    margin: .125rem 0 0;
    font-size: 1rem;
    color: #212529;
    text-align: left;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: .25rem;
}
form {
    margin-bottom: 1em;
}
.container {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}
table {
    border-collapse: collapse;
}
table {
    display: table;
    border-spacing: 2px;
    border-color: grey;
}
.table thead, .table tbody {
    font-size: 14px;
}
thead {
    display: table-header-group;
    vertical-align: middle;
    border-color: inherit;
}
.d-flex {
    display: flex!important;
}
tr {
    display: table-row;
    vertical-align: inherit;
    border-color: inherit;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table td, .table th {
    padding: .75rem;
    border-top: 1px solid #dee2e6;
}
.col-5 {
    -webkit-box-flex: 0;
   flex: 0 0 41.666667%;
    max-width: 41.666667%;
}
.col-5 {
    position: relative;
    width: 100%;
    min-height: 1px;
}
th {
    text-align: inherit;
	    font-weight: bold;
		display: table-cell;
}
.col-2 {
    -webkit-box-flex: 0;
       flex: 0 0 16.666667%;
    max-width: 16.666667%;
	position: relative;
    width: 100%;
    min-height: 1px;
	
}
.text-right {
    text-align: right!important;
}
tbody {
    display: table-row-group;
    vertical-align: middle;
    border-color: inherit;
	border-spacing: 2px;
}
@media (min-width: 768px)
.col-md-4 {
    -webkit-box-flex: 0;
    flex: 0 0 25%;
    max-width: 25.333333%;
	position: relative;
    width: 100%;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}

.input-group {
   position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-align: stretch;
    -ms-flex-align: stretch;
    align-items: stretch;
    width: 100%;
}
.ng-hide:not(.ng-hide-animate) {
    display: none !important;
}
element.style {
    border-radius: 0 !important;
}
.btn {
    cursor: pointer !important;
}
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}
.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
	margin: 0;
    font-family: inherit;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
button {
    -webkit-appearance: button;
}
.fa {
    display: inline-block;
    font: normal normal normal 14px/1 FontAwesome;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
}
.table tbody td {
    padding: 6px !important;
    line-height: 1.3 !important;
}
.modal {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    display: none;
    overflow: hidden;
    outline: 0;
}
.fade {
    opacity: 0;
    transition: opacity .15s linear;
}
@media (min-width: 576px)
.modal-dialog {
    max-width: 500px;
    margin: 1.75rem auto;
}
.modal-dialog {
    position: relative;
    width: auto;
    pointer-events: none;
}
.modal.fade .modal-dialog {
    transition: transform .3s ease-out,-webkit-transform .3s ease-out;
    transform: translate(0,-25%);
}
.modal-content {
    position: relative;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.2);
    border-radius: .3rem;
    outline: 0;
}
.modal-header {
    display: flex;
    -webkit-box-align: start;
    align-items: flex-start;
    -webkit-box-pack: justify;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    border-top-left-radius: .3rem;
    border-top-right-radius: .3rem;
}
.modal-body {
    position: relative;
    -webkit-box-flex: 1;
    flex: 1 1 auto;
    padding: 1rem;
}
close:not(:disabled):not(.disabled) {
    cursor: pointer;
}
.modal-header .close {
    padding: 1rem;
    margin: -1rem -1rem -1rem auto;
}
button.close {
    background-color: transparent;
    border: 0;
    -webkit-appearance: none;
}
.close {
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
}
h5 {
    margin-bottom: .5rem;
    font-family: inherit;
    font-weight: 500;
    line-height: 1.2;
    color: inherit;
	    margin-top: 0;
		    display: block;
	    margin-block-start: 1.67em;
    margin-block-end: 1.67em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
}
.modal-footer {
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: end;
    justify-content: flex-end;
    padding: 1rem;
    border-top: 1px solid #e9ecef;
}
script {
    display: none;
}
button, html [type=button] {
    -webkit-appearance: button;
}
.btn-group-lg>.btn, .btn-lg {
    padding: .5rem 1rem;
    font-size: 1.25rem;
    line-height: 1.5;
    border-radius: .3rem;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.panel {
    margin-bottom: 20px;
    background-color: #fff;
}
.panel {
    border-radius: 2px;
    border: 0;
    box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.12), 0 1px 6px 0 rgba(0, 0, 0, 0.12);
}
.d-flex {
	display: flex!important;
}
</style>

<script>

    $(function(){

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });


        var urlString = "point-of-sale";
        var pathArray = urlString.split( '/' );

        var activeElement = $('.navbar-nav').find('.active').removeClass('active');


        if(pathArray[0] == 'items')
        {

            $("#items-menu").addClass('active');
        }
        else if(urlString =='sales/invoice/create')
        {
            $("#point-of-sale-menu").addClass('active');
        }
        else if(pathArray[0] == 'sales')
        {
            $("#sales-menu").addClass('active');
        }

        else if(pathArray[0] == 'expenses')
        {
            $("#expense-menu").addClass('active');
        }

        else if(pathArray[0] == 'report')
        {
            $("#reports-menu").addClass('active');
        }
        else if(pathArray[0] == 'settings' || pathArray[0] == 'users')
        {
            $("#settings-menu").addClass('active');
        }
        else
        {
            $("#home-menu").addClass('active');
        }
    });

</script>
<script>
	// for Opening and Closing Submenu on One Button
	function openMenu() {
		open = document.getElementsByClassName("dropdown-menu");
		for (i = 0; i < open.length; i++) {
			open[i].style.display = "block";
		}
	}

	function closeMenu() {
		close = document.getElementsByClassName("dropdown-menu");
		for (i = 0; i < close.length; i++) {
			open[i].style.display = "none";
		}
	}

	var flag = false;
	function flip() {
		flag = !flag;

		if (flag == true) {
			openMenu();
		}

		else {
			closeMenu();
		}

		return flag;
	}
</script>
<title>Cashier Dashboard</title>
</head>
<body>
<?php
	include "../ServerConnection/configConnectRecords.php";

	$date = new DateTime();
	$date->add(new DateInterval('PT6H'));
	$fullDateFormat = $date->format('Y-m-d');
	$timeFormat = $date->format('h:i A');

	$transactNum = $_GET['transactNum'];
	$beginBalance = $_GET['beginBalance'];

	$sql = "SELECT * FROM qrcodesCashier
		WHERE qrCashierName = '$cashierName' ";
	$findCashierName = $db->prepare($sql);
	$findCashierName->execute();
	for($i=0; $rowCashierName = $findCashierName->fetch(); $i++){
		$cashierFullName = $rowCashierName['qrCashierFirstName'] . " " . $rowCashierName['qrCashierLastName'];
	}
?>
<div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <a>
                    <h4 align = "center">Cashier </h4>
                </a>
                <div style="height: 1px; width: 100%; border-bottom: 1px solid #fff; margin-top: 5px;"></div>
            </div>
			<?php
				$cashierPOS = "";
				$salesReceipt = "";
				$returnItem = "";

                $sql = "SELECT SUM(qrSalesAmount) FROM qrcodesSales
					WHERE qrSalesDate = '$fullDateFormat' ";
				$resultSalesReport = $db->prepare($sql);
				$resultSalesReport->execute();
				for($i=0; $rowSalesReport = $resultSalesReport->fetch(); $i++){
                    $salesAmount = $rowSalesReport['SUM(qrSalesAmount)'];
                }

				if ($salesAmount == "") {
					$salesAmount = number_format(0, 2, '.', '');
				}

				$subTotalReturn = 0;
				$amountRemit = number_format(0, 2, '.', '');

				$pettyCash = $salesAmount + $beginBalance;

				$sql = "SELECT * FROM qrcodesSales
					INNER JOIN qrcodesReturnItem
					ON qrcodesSales.qrSalesTransactNum = qrcodesReturnItem.qrSalesTransactNum
					INNER JOIN qrcodesStock
					ON qrcodesReturnItem.qrStockId = qrcodesStock.qrStockId
					INNER JOIN qrcodesProduct
					ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
					WHERE qrcodesSales.qrSalesDate = '$fullDateFormat'";
				$resultSalesReport = $db->prepare($sql);
				$resultSalesReport->execute();
				for($i=0; $rowSalesReport = $resultSalesReport->fetch(); $i++){
					$productQtyReturn = $rowSalesReport['qrReturnItemQtyAvail'];
					$productPrice = $rowSalesReport['qrProductPrice'];
					$subTotalReturn = ($productQtyReturn * $productPrice) + $subTotalReturn;
				}

				if ($subTotalReturn == "") {
					$subTotalReturn = 0;
				}

				$amountRemit = ($salesAmount + $beginBalance) - $subTotalReturn;

				if ( ($beginBalance != 0) && ($amountRemit > 0) ) {
					$cashierPOS = "Cashier Transaction without Design/cashierProcessAuditTrail.php?pageOrigin=cashierPettycash.php&linkPage=cashierPOS.php&transactNum&notify&cashierName=" . $cashierName . "&beginBalance=" . $beginBalance;
					$salesReceipt = "Cashier Transaction without Design/cashierProcessAuditTrail.php?pageOrigin=cashierPettycash.php&linkPage=cashierSalesReceipt.php&transactNum&cashierName=" . $cashierName . "&beginBalance=" . $beginBalance;
					$returnItem = "Cashier Transaction without Design/cashierProcessAuditTrail.php?pageOrigin=cashierPettycash.php&linkPage=cashierReturnItem.php&transactNum&cashierName=" . $cashierName . "&beginBalance=" . $beginBalance . "&transactNumString";
				}
			?>
            <ul class="list-unstyled components">
                <li class="">
                    <a href="<?php echo $cashierPOS; ?>"><i class="fa fa-star-o fa-fw"></i> Point of Sale</a>
                </li>
                <li class="">
                    <a href="<?php echo $salesReceipt; ?>"><i class="fa fa-balance-scale fa-fw menu-icon"></i> Sales Receipts</a>
                </li>
                <li class="active">
                    <a href="Cashier Transaction without Design/cashierProcessAuditTrail.php?pageOrigin=cashierPettycash.php&linkPage=cashierPettycash.php&transactNum&cashierName=<?php echo $cashierName; ?>&beginBalance=<?php echo $beginBalance; ?>"><i class="fa fa-money fa-fw menu-icon"></i> Check Cash Drawer</a>
                </li>
                <li class="">
					<a href = "<?php echo $returnItem; ?>"><i class = "fa fa-tachometer fa-fw"></i> Return Item</a>
				</li>
			</ul>

        </nav>
        <!-- Page Content Holder -->
        <div id="content">
            <div class="container-fluid">
                <div class="row" id="top-bar">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <button type="button" id="sidebarCollapse" class="btn btn-default navbar-btn">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>

                        <div class="pull-right">
                            <div class="btn-group" style="margin-top: 6px;">
                                <button type="button" onclick = "flip()" class="btn  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user-circle" aria-hidden="true"></i> <?php echo $cashierFullName; ?>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="Cashier Transaction without Design/cashierSignOut.php?cashierName=<?php echo $cashierName; ?>"> Sign Out</a>
                                </div>
                            </div>
                        </div>


                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
<span id = "printPettyCash">
    <div class="mdl-color--white content-area">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <div class="page-title">Check Cash Drawer</div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <hr>
                <div class="row">
                    <div class="col-md-12">
                    </div>

                    <div class="col-md-6">
						<?php
                            if ( isset($_GET['transactionDate']) ) {
                                $fullDateFormat = $_GET['transactionDate'];
                            }

                            else {
								$date = new DateTime();
								$date->add(new DateInterval('PT6H'));
								$fullDateFormat = $date->format('Y-m-d');
								$timeFormat = $date->format('h:i A');
                            }

                            $sql = "SELECT SUM(qrSalesAmount) FROM qrcodesSales
								WHERE qrSalesDate = '$fullDateFormat' ";
							$resultSalesReport = $db->prepare($sql);
							$resultSalesReport->execute();
							for($i=0; $rowSalesReport = $resultSalesReport->fetch(); $i++){
                                $salesAmount = $rowSalesReport['SUM(qrSalesAmount)'];
                            }

							if ($salesAmount == "") {
								$salesAmount = number_format(0, 2, '.', '');
							}

							$subTotalReturn = 0;
							$amountRemit = number_format(0, 2, '.', '');
							if ( isset($_GET['beginBalance'])  ) {
								$beginBalance = $_GET['beginBalance'];
								$_SESSION['beginBalance'] = $beginBalance;

								$cashierName = $_GET['cashierName'];
								$transactNum = $_GET['transactNum'];

								$pettyCash = $salesAmount + $beginBalance;

								if ($_GET['beginBalance'] != 0) {
									$sql = "INSERT INTO qrcodesBalance (qrBalanceBegin, qrBalanceDate)
										VALUES ('$beginBalance', '$fullDateFormat')";
									$resultSales = $db->prepare($sql);
									$resultSales->execute();
								}

								$sql = "SELECT * FROM qrcodesSales
									INNER JOIN qrcodesReturnItem
									ON qrcodesSales.qrSalesTransactNum = qrcodesReturnItem.qrSalesTransactNum
									INNER JOIN qrcodesStock
									ON qrcodesReturnItem.qrStockId = qrcodesStock.qrStockId
									INNER JOIN qrcodesProduct
									ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
									WHERE qrcodesSales.qrSalesDate = '$fullDateFormat'";
								$resultSalesReport = $db->prepare($sql);
								$resultSalesReport->execute();
								for($i=0; $rowSalesReport = $resultSalesReport->fetch(); $i++){
									$productQtyReturn = $rowSalesReport['qrReturnItemQtyAvail'];
									$productPrice = $rowSalesReport['qrProductPrice'];
									$subTotalReturn = ($productQtyReturn * $productPrice) + $subTotalReturn;
								}

								if ($subTotalReturn == "") {
									$subTotalReturn = 0;
								}

								$amountRemit = ($salesAmount + $beginBalance) - $subTotalReturn;
								$drawerAmount = $amountRemit;

								if ( ($beginBalance != 0) && ($amountRemit != (abs($amountRemit) * -1)) ) {
									$restriction = "readonly";
                                    $fieldRequired = "";
									$submitButton = "";
								}

								else {
									$restriction = "required";
                                    $fieldRequired = "*";
									$submitButton = "<button type='submit' name = 'RemitCash' class='btn btn-primary' style='background-color:#17a2b8;' ><i class='fa fa-paper-plane' aria-hidden='true'></i> Submit</button>&nbsp;&nbsp;&nbsp;&nbsp;";;
								}
							}
						?>
                        <form method="get" class="ng-pristine ng-valid">
							<input type="hidden" name="cashierName" value = "<?php echo $cashierName; ?>"/>
							<input type="hidden" name="transactNum" value = "<?php echo $transactNum; ?>"/>

                            <input type="hidden" name="_token" value="lOmhTu5neHzLuknmcMBFjZNMeUyeOoicOMh7Lo1g" autocomplete="off">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Date <span class="required"></span></label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control datepicker form-control-sm" readonly name="transactionDate" <?php echo $restriction; ?> value="<?php
                                    if ( isset($_GET['RemitCash']) ) {
                                        echo $fullDateFormat = $_GET['transactionDate'];
                                    }

                                    else {
                                        echo $fullDateFormat;
                                    }
                                    ?>" required >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Sales Amount</label>
                                <div class="col-md-8">
                                    <?php echo "Php " . $salesAmount; ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Beginning Balance <span class="required"><?php echo $fieldRequired; ?></span></label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control form-control-sm" name="beginBalance" <?php echo $restriction; ?> placeholder = "0" min = "1" value="<?php
										if ( isset($_GET['RemitCash']) ) {
											echo $beginBalance = number_format($_GET['beginBalance'], 2, '.', '');
										}

										elseif ($beginBalance != 0) {
											echo $beginBalance = number_format($_GET['beginBalance'], 2, '.', '');
										}

										else {
											number_format($beginBalance, 2, '.', '');
										}
									?>">
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Returned Items</label>
                                <div class="col-md-8"><?php echo "Php " . number_format($subTotalReturn, 2, '.', ''); ?></div>
                            </div>



                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Amount Remit</label>
                                <div class="col-md-8"><?php echo "Php " . number_format($amountRemit, 2, '.', ''); ?></div>
                            </div>


									<?php
										$cashierName = $_GET['cashierName'];
										$transactNum = $_GET['transactNum'];

                                        if ( ($amountRemit <= 0) || $beginBalance == 0 || $drawerAmount == 0) {
                                            $notify = "<div style = 'color: Red;'>Print Restricted</div>";
                                            $printCashDrawer = "";
                                        }

                                        elseif ( ($beginBalance != 0) && ($amountRemit > 0) ) {
                                            $notify =  "<div style = 'color: Green;'>Print Allowed</div>";
                                            $printCashDrawer = "Cashier Transaction without Design/cashierRemitProcess.php?transactNum=" . $transactNum . "&cashierName=" . $cashierName . "&transactionDate=" . $fullDateFormat . "&salesAmount=" . $salesAmount . "&beginBalance=" . $beginBalance . "&drawerAmount=" . $drawerAmount . "&amountRemit=" . $amountRemit;
                                        }
									?>
                            <div class="form-group row">
                                <div class="offset-md-4 col-md-8">
									<table>
										<tr align = "center">
											<td colspan = "2"><?php echo $notify; ?></td>
										</tr>
										<tr>
											<td>
												<?php echo $submitButton; ?>
											</td>
											<td><button class="btn btn-primary" style="background-color:#17a2b8;"><a href = "<?php echo $printCashDrawer; ?>" >Print Page</a></button></td>
										</tr>
									</table>
                                </div>
                            </div>
                        </form>





                    </div>

                    <div class="col-md-6">


                                            </div>
                </div>
            </div>
        </div>
    </div>

</span>
                    </div>
                </div>
            </div
        </div>
    </div>
</body>
</html>