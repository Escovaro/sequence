<?php
session_start();
include '../php/dbhandler.php';
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
    header("location: ../../login.php");
    
}
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
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
                <h1 class="display-8">Projekterstellung Schritt 2/3</h1>
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

    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-4 text-left">
                <div class="container-fluid ProjektErstellung-container">                              
                    <table>
                        <tr>
                            <td> 
                                <div class="container" id="schritt2"> 
                                    <?php
                                            //Initialisieren der Variablen
                                            $mitarbeiter = $teamlead = $projektlead = "";
                                            $mitarbeiter_err = $teamlead_err = $projektlead_err = "";

                                            $projektname = $_SESSION['projektname'];

                                            //Verarbeiten der übermittelten Daten
                                            if($_SERVER["REQUEST_METHOD"] == "POST"){
                                                //Wurden zumindest Mitarbeiter hinzugefügt?
                                                if(empty($_POST["mitarbeiter"])){
                                                    $mitarbeiter_err = "Bitte geben Sie min. 1 Mitarbeiter an.";
                                                } else {
                                                    $mitarbeiter = implode(', ', $_POST["mitarbeiter"]);
                                                    @$teamlead = implode(', ', $_POST["teamlead"]);
                                                    @$projektlead = implode(', ', $_POST["projektlead"]);

                                                    if(empty($mitarbeiter_err) && empty($teamlead_err) && empty($projektlead)){
                                                        $sql = "INSERT INTO arbeitetan VALUES ?, ?, ?";

                                                        if($stmt = msqli_prepare($conn, $sql)){
                                                            mysqli_stmt_bind_param($stmt, "sss", $param_mitarbeiter, $param_teamlead, $param_projektlead);

                                                            $param_mitarbeiter = $mitarbeiter;
                                                            $param_teamlead = $teamlead;
                                                            $param_projektlead = $projektlead;

                                                            if(mysqli_stmt_execute($stmt)){
                                                                // was tun?
                                                            } else {
                                                                // oder dann?
                                                            }
                                                            mysqli_stmt_close($stmt);
                                                        }

                                                        }
                                                    }
                                                } mysqli_close($conn);                                                    
                                            ?> 

                                        <p>Mitarbeiter auswählen um sie dem Projekt <?php echo "'". $projektname ."'"; ?> zuzuordnen.</p>
                                        <br>                                        
                                        <form method="POST" action="./np3.php">
                                        <div class="form-group">
                                            <i class="fas fa-user-tie"></i>                                     
                                            <select class="selectpicker" name="projektlead[]" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="projektlead">
                                            <?php 
                                                require("../php/dbhandler.php");
                                                echo $r="SELECT * FROM mitarbeiter WHERE position = 3;";
                                                    $rr = mysqli_query($conn,$r);
                                                    while($row= mysqli_fetch_array($rr))
                                                    {
                                                        ?>
                                                <option value="<?php echo $row['vorname']; ?>"><?php echo $row['vorname']; ?></option>
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
                                                echo $r="SELECT * FROM mitarbeiter WHERE position = 2;";
                                                    $rr = mysqli_query($conn,$r);
                                                    while($row= mysqli_fetch_array($rr))
                                                    {
                                                        ?>
                                                <option value="<?php echo $row['vorname']; ?>"><?php echo $row['vorname']; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>       
                                        </div>

                                        <div class="form-group">   
                                            <i class="fas fa-user"></i>                              
                                            <select class="selectpicker" name="mitarbeiter[]" data-width="fit" data-style="btn-outline-light" multiple data-selected-text-format="static" multiple title="mitarbeiter">
                                            <?php 
                                                require("../php/dbhandler.php");
                                                echo $r="SELECT * FROM mitarbeiter ;";
                                                    $rr = mysqli_query($conn,$r);
                                                    while($row= mysqli_fetch_array($rr))
                                                    {
                                                        ?>
                                                <option value="<?php echo $row['vorname']; ?>"><?php echo $row['vorname']; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>  
                                        </div> 
                                        
                                        <br><br>
                                        <button type="submit" class="btn btn-primary btn-weiter" value="Weiter">Weiter</button>
                                    </form>

                                    <form method="post" action="./np1.php">
                                        <button type="submit" class="btn btn-primary btn-weiter" value="Zurück">Zurück</button>
                                    </form>
                                    <a href="https://fontawesome.com/license">Icons from Fontawesome</a>
                                    <!-- https://fontawesome.com/icons?d=gallery&q=users -->
                                </div>
                            </td>                                    
                        </tr>
                    </table>                
                </div>
            </div>
        </div>
    </div>
</body>
</html>
