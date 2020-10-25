<?php
    include '../php/dbhandler.php';
    //include 'comments.php';
    session_start();
    // Check if the user is logged in, if not then redirect to login page
    if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
        header("location: ../../login.php");
        
    }

$query ="SELECT * FROM mitarbeiter ORDER BY mitarbeiterID ASC";  
$result = mysqli_query($conn, $query);  
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mitarbeiterverwaltung</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
    <link href="../../style.css" rel="stylesheet">
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
                <h1 class="display-8">Mitarbeiterverwaltung</h1>
            </div>
        </div>
    </div>
<!-- 2. Row Table  -->
    <div class="container-fluid padding-left">
        <div class="row">
            <!-- SUBTASK-MENÜ -->
            <div class="col">
                <div class="container-fluid text-nowrap verwaltungs-container table-responsive">
                    <br>
                    <h5 class="text-left">Mitarbeiter:</h5>
                    <table class="table table-striped w-auto">
                        <thead>
                            <tr>
                                <th class="verw_th" scope="col">#</th>
                                <th scope="col">Avatar</th>
                                <!-- Name = Vorname + Nachname -->
                                <th scope="col">Name</th>
                                <th scope="col">Login</th>
                                <th scope="col">Status</th>
                                <!-- NTH Position: Bei Klick auf Feld werden alle Mitarbeiter in gleicher Position angezeigt -->
                                <th scope="col">Position</th>
                                <!-- NTH siehe Position: Bei Klick auf Feld werden alle Mitarbeiter mit überschneidenden Skills angezeigt -->
                                <th scope="col">Skillset</th>
                                <th class="text-center" scope="col">Mitarbeiter seit</th>
                                <th scope="col">Straße</th>
                                <th scope="col">PLZ</th>                                
                                <th scope="col">Ort</th>                                
                                <!-- Projekte, Subtasks NTH: Anzahl + Link jeweils zu Projekten u. Subtasks -->
                                <th scope="col">Anzahl Projekte / Subtasks</th>                                                
                            </tr>
                        </thead>

                        <tbody id="myTable">
                            <?php 
                                while($row = mysqli_fetch_array($result))  
                                {   
                                    // Konvertiert das Datum der Datenbank:
                                    $timestamp = $row["mitarbeiterseit"];                                    
                                    $date = date('d.m.Y',strtotime($timestamp));
                                    
                                    
                                    // Datenausgabe:
                                    echo '  
                                    <tr>
                                            <td scope="row">'.$row["mitarbeiterid"].'</td>
                                            <td>Mitarbeiterbild</td>  
                                            <td>'.$row["vorname"]. " " .$row["nachname"].'</td>  
                                            <td>'.$row["email"].'</td>
                                            <td>'.$row["status"].'</td>                                                                                      
                                            <td>Teamlead</td>
                                            <td>'.$row["skills"].'</td> 
                                            <td class="text-center">'.$date.'</td> 
                                            <td>'.$row["strasse"].'</td> 
                                            <td>'.$row["plz"].'</td> 
                                            <td>'.$row["ort"].'</td> 
                                            <td class="text-center">1 / 2</td> 
                                    </tr>  
                                    ';  
                                }  
                          ?>                            
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>

<!-- Links -->
    <div class="container-fluid padding">
        <div class="row text-center padding">
            <div class="col-12">
                <h2>Connect</h2>
            </div>
            <div class="col-12 social padding">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-google-plus-g"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
<!-- Footer -->
    <footer>
        <div class="container-fluid padding">
            <div class="row text-center">
                <div class="col-md-4">
                    <hr class="dark">
                    <img src="../../img/Schriftlogo_mitlogo_weiss_schmal.png">
                    <hr class="dark">
                    <p>555-555-555</p>
                    <p>email@gmx.at</p>
                    <p>teststr. 53</p>
                    <p>Wien, Österreich</p>
                </div>
                <div class="col-md-4">
                    <hr class="dark">
                    <h5>Unsere Zeiten</h5>
                    <hr class="dark">
                    <p>Montags 8Uhr - 17Uhr</p>
                </div>
                <div class="col-md-4">
                    <hr class="dark">
                    <h5>HTL Wien West</h5>
                    <hr class="dark">
                    <p>Montags 8Uhr - 17Uhr</p>
                </div>
                <div class="col-12">
                    <hr class="dark-100">
                    <h5>&copy; Sequence</h5>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

<script>
    
// Suchfunktion
$(document).ready(function(){
  $("#tableSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>