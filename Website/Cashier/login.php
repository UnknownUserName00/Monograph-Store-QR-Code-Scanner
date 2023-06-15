
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  

    <title> QR Scanner POS</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
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
                        <form class="" role="form" method="POST" action="login.php">
                            <input type="hidden" name="_token" value="AxWAOsKoGmza2uLItQ2kpTGFhCEa3tpSlp2WCpRr">

                            <div class="form-group ">
                                <label for="email" class="control-label">User Name</label>
                                <input id="email" type="email" class="form-control" name="email" value="" required autofocus>

                                                            </div>

                            <div class="form-group ">
                                <label for="password" class="control-label">Password</label>
                                <input id="password" type="password" class="form-control" name="password" required>

                                                            </div>

                            <div class="form-group">

                                
                                
                                
                                
                                

                            </div>

                            <div class="form-group">

                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="forgot.php">
                                    Forgot Your Password?
                                </a>

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
