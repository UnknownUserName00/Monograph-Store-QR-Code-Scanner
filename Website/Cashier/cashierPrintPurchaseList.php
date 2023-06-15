<html>
<link rel="shortcut icon" href="../icon.png">
<title>Cashier Dashboard</title>
<?php
    session_start();

    $cashierName = $_GET['cashierName'];
    if ($_SESSION['SESS_FIRST_NAME'] != $cashierName) {
        header("location: ../staffSignInForm.php");
        exit();
    }

    include('../ServerConnection/configConnectRecords.php');

    $transactNum=$_GET['transactNum'];
	$purchaseNum = 0;
	$purchaseLoop = 0;
	$sql = "SELECT * FROM qrcodesPurchaseItem
		INNER JOIN qrcodesSales
		ON qrcodesPurchaseItem.qrSalesTransactNum = qrcodesSales.qrSalesTransactNum
		INNER JOIN qrcodesStock
		ON qrcodesPurchaseItem.qrStockId = qrcodesStock.qrStockId
		INNER JOIN qrcodesProduct
		ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
		INNER JOIN qrcodesSupplier
		ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId
		WHERE qrcodesSales.qrSalesTransactNum = '$transactNum' ";
	$resultTransact = $db->prepare($sql);
	$resultTransact->execute();
	for($i=0; $row = $resultTransact->fetch(); $i++){
		$purchaseNum+=15;
		$purchaseLoop+=7;
	}
?>
<style>
// for Default Size of Paper in Printing Mode
@media print and (orientation: portrait) {
	@page {
		// size: 280mm 216mm;
		size: 10in 3.5in;
		page-break-inside: avoid;
		page-break-before: avoid;
		page-break-after: avoid;
	}
}

@media print and (orientation: landscape) {
	@page {
		// size: 216mm 280mm;
		size: 3.5in 10in;
	}
}

@media print {
	@page {
		margin-top: <?php echo -93 - $purchaseLoop; ?>%;
		margin-left: -115%;
		margin-right: 0%;
		margin-bottom: 0%;
	}

	#invoice-POS{
		transform: scale(.45, .45);
	}
}

#invoice-POS{
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:5mm;
  // margin: 0 auto;
  margin: 0 auto;
  width: 120mm;
  background: #FFF;
  height: <?php echo 170 + $purchaseNum; ?>mm;
  
  
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
h1{
  font-size: 1.5em;
  color: #222;
}
h3{font-size: .5em;}
h3{
  font-size: 1em;
  font-weight: 300;
  line-height: 2em;
}
p{
  font-size: .15em;
  color: #666;
  line-height: 1.2em;
}
 
#top, #mid,#bot{ /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}

#top{min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;}

#top .logo{
  //float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
	background-size: 60px 60px;
}
.clientlogo{
  float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
	background-size: 60px 60px;
  border-radius: 50px;
}
.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
}
td{
  //padding: 5px 0 5px 15px;
  //border: 1px solid #EEE
}
.tabletitle{
  //padding: 10px;
  font-size: 5em;
  background: #EEE;
}
.service{border-bottom: 1px solid #EEE;}
.item{width: 24mm;}
.itemtext{font-size: .5em;}

#legalcopy{
  margin-top: 5mm;
}

  
  
}
hr { 
  display: block;
  margin-top: 0.9em;
  margin-bottom: 0.5em;
  margin-left: auto;
  margin-right: auto;
  border-style: solid;
  border-width: 2px;
  
 
} 
.hr { 
  display: block;
  margin-top: 0.9em;
  margin-bottom: 0.5em;
  margin-left: auto;
  margin-right: auto;
  border-style: solid;
  border-width: 2px;
  
 
} 
.hr { 
  display: block;
  margin-top: 0.9em;
  margin-bottom: 0.5em;
  margin-left: auto;
  margin-right: auto;
  border-style: solid;
  border-width: 2px;
  
 
} 
</style>
<?php
	$sql = "SELECT * FROM qrcodesCashier
		WHERE qrCashierName = '$cashierName' ";
	$resultTransact = $db->prepare($sql);
	$resultTransact->execute();
	for($i=0; $row = $resultTransact->fetch(); $i++){
		$cashierFirstName = $row['qrCashierFirstName'];
	}
?>
<body onLoad="self.print()">
  <div id="invoice-POS">
    
    <center id="top">
      <div class="logo"></div>
      <div class="info"> 
        <h2>Mon.O.Graph</h2>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <div id="mid">
      <div class="info">
      <center>  <p> 
            Transport Hub Lancaster New City</br>
            VAT Reg TIN:000-388-771-379</br>
            
        </p></center>
      </div>
    </div><!--End Invoice Mid-->
