<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../php/dbhandler.php';
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
    header("location: ../../login.php");
    
}

//Initialisieren der Variablen
$subtaskname = $subtaskbeschreibung = $subtaskdate = $subtasktyp = "";
$subtaskname_err = $subtaskbeschreibung_err = $subtaskdate_err = $subtasktyp_err = "";

$projektordner = $_SESSION['projektordner'];
echo $projektordner;
$projektname = $_SESSION['projektname'];
$projektid = $_SESSION['projektid'];

?>

<!DOCTYPE html>
<html lang="DE">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Subtaskerstellung</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script> src="mainsubtask.js" </script>
        <link href="../../style.css" rel="stylesheet">
        <style>        
        </style>
        <script type="text/javascript">
        /*
        $("#weiter").click(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'subtaskscript.php',
                data: $('form').serialize()
                
            });
        });
        */

        function fileChange()
            {
                //FileList Objekt aus dem Input Element mit der ID "fileA"
                var fileList = document.getElementById("fileA").files;            
                //File Objekt (erstes Element der FileList)
                var file = fileList[0];            
                //File Objekt nicht vorhanden = keine Datei ausgewählt oder vom Browser nicht unterstützt
                if(!file)
                    return;            
                document.getElementById("fileName").innerHTML = 'Dateiname: ' + file.name;
                document.getElementById("fileSize").innerHTML = 'Dateigröße: ' +Math.floor(file.size /1024 /1024) + ' MB';
                document.getElementById("fileType").innerHTML = 'Dateitype: ' + file.type;
                document.getElementById("progress").value = 0;
                document.getElementById("prozent").innerHTML = "0%";                
            }           
            var client = null;

            function uploadFile()
            {
                //Wieder unser File Objekt
                var file = document.getElementById("fileA").files[0];
                //FormData Objekt erzeugen
                var formData = new FormData();
                //XMLHttpRequest Objekt erzeugen
                client = new XMLHttpRequest();
                
                var prog = document.getElementById("progress");
            
                if(!file)
                    return;
            
                prog.value = 0;
                prog.max = 100;
            
                //Fügt dem formData Objekt unser File Objekt hinzu
                formData.append("datei", file);
            
                client.onerror = function(e) {
                    alert("onError");
                };
            
                client.onload = function(e) {
                    document.getElementById("prozent").innerHTML = "100%";
                    prog.value = prog.max;
                };
            
                client.upload.onprogress = function(e) {
                    var p = Math.round(100 / e.total * e.loaded);
                    document.getElementById("progress").value = p;            
                    document.getElementById("prozent").innerHTML = p + "%";
                };
                
                client.onabort = function(e) {
                    alert("Upload abgebrochen");
                };
                
                //Hier werden die Parameter an upload.php geschickt ------------------------------\\
                client.open("POST", "../upload/upload.php");
                client.send(formData);
                window.alert("Mediaupload und Datenbankeintrag erfolgt.");
            }

            function uploadAbort() {
                if(client instanceof XMLHttpRequest)
                    //Bricht die aktuelle Übertragung ab
                    client.abort();
            }
            /*
            $(document).ready(function(){
                $('#weiter').on('click',function(e){    
                    $("#subtaskerstellung").submit(function(){
                        $(".collapse.subtaskerstellung").collapse("show");
                        $('.collapse.subtaskerstellung').removeClass('collapse');
                        
                    });            
                //e.preventDefault('#rotgruen');
                //e.stopImmediatePropagation('#rotgruen');
                //$('#rotgruen').addClass('customsubmitgruen');
                //$(".collapse.subtaskerstellung").collapse("show");
                //$('.collapse.subtaskerstellung').removeClass('collapse');


                });
                //$("form").submit(function(){
                    //$(".collapse.subtaskerstellung").collapse("show");
                //    $('.collapse.subtaskerstellung').removeClass('collapse');
                //});

            });
*/   
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

        <?php
            //Alle diese Parameter werden voraussichtlich nicht mehr benötigt, da DB-Eintrag Projekt bereits erfolgt.
            //$projektnummer = $_POST['projektnummer'];
            //$projektname = $_POST['projektname'];
            //$projektbeschreibung = $_POST['projektbeschreibung'];
            //$_SESSION['projektnummer'] = $projektnummer; 
            //$_SESSION['projektname'] = $projektname;
            //$_SESSION['projektbeschreibung'] = $projektbeschreibung;

            //Projekttyp (sollte noch in Radiobutton-Form umgewandelt werden, multiple-choice)
            //$projekttyp = $_POST['projekttyp'];
            //$_SESSION['projekttyp'] = $projekttyp;                                    
            // Deadline + Umwandlung Datumformat (Björn) 
            //$projektdeadline = $_POST['projektdate'];                                                                     
            //$date = date('d.m.y',strtotime($projektdeadline));
            //$_SESSION['projektdeadline'] = $date;
        ?>

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
                                        <!-- Die id="mitarbeiterauswahl" gibt die Form-Daten an den Submit-Button in der rechten Spalte weiter-->
                                        <form id="subtaskerstellung" method="POST" class="ajax" action="" accept-charset="utf-8" enctype="multipart/form-data">
                                            <!-- Normale Texteingabe -->
                                            <div class="form-group <?php echo (!empty($subtaskname_err)) ? 'has-error' : ''; ?>">                            
                                                <input type="text" name="subtaskname" id="subtaskname" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $subtaskname; ?>">
                                                <small id="helpId" class="text-muted">subtaskname</small>
                                                <span class="help-block"><?php echo $subtaskname_err; ?></span>
                                            </div>
                                            <div class="form-group <?php echo (!empty($subtaskbeschreibung_err)) ? 'has-error' : ''; ?>">                            
                                                <input type="text" name="subtaskbeschreibung" id="subtaskbeschreibung" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $subtaskbeschreibung; ?>">
                                                <small id="helpId" class="text-muted">subtaskbeschreibung</small>
                                                <span class="help-block"><?php echo $subtaskbeschreibung_err; ?></span>
                                            </div>
                                            <div class="form-group <?php echo (!empty($subtaskdate_err)) ? 'has-error' : ''; ?>">                            
                                                <input type="date" name="subtaskdate" id="subtaskdate" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $subtaskdate; ?>">
                                                <small id="helpId" class="text-muted">subtaskdate</small>
                                                <span class="help-block"><?php echo $subtaskdate_err; ?></span>
                                            </div>
                                            <!-- Multiselect -->
                                            <div class="form-group">                                
                                                <select class="selectpicker" name="subtasktyp[]" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="needed staff / subtasktyp">
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
                                            <input type="submit" id="weiter" class="customsubmit" >Subtask anlegen</button>
                                            <button type="button" id="rotgruen" class="customsubmitrot" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">weiter</button>
                                        </form>    
                                </div>  
                        </div>
                        <div class="col-md-6 login-form-rechts_np">
                            <!-- Hier Collapse mit "Subtaskerstellung?" -->   
                            <div class="collapse Subtaskerstellung" id="collapseExample">
                                <div class="card card-body card-text">                          
                                    
                                    <p>Sie müssen nun zu dieser Subtask noch eine Videodatei hochladen:</p>
                                    <br>
                                    <form action="" method="post" enctype="multipart/form-data">                                    
                                        <input name="file" type="file" id="fileA" onchange="fileChange();"/>
                                        <br><br>
                                        <input name="upload" form="subtaskerstellung" type="submit" value="Upload" class="btn btn-primary btn-weiter" onclick="uploadFile();"/>
                                        

                                        <input name="abort" value="Upload abbrechen" class="btn btn-primary btn-weiter" type="button" onclick="uploadAbort();" />
                                    </form>
                                    <div>
                                        <div id="fileName"></div>
                                        <div id="fileSize"></div>
                                        <div id="fileType"></div>
                                        <progress id="progress" class="progress"></progress> <span id="prozent"></span>
                                        <br>
                                        <br>
                                    </div>
                                        <!-- Letzter Versuch (funktioniert, aber Collapse ist nach Submit wieder zu und keine File ausgewählt)
                                        <input form="subtaskerstellung" type="submit" name="submitFile" style="display: none" id="submit">                                        
                                        <input name="uploadFile" type="file" id="fileA" onchange="document.getElementById('submit').click()">
                                        
                                        
                                        <br><br> -->
                                        <!-- Hier auch Form submitten möglich???? 
                                        <input type="button" id="upload" name="upload" value="Upload" class="btn btn-primary btn-weiter" onclick="uploadFile();" />
                                        <input name="abort" value="Upload abbrechen" class="btn btn-primary btn-weiter" type="button" onclick="uploadAbort();" />
                                    </form>
                                    <div>
                                        <div id="fileName"></div>
                                        <div id="fileSize"></div>
                                        <div id="fileType"></div>
                                        <progress id="progress" class="progress"></progress> <span id="prozent"></span>
                                        <br>
                                        <br>
                                    </div> 
                                               -->           
                                    <!-- HIER WIRD BEI BEIDEN BUTTONS FORM SUBMITTED PROJEKT-ORDNERSTRUKTUR ERSTELLT + DB-EINTRAG TABLE "arbeitetan"        
                                    <button type="submit" form="mitarbeiterauswahl" name="stjanein" value="neue_substask1.php" class="customsubmit2" action="neue_substask1.php">Subtask anlegen</button>
                                    <button type="submit" form="mitarbeiterauswahl" name="stjanein" value="only_project.php" class="customsubmit2"  action="only_project.php">Ohne Subtask weiter</button>
                                    -->  
                                    <button type="" form="" name="stjanein" value="only_project.php" class="customsubmit2"  action="only_project.php">Ohne Subtask weiter</button>
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
</html>
