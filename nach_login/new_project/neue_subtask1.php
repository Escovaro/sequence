<?php

/* 
Was passiert bei der Subtaskerstellung:
1.  Übergabe Form-Data (Subtaskname, etc.), bei Klick auf "Subtask anlegen" erfolgt Eintrag in DB-Table "subtask" nach Übermitteln der Form-Data an subtaskscript.php
    Es öffnet sich der Fileupload: Verweis auf Index.php (für Progressbar + Eintrag DB-Table Media) + von dort aus auf upload.php für den "wirklichen" FileUpload 
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../php/dbhandler.php';
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

//Initialisieren der Variablen
$subtaskname = $subtaskbeschreibung = $subtaskdate = $subtasktyp = "";
$subtaskname_err = $subtaskbeschreibung_err = $subtaskdate_err = $subtasktyp_err = "";

//$projektordner = $_SESSION['projektordner'];
//echo $projektordner;
$projektname = $_SESSION['projektname'];
//$projektid = $_SESSION['projektid'];
//$message = include ('subtaskscript.php');

// WICHTIG: Folgende Variable zur Erkennung in upload.php dass EINE SUBTASK ERSTELLT WURDE
$_SESSION['st_erstellt'] = true;

?>

<!DOCTYPE html>
<html lang="DE">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Subtaskerstellung</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
        <!-- MULTISELECT: Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <!-- MULTISELECT: Latest compiled and minified JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>        
        <!--JQuery Form Validation -->
        
        
        <link href="../../style.css" rel="stylesheet">
        <style>        
        </style>
        <script type="text/javascript">        
            //--------------------------------------------JQuery/Ajax, geht an subtaskscript.php -------------------------------------\\
            $(document).ready(function(){
            // Deaktivierung Submit-Button wenn kein Subtasktitel angegeben
                $('.customsubmit').attr('disabled',true);
                    $('#subtaskname').keyup(function(){
                        if($(this).val().length !=0)
                            $('.customsubmit').attr('disabled', false);            
                        else
                            $('.customsubmit').attr('disabled',true);
                    })
                $("#submit").click(function(){      
                    // Validation eigentlich obsolet   
                    $('#subtaskerstellung').validate({
                        rules: {
                            subtaskname: {
                                required: true,
                            }                            
                        }, 
                        messages: {
                            subtaskname: "Bitte Subtasktitel angeben!"
                        },       
                        submitHandler: function(form) {   
                            var subtaskname = $('#subtaskname').val();
                            var subtaskbeschreibung = $('#subtaskbeschreibung').val();
                            var subtaskdate = $('#subtaskdate').val();
                            var subtasktyp = $('#subtasktyp').val();                     
                            //alert('test ob triggered');
                            $.ajax({
                                url: "subtaskscript.php",
                                type: "post",
                                data: {subtaskname : subtaskname , subtaskbeschreibung : subtaskbeschreibung , subtaskdate : subtaskdate , subtasktyp : subtasktyp},
                                success: function (response) {
                                    //$('#textausgabe').text('Daten übermittelt');
                                    //response kommt vom server
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    console.log(textStatus, errorThrown);
                                }
                            });
                        
                        }  
                    })
                });                
            });
            //Resetted Form für neue Subtask (Links!, Rechts muss in upload.php erfolgen, da Adressierung über Form-ID):
            function reset() {
            //document.getElementById("form").reset();
            document.getElementById("subtaskerstellung").reset();
            $('.customsubmit').attr('disabled',true);
                    $('#subtaskname').keyup(function(){
                        if($(this).val().length !=0)
                            $('.customsubmit').attr('disabled', false);            
                        else
                            $('.customsubmit').attr('disabled',true);
                    })
            }
            
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
                <h1 class="display-4">neue subtask</h1>
            </div> 
            <hr>
            <div class="col-12">
                <p class="lead">Step 1/3</p>
            </div>
        </div>
    </div>

<!-- PHP-Teil --> 


<!-- 2 Säulen -->
    <div class="container-fluid padding beschreibung">
        <hr class="my-4">
        <div class="row padding justify-content-center">
            <div class="col-lg-6">                
                <div class="container register-container">
                    <div class="row" id="register">                        
                        <div class="col-md-6 login-form-links_np">  
                                <div class="container" id="schritt2">
                                    <br>
                                    <p>Subtaskerstellung für Projekt: <?php echo "'". $projektname ."'"; ?>:</p>
                                        <br> 
                                        <!---------------------------------------------------------------------- FORM BEGINNT HIER ----------------------------------------------------------->
                                        <!-- Die id="mitarbeiterauswahl" gibt die Form-Daten an den Submit-Button in der rechten Spalte weiter                                   FORM    -->
                                        <form name="subtaskerstellung" id="subtaskerstellung" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="ajax" accept-charset="utf-8" enctype="multipart/form-data">
                                            <!-- Normale Texteingabe -->
                                            <div class="form-group">                            
                                                <input type="text" name="subtaskname" id="subtaskname" class="form-control" aria-describedby="helpId" required>
                                                <small id="helpId" class="text-muted">subtasktitel</small>
                                                
                                            </div>
                                            <div class="form-group">                            
                                                <input type="text" name="subtaskbeschreibung" id="subtaskbeschreibung" class="form-control"aria-describedby="helpId">
                                                <small id="helpId" class="text-muted">subtaskbeschreibung</small>
                                                
                                            </div>
                                            <div class="form-group">                            
                                                <input type="date" min="date" max="2050-12-31" name="subtaskdate" id="subtaskdate" class="form-control" aria-describedby="helpId">
                                                <small id="helpId" class="text-muted">subtaskdate</small>
                                                
                                            </div>
                                            <!-- Multiselect -->
                                            <div class="form-group">                                
                                                <select class="selectpicker" id="subtasktyp" name="subtasktyp[]" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="needed staff / subtasktyp">
                                                <?php 
                                                    require("../php/dbhandler.php");
                                                    echo $r="SELECT * FROM skills ;";
                                                        $rr = mysqli_query($conn,$r);
                                                        while($row= mysqli_fetch_array($rr))
                                                        {
                                                            ?>
                                                    <option value="<?php echo $row['skill']; ?>"><?php echo $row['skill']; ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- SUBMIT BUTTON ----------------------------------------------------------------------------------------->
                                            <input type="submit" name="submit" id="submit" class="customsubmit" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Subtask anlegen</button>
                                            <!-- ALT: Button der von Grün auf Rot geschaltet wurde <button type="button" id="rotgruen" class="customsubmitrot" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">weiter</button> -->
                                            
                                            <!------------------------------------------ AUSGABE OB DB-EINTRAG erfolgt --------------------------------------------->
                                            <p id="textausgabe"></p>

                                        </form>    
                                </div>  
                        </div>
                        <div class="col-md-6 login-form-rechts_np">
                            <!-- Hier Collapse mit "Subtaskerstellung?"------------------------------------- -->   
                            <div class="collapse Subtaskerstellung" id="collapseExample">
                                <div class="card cardsubtaskerstellung card-body card-text">                          
                                    
                                    <p>Sie müssen nun zu dieser Subtask noch eine Videodatei hochladen:</p>
                                    <br>
                                    <?php               
                                    // ------------------------------------------- Verweis auf Index.php für Progressbar + Eintrag DB-Table Media + danach upload.php für den "echten" FileUpload --------------------------------------\\         
                                    include '../upload/index.php';
                                    ?> 
                                    <!-- Weitere Subtask -->
                                    
                                        <button type="submit" class="customsubmit2" value="Weiter" onclick="reset()" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Weitere Subtask</button>
                                    
                                    <!-- Button subtask betrachten -->
                                        
                                    <!-------------------------------- Weiterleitung OHNE Subtask Sollte Porjektübersicht where projekt = session_projektid--------------------------------->    
                                    <a href="finish.php">                            
                                    <button type="button" class="customsubmit2" action="finish.php">Abschliessen</button>
                                    </a>
                            </div>
                                                    
                        </div>
                </div>      
            </div>    
        </div>        
    </div>
<!-- Eine Säule -->
    <div class="container-fluid padding beschreibung">
        <div class="row padding justify-content-center">
            <div class="col-lg-9">
                 
            </div>
        </div>
        <hr class="my-4">
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
            
                <div class="col-12">
                    <hr class="dark-top">
                    <img src="../../img/Schriftlogo_mitlogo_weiss_schmal.png">
                    <hr class="dark smol">
                    <h5>Kontakt</h5>
                    <hr class="dark">
                    <p>555-555-555</p>
                    <p>email@gmx.at</p>
                    <p>teststr. 53</p>
                    <p>Wien, Österreich</p>
                </div>
            
                <div class="col-12">
                    <hr class="dark-100">
                    <h5>&copy; Sequence</h5>
                </div>
            </div>
        </div>
    </footer>

</body>

<script>
//Datepickermin / -value
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var MM = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    yyyy-MM-dd
    today = yyyy + '-' + MM + '-' + dd;
    //document.write(today);

    var dateControl = document.querySelector('input[type="date"]');
    var dateControl2 = document.querySelector('input[min="date"]');
    
    dateControl.value = today;
    document.getElementById('subtaskdate').setAttribute('min', today)

</script>

</html>
