<?php
    include 'php/dbhandler.php';
    //include 'comments.php';  
    session_start();
    //@$sbtsktid = "";
    
    @$stid = $_GET['name'];
    if(!isset($stid)) {
        $stid = $_SESSION['stid'];
    }
    
    // echo $_SESSION['projektid'];
    $_SESSION['stid'] = $stid;
    //echo nl2br( $stid . "\n");
    
    //$mitarbeiterid = $_SESSION['mitarbeiterid'];
    $mitarbeiterid = $_SESSION['mitarbeiterid'];
    // echo nl2br("MA-ID: " . $mitarbeiterid);


    // Check, ob User eingeloggt, sonst Redirect zum Login
    if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
        header("location: ../login.php");    
    }

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
            // Check, ob Account Zugriff auf dieses Projekt hat (LEFT JOIN):
            $sql0 = "SELECT a.projektid AS AApid, s.projektid AS Spid, s.subtaskid FROM arbeiteteannorm a LEFT JOIN subtask s ON a.projektid=s.projektid WHERE mitarbeiterid = '".$mitarbeiterid."' AND subtaskid = '".$stid."'";
            $resultset = mysqli_query($conn, $sql0) or die("database error:". mysqli_error($conn));
            while($record = mysqli_fetch_assoc($resultset) ) {
                //$mpids: Mitarbeiterprojekte
                $mpid = $record['AApid'];
                echo "MA-PID: " . $mpid;

                //$spid: Projekt-ID der Subtask
                $spid = $record['Spid'];
                //Test:echo "Projekt-ID: " . $spid;
            }   
            //Wenn MA Subtask nicht zugeteilt, kein Zugriff 
            if(!isset($mpid) || $mpid !== $spid) {
                $permgranted = false;
                //Test: echo "Kein Zugriff";
                //Weiterleitung:  
                header("location: ../sonstiges/perm_denied.php");
            }
        }

/*
        // Überprüfung: 
        if(!isset($mpid) || $mpid !== $spid && $position_ma <= '2'){
            $permgranted = false;
            Test: echo "Kein Zugriff";
            //Weiterleitung:  
            //header("location: ../sonstiges/perm_denied.php");
        } else if($position_ma >= '3'){
            $permgranted = true;
        }*/
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subtaskansicht</title>
    
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="../style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
    <!-- Library to help drawing -->
    <script src="js/draw.js"></script> 
    <!-- Library to help text -->
	<script type="text/javascript" src="js/text.js"></script>    
    <!-- File für Versionslog -->
    <script src="php/get_media.js"></script> 

    <script type="text/javascript">
    //Abgaben-Upload
        $(document).ready(function(e){
            $("#stupload").on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'stupload_ajax_receiver.php',
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function(){
                        $('.submitBtn').attr("disabled","disabled");
                        $('#stupload').css("opacity",".5");
                    },
                    success: function(response){
                        // console.log(response);
                        $('.statusMsg').html('');
                        if(response.status == 1){
                            $('#stupload')[0].reset();
                            $('.statusMsg').html('<p class="alert alert-success">'+response.message+'</p>');
                        } else {
                            $('.statusMsg').html('<p class="alert alert-danger">'+response.message+'</p>');
                        }
                        $('#stupload').css("opacity", "");
                        $(".submitBtn").removeAttr("disabled");
                    }
                });
            });
        });
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

<!-- 1. Row Projekt- und Subtasktitel -->
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-lg-12">
                <br>

                <?php
                $sql = "SELECT s.titel as stitel, p.titel as ptitel FROM subtask s LEFT JOIN projekt p ON s.projektid=p.projektid WHERE s.subtaskid = '".$stid."'";
                $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                while($record = mysqli_fetch_assoc($resultset) ) {
                    $_SESSION['stitel'] = $record['stitel'];
                    $_SESSION['ptitel'] = $record['ptitel'];
                }
                ?>
                <h6 class="display-12">Projekt-Titel: <?=$_SESSION['ptitel']; ?></h6>
                <h6 class="display-12">Subtask-Titel: <?=$_SESSION['stitel']; ?></h6>
                <h6 class="display-12">Subtask-ID: <?=$stid;?></h6>
            </div>
        </div>
    </div>

