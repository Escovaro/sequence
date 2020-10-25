<?php
session_start();

// Include config file
require_once "../php/dbhandler.php";

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

<!-- Seitentitel -->
<div class="container-fluid padding">
        <div class="row welcome text-center">
            <div class="col-12">
                <h1 class="display-4">neues projekt</h1>
            </div> 
            <hr>
            <div class="col-12">
                <p class="lead">Step 3/3</p>
            </div>
        </div>
    </div>

<!-- PHP-Teil --> 
    <div class="container-fluid padding beschreibung">
        <hr class="my-4">
        <div class="row padding justify-content-center">
            <div class="col-lg-6">                
                <div class="container register-container">
                    <div class="row" id="register">                        
                        <div class="col-md-6 login-form-links_np ">  
                                
                              
                            <br>                          
                            <table>
                                <tr>
                                    <td><p>Bitte laden Sie zum Abschluss der Projekterstellung noch eine Videodatei hoch:</p><br><br>
                                        <?php
                                            
                                                //Verweis auf File-Upload zum direkten upload nach Subtask Anlage
                                                include '../upload/index.php';
                                                
                                        ?>                                    
                                            <form method="post" action="./finish.php" >
                                                <button type="submit" class="btn btn-primary btn-weiter" value="Fertig">Fertig</button>
                                            </form>
                                            
                                    </td>                                    
                                </tr>
                            </table>
                            </div>
                        <div class="col-md-6 login-form-rechts_np">
                         

                    </div>
                </div>      
            </div>    
        </div>        
    </div>
</body>
</html>