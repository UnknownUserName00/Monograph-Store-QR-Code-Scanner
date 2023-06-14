<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="icon.png">


    <title>Admin Registration</title>
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
        <div class="col-md-9 offset-md-3">
            <div class="card" style="width: 40rem; margin-top: 20px;">

                <div class="card-body">
                    <h4 class="card-title">Admin Registration</h4>
                    <div>
                        <hr>
						<?php
							if (isset($_GET['notify'])) {
								$notify = $_GET['notify'];
							}

							else {
								$notify = "";
							}
						?>
                        <form class="form-horizontal" role="form" action = "adminProcessRegistration.php" method="POST" action="" autocomplete="off">
                            <input type="hidden" name="_token" value="AxWAOsKoGmza2uLItQ2kpTGFhCEa3tpSlp2WCpRr">

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Username</label>

                                <div class="col-md-12">
                                    <input id="email" type="text" name="qrNameUser" class="form-control" placeholder = "Enter Username" required value="<?php
										if(isset($_POST['AdminRegister'])){
											$_POST['qrNameUser'];
										} ?>">
								</div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Password</label>

                                <div class="col-md-12">
                                    <input id="email" type="password" name="qrPassUser" class="form-control" placeholder = "Enter Password" required value="<?php
										if(isset($_POST['AdminRegister'])){
											$_POST['qrPassUser'];
										} ?>">
								</div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">First Name</label>

                                <div class="col-md-12">
                                    <input id="email" type="text" name="qrFirstName" class="form-control" placeholder = "Enter First Name" required value="<?php
										if(isset($_POST['AdminRegister'])){
											$_POST['qrFirstName'];
										} ?>">
								</div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Last Name</label>

                                <div class="col-md-12">
                                    <input id="email" type="text" name="qrLastName" class="form-control" placeholder = "Enter Last Name" required value="<?php
										if(isset($_POST['AdminRegister'])){
											$_POST['qrLastName'];
										} ?>">
								</div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Security Question</label>

                                <div class="col-md-12">
                                    <select name = "qrSecurityQuestion" class="form-control" required value = "<?php
                                            if(isset($_POST['AdminRegister'])){
												$_POST['qrSecurityQuestion'];
											}
										?>">
											<option></option>
											<option>In what city or town was your first job?</option>
											<option>In what city you were born?</option>
											<option>What is the name of your pet?</option>
											<option>What is your favorite color?</option>
											<option>What is your favorite movie?</option>
										</select>
								</div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Security Answer</label>

                                <div class="col-md-12">
                                    <input id="email" type="text" name="qrSecurityAnswer" placeholder = "Answer" class="form-control" required value="<?php
										if(isset($_POST['AdminRegister'])){
											$_POST['qrSecurityAnswer'];
										} ?>">
								</div>
                            </div>

							<?php echo $notify; ?>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
									<input type="submit" class="btn btn-primary" value="Submit" name = "AdminRegister">

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
