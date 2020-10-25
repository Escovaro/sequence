<!DOCTYPE html>
<html lang="DE">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" /><!-- Core Meta Data -->
        <title>SEQUENCE</title>
        <style>
        
        </style>
        
    </head>
<body>
    <?php
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . uniqid($_SERVER['PHP_SELF']); 
    
        $empfaenger = 'sahil.m94@htlwienwest.at';
        $betreff = 'SEQUENCE';
        $nachricht = $link;
        $header = 'From: sequence' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

        mail($empfaenger, $betreff, $nachricht, $header);
    	die ("");
    //echo $link; 
    ?>
</body>
