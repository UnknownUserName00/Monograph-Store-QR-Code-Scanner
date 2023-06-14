<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="icon.png">


    <title>Admin Forgot Password</title>

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
<?php
	session_start();

    $adminName = $_GET['adminName'];
    if ($_SESSION['SESS_FIRST_NAME'] != $adminName) {
		header("location: staffSignInForm.php");
		exit();
    }
?>
    <!-- Scripts -->
    <script>
        window.Laravel = {"csrfToken":"AxWAOsKoGmza2uLItQ2kpTGFhCEa3tpSlp2WCpRr"};
    </script>
</head>
<body>

    <div id="app">


        
            
        



        <div class="container">
    <div class="row">
        <div class="col-md-9 offset-md-3">
            <div class="card" style="width: 40rem; margin-top: 20px;">

                <div class="card-body">
                    <h4 class="card-title">Admin Forgot Password</h4>
                    <div>
                        <hr>

						<?php
							$adminName = $_GET['adminName'];
							$notify = $_GET['notify'];
						?>
                        <form class="form-horizontal" role="form" method="POST" action="adminProcessForgotPass.php" autocomplete="off">
                            <input type="hidden" name="_token" value="AxWAOsKoGmza2uLItQ2kpTGFhCEa3tpSlp2WCpRr">

							<input type = "hidden" name = "adminName" value = "<?php echo $adminName = $_GET['adminName']; ?>"/>
									<?php
										include "ServerConnection/configConnectRecords.php";

										$sql = "SELECT * FROM qrcodesAdmin
											WHERE qrAdminName = '$adminName' ";
										$result = $db->prepare($sql);
										$result->execute();
										for($i=0; $row = $result->fetch(); $i++){
											$question = $row['qrAdminSecurityQuestion'];
										}
									?>
                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Security Question</label><br/>
                                <label for="email" class="col-md-4 control-label" style = "white-space: pre;"><?php echo $question; ?></label>

                                <div class="col-md-12">
                                    <input id="email" type="text" name="qrSecurityAnswer" class="form-control" required placeholder = "Answer" value="">
								</div>
                            </div><br/>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">New Password</label>

                                <div class="col-md-12">
                                    <input id="email" type="password" name="qrNewPassUser" class="form-control" required placeholder = "Enter New Password" value="">
								</div>
                            </div>
							<center style = "color: Red; font-weight: Bold; font-size: 14; white-space: pre;"><?php echo $notify; ?></center>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
									<input type="submit" class="btn btn-primary" value="Submit" name = "AdminChangePass">

                                    <a href = "staffSignInForm.php" style = "font-weight: Bold; font-size: 14; white-space: pre;">Back to Sign In</a>
                                </div>
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
