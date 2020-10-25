<?php

    include '../php/dbhandler.php';
    session_start(); 


// Kunden CREATE
    function insertkunde($firmenname, $kontaktvorname, $kontaktnachname, $email, $telefon, $strasse, $ort, $plz, $land, $branche, $beschreibung){
        include '../php/dbhandler.php';
        $sql = "INSERT INTO kunde (firmenname, kontaktvorname, kontaktnachname, email, telefon, strasse, ort, plz, land, branche, beschreibung) VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Variablen dem Prepared statement zuweisen
            mysqli_stmt_bind_param($stmt, "sssssssssss", $param_firmenname, $param_kontaktvorname, $param_kontaktnachname, $param_email, $param_telefon, $param_strasse, $param_ort, $param_plz, $param_land, $param_branche, $param_beschreibung);
            
            // Parameter setzen
            $param_firmenname = $firmenname;
            $param_kontaktvorname = $kontaktvorname;
            $param_kontaktnachname = $kontaktnachname;
            $param_email = $email;
            $param_telefon = $telefon;
            $param_strasse = $strasse;
            $param_ort = $ort;
            $param_plz = $plz;
            $param_land = $land;
            $param_branche = $branche;
            $param_beschreibung = $beschreibung;
            
            if(mysqli_stmt_execute($stmt)){
                return true;
                echo "Kunde " . $firmenname . " wurde angelegt.";
            } else{
                return true;
                echo nl2br("Bei der Kundenanlage ist ein Fehler aufgetreten, bitte schicken Sie uns ein Mail an info@sequence.com.\n");
            }
            // Statement schließen
            mysqli_stmt_close($stmt);
            
        }
    }   

