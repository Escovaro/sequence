<?php
session_start();
?>

<!DOCTYPE html>
<html lang="DE">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Projekterstellung</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
        <link href="../../style.css" rel="stylesheet">
        <style>
        
        </style>
        
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
                <h1 class="display-8">Subtaskerstellung Schritt 2/3</h1>
            </div>
        </div>
    </div>

<!-- PHP-Teil --> 
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-6 text-left">
                <div class="container-fluid ProjektErstellung-container">                              
                    <table>
                        <tr>
                            <td> 
                                <?php        
                                    $subtaskname = $_POST['subtaskname'];
                                    $_SESSION['subtaskname'] = $subtaskname;                                     

                                    //Variablen f. Übergabe an Table Subtask (Björn)
                                    //Subtaskbeschreibung
                                    $beschreibung = $_POST['beschreibung'];
                                    $_SESSION['beschreibung'] = $beschreibung;
                                    //Subtasktyp (sollte noch in Radiobutton-Form umgewandelt werden, multiple-choice)
                                    $typ = $_POST['typ'];
                                    $_SESSION['typ'] = $typ;                                    
                                    // Deadline + Umwandlung Datumformat (Björn) 
                                    $deadline = $_POST['date'];                                                                     
                                    $date = date('d.m.y',strtotime($deadline));
                                    $_SESSION['deadline'] = $deadline;                                                                       
                                    //Projekt & Subtaskname                                   
                                    $pname = $_SESSION["projektnummer"] . '_' . $_SESSION["projektname"];
                                    $stname = $_SESSION["subtaskname"];

                                    // Der Zweizeiler ist für  Pfadübergabe an DB (Björn) durch upload.php (Björn)
                                    $_SESSION["pname"] = $_SESSION["projektnummer"] . '_' . $_SESSION["projektname"];
                                    $_SESSION["stname"] = $_SESSION["subtaskname"];

                                    // Erstellung Ordnerstruktur (Michi) (ZUSATZ "/screenshots" von Björn)
                                    $_SESSION["structure"] = '../data/' . $pname . '/' . $stname . '/screenshots' . '/';

                                    //Duplikat für Videoupload (Björn)
                                    $_SESSION["structure1"] = '../data/' . $pname . '/' . $stname . '/';

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

                                    if (!file_exists($_SESSION["structure"])) {
                                        mkdir($_SESSION["structure"], 0777, true);
                                        echo "<p>Filespace für Subtask '" . $_SESSION["subtaskname"] . "' wurde erfolgreich eingerichtet!</p>";

                                        //------------- WICHTIG: if schleife wenn subtid = false dann subtid = 0 // ZUSATZ NEU: "@" verwenden? --------------------------
                                        //$stid = $_SESSION['subtaskid']+1;
                                        // $_SESSION["sbtsktid"] = $stid; 
                                        //echo "<p>ID der angelegten Subtask in DB (nach Klick auf 'Upload'): '" . $stid . "'</p>";
                                        echo '<form method="post" action="../subtaskansicht3.php" >';
                                        echo '<button type="submit" class="btn btn-primary btn-weiter" value="Weiter">Subtask ansehen</button>';
                                        echo '</form>';

                                        echo "<p>Videodatei dieser Subtask hinzufügen: </p>";                                        

                                        //Verweis auf File-Upload zum direkten upload nach Subtask Anlage
                                        include '../upload/index.php';
                                    }
                                ?>                            
                                    
                                    <form method="post" action="./ns1.php" >
                                        <button type="submit" class="btn btn-primary btn-weiter" value="Weiter">Weitere Subtask anlegen</button>
                                    </form>
                                
                                
                                    <form method="post" action="./finish.php" >
                                        <button type="submit" class="btn btn-primary btn-weiter" value="Fertig">Fertig</button>
                                    </form>                                
                            </td>                                    
                        </tr>
                    </table>                
                </div>
            </div>
        </div>
    </div>
</body>
</html>