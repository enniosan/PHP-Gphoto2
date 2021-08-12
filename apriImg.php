<?php

$img = str_replace( "thumb_", "" , $_REQUEST['img'] );

#   scarico l'immagine

$cartelle = [];

$a = shell_exec("gphoto2 -L");
$a = explode("\n", $a );


$ocartella = "";

foreach( $a as $k => $riga){
    
    $riga = trim( $riga );
    
    if( strpos( $riga, "no file" ) !== false )
    continue;
    else{
        $ocartella = explode("'",$riga)[1];
    }
    
    $a = shell_exec("gphoto2 -L");

    
    
    
    
}





print_r( $img );

