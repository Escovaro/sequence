<?php
//Verarbeitung des Uploads UND Weiterleitung von Daten an upload.php sowie Form des Uploads! 
    //session_start();
?>

<!DOCTYPE html>
<html lang="DE">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" /><!-- Core Meta Data -->
        <title>SEQUENCE</title>
        <style>
        
        </style>
        
    </head>
<body>
<script type="text/javascript">

function fileChange()
{
    //FileList Objekt aus dem Input Element mit der ID "fileA"
    var fileList = document.getElementById("fileA").files;
 
    //File Objekt (erstes Element der FileList)
    var file = fileList[0];
 
    //File Objekt nicht vorhanden = keine Datei ausgewählt oder vom Browser nicht unterstützt
    if(!file)
        return;
 
    document.getElementById("fileName").innerHTML = 'Dateiname: ' + file.name;
    document.getElementById("fileSize").innerHTML = 'Dateigröße: ' +Math.floor(file.size /1024 /1024) + ' MB';
    document.getElementById("fileType").innerHTML = 'Dateitype: ' + file.type;
    document.getElementById("progress").value = 0;
    document.getElementById("prozent").innerHTML = "0%";

    //Weitergabe Dateiname (globale Variable) für Alert nach Upload:
    window.name = file.name;
}

var client = null;

function uploadFile()
{
    //Wieder unser File Objekt
    var file = document.getElementById("fileA").files[0];
    //FormData Objekt erzeugen
    var formData = new FormData();
    //XMLHttpRequest Objekt erzeugen
   	client = new XMLHttpRequest();
	
    var prog = document.getElementById("progress");
 
    if(!file)
        return;
 
    prog.value = 0;
    prog.max = 100;
 
    //Fügt dem formData Objekt unser File Objekt hinzu
    formData.append("datei", file);
 
    client.onerror = function(e) {
        alert("onError");
    };
 
    client.onload = function(e) {
        document.getElementById("prozent").innerHTML = "100%";
        prog.value = prog.max;
    };
 
    client.upload.onprogress = function(e) {
		var p = Math.round(100 / e.total * e.loaded);
        document.getElementById("progress").value = p;            
        document.getElementById("prozent").innerHTML = p + "%";
    };
	
	client.onabort = function(e) {
		alert("Upload abgebrochen");
	};
    
    //Hier werden die Parameter an upload.php geschickt ------------------------------\\
    client.open("POST", "../upload/upload.php");
    client.send(formData);
    window.alert("Mediaupload und Datenbankeintrag erfolgt");
    window.alert(window.name);
    document.getElementById("form").reset();
    document.getElementById("fileName").innerHTML = 'Dateiname: ';
    document.getElementById("fileSize").innerHTML = 'Dateigröße: ';
    document.getElementById("fileType").innerHTML = 'Dateitype: ';
    document.getElementById("progress").value = 0;
    document.getElementById("prozent").innerHTML = "0%";
            
}

function uploadAbort() {
	if(client instanceof XMLHttpRequest)
		//Briecht die aktuelle Übertragung ab
		client.abort();
}
</script>

<!-- HIER IST DER WUNDERSCHÖNE CUSTOM UPLOAD BUTTON, aus irgendeinem Grund refresht der aber leider die Page :/ BITTE TROTZDEM BELASSEN DERWEIL, DANKE
<form action="" id="file_ajax" method="post" enctype="multipart/form-data"> 
    !-- Das Label ist eine Fläche HINTER dem "upl video"-Button die den Filechange() triggert durch Klick 
    <label for="file-upload" class="custom-file-upload">
    <i class="fas fa-angle-double-up"></i> upl video
    </label>
    !-----------------------------------------------------Hier der Klick hinter Customupload-Button------------FILE AUSSUCHEN ------------
    <input name="file" type="file" id="file-upload" onchange="fileChange();"/></br>    
    !--------------------------------------------------------------------------------FILE UPLOADEN ----------------------------------------                
    <button name="upload" form="subtaskerstellung" type="submit" value="Upload" class="customsubmit" onclick="uploadFile();">absenden</button>    
    !--------------------------------------------------------------------------------UPLOAD ABBRECHEN ----------------------------------------
    <input name="abort" value="Upload abbrechen" class="customsubmit" type="button" onclick="uploadAbort();" />
</form>
!-- Ausgabe Metadaten --
<div>
    <div id="fileName"></div>
    <div id="fileSize"></div>
    <div id="fileType"></div>
    <progress id="progress" class="progress"></progress> <span id="prozent"></span>
    <br>
    <br>
</div>
-->

<!-- Form für Dateiupload (Buttons, Metadatenausgabe) ---------------------------------------------->
<form action="" method="post" enctype="multipart/form-data" id="form">
    <input name="file"  type="file" id="fileA" onchange="fileChange();"/>
    <br><br>
    <input name="upload" value="Upload" class="customsubmit" type="button" onclick="uploadFile();" />
	<input name="abort" value="Upload abbrechen" class="customsubmit" type="button" onclick="uploadAbort();" />

<div>
    <div id="fileName"></div>
    <div id="fileSize"></div>
    <div id="fileType"></div>
    <progress id="progress" class="progress"></progress> <span id="prozent"></span>
    <br>
    <br>
</div>
</form>

</body>
</html>