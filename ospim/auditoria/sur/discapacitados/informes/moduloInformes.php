<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes SUR OSPIM :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
function informes(dire) {
	$.blockUI({ message: "<h1>Generando Informe. Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" } );
	location.href = dire;
}
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	  <input type="button" name="volver" value="Volver" onclick="location.href = '../moduloDisca.php'" /> 
	  <h2>Men&uacute; Consultas e Informes</h2>
	  <table width="600" border="3">
	    <tr>
	      <td width="200"><p align="center">CERTIFICADOS POR FECHA VTO. </p>
	          <p align="center"><a class="enlace" href="certificadosPorVto.php"><img src="img/consultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	        <p align="center">&nbsp;</p></td>
			 <td width="200"><p align="center">CANTIDAD DISCAPACITADOS POR DELEGACION</p>
	          <p align="center"><a class="enlace" href="#"><img onclick="informes('cantidadDicapacitadosPorDelegacion.php')" src="img/listado.png" width="90" height="90" border="0"/></a></p>
	        <p align="center">&nbsp;</p></td>
			<td width="200"><p align="center">DISCAPACITADOS POR DELEGACION</p>
	          <p align="center"><a class="enlace" href="discapacitadosPorDelegacion.php"><img src="img/excellogo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	        <p align="center">&nbsp;</p></td>
	    </tr>
	    <tr>
	      <td><p align="center">&nbsp;</p>
	        
	      <p>&nbsp;</p></td>
	      <td><p align="center">EXPEDIENTES INCOMPLETOS</p>
	      <p align="center"><a class="enlace" href="expedientesIncompletos.php"><img src="img/consultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	      <p align="center">&nbsp;</p></td>
	      <td>&nbsp;</td>
	    </tr>
	  </table>
</div>
</body>
</html>
