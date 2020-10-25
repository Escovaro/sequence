<?php
    include '../php/dbhandler.php';
    //include 'comments.php';
    session_start(); 

    //$mitarbeiterid = $_SESSION['mitarbeiterid'];
    $mitarbeiterid = $_SESSION['mitarbeiterid'];
    echo nl2br("MA-ID: " . $mitarbeiterid);

    // Check if the user is logged in, if not then redirect to login page
    if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
        header("location: ../../login.php");
    }
    //Überprüfung Mitarbeiterposition
    $sql2 = "SELECT position FROM mitarbeiter WHERE mitarbeiterid = $mitarbeiterid";
        $resultset = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));
        while($record = mysqli_fetch_assoc($resultset) ) {
            $position_ma = $record['position'];
            echo "MA-Position: " . $position_ma;
        }
        
        //Wenn Projektlead oder Admin, dann kein Problem
        if ($position_ma >= '3'){
            $permgranted = true;
            //Sonst Weiterleitung an Verwaltungsansicht für Mitarbeiter
        } else if ($position_ma <= '2') {
            //Test: echo "Kein Zugriff";
            header("location: Projektverwaltung_mitarbeiter.php");
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
    <!-- 3 Datatables-Skripte -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/af-2.3.5/b-1.6.2/b-colvis-1.6.2/b-flash-1.6.2/b-html5-1.6.2/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/af-2.3.5/b-1.6.2/b-colvis-1.6.2/b-flash-1.6.2/b-html5-1.6.2/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.21/i18n/German.json"></script>
    
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

<!-- Seitentitel -->
<div class="container-fluid padding">
        <div class="row welcome text-center">
            <div class="col-12">
                <h1 class="display-4">projektverwaltung</h1>
            </div> 
            <hr>
            <div class="col-12">
                <p class="lead"><p>
            </div>
        </div>
    </div>

<!-- 2. Table  -->
    <div class="container-fluid padding-left">
        <div class="row">
            <!-- SUBTASK-MENÜ -->
            <div class="col">
                <div class="container-fluid text-nowrap verwaltungs-container table-responsive">
                
                    <table class="table table-striped w-auto" id="myTable">
                    
                        <thead>
                            <tr>
                                <th scope="col" class="no-sort">Link zu Projekt</th>
                                <th class="verw_th no-sort" scope="col">#</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="no-sort">Thumbnail</th>
                                <th scope="col">Titel</th>                                
                                <th scope="col">Deadline</th>
                                <th scope="col">Letzte Änderung</th>
                                <th scope="col">Erstelldatum</th>   
                                <th scope="col" class="no-sort">Mitarbeiteravatare</th>
                                <th scope="col">Projekttyp</th>
                                <th scope="col" class="no-sort">Beschreibung</th>
                                <th scope="col" class="no-sort">Aktion</th>

                            </tr>
                        </thead>

                        <tbody>
                        <?php 
                            //Mitarbeiterposition abfragen:
                            //echo $_SESSION['mitarbeiterid'];
                            $mitarbeiterid = $_SESSION['mitarbeiterid'];
                            
                            $sql0 = "SELECT position FROM mitarbeiter WHERE mitarbeiterid = $mitarbeiterid";
                            $resultset2 = mysqli_query($conn, $sql0) or die("database error:". mysqli_error($conn));
                            while($record = mysqli_fetch_assoc($resultset2)) {
                                $position_ma = $record['position'];
                                //echo "position_ma: " . $position_ma;
                                
                                //Wenn User Admin oder Projektleiter ist, dann alles ausgeben
                                if ($position_ma >= '4' && empty($position_pr) || $position_ma == '3') {
                                    $sql = "";
                                    $sql = "SELECT * FROM projekt ORDER BY projektid ASC";                              
                                    $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                                    while( $record = mysqli_fetch_assoc($resultset) ) {
                                        $date1 = date('d.m.y',strtotime($record['erstelldatum']));
                                        $date2 = date('d.m.y',strtotime($record['deadline']));
                                        $date3 = date('d.m.y H:i:s',strtotime($record['erstelldatum']));   
                                        $pstatus = $record['status'];                             
                                        ?>
                                    <tr>  
                                        <!-- Dynamischer Link zu Projektansicht -->
                                        <?php $Linkid = $record['projektid']; 
                                        //echo 'IDs der angezeigten Projekte: ' . $Linkid;
                                        ?>

                                        <td scope="col"><a href="<?php echo '../projektansichtdyn.php?name=' . $Linkid; ?>" title="Details ansehen" class="text-success"><i class="fas fa-info-circle fa-lg"></i></a></td>
                                        <!--<td><a href='../projektansichtdyn.php?name=".echo$Linkid."'>Zur Projektansicht</a></td> -->

                                        <td scope="row"><?php echo $record['projektid']; ?></td>
                                        <td class="text-center istatus" id="<?php echo $pstatus; ?>"><?php echo $pstatus; ?></td>                            
                                        <td>Bild Thumbail</td>
                                        <td><?php echo $record['titel']; ?></td>
                                        <td><?php echo $date2; ?></td>
                                        <td><?php echo $date3; ?></td>
                                        <td><?php echo $date1; ?></td>
                                        <td>Avatarbilder</td>
                                        <td><?php echo $record['typ']; ?></td>
                                        <td><?php echo $record['beschreibung']; ?></td>    
                                        <td scope="col">
                                        <a href="#" class="text-primary" title="Editieren"><i class="fas fa-edit fa-lg"></i></a>
                                        <a href="#" class="text-danger" title="Löschen"><i class="fas fa-trash-alt fa-lg"></i></a>
                                        </td>                            
                                    </tr>
                                    <?php
                                    } 
                                    //Ansonsten alles dem User zugeteiltem abfragen 
                                } else {  
                                    $sql1 = "SELECT projektid, position FROM arbeiteteannorm WHERE mitarbeiterid = $mitarbeiterid";
                                            $resultset1 = mysqli_query($conn, $sql1) or die("database error:". mysqli_error($conn));
                                            while($record = mysqli_fetch_assoc($resultset1)) {
                                                $position_pr = $record['position'];
                                                $projektid = $record['projektid'];
                                                //print_r($projektid);
                                                //echo $position_pr;

                                                    if ($position_pr == '1' || $position_pr == '2' && $position_ma == '1' || $position_ma == '2'){                        
                                                        $sql2 = "SELECT * FROM projekt WHERE projektid = $projektid ORDER BY projektid ASC";
                                                            $resultset = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));

                                                            while( $record = mysqli_fetch_assoc($resultset)) {
                                                                $pdate1 = date('d.m.y',strtotime($record['erstelldatum']));
                                                                $pdate2 = date('d.m.y',strtotime($record['deadline']));
                                                                $pdate3 = date('d.m.y H:i:s',strtotime($record['erstelldatum']));
                                                                $ptitel = $record['titel'];
                                                                $ptyp = $record['typ'];
                                                                $pbeschreibung = $record['beschreibung'];
                                                                $pid = $record['projektid'];
                                                                $_SESSION['pid'] = $pid;
                                                                $pstatus = $record['status'];
                                                                $linkid = $pid;
                                                                ?>
                                                                <tbody>
                                                                <tr>                                   
                                                                    <td scope="col"><a href="<?php echo '../projektansichtdyn.php?name=' . $linkid; ?>">projektdetails</a></td>
                                                                    <!--<td><a href='../Subtaskansichtdyn.php?name=".echo$linkid."'>Zur Subtaskansicht</a></td> -->

                                                                    <td scope="row"><?php echo $pid; ?></td>
                                                                    <td class="text-center istatus" id="<?php echo $pstatus; ?>"><?php echo $ststatus; ?></td>                            
                                                                    <td>Bild Thumbail</td>
                                                                    <td><?php echo $ptitel; ?></td>
                                                                    <td><?php echo $pdate2; ?></td>
                                                                    <td><?php echo $pdate3; ?></td>
                                                                    <td><?php echo $pdate1; ?></td>
                                                                    <td>Avatarbilder</td>
                                                                    <td><?php echo $ptyp; ?></td>
                                                                    <td><?php echo $pbeschreibung; ?></td>                                
                                                                </tr>
                                                            <?php
                                                            }
                                                    }
                                            } 
                                } 
                            }
                            ?>           
                        </tbody>
                    </table>
                    <br>                    
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

