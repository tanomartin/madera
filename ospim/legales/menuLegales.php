<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Legales :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <h3>Menú Legales </h3>
  <table width="600" border="2" style="text-align: center">
    <tr>
	    <td width="200">
	     	<p>JUICIOS</p>
	        <p><a href="juicios/moduloJuicios.php"><img src="img/juicios.png" width="90" height="90" border="0" /></a></p>
		</td>
		<td width="200">
		  	<p>CONFIGURACIÓN</p>
	        <p><a href="configuracion/menuConfiguracion.php"><img src="img/configuracion.png" width="90" height="90" border="0"/></a></p> 
		</td>
	    <td width="200">
	      	<p>INFORMES</p>
	        <p><a class="enlace" href="informes/moduloInformes.php"><img src="img/informes.png" width="90" height="90" border="0"/></a></p>
		</td> 
    </tr>
  </table>
</div>
</body>
</html>
