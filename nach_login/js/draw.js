// Diese Datei ist AUSSCHLIESSLICH für die Bildbearbeitung eines Videoscreenshots im Browser
// Referenz zum Canvas
let canvas;
// Zwischenvariable, welche die Funktionen bereitstellt 
let cntxt;
// Zwischenspeicher 
let savedImageData;
// Speichert, ob die Maus während des Klicks gezogen wird
let dragging = false;

// Init. Variablen
let strokeColor = '';
let lineColor = '';
let lineWidth = 1;
let currTool = 'brush';

// Canvasmaße, hier hardcoded, können aber entfernt werden:
// let canvasWidth = document.getElementById('my-canvas').width;
// let canvasHeight = document.getElementById('my-canvas').height;

// Farbwechselfunktion
function changeColor(color) {
    cntxt.strokeStyle = color;
};

// Linienbreite-Wechsel
function changeWidth(drawingWidth) {
    cntxt.lineWidth = drawingWidth;
};

// Speichert, ob gerade der Pinsel verwendet wird
let usingBrush = false;
// Speichert die Linien X u. Ys in einem Array für die Linien
let brushXPoints = new Array();
let brushYPoints = new Array();
// Speichert, ob die Maus gerade geklickt wird
let brushDownPos = new Array();

// Speichert die Maßdaten wenn die Formen (oder die gerade Linie) größergezogen werden
class BoundingBox{
    constructor(left, top, width, height) {
        this.left = left;
        this.top = top;
        this.width = width;
        this.height = height;
    }
}

// Speichert Position, bei welcher geklickt wird 
class MouseDownPos{
    constructor(x,y) {
        this.x = x,
        this.y = y;
    }
}

// Speichert aktuelle Position der Maus 
class Location{
    constructor(x,y) {
        this.x = x,
        this.y = y;
    }
}

// Initialisierung boundingBox 
let boundingBox = new BoundingBox(0,0,0,0);
// Init. Mausklick-Position
let mousedown = new MouseDownPos(0,0);
// Init. "jetzige" Mausposition
let loc = new Location(0,0);

// Funktion setupCanvas nach Laden des DOMs ausführen:
document.addEventListener('DOMContentLoaded', setupCanvas);


function setupCanvas(){
    // Referenz zur Zeichenfläche
    canvas = document.getElementById('my-canvas');
    // Zeichenfläche im zweidimensionalem Kontext verstehen
    cntxt = canvas.getContext('2d');
    // Zuweisen unserer Funktionen zu den HTML-Canvas Eigenschaften 
    cntxt.strokeStyle = changeColor();
    cntxt.lineWidth = changeWidth();
    // Funktion feuern wenn Maus gedrückt
    canvas.addEventListener("mousedown", MouseDown);
    // Funktion feuern wenn Maus gedrückt und bewegt
    canvas.addEventListener("mousemove", MouseMove);
    // Funktion feuern wenn Maus losgelassen
    canvas.addEventListener("mouseup", MouseUp);
}

// Werkzeug ändern zu:
function ChangeTool(toolClicked){
    document.getElementById("brush").className = "";
    document.getElementById("line").className = "";
    document.getElementById("rectangle").className = "";
    document.getElementById("circle").className = "";
    document.getElementById("ellipse").className = "";
    
    // Hervorheben aktives Tool
    document.getElementById(toolClicked).className = "selected";
    // Ändern des Werkzeugs
    currTool = toolClicked;
}

// Holt Mausposition
function GetMousePosition(x,y){
    // Maße des Canvas bestimmen
    let canvasSizeData = canvas.getBoundingClientRect();
    return { 
        x: (x - canvasSizeData.left) * (canvas.width  / canvasSizeData.width),
        y: (y - canvasSizeData.top)  * (canvas.height / canvasSizeData.height)
      };
}

// Zwischenspeichern des Bildes
function SaveCanvas(){
    savedImageData = cntxt.getImageData(0,0,canvas.width,canvas.height);
}

// Laden des Zwischenspeichers
function RedrawCanvas(){
    cntxt.putImageData(savedImageData,0,0);
}

