<?php 
session_start();
    include 'nach_login/php/dbhandler.php';
                        //if(isset($_GET['register'])) {
                            $error = false;
                            $email = $_POST['email'];
                            $vorname = $_POST['vorname'];
                            $nachname = $_POST['nachname'];
                            $strasse = $_POST['strasse'];
                            $ort = $_POST['ort'];
                            $plz = $_POST['plz'];
                            $passwort = $_POST['passwort'];
                            $position = $_POST['position'];
                            $skills = $_POST['skills'];
                            
                            if (isset($_FILES['datei']['name'])){
                                //Avatar-Upload
                                $upload_folder = 'nach_login/data/avatare/'; //Das Upload-Verzeichnis
                                $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
                                $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
                                
                                
                                //Überprüfung der Dateiendung
                                $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                                if(!in_array($extension, $allowed_extensions)) {
                                die("Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt");
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
                        
                            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                echo '<p>Bitte geben Sie eine gültige Emailadresse an! </p>';
                                $error = true;
                            }     
                            if(strlen($passwort) == 0) {
                                echo '<p>Bitte geben Sie ein Passwort an! </p>';
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
                            if(strlen($position) == 0) {
                                echo '<p>Bitte geben Sie eine Mitarbeiterposition an! </p>';
                                $error = true;
                            }
                        
                            //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
                            if(!$error) { 
                                $query = mysqli_query($conn, "SELECT email FROM mitarbeiter WHERE email = '".$email."'");
                                if (!$query)
                            {
                                die('Error: ' . mysqli_error($conn));
                            }
                            if(mysqli_num_rows($query) > 0){
                                echo "Email-Adresse existiert bereits!";
                                //$showFormular = true;
                                //-----------------------------------HIER MUSS NOCH DEFINIERT WERDEN, WAS PASSIERT, OBEN UND UNTEN ---------------------------- //
                            }else{
                                ///Keine Fehler, wir können den Nutzer registrieren
                                if(!$error) {           
                                    $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
                                    echo $_SESSION['new_path'];
                                    $sql = "INSERT INTO mitarbeiter (email, vorname, nachname, strasse, ort, plz, pw, avatarpfad, position, skills) 
                                    VALUES ('".$_POST['email']."', '".$_POST['vorname']."', '".$_POST['nachname']."', '".$_POST['strasse']."', '".$_POST['ort']."', '".$_POST['plz']."', '".$passwort_hash."', '".$new_path."', '".$_POST['position']."', '".$_POST['skills']."')";
                                            $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                                            
                                    if($resultset) {        
                                        echo '<p>Neuer Benutzer wurde erfolgreich angelegt.</p>';
                                        $showFormular = true;
                                    } else {
                                        echo '<p></p>Beim Anlegen des Benutzers ist ein Fehler aufgetreten.</p>';
                                    }
                                }
                            }
                        }      
    ?>       
                             
                         