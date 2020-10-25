<?php
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
$mitarbeiter = $teamlead = $projektlead = "";
$mitarbeiter_err = $teamlead_err = $projektlead_err = "";

$projektname = $_SESSION['projektname'];
$projektid = $_SESSION['projektid'];

//Link von Buttons Rechts (ns1.php o. only_project.php) -------------------------- ACHTUNG: HIER EIN "@" vor??? ---------------------\\
@$link = $_POST["stjanein"];
//echo $link; 

//Verarbeiten der übermittelten Daten
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //Wurden zumindest Mitarbeiter hinzugefügt?
    if($_POST["mitarbeiter"] == ""){
        $mitarbeiter_err = "Bitte geben Sie min. 1 Mitarbeiter an.";
        } else {
            $mitarbeiter = implode(', ', $_POST["mitarbeiter"]);
            @$teamlead = implode(', ', $_POST["teamlead"]);
            @$projektlead = implode(', ', $_POST["projektlead"]);
            //echo nl2br("projektid: " . $projektid . "\n");                                       
            //echo nl2br("projektlead: " . $projektlead . "\n");
            //echo nl2br("teamlead: " . $teamlead . "\n");
            //echo nl2br("mitarbeiter: " . $mitarbeiter. "\n");

            if(empty($mitarbeiter_err) && empty($teamlead_err) && empty($projektlead_err)){
                $sql = "INSERT INTO arbeitetan (projektid, projektlead, teamlead, mitarbeiter) VALUES (?, ?, ?, ?)";

                if($stmt = mysqli_prepare($conn, $sql)){
                    mysqli_stmt_bind_param($stmt, "ssss", $param_projektid, $param_projektlead, $param_teamlead, $param_mitarbeiter);

                    $param_projektid = $projektid;
                    $param_mitarbeiter = $mitarbeiter;
                    $param_teamlead = $teamlead;
                    $param_projektlead = $projektlead;
                    //echo nl2br("projektid: " . $param_projektid . "\n");
                    //echo nl2br("mitarbeiter: " . $param_mitarbeiter. "\n");
                    //echo nl2br("teamlead: " . $param_teamlead . "\n");
                    //echo nl2br("param_projektlead: " . $projektlead . "\n");                

                    if(mysqli_stmt_execute($stmt)){
                        //echo $link;   
                        header("location: $link");                 
                        // was tun? Archiv: < header('location: np3.php'); <- Dort ging es ursprünglich hin nach DB-Übertrag (Crumb) >
                    } 
                }
                    /*else {                    
                        exit(mysqli_error($conn));
                        // oder dann?
                    }
                    mysqli_stmt_close($stmt);
                }
                    */
                //$mitarbeiterid = $_POST['mitarbeiter'];
                @$teamleadid = $_POST['teamlead'];
                @$projektleadid = $_POST['projektlead'];

                $projektid = $projektid;
                $pos1 = '1';
                $pos2 = '2';
                $pos3 = '3';
                
                foreach ($_POST['mitarbeiter'] as $mid) {                
                    $query1 = "INSERT INTO arbeiteteannorm (projektid, mitarbeiterid, position) VALUES ('".$projektid."','".$mid."','".$pos1."')";
                    mysqli_query($conn,$query1) or exit(mysqli_error($conn));
                }
                if (isset($_POST["teamlead"])){
                    foreach ($_POST["teamlead"] as $tlid) {                
                        $query2 = "INSERT INTO arbeiteteannorm (projektid, mitarbeiterid, position) VALUES ('".$projektid."','".$tlid."','".$pos2."')";
                        mysqli_query($conn,$query2) or exit(mysqli_error($conn));
                    }
                }
                if (isset($_POST["projektlead"])){
                    foreach ($_POST["projektlead"] as $plid) {                
                        $query3 = "INSERT INTO arbeiteteannorm (projektid, mitarbeiterid, position) VALUES ('".$projektid."','".$plid."','".$pos3."')";
                        mysqli_query($conn,$query3) or exit(mysqli_error($conn));
                    }
                }
                mysqli_close($conn);
        
                if(mysqli_stmt_execute($stmt)){
                    //echo $link;   
                    header("location: $link");                 
                    // was tun? Archiv: < header('location: np3.php'); <- Dort ging es ursprünglich hin nach DB-Übertrag (Crumb) >   
            } 
        }
    }
}
  
?>

