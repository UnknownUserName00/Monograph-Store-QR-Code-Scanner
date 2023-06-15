<html>
<head>
    <link rel="shortcut icon" href="../icon.png">
	<title>Cashier Dashboard</title>
    <?php
        session_start();

        $cashierName = $_GET['cashierName'];
        if ($_SESSION['SESS_FIRST_NAME'] != $cashierName) {
            header("location: ../staffSignInForm.php");
            exit();
        }
    ?>
</head>
<body onLoad="self.print()">
    <link rel="stylesheet" type="text/css" href="cashdrawer.css">
    <div id="invoice-POS">

    <center id="top">
        <div class="logo"></div>
        <div class="info"> 
            <h2> MONOGRAPH</h2>
        </div><!--End Info-->
    </center><!--End InvoiceTop-->

    <center>
        <div id="mid">
            <div class="info">
                <h4>Contact Info</h4>
                <p>Address :Transport Hub Lancaster New City 
</br>
                Phone: (046)446-8765</br>
                </p>
            </div>
        </div>
    </center><!--End Invoice Mid-->

    <div id="bot">
        <div id="table">
            <table>
                <h2> Check Cash Drawer</h2>
                <?php
					include "../ServerConnection/configConnectRecords.php";

                    $transactNum = $_GET['transactNum'];

                    $transactionDate = $_GET['transactionDate'];
					$salesAmount = $_GET['salesAmount'];
					$beginBalance = number_format($_GET['beginBalance'], 2, '.', '');
					$drawerAmount = number_format($_GET['drawerAmount'], 2, '.', '');
					$amountRemit = number_format($_GET['amountRemit'], 2, '.', '');
				?>
                <tr class="service">
                    <td class="tableitem"><p class="itemtext">Date: <?php echo $transactionDate; ?></p></td>
                </tr>
                <tr class="service">
                    <td class="tableitem"><p class="itemtext">Sales Amount: <?php echo "Php " . $salesAmount; ?></p></td>
                </tr>
                <tr class="service">
                    <td class="tableitem"><p class="itemtext">Beginning Balance: <?php echo "Php " . $beginBalance; ?></p></td>
                </tr>
                <tr class="service">
                    <td class="tableitem"><p class="itemtext">Amount in Drawer: <?php echo "Php " . $drawerAmount; ?></p></td>
                </tr>
                <tr class="service">
                    <td class="tableitem"><p class="itemtext">Amount Remit: <?php echo "Php " . $amountRemit; ?></p></td>
                </tr>
                <tr class="tabletitle">

                </tr><br><br>
                <tr class="tabletitle">
                    <td></td>
                    <td class="payment"><h3>Total: <?php echo "Php " . $amountRemit; ?></h3></td>
                </tr>
            </table>
<br></br>
            <h4>Date and Signature</h4>
        </div>
    </div><!--End InvoiceBot-->
</div><!--End Invoice-->
</body>
</html>