<!-- 2. Row Videplayer, Toolbar, Subtaskmenu (rechts)  -->
<div class="container-fluid padding-left">
        <div class="row">
            <!-- VIDEOPLAYER & SCREENSHOT-BUTTON-->
            
                <div class='col-xl-7'>  
                    <?php    
                        // Variable für Aufrufen des richtigen Videos (Björn):
                        //$sbtsktid = $_SESSION['subtaskid'];                           
                            echo "<video class='embed-responsive embed-responsive-16by9 video-player' controls>";                        
                            $fetchVideos = mysqli_query($conn, "SELECT mediapfad, mediaid FROM media WHERE subtaskid = '".$stid."' ORDER BY mediaid ASC");
                            while($row = mysqli_fetch_assoc($fetchVideos)){
                            $mediaid = $row['mediaid'];
                            $_SESSION['mediaid'] = $mediaid;
                            $mediapfad = $row['mediapfad'];
                            //$titel = $row['titel'];
                            //echo $titel;
                            echo "<source src='" . $mediapfad. "'type='video/mp4'>";                      
                        echo "</video>";
                        }
                    ?>
                        <br>
                        
                        
                        <!-- Nicht sicher, ob die Container-Div geschlossen wird, bitte überprüfen -->       
                            <div class="container_subttaskansichtdyn">
                                <!--SCREENSHOTBUTTON -->                
                                <button data-toggle="collapse" data-target=".collapseZeichnenToolbar" aria-expanded="false" aria-controls="collapseZeichnenToolbar" class="customsubmit5" id="snap" onclick="snap()">take screenshot</button>  
                                
                                <!-- ABGABE BUTTON -->
                                <button type="button" data-toggle="collapse" class="customsubmit5 " data-target=".Abgabenupload" aria-expanded="false" aria-controls="Abgabenupload" class="customsubmit4">abgabe erstellen</button>
                                <br>

                                <div class="col-lg-12 collapse Abgabenupload">
                                    <!-- FILEUPLOAD ABGABE -->
                                    <!--?php include 'upload/indexabgabe.php';       ?-->

                                    <form id="stupload" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="updatebeschreibung">Updatebeschreibung</label>
                                            <input type="text-area" class="form-control" id="updatebeschreibung" name="updatebeschreibung" placeholder="Bitte Updatebeschreibung angeben" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="file">upl video</label>
                                            <input type="file" class="form-control" id="file" name="file" required>
                                        </div>
                                            <input type="submit" name="submit" class="customsubmit4 submitBtn" value="abgeben">
                                        </div>
                                    </form>
                                </div>
                            </div> 
                        <br>
                        <br>

                <!-- Mitarbeiter-MENÜ                          TO DO DYNAMISIEREN Siehe Subtask-Menü in Projektansicht + Links zu Mitarbeiterwaltung (später vlt. Mitarbeiterprofil) -->
                <div class="col-xl-5 text-center">
                    <div class="container-fluid subtask-container table-responsive" id= "register">
                        <br>
                        <h5 class="">Uploadverlauf</h5>
                        <table class="table table-striped w-auto" id="mediatabelle">
                            <thead>
                                <tr>
                                <th scope="col">Link</th>
                                <th scope="col">MediaID</th>   
                                <th scope="col">Uploader</th>
                                <th scope="col">Dateiversion</th>
                                <th scope="col">Beschreibung</th>  
                                <th scope="col">Uploaddatum</th>  
                                <th scope="col">Dateiname</th> 
                                </tr>
                            </thead>

                            <tbody>
                            
                                <tr>
                                <?php
                                    // Abfrage Media dieser Subtask für Tabelle rechts: 
                                    $sql1 = "";
                                    $sql1 = "SELECT * FROM media WHERE subtaskid = '".$stid."' ORDER BY mediaid ASC";                              
                                    $resultset = mysqli_query($conn, $sql1) or die("database error:". mysqli_error($conn));
                                    $dateiversion = 1;
                                    while($record = mysqli_fetch_assoc($resultset)) {                        
                                        $mediadate = date('d.m.y',strtotime($record['uploaddatum']));                                
                                        $mediabeschreibung = $record['updatebeschreibung'];
                                        $ersteller = $record['erstellerid'];                                
                                        $linkid = $record['mediaid'];
                                        $dateiname = $record['dateiname'];
                                        $dateiversion = $dateiversion;                                    
                                ?>
                                        <!-- <td scope="col"><a href="../projektansichtdyn.php?name=' . $row['projektid'] . '" title="Details ansehen" class="text-success"><i class="fas fa-info-circle fa-lg"></i></a></td> -->
                                        <td><a href="<?php echo 'st_view_media.php?name=' . $linkid; ?>"><i class="fas fa-info-circle fa-lg"></a></td>
                                        <td scope="row"><?php echo $linkid; ?></td>
                                        <!--td>Bild Thumbail</td-->
                                        <td><?php echo $ersteller; ?></td>
                                        <!-- Gibt Dateiversion +1 aus -->
                                        <td><?php echo $dateiversion++; ?></td>
                                        <td><?php echo $mediabeschreibung; ?></td>
                                        <td><?php echo $mediadate; ?></td>     
                                        <td><?php echo $dateiname; ?></td>                                                                
                                </tr>

                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <button type="button" class="customsubmit" id="refreshBtn"><i class="fas fa-sync"></i> Synchronisieren </button>
                            </div>
                        </div>                    
                    </div>
                </div>
        </div>
    </div>

<!-- 3. Row Zeichenfläche, Speicherbutton, unsichtbares Canvas (wird benötigt) -->
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-lg-12 collapse collapseZeichnenToolbar">
                <!-- TOOLBAR -->   
                <div class="wrapper">
                    <div class="toolbar">
                    
                        <a class="selected" href="javascript:void();" id="brush" onclick="ChangeTool('brush')">
                        <img src="icons_toolbar/schwarz/brush-icon.png"></a>

                        <a href="javascript:void();" id="rectangle" onclick="ChangeTool('rectangle')">
                        <img src="icons_toolbar/schwarz/rectangle-icon.png"></a>

                        <a href="javascript:void();" id="line" onclick="ChangeTool('line')">
                        <img src="icons_toolbar/schwarz/line-icon.png"></a>

                        <a href="javascript:void();" id="circle" onclick="ChangeTool('circle')">
                        <img src="icons_toolbar/schwarz/circle-icon.png"></a>

                        <a href="javascript:void();" id="ellipse" onclick="ChangeTool('ellipse')">
                        <img src="icons_toolbar/schwarz/ellipse-icon.png"></a>

                        <a href="javascript:void();" id="polygon" onclick="ChangeTool('polygon')">
                        <img src="icons_toolbar/schwarz/polygon-icon.png"></a>
                        <br>
                        <br>
                        
                        <!-- Linienbreite -->
                        Linienbreite: <!-- <select id="selWidth"> -->
                            <button type="button" class="btn btn-primary dicke1" onclick="changeWidth('1')" id="1">1</button>
                            <button type="button" class="btn btn-primary dicke2" onclick="changeWidth('2')" id="2">2</button>
                            <button type="button" class="btn btn-primary dicke3" onclick="changeWidth('4')" id="4">4</button>
                        </select>
                        <br><br>
                        <!-- Farbauswahl -->
                        Farbe : <!-- <select id="selColor"> -->
                            <button type="button" class="btn btn-primary black" onclick="changeColor('black')" id="black">black</button>
                            <button type="button" class="btn btn-primary blue" onclick="changeColor('blue')" id="blue">blue</button>
                            <button type="button" class="btn btn-primary red" onclick="changeColor('red')" id="red">red</button>
                            <button type="button" class="btn btn-primary green" onclick="changeColor('green')" id="green">green</button>
                            <button type="button" class="btn btn-primary yellow" onclick="changeColor('yellow')" id="yellow">yellow</button>
                            <button type="button" class="btn btn-primary gray" onclick="changeColor('gray')" id="gray">gray</button>   

                            <div id="logPos">Positionsausgabe Cursor</div>                     
                    </div>                
                </div>
            </div>
        </div>
    </div>

<!-- 4. Row Zeichenfläche, Speicherbutton, unsichtbares Canvas (wird benötigt) -->
    <div class="container-fluid padding-left">
        <div class="row">
            <div class="col-lg-12 collapse collapseZeichnenToolbar">
                <!-- TOOLBAR -->   
                <div class="wrapper">
                <!--  ZEICHENFLÄCHE -->
                    <div  id="createImg">
                        <canvas id="my-canvas"></canvas>
                    </div>
                    <br>                    
                <!-- SPEICHERBUTTON -->
                    <button id="geeks" type="button" class="btn btn-primary top">Bild speichern!</button>    
                    <!-- onclick="window.scrollTo(0,0)" <- Das war ein Fix für Teil des gespeicherten Screenshots komplett weiß -->
                </div>
            </div>
        </div>
    </div>

<!-- 5. Row Projektinformationen & Kommentare -->
    <div class="container-fluid">
        <p>
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                Subtaskinformationen ausklappen
            </button>                            
        </p>
        <div class="row">                        
            <div class="collapse" id="collapseExample">
                    <!-- Projektinformationen --> 
                    <?php
                        $sql = "SELECT titel, erstelldatum, beschreibung, typ, deadline, erstellerid FROM subtask WHERE subtaskid = '".$stid."'";
                        $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                        while($record = mysqli_fetch_assoc($resultset) ) {
                            $date1 = date('d.m.y',strtotime($record['erstelldatum']));
                            $date2 = date('d.m.y H:i:s',strtotime($record['erstelldatum']));
                            $_SESSION['titel'] = $record['titel'];
                            $stersteller = $record['erstellerid'];
                            $stbeschreibung = $record['beschreibung'];
                            $sttyp = $record['typ'];
                            //Erstellername abfragen
                                $sql2 = "SELECT vorname, nachname FROM mitarbeiter WHERE mitarbeiterid = '".$stersteller."'";
                                $resultset2 = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));
                                while($record = mysqli_fetch_assoc($resultset2) ) {
                                    $sterstellername = $record['vorname'] . " " . $record['nachname'];
                                
                            ?>
                            <div class="col-lg-10 Projektinfo">
                                <div class="card card-body card-text">
                                    <p>Erstelldatum: <?php echo $date1; ?></p>
                                    <p>Letzte Änderung: <?php echo $date2; ?></p>
                                    
                                        <p>Projektbeschreibung: </p>
                                        <p><?php echo $stbeschreibung; ?></p>                                
                                    
                                    <p>Typ: <?php echo $sttyp; ?></p>
                                    
                                    <p>Ersteller: <?php echo $sterstellername; ?></p>
                                    <p>Auftraggeber: </p>
                                </div>
                            </div>
                            <?php
                            
                            }
                        }
                            ?>
                    <!-- Wrapper für Bilder-Galerie -->
                    
                    <div class="col-lg-6 mt-4 p-2">        
                        <p> Screenshotgalerie</p> 
                        <div class="card-group" id="images_preview">
                         
                            <!-- Hier kommen die Bilder hinein -->
                        </div>
                    </div>
                                      
            </div>
        </div>
    </div>

