<?php
    include 'dbhandler.php';
    //include 'comments.php';  
    session_start();
    
    $stid = $_SESSION['stid'];    
    $_SESSION['stid'] = $stid;    
    
    $mitarbeiterid = $_SESSION['mitarbeiterid'];

    $mediaid = $_SESSION['mediaid'];
    
    if($mediaid == ''){
        $sql = "SELECT * FROM screenshot WHERE subtaskid = $stid ORDER BY screenshotid DESC";
    } else {
        $sql = "SELECT * FROM screenshot WHERE mediaid = $mediaid ORDER BY screenshotid DESC";
    }
   

//    $sql = "SELECT * FROM screenshot WHERE subtaskid = $stid ORDER BY screenshotid DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = '';

    while ($row = $result->fetch_assoc()){
        //echo $row['screenshotpfad'];
        $data .=    '<div class="col-lg-4">
                        <div class="card-group">
                            <div class="card mb-3">
                                <a href="'.$row['screenshotpfad'].'">
                                    <img src="'.$row['screenshotpfad'].'" 
                                        class="card-img-top" max-height="150" max-width="150">
                                </a>
                            </div>
                        </div>
                    </div>';
    }
    echo $data;


?>