<hr/>
					<div id="table">
						<table cellspacing = "10">
							<tr class="tabletitle" align = "center">
                                <td><h3>Brand</h3></td>
								<td class="item"><h3>Item</h3></td>
								<td class="Hours"><h3>Qty</h3></td>
								<td class="piece" style = "width: 20%;"><h3>Price</h1></td>
								<td class="Rate"><h3>Sub Total</h3></td>
							</tr>

	<?php
		$sql = "SELECT * FROM qrcodesSales
			WHERE qrSalesTransactNum = '$transactNum' ";
		$resultTransact = $db->prepare($sql);
		$resultTransact->execute();
		for($i=0; $row = $resultTransact->fetch(); $i++){
			$tenderCash = $row['qrSalesTenderCash'];
			$amountChange = $row['qrSalesAmountChange'];
		}

		$sumItemPriceAvail = 0;
		$stringSumItemPriceAvail = "";
        $salesTaxAmount = 0;
        $salesAmount = 0;

		$sql = "SELECT * FROM qrcodesPurchaseItem
			INNER JOIN qrcodesSales
			ON qrcodesPurchaseItem.qrSalesTransactNum = qrcodesSales.qrSalesTransactNum
			INNER JOIN qrcodesStock
			ON qrcodesPurchaseItem.qrStockId = qrcodesStock.qrStockId
			INNER JOIN qrcodesProduct
			ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
			INNER JOIN qrcodesSupplier
			ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId
			WHERE qrcodesSales.qrSalesTransactNum = '$transactNum' ";
		$resultTransact = $db->prepare($sql);
		$resultTransact->execute();
		for($i=0; $row = $resultTransact->fetch(); $i++){
			$brandName = $row['qrSupplierBrandName'];
			$productName = $row['qrSupplierProductName'];
			$qtyAvail = $row['qrPurchaseItemQtyAvail'];
			$productPrice = number_format( $row['qrProductPrice'], 2, '.', '');
			$subTotal = number_format( ($qtyAvail * $productPrice) , 2, '.', '');
			$salesTaxAmount = $row['qrSalesTaxAmount'];
			$salesAmount = $row['qrSalesAmount'];
	?>
							<tr class="service" align = "center">
								<td class="tableitem"><p class="itemtext"><?php echo $brandName; ?></p></td>
								<td class="tableitem"><p class="itemtext"><?php echo $productName; ?></p></td>
								<td class="tableitem"><p class="itemtext"><?php echo $qtyAvail; ?></p></td>
								<td class="tableitem"><p class="itemtext" style = "white-space: pre;"><?php echo "Php " . $productPrice; ?></p></td>
								<td class="tableitem"><p class="itemtext" style = "white-space: pre;"><?php echo "Php " . $subTotal; ?></p></td>
							</tr>
	<?php
		}

        if ($salesTaxAmount == "" && $salesAmount == "") {
            $salesTaxAmount = number_format(0, 2, '.', '');
            $salesAmount = number_format(0, 2, '.', '');
        }
	?>
							<tr class="service" align = "center">
								<td class="tableitem"><p class="itemtext">Tax&nbsp;12%</p></td>
								<td class="tableitem" colspan = "3"></td>
								<td class="tableitem"><p class="itemtext"><?php echo "Php " . $salesTaxAmount; ?></p></td>
							</tr>

							<tr class="tabletitle">
								<td class="Rate" colspan = "5" align = "right">Amount Due: <?php echo "Php " . $salesAmount; ?></td></br>
							</tr>
							
							<tr class="tabletitle">
								<td class="Rate" colspan = "5" align = "right">Cash: Php <?php echo $tenderCash; ?></td></br>
							</tr>
                            <tr class="tabletitle">
								<td class="Rate" colspan = "5" align = "right">Change: Php <?php echo $amountChange; ?></td></br>
							</tr>
						

						</table>
					</div><!--End Table-->
					<hr class="hr">
					<div id="legalcopy">
		<?php
			$sql = "SELECT * FROM qrcodesSales
				WHERE qrSalesTransactNum = '$transactNum' ";
			$resultTransact = $db->prepare($sql);
			$resultTransact->execute();
			for($i=0; $row = $resultTransact->fetch(); $i++){
		?>
						<p class="legal"><strong>OR Number:</strong> <?php echo $transactNumString = $row['qrSalesTransactString']; ?></p>
		<?php
			}
		?>
						<p class = "legal"><strong>Cashier Name: </strong><?php echo $cashierFirstName; ?></p>
					</div>
<hr class="hr">
				
				<div id="legalcopy">
				<table>
					<tr>
						<td>
							<img src="../Admin/Generate QR Codes/generate.php?text=<?php echo htmlentities($transactNumString); ?>" style = "height: 80px; width: 80px;" alt="">
						</td>
						<td>
							<p class="legal"><strong>Thank you for your business!</strong>  Payment is expected within 31 days; please process this invoice within that time. There will be a 5% interest charge per month on late invoices.</p>
						</td>
				</table>
					</div></div><!--End InvoiceBot-->
  </div><!--End Invoice-->
 </body>
