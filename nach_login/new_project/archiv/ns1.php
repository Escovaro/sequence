<?php
session_start();
include '../php/dbhandler.php';
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
                <h1 class="display-8">Subtaskerstellung Schritt 1/3</h1>
                <?php                                       
                    echo "Subtaskerstellung für Projekt: '" . $_SESSION["projektname"] . "'";
                    $subtaskname = $beschreibung = $typ = $latest_id = $date = "";
            
                ?>
            </div>
        </div>
    </div>

<!-- PHP-Teil -->    
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-8 text-left">
                <div class="container-fluid ProjektErstellung-container">                              
                    <table>
                        <tr>
                            <td>        
                                <div class="container">
                                        <form method="post" action="./ns2.php" >
                                            <label for="subtaskname"><p>Subtaskname*:</p></label><br>
                                            <input type="text" class="input" placeholder="Bitte Subtasknamen eingeben" required name="subtaskname" size="50" value="<?php echo $subtaskname;?>"><br>
                                            
                                            <label for="beschreibung"><p>Subtaskbeschreibung*:</p></label><br>
                                            <textarea name="beschreibung" class="input" rows="5" cols="48" value="<?php echo $beschreibung;?>"> </textarea><br>

                                            <label for="typ"><p>Subtask-Typ*:</p></label><br>
                                            <input type="text" class="input" placeholder="Bitte Typ/Art der Subtask eingeben" required name="typ" size="50" value="<?php echo $typ;?>"><br>

                                            
                                            <label for="example-date-input" class="form-label">Geplantes Abschlussdatum</label>                                                
                                            <input class="form-control" type="date"  required name="date" value="<?php echo $date;?>">       
                                            <br>
                                            <button type="submit" class="btn btn-primary btn-weiter" name="send" value="Weiter">Subtask anlegen</button>                                     
                                                                               
                                        </form>
                                </div>
                            </td>                                    
                        </tr>
                    </table>                
                </div>
            </div>
        </div>
    </div>

</body>

<script>

</script>