<!-- 6. Row Kommentare -->    
<div class="container-fluid padding-left">
        <div class="row">
            <div class="col-lg-6">     
    <!-- Kommentarteil -->
                <br><br>
                    <form method="POST" id="comment_form">
                        <!-- Name wird jetzt dynamisch geladen = $_SESSION['mitarbeiterid']
                        <div class="form-group">
                            <input type="text" name="comment_name" id="comment_name" class="form-control" placeholder="Bitte Namen angeben" />
                        </div>
                        -->
                        <div class="form-group">
                            <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Nachricht eingeben" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="comment_id" id="comment_id" value="0" />
                            <input type="submit" name="submit" id="submit" class="customsubmit" value="Senden" />
                        </div>
                    </form>
                    <span id="comment_message"></span>
                    <br>
                    <div id="display_comment"></div>
                        </div>
                    </div>            
            </div>
        </div>
    </div><br>

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

//Videoplayer Metadaten ermitteln etc.
		// Rückgabe der Elemente "video" und "canvas"
		var video = document.querySelector('video');
		var canvas2 = document.querySelector('canvas');
		// Zweidimensionalen Kontext des Canvas ermitteln
		var context = canvas2.getContext('2d');
		// Breite, Höhe, Verhältnis deklarieren
		var w, h, ratio;
		
		// Listener, der abwartet, bis die Metadaten geladen wurden 
        // und dann eine Funktion feuert
		video.addEventListener('loadedmetadata', function() {
            // Ermitteln des Bildverhältnisses
            ratio = video.videoWidth / video.videoHeight;
            // Breite ermitteln
            w = video.videoWidth;
            // Höhe des Videos ermitteln
            h = parseInt(w / ratio, 10);
            // Zeichenfläche entsprechend definieren			
            canvas2.width = w;
            canvas2.height = h;
            
            var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
            var div = $("#createImg");
            if(w > width){
                div.ready(function () {
                div.width(width - 50);
                div.height(parseInt(width / ratio, 10));
                })
            } else {
                div.ready(function () {
                    div.width(w);
                    div.height(h);
                })
            };		

            // Ausgabe Breite und Höhe des gespeicherten Bildes:
            console.log(w, h);		
        //Ende der Funktion
        }, false);			

		
