<?php
    include '../php/dbhandler.php';
    include_once 'verw_db.php';

    // Ausgabe Projekte
    if(isset($_POST['action']) && $_POST['action'] == "view"){
        $ausgabe = '';
        $data = read_projekte();
        // print_r($data1);

        if (AnzahlEintraege()>0){
            $ausgabe .= '<table class="table table-striped w-auto">
                        <thead>
                            <tr>
                                <th scope="col" class="no-sort">Detailansicht</th>
                                <th class="verw_th no-sort" scope="col">#</th>
                                <th scope="col">Status</th>
                                
                                <th scope="col">Titel</th>                                
                                <th scope="col">Deadline</th>
                                <th scope="col">Letzte Änderung</th>
                                <th scope="col">Erstelldatum</th>   
                                <th scope="col" class="no-sort">Mitarbeiteravatare</th>
                                <th scope="col">Projekttyp</th>
                                <th scope="col" class="no-sort">Beschreibung</th>
                                <th scope="col" class="no-sort">Kunde-ID</th>
                                <th scope="col" class="no-sort">Aktion</th>

                            </tr>
                        </thead>
                        <tbody>';
                            foreach ($data as $row){
                                // print_r($data1);
                                
                                $ausgabe .= '<tr>
                                                <td scope="col"><a href="../projektansichtdyn.php?name=' . $row['projektid'] . '" title="Details ansehen" class="text-success"><i class="fas fa-info-circle fa-lg"></i></a></td>
                                                <td scope="row">' . $row['projektid'] . '</td>
                                                <td class="text-center istatus" id="' . $row['status'] . '">'  . $row['status'] . '</td>                            
                                                
                                                <td>' . $row['titel'] . '</td>
                                                <td>' . date('d.m.y',strtotime($row['deadline'])) . '</td>
                                                <td>' . date('d.m.y H:i:s',strtotime($row['erstelldatum'])) . '</td>
                                                <td>' . date('d.m.y',strtotime($row['erstelldatum'])) . '</td>
                                                <td>Avatarbilder</td>
                                                <td>' . $row['typ'] . '</td>
                                                <td>' . $row['beschreibung'] . '</td>    
                                                <td>' . $row['kundeid'] . '</td>    
                                                <td scope="col">
                                                <a href="#" id="' . $row['projektid'] . '" title="Editieren" class="text-primary editBtn" data-toggle="modal" data-target="#editMod"><i class="fas fa-edit fa-lg"></i></a>
                                                
                                                <a href="#" id="' . $row['projektid'] . '" class="text-danger deleteBtn" title="Löschen"><i class="fas fa-trash-alt fa-lg"></i></a>
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

        $projektid = $_POST['edit_id'];

        $row = getProjekt($projektid);
        echo json_encode($row);   
    }

// Update 2 (Updaten)
    if(isset($_POST['action']) && $_POST['action'] == "update"){
        include_once 'verw_db.php';
        include '../php/dbhandler.php';
        
        $projektid = $_POST['projektid'];
        $titel = $_POST['titel'];
        $status = $_POST['status'];
        $deadline = $_POST['deadline'];
        $beschreibung = $_POST['beschreibung'];
        $typ = $_POST['typ'];
        $auftraggeberid = $_POST['auftraggeberid'];

        updateprojekt($titel, $status, $deadline, $beschreibung, $typ, $auftraggeberid, $projektid);
    }

// Delete
    if(isset($_POST['del_id'])){
        $projektid = $_POST['del_id'];
        // print_r($projektid);
        
        deleteprojekt($projektid);
    }
?>