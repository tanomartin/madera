<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$sqlPedidos = "SELECT origen, estado FROM pedidos WHERE estado not in (4,5)";
$resPedidos = mysql_query($sqlPedidos,$db);
$numPedidos = mysql_num_rows($resPedidos);

$peOSPPendientes = 0;
$peOSPEstudio = 0;
$peOSPEjecucion = 0;

$peUSPPendientes = 0;
$peUSPEstudio = 0;
$peUSPEjecucion = 0;

if ($numPedidos > 0) {
	while ($rowPedidos = mysql_fetch_assoc($resPedidos)) {
		if ($rowPedidos['origen'] == 'O') {
			if ($rowPedidos['estado'] == 1) {
				$peOSPPendientes++;
			}
			if ($rowPedidos['estado'] == 2) {
				$peOSPEstudio++;
			}
			if ($rowPedidos['estado'] == 3) {
				$peOSPEjecucion++;
			}
		}
		if ($rowPedidos['origen'] == 'U') {
			if ($rowPedidos['estado'] == 1) {
				$peUSPPendientes++;
			}
			if ($rowPedidos['estado'] == 2) {
				$peUSPEstudio++;
			}
			if ($rowPedidos['estado'] == 3) {
				$peUSPEjecucion++;
			}
		}
	}
} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Sistemas Correcciones :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'" />
  </span></p>
  <h3>Menú Pedidos</h3>
  <table width="600" border="3" style="text-align: center;vertical-align: middle;">
    <tr>
      <td width="200">
      	<p>U.S.I.M.R.A.</p>
        <p><a href="listadoPedidos.php?origen=U"><img src="img/usimra.png" width="90" height="90" border="0" /></a></p>
        <p><b><?php echo $peUSPPendientes ?></b> (P) - <b><?php echo $peUSPEstudio ?></b> (Es) - <b><?php echo $peUSPEjecucion ?></b> (Ej)</p>
      </td>
       <td width="200">
      	<p>O.S.P.I.M.</p>
        <p><a href="listadoPedidos.php?origen=O"><img src="img/ospim.png" width="90" height="90" border="0" /></a></p>
        <p> <b><?php echo $peOSPPendientes ?></b> (P) - <b><?php echo $peOSPEstudio ?></b> (Es) - <b><?php echo $peOSPEjecucion ?></b> (Ej)</p>
      </td>
      <td width="200">
      	<p>BUSCADOR</p>
        <p><a href="#"><img src="img/buscar.png" width="90" height="90" border="0" /></a></p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>

