<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes de Aportes :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>
<body bgcolor="#B2A274">
	<div align="center">
	  <p>
	    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAportes.php'" />
      </p>
	  <p><span class="Estilo1">Men&uacute; Informes </span></p>
	  <table style="width: 660; height: 168" border="1">
		<tr>
	    	<td width="220" height="164"><p align="center">Ingresos por Aportes</p>
        		<p align="center"><a class="enlace" href="ingresosAportes.php"><img src="img/excellogo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	      		<p align="center">&nbsp;</p></td>
        	<td width="220" height="164"><p align="center">Descargas Aplicativo D.D.J.J. </p>
         		<p align="center"><a class="enlace" href="listadoDescarga.php"><img src="img/consultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	            <p align="center">&nbsp;</p></td>
   			<td width="220" height="164"><p align="center">Ingresos por Cuota Excepcional</p>
		        <p align="center"><a class="enlace" href="ingresosExtraordinaria.php"><img src="img/excellogo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
		      <p>&nbsp;</p></td>
		</tr>
      </table>
</div>
</body>
</html>
