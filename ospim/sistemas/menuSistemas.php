<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if ($_SESSION['usuario'] != 'sistemas') {
	$redire = "Location: http://".$_SERVER['SERVER_NAME']."/madera/comun/sistemas/menuSistemas.php?origen=ospim";
	header($redire);
} 

//******************************** CORRECCIONES ******************************************* // 
$sqlCorrecUSIMRAPendiente = "SELECT * FROM correcciones c, usuarios u
								WHERE c.origen = 'U' and c.corrector is null and c.usuarioregistro = u.usuariosistema";
$resCorrecUSIMRAPendiente = mysql_query($sqlCorrecUSIMRAPendiente,$db);
$numCorrecUSIMRAPendiente = mysql_num_rows($resCorrecUSIMRAPendiente);

$sqlCorrecUSIMRAEjecucion = "SELECT * FROM correcciones c, usuarios u
								WHERE c.origen = 'U' and c.fechacorrector is not null and 
									c.fechafinalizacion is null and c.fecharechazo is null and
									u.usuariosistema = c.usuarioregistro";
$resCorrecUSIMRAEjecucion = mysql_query($sqlCorrecUSIMRAEjecucion,$db);
$numCorrecUSIMRAEjecucion = mysql_num_rows($resCorrecUSIMRAEjecucion);

$sqlCorrecOSPIMPendiente = "SELECT * FROM correcciones c, usuarios u
							WHERE c.origen = 'O' and c.corrector is null and u.usuariosistema = c.usuarioregistro";
$resCorrecOSPIMPendiente = mysql_query($sqlCorrecOSPIMPendiente,$db);
$numCorrecOSPIMPendiente = mysql_num_rows($resCorrecOSPIMPendiente);

$sqlCorrecUSIMRAEjecucion = "SELECT * FROM correcciones c, usuarios u
							 WHERE c.origen = 'O' and c.fechacorrector is not null and 
								c.fechafinalizacion is null and c.fecharechazo is null and
								u.usuariosistema = c.usuarioregistro";
$resCorrecOSPIMEjecucion = mysql_query($sqlCorrecUSIMRAEjecucion,$db);
$numCorrecOSPIMEjecucion = mysql_num_rows($resCorrecOSPIMEjecucion);
// ****************************************************************************************** //

//********************************** PEDIDOS ************************************************ //
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
}

// ****************************************************************************************** //
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Sistemas :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <h2>Men&uacute; Sistemas </h2>
  
  <table width="900" border="3" style="text-align: center;vertical-align: middle;">
    <tr>
      <td width="200">
      	<p>FISCALIZACION</p>
        <p><a class="enlace" href="fiscalizacion/menuFiscalizacion.php"><img src="img/fiscalizacion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
      	<p>APLICATIVO DDJJ </p>
      	<p><a class="enlace" href="aplicativoddjj/menuAplicativoddjj.php"><img src="img/aplicativoddjj.png" width="97" height="85" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
      	<p>PADRONES</p>
        <p><a class="enlace" href="padrones/menuPadrones.php"><img src="img/padrones.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
      	<p>STOCK</p>
      	<p><a class="enlace" href="stock/menuStock.php"><img src="img/stock.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
    </tr>
    <tr>
      <td>
      	<p>TRATAMIENTO A.F.I.P.</p>
        <p><a class="enlace" href="afip/menuAfip.php"><img src="img/afip.png" width="110" height="90" border="0" alt="enviar"/></a></p>
      </td>
       <td>
      	<p>DESEMPLEO</p>
      	<p><a class="enlace" href="desempleo/menuDesempleo.php"><img src="img/anses.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>MEDICAMENTOS</p>
      	<p><a class="enlace" href="medicamentos/menuMedicamentos.php"><img src="img/medicamentos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>INTRANETS</p>
        <p><a class="enlace" href="intranets/menuIntranet.php"><img src="img/intranets.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
    </tr>
    <tr>
      <td>
      	<p>USUARIOS</p>
        <p><a href="usuarios/menuUsuarios.php"><img src="img/users.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>ESTADOS CONTABLES</p>
        <p><a href="estadocontable/estadoContable.php"><img src="img/estadocontable.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>CORRECCIONES</p>
        <p><a href="correcciones/menuCorrecciones.php"><img src="img/correcciones.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      	<p>
      		<font color="brown">USIMRA:</font></br>  <b><?php echo $numCorrecUSIMRAPendiente ?></b> (P) - <b><?php echo $numCorrecUSIMRAEjecucion ?></b> (E) <br></br>
      		<font color="blue">OSPIM:</font></br> <b><?php echo $numCorrecOSPIMPendiente ?></b> (P) - <b><?php echo $numCorrecOSPIMEjecucion ?></b> (E)
      	</p>
      </td>
      <td>
      	<p>PEDIDOS</p>
        <p><a href="pedidos/menuPedidos.php"><img src="img/pedidos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p>
      		<font color="brown">USIMRA</font></br> <b><?php echo $peUSPPendientes ?></b> (P) - <b><?php echo $peUSPEstudio ?></b> (Es) - <b><?php echo $peUSPEjecucion ?></b> (Ej) <br></br>
      		<font color="blue">OSPIM:</font></br> <b><?php echo $peOSPPendientes ?></b> (P) - <b><?php echo $peOSPEstudio ?></b> (Es) - <b><?php echo $peOSPEjecucion ?></b> (Ej)
      	</p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
