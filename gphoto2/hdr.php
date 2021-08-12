<?php

#   prendo l'attuale impostazione


if( $_REQUEST['action'] == "init" ){

    $a = shell_exec( "gphoto2 --get-config /main/capturesettings/shutterspeed" );

    $a = explode("\n", $a );

    $current = false;

    $scelte = [];
    $sceltaCorrente = false;

    foreach( $a as $c ){
        if( stripos( $c, "current") !== false ){
            $current = trim( end( explode(":", $c) ) );
        }

        if( stripos( $c, "choice") !== false ){
            $scelta = explode(" ", $c);

            $scelte[ $scelta[1] ] = $scelta[2];

            
            if( $scelta[2] === $current ){
                $sceltaCorrente = $scelta[1];

            }
        }
    }

    $min = $sceltaCorrente -6;
    $max = $sceltaCorrente +6;


    $seq = [];

    for( $i = $min ; $i <= $max ; $i+=2){

        $seq[] = $scelte[$i];

    }

    echo "
    <script>
        parent.hdrSeq           = " . json_encode($seq).";
        parent.hdrInitStatus    = '{$scelte[$sceltaCorrente]}';
        parent.aggiornaDebug(\"HDR OK\");  
        parent.doHdr(0);
    </script>";
    die;
}

if( $_REQUEST['action'] == "shoot"){

    $hdrSeq = json_decode( $_REQUEST['seq'], 1 );
    $n      = $_REQUEST['n'];

    $ssp = $hdrSeq[ $n ];

    $cmd = "gphoto2 --set-config capturetarget=1 --set-config shutterspeed=".$ssp." --capture-image-and-download -q";
    $a = shell_exec( $cmd  );
    
    $n ++;


    ?>

    
    <script>
        parent.aggiornaDebug("foto <?php echo $n;?> ok");  
        parent.doHdr(<?php echo $n;?>);
    </script>

    <?php
    die;

}


if( $_REQUEST['action'] == "reset"){

    $v      = $_REQUEST['v'];
    $ssp = $hdrSeq[ $n ];
    $cmd = "gphoto2 --set-config shutterspeed=".$v;
    shell_exec( $cmd  );
    
    echo "
    <script>
        parent.hdrSeq = [];
        parent.aggiornaDebug(\"Valore di velocità dell'otturatore riportato al valore iniziale ( $v )\");  
    </script>";
    die;
}

die;
/*
$out = "";
$out .= "Elaborazione HDR: 7 foto da {$scelte[$min]} a {$scelte[$max]}<br>";
$out .= "Posizione di partenza: {$scelte[$sceltaCorrente]}";


for( $i = $min ; $i <= $max ; $i+=2){

    $cmd = "gphoto2 --set-config capturetarget=1 --set-config shutterspeed=".$scelte[$i]." --trigger-capture -q";
    $a = shell_exec( $cmd  );
}

$cmd = "gphoto2 --set-config shutterspeed=".$scelte[$sceltaCorrente];
shell_exec( $cmd  );

*/