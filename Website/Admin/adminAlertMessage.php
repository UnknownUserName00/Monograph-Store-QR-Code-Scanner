<style>
.alertMessage {
    padding: 10px;
    color: white;
    opacity: 1;
    transition: opacity 0.6s;
    margin-bottom: 15px;
    width: 100%;

    font-weight: Bold;
}

.alertMessage:hover {
    opacity: .5;
}

.closebtnAlertMessage {
    margin-left: 15px;
    color: white;
    font-weight: bold;
    float: right;
    font-size: 25px;
    line-height: 20px;
    cursor: pointer;
    transition: 0.3s;
}

.closebtnAlertMessage:hover {
    color: black;
}
</style>
<?php
include "../ServerConnection/configConnectRecords.php";

$sql = "SELECT * FROM qrcodesSupplier
    INNER JOIN qrcodesProduct
    ON qrcodesSupplier.qrSupplierId = qrcodesProduct.qrProductId
    INNER JOIN qrcodesStock
    ON qrcodesProduct.qrProductId = qrcodesStock.qrStockId
    ORDER BY qrcodesSupplier.qrSupplierId DESC";
$result = $db->prepare($sql);
$result->execute();
for($i=0; $row = $result->fetch(); $i++){
    $brandName = $row['qrSupplierBrandName'];
    $productName = $row['qrSupplierProductName'];
    $productLeft = $row['qrStockQtyLeft'];
    $productDelivered = $row['qrSupplierProductQtyDelivered'];
	$productLimit = round( (($productLeft / $productDelivered) * 100) );
	$adminSetCritical = $row['qrStockCriticalLevelSet'];

    if ($productLimit <= $adminSetCritical) {
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
?>

<script>
var close = document.getElementsByClassName("closebtnAlertMessage");
var i;

for (i = 0; i < close.length; i++) {
    close[i].onclick = function(){
        var div = this.parentElement;
        div.style.opacity = "0";
        setTimeout(function(){ div.style.display = "none"; }, 600);
    }
}
</script>
