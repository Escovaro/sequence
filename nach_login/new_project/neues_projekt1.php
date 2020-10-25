<?php
//ACHTUNG HTML Special Chars notwendig für nicht zwingende Felder da kein Trim() durchgef.?
//TO-DO:
//Kundenein- und übergabe
//Ersteller-ID
//ZUERST Button Ordnerstruktur anlegen DANN Button Weiter (Collapse)?
session_start();
include '../php/dbhandler.php';
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
    header("location: ../../login.php");
}
    
$mitarbeiterid = $_SESSION['mitarbeiterid'];

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Neues Projekt</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.8.0/js/all.js"></script>
    <!-- Nächsten für Multiselects -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
	<link href="../../style.css" rel="stylesheet">
</head>
<body>
<header class="sticky-top">
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
</header>

<!-- Zwischenbar 
    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-10">
                <p class="lead" id="jumbotext">Willkommen bei SEQUENCE, einer webbasierten Mediendatenbank- und Projektmanagementapplikation für Profis im kreativen Sektor!</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                <a href="registrieren.php"><button type="button" class="btn btn-outline-secondary btn-lg">Registrierung!</button></a>
            </div>
        </div>
    </div>
-->


<!-- Seitentitel -->
    <div class="container-fluid padding">
        <div class="row welcome text-center">
            <div class="col-12">
                <h1 class="display-4">neues projekt</h1>
            </div> 
            <hr>
            <div class="col-12">
                <p class="lead">Für die Magie sind Sie zuständig.</p>
            </div>
        </div>
    </div>

    
