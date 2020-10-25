<?php

//fetch_comment.php

session_start();
// include '../php/dbhandler.php';

$connect = new PDO('mysql:host=localhost;dbname=sqdb', 'root', '');
@$pid = $_SESSION['projektid'];
@$stid = $_SESSION['stid'];

if ($stid == ''){
    $query = "SELECT * FROM kommentare, mitarbeiter WHERE parent_comment_id = '0' AND comment_sender_name = mitarbeiterid AND projektid = $pid";
} else {
    $query = "SELECT * FROM kommentare, mitarbeiter WHERE parent_comment_id = '0' AND comment_sender_name = mitarbeiterid AND subtaskid = $stid";
}


//$query = "SELECT * FROM kommentare, mitarbeiter WHERE parent_comment_id = '0' AND comment_sender_name = mitarbeiterid";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();
$output = '';
foreach($result as $row) {
    $output .= '
    <div class="panel panel-default">
    <div class="panel-heading">Von <b>' . $row["vorname"] . ' ' . $row["nachname"] . '</b> am <i>'.$row["date"].'</i></div>
    <div class="panel-body">'.$row["comment"].'</div>
    <div class="" align="left"><button type="button" class="customsubmit4 reply" id="'.$row["comment_id"].'">Antworten</button></div>
    
    </div>
    ';
    $output .= get_reply_comment($connect, $row["comment_id"]);
}

echo $output;

function get_reply_comment($connect, $parent_id = 0, $marginleft = 0)
{
    @$pid = $_SESSION['projektid'];
    @$stid = $_SESSION['stid'];
    if ($stid == ''){
        $query2 = "SELECT * FROM kommentare, mitarbeiter WHERE parent_comment_id = '".$parent_id."' AND comment_sender_name = mitarbeiterid AND projektid = $pid";
    } else {
        $query2 = "SELECT * FROM kommentare, mitarbeiter WHERE parent_comment_id = '".$parent_id."' AND comment_sender_name = mitarbeiterid AND subtaskid = $stid";
    }
    //$query2 = "SELECT * FROM kommentare, mitarbeiter WHERE parent_comment_id = '".$parent_id."' AND comment_sender_name = mitarbeiterid";
    $output = '';
    $statement = $connect->prepare($query2);
    $statement->execute();
    $result = $statement->fetchAll();
    $count = $statement->rowCount();

    if($parent_id == 0) {
        $marginleft = 0;
    } else {
        $marginleft = $marginleft +  15;
    }
    if($count > 0) {
        foreach($result as $row) {
            $output .= '
            <div class="panel panel-default" style="margin-left:'.$marginleft.'px">
                <div class="panel-heading">Von <b>'. $row["vorname"] . ' ' . $row["nachname"] .'</b> am <i>'.$row["date"].'</i></div>
                <div class="panel-body">'.$row["comment"].'</div>
                <div class="" align="left"><button type="button" class="customsubmit4 reply" id="'.$row["comment_id"].'">Antworten</button></div>
                <hr class="my-4">
            </div>
            ';
            $output .= get_reply_comment($connect, $row["comment_id"], $marginleft);
        }
    }
    return $output;
}


/* Ein gescheiterter Versuch (prozedural)
$sql = "SELECT * FROM tbl_comment, mitarbeiter WHERE parent_comment_id = '0' AND comment_sender_name = mitarbeiterid";
    $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
    foreach($resultset as $row) {
        $date1 = date('d.m.y H:i:s',strtotime($row['date']));
        $kommentar = $row['comment'];
        $kommentarid = $row['comment_id'];
        $vorname = $row['vorname'];
        $nachname = $row['nachname'];
        $position = $row['position'];        
        $comment_name = $vorname . ' ' . $nachname;    
        $output = "";

        $output .= '
            <div class="panel panel-default">
            <div class="panel-heading">Von <b>'.$comment_name.'</b> am <i>'.$date1.'</i></div>
            <div class="panel-body">'.$kommentar.'</div>
            <div class="panel-footer" align="right"><button type="button" class="customsubmit2 reply" id="'.$kommentarid.'">Antworten</button></div>
            </div>
            ';
            
            $output .= get_reply_comment($conn, $kommentarid);
            echo @$output;
        } 

function get_reply_comment($conn, $parent_id = 0, $marginleft = 0) {
    //echo nl2br("parentid: " . $parent_id . "\n\r");
    //SELECT * FROM tbl_comment JOIN mitarbeiter ON mitarbeiter.mitarbeiterid = tbl_comment.comment_sender_name WHERE parent_comment_id = 40
    $sql = "SELECT * FROM tbl_comment JOIN mitarbeiter ON mitarbeiter.mitarbeiterid = tbl_comment.comment_sender_name WHERE parent_comment_id = $parent_id";
    $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
        foreach($resultset as $row) {
            $count = mysqli_num_rows($resultset);
            //echo nl2br ("Anzahl Antworten: " . $count . "\n\r");
            $date1 = date('d.m.y H:i:s',strtotime($row['date']));
            $kommentar = $row['comment'];
            $kommentarid = $row['comment_id'];
            $vorname = $row['vorname'];
            $nachname = $row['nachname'];
            $position = $row['position'];
            $comment_name = $vorname . ' ' . $nachname; 
            $output = '';    
    
        if($parent_id == 0) {
            $marginleft = 0;
            } else {
            $marginleft = $marginleft +  8;
            }
            if($count > 0) {
                foreach($resultset as $row) {
                    $output .= '
                    <div class="panel panel-default" style="margin-left:' . $marginleft . 'px">
                        <div class="panel-heading">Von <b>' . $comment_name.'</b> am <i>' . $date1 . '</i></div>
                        <div class="panel-body">' . $kommentar . '</div>
                        <div class="panel-footer" align="right"><button type="button" class="customsubmit2" id="' . $kommentarid . '">Antworten</button></div>
                    </div>
                    ';
                    $output .= get_reply_comment($conn, $kommentarid, $marginleft);
                    
                }
            } //return $output; 
            echo @$output;
        }
    }   
*/


?>