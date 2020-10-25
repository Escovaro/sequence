<?php
// Initialize the session
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["active"]) && $_SESSION["active"] === true){
    //redirect("willkommen.php");
    header('location: nach_login/willkommen.php');
    exit;
}
 
// Include config file
require_once "nach_login/php/dbhandler.php";
 
// Define variables and initialize with empty values
$email = $password = $position = "";
$email_err = $password_err = $position_err = "";
 
//Eingaben überprüfen
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //Email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    //Passwort
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    //Validierung
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT mitarbeiterid, email, pw, position FROM mitarbeiter WHERE email = ?";        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            //Parameter definieren
            $param_email = $email;            
            //Abfrage ausführen
            if(mysqli_stmt_execute($stmt)){
                //Speichern der Abfragen
                mysqli_stmt_store_result($stmt);                
                //Existiert der Benutzer?
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    //Speichern in einzelne Param
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $position);
                    //Passwortüberprüfung
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["active"] = true;
                            $_SESSION["mitarbeiterid"] = $id;
                            $_SESSION["email"] = $email;    
                            $_SESSION["position"] = $position;                      
                            
                            //----------------------------Willkommenseite hier verlinken ---------------------------\\
                            header('Location: nach_login/willkommen.php');
                            //redirect("willkommen.php");
                            //echo '<script>parent.window.location.reload(true);</script>';
                        } else{
                            //Passwort stimmt nicht
                            $password_err = "Das angegebene Passwort ist nicht korrekt.";
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $email_err = "Kein Account mit dieser Email gefunden.";
                }
            } else{
                echo nl2br("Es ist ein Fehler aufgetreten, bitte schicken Sie uns ein Mail an info@sequence.com.\n");
            }            
            mysqli_stmt_close($stmt);
        }
    }   
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
	<link href="style.css" rel="stylesheet">
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

<!-- Zwischenbar -->
    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-10">
                <p class="lead" id="jumbotext">Willkommen bei SEQUENCE, einer webbasierten Mediendatenbank- und Projektmanagementapplikation für Profis im kreativen Sektor!</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                <a href="registrierung.php"><button type="button" class="btn btn-outline-secondary btn-lg">Registrierung!</button></a>
            </div>
        </div>
    </div>



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
                        <div class="col-sm-5 login-form-links_login">                         
                            
                                    <object class="logo_login" data="img/SVG/Element 2.svg"
                                    type="image/svg+xml"></object>
                                    <!--    <img class="img-fluid"  src="img/Logo_schwarz_groß.png"s="img-fluid"  src="img/Logo_schwarz_groß.>-->
                                
                        </div>
                        <div class="col-md-7 login-form-rechts">

                        <!-- Form -->  
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                                <small id="helpId" class="text-muted" >email</small>
                                <span class="help-block"><?php echo $email_err; ?></span>
                            </div>       
                            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">                              
                                <input type="password" name="password" id="password" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $password; ?>">
                                <small id="helpId" class="text-muted" >passwort</small>
                                <span class="help-block"><?php echo $password_err; ?></span>
                            </div>               
                            <button type="submit" class="customsubmit">einloggen</button>
                        </form>
                        <small id="helpId" class="text-muted">Sie haben noch keinen Account? <a href="registrierung.php">Hier registrieren!</a></small> 
                        </div>
                    </div>
                </div>                
            </div>            
        </div>        
    </div>
<!-- Eine Säule -->
    <div class="container-fluid padding beschreibung">
        <div class="row padding justify-content-center">
            <div class="col-lg-9">
                 
            </div>
        </div>
        <hr class="my-4">
    </div>
    
<!-- Links -->
    <div class="container-fluid padding">
        <div class="row text-center padding">
            <div class="col-12">
                <h2>Connect</h2>
            </div>
            <div class="col-12 social padding">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-google-plus-g"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
<!-- Footer -->
    <footer>
        <div class="container-fluid padding">
            <div class="row text-center">
            
                <div class="col-12">
                    <hr class="dark-top">
                    <img src="img/Schriftlogo_mitlogo_weiss_schmal.png">
                    <hr class="dark smol">
                    <h5>Kontakt</h5>
                    <hr class="dark">
                    <p>555-555-555</p>
                    <p>email@gmx.at</p>
                    <p>teststr. 53</p>
                    <p>Wien, Österreich</p>
                </div>
            
                <div class="col-12">
                    <hr class="dark-100">
                    <h5>&copy; Sequence</h5>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>