// Aufnahme Screenshot
		function snap() {
			// Dimensionen des Canvas
			context.fillRect(0, 0, w, h);
			// Holen und Ausgeben des 1. Canvas in 2. Canvas
			var snapshot = context.drawImage(video, 0, 0, w, h);
			context.drawImage(video, 0, 0, w, h);
		}

		
// Speichern Screenshot
		$(function() { 
			$("#geeks").click(function(event) {
                //Speichert Bildschirmposition vor Speichern
                var currPosition = $(window).scrollTop(); 
                event.preventDefault();  
				html2canvas($("#createImg"), { 
					onrendered: function(canvas) {                          
						var imgsrc = canvas2.toDataURL("image/png",); 
						console.log(imgsrc); 
						$("#newimg").attr('src', imgsrc); 
						$("#img").show(); 
                        var dataURL = canvas2.toDataURL();                         
						$.ajax({ 
							type: "POST", 
							url: "php/st_script.php", 
							data: { 
								imgBase64: dataURL 
                            }
						}).done(function(o) { 
							console.log('saved'); 
                            load_images();   
                            //Lädt Bildschirmposition nach Speichern
                            $(window).scrollTop(currPosition); 
                            // o.preventDefault();                                                
						}); 
					} 
				}); 
			}); 
        }); 
        

