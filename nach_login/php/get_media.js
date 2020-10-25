//get_media.js
//Erstellt die Ausgabe für Media-Tabellen

$(document).ready(function(){
    $("#refreshBtn").click(function() {
        $.ajax({
            type: "GET", //AUF POST ändern?
            url: "php/get_media.php", //Dort findet DB-Abfrage statt
            data: {},
            contentType: "application/json; charset=utf-8",
            dataType: "json",                    
            cache: false,                       
            success: function(response) {                        
                var trHTML = ''; //Ausgabevariable, welche im Table auf z.B. st_view.php ausgegeben wird. 
                    $.each(response, function (i, item) {
                        //Linkerstellung, date fehlen noch:
                        // <td><a href="<?php echo 'subtaskansichtdyn.php?name=' . $linkid; ?>">#</a></td>
                        trHTML += '<tr><td><a href="st_view_media.php?name=' +  item.mediaid + '">#</a></td><td>' + item.mediaid + '</td><td>' + item.erstellerid + '</td><td>' + item.mediaid +
                        '</td><td>' + item.updatebeschreibung + '</td><td>' + item.uploaddatum + '</td><td>' + item.dateiname + '</td></tr>';
                    });

                    $("#mediatabelle tbody").html(trHTML);
                    //$('#mediatabelle tbody').empty();
                    //$('#mediatabelle td').append('');
                    //$('#mediatabelle').append(trHTML);
                    
                    //$('#mediatabelle tbody').append('');
                    //$("#mediatabelle").html('');
                    
            },
            error: function (e) {
                console.log(response);
            }
        });  
    });        
});



                            