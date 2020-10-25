<?php
    session_start();
    include '../php/dbhandler.php';
?>

<?php
    //Übername der Ordnerstruktur (Michi)
    $upload_folder = $_SESSION["structure"];
    $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
    $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
    $id = 1;
    echo $upload_folder;

    //$upload_folder = '../data/'; //Upload-Verzeichnis
    //$filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
    //$extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
    //$id = 1;

    //Variablen für Speichern in Tabelle SUBTASK (Björn)
    $stname = $_SESSION["stname"];
    $beschreibung = $_SESSION['beschreibung'];
    $deadline = $_SESSION['deadline'];
    $typ = $_SESSION['typ'];
    $stid = $_SESSION['stid'];

    //Variablen für Speichern in Tabelle PROJEKT (Björn)
    $pname = $_SESSION["pname"];
    $pbeschreibung = $_SESSION['projektbeschreibung'];
    $pdeadline = $_SESSION['projektdeadline'];
    $ptyp = $_SESSION['projekttyp'];
    $pid = $_SESSION['pid'];

    //Pfaderstellung zum Upload (Michi)
    $new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;

    //Pfaderstellung zum Speichern in der Datenbank (Björn)   
    $pathfordb = $pname . '/' . $stname . '/' . $filename.'_'.$id.'.'.$extension;

    
    
    //Dateinamen aendern (fortlaufende Nummer) (Michi)
    if(file_exists($new_path))
    { 
        do
        {
            //$_SESSION["new_path"] = $_SESSION["upload_folder"].$_SESSION["filename"].'_'.$_SESSION["id"].'.'.$_SESSION["extension"];
            $id++;
        }
        while(file_exists($new_path));
    }
    
    //move datei (Michi)
    move_uploaded_file($_FILES['datei']['tmp_name'], $new_path);
    //$new_path = $_SESSION["new_path"]  
    

    // -------------- DIE FOLGENDEN QUERIES SOLLTEN IN EINE TRANSACTION! ----------------------
    $query1 = "INSERT IGNORE INTO projekt(titel,deadline,beschreibung,typ) VALUES('".$pname."','".$pdeadline."','".$pbeschreibung."','".$ptyp."')";
    mysqli_query($conn,$query1);
       /*     INSERT IGNORE //    ON DUPLICATE KEY UPDATE */
    
    // Holen der ProjektID
    $result1= mysqli_query($conn, "SELECT * from projekt ORDER BY projektid DESC LIMIT 1");
    while ($row = mysqli_fetch_assoc($result1)) {
        $pid = $row['projektid'];             
            }
    $_SESSION['projektid'] = $pid;    

    //Eintrag in Tabelle Subtask (Björn)
    $query2 = "INSERT INTO subtask(titel,deadline,beschreibung,typ,projektid) VALUES('".$stname."','".$deadline."','".$beschreibung."','".$typ."','".$pid."')";
    mysqli_query($conn,$query2);

    // Holen SubtaskID
    $result2= mysqli_query($conn, "SELECT * from subtask ORDER BY subtaskid DESC LIMIT 1");
    while ($row = mysqli_fetch_assoc($result2)) {
        $stid = $row['subtaskid'];             
            }
    $_SESSION['subtaskid'] = $stid;

    //Eintrag des Pfades u. SubtaskID in die DB (Björn):
    $query3 = "INSERT INTO media(mediapfad,subtaskid) VALUES('".$pathfordb."','".$stid."')";
    mysqli_query($conn,$query3);
           
    echo "Upload erfolgreich!"; 
    
?>