// Texterstellung in Screenshot
        
        function writeMessage(canvas2, message) {
            var context = canvas2.getContext('2d');            
            context.font = '18pt Calibri';
            context.fillStyle = 'white';            
        }
        
        function getMousePos(canvas2, evt) {
            var rect = canvas2.getBoundingClientRect();
            return {
            x: evt.clientX - rect.left,
            y: evt.clientY - rect.top
            };
        }
        canvas2.addEventListener('mousemove', function(evt) {
            mousePos = getMousePos(canvas2, evt);
            var message = 'Mouse position: ' + mousePos.x + ',' + mousePos.y;
            document.getElementById('logPos').innerHTML = message;
            writeMessage(canvas2, message);
        }, false);

        
		$('#my-canvas').dblclick(function(e){
            
                if ($('#textAreaPopUp').length == 0) { 
                    canvas2.addEventListener('mousemove', function(evt) {
                    var mousePos = getMousePos(canvas2, evt);
                    mouseX = mousePos.x - this.offsetLeft + $("#my-canvas").position().left;
                    mouseY = mousePos.y - this.offsetTop + $("#my-canvas").position().top;
                    }, false);            
                     
                    //console.log('X-Pos. vor Schreiben: ' + mouseX);
                    //console.log('Y-Pos. vor Schreiben: ' + mouseY);                    
                    
                    //append a text area box to the canvas where the user clicked to enter in a comment
                    var textArea = "<div id='textAreaPopUp' style='position:absolute;top:"+mouseY+"px; margin-left:"+mouseX+"px;'><textarea id='textareaTest' style='width:100px;height:50px;'></textarea>";
                    var saveButton = "<input type='button' value='save' id='saveText' onclick='saveTextFromArea("+mouseY+","+mouseX+");'></div>";
                    var appendString = textArea + saveButton;
                    $("#createImg").append(appendString);
                    console.log('Übernommene X-Pos: ' + mouseX);
                    console.log('Übernommene Y-Pos: '+ mouseY);

                } else {
                    $('textarea#textareaTest').remove();
                    $('#saveText').remove();
                    $('#textAreaPopUp').remove();     
                    
                    canvas2.addEventListener('mousemove', function(evt) {
                    var mousePos = getMousePos(canvas2, evt);
                    mouseX = mousePos.x - this.offsetLeft + $("#my-canvas").position().left;
                    mouseY = mousePos.y - this.offsetTop + $("#my-canvas").position().top;
                    }, false);
                    
                    //console.log('X-Pos. vor Schreiben: ' + mouseX);
                    //console.log('Y-Pos. vor Schreiben: ' + mouseY);
                    
                    //append a text area box to the canvas where the user clicked to enter in a comment
                    var textArea = "<div id='textAreaPopUp' style='position:absolute;top:"+mouseY+"px; margin-left:"+mouseX+"px;'><textarea id='textareaTest' style='width:100px;height:50px;'></textarea>";
                    var saveButton = "<input type='button' value='save' id='saveText' onclick='saveTextFromArea("+mouseY+","+mouseX+");'></div>";
                    var appendString = textArea + saveButton;
                    $("#createImg").append(appendString);
                    console.log('Übernommene X-Pos: ' + mouseX);
                    console.log('Übernommene Y-Pos: ' + mouseY);
                }
            });	
 
// Kommentarfunktion 
    $(document).ready(function(){           
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
                success:function(data) {
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

    //JQuery für Versionslog:
        //Konstante für Tabellenidentifizierung und Referenz auf Tabellenbody am Ende der Zeile:
        const mediabody = document.querySelector("#mediatabelle > tbody");

        //Info in Konsole:
        console.log(mediabody);

    //Bilder-Galerie:
      load_images();
        function load_images(){
            $.ajax({
                url: 'php/load_images.php',
                method: 'get',
                success: function(data){
                    $("#images_preview").html(data);                
                }
            });
        }   

</script>
</body>


</html>