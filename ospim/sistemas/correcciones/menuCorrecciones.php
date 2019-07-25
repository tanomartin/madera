<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

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
?>

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
  <h3>Menú Correcciones</h3>
  <table width="600" border="3" style="text-align: center;vertical-align: middle;">
    <tr>
      <td width="200">
      	<p>U.S.I.M.R.A.</p>
        <p><a href="listadoCorrecciones.php?origen=U"><img src="img/usimra.png" width="90" height="90" border="0" /></a></p>
        <p><b><?php echo $numCorrecUSIMRAPendiente ?></b> (P) - <b><?php echo $numCorrecUSIMRAEjecucion ?></b> (E)</p>
      </td>
       <td width="200">
      	<p>O.S.P.I.M.</p>
        <p><a href="listadoCorrecciones.php?origen=O"><img src="img/ospim.png" width="90" height="90" border="0" /></a></p>
        <p><b><?php echo $numCorrecOSPIMPendiente ?></b> (P) - <b><?php echo $numCorrecOSPIMEjecucion ?></b> (E)</p>
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