// Updaten der Formengrößen
function updateShapeSize(loc){
    // beherbergen die Differenz im Koordinatensystem zwischen initialem 
    // Mausdruck und aktueller Position.
    boundingBox.width = Math.abs(loc.x - mousedown.x);
    boundingBox.height = Math.abs(loc.y - mousedown.y);

    // Wenn Maus aktuell weiter rechts als bei Mausdruck
    if(loc.x > mousedown.x){
        // dann Speichern des Mausdruck-Wertes als linke Seite
        boundingBox.left = mousedown.x;
    } else {
        // Sonst aktuelle Position für links setzen
        boundingBox.left = loc.x;
    }

    // Wenn Maus aktuell UNTER init. Mausdruck
    if(loc.y > mousedown.y){
        // dann Speichern des Mausdruck-Wertes
        boundingBox.top = mousedown.y;
    } else {
        // Sonst akt. Pos. speichern
        boundingBox.top = loc.y;
    }
}

// Mauszieh-Aktionen
function drawShape(loc){
    cntxt.strokeStyle = changeColor();
    cntxt.fillStyle = lineColor;
    if(currTool === "brush"){
        // Pinsel-Funktions-Call
        DrawBrush();
    } else if(currTool === "line"){
        // Linie zeichnen
        cntxt.beginPath();
        cntxt.moveTo(mousedown.x, mousedown.y);
        cntxt.lineTo(loc.x, loc.y);
        cntxt.stroke();
        // "cntxt.closePath();" von mir
        cntxt.closePath();
    } else if(currTool === "rectangle"){
        // Rechtecke zeichnen
        cntxt.strokeRect(boundingBox.left, boundingBox.top, boundingBox.width, boundingBox.height);
    } else if(currTool === "circle"){
        // Kreise zeichnen
        let radius = boundingBox.width;
        cntxt.beginPath();
        cntxt.arc(mousedown.x, mousedown.y, radius, 0, Math.PI * 2);
        cntxt.stroke();
    } else if(currTool === "ellipse"){
        // Ellipsen zeichnen
        // cntxt.ellipse(x, y, radiusX, radiusY, rotation, startAngle, endAngle)
        let radiusX = boundingBox.width / 2;
        let radiusY = boundingBox.height / 2;
        cntxt.beginPath();
        cntxt.ellipse(mousedown.x, mousedown.y, radiusX, radiusY, Math.PI / 4, 0, Math.PI * 2);
        cntxt.stroke();
    } 
}
// Maus-ziehen-Aktualisierungen
function updateShape(loc){
    // Speichert adaptierte Höhe & Breite, und x + y-Koordinaten des Startpunktes, (meist oben links der geom. Form)
    updateShapeSize(loc);
    // Neuzeichnen der Form
    drawShape(loc);
}


// Speichert Koordinaten wenn Maus bewegt in jew. Array und ob Maus gedrückt 
function AddBrushPoint(x, y, mouseDown){
    brushXPoints.push(x);
    brushYPoints.push(y);
    brushDownPos.push(mouseDown);
}

// Durchlaufen aller Pinsel-Punkte (Array) und verbinden der Punkte
function DrawBrush(){
    for(let i = 1; i < brushXPoints.length; i++){
        cntxt.beginPath();
        // Überprüfung, ob Maus gedrückt wird, wenn ja weiterzeichnen 
        if(brushDownPos[i]){
            cntxt.moveTo(brushXPoints[i-1], brushYPoints[i-1]);
        } else {
            cntxt.moveTo(brushXPoints[i]-1, brushYPoints[i]);
        }     
        cntxt.lineTo(brushXPoints[i], brushYPoints[i]);

        cntxt.closePath();
        cntxt.stroke();    
        console.log(cntxt);          
    }
}

function MouseDown(e){
    // Cursor zu Fadenkreuz
    canvas.style.cursor = "crosshair";
    // Mauspos. speichern 
    loc = GetMousePosition(e.clientX, e.clientY);
    // Zwischenspeichern
    SaveCanvas();
    // Klick-Pos. speichern
    mousedown.x = loc.x;
    mousedown.y = loc.y;
    // Maus wird gezogen: true
    dragging = true;

    // Speichern der Pinselkoordination in Array
    if(currTool === 'brush'){
        usingBrush = true;
        AddBrushPoint(loc.x, loc.y);
        }
};

function MouseMove(e){
    canvas.style.cursor = "crosshair";
    loc = GetMousePosition(e.clientX, e.clientY);

    // Löscht alles außerhalb des Canvas bei Pinselzeichn.
    if(currTool === 'brush' && dragging && usingBrush){
        if(loc.x > 0 && loc.x < document.getElementById('my-canvas').width && loc.y > 0 && loc.y < document.getElementById('my-canvas').height){
            AddBrushPoint(loc.x, loc.y, true);
        }
        RedrawCanvas();
        DrawBrush();
    } else {
        if(dragging){
            RedrawCanvas();
            updateShape(loc);
        }
    }
};

