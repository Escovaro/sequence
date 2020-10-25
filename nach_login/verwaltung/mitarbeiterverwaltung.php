<?php
    include '../php/dbhandler.php';
    //include 'comments.php';
    session_start();
    
    //$mitarbeiterid = $_SESSION['mitarbeiterid'];
    $mitarbeiterid = $_SESSION['mitarbeiterid'];
    // echo nl2br("MA-ID: " . $mitarbeiterid);

    // Check if the user is logged in, if not then redirect to login page
    if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
        header("location: ../../login.php");
    }
    //Überprüfung Mitarbeiterposition
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
    <!-- Datatables-Skripte -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/af-2.3.5/b-1.6.2/b-colvis-1.6.2/b-flash-1.6.2/b-html5-1.6.2/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/af-2.3.5/b-1.6.2/b-colvis-1.6.2/b-flash-1.6.2/b-html5-1.6.2/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.js"></script>
    <!-- Sweetalerts -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css"> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    
    <link href="../../style.css" rel="stylesheet">

<script type="text/javascript">

    
                   
// Mitarbeiter anzeigen 
$(document).ready(function(){
        manzeigen();
        function manzeigen(){
            $.ajax({
                url: "action_mitarbeiter.php",
                type: "POST",
                data: {action:"view"},
                success:function(response){
                    // console.log(response);
                    $('#manzeigen').html(response);
                    // Call der Status-Farben Funktion
                    status();
                    
                    $("table").DataTable({
                        //Spracheinstellungen
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/German.json"
                        },
                        //Deaktivierung spez. Sortierungsfunktionen mit Klasse "no-sort"
                        "order": [],
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }]
                    }); 
                    
                }
            });
        }
    // Mitarbeiter-Update 
        $("body").on("click", ".editBtn", function(e){
            // Zuerst holen der Daten zu ID
            // console.log("working");
            e.preventDefault();
            // Holt sich ID des Datensatzes aus der id="" des Edit-Buttons in action_*.php 
            edit_id = $(this).attr('id');
            console.log(edit_id);
            $.ajax({
                url:"action_mitarbeiter.php",
                type: "POST",
                // Übergabe der ID an PHP-Abfrage in getKunde();
                data:{edit_id:edit_id},
                success:function(response){
                    console.log(response);
                    // Gibt die Daten dieses Kunden als Javascript-Objekt zurück
                    data = JSON.parse(response);
                    console.log(data);
                    // Mit Hilfe der ID werden nun im 1. Schritt die Daten an die Felder im Modal übergeben, wobei die ID im hidden-field bleibt
                    $("#mitarbeiterid").val(data.mitarbeiterid);
                    $("#status").val(data.status);
                    $("#email").val(data.email);
                    $("#vorname").val(data.vorname);
                    $("#nachname").val(data.nachname);
                    $("#strasse").val(data.strasse);
                    $("#ort").val(data.ort);
                    $("#plz").val(data.plz);
                    $("#position").val(data.position);
                    $("#mitarbeiterseit").val(data.mitarbeiterseit);
                    $("#skills").val(data.skills);
                }
            });
            
        // Mitarbeiter-Update (Durchführung)
            $("#update").click(function(e){
                // id="form-data" : ID der Form im Modal
                if($("#edit-form-data")[0].checkValidity()){
                    e.preventDefault();
                    $.ajax({
                        url: "action_mitarbeiter.php",
                        type: "POST",
                        data: $("#edit-form-data").serialize()+"&action=update",
                        success:function(response){
                            // console.log(response);
                            console.log(data);
                            Swal.fire({
                                title: 'Mitarbeiter geupdatet!',
                                type: 'success',
                            })
                            $("#editMod").modal('hide');
                            $("#edit-form-data")[0].reset();
                            manzeigen();
                        }
                    })
                }
            })            
        });

    // Mitarbeiter-Delete
        $("body").on("click", ".deleteBtn", function(e){
                e.preventDefault();
                var tr = $(this).closest('tr');
                // console.log(td);
                del_id = $(this).attr('id');
                console.log(del_id);

                Swal.fire({
                        title: 'Sind Sie sicher?',
                        text: "Dies kann nicht rückgängig gemacht werden!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Abbruch!',
                        confirmButtonText: 'Ja, do it!'
                    }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "action_mitarbeiter.php",
                            type: "POST",
                            data:{del_id:del_id},
                            success:function(response){
                                // console.log(del_id);
                                tr.css('background-color', '#ff6666');
                                Swal.fire(
                                    'Gelöscht!',
                                    'Mitarbeiter erfolgreich gelöscht!',
                                    'success'
                                )
                                manzeigen();
                            }
                        });
                    }
                });           
            });

    }); 
        
</script>
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
                <h1 class="display-4">mitarbeiterverwaltung</h1>
            </div> 
            <hr>
            <div class="col-12">
                <p class="lead"></p>
            </div>
        </div>
    </div>

<!-- 2. Table  -->
<div class="container-fluid padding-left">
        <div class="row">
            <!-- SUBTASK-MENÜ -->
            <div class="col">
            <div class="container-fluid text-nowrap verwaltungs-container">
                    <!-- <br><button type="button" class="btn btn-primary m-1 float-right" data-toggle="modal" data-target="#neuKunde"><i class="fas fa-user-plus fa-lg"></i>Neuen Kunden anlegen</button> -->
                    <!-- <a href="#" class="btn btn-success m-1 mt-3 float-right"><i class="fas fa-table fa-lg "></i>Excel exportieren</a><br> -->
                    
                    <div class="table-responsive" id="manzeigen">

                    <!-- INHALT kommt über verw_db.php aus action.php  -->
                        
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

<!-- Mitarbeiter-Editieren-Modal -->
<div class="modal fade" id="editMod">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Mitarbeiter editieren</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <form method="post" action="" id="edit-form-data">
                    <!-- ID-Übergabe -->
                    <!-- $projektid, $titel, $status, $deadline, $beschreibung, $typ, $auftraggeberid -->
                    <div class="form-group">
                        <input type="text" class="form-control" name="mitarbeiterid" id="mitarbeiterid" placeholder="mitarbeiterid" required>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" name="email" id="email" placeholder="email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="status" id="status" placeholder="status" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="vorname" id="vorname" placeholder="vorname" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="nachname" id="nachname" placeholder="nachname." required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="strasse" id="strasse" placeholder="strasse" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="ort" id="ort" placeholder="ort" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="plz" id="plz" placeholder="plz" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="skills" id="skills" placeholder="skills" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="position" id="position" placeholder="position" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" id="update" name="update" class="btn btn-danger btn-block" value="Mitarbeiter updaten" required></button>
                    </div>
                </form>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">schließen</button>
            </div>
            
        </div>
        </div>
    </div>



<script>
//Statusfarben (Björni):
function status(){    
        var items = document.getElementsByClassName("istatus");
        // console.log (items.length);

        // Verändert Feldfarbe über $row['status']
        for (var i = items.length-1; i >= 0; i--) {
            // console.log("Subtask " + [i] + " Status: " + items[i].textContent);
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
    }
</script>
</body>
</html>