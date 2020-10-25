<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>login</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.8.0/js/all.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
	<link href="../style.css" rel="stylesheet">
</head>
<body>
<header class="sticky-top">
<!-- Navbars -->
    <div id="include">    
        <script>
            window.onload = function(){
                $.get("Navbar.php", function(data){
                    $("#include").html(data);
                })
            }
        </script>
    </div>
</header>

<!-- Seitentitel -->
    <div class="container-fluid padding">
        <div class="row welcome text-center">
            <div class="col-12">
                <h1 class="display-4">login</h1>
            </div> 
            <hr>
            <div class="col-12">
                <p class="lead">lessgo.</p>
            </div>
        </div>
    </div>
    
<!-- 2 Säulen -->
    <div class="container-fluid padding beschreibung">
        <hr class="my-4">
        <div class="row padding justify-content-center">
            <div class="col-lg-6">      
                <div class="container login-container">
                    <div class="row" id="register">                        
                        <div class="col-md-6 login-form-links">                         
                            <div class="form-group">
                                <div>
                                    <object class="logo_willkommen" data="../img/SVG/Element 2.svg"
                                    type="image/svg+xml"></object>
                                    <!--    <img class="img-fluid"  src="img/Logo_schwarz_groß.png"s="img-fluid"  src="img/Logo_schwarz_groß.>-->
                                </div>                                 
                            </div>
                        </div>
                        <div class="col-md-6 login-form-rechts">        

                        <div class="page-header">
                            <h6>Mitarbeiter-ID: <b><?php echo htmlspecialchars($_SESSION["mitarbeiterid"]); ?></h6>
                            <h6>Eingeloggt als: <b><?php echo htmlspecialchars($_SESSION["email"]); ?></b><br><br> Willkommen zurück!</h6>
                        </div>
                        <div class="page-header">
                            
                        </div>
                            <!-- <a href="reset_password.php" class="btn customsubmit2" role="button">PW zurücksetzen</a> -->
                            <a href="verwaltung/subtaskverwaltung.php">
                                <button type="button" class="customsubmit3">Zu meinen Aufgaben</button>
                            </a>
                            <br>
                            <a href="reset_password.php">
                                <button type="button" class="customsubmit2">PW zurücksetzen</button>
                            </a>
                            <br>
                            <a href="logout.php">
                                <button type="button" class="customsubmit2">logout</button>
                            </a>
                            
                            
                         
                    </div>
                </div>                
            </div>            
        </div>        
    </div>
</body>
</html>