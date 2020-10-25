<?php

//Reine Subtaskerstellung?
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../php/dbhandler.php';

    $subtaskname = $_POST["subtaskname"];
    $subtaskbeschreibung = $_POST["subtaskbeschreibung"];
    $subtaskdate = $_POST["subtaskdate"];
    $date = date('d.m.y',strtotime($subtaskdate));
    @$subtasktyp = implode(', ', $_POST["subtasktyp"]);

    $projektordner = $_SESSION['projektordner'];
    //echo $projektordner;
    $projektname = $_SESSION['projektname'];
    $projektid = $_SESSION['projektid'];

    // Für Upload.php
    $_SESSION["subtaskname"] = $subtaskname;

    //Validierung
    $errorEmpty = false;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $subtaskname = $_POST["subtaskname"];
        ////--------------------------------------------------ZUERST REIN FÜR ORDNERERSTELLUNG:---------------------------------------------------------------------\\
        // Erstellung Ordnerstruktur (Michi) (ZUSATZ "/screenshots" von Björn)
        $_SESSION["structure"] = '../data/' . $projektordner . '/' . $subtaskname . '/screenshots' . '/';
    
        //Duplikat für Videoupload (Björn)
        $_SESSION["structure1"] = '../data/' . $projektordner . '/' . $subtaskname . '/';
    
        echo "<br>";
        //echo $pname . "<br>";
        //echo $stname . "<br>";
        //echo $_SESSION["structure"] . "<br>";
    
        if (file_exists($_SESSION["structure"]))
        {
            echo "<p>Eine Subtask mit dem selben Namen existiert bereits.<p>";
            ?>
            <form method="post" action="./ns1.php">
                <button type="submit" class="btn btn-primary btn-weiter" value="Zurück">Zurück</button>
            </form>
            <?php
            die ('');
        }
        /*
        //if (!file_exists($_SESSION["structure"])) {
            mkdir($_SESSION["structure"], 0777, true);
            echo "<p>Filespace für Subtask '" . $subtaskname . "' wurde erfolgreich eingerichtet!</p>";
    
            //------------- WICHTIG: if schleife wenn subtid = false dann subtid = 0 // ZUSATZ NEU: "@" verwenden? ---------------\\
            //$stid = $_SESSION['subtaskid']+1;
            // $_SESSION["sbtsktid"] = $stid; 
            //echo "<p>ID der angelegten Subtask in DB (nach Klick auf 'Upload'): '" . $stid . "'</p>";
            echo '<form method="post" action="../subtaskansicht3.php" >';
            echo '<button type="submit" class="btn btn-primary btn-weiter" value="Weiter">Subtask ansehen</button>';
            echo '</form>';
    
            //echo "<p>Videodatei dieser Subtask hinzufügen: </p>";                                        
        */
            //Verweis auf File-Upload zum direkten upload nach Subtask Anlage
            include '../upload/index.php';
            $showupload = true;
        //}
    
        //---------------------------------------DANN FORMINHALT -> DB----------------------------------------------------------------------------------------------\\
    
        //Wurde ein Subtaskname erteilt?
        if(empty($subtaskname)){
            //Alt
            $subtaskname_err = "Bitte geben Sie dieser Subtask einen Titel.";
            //Neu:
            echo "<span class='errormsgsbtsk'>Bitte geben Sie der Subtask einen Titel!</span>";
            $errorEmpty  = true;

        } else {
            $subtaskname = $_POST["subtaskname"];
            //Bereitstellung für upload.php:
            $_SESSION['subtaskname'] = $_POST["subtaskname"];

            $mitarbeiterid = $_SESSION['mitarbeiterid'];
            $subtaskbeschreibung = $_POST["subtaskbeschreibung"];
            $subtaskdate = $_POST["subtaskdate"];
            $date = date('d.m.y',strtotime($subtaskdate));
            @$subtasktyp = implode(', ', $_POST["subtasktyp"]);
            //echo nl2br("projektid: " . $projektid . "\n");                                       
            
            if(empty($subtaskname_err) && empty($subtaskbeschreibung_err) && empty($subtaskdate_err) && empty($subtasktyp_err)){
                $sql = "INSERT INTO subtask (projektid, titel, beschreibung, deadline, typ, erstellerid) VALUES (?, ?, ?, ?, ?, ?)";
    
                if($stmt = mysqli_prepare($conn, $sql)){
                    mysqli_stmt_bind_param($stmt, "ssssss", $param_projektid, $param_subtaskname, $param_subtaskbeschreibung, $param_date, $param_subtasktyp, $param_erstellerid);
    
                    $param_projektid = $projektid;
                    $param_subtaskname = $subtaskname;
                    $param_subtaskbeschreibung = $subtaskbeschreibung;
                    $param_date = $date;
                    $param_subtasktyp = $subtasktyp;
                    $param_erstellerid = $mitarbeiterid;
                    //echo nl2br("projektid: " . $param_projektid . "\n");
    
                    if(mysqli_stmt_execute($stmt)){
                        echo "Erfolgreich";
                        $_SESSION['stid'] = mysqli_insert_id($conn);
                        //SESSION-Variable für upload.php, DASS SUBTASK ERSTELLT WURDE
                        $_SESSION['$st_erstellt'] = true;
                    } else {           
                        echo "Fehlschlag";         
                        exit(mysqli_error($conn));
                        // oder dann?
                    }
                    mysqli_stmt_close($stmt);
                }
                } else {
                    
                    exit(mysqli_error($conn));
                }
            }
        } mysqli_close($conn);   
    ?>

<script>

// Validierung:
$("#subtaskname").removeClass("errormsgsbtsk");

var errorEmpty = "<?php echo $errorEmpty; ?>";

if(errorEmpty == true) {
    $("#subtaskname").addClass("errormsgsbtsk")
}

if(errorEmpty == false) {

}

</script>

