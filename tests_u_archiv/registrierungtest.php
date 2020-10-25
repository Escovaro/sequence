<?php
$conn = mysqli_connect('localhost', 'root', '', 'sqdb');
if (!$conn) {
    die("Connection failed: ".mysqli_connect_error());
}

// Define variables and initialize with empty values
$email = $password = $confirm_password = $vorname = $nachname = $strasse = $ort = $plz = $skills = $position = "";
$email_err = $password_err = $confirm_password_err = $vorname_err = $nachname_err = $strasse_err = $ort_err = $plz_err = $skills_err = $position_err = "";

// Processing form data when form is submitted
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

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Bitte geben Sie eine Mailadresse an.";
    } else{
        //echo "empfangene Mailadresse: " . $email;
        // Prepare a select statement
        $sql = "SELECT mitarbeiterid FROM mitarbeiter WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "Diese Mailadresse ist bereits vergeben.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Es gab einen Fehler bei der Mail-Überprüfung.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 3){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    $vorname = $_POST['vorname'];
    $nachname = $_POST['nachname'];
    $strasse = $_POST['strasse'];
    $ort = $_POST['ort'];
    $plz = $_POST['plz'];
    @$skills = $_POST['skills'];
    $position = $_POST['position'];
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO mitarbeiter (email, pw, avatarpfad, vorname, nachname, strasse, ort, plz, skills, position) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssss", $param_email, $param_password, $param_avatarpfad, $param_vorname, $param_nachname, $param_strasse, $param_ort, $param_plz, $param_skills, $param_position);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            $param_avatarpfad = @$new_path;
            $param_vorname = $vorname;
            $param_nachname = $nachname;
            $param_strasse = $strasse;
            $param_ort = $ort;
            $param_plz = $plz;
            $param_skills = @$skills;
            $param_position = $position;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                //header("location: login_neu.php");
            } else{
                echo nl2br("Bei der Registrierung ist ein Fehler aufgetreten, bitte kontaktieren sie einen Admin unter info@sequence.com.\n");
            }

            // Close statement
            mysqli_stmt_close($stmt);
            echo "Angelegter Account für: " . $email;
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
	<title>register</title>
	<conn rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.8.0/js/all.js"></script>
    <!-- Latest compiled and minified CSS -->
    <conn rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
	<conn href="style.css" rel="stylesheet">
</head>
<body>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
        <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
        <small id="helpId" class="text-muted" >email</small>
        <span class="help-block"><?php echo $email_err; ?></span>
    </div>       
    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">                              
        <input type="text" name="password" id="password" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $password; ?>">
        <small id="helpId" class="text-muted" >passwort</small>
        <span class="help-block"><?php echo $password_err; ?></span>
    </div>        
    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">                            
        <input type="text" name="confirm_password" id="password" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $confirm_password; ?>">
        <small id="helpId" class="text-muted">passwort</small>
        <span class="help-block"><?php echo $confirm_password_err; ?></span>
    </div>  
    <div class="form-group <?php echo (!empty($vorname_err)) ? 'has-error' : ''; ?>">                            
        <input type="text" name="vorname" id="vorname" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $vorname; ?>">
        <small id="helpId" class="text-muted">vorname</small>
        <span class="help-block"><?php echo $vorname_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($nachname_err)) ? 'has-error' : ''; ?>">                            
        <input type="text" name="nachname" id="nachname" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $nachname; ?>">
        <small id="helpId" class="text-muted">nachname</small>
        <span class="help-block"><?php echo $nachname_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($strasse_err)) ? 'has-error' : ''; ?>">                            
        <input type="text" name="strasse" id="strasse" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $strasse; ?>">
        <small id="helpId" class="text-muted">strasse</small>
        <span class="help-block"><?php echo $strasse_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($ort_err)) ? 'has-error' : ''; ?>">                            
        <input type="text" name="ort" id="ort" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $ort; ?>">
        <small id="helpId" class="text-muted">ort</small>
        <span class="help-block"><?php echo $ort_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($plz_err)) ? 'has-error' : ''; ?>">                            
        <input type="text" name="plz" id="plz" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $plz; ?>">
        <small id="helpId" class="text-muted">plz</small>
        <span class="help-block"><?php echo $plz_err; ?></span>
    </div>   
    <div class="form-group">                              
        <select class="selectpicker" name="position" data-style="btn-outline-light" data-width="fit">
        <option value="1">mitarbeiter</option>
        <option value="2">teamlead</option>
        <option value="3">projektlead</option>
        <option value="4">admin</option>
    </select>
    </div>
    <div class="form-group">                                
        <select class="selectpicker" name="skills" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="area">
            <option value="Previsualization">Previsualization</option>
            <option value="Layout TD">Layout TD</option>
            <option value="Concept Artist">Concept Artist</option>
            <option value="Modeler">Modeler</option>
            <option value="Texture Artist">Texture Artist</option>
            <option value="Rigging TD">Rigging TD</option>
            <option value="Animator">Animator</option>
            <option value="FX TD">FX TD</option>
            <option value="Lighting TD">Lighting TD</option>
            <option value="Rendering TD">Rendering TD</option>
            <option value="Compositor">Compositor</option>
            <option value="Roto Artist">Roto Artist</option>
            <option value="Matchmover">Matchmover</option>
            <option value="Matte Painter">Matte Painter</option>
            <option value="Pipeline TD">Pipeline TD</option>
            <option value="VFX Producer">VFX Producer</option>
            <option value="CG Producer">CG Producer</option>
            <option value="CG Supervisor">CG Supervisor</option>
            <option value="VFX Supervisor">VFX Supervisor</option>
        </select>        
    </div>        
    <div class="container">    
            <label for="file-upload" class="custom-file-upload">
            <i class="fas fa-angle-double-up"></i> upl avatar
            </label>
            <input id="file-upload" type="file" name="datei"/>       
                                
        <button type="submit" class="customsubmit">absenden</button>    
</form>
    <?php
        //} //Ende von if($showFormular)
        //$showFormular = true; //Variable ob das Registrierungsformular angezeigt werde
    ?>    
    <p>Sie haben bereits einen Account? <a href="login_neu.php">Hier einloggen!</a></p> 
</body>
</html>