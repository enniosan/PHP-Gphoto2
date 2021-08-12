<?php
$a = shell_exec( "gphoto2 --set-config capturetarget=1 --trigger-capture --stdout -q" );

$next_frame = $_REQUEST['frame'] + 1;

if( $_REQUEST['sleep'])
    sleep( (int) $_REQUEST['sleep']);
?>

<script>
    parent.aggiornaDebug("-- fine scatto");
    parent.canShoot = true;
    parent.doIntervallometro(<?php echo $next_frame; ?>);
</script>
