<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Informes de Aportes :.</title>
</head>
<body bgcolor="#B2A274">
	<div align="center">
	  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAportes.php'" /></p>
	  <h3>Men� Informes </h3>
	  <table width="600" border="1" style="text-align: center">
		<tr>
	    	<td width="200">
	    		<p>INGRESO POR APORTE</p>
        		<p><a href="ingresosAportes.php"><img src="img/excellogo.png" width="90" height="90" border="0" /></a></p>
	      	</td>
        	<td width="200">
        		<p>DESCARGA <br/> APLICATIVO DDJJ</p>
         		<p><a href="listadoDescarga.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
	        </td>
   			<td width="200">
   				<p>INGRESO POR CUOTA EXCEPCIONAL</p>
		        <p><a href="ingresosExtraordinaria.php"><img src="img/excellogo.png" width="90" height="90" border="0" /></a></p>
		    </td>
		</tr>
		<tr>
			<td></td>
			<td>
				<p>BUSQUEDA DDJJ <br/> POR MONTO</p>
         		<p><a href="buscarMonto.php"><img src="img/buscar.png" width="90" height="90" border="0" /></a></p>
			</td>
			<td></td>
		</tr>
      </table>
</div>
</body>
</html>
