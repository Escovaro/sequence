<?php
    include 'php/dbhandler.php';
    //include 'comments.php';
    session_start();
    @$projektid = $_GET['name'];
    $_SESSION['projektid'] = $projektid;
    //echo "projektid: " . $projektid;  

    $_SESSION['stid'] = '';
    // Check if the user is logged in, if not then redirect to login page
    if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
        header("location: ../login.php");
    } 

    $mitarbeiterid = $_SESSION['mitarbeiterid'];
    //echo "MA-ID: " . $mitarbeiterid;

    //Überprüfung Mitarbeiterposition
    $sql2 = "SELECT position FROM mitarbeiter WHERE mitarbeiterid = '".$mitarbeiterid."'";
        $resultset = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));
        while($record = mysqli_fetch_assoc($resultset) ) {
            $position_ma = $record['position'];
            // echo "MA-Position: " . $position_ma;
        }
        
        //Wenn Projektlead oder Admin, dann kein Problem
        if ($position_ma >= '3'){
            $permgranted = true;
        } else if ($position_ma <= '2') {
            //Check, ob Account Zugriff auf dieses Projekt hat:
            $sql0 = "SELECT projektid FROM arbeiteteannorm WHERE mitarbeiterid = '".$mitarbeiterid."'";
                $resultset = mysqli_query($conn, $sql0) or die("database error:". mysqli_error($conn));
                while($record = mysqli_fetch_assoc($resultset) ) {
                    //$mpids = Mitarbeiterprojekte
                    $mpid = $record['projektid'];
                    //echo "MA-PID: " . $mpid;
                }
                if(!isset($mpid) ||  $mpid !== $projektid){
                    $permgranted = false;  
                    header("location: ../sonstiges/perm_denied.php");
                }else {
                    $permgranted = true;
                }
        }
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
                <?php 
                $sql = "SELECT titel FROM projekt WHERE projektid = '".$projektid."'";
                $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                while($record = mysqli_fetch_assoc($resultset) ) {
                    $_SESSION['titel'] = $record['titel'];
                }
                    ?>
                <h3 class="display-8">Projekttitel: <?php echo $_SESSION['titel']; ?></h3>
            </div>
        </div>
    </div>

<!-- 2. Row f. Thumbnail Projektinfo u. Subtaskmenu (rechts) -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <!-- Hierher muss das Thumbnail des Projekts $Thumbnail -->
                <a href="https://www.pexels.com/photo/man-in-raglan-sleeve-shirt-using-computer-2330137/">
                    <img src="../img/Kassette.jpg" class="img-fluid projektansicht_thumbnail" alt="Rodolfo_Quirós-Arbeiter">
                </a>
                <p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Projektinformationen ausklappen
                </button>
                </p>
                <div class="collapse Projektinfo" id="collapseExample">
                <?php
                        $sql = "SELECT titel, erstelldatum, p.beschreibung, typ, deadline, erstellerid, kundeid, firmenname FROM projekt p INNER JOIN kunde k ON p.kundeid=k.kundenid WHERE projektid = '".$projektid."'";
                        $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                        while($record = mysqli_fetch_assoc($resultset) ) {
                            $date1 = date('d.m.y',strtotime($record['erstelldatum']));
                            $date2 = date('d.m.y H:i:s',strtotime($record['erstelldatum']));
                            $_SESSION['titel'] = $record['titel'];
                            $persteller = $record['erstellerid'];
                            $pbeschreibung = $record['beschreibung'];
                            $ptyp = $record['typ'];
                            $kundeid = $record['kundeid'];
                            $firmenname = $record['firmenname'];
                            //Erstellername abfragen
                                $sql2 = "SELECT vorname, nachname FROM mitarbeiter WHERE mitarbeiterid = '".$persteller."'";
                                $resultset2 = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));
                                while($record = mysqli_fetch_assoc($resultset2) ) {
                                    $perstellername = $record['vorname'] . " " . $record['nachname'];

                ?>
                    <div class="col-lg-10 Projektinfo">
                        <div class="card card-body card-text">
                            <p>Erstelldatum: <?php echo $date1; ?></p>
                            <p>Letzte Änderung: <?php echo $date2; ?></p>
                            
                                <p>Projektbeschreibung: </p>
                                <p><?php echo $pbeschreibung; ?></p>                                
                            
                            <p>Typ: <?php echo $ptyp; ?></p>
                            
                            <p>Ersteller: <?php echo $perstellername; ?></p>
                            <p>Auftraggeber: <?php echo $firmenname; ?></p>
                        </div>
                    </div>
                    <?php
                    
                    }
                }
                ?>
                </div>
                <br>                   
            </div>

            <div class="col-lg-6 text-center">
                <div class=" text-nowrap container-fluid verwaltungs-container table-responsive">
                <br>
                    <p class="">Subtasks</p>
                    <table class="table table-striped w-auto" id="mySTTable">
                        <thead>
                            <tr>
                            <th scope="col">Link</th>
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
                                $sqlsubtasktable = "";
                                //echo $projektid;
                                $sql = "";
                                $sql = "SELECT * FROM subtask WHERE projektid = '".$projektid."'"; 
                                //echo $sql;
            
                                $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                                while( $record = mysqli_fetch_assoc($resultset) ) {
                                $date1 = date('d.m.y',strtotime($record['erstelldatum']));
                                $date2 = date('d.m.y',strtotime($record['deadline']));
                                $date3 = date('d.m.y H:i:s',strtotime($record['erstelldatum']));
                                $linkid = $record['subtaskid'];
                                ?>
                                    <tr>
                                        <td><a href="<?php echo 'st_view.php?name=' . $linkid; ?>">Subtaskdetails</a></td>
                                        <td><?php echo $record['subtaskid']; ?></td> 
                                        <td><?php echo $date1; ?></td>
                                        <td scope="row"><a href="https://www.pexels.com/photo/man-in-raglan-sleeve-shirt-using-computer-2330137/"><img src="../img/Kassette.jpg" class="img-fluid thumbnail-subtaskmenu" alt="Rodolfo_Quirós-Arbeiter"></a></td>
                                        <td><?php echo $record['titel']; ?></td>
                                        <td><?php echo $date2; ?></td>
                                        <td><?php echo $date1; ?></td>                                                
                                    </tr>  
                                <?php 
                                    }  
                                ?>    
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>
<!-- 3. Row Kommentare -->
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-lg-6">
            <!-- Kommentarteil -->            
                <form method="POST" id="comment_form">                    
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
        </div>
    </div>

<!-- 4. Row Projektinfos links und Avatare aller Mitarbeiter rechts -->
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
                    <img src="../img/Schriftlogo_mitlogo_weiss_schmal.png">
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
// Kommentarfunktion            
    $('#comment_form').on('submit', function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
            url:"php/add_comment.php",
            method:"POST",
            data:form_data,
            dataType:"JSON",
            success:function(data){
                if(data.error != ''){
                $('#comment_form')[0].reset();
                $('#comment_message').html(data.error);
                $('#comment_id').val('0');
                load_comment();
                }
            }
            })
        });
            load_comment();
            function load_comment(){
                $.ajax({
                    url:"php/fetch_comment.php",
                    method:"POST",
                    success:function(data){
                        $('#display_comment').html(data);
                    }
                })
            }

            $(document).on('click', '.reply', function(){
                var comment_id = $(this).attr("id");
                $('#comment_id').val(comment_id);
                $('#comment_name').focus();
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