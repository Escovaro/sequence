<?php

// Include config file
require_once "../php/dbhandler.php";
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
    header("location: ../../login.php");
    
}

//Überprüfung Mitarbeiterposition
$mitarbeiterid = $_SESSION['mitarbeiterid'];
    
$sql2 = "SELECT position FROM mitarbeiter WHERE mitarbeiterid = $mitarbeiterid";
$resultset = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));
while($record = mysqli_fetch_assoc($resultset) ) {
    $position_ma = $record['position'];
    // echo "MA-Position: " . $position_ma;
}

//Wenn Projektlead oder Admin, dann kein Problem
    if ($position_ma >= '3'){
        $permgranted = true;
        //Sonst Weiterleitung an Verwaltungsansicht für Mitarbeiter
    } else if ($position_ma <= '2') {
        //Test: echo "Kein Zugriff";
        header("location: ../../sonstiges/perm_denied.php");
    }
?>

<!DOCTYPE html>
<html lang="DE">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Projekterstellung</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
        <link href="../../style.css" rel="stylesheet">
        <style>
        
        </style>
        
    </head>
<body>

<!-- Navbars -->
    <div id="include">    
        <script>
            window.onload = function(){
                $.get("Navbar.php", function(data){
                    $("#include").html(data);
                })
            }
        </script>
    </div>

<!-- 1. Row Titel -->
    <div class="container-fluid padding">
        <div class="row welcome text-center">
            <div class="col-12">
                <h1 class="display-4">neues projekt</h1>
            </div> 
            <hr>
            <div class="col-12">
                <p class="lead">abschuss!</p>
            </div>
        </div>
    </div>

<!-- PHP-Teil -->
<div class="container">
    <div class="container-fluid padding-left ">
        <div class="row justify-content-center">
            <div class="col-6 text-left">
                <div class="container-fluid" id="register">                              
                    <table>
                        <tr>
                            <td>
                                <?php                                    
                                    $pnamenummer = $_SESSION["projektnummer"] . "_" . $_SESSION["projektname"];
                                    $pnummer = $_SESSION["projektnummer"];
                                    $pnurname =$_SESSION["projektname"];
                                    //$pname = "1234_testprojekt";
                                    //echo $pname;
                                    $dir = "../data/" . $pnamenummer . "/";
                                    
                                    echo "<br><p>Folgendes Projekt wurde soeben angelegt: </p><br>";
                                    // Open a known directory, and proceed to read its contents
                                    // echo "Projektnummer: " . $pnummer . "<br>";
                                    echo "Projektname: " . $pnurname . "<br><br>";
                                    
                                    // if (is_dir($dir))
                                    //     {                                           
                                    //         if ($dh = opendir($dir))
                                    //         {
                                    //             for ($i = 1; $i < 100 ; $i++) {  
                                    //                 while (($file = readdir($dh)) !== false)
                                    //                 {                                                        
                                    //                     if ($file == '.' || $file == '..')
                                    //                     {
                                    //                         $file = "";
                                    //                     }
                                    //                     else 
                                    //                     {                                                                                                   
                                    //                         echo "Subtask " . $i++ . " : " . "$file" . "<br>";
                                    //                     }
                                    //                 }
                                    //             }
                                    //             closedir($dh);
                                    //         }
                                    //     }
                                    
                                    ?>
                                    <br><br>
                                    
                                    <form method="post" action="./neue_subtask1.php" >
                                        <button type="submit" class="customsubmit2" value="Weiter">Weitere Subtask hinzufügen</button>                                 
                                    </form>

                                    <form method="post" action="../verwaltung/Projektverwaltung.php" >
                                        <button type="submit" class="customsubmit2" value="Weiter">Zur Projektverwaltung</button>
                                    </form>
                                    <br>
                            </td>                                    
                        </tr>
                    </table>                
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>