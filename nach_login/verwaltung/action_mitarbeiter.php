<?php
    include '../php/dbhandler.php';
    include_once 'verw_db.php';

    // Ausgabe Projekte
    if(isset($_POST['action']) && $_POST['action'] == "view"){
        $ausgabe = '';
        $data = read_mitarbeiter();
        // print_r($data1);

        if (AnzahlEintraege()>0){
            $ausgabe .= '<table class="table table-striped w-auto">
                        <thead>
                            <tr>
                                
                            <th class="verw_th" scope="col">#</th>
                            <th scope="col" class="no-sort">Avatar</th>
                            <!-- Name = Vorname + Nachname -->
                            <th scope="col">Name</th>
                            <th scope="col">Login</th>
                            <th scope="col">Status</th>
                            <!-- NTH Position: Bei Klick auf Feld werden alle Mitarbeiter in gleicher Position angezeigt -->
                            <th scope="col">Position</th>
                            <!-- NTH siehe Position: Bei Klick auf Feld werden alle Mitarbeiter mit überschneidenden Skills angezeigt -->
                            <th scope="col" class="no-sort">Skillset</th>
                            <th class="text-center" scope="col">Mitarbeiter seit</th>
                            <th scope="col">Straße</th>
                            <th scope="col">PLZ</th>                                
                            <th scope="col">Ort</th>                                
                            <!-- Projekte, Subtasks NTH: Anzahl + Link jeweils zu Projekten u. Subtasks -->
                            <!--th scope="col">Anzahl Projekte / Subtasks</th-->
                            <th scope="col" class="no-sort">Aktion</th>

                            </tr>
                        </thead>
                        <tbody>';
                            foreach ($data as $row){
                                // print_r($data1);
                                
                                $ausgabe .= '<tr>
                                                <td scope="row">'.$row["mitarbeiterid"].'</td>
                                                <td><img class="avatar_verw" src="../../' . $row["avatarpfad"] . '"></td>  
                                                <td>'.$row["vorname"]. " " .$row["nachname"].'</td>  
                                                <td>'.$row["email"].'</td>
                                                <td class="text-center istatus" id="' . $row['status'] . '">'  . $row['status'] . '</td>                                                                                      
                                                <td>'  . $row['position'] . '</td>
                                                <td>'.$row["skills"].'</td> 
                                                <td>' . date('d.m.y',strtotime($row['mitarbeiterseit'])) . '</td>
                                                <td>'.$row["strasse"].'</td> 
                                                <td>'.$row["plz"].'</td> 
                                                <td>'.$row["ort"].'</td> 
                                                <!--td class="text-center">1 / 2</td-->  
                                                <td scope="col">
                                                <a href="#" id="' . $row['mitarbeiterid'] . '" title="Editieren" class="text-primary editBtn" data-toggle="modal" data-target="#editMod"><i class="fas fa-edit fa-lg"></i></a>
                                                
                                                <a href="#" id="' . $row['mitarbeiterid'] . '" class="text-danger deleteBtn" title="Löschen"><i class="fas fa-trash-alt fa-lg"></i></a>
                                                </td>                          
                                            </tr>
                                ';
                            }
                            
                            $ausgabe .= '</tbody></table>';
                            echo $ausgabe;
                            // print_r($ausgabe);
        }  else {
            echo '<h3 class="text-center text-secondary mt-5">Keine Einträge gefunden</h3>';
        }
    }
// Update 1 (Holen der Daten)
    if(isset($_POST['edit_id'])){
        include_once 'verw_db.php';
        include '../php/dbhandler.php';

        $mitarbeiterid = $_POST['edit_id'];

        $row = getMitarbeiter($mitarbeiterid);
        echo json_encode($row);   
    }

// Update 2 (Updaten)
    if(isset($_POST['action']) && $_POST['action'] == "update"){
        include_once 'verw_db.php';
        include '../php/dbhandler.php';
        
        $mitarbeiterid = $_POST['mitarbeiterid'];
        $email = $_POST['email'];
        $vorname = $_POST['vorname'];
        $nachname = $_POST['nachname'];
        $status = $_POST['status'];
        $position = $_POST['position'];
        $skills = $_POST['skills'];
        $strasse = $_POST['strasse'];
        $plz = $_POST['plz'];
        $ort = $_POST['ort'];
        

        updatemitarbeiter($email, $vorname, $nachname, $status, $position, $skills, $strasse, $plz, $ort, $mitarbeiterid);
    }

// Delete
    if(isset($_POST['del_id'])){
        $mitarbeiterid = $_POST['del_id'];
        // print_r($projektid);
        
        deletemitarbeiter($mitarbeiterid);
    }
?>