<?php
include 'php/dbhandler.php';
session_start();
$response = array(
'status' => 0,
'message' => 'Form submission failed, please try again.'
);

//Wenn die Form submittet wird
if(isset($_POST['updatebeschreibung']) || isset($_SESSION['stid']) || isset($_SESSION['mitarbeiterid'])){
    $updatebeschreibung = $_POST['updatebeschreibung'];
    $stid = $_SESSION["stid"];
    $mitarbeiterid = $_SESSION['mitarbeiterid'];
    $uploadStatus = 1; 
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

    $ursprungspfad_file = '../data/' . $pid . '_' . $pname . '/' . $stid . '_' . $stname . '/' . 'abgaben' . '/';
    $ursprungspfad_db = '../data/' . $pid . '_' . $pname . '/' . $stid . '_' . $stname . '/' . 'abgaben' . '/';

    //Überprüfen, ob leer
    if(!empty($updatebeschreibung)){
        $uploadstatus = "1";
        $uploadedFile = '';
        if(!empty($_FILES["file"]["name"])){
            //Upload-Ordner-Definition
            $filename = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);

            //Pfaderstellung für Upload (inkl. Dateiname u. -endung)
            //$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

            $dateiname = $filename . $extension;
            $neuerpfad_file= "";
            
            //Bestimmte Filetypes zulassen
            /*$allowtypes = array('mp4');
            if(in_array($fileType, $allowtypes)){                     
            */
            //Neuer Dateiname falls die Datei bereits existiert
            if(file_exists($ursprungspfad_file . $dateiname)) { //Falls Datei existiert, hänge eine Zahl +1 an den Dateinamen
                $id = 1;
                do {
                    $neuerpfad_file = $ursprungspfad_file . $filename . '_'  . $id . '.' . $extension;
                    $neuerpfad_db = $ursprungspfad_db . $filename . '_'  . $id . '.' . $extension;
                    $dateiname = $filename . '_'  . $id . '.' . $extension;
                    $id++;
                    
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
                move_uploaded_file($_FILES['file']['tmp_name'], $neuerpfad_file); 
                $uploadstatus == 1;
                } else {
                    //Alles okay, verschiebe Datei an neuen Pfad
                    move_uploaded_file($_FILES['file']['tmp_name'], $neuerpfad_file);
                    $uploadstatus == 1;
                } 
                /*
                if ($uploadstatus == 0){
                    $response['message'] = 'Sorry, beim Upload ist ein Fehler aufgetreten';
                } 
                */
                if($uploadstatus == 1){
                    include_once 'upload/dbhander.php';

                    //DB-Eintrag MEDIA
                    $query3 = "INSERT INTO media (mediapfad, subtaskid, erstellerid, dateiname, updatebeschreibung) VALUES ('".$neuerpfad_db."','".$stid."','".$mitarbeiterid."','".$dateiname."','".$updatebeschreibung."')";
                    if (mysqli_query($conn, $query3)) {
                        $status = 1;
                        $response = ['status'];
                        $message = 'Upload erfolgreich abgeschlossen';
                        $response = ['message'];
                    } else {
                        echo "Error updating record: " . mysqli_error($conn);
                    }
                }    
        } else {
            $uploadstatus = 0;
            $response['message'] = 'Bitte laden Sie eine Datei hoch.'; 
            //$response['message'] = 'Bitte nur Videodateien hochladen.';   
        }
    } else {
        $uploadstatus = 0;
        $response['message'] = 'Bitte geben Sie eine Updatebeschreibung an.';  
    }
} else {
    $uploadstatus = 0;
    $response['message'] = 'Kritische Variable leer.';  
}
echo json_encode($response);