// Wenn Maus losgelassen wird, Aktualisierung des Canvas, der Mauszustände + Mausposition, Leeren der Pinselarrays
function MouseUp(e){
    canvas.style.cursor = "default";
    loc = GetMousePosition(e.clientX, e.clientY);
    RedrawCanvas();
    updateShape(loc);
    dragging = false;
    usingBrush = false;
    brushXPoints = [];
    brushYPoints = [];
}

// Texterstellung

//TODO: Herausfinden WO der Canvasinhalt gelöscht wird nach Drücken Button "Text speichern" - ERLEDIGT

function saveTextFromArea(y,x){
    // Inhalt des Textfeldes holen, danach Button und Textfeld entfernen    
    var text = $('textarea#textareaTest').val();
    $('textarea#textareaTest').remove();
    $('#saveText').remove();
    $('#textAreaPopUp').remove();
    
    // Wieder Referenz und Def. Canvas  
    var canvas = document.getElementById('my-canvas');
    var cntxt = canvas.getContext('2d');

     
    //---------------------------------ACHTUNG: AUSKOMMENTIERT LASSEN, SONST WEIßER CANVAS---------------------
    //var cw = canvas.clientWidth;
    //var ch = canvas.clientHeight;
    //canvas.width = w;
    //canvas.height = h;
    //----------------------BIS HIER. WENN ALS KOMMENTAR, SONST LÖSCHEN DES BEREITS ERSTELLTEN CANVAS--------------
    
    // Aufbrechen des Textes in Array wenn Breite > 200px    
    var phraseArray = getLines(cntxt,text,100);

    // Enabling der Canvas-Text-Funktionen  
    CanvasTextFunctions.enable(cntxt);
    
    var counter = 0;
    
    // HIER SCHRIFTART ÄNDERN  --------- 
    var font = "arial";
    var fontsize = 18;
    cntxt.strokeStyle = "rgb(247, 44, 44)";
    cntxt.shadowOffsetX = 2;
    cntxt.shadowOffsetY = 2;
    cntxt.shadowBlur = 1;
    cntxt.shadowColor = "rgba(0,0,0,1)";
     
    
    $.each(phraseArray, function() {
        // Textaufteilung (Linienhöhe, Zeilencount), 
        // Newline ist eine Maßeinheit die der Textausgabe hinzugerechnet wird bei mehreren Zeilen
        var lineheight = fontsize * 1.5;
        var newline = ++counter;
        newline = newline * lineheight;

        canvas2.addEventListener('mousemove', function(evt) {
            var mousePos = getMousePos(canvas2, evt);
            mouseX = mousePos.x;
            mouseY = mousePos.y;
            }, false);

        var topPlacement = y - $("#my-canvas").position().top + newline;
        var leftPlacement = x - $("#my-canvas").position().left;
        text = this;

        //Drucken des Textes       
        console.log(cntxt);
        cntxt.drawText(font, fontsize, leftPlacement, topPlacement, text);
        cntxt.save();
        cntxt.restore();
    });    
    
    // Zurücksetzen des Schattens, damit andere zwischenzeitl. Formen/Zeichnungen diesen nicht auch haben
    cntxt.shadowOffsetX = 0;
    cntxt.shadowOffsetY = 0;
    cntxt.shadowBlur = 0;
    cntxt.shadowColor = "rgba(0,0,0,0)";
}

function getLines(cntxt,phrase,maxPxLength=100) {
    //Aufbrechen des Textes in mehrere Zeilen
    var wa=phrase.split(" "),
    phraseArray=[],
    lastPhrase="",
    l=maxPxLength,
    measure=0;
    cntxt.font = "16px sans-serif";
    for (var i=0;i<wa.length;i++) {
        var w=wa[i];
        measure=cntxt.measureText(lastPhrase+w).width;
        if (measure<l) {
            lastPhrase+=(" "+w);
        }else {
            phraseArray.push(lastPhrase);
            lastPhrase=w;
        }
        if (i===wa.length-1) {
            phraseArray.push(lastPhrase);
            break;
        }
    }
    return phraseArray;
}