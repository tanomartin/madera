<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$sqlPedidosOSPIMPendientes = "SELECT * FROM pedidos
					 			WHERE origen = 'O' and usuariosistemas is null and motivorechazo is null";
$resPedidosOSPIMPendientes = mysql_query($sqlPedidosOSPIMPendientes,$db);
$numPedidosOSPIMPendientes = mysql_num_rows($resPedidosOSPIMPendientes);

$sqlPedidosUSIMRAPendientes = "SELECT * FROM pedidos
					 			WHERE origen = 'U' and usuariosistemas is null and motivorechazo is null";
$resPedidosUSIMRAPendientes = mysql_query($sqlPedidosUSIMRAPendientes,$db);
$numPedidosUSIMRAPendientes = mysql_num_rows($resPedidosUSIMRAPendientes);

$sqlPedidosOSPIMEjecucion = "SELECT * FROM pedidos
					 			WHERE origen = 'O' and usuariosistemas is not null and motivorechazo is null";
$resPedidosOSPIMEjecucion = mysql_query($sqlPedidosOSPIMEjecucion,$db);
$numPedidosOSPIMEjecucion = mysql_num_rows($resPedidosOSPIMEjecucion);

$sqlPedidosUSIMRAEjecucion = "SELECT * FROM pedidos
					 			WHERE origen = 'U' and usuariosistemas is not null and motivorechazo is null";
$resPedidosUSIMRAEjecucion = mysql_query($sqlPedidosUSIMRAEjecucion,$db);
$numPedidosUSIMRAEjecucion = mysql_num_rows($resPedidosUSIMRAEjecucion); ?>

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
        <p><b><?php echo $numPedidosUSIMRAPendientes ?></b> (P) - <b><?php echo $numPedidosUSIMRAEjecucion ?></b> (E)</p>
      </td>
       <td width="200">
      	<p>O.S.P.I.M.</p>
        <p><a href="listadoPedidos.php?origen=O"><img src="img/ospim.png" width="90" height="90" border="0" /></a></p>
        <p><b><?php echo $numPedidosOSPIMPendientes ?></b> (P) - <b><?php echo $numPedidosOSPIMEjecucion ?></b> (E)</p>
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

