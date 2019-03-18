<?php
$ruta = $_GET['file'];
$archivo = substr($ruta,-22);
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename='.$archivo);
header('Content-Transfer-Encoding: binary');
header('Content-Length: '.filesize($ruta));
readfile($ruta);
?>