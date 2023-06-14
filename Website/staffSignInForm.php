<?php
	session_start();

    if (isset($_SESSION['SESS_LAST_NAME'])) {
        if (isset($_SESSION['SESS_FIRST_NAME'])) {
            if ($_SESSION['SESS_LAST_NAME'] == "adminDashboard.php") {
                $qrNameStaff = $_SESSION['SESS_FIRST_NAME'];
				header("location: Admin/adminDashboard.php?adminName=$qrNameStaff");
                exit();
            }

            elseif ($_SESSION['SESS_LAST_NAME'] == "cashierPettycash.php") {
                $qrNameStaff = $_SESSION['SESS_FIRST_NAME'];
                header("location: Cashier/cashierPettycash.php?transactNum&notify&cashierName=$qrNameStaff&beginBalance=0");
                exit();
            }
        }
    }

    else {
        unset($_SESSION['SESS_FIRST_NAME']);
        unset($_SESSION['SESS_LAST_NAME']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="icon.png">

  

    <title>Staff Sign In</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <style>
        body {
            background-color: #193048;
            font-family: 'Roboto', sans-serif;
        }
        button{
            cursor: pointer;
        }
    </style>


    <!-- Scripts -->
    <script>
        window.Laravel = {"csrfToken":"AxWAOsKoGmza2uLItQ2kpTGFhCEa3tpSlp2WCpRr"};
    </script>
</head>
<body>

    <div id="app">


        
            
        



        <div class="container">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <br>

            <div class="card" style="width: 25rem; margin-top: 20px;">

                <div class="card-body">

                    <h5 class="card-title text-center">LOGIN</h5>
                    <div>
                        <hr>
<?php
	include "ServerConnection/configConnectRecords.php";

    $notify = "";
	$forgotPassword = isset($forgotPassword);
	$rowSearchedAdmin = "";
	$rowSearchedAdminName = "";
	$rowSearchedCashierName = "";
	$rowSearchedCashier = "";

    if( isset($_POST['qrNameStaff']) and isset($_POST['qrPassStaff']) ) {
		$date = new DateTime();
		$date->add(new DateInterval('PT6H'));
		$fullDateFormat = $date->format('Y-m-d');
		$timeFormat = $date->format('h:i A');

		$qrNameStaff=$_POST['qrNameStaff'];
		$qrPassStaff=hash('sha512', $_POST['qrPassStaff'], false);
        $attempts = $_POST['attempts'];
        $previousUser = $_POST['previousUser'];

		$checkNameCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $qrNameStaff);
		$checkPassCharSymbols = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['qrPassStaff']);

		$adminId = 1;
		$findAdminName = "";
		$findAdminPass = "";
		$getAdminVerification = "False";
		$sql = "SELECT * FROM qrcodesAdmin
			WHERE qrAdminName != '' ";
		$retrieveAdmin = $db->prepare($sql);
		$retrieveAdmin->execute();
		for($i=0; $rowAdmin = $retrieveAdmin->fetch(); $i++){
			$checkAdminName = $rowAdmin['qrAdminName'];
			$checkAdminPass = $rowAdmin['qrAdminPassword'];

			$verifyAdmin = ($checkAdminName == $qrNameStaff) ? "True" : "False";

			if ($verifyAdmin == "True") {
				$adminId = $rowAdmin['qrAdminId'];
				$findAdminName = $rowAdmin['qrAdminName'];
				$findAdminPass = $rowAdmin['qrAdminPassword'];

				$getAdminVerification = $verifyAdmin;
			}
		}

		$cashierId = 1;
		$findCashierName = "";
		$findCashierPass = "";
		$getCashierVerification = "False";
		$sql = "SELECT * FROM qrcodesCashier
			WHERE qrCashierName != '' ";
		$retrieveCashier = $db->prepare($sql);
		$retrieveCashier->execute();
		for($i=0; $rowCashier = $retrieveCashier->fetch(); $i++){
			$checkCashierName = $rowCashier['qrCashierName'];
			$checkCashierPass = $rowCashier['qrCashierPassword'];

			$verifyCashier = ($checkCashierName == $qrNameStaff) ? "True" : "False";

			if ($verifyCashier == "True") {
				$cashierId = $rowCashier['qrCashierId'];
				$findCashierName = $rowCashier['qrCashierName'];
				$findCashierPass = $rowCashier['qrCashierPassword'];

				$getCashierVerification = $verifyCashier;
			}
		}

		if ( ($getAdminVerification == "True") && ($checkNameCharSymbols == 0) ) {
			if ( ($findAdminPass == $qrPassStaff) && ($checkPassCharSymbols == 0) ) {
                $sql = "SELECT * FROM qrcodesAdmin
                    WHERE qrAdminName = '$findAdminName' ";
                $retrieveAdmin = $db->prepare($sql);
                $retrieveAdmin->execute();
                for($i=0; $rowAdmin = $retrieveAdmin->fetch(); $i++){
                    $_SESSION['SESS_FIRST_NAME'] = $rowAdmin['qrAdminName'];
                }

                $_SESSION['SESS_LAST_NAME'] = "adminDashboard.php";

				$forgotPassword = "";

				$staffActivity = "Successful Sign In to Admin Proceeding to Admin Control Panel!";

				$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
					VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
				$resultAudit = $db->prepare($sql);
				$resultAudit->execute();

                $attempts = 0;

                $sql = "UPDATE qrcodesAdmin
                    SET qrAdminLogInAttempt = '$attempts'
                    WHERE qrAdminName = '$qrNameStaff' ";
                $attemptCounter = $db->prepare($sql);
				$attemptCounter->execute();

				header("location: Admin/adminDashboard.php?adminName=$qrNameStaff");
                exit();
			}

			else {
                $sql = "SELECT * FROM qrcodesAdmin
                    WHERE qrAdminName = '$findAdminName' ";
                $retrieveAdmin = $db->prepare($sql);
                $retrieveAdmin->execute();
                for($i=0; $rowAdmin = $retrieveAdmin->fetch(); $i++){
                    $_SESSION['SESS_FIRST_NAME'] = $rowAdmin['qrAdminName'];
                }

				$notify = 'Invalid Either Username or Password!';

                $sql = "SELECT * FROM qrcodesAdmin
                    WHERE qrAdminName = '$qrNameStaff' ";
                $checkCounterAdmin = $db->prepare($sql);
				$checkCounterAdmin->execute();
                for($i=0; $checkAdmin = $checkCounterAdmin->fetch(); $i++){
                    $attemptsCounterAdmin = $checkAdmin['qrAdminLogInAttempt'];
                }

                if ($previousUser != $qrNameStaff) {
                    $attemptsCounterAdmin = 0;
                }

                if ($attemptsCounterAdmin > 1) {
                    $forgotPassword = "<a href = 'adminForgotPass.php?adminName=$qrNameStaff&notify'><button type = 'button' class='btn btn-primary'>Forgot Password?</button></a>";
                }

				$staffActivity = "Attempting Sign In to Admin!";

				$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
					VALUES ('$adminId', 1, '$staffActivity', '$fullDateFormat', '$timeFormat')";
				$resultAudit = $db->prepare($sql);
				$resultAudit->execute();

                $attempts = $attemptsCounterAdmin + 1;

                $sql = "UPDATE qrcodesAdmin
                    SET qrAdminLogInAttempt = '$attempts'
                    WHERE qrAdminName = '$qrNameStaff' ";
                $attemptCounter = $db->prepare($sql);
				$attemptCounter->execute();

                $previousUser = $qrNameStaff;
			}
		}

		elseif ( ($getCashierVerification == "True") && ($checkNameCharSymbols == 0) ) {
			if ( ($findCashierPass == $qrPassStaff) && ($checkPassCharSymbols == 0) ) {
                $sql = "SELECT * FROM qrcodesCashier
                    WHERE qrCashierName = '$findCashierName' ";
                $retrieveCashier = $db->prepare($sql);
                $retrieveCashier->execute();
                for($i=0; $rowCashier = $retrieveCashier->fetch(); $i++){
                    $_SESSION['SESS_FIRST_NAME'] = $rowCashier['qrCashierName'];
                }

                $_SESSION['SESS_LAST_NAME'] = "cashierPettycash.php";
				$_SESSION['beginBalance'] = "0";

				$staffActivity = "Successful Sign In to Cashier Proceeding to Cashier Dashboard!";

				$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
					VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
				$resultAudit = $db->prepare($sql);
				$resultAudit->execute();

                $attempts = 0;

                $sql = "UPDATE qrcodesCashier
                    SET qrCashierLogInAttempt = '$attempts'
                    WHERE qrCashierName = '$qrNameStaff' ";
                $attemptCounter = $db->prepare($sql);
				$attemptCounter->execute();

				header("location: Cashier/cashierPettycash.php?transactNum&notify&cashierName=$qrNameStaff&beginBalance=0");
                exit();
			}

			else {
				$notify = 'Invalid Either Username or Password!';

                $sql = "SELECT * FROM qrcodesCashier
                    WHERE qrCashierName = '$qrNameStaff' ";
                $checkCounterCashier = $db->prepare($sql);
				$checkCounterCashier->execute();
                for($i=0; $checkCashier = $checkCounterCashier->fetch(); $i++){
                    $attemptsCounterCashier = $checkCashier['qrCashierLogInAttempt'];
                }

                if ($previousUser != $qrNameStaff) {
                    $attemptsCounterCashier = 0;
                }

                if ($attemptsCounterCashier > 1) {
                    $forgotPassword = "<button type = 'submit' class='btn btn-primary' name = 'confirmAcount'>Forgot Password?</button>";
                }

				if (isset($_POST['confirmAcount'])) {
					echo "<script>alert('Changed Password Permission Request Already Sent to Admin!');</script>";
					$notify = "";
					$forgotPassword = "";

					$sql = "UPDATE qrcodesCashier
						SET qrCashierChangePassRequest = 'Pending'
						WHERE qrCashierName = '$qrNameStaff'";
					$result = $db->prepare($sql);
					$result->execute();

					$staffActivity = "Cashier Permission to Change Password to Admin!";

					$sql = "INSERT INTO qrcodesAuditTrail (qrAdminId, qrCashierId, qrUserActivity, qrAuditTrailDate, qrAuditTrailTime)
						VALUES (1, '$cashierId', '$staffActivity', '$fullDateFormat', '$timeFormat')";
					$resultAudit = $db->prepare($sql);
					$resultAudit->execute();
				}

                $attempts = $attemptsCounterCashier + 1;

                $sql = "UPDATE qrcodesCashier
                    SET qrCashierLogInAttempt = '$attempts'
                    WHERE qrCashierName = '$qrNameStaff' ";
                $attemptCounter = $db->prepare($sql);
				$attemptCounter->execute();

                $previousUser = $qrNameStaff;
			}
		}

		else {
			$notify = 'Invalid Either Username or Password!';
			$forgotPassword = "";
		}
    }

    else {
        $attempts = 0;
        $previousUser = "";
    }
