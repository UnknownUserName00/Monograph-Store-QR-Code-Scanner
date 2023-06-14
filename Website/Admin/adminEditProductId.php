<head>
  <link rel="shortcut icon" href="../icon.png">
  <meta name="viewport" content="width=device-width">
  <link href="akoto.css" rel="stylesheet" type="text/css" />
  <title>Admin Dashboard</title>
<style>
.wrapper {
  height: 600px;
}
</style>
</head>

<div class="wrapper">
<form action="Admin Transaction without Design/adminSaveEditProduct.php" method="post" autocomplete="off">
	<input type = "hidden" name = "productId" value = "<?php echo $productId = $_GET['productId']; ?>"/>
	<input type = "hidden" name = "adminName" value="<?php echo $adminName = $_GET['adminName']; ?>"/>

    <div class="form-header">Edit Product</div>
<?php
	session_start();

    $adminName = $_GET['adminName'];
    if ($_SESSION['SESS_FIRST_NAME'] != $adminName) {
		header("location: ../staffSignInForm.php");
		exit();
    }

	include "../ServerConnection/configConnectRecords.php";

	$sql = "SELECT * FROM qrcodesProduct
		INNER JOIN qrcodesSupplier
		ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId
        WHERE qrcodesProduct.qrProductId = '$productId'
        ORDER BY qrcodesSupplier.qrSupplierId DESC";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
?>
    <div class="form-grp">
        <label>Product Code</label>
        <input type="text" placeholder="Input Value" readonly style = "background-color: LightGray;" required value = "<?php
			echo $row['qrProductCode'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Product Cost</label>
        <input type="number" min = "1" placeholder="Input Value" name = "productCost" required value = "<?php
			echo $row['qrProductCost'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Product Price</label>
        <input type="number" min = "1" placeholder="Input Value" name = "productPrice" required value = "<?php
			echo $row['qrProductPrice'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Product Brand</label>
        <input type="text" placeholder="Input Value" readonly style = "background-color: LightGray;" required value = "<?php
			echo $row['qrSupplierBrandName'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Product Name</label>
        <input type="text" placeholder="Input Value" readonly style = "background-color: LightGray;" required value = "<?php
			echo $row['qrSupplierProductName'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Product Category</label>
        <input type="text" placeholder="Input Value" name = "productCategory" required value = "<?php
			echo $row['qrProductCategory'];
		?>"/>
    </div></br>
    <div class="form-grp">
        <center>
			<input type="submit" value="Save" class="form-grp">

			<a href = "Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminEditProductId.php&linkPage=adminProduct.php&adminName=<?php echo $adminName; ?>&notify">
				<input type="button" value="Close" id = "buttonDesign"/>
			</a>
		</center>
    </div>
<?php
	}
?>
</form>
</div>
