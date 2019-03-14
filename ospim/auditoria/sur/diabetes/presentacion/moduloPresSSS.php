<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlPresSSSFinalizadas = "SELECT * FROM diabetespresentacion 
							WHERE fechacancelacion is not null or fechadevolucion is not null order by id DESC";
$resPresSSSFinalizadas = mysql_query($sqlPresSSSFinalizadas,$db);
$canPresSSSFinalizadas = mysql_num_rows($resPresSSSFinalizadas);

$sqlPresSSSActiva = "SELECT * FROM diabetespresentacion
							WHERE fechacancelacion is null or fechadevolucion is null order by id DESC";
$resPresSSSActiva = mysql_query($sqlPresSSSActiva,$db);
$canPresSSSActiva = mysql_num_rows($resPresSSSActiva);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Diabetes Presentacion S.S.S. :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloDiabetes.php'" /></p>
  	<h2>Módulo Presentacion Diabetes S.S.S.</h2>
  	<h3>Presentacion Activa</h3>
  	<?php if ($canPresSSSActiva != 0) { ?>
  			<table>
  				<thead>
  					<tr>
	  					<th>ID</th>
	  					<th>Fecha Desde - Hasta</th>
	  					<th># Beneficiarios</th>
	  					<th>Estado</th>
	  					<th></th>
  					</tr>
  				</thead>
  			</table>
  	<?php } else { ?>
  			<h3 style="color: blue">No Existe Presentacion Activas</h3>
  			<button onclick="location.href = 'nuevaPresentacion.php'">Nueva Presentacion</button>
  	<?php } ?>
  	<h3>Presentaciones Finalizadas</h3>
  	<?php if ($canPresSSSFinalizadas != 0) { ?>
  				<table>
  				<thead>
  					<tr>
	  					<th>ID</th>
	  					<th>Fecha Desde - Hasta</th>
	  					<th># Beneficiarios</th>
	  					<th>Estado</th>
	  					<th></th>
  					</tr>
  				</thead>
  			</table>
  	<?php } else { ?>
  			<h3 style="color: blue">No Existen Presentaciones Finalizadas</h3>
  	<?php } ?>
</div>
</body>
</html>