<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo USIMRA :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <input type="button" name="volver" value="Volver" onclick="location.href = '../menuAcuerdos.php'" /> 	
  <p><span class="Estilo2">Men&uacute; Boletas </span></p>
  <table width="600" border="3">
    <tr>
      <td width="200">
		  <p align="center">Anulacion de Boletas </p>
		  <p align="center"><a href="cargaAnulacion.php" ><img src="img/anulacion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
		  <p align="center">&nbsp;</p>
	  </td>
      <td width="200">
		  <p align="center">Impresi&oacute;n de Boletas </p>
		  <p align="center"><a href="moduloImpresion.php"><img src="img/impresora.png" width="90" height="90" border="0" alt="enviar"/></a></p>
		  <p align="center">&nbsp;</p>
	  </td>
      <td width="200">
		  <p align="center">Buscador de Boletas </p>
		  <p align="center"><a href="buscadorBoleta.php"><img src="img/lupa.png" width="90" height="90" border="0" /> </a></p>
		  <p align="center">&nbsp;</p>
	  </td>
    </tr>
  </table>
</div>
</body>
</html>
