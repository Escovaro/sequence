<?php
    include '../php/dbhandler.php';
    include_once 'verw_db.php';

// Ausgabe Kunden
    if(isset($_POST['action']) && $_POST['action'] == "view"){
        $ausgabe = '';
        $data = read_kunden();
        // print_r($data);

        if (AnzahlEintraege()>0){
            $ausgabe .= '<table class="table table-striped w-auto">
                        <thead>
                            <tr>
                                <th class="verw_th" scope="col">#</th>
                                <th scope="col">Firmenname</th>
                                <th scope="col">Kontakt</th>
                                <th scope="col">Mail</th>                                
                                <th scope="col">Telefon</th>
                                <th scope="col">Straße</th>
                                <th scope="col">PLZ</th>   
                                <th scope="col">Ort</th>
                                <th scope="col">Land</th>
                                <th scope="col">Branche</th>
                                <th scope="col" class="no-sort">Beschreibung</th>
                                <th scope="col">Kunde seit</th>
                                <th scope="col" class="no-sort">Aktion</th>
                            </tr>
                        </thead>
                        <tbody>';
                            foreach ($data as $row){
                                // print_r($data1);
                                
                                $ausgabe .= '<tr>
                                                <td>' . $row['kundenID'] . '</td>
                                                <td>' . $row['firmenname'] . '</td>                            
                                                <td>' . $row['kontaktvorname'] . ' ' . $row['kontaktnachname'] . '</td>
                                                <td>' . $row['email'] . '</td>
                                                <td>' . $row['telefon'] . '</td>
                                                <td>' . $row['strasse'] . '</td>
                                                <td>' . $row['ort'] . '</td>
                                                <td>' . $row['plz'] . '</td>
                                                <td>' . $row['land'] . '</td>
                                                <td>' . $row['branche'] . '</td>
                                                <td>' . $row['beschreibung'] . '</td>
                                                <td>' . $row['kundeseit'] . '</td>
                                                  
                                                <td scope="col">
                                                <a href="#" id="' . $row['kundenID'] . '" title="Editieren" class="text-primary editBtn" data-toggle="modal" data-target="#editMod"><i class="fas fa-edit fa-lg"></i></a>
                                                
                                                <a href="#" id="' . $row['kundenID'] . '" class="text-danger deleteBtn" title="Löschen"><i class="fas fa-trash-alt fa-lg"></i></a>
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

// Insert
    if(isset($_POST['action']) && $_POST['action'] == "insert"){
        include_once 'verw_db.php';
        include '../php/dbhandler.php';

        $firmenname = $_POST['firmenname'];
        $kontaktvorname = $_POST['kontaktvorname'];
        $kontaktnachname = $_POST['kontaktnachname'];
        $email = $_POST['email'];
        $telefon = $_POST['telefon'];
        $strasse = $_POST['strasse'];
        $ort = $_POST['ort'];
        $plz = $_POST['plz'];
        $land = $_POST['land'];
        $branche = $_POST['branche'];
        $beschreibung = $_POST['beschreibung'];

        insertkunde($firmenname, $kontaktvorname, $kontaktnachname, $email, $telefon, $strasse, $ort, $plz, $land, $branche, $beschreibung);
    }

// Update 1 (Holen der Daten)
    if(isset($_POST['edit_id'])){
        include_once 'verw_db.php';
        include '../php/dbhandler.php';

        $kundenid = $_POST['edit_id'];

        $row = getKunde($kundenid);
        echo json_encode($row);   
    }

// Update 2 (Updaten)
    if(isset($_POST['action']) && $_POST['action'] == "update"){
        include_once 'verw_db.php';
        include '../php/dbhandler.php';
        
        $kundenid = $_POST['kundenid'];
        $firmenname = $_POST['firmenname'];
        $kontaktvorname = $_POST['kontaktvorname'];
        $kontaktnachname = $_POST['kontaktnachname'];
        $email = $_POST['email'];
        $telefon = $_POST['telefon'];
        $strasse = $_POST['strasse'];
        $ort = $_POST['ort'];
        $plz = $_POST['plz'];
        $land = $_POST['land'];
        $branche = $_POST['branche'];
        $beschreibung = $_POST['beschreibung'];

        updatekunde($firmenname, $kontaktvorname, $kontaktnachname, $email, $telefon, $strasse, $ort, $plz, $land, $branche, $beschreibung, $kundenid);
    }

// Delete
    if(isset($_POST['del_id'])){
        $kundenid = $_POST['del_id'];
        print_r($kundenid);
        
        deletekunde($kundenid);
    }



?>