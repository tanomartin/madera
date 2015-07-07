<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo SUR OSPIM :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
.Estilo6 {font-size: 24}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="button" name="volver" value="Volver" onclick="location.href = '../menuAuditoria.php'" />
  </p>
  <h2><span class="Estilo6">Men&uacute; S.U.R. </span></h2>
  <table width="600" border="3">
    <tr>
	  <td width="200"><p align="center">Discapacitados </p>
			<p align="center"><a class="enlace" href="abm/moduloABMDisca.php"><img src="img/discapacitados.png" width="90" height="90" border="0" alt="enviar"/></a></p>
			<p align="center">&nbsp;</p></td>
		<td width="200"><p align="center">H.I.V</p>
			<p align="center"><a class="enlace" href="#"><img src="img/hiv.png" width="90" height="90" border="0" alt="enviar"/></a></p>
			<p align="center">&nbsp;</p></td>
		<td width="200"><p align="center">Drogadependencia</p>
			<p align="center"><a class="enlace" href="#"><img src="img/drogadependencia.png" width="90" height="90" border="0" alt="enviar"/></a></p>
			<p align="center">&nbsp;</p></td>
    </tr>  
	<tr>  
	<td></td>
	  <td width="200"><p align="center">Informes</p>
          <p align="center"><a class="enlace" href="informes/moduloInformes.php"><img src="img/informesconsultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
          <p align="center">&nbsp;</p></td>
	<td></td>
	</tr>
  </table>
</div>
</body>
</html>