<!-- 2 Säulen -->
    <div class="container-fluid padding beschreibung">
        <hr class="my-4">
        <div class="row padding justify-content-center">
            <div class="col-lg-6">                
                <div class="container register-container">
                    <div class="row" id="register">                        
                        <div class="col-md-6 login-form-links_np">                         
                            <?php 
                                // Initialisierung Variablen
                                $projektnummer = $projektname = $projektbeschreibung = $projekttyp = $projektdate = $projektordner = $kunde = "";
                                $projektnummer_err = $projektname_err = $projektbeschreibung_err = $projekttyp_err = $projektdate_err = $kunde_err = "";

                                // Verarbeitung der Form
                                if($_SERVER["REQUEST_METHOD"] == "POST"){
                                    // Validierung Projektname
                                    if(empty(trim($_POST["projektname"]))){
                                        $projektname_err = "Bitte geben Sie einen Projektnamen an.";
                                    } else if (empty(trim(@$_POST["kunde"]))){
                                        $kunde_err = "Bitte wählen Sie einen Kunden aus.";
                                    } else {
                                        //Test-/ Überprüfungsausgabe:
                                        //echo "empfangener Projektname: " . $projektname;
                                        // Vorbereitung Query
                                        $sql = "SELECT titel FROM projekt WHERE titel = ?";
                                        
                                        if($stmt = mysqli_prepare($conn, $sql)){
                                            // Variablen dem Statement als Parameter übergeben
                                            mysqli_stmt_bind_param($stmt, "s", $param_projektname);
                                            
                                            // Parameter setzen
                                            $param_projektname = trim($_POST["projektname"]);
                                            
                                            // Ausführen Statement
                                            if(mysqli_stmt_execute($stmt)){
                                                /* Speichern der Rückgabe */
                                                mysqli_stmt_store_result($stmt);
                                                
                                                if(mysqli_stmt_num_rows($stmt) == 1){
                                                    $projektname_err = "Es gibt bereit ein Projekt mit selbem Titel.";
                                                } else{
                                                    $projektname = trim($_POST["projektname"]);
                                                }
                                            } else{
                                                echo "Es gab einen Fehler bei der Projektname-Überprüfung.";
                                            }
                                            // Statement schließen
                                            mysqli_stmt_close($stmt);
                                        }
                                    }   
                                    
                                    //Holen der Werte aus Formular    
                                    // "@" ignoriert Fehlermeldungen falls leer/NULL
                                    @$projektnummer = $_POST['projektnummer'];
                                    $projektname = $_POST['projektname'];
                                    @$projektbeschreibung = $_POST['projektbeschreibung'];
                                    @$projekttyp = implode(', ', $_POST['projekttyp']);
                                    @$kunde = $_POST['kunde'];
                                    @$projektdate = $_POST['projektdate'];
                                    //Nicht sicher, ob das so funktioniert (Erstellerid - tut es):
                                    $mitarbeiterid = $_SESSION['mitarbeiterid'];

                                    //Weitergabe Projektname für neues_projekt2.php UND upload.php (letzteres nur wenn KEINE Subtaskanlage)
                                    $_SESSION['projektname'] = $projektname;

                                    //Weitergabe Projektnummer für only_project.php
                                    $_SESSION['projektnummer'] = $projektnummer;
                                    
                                    
                                    // Prüfen, ob alle Fehler-Variablen leer sind
                                    if(empty($projektname_err) && empty($kunde_err) ){
                                        
                                        // Vorbereiten der Query
                                        $sql = "INSERT INTO projekt (titel, beschreibung, typ, deadline, erstellerid, kundeid) VALUES (?, ?, ?, ?, ?, ?)";
                                        
                                        if($stmt = mysqli_prepare($conn, $sql)){
                                            // Variablen dem Statement als Parameter übergeben
                                            mysqli_stmt_bind_param($stmt, "ssssss", $param_projektname, $param_projektbeschreibung, $param_projekttyp, $param_projektdate, $param_erstellerid, $param_kunde);
                                            
                                            // Parameter setzen
                                            $param_projektname = $projektname;                                        
                                            @$param_projektbeschreibung = $projektbeschreibung;
                                            @$param_projekttyp = $projekttyp;
                                            @$param_kunde = $kunde;
                                            @$param_projektdate = $projektdate;
                                            $param_erstellerid = $mitarbeiterid;
                                            /*
                                            echo nl2br("Projektname: " . $param_projektname . "\n");
                                            echo nl2br("Projektbeschreibung: " . $param_projektbeschreibung. "\n");
                                            echo nl2br("Projekttyp: " . $param_projekttyp . "\n");
                                            echo nl2br("Deadline: " . $param_projektdate . "\n");
                                            echo nl2br("Ersteller-ID: " . $param_erstellerid . "\n");
                                            */

                                            // Statement ausführen
                                            if(mysqli_stmt_execute($stmt)){
                                                //Holen der Projektid
                                                $projektid = mysqli_insert_id($conn); 
                                                $_SESSION['projektid'] = $projektid;
                                                // Weiterleitung zu nächster Seite
                                                header('location: neues_projekt2.php');
                                                // echo "Projekt wurde in der Datenbank erfasst.";
                                            } else {
                                                echo nl2br("Bei der Projektanlage ist ein Fehler aufgetreten, bitte schicken Sie uns ein Mail an info@sequence.com.\n");
                                            }
                                            // Schließen des Statements
                                            mysqli_stmt_close($stmt);                                        
                                            } 
                                            
                                            //----------------Ordnererstellung---------------\\
                                            $projektordner = $projektnummer . '_' . $projektname;
                                            //WEITERGABE pname an neue_subtask1 für Pfaderstellung der Subtask:
                                            $_SESSION['projektordner'] = $projektordner;

                                            $structure = '../data/' . $projektordner;
                                            //Weitergabe der Struktur, falls keine Subtask erstellt wird an upload.php
                                            $_SESSION['structure'] = $structure;

                                            
                                            if (file_exists($structure))
                                            {
                                                echo "<br><p>Ein Projekt mit dem selben Namen existiert bereits!</p><br>";
                                                //Link gleich zum Projekt???
                                                ?>
                                                <form method="post" action="./np1.php">
                                                    <button type="submit" class="btn btn-primary btn-weiter" value="Zurück">Zurück</button>
                                                </form>
                                                <?php
                                                die ('');
                                            }
                                            /*
                                            // Erstellung Pfad falls er noch nicht existiert (AUSKOMMENTIEREN JA/NEIN?)    
                                            if (!file_exists($structure)) {
                                                mkdir($structure, 0777, true);
                                                echo "<br><p>Projekt wurde erfolgreich angelegt</p><br>";
                                            } */
                                            } // Close connection
                                            mysqli_close($conn);
                                }                        
                            ?>
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" accept-charset="utf-8" enctype="multipart/form-data">
                                    <!-- Normale Texteingabe -->
                                        <div class="form-group <?php echo (!empty($projektnummer_err)) ? 'has-error' : ''; ?>">                            
                                            <input type="text" name="projektnummer" id="projektnummer" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $projektnummer; ?>">
                                            <small id="helpId" class="text-muted">Projektnummer</small>
                                            <span class="help-block"><?php echo $projektnummer_err; ?></span>
                                        </div>
                                        <div class="form-group <?php echo (!empty($projektname_err)) ? 'has-error' : ''; ?>">                            
                                            <input type="text" name="projektname" id="projektname" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $projektname; ?>">
                                            <small id="helpId" class="text-muted">projekttitel</small>
                                            <span class="help-block"><?php echo $projektname_err; ?></span>
                                        </div>
                                        <div class="form-group <?php echo (!empty($projektbeschreibung_err)) ? 'has-error' : ''; ?>">                            
                                            <input type="text" name="projektbeschreibung" id="projektbeschreibung" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $projektbeschreibung; ?>">
                                            <small id="helpId" class="text-muted">projektbeschreibung</small>
                                            <span class="help-block"><?php echo $projektbeschreibung_err; ?></span>
                                        </div>
                                        <div class="form-group <?php echo (!empty($projektdate_err)) ? 'has-error' : ''; ?>">                            
                                            <input type="date" min="date" max="2050-12-31" name="projektdate" id="projektdate" class="form-control" placeholder="" aria-describedby="helpId" value="<?php echo $projektdate; ?>">
                                            <small id="helpId" class="text-muted">projektdate</small>
                                            <span class="help-block"><?php echo $projektdate_err; ?></span>
                                        </div>
                                    <!-- Multiselect -->
                                        <div class="form-group"> 
                                            <select class="selectpicker" name="kunde" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="Kunde">
                                                <?php 
                                                    require("../php/dbhandler.php");
                                                    echo $r="SELECT * FROM kunde WHERE benutzerid = 1;";
                                                        $rr = mysqli_query($conn,$r);
                                                        while($row= mysqli_fetch_array($rr)) {
                                                            ?>
                                                                <option value="<?php echo $row['kundenID']; ?>"><?php echo $row['firmenname']; ?></option>
                                                            <?php
                                                        }
                                                ?>
                                            </select>
                                            <span class="help-block"><?php echo $kunde_err; ?></span>                            
                                            <select class="selectpicker" name="projekttyp[]" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="needed staff / projekttyp">
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
                                            
                                        
                                            <input id="file-upload" type="file" name="datei"/>     
                                            <br>
                                            <br>    
                                            <button type="submit" class="customsubmit">absenden</button>    
                                        </div>   
                                                                                   
                                </form>                                                         
                        </div> 
                        <div class="col-md-6 login-form-rechts"> 
                        <table>
                        <tr>
                            <td>
                                Folgende Projekte haben Sie bereits angelegt:
                                <br>
                                <?php 
                                    //REFERENZ: finish.php fuer Kundenunterordner !!!
                                    //eventuell die 2 Zeilen auskommentieren und mit Kundenvariable ergänzen.
                                    //zZ wird ganzer data Ordner angezeigt

                                    //$pname = $_SESSION["projektnummer"] . '_' . $_SESSION["projektname"];
                                    //$dir = "../data/" . $pname . "/";
                                
                                    $dir = "../../data/";  //<-----------

                                    // Open a known directory, and proceed to read its contents
                                    //echo "-" . $pname . "<br>";
                                    if (is_dir($dir))
                                        {                                           
                                            if ($dh = opendir($dir))
                                            {
                                                for ($i = 1; $i < 100 ; $i++) {  
                                                    while (($file = readdir($dh)) !== false)
                                                    {                                                        
                                                        if ($file == '.' || $file == '..')
                                                        {
                                                            $file = "";
                                                        }
                                                        else 
                                                        {                                                                                                   
                                                            echo "Projekt " . $i++ . " : " . "$file" . "<br>";
                                                        }
                                                    }
                                                }
                                                closedir($dh);
                                            }
                                        }
                                ?>
                            </td>
                        </tr>
                    </table> 
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
    document.getElementById('projektdate').setAttribute('min', today)

</script>
</html>