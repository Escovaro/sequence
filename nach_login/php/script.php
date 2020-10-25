<?php 
session_start();
include '../php/dbhandler.php'; 
//SPEICHERN DES SCREENSHOTS (Björn)

//Pfaderstellung zum Speichern in der Datenbank (Björn)
//Holt sich diese Variablen aus upload.php 
$stname = $_SESSION["stname"];
$pname = $_SESSION["pname"];
$path = $pname . '/' . $stname . '/';
$pathforfolder = '../data/' . $path . 'screenshots/image';

// Speichern in Folder   
define('UPLOAD_DIR', $pathforfolder);   
$img = $_POST['imgBase64'];   
$img = str_replace('data:image/png;base64,', '', $img);   
$img = str_replace(' ', '+', $img);   
$data = base64_decode($img);   
$file = UPLOAD_DIR . uniqid() . '.png';   
$success = file_put_contents($file, $data);   
print $success ? $file : 'Unable to save the file.'; 

//Eintrag in DB (Screenshot)
//Variable f. SubtaskID:
$sbtsktid = $_SESSION['subtaskid'];
//Pfaderstellung für DB
$pathfordb = '../data/' . $path . 'screenshots/' . $file;

//Eintrag in Tabelle Subtask (Björn)
$query2 = "INSERT INTO screenshot(screenshotpfad,subtaskid) VALUES('".$pathfordb."','".$sbtsktid."')";
mysqli_query($conn,$query2);
  
?> 