// Kunden READ
    // Wird in action_*.php gecallt
    read_kunden();
    function read_kunden(){        
        include '../php/dbhandler.php';
       
        // ACHTUNG: WENN ANZAHL EINTRÄGE IN DER TABELLE <= 0 dann u.U. gibt es Fehler mit "ORDER BY"!
        $sql = "SELECT * FROM kunde ORDER BY kundenID ASC";     
        $stmt = mysqli_prepare($conn,$sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        foreach ($result as $row){
            // print_r($data0);               
            $data[] = $row;
        }                
        //    print_r($data);  
        return $data;
    }
// Kunde By ID
    function getKunde($kundenid){        
        include '../php/dbhandler.php';
    
        $sql = "SELECT * FROM kunde WHERE kundenID = ?";     
        $stmt = mysqli_prepare($conn,$sql);

        mysqli_stmt_bind_param($stmt, 's',$kundenid);
    
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        
        return $data;
        // console.log($data);
    }

// Kunden UPDATE
    function updatekunde($firmenname, $kontaktvorname, $kontaktnachname, $email, $telefon, $strasse, $ort, $plz, $land, $branche, $beschreibung, $kundenid){
        include '../php/dbhandler.php';
        $conn = mysqli_connect('localhost', 'root', '', 'sqdb');

        if (!$conn) {
            die("Connection failed: ".mysqli_connect_error());
        }
        

        $sql = "UPDATE kunde SET firmenname = ?, kontaktvorname = ?, kontaktnachname = ?, email = ?, telefon = ?, strasse = ?, ort = ?, plz = ?, land = ?, branche = ?, beschreibung = ? WHERE kundenID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt === FALSE){ die(mysqli_error($conn)); }
        
        mysqli_stmt_bind_param($stmt, 'sssssssssssi', $firmenname, $kontaktvorname, $kontaktnachname, $email, $telefon, $strasse, $ort, $plz, $land, $branche, $beschreibung, $kundenid);
        mysqli_stmt_execute($stmt);
        //echo mysqli_affected_rows($conn);
        
        mysqli_close($conn);

        return true;
    }
// Kunden DELETE
    function deletekunde($kundenid){
        include '../php/dbhandler.php';

        $deleteQry = "DELETE FROM kunde WHERE kundenID = ?";
        $deleteStatement = mysqli_prepare($conn,$deleteQry);
        
        mysqli_stmt_bind_param($deleteStatement, 'i',$kundenid);
        mysqli_stmt_execute($deleteStatement);
        
        echo mysqli_affected_rows($conn);
        return true;
    }

// Mitarbeiter CREATE Deaktiviert, da Registrierungsformular
// function insertkunde($firmenname, $kontaktvorname, $kontaktnachname, $email, $telefon, $strasse, $ort, $plz, $land, $branche, $beschreibung){
//     include '../php/dbhandler.php';
//     $sql = "INSERT INTO kunde (firmenname, kontaktvorname, kontaktnachname, email, telefon, strasse, ort, plz, land, branche, beschreibung) VALUES
//     (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
//     if($stmt = mysqli_prepare($conn, $sql)){
//         // Variablen dem Prepared statement zuweisen
//         mysqli_stmt_bind_param($stmt, "sssssssssss", $param_firmenname, $param_kontaktvorname, $param_kontaktnachname, $param_email, $param_telefon, $param_strasse, $param_ort, $param_plz, $param_land, $param_branche, $param_beschreibung);
        
//         // Parameter setzen
//         $param_firmenname = $firmenname;
//         $param_kontaktvorname = $kontaktvorname;
//         $param_kontaktnachname = $kontaktnachname;
//         $param_email = $email;
//         $param_telefon = $telefon;
//         $param_strasse = $strasse;
//         $param_ort = $ort;
//         $param_plz = $plz;
//         $param_land = $land;
//         $param_branche = $branche;
//         $param_beschreibung = $beschreibung;
        
//         if(mysqli_stmt_execute($stmt)){
//             return true;
//             echo "Kunde " . $firmenname . " wurde angelegt.";
//         } else{
//             return true;
//             echo nl2br("Bei der Kundenanlage ist ein Fehler aufgetreten, bitte schicken Sie uns ein Mail an info@sequence.com.\n");
//         }
//         // Statement schließen
//         mysqli_stmt_close($stmt);
        
//     }
// }   

// Mitarbeiter READ
// Wird in action_*.php gecallt
read_mitarbeiter();
function read_mitarbeiter(){        
    include '../php/dbhandler.php';
   
    // ACHTUNG: WENN ANZAHL EINTRÄGE IN DER TABELLE <= 0 dann u.U. gibt es Fehler mit "ORDER BY"!
    $sql = "SELECT * FROM mitarbeiter ORDER BY mitarbeiterid ASC";     
    $stmt = mysqli_prepare($conn,$sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    foreach ($result as $row){
        // print_r($data0);               
        $data[] = $row;
    }                
    //    print_r($data);  
    return $data;
}
// Mitarbeiter By ID
function getMitarbeiter($mitarbeiterid){        
    include '../php/dbhandler.php';

    $sql = "SELECT * FROM mitarbeiter WHERE mitarbeiterid = ?";     
    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param($stmt, 's',$mitarbeiterid);

    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    
    return $data;
    // console.log($data);
}

// Mitarbeiter UPDATE
function updatemitarbeiter($email, $vorname, $nachname, $status, $position, $skills, $strasse, $plz, $ort, $mitarbeiterid){
    include '../php/dbhandler.php';
    // $conn = mysqli_connect('localhost', 'root', '', 'sqdb');

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }    

    $sql = "UPDATE mitarbeiter SET email = ?, vorname = ?, nachname = ?, `status` = ?, position = ?, skills = ?, strasse = ?, plz = ?, ort = ? WHERE mitarbeiterid = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if($stmt === FALSE){ die(mysqli_error($conn)); }
    
    mysqli_stmt_bind_param($stmt, 'sssssssssi', $email, $vorname, $nachname, $status, $position, $skills, $strasse, $plz, $ort, $mitarbeiterid);
    mysqli_stmt_execute($stmt);
    //echo mysqli_affected_rows($conn);
    
    mysqli_close($conn);

    return true;
}
// Mitarbeiter DELETE
function deletemitarbeiter($mitarbeiterid){
    include '../php/dbhandler.php';

    $deleteQry = "DELETE FROM mitarbeiter WHERE mitarbeiterid = ?";
    $deleteStatement = mysqli_prepare($conn,$deleteQry);
    
    mysqli_stmt_bind_param($deleteStatement, 'i',$mitarbeiterid);
    mysqli_stmt_execute($deleteStatement);
    
    echo mysqli_affected_rows($conn);
    return true;
}

// Projekte READ
    function read_projekte(){        
        include '../php/dbhandler.php';
       
        
        $data0 = array();
        $data1 = array();

        $sql = "SELECT * FROM projekt ORDER BY projektid ASC";     
        $stmt = mysqli_prepare($conn,$sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        foreach ($result as $row){
            // print_r($data0);   
            
            $data[] = $row;
        }                
        //   print_r($data1);  
        return $data;
    }
// Projekt By ID
    function getProjekt($projektid){        
        include '../php/dbhandler.php';
    
        $sql = "SELECT * FROM projekt WHERE projektid = ?";     
        $stmt = mysqli_prepare($conn,$sql);

        mysqli_stmt_bind_param($stmt, 's',$projektid);
    
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        
        return $data;
        // console.log($data);
    }

// Projekt UPDATE
    function updateprojekt($titel, $status, $deadline, $beschreibung, $typ, $kundeid, $projektid){
        include '../php/dbhandler.php';
        

        $sql = "UPDATE projekt SET titel = ?, `status` = ?, deadline = ?, beschreibung = ?, typ = ?, kundeid = ? WHERE projektid = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt === FALSE){ die(mysqli_error($conn)); }
        
        mysqli_stmt_bind_param($stmt, 'ssssssi', $titel, $status, $deadline, $beschreibung, $typ, $kundeid, $projektid);
        mysqli_stmt_execute($stmt);
        //echo mysqli_affected_rows($conn);
        
        mysqli_close($conn);

        return true;
    }
// Projekt DELETE
    function deleteprojekt($projektid){
        include '../php/dbhandler.php';

        $deleteQry = "DELETE FROM projekt WHERE projektid = ?";
        $deleteStatement = mysqli_prepare($conn,$deleteQry);
        
        mysqli_stmt_bind_param($deleteStatement, 'i',$projektid);
        mysqli_stmt_execute($deleteStatement);
        
        echo mysqli_affected_rows($conn);
        return true;
    }

// Subtask READ
    function read_subtasks(){        
        include '../php/dbhandler.php';
       
        
        $data0 = array();
        $data1 = array();

        $sql = "SELECT * FROM subtask ORDER BY subtaskid ASC";     
        $stmt = mysqli_prepare($conn,$sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        foreach ($result as $row){
            // print_r($data0);   
            
            $data[] = $row;
        }                
        //   print_r($data1);  
        return $data;
    }
// Subtask By ID
    function getSubtask($subtaskid){        
        include '../php/dbhandler.php';
    
        $sql = "SELECT * FROM subtask WHERE subtaskid = ?";     
        $stmt = mysqli_prepare($conn,$sql);

        mysqli_stmt_bind_param($stmt, 's',$subtaskid);
    
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        
        return $data;
        // console.log($data);
    }

// Subtask UPDATE
    function updatesubtask($titel, $status, $deadline, $beschreibung, $typ, $subtaskid){
        include '../php/dbhandler.php';

        $sql = "UPDATE subtask SET titel = ?, `status` = ?, deadline = ?, beschreibung = ?, typ = ? WHERE subtaskid = ?";
        $stmt = mysqli_prepare($conn, $sql);
        // if($stmt === FALSE){ die(mysqli_error($conn)); }
        
        mysqli_stmt_bind_param($stmt, 'sssssi', $titel, $status, $deadline, $beschreibung, $typ, $subtaskid);
        mysqli_stmt_execute($stmt);
        //echo mysqli_affected_rows($conn);
        
        mysqli_close($conn);

        return true;
    }
// Subtask DELETE
    function deletesubtask($subtaskid){
        include '../php/dbhandler.php';

        $deleteQry = "DELETE FROM subtask WHERE subtaskid = ?";
        $deleteStatement = mysqli_prepare($conn,$deleteQry);
        
        mysqli_stmt_bind_param($deleteStatement, 'i',$subtaskid);
        mysqli_stmt_execute($deleteStatement);
        
        echo mysqli_affected_rows($conn);
        return true;
    }

// ROWCOUNT f. Fehlermeldung
    function AnzahlEintraege(){
        include '../php/dbhandler.php';

        $sql = "SELECT * FROM projekt";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_execute($stmt);

        /* store result */
        mysqli_stmt_store_result($stmt);

        // printf("Number of rows: %d.\n", mysqli_stmt_num_rows($stmt));

        return mysqli_stmt_num_rows($stmt);
    }

// ARCHIV:
/*  Vorherige Sicherheitsüberprüfung
    //$mitarbeiterid = $_SESSION['mitarbeiterid'];
    $mitarbeiterid = $_SESSION['mitarbeiterid'];
    echo nl2br("MA-ID: " . $mitarbeiterid);

    // Check if the user is logged in, if not then redirect to login page
    if(!isset($_SESSION["active"]) || $_SESSION["active"] !== true){      
        header("location: ../../login.php");
    }

    //Überprüfung Mitarbeiterposition
    $sql2 = "SELECT position FROM mitarbeiter WHERE mitarbeiterid = $mitarbeiterid";
        $resultset = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));
        while($record = mysqli_fetch_assoc($resultset) ) {
            $position_ma = $record['position'];
            echo "MA-Position: " . $position_ma;
        }
        
    //Wenn Projektlead oder Admin, dann kein Problem
    if ($position_ma >= '3'){
        $permgranted = true;
        //Sonst Weiterleitung an Verwaltungsansicht für Mitarbeiter
    } else if ($position_ma <= '2') {
        //Test: echo "Kein Zugriff";
        header("location: Projektverwaltung_mitarbeiter.php");
    }

    */
?>