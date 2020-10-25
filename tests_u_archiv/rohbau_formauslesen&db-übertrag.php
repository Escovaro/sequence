<?php
session_start();
include 'nach_login/php/dbhandler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>register</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.8.0/js/all.js"></script>
    <!-- Nächsten für Multiselects -->
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
                    $.get("Navbar.html", function(data){
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
                <a href="registrieren.php"><button type="button" class="btn btn-outline-secondary btn-lg">Registrierung!</button></a>
            </div>
        </div>
    </div>



<!-- Seitentitel -->
    <div class="container-fluid padding">
        <div class="row welcome text-center">
            <div class="col-12">
                <h1 class="display-4">registrieren</h1>
            </div> 
            <hr>
            <div class="col-12">
                <p class="lead">Für die Magie sind Sie zuständig.</p>
            </div>
        </div>
    </div>

    
<!-- 2 Säulen -->
    <div class="container-fluid padding beschreibung">
        <hr class="my-4">
        <div class="row padding justify-content-center">
            <div class="col-lg-6">                
                    <div class="container register-container">
                        <div class="row" id="register">                        
                            <div class="col-md-6 login-form-links">                         
                            <div class="form-group">
                                        <img src="img/smallstep-2.jpg" alt="Pixabay-Composition_nah" id="regpic"></a>
                                </div> 
                            </div>
                            <div class="col-md-6 login-form-rechts">  
                        <?php 
                            // Initialisierung Variablen
                            $email = $password = $confirm_password = $vorname = $nachname = $strasse = $ort = $plz = $skills = $position = "";
                            $email_err = $password_err = $confirm_password_err = $vorname_err = $nachname_err = $strasse_err = $ort_err = $plz_err = $skills_err = $position_err = "";

                            // Verarbeitung der Form
                            if($_SERVER["REQUEST_METHOD"] == "POST"){
                                
                                //Avatar
                                if(!file_exists($_FILES['datei']['tmp_name']) || !is_uploaded_file($_FILES['datei']['tmp_name'])) {
                                    echo nl2br("Es wurde kein Avatar hochgeladen, dies kann später auf dem Mitarbeiterprofil geändert werden.\n");
                                } else {

                                    if (isset($_FILES['datei']['name'])){
                                        //Avatar-Upload
                                        $upload_folder = 'nach_login/data/avatare/'; //Das Upload-Verzeichnis
                                        $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
                                        $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
                                                                            
                                        //Überprüfung der Dateiendung
                                        $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                                        if(!in_array($extension, $allowed_extensions)) {
                                        // die("Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt");
                                        }

                                        //Überprüfung der Dateigröße
                                        $max_size = 500*1024; //500 KB
                                        if($_FILES['datei']['size'] > $max_size) {
                                        die("Bitte keine Dateien größer 500kb hochladen");
                                        }
                                        
                                        //Überprüfung dass das Bild keine Fehler enthält
                                        if(function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
                                        $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
                                        $detected_type = exif_imagetype($_FILES['datei']['tmp_name']);
                                        if(!in_array($detected_type, $allowed_types)) {
                                        die("Nur der Upload von Bilddateien ist gestattet");
                                        }
                                        }
                                        
                                        //Pfad zum Upload
                                        $new_path = $upload_folder.$filename.'.'.$extension;
                                        
                                        //Neuer Dateiname falls die Datei bereits existiert
                                        if(file_exists($new_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
                                        $id = 1;
                                        do {
                                        $new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
                                        $id++;
                                        } while(file_exists($new_path));
                                        }
                                    }
                                

                                    //Alles okay, verschiebe Datei an neuen Pfad
                                    move_uploaded_file($_FILES['datei']['tmp_name'], $new_path);
                                    //echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';
                                }

                                // Validierung email
                                if(empty(trim($_POST["email"]))){
                                    $email_err = "Bitte geben Sie eine Mailadresse an.";
                                } else{
                                    //Test-/ Überprüfungsausgabe:
                                    //echo "empfangene Mailadresse: " . $email;
                                    // Vorbereitung Query
                                    $sql = "SELECT mitarbeiterid FROM mitarbeiter WHERE email = ?";
                                    
                                    if($stmt = mysqli_prepare($conn, $sql)){
                                        // Variablen dem Statement als Parameter übergeben
                                        mysqli_stmt_bind_param($stmt, "s", $param_email);
                                        
                                        // Parameter setzen
                                        $param_email = trim($_POST["email"]);
                                        
                                        // Ausführen Statement
                                        if(mysqli_stmt_execute($stmt)){
                                            /* Speichern der Rückgabe */
                                            mysqli_stmt_store_result($stmt);
                                            
                                            if(mysqli_stmt_num_rows($stmt) == 1){
                                                $email_err = "Diese Mailadresse ist bereits vergeben.";
                                            } else{
                                                $email = trim($_POST["email"]);
                                            }
                                        } else{
                                            echo "Es gab einen Fehler bei der Mail-Überprüfung.";
                                        }
                                        // Statement schließen
                                        mysqli_stmt_close($stmt);
                                    }
                                }
                                
                                // Validierung Passwort
                                if(empty(trim($_POST["password"]))){
                                    $password_err = "Please enter a password.";     
                                } elseif(strlen(trim($_POST["password"])) < 3){
                                    $password_err = "Password must have atleast 6 characters.";
                                } else{
                                    $password = trim($_POST["password"]);
                                }
                                
                                // Validierung Bestätigungspasswort
                                if(empty(trim($_POST["confirm_password"]))){
                                    $confirm_password_err = "Please confirm password.";     
                                } else{
                                    $confirm_password = trim($_POST["confirm_password"]);
                                    if(empty($password_err) && ($password != $confirm_password)){
                                        $confirm_password_err = "Password did not match.";
                                    }
                                }
                                //Holen der Werte aus Formular    
                                $vorname = $_POST['vorname'];
                                $nachname = $_POST['nachname'];
                                $strasse = $_POST['strasse'];
                                $ort = $_POST['ort'];
                                $plz = $_POST['plz'];
                                @$skills = implode(', ', $_POST['skills']);
                                $position = $_POST['position'];
                                
                                // Prüfen, ob alle Fehler-Var. leer sind
                                if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
                                    
                                    // Vorbereiten der Query
                                    $sql = "INSERT INTO mitarbeiter (email, pw, avatarpfad, vorname, nachname, strasse, ort, plz, skills, position) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                    
                                    if($stmt = mysqli_prepare($conn, $sql)){
                                        // Variablen dem Statement als Parameter übergeben
                                        mysqli_stmt_bind_param($stmt, "ssssssssss", $param_email, $param_password, $param_avatarpfad, $param_vorname, $param_nachname, $param_strasse, $param_ort, $param_plz, $param_skills, $param_position);
                                        
                                        // Parameter setzen
                                        $param_email = $email;                                        
                                        $param_skills = @$skills;
                                        

                                        // Statement ausführen
                                        if(mysqli_stmt_execute($stmt)){
                                            // Weiterleitung zum Login
                                            header('location: login.php');
                                            echo "Account für: " . $email . " wurde angelegt.";
                                        } else{
                                            echo nl2br("Bei der Registrierung ist ein Fehler aufgetreten, bitte schicken Sie uns ein Mail an info@sequence.com.\n");
                                        }
                                        // Schließen des Statements
                                        mysqli_stmt_close($stmt);                                        
                                        } else // echo "";
                                    } 
                                }
                                
                                // Close connection
                                mysqli_close($conn);
                            
                            ?>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" accept-charset="utf-8" enctype="multipart/form-data">
                                <!-- Normale Texteingabe -->
                                    <div class="form-group <?php echo (!empty($vorname_err)) ? 'has-error' : ''; ?>">                            
                                        <input type="text" name="vorname" id="vorname" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $vorname; ?>">
                                        <small id="helpId" class="text-muted">vorname</small>
                                        <span class="help-block"><?php echo $vorname_err; ?></span>
                                    </div>
                                <!-- Single Select -->                                   
                                    <div class="form-group">                              
                                        <select class="selectpicker" name="position" data-style="btn-outline-light" data-width="fit">
                                            <option value="1">mitarbeiter</option>
                                            <option value="2">teamlead</option>
                                            <option value="3">projektlead</option>
                                            <option value="4">admin</option>
                                        </select>
                                    </div>
                                <!-- Multiselect -->
                                    <div class="form-group">                                
                                        <select class="selectpicker" name="skills[]" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="area">
                                        <?php 
                                            require("nach_login/php/dbhandler.php");
                                            echo $r="SELECT * FROM skills ;";
                                                $rr = mysqli_query($conn,$r);
                                                while($row= mysqli_fetch_array($rr))
                                                {
                                                    ?>
                                            <option value="<?php echo $row['skill']; ?>"><?php echo $row['skill']; ?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>        
                                    </div> 
                                    <!-- File-Upload-->
                                    <!-- Nicht sicher, ob die Container-Div geschlossen wird, bitte überprüfen -->       
                                    <div class="container">    
                                        <label for="file-upload" class="custom-file-upload">
                                            <i class="fas fa-angle-double-up"></i> upl avatar
                                        </label>
                                        <input id="file-upload" type="file" name="datei"/>     
                                        <button type="submit" class="customsubmit">absenden</button>    
                                    </div>
                            </form> 
                            <br>
                            <small id="helpId" class="text-muted">Sie haben bereits einen Account? <a href="login_neu.php">Hier einloggen!</a></small>    
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