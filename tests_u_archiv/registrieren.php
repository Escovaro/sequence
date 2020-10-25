<?php 
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=mitarbeitertest', 'root', '');
$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
                <h1 class="display-4">Registrierung</h1>
            </div> 
            <hr>

           
        </div>
    </div>

<!-- PHP-Teil -->
    <?php
    $showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
    
    if(isset($_GET['register'])) {
        $error = false;
        $email = $_POST['email'];
        $passwort = $_POST['passwort'];
        $passwort2 = $_POST['passwort2'];
        $nachname = $_POST['nachname'];
        $vorname = $_POST['vorname'];
        $strasse = $_POST['strasse'];
        $plz = $_POST['plz'];
        $ort = $_POST['ort'];
        $skills = $_POST['skills'];
        $mitarbeiterseit =  date("d-m-Y H:i:s");
        //Avatarpfad und status fehlen

        
        /* Unnötig, da die Inputfelder das selbst erledigen   
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<p>Bitte geben Sie eine gültige Emailadresse an! </p>';
            $error = true;
        }     
        if(strlen($passwort) == 0) {
            echo '<p>Bitte geben Sie ein Passwort an! </p>';
            $error = true;
        }
        if($passwort != $passwort2) {
            echo '<p>Die beiden Passwörter stimmen nicht überein! </p>';
            $error = true;
        }
        if(strlen($vorname) == 0) {
            echo '<p>Bitte geben Sie einen Vornamen an! </p>';
            $error = true;
        }
        if(strlen($nachname) == 0) {
            echo '<p>Bitte geben Sie einen Nachnamen an! </p>';
            $error = true;
        }
        if(strlen($strasse) == 0) {
            echo '<p>Bitte geben Sie eine Straße an! </p>';
            $error = true;
        }
        if(strlen($plz) == 0) {
            echo '<p>Bitte geben Sie eine Postleitzahl an! </p>';
            $error = true;
        }
        if(strlen($ort) == 0) {
            echo '<p>Bitte geben Sie einen Ort an! </p>';
            $error = true;
        }
        if(strlen($skills) == 0) {
            echo '<p>Bitte geben Sie die Skills an! </p>';
            $error = true;
        }

        */ 
        //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
        if(!$error) { 
            $statement = $pdo->prepare("SELECT * FROM mitarbeiter WHERE email = :email");
            $result = $statement->execute(array('email' => $email));
            $user = $statement->fetch();
            
            if($user !== false) {
                echo '<div class="container"><p>ACHTUNG: Diese Emailadresse ist bereits vergeben! </p></div>';
                $error = true;
            }    
        }
        
        //Keine Fehler, wir können den Nutzer registrieren
        if(!$error) {  
            $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
            
            $statement = $pdo->prepare("INSERT INTO mitarbeiter (email, passwort, vorname, nachname, straße, plz, ort, skills, mitarbeiterseit) VALUES ('".$email."', '".$passwort_hash."', '".$vorname."', '".$nachname."' , '".$strasse."', '".$plz."' , '".$ort."' , '".$skills."' , NOW())");
            $result = $statement->execute(array('email' => $email, 'passwort' => $passwort_hash, 'vorname' => $vorname, 'nachname' => $nachname, 'straße' => $strasse, 'plz' => $plz, 'ort' => $ort, 'skills' => $skills));
            
            if($result) {        
                echo '<div class="container text-center"><p>Mitarbeiter wurde erfolgreich angelegt!</p></div>';
                $showFormular = false;
            } else {
                echo trigger_error("PDO errorInfo: ".$pdo->errorInfo());
            }
        } 
    }

    // Diesen Teil ausgeklammert, damit man viele Mitarbeiter auf einmal registrieren kann, ohne dass das Formular verschwindet
    //if($showFormular) {
    ?>
    
    <form action="?register=1" method="post">
        <div class="container">

            <label for="email"><b>Email</b></label><br>
            <input type="email" class="input" size="40" maxlength="250" placeholder="Emailadresse eingeben" name="email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" /><br>
            
            <label for="vorname"><b>Vorname</b></label><br>
            <input type="text" class="input" size="30" maxlength="250" placeholder="Vornamen eingeben" name="vorname" required value="<?php echo isset($_POST['vorname']) ? $_POST['vorname'] : '' ?>" /><br>

            <label for="nachname"><b>Nachname</b></label><br>
            <input type="text" class="input" size="30" maxlength="250" placeholder="Nachnamen eingeben" name="nachname" required value="<?php echo isset($_POST['nachname']) ? $_POST['nachname'] : '' ?>" /><br>

            <label for="passwort"><b>Passwort</b></label><br>
            <input type="password" class="input" size="40" maxlength="250" placeholder="Passwort eingeben" name="passwort" required value="<?php echo isset($_POST['passwort']) ? $_POST['passwort'] : '' ?>" /><br>

            <label for="passwort"><b>Passwort wiederholen</b></label><br>
            <input type="password" class="input" size="40" maxlength="250" placeholder="Passwort eingeben" name="passwort2" required value="<?php echo isset($_POST['passwort2']) ? $_POST['passwort2'] : '' ?>" /><br>

            <label for="strasse"><b>Straße</b></label><br>
            <input type="strasse" class="input" size="40" maxlength="250" placeholder="Straße eingeben" name="strasse" required value="<?php echo isset($_POST['strasse']) ? $_POST['strasse'] : '' ?>" /><br>

            <label for="plz"><b>Postleitzahl</b></label><br>
            <input type="plz" class="input" size="40" maxlength="250" placeholder="Postleitzahl eingeben" name="plz" required value="<?php echo isset($_POST['plz']) ? $_POST['plz'] : '' ?>" /><br>

            <label for="ort"><b>Ort</b></label><br>
            <input type="ort" class="input" size="40" maxlength="250" placeholder="Ort eingeben" name="ort" required value="<?php echo isset($_POST['ort']) ? $_POST['ort'] : '' ?>" /><br>

            <label for="skills"><b>Mitarbeiter-Skills</b></label><br>
            <input type="skills" class="input" size="40" maxlength="250" placeholder="Skills eingeben" name="skills" required value="<?php echo isset($_POST['skills']) ? $_POST['skills'] : '' ?>" /><br><br>

            <label>
            </label>
            
            <button type="submit" class="btn btn-outline-secondary btn-lg" value="Senden">Absenden!</button>
            <br><br>
                            
        </div>
    </form>
    <!-- Diesen Teil ausgeklammert, damit man viele Mitarbeiter auf einmal registrieren kann, ohne dass das Formular verschwindet
    <?php
    // } //Ende von if($showFormular)
    ?>
    -->
    <!-- Footer -->
    <footer>
    <div class="container-fluid padding">
        <div class="row text-center">
            <!--
            <div class="col-md-4">
                <hr class="dark">
                <img src="img/Schriftlogo_mitlogo_weiß_schmal.png">
                <hr class="dark">
                <p>555-555-555</p>
                <p>email@gmx.at</p>
                <p>teststr. 53</p>
                <p>Wien, Österreich</p>
            </div>
            -->
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
            <!--
            <div class="col-md-4">
                <hr class="dark">
                <h5>HTL Wien West</h5>
                <hr class="dark">
                <p>Montags 8Uhr - 17Uhr</p>
            </div>
            -->
            <div class="col-12">
                <hr class="dark-100">
                <h5>&copy; Sequence</h5>
            </div>
        </div>
    </div>
</footer>
</body>
</html>