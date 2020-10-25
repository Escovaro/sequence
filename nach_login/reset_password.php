<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){
    header("location: ../login.php");
    exit;
}
 
// Include config file
require_once "php/dbhandler.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 3){
        $new_password_err = "Password must have atleast 3 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE mitarbeiter SET pw = ? WHERE mitarbeiterid = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["mitarbeiterid"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: ../login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
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
                <h1 class="display-4">password reset</h1>
            </div> 
            <hr>
            <div class="col-12">
                <p class="lead">time for a change.</p>
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
                        <div class="col-md-5 login-form-links">                         
                            <div class="form-group">
                                <div>
                                    <object class="logo_login" data="../img/SVG/Element 2.svg"
                                    type="image/svg+xml"></object>
                                    <!--    <img class="img-fluid"  src="img/Logo_schwarz_groß.png"s="img-fluid"  src="img/Logo_schwarz_groß.>-->
                                </div>                                 
                            </div>
                        </div>
                        <div class="col-md-7 login-form-rechts">        
                            <div class="wrapper">
                                
                                <p>Bitte geben Sie ein neues Passwort an.</p>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                                    <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                                        <small>neues Passwort</small>
                                        <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                                        <span class="help-block"><?php echo $new_password_err; ?></span>
                                    </div>
                                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                        <small>neues Passwort bestätigen</small>
                                        <input type="password" name="confirm_password" class="form-control">
                                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="customsubmit2" value="absenden">
                                        <a class="btn btn-primary customsubmit2" href="willkommen.php" role="button">abbrechen</a>
                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>                
            </div>            
        </div>        
    </div>    
</body>
</html>