<?php
    session_start();
    include '../php/dbhandler.php';
    //include 'comments.php';
    // Check if the user is logged in, if not then redirect to login page
    if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
        header("location: ../../login.php");
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Subtaskansicht</title>
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
                <h1 class="display-8">Kundenverwaltung</h1>
            </div>
        </div>
    </div>
<!-- 2. Row Videplayer, Toolbar, Subtaskmenu (rechts)  -->
    <div class="container-fluid padding-left">
        <div class="row">
            <!-- SUBTASK-MENÜ -->
            <div class="col">
                <div class="container-fluid text-nowrap verwaltungs-container table-responsive">
                    <br>
                    <h5 class="text-left">Kunden:</h5>
                    <table class="table table-striped w-auto">
                        <thead>
                            <tr>
                                <th class="verw_th" scope="col">#</th>
                                <th scope="col">Firmenname</th>
                                <th scope="col">Kontakt</th>
                                <th scope="col">Mail</th>                                
                                <th scope="col">Telefon</th>
                                <th scope="col">Straße</th>
                                <th scope="col">PLZ</th>   
                                <th scope="col">Ort</th>  
                                <th scope="col">Land</th> 
                                <th scope="col">Branche</th>
                                <th scope="col">Beschreibung</th>    
                                <th scope="col">Kunde seit</th>  
                                <!-- Feld Projekte zeigt Anzahl Projekte + Verlinkung Stored Procedure Projekte des Kunden -->
                                <th scope="col">Projekte</th>                
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td scope="row">1</td>
                                <td>Firma XY</td>
                                <td>Thomas Muster</td>
                                <td>muster@xy.at</td>
                                <td>01/123456</td>
                                <td>Musterstr. 6</td>
                                <td>1010</td>
                                <td>Wien</td>
                                <td>Österreich</td>
                                <td>Werbung</td>  
                                <td>Dieser Kunde ...</td> 
                                <td>Timestamp</td> 
                                <td>5</td>                               
                            </tr>

                            <tr>
                                <td scope="row">1</td>
                                <td>Firma XY</td>
                                <td>Thomas Muster</td>
                                <td>muster@xy.at</td>
                                <td>01/123456</td>
                                <td>Musterstr. 6</td>
                                <td>1010</td>
                                <td>Wien</td>
                                <td>Österreich</td>
                                <td>Werbung</td>  
                                <td>Dieser Kunde ...</td> 
                                <td>Timestamp</td> 
                                <td>5</td>                               
                            </tr>

                            <tr>
                                <td scope="row">1</td>
                                <td>Firma XY</td>
                                <td>Thomas Muster</td>
                                <td>muster@xy.at</td>
                                <td>01/123456</td>
                                <td>Musterstr. 6</td>
                                <td>1010</td>
                                <td>Wien</td>
                                <td>Österreich</td>
                                <td>Werbung</td>  
                                <td>Dieser Kunde ...</td> 
                                <td>Timestamp</td> 
                                <td>5</td>                               
                            </tr>           
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