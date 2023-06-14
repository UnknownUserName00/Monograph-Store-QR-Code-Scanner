<head>
  <link rel="shortcut icon" href="../icon.png">
  <meta name="viewport" content="width=device-width">
  <link href="akoto.css" rel="stylesheet" type="text/css" />
  <title>Admin Dashboard</title>
<style>
.wrapper {
  height: 870px;
}
</style>
</head>

<div class="wrapper">
<form action="Admin Transaction without Design/adminSaveEditSupplier.php" method="post" autocomplete="off">
	<input type = "hidden" name = "adminName" value="<?php echo $adminName = $_GET['adminName']; ?>"/>
	<input type="hidden" name="supplierId" value="<?php echo $supplierId = $_GET['supplierId']; ?>" />

    <div class="form-header">Edit Supplier</div>
<?php
	session_start();

    $adminName = $_GET['adminName'];
    if ($_SESSION['SESS_FIRST_NAME'] != $adminName) {
		header("location: ../staffSignInForm.php");
		exit();
    }

	include "../ServerConnection/configConnectRecords.php";

    $sql = "SELECT * FROM qrcodesSupplier
        WHERE qrSupplierId = '$supplierId' ";
	$result = $db->prepare($sql);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
?>
    <div class="form-grp">
        <label>Product Brand</label>
        <input type="text" placeholder="Input Value" name = "productBrand" required value = "<?php
			echo $row['qrSupplierBrandName'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Product Name</label>
        <input type="text" placeholder="Input Value" name = "productName" required value = "<?php
			echo $row['qrSupplierProductName'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Product Qty Delivered</label>
        <input type="number" min = "1" placeholder="Input Value" name = "productQtyDelivered" required value = "<?php
			echo $row['qrSupplierProductQtyDelivered'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Expiry Date</label>
        <input type="date" placeholder="Input Value" name = "productDateExpired" value = "<?php
			echo $row['qrSupplierProductDateExpired'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Supplier Name</label>
        <input type="text" placeholder="Input Value" name = "supplierName" required value = "<?php
			echo $row['qrSupplierName'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Supplier Address</label>
        <input type="text" placeholder="Input Value" name = "supplierAddress" required value = "<?php
			echo $row['qrSupplierAddress'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Contact Number</label>
        <input type="number" placeholder="Input Value" name = "contactNum" required value = "<?php
			echo $row['qrSupplierContactNum'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Contact Person</label>
        <input type="text" placeholder="Input Value" name = "contactPerson" required value = "<?php
			echo $row['qrSupplierContactPerson'];
		?>"/>
    </div>
    <div class="form-grp">
        <label>Time Delivered</label>
        <input type="time" placeholder="Input Value" name = "timeDelivered" required value = "<?php
			$getTime = $row['qrSupplierTimeDelivered'];
			$hourTime = substr($getTime, 0, 2);
			$minuteTime = substr($getTime, 3, 4);
			$timeFix = substr($getTime, 6, 7);

			$timeDisplay = ($timeFix == "AM") ? $getTime : ($hourTime + 12 . ":" . $minuteTime);
			echo substr($timeDisplay, 0, 5);
		?>"/>
    </div>
    <div class="form-grp">
        <label>Date Delivered</label>
        <input type="date" name = "dateDelivered" required value = "<?php
			echo $row['qrSupplierDateDelivered'];
		?>">
    </div></br>
    <div class="form-grp">
        <center>
			<input type="submit" value="Save" class="form-grp">
			<a href = "Admin Transaction without Design/adminProcessAuditTrail.php?pageOrigin=adminEditSupplierId.php&linkPage=adminSupply.php&adminName=<?php echo $adminName; ?>&notify">
				<input type="button" value="Close" id = "buttonDesign"/>
			</a>
		</center>
    </div>
<?php
	}
?>
</form>
</div>
