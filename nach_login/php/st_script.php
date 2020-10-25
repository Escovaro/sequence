<?php 
//------------------- SPEICHERN DES SCREENSHOTS (Björn)
session_start();
include '../php/dbhandler.php'; 


//Pfaderstellung zum Speichern in der Datenbank (Björn)
//Holt sich diese Variable aus subtaskansichtdyn.php 
$mitarbeiterid = $_SESSION["mitarbeiterid"];   
$mediaid = $_SESSION['mediaid'];

if(isset($_SESSION["sbtsktid"])) {
    $stid = $_SESSION["sbtsktid"];
} else {
    $stid = $_SESSION['stid'];
}


//echo 'console.log('. json_encode($my_arr, JSON_HEX_TAG) .')';
//echo '<script>console.log('.$stid.')</script>';
//echo '<script>console.log("\"$stid\"")</script>';
//"'<script>console.log(\"$stid\")</script>'";

//Holen des Subtasktitels:
$sql = "SELECT subtaskid FROM media WHERE mediaid = '".$mediaid."'";
$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
while($record = mysqli_fetch_assoc($resultset)){
$stid = $record['subtaskid'];
}

//Holen des Subtasktitels:
$sql1 = "SELECT titel, projektid FROM subtask WHERE subtaskid = '".$stid."'";
$resultset = mysqli_query($conn, $sql1) or die("database error:". mysqli_error($conn));
while($record = mysqli_fetch_assoc($resultset)){
$stname = $record['titel'];
$pid = $record['projektid'];
}
//Holen des Projekttitels
$sql2 = "SELECT titel FROM projekt WHERE projektid = '".$pid."'";
$resultset = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));
while($record = mysqli_fetch_assoc($resultset)){
$pname = $record['titel'];
}

// Die stid ist wsl eine andere ID als der name des Ordners, da die erste ST im Projekt die Nummer 1 ist! (OUTDATED Info glaube ich, 18.05.2020)
$path = '../data/' . $pid . '_' . $pname . '/' . $stid . '_' . $stname . '/';
$pathforfolder = '../../data/' . $path . 'screenshots/image';


echo $pathforfolder;
$uniqid = uniqid();

//Pfaderstellung für DB
$pathfordb = $path . 'screenshots/' . $file . 'image' . $uniqid . '.png';

// Speichern in Folder   
define('UPLOAD_DIR', $pathforfolder);   
$img = $_POST['imgBase64'];   
$img = str_replace('data:image/png;base64,', '', $img);   
$img = str_replace(' ', '+', $img);   
$data = base64_decode($img);   
$file = UPLOAD_DIR . $uniqid . '.png';   
$success = file_put_contents($file, $data);   
print $success ? $file : 'Unable to save the file.'; 

//Eintrag in DB (Screenshot)
//Variable f. SubtaskID:
//$sbtsktid = $_SESSION['subtaskid'];


//Eintrag in Tabelle Screenshot (Björn)
$query2 = "INSERT INTO screenshot(screenshotpfad,subtaskid, mediaid, erstellerid) VALUES('".$pathfordb."','".$stid."','".$mediaid."','".$mitarbeiterid."')";
mysqli_query($conn,$query2);
  
error_reporting(E_ALL);
ini_set('display_errors', 1);

?> 