<!DOCTYPE html>
<html lang="DE">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Projekterstellung</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
        <link href="../../style.css" rel="stylesheet">
        <style>        
        </style>
        <script type="text/javascript">
        // // var subtaskname = $('#subtaskname').val();
        // // Deaktivierung Weiter-Button wenn Mitarbeiterauswahl leer
        // $(document).ready(function(){
        //     $('.customsubmit').attr('disabled',true);                
        //             if($('#mitarbeiter').val() = ''){
        //                 alert('Bitte wählen Sie min. einen Mitarbeiter aus!');
        //                 $('.customsubmit').attr('disabled', false);            
        //             } else {
        //                 $('.customsubmit').attr('disabled',true);
        //             }
        //         }) 
           
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
                <h1 class="display-4">neues projekt</h1>
            </div> 
            <hr>
            <div class="col-12">
                <p class="lead">Step 2/3</p>
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
                                    <p>Bitte wählen Sie die Mitarbeiter für das Projekt <?php echo "'". $projektname ."'"; ?>:</p>
                                        <br> 
                                        <!-- Die id="mitarbeiterauswahl" gibt die Form-Daten an den Submit-Button in der rechten Spalte weiter-->
                                        <form id="mitarbeiterauswahl" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" accept-charset="utf-8" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <i class="fas fa-user-tie"></i>                                     
                                                <select class="selectpicker" name="projektlead[]" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="projektlead">
                                                <?php 
                                                    require("../php/dbhandler.php");
                                                    echo $r="SELECT * FROM mitarbeiter WHERE position = 3 ORDER BY vorname ASC;";
                                                        $rr = mysqli_query($conn,$r);
                                                        while($row= mysqli_fetch_array($rr))
                                                        {
                                                            ?>
                                                            <option value="<?php echo $row['mitarbeiterid']; ?>"><?php echo $row['vorname']; ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select> 
                                            </div>

                                            <div class="form-group">   
                                                <i class="fas fa-users"></i>                             
                                                <select class="selectpicker" name="teamlead[]" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="teamlead">
                                                <?php 
                                                    require("../php/dbhandler.php");
                                                    echo $r="SELECT * FROM mitarbeiter WHERE position = 2 ORDER BY vorname ASC;";
                                                        $rr = mysqli_query($conn,$r);
                                                        while($row= mysqli_fetch_array($rr))
                                                        {
                                                            ?>
                                                            <option value="<?php echo $row['mitarbeiterid']; ?>"><?php echo $row['vorname']; ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>       
                                            </div>

                                            <div class="form-group">   
                                                <i class="fas fa-user"></i>                              
                                                <select class="selectpicker" id="mitarbeiter" name="mitarbeiter[]" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="mitarbeiter*" required>
                                                <?php 
                                                    require("../php/dbhandler.php");
                                                    echo $r="SELECT * FROM mitarbeiter ORDER BY vorname ASC;";
                                                        $rr = mysqli_query($conn,$r);
                                                        while($row= mysqli_fetch_array($rr))
                                                        {
                                                            ?>
                                                            <!-- <option value=""></option> -->
                                                            <option value="<?php echo $row['mitarbeiterid']; ?>"><?php echo $row['vorname']; ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>  
                                            </div>      
                                            <!-- HIER COLLAPSE -->
                                            <button type="button" id="weiter" class="customsubmit" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">weiter</button>
                                            
                                            <!-- Alter "Zurück-Button
                                            <form method="POST" action="./np1.php"> 
                                                <button type="submit" class="btn btn-primary btn-weiter" value="Zurück">Zurück</button>
                                            </form> -->
                                            <br><small id="helpId" class="text-muted"><a href="https://fontawesome.com/license">Icons from Fontawesome</a></small>
                                            <!-- https://fontawesome.com/icons?d=gallery&q=users -->
                                        </form>    
                                    </div>  
                        </div>
                        <div class="col-md-6 login-form-rechts_np">
                            <!-- Hier Collapse mit "Subtaskerstellung?" -->   
                            <div class="collapse Subtaskerstellung" id="collapseExample">
                                <div class="card cardsubtaskerstellung card-body card-text">                          
                                    <!-- Könnte man später noch befüllen --------------------------------------
                                    <p>Erstelldatum: </p>
                                    <p>Letzte Änderung: </p>

                                    <div class="Projekt-Beschreibung">
                                        <p>Projektbeschreibung: </p>
                                        <p>
                                        "But I must explain to you ....
                                        </p>
                                    </div>
                                    <p>Typ: </p>
                                    <p>Ersteller: </p>
                                    <p>Auftraggeber/Kunde: </p>
                                    -->
                                    <p>Möchten Sie zu diesem Projekt eine o. mehrere Subtasks erstellen?<br>
                                    Wenn nicht, wird dieses Projekt im Backend so behandelt, als würde es aus einer einzigen Subtask bestehen.</p><br>

                                    <!-- HIER WIRD BEI BEIDEN BUTTONS FORM SUBMITTED ORDNERSTRUKTUR ERSTELLT + DB-EINTRAG TABLE "arbeitetan" -->         
                                    <button type="submit" form="mitarbeiterauswahl" name="stjanein" value="neue_subtask1.php" class="customsubmit2" action="neue_substask1.php">Subtask anlegen</button>
                                    <button type="submit" form="mitarbeiterauswahl" name="stjanein" value="only_project.php" class="customsubmit2"  action="only_project.php">Ohne Subtask weiter</button>
                                    
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
