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
	<title>Subtaskverwaltung</title>
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
                <!-- $Subtaskname -->
                <h1 class="display-8">Subtaskverwaltung</h1>
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
                    <h5 class="text-left">Subtasks:</h5>
                    <p> Grün/1 = aktive Subtask <br>
                     Gelb/2 = pausierte Subtask <br>
                     Rot/3 = abgeschlossene/Abgebrochene Subtask </p>
                    <table class="table table-striped w-auto" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">Link zu Subtask</th>
                                <th class="verw_th" scope="col">#</th>
                                <th scope="col">Status</th>
                                <th scope="col">Thumbnail</th>
                                <th scope="col">Titel</th>                                
                                <th scope="col">Deadline</th>
                                <th scope="col">Letzte Änderung</th>
                                <th scope="col">Erstelldatum</th>   
                                <th scope="col">Mitarbeiteravatare</th>
                                <th scope="col">Subtasktyp</th>
                                <th scope="col">Beschreibung</th>                    
                            </tr>
                        </thead>

                        <tbody>
                        <?php 
                            /* Urspr. (vor 17.05.2020)
                            $sql = "";
                            $sql = "SELECT * FROM subtask ORDER BY subtaskid ASC";                              
                            $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                            while( $record = mysqli_fetch_assoc($resultset) ) {
                                $date1 = date('d.m.y',strtotime($record['erstelldatum']));
                                $date2 = date('d.m.y',strtotime($record['deadline']));
                                $date3 = date('d.m.y H:i:s',strtotime($record['erstelldatum']));   
                            */                             
                        ?>
                            <tr>  
                                <!-- Dynamischer Link zu Subtaskansicht -->
                                <?php //$linkid = $record['subtaskid']; 
                                //echo $linkid;

                                // Neu (17.05.2020, Björn): Es wird das ERSTE zur Subtask hochgeladene Video gezeigt + Button um das zuletzt hochgeladene Video
                                // sowie Aktualisierung des Versionslogs

                                // 1. Abfrage Subtaskdaten f. Tabelle: 
                                $sql1 = "";
                                $sql1 = "SELECT * FROM subtask ORDER BY subtaskid ASC";                              
                                $resultset = mysqli_query($conn, $sql1) or die("database error:". mysqli_error($conn));
                                while( $record = mysqli_fetch_assoc($resultset) ) {
                                    $stdate1 = date('d.m.y',strtotime($record['erstelldatum']));
                                    $stdate2 = date('d.m.y',strtotime($record['deadline']));
                                    $stdate3 = date('d.m.y H:i:s',strtotime($record['erstelldatum']));
                                    $sttitel = $record['titel'];
                                    $sttyp = $record['typ'];
                                    $stbeschreibung = $record['beschreibung'];
                                    $stid = $record['subtaskid'];
                                    $_SESSION['stid'] = $stid;
                                    $ststatus = $record['status'];
                                    $linkid = $stid;
                                ?>

                                    <td scope="col"><a href="<?php echo '../st_view.php?name=' . $linkid; ?>">#</a></td>
                                    <!--<td><a href='../Subtaskansichtdyn.php?name=".echo$linkid."'>Zur Subtaskansicht</a></td> -->

                                    <td scope="row"><?php echo $stid; ?></td>
                                    <td class="text-center istatus" id="istatus"><?php echo $ststatus; ?></td>                            
                                    <td>Bild Thumbail</td>
                                    <td><?php echo $sttitel; ?></td>
                                    <td><?php echo $stdate2; ?></td>
                                    <td><?php echo $stdate3; ?></td>
                                    <td><?php echo $stdate1; ?></td>
                                    <td>Avatarbilder</td>
                                    <td><?php echo $sttyp; ?></td>
                                    <td><?php echo $stbeschreibung; ?></td>                                
                                </tr>
                                <?php
                                }
                                    /*
                                    // 2. Abfrage Media für Linkgenerierung der ältesten (ursprünglichen) Videodatei
                                    $sql2 = "";
                                    $stid = $_SESSION['stid'];
                                    $sql2 = "SELECT MIN(mediaid) FROM media WHERE subtaskid = $stid ORDER BY mediaid ASC";                              
                                    $resultset = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));
                                    while( $record = mysqli_fetch_assoc($resultset) ) {
                                        $_SESSION['linkid'] = $record['mediaid']; 
                                        echo $linkid;
                                    }
                                */
                                ?>
<!--
                                
                                    ?>
                            -->
                                
                        <?php
                                                        
                        ?>    
                        
                            
                        <!--            
                            <tr>
                                <td scope="row">2</td>
                                <td>Statusfarbe</td>
                                <td>Bild Thumbail</td>
                                <td>Subtask XY</td>
                                <td>Zieldatum</td>
                                <td>Änderungsdatum</td>
                                <td>Erstelldatum</td>
                                <td>Avatarbilder</td>
                                <td>Schnitt,Ton</td>
                                <td>In diesem Subtask...</td>                                
                            </tr>

                            <tr>
                                <td scope="row">3</td>
                                <td>Statusfarbe</td>
                                <td>Bild Thumbail</td>
                                <td>Subtask XY</td>
                                <td>Zieldatum</td>
                                <td>Änderungsdatum</td>
                                <td>Erstelldatum</td>
                                <td>Avatarbilder</td>
                                <td>Schnitt,Ton</td>
                                <td>In diesem Subtask...</td>                                
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
    var inhalt = document.getElementById('istatus').textContent;
    //console.log(inhalt);
    //FOR-Loop in Reverse, da die Klassennamen dynamisch geändert werden! (Björn)
    var items = document.getElementsByClassName("istatus");
    console.log (items.length);
    for (var i = items.length-1; i >= 0; i--) {
        console.log("Subtask " + [i] + " Status: " + items[i].textContent);
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