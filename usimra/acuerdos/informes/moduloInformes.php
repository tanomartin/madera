<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes de Acuerdos :.</title>
</head>

<body bgcolor="#B2A274">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAcuerdos.php'"/></p>
  	<h3>M&oacute;dulo De Informes Acuerdos</h3>
  	<table style="width: 800px; text-align: center" border="1">
	    <tr>
	    	<td width="200px">
	    		<p>CHEQUES <br/> EN CARTERA</p>
	        	<p><a href="chequesCartera.php"><img src="img/excellogo.png" width="90" height="90" border="0"/></a></p>
	     	</td>
	      	<td width="200px">
	      		<p>LIQUIDACIONES</p>
	      		<p><a href="liquidaComisiones.php"><img src="img/excellogo.png" width="90" height="90" border="0"/></a></p>
	      	</td>
	      	<td width="200px">
	      		<p>CUOTAS VENCIDAS</p>
	        	<p><a href="cuotasVencidas.php"><img src="img/excellogo.png" width="90" height="90" border="0"/></a></p>
	      	</td>
	      	<td width="200px">
	      		<p>CHEQUES <br/> RECHAZADOS</p>
        		<p><a href="chequesRechazados.php"><img src="img/excellogo.png" width="90" height="90" border="0"/></a></p>
	      	</td>
	    </tr>
    	<tr>
      		<td>
      			<p>DISTINTOS MONTOS</p>
      			<p><a href="distintoMonto.php"><img src="img/excellogo.png" width="90" height="90" border="0" /></a></p>
      		</td>
      		<td>
      			<p>DEUDA POR AÑO</p>
        		<p><a href="deudaAcuerdos.php"><img src="img/excellogo.png" width="90" height="90" border="0" /></a></p>
        	</td>
      		<td>
      			<p>VERIFICACION DE CUOTAS</p>
          		<p><a href="verificacionCuotas.php"><img src="img/excellogo.png" width="90" height="90" border="0" /></a></p>
        	</td>
        	<td>
        		<p>PERIODOS REPETIDOS</p>
        		<p><a href="repeticionPeriodos.php"><img src="img/excellogo.png" width="90" height="90" border="0" /></a></p>
        	</td>
    	</tr>
    	<tr>
      		<td>
      			<p>EXISTENCIA DE CAJA</p>
      			<p><a href="caja.php"><img src="img/excellogo.png" width="90" height="90" border="0"/></a></p>
      		</td>
      		<td>
      			<p>ACUERDOS POR DELEGACION</p>
      			<p><a href="acupordelega.php"><img src="img/consultas.png" width="90" height="90" border="0"/></a></p>
      		</td>
      		<td></td>
      		<td></td>
    	</tr>
  	</table>
</div>
</body>
</html>
