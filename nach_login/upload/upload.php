<?php
//DATENBANK-EINTRAG nach Projekt- und Subtaskerstellung
//Erhält seine Daten von index.php!
    session_start();
    include '../php/dbhandler.php';
?>

<?php
            
        

        $pname = $_SESSION["projektname"];
        $pid = $_SESSION['projektid'];
        

        $mitarbeiterid = $_SESSION["mitarbeiterid"]; 
        
        $neuerpfad_media= "";

    //Falls vorher Subtask angelegt wurde:          
        if (isset($_SESSION['$st_erstellt']) && $_SESSION['$st_erstellt'] == true){

            $stid = $_SESSION['stid'];
            @$stname = $_SESSION['subtaskname'];
            //----------------------------------------------------------------------File-Upload ---------------------------------------------------------\\
            
            //Speicherpfad für die DB:
            $ursprungspfaddb = '../../data/' . $pid . '_' . $pname . '/' . $stid . '_' . $stname . '/';            
            
            //Speicherpfad auf welchen beim jetzigen Speichern die Datei hinverschoben wird:
            $ursprungspfad = '../data/' . $pid . '_' . $pname . '/' . $stid . '_' . $stname . '/';

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
                $neuerpfad_media= "";
            //Neuer Dateiname falls die Datei bereits existiert
                if(file_exists($ursprungspfad . $dateiname)) { //Falls Datei existiert, hänge eine Zahl +1 an den Dateinamen
                    $id = 1;
                    do {
                        $neuerpfad_media = $ursprungspfad . $filename . '_'  . $id . '.' . $extension;
                        $id++;
                    } while(file_exists($neuerpfad_media));
                } else $neuerpfad_media = $ursprungspfad . $filename . '_'  . '1' . '.' . $extension;

            //vorstellen des Schrittes zurück im Verzeichnis quasi "cd ..":
                $ursprungspfad = '../' . $ursprungspfad;

                $neuerpfad_media_move = '../' . $neuerpfad_media; 

            //Ordner anlegen
                if (!file_exists($ursprungspfad)) {
                    //Hinzufügen Folder "screenshot" und "rendered" 
                    $ursprungspfad1 = $ursprungspfad  . 'screenshots' . '/';
                    mkdir($ursprungspfad1, 0777, true);

                    $ursprungspfad2 = $ursprungspfad  . 'rendered' . '/';
                    mkdir($ursprungspfad2, 0777, true);
                    echo "<p>Filespace für Subtask '" . $stname . "' wurde erfolgreich eingerichtet!</p>";
    

            //Alles okay, verschiebe Datei an neuen Pfad
                move_uploaded_file($_FILES['datei']['tmp_name'], $neuerpfad_media_move);
                //echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';
            
            //--------------------------------------------- DYN. SCREENSHOT-DB-PFAD -------------------------------\\
            //$_SESSION['pathfordbscreenshot'] = $pid . '_' . $pname . '/' . $stid . '_' . $stname . '/';

            // ------------------------------------------------------FILEUPLOAD BEENDET-----------------------------------------------------------------\\

            //------------- WICHTIG: if schleife wenn subtid = false dann subtid = 0 // ZUSATZ NEU: "@" verwenden? ---------------\\
                //$stid = $_SESSION['subtaskid']+1;
                // $_SESSION["sbtsktid"] = $stid; 
                //echo "<p>ID der angelegten Subtask in DB (nach Klick auf 'Upload'): '" . $stid . "'</p>";
                echo '<form method="post" action="../subtaskansicht3.php" >';
                echo '<button type="submit" class="btn btn-primary btn-weiter" value="Weiter">Subtask ansehen</button>';
                echo '</form>';
                
            } 

            //Eintrag in Tabelle Subtask (Björn)
                //Holen der Mitarbeiterid
                $mitarbeiterid = $_SESSION['mitarbeiterid']; 
                
                //holen der letzten Subtaskid von Subtaskscript.php für media-table
                $stid = $_SESSION['stid'];
                
                // und Eintrag Media-Table (Björn):
                $query2 = "INSERT INTO media(mediapfad,subtaskid, erstellerid, dateiname) VALUES('".$neuerpfad_media."','".$stid."','".$mitarbeiterid."','".$dateiname."')";
                mysqli_query($conn,$query2) or exit(mysqli_error($conn));     


                //Leeren stid:
                $_SESSION["subtaskname"] = "";    
                $_SESSION['stid'] = "";
                $stid = '';

                $_SESSION['$st_erstellt'] = false;

            //FALLS KEINE SUBTASK ERSTELLT WURDE:
        } else { 
            /* $fakestname = $_SESSION['projektname']; */  
            //Leeren der Subtaskid:  
                

                $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
                $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));

            //----------------------------------------------------------------------File-Upload ---------------------------------------------------------\\
            //Da Subtaskname = Projektname hier entsprechender Pfad ($stid ist noch leer!):
                //$ursprungspfad = '../../data/' . $pid . '_' . $pname . '/' . $fakestid . '_' . $fakesttitel . '/';

            //Schauen, ob noch keine Subtask zum zuletzt erstelltem Projekt in DB         
            //$result1 = mysqli_query($conn, "SELECT * FROM subtask WHERE projektid = $pid");            
            //$row_cnt = mysqli_num_rows($result1);
                //Falls nicht dann:
                //if ($row_cnt == 0) {      
                    $result2 = mysqli_query($conn, "SELECT * from projekt WHERE projektid = $pid");
                    while ($row = mysqli_fetch_assoc($result2)) {
                        $pr_beschreibung = $row['beschreibung'];
                        $pr_deadline = $row['deadline'];
                        $pr_typ = $row['typ'];
                        $pr_titel = $row['titel'];        
                    }
                
                //Eintrag in Tabelle Subtask (Björn)
                    //Holen der Mitarbeiterid
                    $mitarbeiterid = $_SESSION['mitarbeiterid']; 
                    $query3 = "INSERT INTO subtask(titel, deadline, beschreibung, typ, projektid, erstellerid) VALUES('".$pr_titel."','".$pr_deadline."','".$pr_beschreibung."','".$pr_typ."','".$pid."','".$mitarbeiterid."')";
                    mysqli_query($conn, $query3);
                    //holen der letzten id für media-table
                    $stid = mysqli_insert_id($conn);                            
                
                //Da Subtaskname = Projektname hier entsprechender Pfad:
                    $ursprungspfad = '../../data/' . $pid . '_' . $pr_titel . '/' . $stid . '_' . $pr_titel . '/';
                    $ursprungspfad_db = '../data/' . $pid . '_' . $pr_titel . '/' . $stid . '_' . $pr_titel . '/';

                //Pfaderstellung zum Hinterlegen in Media-Datenbank (Björn)   ACHTUNG STNAME IST HIER DER PROJEKTNAME!!!!! <---------------------------
                    //$pathfordb = $pname . '/' . $stname . '/' . $filename.'_'.$id.'.'.$extension;
                    //$neuerpfad_media = $pid . '_' . $pname . '/' . $fakestid . '/' . $fakesttitel . '/' . $filename . '_' . $id . '.' . $extension; 
                    
                    if (!file_exists($ursprungspfad)) {
                        //Hinzufügen Folder "screenshot" und "rendered" 
                        $ursprungspfad1 = $ursprungspfad  . 'screenshots' . '/';
                        mkdir($ursprungspfad1, 0777, true);

                        $ursprungspfad2 = $ursprungspfad  . 'rendered' . '/';
                        mkdir($ursprungspfad2, 0777, true);

                        echo "<p>Filespace für Projekt '" . $pr_titel . "' wurde erfolgreich eingerichtet!</p>";
                    }

                //Pfaderstellung für Upload (inkl. Dateiname u. -endung)
                    $dateiname = $filename . '.' . $extension;
                    $neuerpfad_media = $ursprungspfad . $dateiname;
                    $neuerpfad_media_db = $ursprungspfad_db . $dateiname;
                //Neuer Dateiname falls die Datei bereits existiert
                    

                        //Alles okay, verschiebe Datei an neuen Pfad
                        move_uploaded_file($_FILES['datei']['tmp_name'], $neuerpfad_media);

                        // und Eintrag Media-Table (Björn):
                        $query4 = "INSERT INTO media(mediapfad, subtaskid, erstellerid, dateiname) VALUES('".$neuerpfad_media_db."','".$stid."','".$mitarbeiterid."','".$dateiname."')";
                        mysqli_query($conn,$query4) or exit(mysqli_error($conn));
                    
                        
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
        } 
       // }  
     
?>
