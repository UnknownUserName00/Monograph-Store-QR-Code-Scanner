<?php
	include "../ServerConnection/configConnectRecords.php";

	$date = new DateTime();
	$date->add(new DateInterval('PT6H'));
	$fullDateFormat = $date->format('Y-m-d');
	$timeFormat = $date->format('h:i A');

	function check_internet_connection1($checkHost1 = 'www.google.com') 
	{
		return (bool) @fsockopen($checkHost1, 80, $iErrno, $sErrStr, 5);
	}

	$verifyConnection1 = check_internet_connection1();
	$checkInternetConnection1 = ($verifyConnection1) ? "" : "<h4 align = 'center' style = 'color: #ff5d48;'>You are not connected to the Internet!</h4>";

												$display = "";
												$totalSales = 0;
												$profit = 0;
												$transactPreviousValues = "";
												$verifyMonth = substr($fullDateFormat, 5, 2);
                                                $sql = "SELECT *, SUM(qrSalesAmount), SUM(qrBalanceBegin) FROM qrcodesSales
													INNER JOIN qrcodesBalance
													ON qrcodesSales.qrSalesTransactNum = qrcodesBalance.qrBalanceId
                                                    WHERE qrcodesSales.qrSalesDate LIKE '%-$verifyMonth-%' AND qrcodesSales.qrSalesTenderCash != 0 AND qrcodesSales.qrSalesAmountChange != -qrcodesSales.qrSalesAmountChange
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
                                                            WHERE qrcodesSales.qrSalesDate = '$getDate' AND qrcodesSales.qrSalesTenderCash != 0 AND qrcodesSales.qrSalesAmountChange != -qrcodesSales.qrSalesAmountChange";
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
														$display = $display . "['" . $monthFormat . "', " . $profit . ", 'SkyBlue'],";
												}

												if ($totalSales == "") {
													$totalSales = number_format(0, 2, '.', '');
												}

												if ($profit == "") {
													$profit = number_format(0, 2, '.', '');
												}

												if ($display == "") {
													$display = "['Empty', 0, 'SkyBlue'],";
												}
?>
	<script type="text/javascript" src="adminBarGraphLoader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["", "", { role: "style" } ],
		<?php echo $display; ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
		  isStacked: true,
        width: 600,
        height: 400,
        bar: {groupWidth: "50%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
  }
  </script>
	<?php echo $checkInternetConnection1; ?>
<div id="columnchart_values" style="width: 900px; height: 300px; top: -200px; left: -50px; position: relative;"></div>