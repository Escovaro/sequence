<?php
//<!-- TO-DOs:
//- Subtasktabelle mit Mitarbeitern- / Avataren befüllen
//- Subtasktabelle Scrolling impl.
//- Evtl. Schatten um Projekt-Thumbnail und Subtasktabelle
//- Hintergrundfarbe Projektinformationen ändern *kotz
//-->
session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
    header("location: ../login.php");
    //-------------------------------Textausgabe wäre nice --------------------------\\
    //echo "Sie müssen sich vorher einloggen!";
    
}    
    include 'php/dbhandler.php';
    //include 'comments.php';
    $query ="SELECT * FROM subtask ORDER BY subtaskid DESC";  
    $result = mysqli_query($conn, $query);  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Projektansicht</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
	<link href="../style.css" rel="stylesheet">
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

<!-- 1. Row f. Titel -->
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-lg-12">
                <!-- $Projektname -->
                <h1 class="display-8">Projektname</h1>
            </div>
        </div>
    </div>

<!-- 2. Row f. Thumbnail Projektinfo, Kommentare u. Subtaskmenu (rechts) -->
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-lg-6">
                <!-- Hierher muss das Thumbnail des Projekts $Thumbnail -->
                <a href="https://www.pexels.com/photo/man-in-raglan-sleeve-shirt-using-computer-2330137/"><img src="../img/Kassette.jpg" class="img-fluid thumbnail" alt="Rodolfo_Quirós-Arbeiter"></a>
                <p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Projektinformationen ausklappen
                </button>
                </p>
                <div class="collapse Projektinfo" id="collapseExample">
                    <div class="card bg-light card-body card-text">
                        <p>Erstelldatum: </p>
                        <p>Letzte Änderung: </p>
                        <div class="Projekt-Beschreibung">
                            <p>Projektbeschreibung: </p>
                            <p>
                            "But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?"
                            </p>
                        </div>
                        <p>Typ: </p>
                        <p>Ersteller: </p>
                        <p>Auftraggeber/Kunde: </p>
                    </div>
                </div>
                <br>

            <!-- Kommentarteil -->            
                <form method="POST" id="comment_form">
                    <div class="form-group">
                        <input type="text" name="comment_name" id="comment_name" class="form-control" placeholder="Bitte Namen angeben" />
                    </div>
                    <div class="form-group">
                        <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Nachricht eingeben" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="comment_id" id="comment_id" value="0" />
                        <input type="submit" name="submit" id="submit" class="btn btn-info" value="Senden" />
                    </div>
                </form>
                <span id="comment_message"></span>
                <br />
                <div id="display_comment"></div>                    
            </div>

            <div class="col-lg-6 text-center">
                <div class="container-fluid subtask-container">
                    <p class="">Subtasks</p>
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Subtask-Nr.</th>   
                            <th scope="col">Erstelldatum</th> 
                            <th scope="col">Thumbnail</th>
                            <th scope="col">Name</th>
                            <th scope="col">Deadline</th>
                            <th scope="col">Letzte Änderung</th>                    
                            </tr>
                        </thead>

                        <tbody id="myTable">
                            <?php 
                                    while($row = mysqli_fetch_array($result))  
                                    {   
                                        // Konvertiert das Datum der Datenbank:
                                        $timestamp = $row["erstelldatum"];                                    
                                        $date = date('d.m.Y',strtotime($timestamp));
                                        
                                        
                                        // Datenausgabe:
                                        //----------------------------folgendes muss eingebunden werden, sobald mitarbeiter der subtasks verfügbar! --------------\\ 
                                        //<td scope="row">'.$row["mitarbeiterid"].'</td>
                                        //<td>Mitarbeiterbild</td>
                                        echo '  
                                        <tr>
                                                  
                                                <td>'.$row["subtaskid"].'</td>  
                                                <td>'.$row["erstelldatum"].'</td>
                                                <td scope="row"><a href="https://www.pexels.com/photo/man-in-raglan-sleeve-shirt-using-computer-2330137/"><img src="../img/Kassette.jpg" class="img-fluid thumbnail-subtaskmenu" alt="Rodolfo_Quirós-Arbeiter"></a></td>
                                                <td>'.$row["titel"].'</td>                                                                                      
                                                <td>'.$row["deadline"].'</td>
                                                <td>'.$row["letzteaenderung"].'</td>                                                  
                                        </tr>  
                                        ';  
                                    }  
                            ?>            
                            
                            
                            </tr>
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>

<!-- 3. Row Projektinfos links und Avatare aller Mitarbeiter rechts -->
    <div class="container-fluid">
        <div class="row padding">
            <div class="col-lg-6">       
        
            </div>
            <div class="col-lg-6">            
            </div>
        </div>
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
                <div class="col-md-4">
                    <hr class="dark">
                    <img src="img/Schriftlogo_mitlogo_weiss_schmal.png">
                    <hr class="dark">
                    <p>555-555-555</p>
                    <p>email@gmx.at</p>
                    <p>teststr. 53</p>
                    <p>Wien, Österreich</p>
                </div>
                <div class="col-md-4">
                    <hr class="dark">
                    <h5>Unsere Zeiten</h5>
                    <hr class="dark">
                    <p>Montags 8Uhr - 17Uhr</p>
                </div>
                <div class="col-md-4">
                    <hr class="dark">
                    <h5>HTL Wien West</h5>
                    <hr class="dark">
                    <p>Montags 8Uhr - 17Uhr</p>
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

<script>
    $(document).ready(function(){
    
    $('#comment_form').on('submit', function(event){
    event.preventDefault();
    var form_data = $(this).serialize();
    $.ajax({
    url:"add_comment.php",
    method:"POST",
    data:form_data,
    dataType:"JSON",
    success:function(data)
    {
        if(data.error != '')
        {
        $('#comment_form')[0].reset();
        $('#comment_message').html(data.error);
        $('#comment_id').val('0');
        load_comment();
        }
    }
    })
    });

    load_comment();

    function load_comment()
    {
    $.ajax({
    url:"fetch_comment.php",
    method:"POST",
    success:function(data)
    {
        $('#display_comment').html(data);
    }
    })
    }

    $(document).on('click', '.reply', function(){
    var comment_id = $(this).attr("id");
    $('#comment_id').val(comment_id);
    $('#comment_name').focus();
    });
    
    });

    // Suchfunktion
    $(document).ready(function(){
    $("#tableSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
</script>