<html>
  <head>
<?php
	include('../ServerConnection/configConnectRecords.php');

	function check_internet_connection($checkHost = 'www.google.com') 
	{
		return (bool) @fsockopen($checkHost, 80, $iErrno, $sErrStr, 5);
	}

	$verifyConnection = check_internet_connection();
	$checkInternetConnection = ($verifyConnection) ? "" : "<h4 align = 'center' style = 'color: #ff5d48;'>You are not connected to the Internet!</h4>";

	$notify = "";
	$display = "<div id='piechart' style='width: 900px; height: 200px; top: -250px; left: -100px; position: relative;'></div>";

	$subTotalPercentFull = 0;
	$subTotalPercentModerate = 0;
	$subTotalPercentCritical = 0;

    $sql = "SELECT * FROM qrcodesStock
        INNER JOIN qrcodesProduct
        ON qrcodesStock.qrProductId = qrcodesProduct.qrProductId
        INNER JOIN qrcodesSupplier
        ON qrcodesProduct.qrSupplierId = qrcodesSupplier.qrSupplierId";
    $result = $db->prepare($sql);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){
        $partialNumerator = $row['qrStockQtyLeft'];
        $partialDenominator = $row['qrSupplierProductQtyDelivered'];
        $partialPercentage = ($partialNumerator / $partialDenominator) * 100;
		$nameProduct = $row['qrSupplierProductName'];
		$adminSetCritical = $row['qrStockCriticalLevelSet'];

		if ( ($partialPercentage <= 100) && ($partialPercentage > 80) ) {
			$subTotalPercentFull = $partialPercentage + $subTotalPercentFull;
		}

		elseif ( ($partialPercentage <= 80) && ($partialPercentage > $adminSetCritical) ) {
			$subTotalPercentModerate = $partialPercentage + $subTotalPercentModerate;
		}

		elseif ($partialPercentage <= $adminSetCritical) {
			$subTotalPercentCritical = $partialPercentage + $subTotalPercentCritical;
		}
    }

	if ($subTotalPercentFull == 0 && $subTotalPercentModerate == 0 && $subTotalPercentCritical == 0) {
		$notify = "<h4 align = 'center' style = 'color: #ff5d48;'>Sorry! No Any Product(s) has Yet been Registered on the System!</h4>";
		$display = "";
	}
?>
    <script type="text/javascript" src="adminPieChartLoader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', ''],
          ['Full',     <?php echo $subTotalPercentFull; ?>],
          ['Moderate',      <?php echo $subTotalPercentModerate; ?>],
          ['Critical',  <?php echo $subTotalPercentCritical; ?>]
        ]);

        var options = {
		  width: 900,
		  height: 500,
		  colors: ['#28a745', '#3db9dc', '#ff5d48']
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
	<?php echo $checkInternetConnection; ?>
  	<?php echo $notify; ?>
    <?php echo $display; ?>
  </body>
</html>