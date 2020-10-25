<?php
//TO-DO:
//Projekttyp in Multiselect wie bei "area" in Registrierung.php, 
//sodass bei Mitarbeiterauswahl ausgewählt werden kann, ob nur passende Mitarbeiter
//angezeigt werden oder diese einfach gehighlightet werden. 
//Design aufhübschen
//Nur Projektlead &/ Teamlead dürfen Projekte erstellen? 
session_start();
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
    <div class="container-fluid padding">
        <div class="row">
            <div class="col-lg-12">
                <!-- $Projektname -->
                <h1 class="display-8">Projekterstellung Schritt 1/3</h1>
            </div>
        </div>
    </div>

<!-- PHP-Teil --> 
    <?php
    $projektnummer = $projektname = $projektbeschreibung = $projekttyp = $projektdate = "";    
    ?>

    <div class="container-fluid padding">
        <div class="row">
            <div class="col-7 text-left">
                <div class="container-fluid ProjektErstellung-container">                              
                    <table>
                        <tr>
                            <td>                        
                                <form method="post" action="./np2.php" >
                                    <label for="projektnummer" ><p>Projektnummer*:</p></label><br>
                                    <input type="text" class="input" placeholder="Bitte Projektnummer eingeben" required name="projektnummer" size="50" value="<?php echo $projektnummer;?>"><br>

                                    <label for="projektname"><p>Projektname*:</p></label><br>
                                    <input type="text" class="input" placeholder="Bitte Projektnamen eingeben" required name="projektname" size="50" value="<?php echo $projektname;?>"><br>

                                    <label for="projektbeschreibung"><p>Projektbeschreibung*:</p></label><br>
                                    <textarea name="projektbeschreibung" class="input" placeholder="Bitte eine Projektbeschreibung eingeben" rows="5" cols="48" value="<?php echo $projektbeschreibung;?>"> </textarea><br>
                                    <br>

                                    <label for="projekttyp"><p>Projekt-Typ*:</p></label><br>
                                        <input type="text" class="input" placeholder="Bitte Typ/Art der Subtask eingeben" required name="projekttyp" size="50" value="<?php echo $projekttyp;?>"><br>

                                    <label for="example-date-input" class="form-label">Geplantes Abschlussdatum</label>                                                
                                    <input class="form-control" type="date" required name="projektdate" value="<?php echo $projektdate;?>">       
                                    <br>                                
                                    <button class="btn btn-primary btn-weiter" type="submit" name="send" value="Weiter">Weiter</button>
                                </form>                        
                            </td>                                    
                        </tr>
                    </table>                
                </div>
            </div>

            <div class="col-4 text-center">
                <div class="container-fluid ProjektErstellung-container">    
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
                                
                                    $dir = "../data/";  //<-----------

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
                <div>
                        <object class="logo_np1" data="../../img/SVG/Element 2.svg"
                            type="image/svg+xml"></object>
                    <!--    <img class="img-fluid"  src="img/Logo_schwarz_groß.png"s="img-fluid"  src="img/Logo_schwarz_groß.>-->
                </div>            
            </div>
        </div>
    </div>
    

</body>	
</html>
