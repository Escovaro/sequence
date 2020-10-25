<?php 
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=mitarbeitertest', 'root', '');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>sequence</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://use.fontawesome.com/releases/v5.8.0/js/all.js"></script>
        <link href="style.css" rel="stylesheet">
    </head>
<body>

<!-- Navbars -->
    <div id="include">    
        <script>
            window.onload = function(){
                $.get("Navbar.html", function(data){
                    $("#include").html(data);
                })
            }
        </script>
    </div>

<!-- Seitentitel -->
    <div class="container-fluid padding">
        <div class="row welcome text-center">
            <div class="col-12">
                <h1 class="display-4">Login</h1>
            </div> 
            <hr>

           
        </div>
    </div>
             <!-- object type="image/svg+xml" class="film" data="negativ_3.svg"></span></object-->  

<!-- PHP-Teil -->
    <?php
    if(isset($_GET['login'])) {
        $email = $_POST['email'];
        $passwort = $_POST['passwort'];
        $link ="#Projektübersicht";
        
        $statement = $pdo->prepare("SELECT * FROM mitarbeiter WHERE email = :email");
        $result = $statement->execute(array('email' => $email));
        $user = $statement->fetch();
            
        //Überprüfung des Passworts
        if ($user !== false && password_verify($passwort, $user['passwort'])) {
            $_SESSION['userid'] = $user['id'];
            $_SESSION['Benutzer'] = $user['vorname'];
            die("<p>"."Login erfolgreich! "."<a href='$link'>"."<font color=white>"."HIER"."</a>"." geht's weiter zum internen Bereich!");
        } else {
            $errorMessage = "E-Mail oder Passwort war ungültig<br>";
            echo trigger_error("PDO errorInfo: ".$pdo->errorInfo());
        }
        
    }
    ?>

    
    <?php 
    if(isset($errorMessage)) {
        echo $errorMessage;
    }
    ?>
    
    <form action="?login=1" method="post">
        <div class="container">
            E-Mail:<br>
            <input type="email" class="input" size="40" maxlength="250" name="email"><br><br>
            
            Dein Passwort:<br>
            <input type="password" class="input" size="40"  maxlength="250" name="passwort"><br>
            <br>

            <button type="submit" class="btn btn-outline-secondary btn-lg" value="Senden">Einloggen</button>
        </div>
    </form> 
</body>
</html>