?>
                        <form class="" role="form" method = "post" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="_token" value="AxWAOsKoGmza2uLItQ2kpTGFhCEa3tpSlp2WCpRr">
                            <input type = "hidden" name = "attempts" value = "<?php echo $attempts; ?>"/>
                            <input type = "hidden" name = "previousUser" value = "<?php echo $previousUser; ?>"/>

                            <div class="form-group ">
                                <label for="email" class="control-label">User Name</label>
                                <input id="email" type="text" class="form-control" name="qrNameStaff" placeholder="Enter Username" value="<?php
									if(isset($_POST['StaffSignIn'])){
										echo $_POST['qrNameStaff'];
									} ?>" required autofocus>
                            </div>

                            <div class="form-group ">
                                <label for="password" class="control-label">Password</label>
                                <input id="password" type="password" class="form-control" name="qrPassStaff" placeholder="Enter Password" value = "<?php
									if(isset($_POST['StaffSignIn'])){
										echo $_POST['qrPassStaff'];
								} ?>" required autofocus>
							</div>

                            <div class="form-group"></div>

                            <div class="form-group">
								<center id = "modifyFormat" style = "color: Red; font-weight: Bold; font-size: 14; white-space: pre;"><?php echo $notify; ?></center>
							</div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name = "StaffSignIn">
                                    Login
                                </button>

                                <a class="btn btn-link"><?php echo $forgotPassword; ?></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    </div>



</body>
</html>
