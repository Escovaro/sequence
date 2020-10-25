<?php
    //Datenbankverbindung
    include '../php/dbhandler.php';
    
    //Methoden/Funktionen
    include_once 'verw_db.php';
    include_once 'action.php';
    
    //session_start(); 

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
    <!-- Datatables-Skripte -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/af-2.3.5/b-1.6.2/b-colvis-1.6.2/b-flash-1.6.2/b-html5-1.6.2/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/af-2.3.5/b-1.6.2/b-colvis-1.6.2/b-flash-1.6.2/b-html5-1.6.2/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.js"></script>
    <!-- Sweetalerts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    
    <link href="../../style.css" rel="stylesheet">

<script type="text/javascript">
    $(document).ready(function(){
        panzeigen();
        function panzeigen(){
            $.ajax({
                url: "action.php",
                type: "POST",
                data: {action:"view"},
                success:function(response){
                    console.log(response);
                    $('#panzeigen').html(response);
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
        // id="insert" ist ID von Submit-Btn Modal
        $("#insert").click(function(e){
            // id="form-data" : ID der Form im Modal
            if($("#form-data")[0].checkValidity()){
                e.preventDefault();
                $.ajax({
                    url: "action.php",
                    type: "POST",
                    data: $("#form-data").serialize()+"&action=insert",
                    success:function(response){
                        // console.log(response);
                        swal({
                            title: 'Kunde hinzugefügt!',
                            type: 'success',
                        })
                        $("#neuKunde").modal('hide');
                        $("#form-data")[0].reset();
                        panzeigen();
                    }
                })
            }
        })
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
            <div class="container-fluid text-nowrap verwaltungs-container">
                    <br><button type="button" class="btn btn-primary m-1 float-right" data-toggle="modal" data-target="#neuKunde"><i class="fas fa-user-plus fa-lg"></i>Neuen Kunden anlegen</button>
                    <a href="#" class="btn btn-success m-1"><i class="fas fa-table fa-lg"></i>Excel exportieren</a><br>
                    
                    <div class="table-responsive" id="panzeigen">

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

<!-- Neuen Kunden hinzufügen -->
<div class="modal fade" id="neuKunde">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Kunden hinzufügen</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <form method="post" action="" id="form-data">
                <div class="form-group">
                    <input type="text" class="form-control" name="firmenname" placeholder="Firmenname" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="kontaktvorname" placeholder="Kontaktvorname" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="kontaktnachname" placeholder="Kontaktnachname" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="telefon" placeholder="Telefonnr." required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="strasse" placeholder="strasse" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="plz" placeholder="PLZ" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="ort" placeholder="Ort" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="land" placeholder="Land" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="branche" placeholder="Branche" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="beschreibung" placeholder="Beschreibung" required>
                </div>
                <div class="form-group">
                    <input type="submit" id="insert" name="insert" class="$btn btn-danger btn-block" value="Kunden hinzufügen" required></button>
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

<!-- Editieren-Modal -->
<div class="modal fade" id="editMod">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Projekt ändern</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <form method="post" action="" id="edit-form-data">
                <!-- ID-Übergabe -->
                <input type="hidden" name="id" id="id">
                <div class="form-group">
                    <input type="text" class="form-control" id="firmenname" placeholder="Firmenname" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="kontaktvorname" placeholder="Kontaktvorname" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="kontaktnachname" placeholder="Kontaktnachname" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" id="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="telefon" placeholder="Telefonnr." required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="strasse" placeholder="strasse" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="plz" placeholder="PLZ" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="ort" placeholder="Ort" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="land" placeholder="Land" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="branche" placeholder="Branche" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="beschreibung" placeholder="Beschreibung" required>
                </div>
                <div class="form-group">
                    <input type="submit" id="update" name="update" class="$btn btn-danger btn-block" value="Projekt updaten" required></button>
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

</body>
</html>

<script>
    

//Statusfarben (Björni):
    function status(){    
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
    }
</script>