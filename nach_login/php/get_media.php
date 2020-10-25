<?php
//get_media.php
//Holt alle Informationen aus Tabelle "media" für st_view.php
session_start();
include_once "dbhandler.php";
    $stid = $_SESSION['stid'];
    // Abfrage Media dieser Subtask f. Tabelle: 
    $sql1 = "";
    //$sql1 = "SELECT * FROM media WHERE subtaskid = $stid ORDER BY mediaid ASC";                              
    //$resultset = mysqli_query($conn, $sql1) or die("database error:". mysqli_error($conn));
    $result = array();
    $sql = "SELECT * FROM media WHERE subtaskid = $stid ORDER BY mediaid ASC";
    $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
        while($record = mysqli_fetch_array($resultset, MYSQLI_ASSOC)) {
            //ACHTUNG ZERBRECHLICH (Datumreformatting)
            $uploaddatum1 = $record['uploaddatum'];
            $uploaddatum = date('d.m.yy',strtotime($uploaddatum1));            
            $row_array['uploaddatum'] = $uploaddatum;
            //Rest des Arrays:            
            $row_array['mediaid'] = $record['mediaid'];
            $row_array['erstellerid'] = $record['erstellerid'];
            $row_array['updatebeschreibung'] = $record['updatebeschreibung'];
            $row_array['dateiname'] = $record['dateiname'];
            //Ergebnisse in das Array geben:            
            array_push($result,$row_array); 
            
        } 
           echo json_encode($result);
?> 