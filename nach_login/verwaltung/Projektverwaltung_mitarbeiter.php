<?php
    include '../php/dbhandler.php';
    //include 'comments.php';
    session_start(); 
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
	<title>Projektverwaltung</title>
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
                <h1 class="display-8">Projektverwaltung</h1>
            </div>
        </div>
    </div>
<!-- 2. Table  -->
    <div class="container-fluid padding-left">
        <div class="row">
            <!-- SUBTASK-MENÜ -->
            <div class="col">
                <div class="container-fluid text-nowrap verwaltungs-container table-responsive">
                    <br>
                    <h5 class="text-left">Projekte:</h5>
                    <p> Grün/1 = Aktives Projekt <br>
                     Gelb/2 = Pausiertes Projekt <br>
                     Rot/1 = Abgeschlossenes/Abgebrochenes Projekt </p>
                    <table class="table table-striped w-auto" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">Link zu Projekt</th>
                                <th class="verw_th" scope="col">#</th>
                                <th scope="col">Status</th>
                                <th scope="col">Thumbnail</th>
                                <th scope="col">Titel</th>                                
                                <th scope="col">Deadline</th>
                                <th scope="col">Letzte Änderung</th>
                                <th scope="col">Erstelldatum</th>   
                                <th scope="col">Mitarbeiteravatare</th>
                                <th scope="col">Projekttyp</th>
                                <th scope="col">Beschreibung</th>                    
                            </tr>
                        </thead>

                        <tbody>
                        <?php 
                            $sql = "";
                            $sql = "SELECT * FROM projekt ORDER BY projektid ASC";                              
                            $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                            while( $record = mysqli_fetch_assoc($resultset) ) {
                                $date1 = date('d.m.y',strtotime($record['erstelldatum']));
                                $date2 = date('d.m.y',strtotime($record['deadline']));
                                $date3 = date('d.m.y H:i:s',strtotime($record['erstelldatum']));                                
                        ?>
                            <tr>  
                                <!-- Dynamischer Link zu Projektansicht -->
                                <?php $Linkid = $record['projektid']; 
                                //echo 'IDs der angezeigten Projekte: ' . $Linkid;
                                ?>

                                <td><a href="<?php echo '../projektansichtdyn.php?name=' . $Linkid; ?>">Zur Projektansicht</a></td>
                                <!--<td><a href='../projektansichtdyn.php?name=".echo$Linkid."'>Zur Projektansicht</a></td> -->

                                <td scope="row"><?php echo $record['projektid']; ?></td>
                                <td class="text-center istatus" id="istatus"><?php echo $record['status']; ?></td>                            
                                <td>Bild Thumbail</td>
                                <td><?php echo $record['titel']; ?></td>
                                <td><?php echo $date2; ?></td>
                                <td><?php echo $date3; ?></td>
                                <td><?php echo $date1; ?></td>
                                <td>Avatarbilder</td>
                                <td><?php echo $record['typ']; ?></td>
                                <td><?php echo $record['beschreibung']; ?></td>                                
                            </tr>
                        <?php
                            }                            
                        ?>    
                        
                            
                        <!--            
                            <tr>
                                <td scope="row">2</td>
                                <td>Statusfarbe</td>
                                <td>Bild Thumbail</td>
                                <td>Projekt XY</td>
                                <td>Zieldatum</td>
                                <td>Änderungsdatum</td>
                                <td>Erstelldatum</td>
                                <td>Avatarbilder</td>
                                <td>Schnitt,Ton</td>
                                <td>In diesem Projekt...</td>                                
                            </tr>

                            <tr>
                                <td scope="row">3</td>
                                <td>Statusfarbe</td>
                                <td>Bild Thumbail</td>
                                <td>Projekt XY</td>
                                <td>Zieldatum</td>
                                <td>Änderungsdatum</td>
                                <td>Erstelldatum</td>
                                <td>Avatarbilder</td>
                                <td>Schnitt,Ton</td>
                                <td>In diesem Projekt...</td>                                
                            </tr>   
                        -->            
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

    //Statusfarben (Björn):
    // var inhalt = document.getElementById('istatus').textContent;
    //console.log(inhalt);
    //FOR-Loop in Reverse, da die Klassennamen dynamisch geändert werden! (Björn)
    var items = document.getElementsByClassName("istatus");
    console.log (items.length);
    for (var i = items.length-1; i >= 0; i--) {
        console.log("Projekt " + [i] + " Status: " + items[i].textContent);
        //console.log (items.length);
        if (items[i].textContent < "2"){
            items[i].className="aktiv text-center";
        } else if (items[i].textContent > "2") {
            items[i].className="finished text-center";
        } else {
            items[i].className="hold text-center";
        }        
    }
    
                   
</script>