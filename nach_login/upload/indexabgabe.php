<?php
//Verarbeitung des Uploads UND Weiterleitung von Daten an upload.php sowie Form des Uploads! 
include 'php/dbhandler.php';

    //$updatebeschreibung = $updatebeschreibung_err = "";
    $updatebeschreibung = "";
    $updatebeschreibung_err = "";

    //$_SESSION['updatebeschreibung'] = $_GET['updatebeschreibung'];
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){  
        
        $updatebeschreibung = $_POST['updatebeschreibung'];
        if(empty(trim($updatebeschreibung))){
            $updatebeschreibung_err = "Bitte geben Sie eine Updatebschreibung an.";
        } else {
            $updatebeschreibung = mysqli_real_escape_string($conn, $updatebeschreibung);
            //$_SESSION['updatebeschreibung'] = $updatebeschreibung;
        }
       
        // und Eintrag Media-Table (Björn):
        $query2 = "INSERT INTO media(updatebeschreibung) VALUES('".$updatebeschreibung."')";
        mysqli_query($conn,$query2) or exit(mysqli_error($conn));     
        $mediaid = mysqli_insert_id($conn);
        $_SESSION['mediaid'] = $mediaid;

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
    }

    
?>

<!DOCTYPE html>
<html lang="DE">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" /><!-- Core Meta Data -->
        <title>SEQUENCE</title>
        <style>
        
        </style>
        
    </head>
<body>
<script type="text/javascript">

function fileChange()
{
    //FileList Objekt aus dem Input Element mit der ID "fileA"
    var fileList = document.getElementById("fileA").files;
 
    //File Objekt (erstes Element der FileList)
    var file = fileList[0];
 
    //File Objekt nicht vorhanden = keine Datei ausgewählt oder vom Browser nicht unterstützt
    if(!file)
        return;
 
    document.getElementById("fileName").innerHTML = 'Dateiname: ' + file.name;
    document.getElementById("fileSize").innerHTML = 'Dateigröße: ' +Math.floor(file.size /1024 /1024) + ' MB';
    document.getElementById("fileType").innerHTML = 'Dateitype: ' + file.type;
    document.getElementById("progress").value = 0;
    document.getElementById("prozent").innerHTML = "0%";

    //Weitergabe Dateiname (globale Variable) für Alert nach Upload:
    window.name = file.name;
}

var client = null;

function uploadFile()
{
    //Wieder unser File Objekt
    var file = document.getElementById("fileA").files[0];
    //FormData Objekt erzeugen
    var formData = new FormData();
    //XMLHttpRequest Objekt erzeugen
   	client = new XMLHttpRequest();
	
    var prog = document.getElementById("progress");
 
    if(!file)
        return;
 
    prog.value = 0;
    prog.max = 100;
 
    //Fügt dem formData Objekt unser File Objekt hinzu
    formData.append("datei", file);
 
    client.onerror = function(e) {
        alert("onError");
    };
 
    client.onload = function(e) {
        document.getElementById("prozent").innerHTML = "100%";
        prog.value = prog.max;
    };
 
    client.upload.onprogress = function(e) {
		var p = Math.round(100 / e.total * e.loaded);
        document.getElementById("progress").value = p;            
        document.getElementById("prozent").innerHTML = p + "%";
    };
	
	client.onabort = function(e) {
		alert("Upload abgebrochen");
	};
    
    //Hier werden die Parameter an upload.php geschickt ------------------------------\\
    //client.open("POST", "upload/subtaskabgabe.php");
    //client.send(formData);
    window.alert("Mediaupload und Datenbankeintrag erfolgt");
    window.alert(window.name);
    //document.getElementById("form").reset();
    document.getElementById("fileName").innerHTML = 'Dateiname: ';
    document.getElementById("fileSize").innerHTML = 'Dateigröße: ';
    document.getElementById("fileType").innerHTML = 'Dateitype: ';
    document.getElementById("progress").value = 0;
    document.getElementById("prozent").innerHTML = "0%";
            
}

function uploadAbort() {
	if(client instanceof XMLHttpRequest)
		//Briecht die aktuelle Übertragung ab
		client.abort();
}
</script>

<!-- HIER IST DER WUNDERSCHÖNE CUSTOM UPLOAD BUTTON, aus irgendeinem Grund refresht der aber leider die Page :/ BITTE TROTZDEM BELASSEN DERWEIL, DANKE
<form action="" id="file_ajax" method="post" enctype="multipart/form-data"> 
    !-- Das Label ist eine Fläche HINTER dem "upl video"-Button die den Filechange() triggert durch Klick 
    <label for="file-upload" class="custom-file-upload">
    <i class="fas fa-angle-double-up"></i> upl video
    </label>
    !-----------------------------------------------------Hier der Klick hinter Customupload-Button------------FILE AUSSUCHEN ------------
    <input name="file" type="file" id="file-upload" onchange="fileChange();"/></br>    
    !--------------------------------------------------------------------------------FILE UPLOADEN ----------------------------------------                
    <button name="upload" form="subtaskerstellung" type="submit" value="Upload" class="customsubmit" onclick="uploadFile();">absenden</button>    
    !--------------------------------------------------------------------------------UPLOAD ABBRECHEN ----------------------------------------
    <input name="abort" value="Upload abbrechen" class="customsubmit" type="button" onclick="uploadAbort();" />
</form>
!-- Ausgabe Metadaten --
<div>
    <div id="fileName"></div>
    <div id="fileSize"></div>
    <div id="fileType"></div>
    <progress id="progress" class="progress"></progress> <span id="prozent"></span>
    <br>
    <br>
</div>
-->

<!-- Form für Dateiupload (Buttons, Metadatenausgabe) ---------------------------------------------->
<form id="form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" accept-charset="utf-8" enctype="multipart/form-data">

    <div class="form-group <?php echo (!empty($vorname_err)) ? 'has-error' : ''; ?>">                            
        <small id="helpId" class="text-muted">Updatebeschreibung</small>    
        <textarea rows="5" type="text" name="updatebeschreibung" id="updatebeschreibung" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $updatebeschreibung; ?>"></textarea>
        <span class="help-block"><?php echo $updatebeschreibung_err; ?></span>
    </div>   

    <input name="file"  type="file" id="fileA" onchange="fileChange();"/>
    <br><br>
    <input name="upload" value="Upload" class="customsubmit" type="button" onclick="uploadFile();">
	<input name="abort" value="Upload abbrechen" class="customsubmit" type="button" onclick="uploadAbort();" />
    <br>
   
    <div>
        <div id="fileName"></div>
        <div id="fileSize"></div>
        <div id="fileType"></div>
        <progress id="progress" class="progress"></progress> <span id="prozent"></span>
        <br>
        <button type="submit" value="Upload"class="customsubmit">absenden</button>
        <hr class="my-4">
    </div>
</form>

</body>
</html>