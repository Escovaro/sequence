<?php

//add_comment.php
session_start();
include '../php/dbhandler.php';
$error = '';
$mitarbeiterid = $_SESSION['mitarbeiterid'];
@$stid = $_SESSION['stid'];
@$projektid = $_SESSION['projektid'];


//Name der Person, die Kommentiert
$sql = "SELECT vorname, nachname, position FROM mitarbeiter WHERE mitarbeiterid = $mitarbeiterid";
                $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
                while($record = mysqli_fetch_assoc($resultset) ) {
                    $vorname = $record['vorname'];
                    $nachname = $record['nachname'];
                    $position = $record['position'];
                }

$comment_name = $vorname . ' ' . $nachname;
$comment_content = '';

if(empty($_POST["comment_content"])) {
    $error .= '<p class="text-danger">Bitte geben Sie eine Nachricht ein!</p>';
    }
    else {
        $comment_content = $_POST["comment_content"];
    }

if($error == '') { 
    $parent_comment_id = $_POST["comment_id"];

    if($stid == '') {
        $sql2 = "INSERT INTO kommentare (parent_comment_id, comment, comment_sender_name, projektid) VALUES (?, ?, ?, ?)";                                    
        if($stmt = mysqli_prepare($conn, $sql2)){
            // Variablen dem  prepared statement als Parameter übergeben
            mysqli_stmt_bind_param($stmt, "ssss", $param_parent_comment_id, $param_comment, $param_absender, $param_projektid);
            
            // Set parameters
            $param_parent_comment_id = $parent_comment_id;
            $param_comment = $comment_content;
            $param_absender = $mitarbeiterid;
            $param_projektid = $projektid;
        
            // Versuchen das prepared statement auszuführen
            if(mysqli_stmt_execute($stmt)){
                $error = '<label class="text-success">Kommentar hinzugefügt</label>';
                $data = array(
                    'error'  => $error
                );
                echo json_encode($data);
            } else {
                echo nl2br("Bei dem Erstellen des Kommentars ist ein Fehler aufgetreten, bitte schicken Sie uns ein Mail mit Screenshot an info@sequence.com.\n");
        
            // Statement schließen
            mysqli_stmt_close($stmt);
            }
            // Connection schließen
            mysqli_close($conn);
        }
    } 
        else {
        $sql = "INSERT INTO kommentare (parent_comment_id, comment, comment_sender_name, subtaskid) VALUES (?, ?, ?, ?)";                                    
        if($stmt = mysqli_prepare($conn, $sql)){
            // Variablen dem  prepared statement als Parameter übergeben
            mysqli_stmt_bind_param($stmt, "ssss", $param_parent_comment_id, $param_comment, $param_absender, $param_subtaskid);
            
            // Set parameters
            $param_parent_comment_id = $parent_comment_id;
            $param_comment = $comment_content;
            $param_absender = $mitarbeiterid;
            $param_subtaskid = $stid;
        
            // Versuchen das prepared statement auszuführen
            if(mysqli_stmt_execute($stmt)){
                $error = '<label class="text-success">Kommentar hinzugefügt</label>';
                $data = array(
                    'error'  => $error
                );
                echo json_encode($data);
            } else {
                echo nl2br("Bei dem Erstellen des Kommentars ist ein Fehler aufgetreten, bitte schicken Sie uns ein Mail mit Screenshot an info@sequence.com.\n");
        
            // Statement schließen
            mysqli_stmt_close($stmt);
            }
            // Connection schließen
            mysqli_close($conn);
        }
    }
    
}