//Datatables-Aufruf
    $(document).ready( function () {
        $('#myTable').DataTable({
            //Spracheinstellungen
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/German.json"
                },
            //Deaktivierung spez. Sortierungsfunktionen mit Klasse "no-sort"
            "order": [],
            "columnDefs": [ {
            "targets"  : 'no-sort',
            "orderable": false,
            }]
        });
    });


    

//Statusfarben (Björni):
    
    //var inhalt = document.getElementById('1').textContent;
    var items = document.getElementsByClassName("istatus");
    console.log (items.length);

    // Verändert Feldfarbe über $row['status']
    for (var i = items.length-1; i >= 0; i--) {
        console.log("Subtask " + [i] + " Status: " + items[i].textContent);
        //console.log (items.length);
        if (items[i].textContent < "2"){
            items[i].innerHTML = '<i class="fas fa-exclamation"></i>';
            items[i].className = "alert alert-success text-center";
            //items[i].innerHTML = "pausiert";
        } else if (items[i].textContent > "2") {
            items[i].innerHTML = '<i class="fas fa-lock"></i>';
            items[i].className = "alert alert-danger text-center";
            //items[i].innerHTML = "pausiert";
        } else {
            items[i].innerHTML = '<i class="fas fa-lock-open"></i>';
            items[i].className = "alert alert-warning text-center";
            //items[i].innerHTML = '<small><i class="fas fa-lock"></i> abgeschlossen/abgebrochen';
        }        
    }

    /* Verändert Feldfarbe über $row['status']
    for (var i = items.length-1; i >= 0; i--) {
        console.log("Subtask " + [i] + " Status: " + items[i].textContent);
        //console.log (items.length);
        if (items[i].textContent < "2"){
            items[i].className="alert alert-success text-center";
            //items[i].innerHTML = "pausiert";
        } else if (items[i].textContent > "2") {
            items[i].className="alert alert-warning text-center";
            //items[i].innerHTML = "pausiert";
        } else {
            items[i].className="alert alert-danger text-center";
            //items[i].innerHTML = '<small><i class="fas fa-lock"></i> abgeschlossen/abgebrochen';
        }        
    }
    */
</script>