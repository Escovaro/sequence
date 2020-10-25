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
                    <h1 class="display-8">Projekterstellung Schritt 3/3</h1>
                </div>
            </div>
        </div>

<!-- PHP-Teil --> 
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-4 text-left">
                <div class="container-fluid ProjektErstellung-container">                              
                    <table>
                        <tr>
                            <td>
                                <div class="container" id="schritt3">
                                    <form method="post" action="create_project.php">
                                        <br>
                                        <table>
                                            <colgroup width="150" span="3"></colgroup>
                                            <tr>
                                                <td>
                                                <?php echo "Projektnummer:"; ?>
                                                </td>
                                                <td>
                                                <?php echo $_SESSION["projektnummer"] . "<br>"; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                <?php echo "Projektname:"; ?>
                                                </td>
                                                <td>
                                                <?php echo $_SESSION["projektname"] . "<br>"; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                <?php echo "Beschreibung:"; ?>
                                                </td>
                                                <td>
                                                <?php echo $_SESSION["projektbeschreibung"] . "<br>"; ?>
                                                </td>
                                            </tr>                                            
                                        </table>
                                        <br>
                                        <button type="submit" class="btn btn-primary btn-weiter" value="Projekterstellen" onclick="uploadFile()";>Projekt speichern!</button>
                                    </form>
                                    <form method="post" action="./np2.php">
                                        <button type="submit" class="btn btn-primary btn-weiter" value="Zurück">Zurück</button>
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
</html>
