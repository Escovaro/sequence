<?php
//DATENBANK-EINTRAG nach Projekt- und Subtaskerstellung
//Erhält seine Daten von index.php!
    session_start();
    include '../php/dbhandler.php';
?>

<?php
   
        $stid = $_SESSION['stid'];   
        $_SESSION['stid'] = $stid;     

        $mitarbeiterid = $_SESSION["mitarbeiterid"];
        //$updatebeschreibung = $_POST['updatebeschreibung'];    
        $updatebeschreibung = $_SESSION['updatebeschreibung'];

        $mediaid = $_SESSION['mediaid'];

        $neuerpfad_file= ""; 
        
        //Holen des Subtasktitels:
            $sql = "SELECT titel, projektid FROM subtask WHERE subtaskid = '".$stid."'";
            $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
            while($record = mysqli_fetch_assoc($resultset)){
            $stname = $record['titel'];
            $pid = $record['projektid'];
            }
            //Holen des Projekttitels
            $sql1 = "SELECT titel FROM projekt WHERE projektid = '".$pid."'";
            $resultset = mysqli_query($conn, $sql1) or die("database error:". mysqli_error($conn));
            while($record = mysqli_fetch_assoc($resultset)){
            $pname = $record['titel'];
            }


    //Falls Subtask angelegt wurde:          
        if (isset($stid) && ($stid != "")){
            //----------------------------------------------------------------------File-Upload ---------------------------------------------------------\\
            $ursprungspfad_file = '../../data/' . $pid . '_' . $pname . '/' . $stid . '_' . $stname . '/' . 'abgaben' . '/';
            $ursprungspfad_db = '../data/' . $pid . '_' . $pname . '/' . $stid . '_' . $stname . '/' . 'abgaben' . '/';
            
            $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
            $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));

        /* DIE DATEIPRÜFUNG MUSS ADAPTIERT UND AKTIVIERT WERDEN, AUCH WEITER UNTEN NOCHMAL + NEUPOSITIONIERUNG ENTSPRECHEND, DANKE <------------------------------- WICHTIG!-----------------------<<<<<
            /*Überprüfung der Dateiendung
                $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                if(!in_array($extension, $allowed_extensions)) {
                die("Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt");
                }*/

            /*Überprüfung der Dateigröße
                $max_size = 500*1024; //500 KB
                if($_FILES['datei']['size'] > $max_size) {
                die("Bitte keine Dateien größer 500kb hochladen");
                }*/
            
            /*Überprüfung dass das Bild keine Fehler enthält
                if(function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
                $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
                $detected_type = exif_imagetype($_FILES['datei']['tmp_name']);
                if(!in_array($detected_type, $allowed_types)) {
                die("Nur der Upload von Bilddateien ist gestattet");
                }
                }*/
        
            //Pfaderstellung für Upload (inkl. Dateiname u. -endung)
                $dateiname = $filename . $extension;
                $neuerpfad_file= "";
            //Neuer Dateiname falls die Datei bereits existiert
                if(file_exists($ursprungspfad_file . $dateiname)) { //Falls Datei existiert, hänge eine Zahl +1 an den Dateinamen
                    $id = 1;
                    do {
                        $neuerpfad_file = $ursprungspfad_file . $filename . '_'  . $id . '.' . $extension;
                        $neuerpfad_db = $ursprungspfad_db . $filename . '_'  . $id . '.' . $extension;
                        $id++;
                        $dateiname = $filename . '_'  . $id . '.' . $extension;
                    } while(file_exists($neuerpfad_file));
                } else {
                    $neuerpfad_file = $ursprungspfad_file . $filename . '_'  . '1' . '.' . $extension;
                    $neuerpfad_db = $ursprungspfad_db . $filename . '_'  . '1' . '.' . $extension;
                    $dateiname = $filename . '_'  . '1' . '.' . $extension;
                }
            //Ordner anlegen
                if (!file_exists($ursprungspfad_file)) {
                    //Hinzufügen Folder "screenshot" und "rendered" 
                    mkdir($ursprungspfad_file, 0777, true);      
                    //Alles okay, verschiebe Datei an neuen Pfad
                    move_uploaded_file($_FILES['datei']['tmp_name'], $neuerpfad_file);  
                } else {
                    //Alles okay, verschiebe Datei an neuen Pfad
                    move_uploaded_file($_FILES['datei']['tmp_name'], $neuerpfad_file);
                }
            
                //echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';
            
            //--------------------------------------------- DYN. SCREENSHOT-DB-PFAD -------------------------------\\
            $_SESSION['pathfordbscreenshot'] = $pid . '_' . $pname . '/' . $stid . '_' . $stname . '/';

            // ------------------------------------------------------FILEUPLOAD BEENDET-----------------------------------------------------------------\\

            //------------- WICHTIG: if schleife wenn subtid = false dann subtid = 0 // ZUSATZ NEU: "@" verwenden? ---------------\\
                //$stid = $_SESSION['subtaskid']+1;
                // $_SESSION["sbtsktid"] = $stid; 
                //echo "<p>ID der angelegten Subtask in DB (nach Klick auf 'Upload'): '" . $stid . "'</p>";
                echo '<form method="post" action="../subtaskansicht3.php" >';
                echo '<button type="submit" class="btn btn-primary btn-weiter" value="Weiter">Subtask ansehen</button>';
                echo '</form>';
                // ICH WEIß NICHT MEHR WARUM DIESER BUTTON HIER IST, ABER MAN KÖNNTE SICH DEN NEUEN UPLOAD ANSEHEN GEHEN UND 
                // VLT EIN KOMMENTAR SCHREIBEN FÜR DIE NÄCHSTEN BETRACHTER \\ 
                
                //Holen der Mitarbeiterid
                $mitarbeiterid = $_SESSION['mitarbeiterid']; 

                //Eintrag in Tabelle Subtask (Björn)
                // und Eintrag Media-Table (Björn):
                $query3 = "INSERT INTO media (mediapfad, subtaskid, erstellerid, dateiname, updatebeschreibung) VALUES ('".$neuerpfad_db."','".$stid."','".$mitarbeiterid."','".$dateiname."','".$updatebeschreibung."')";

                if (mysqli_query($conn, $query3)) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                } 
                /*

                // und Eintrag Media-Table (Björn):
                $query2 = "UPDATE media SET 
                    mediapfad='$neuerpfad_db',
                    subtaskid='$stid',
                    erstellerid='$mitarbeiterid',
                    dateiname='$dateiname' 
                WHERE mediaid='$mediaid'";

                if (mysqli_query($conn, $query2)) {
                echo "Record updated successfully";
                } else {
                echo "Error updating record: " . mysqli_error($conn);
                }   
                */
                //Vielleicht kann man hiermit was machen?
                $_SESSION['mediaid'] = $mediaid;

                //Öffnen der aktualisierten Subtask ()
                header('Location: ../subtaskansichtdyn.php?name=' . $stid);
                exit;
                mysqli_close($conn);      

                //Leeren stid:
                //$_SESSION["subtaskname"] = false;    
                //$_SESSION['stid'] = false;

        
        } 
     
?>
