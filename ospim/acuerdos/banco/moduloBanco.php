<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco OSPIM :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAcuerdos.php'" /> </p>
 	<h3>Módulo De Procesamiento Bancario</h3>
  	<table style="width: 600; text-align: center" border="1">
    	<tr>
	      	<td width="200">
	      		<p>ARCHIVOS TRANSFERIDOS</p>
	        	<p><a href="procesamientoArchivos.php"><img src="img/archivosBanco.png" width="90" height="90" border="0" /></a></p>
	        </td>
	      	<td width="200">
	      		<p>IMPUTACION</p>
	          	<p><a href="procesamientoRegistros.php"><img src="img/imputacion.png" width="90" height="90" border="0" /></a></p>
	        </td>
			<td width="200">
				<p>CONSULTAS</p>
	          	<p><a href="informes/moduloInformes.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
	        </td>
    	</tr>
  	</table>
</div>
</body>
</html>