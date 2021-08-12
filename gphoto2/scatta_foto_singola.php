<?php
$a = shell_exec( "gphoto2 --set-config capturetarget=1 --trigger-capture -q --stdout &" );
?>

<script>
    parent.aggiornaDebug("-- fine scatto");
    parent.canShoot = true;
</script>
