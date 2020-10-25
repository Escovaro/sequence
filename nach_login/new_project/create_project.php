<?php
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
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-lg-12">
                <!-- $Projektname -->
                <h4 class="display-8">Möchten Sie eine Subtask zum erstellten Projekt anlegen?</h4>
            </div>
        </div>
    </div>

<!-- PHP-Teil -->
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-4 text-left">
                <div class="container-fluid ProjektErstellung-container">                              
                    <table>
                        <tr>
                            <td>   
                                <?php
                                    $pname = $_SESSION["projektnummer"] . '_' . $_SESSION["projektname"];
                                    $structure = '../data/' . $pname;
                                //----------------------DIESER TEIL IST BEREITS NACH neues_projekt2.php kopiert------------\\
                                    if (file_exists($structure))
                                    {
                                        echo "<br><p>Ein Projekt mit dem selben Namen existiert bereits!</p><br>";
                                        //Link gleich zum Projekt???
                                        ?>
                                        <form method="post" action="./np1.php">
                                            <button type="submit" class="btn btn-primary btn-weiter" value="Zurück">Zurück</button>
                                        </form>
                                        <?php
                                        die ('');
                                    }

                                    if (!file_exists($structure)) {
                                        mkdir($structure, 0777, true);
                                        echo "<br><p>Projekt wurde erfolgreich angelegt</p><br>";
                                    }
                                ?>
                                
                                <div class="container">
                                    <form method="post" action="./ns1.php" >
                                        <button type="submit" class="btn btn-primary btn-weiter" value="Weiter">Subtask anlegen</button>
                                    </form>
                                </div>
                                <div class="container">
                                    <form method="post" action="./only_project.php" >
                                        <button type="submit" class="btn btn-primary btn-weiter" value="Abbruch">Ohne Subtask weiter</button>
                                    </form>
                                </div>
                                <!-- ----------------------- BIS HIER KOPIERT ------------------->
                            </td>                                    
                        </tr>
                    </table>                
                </div>
            </div>
        </div>
    </div>
</body>
</html>