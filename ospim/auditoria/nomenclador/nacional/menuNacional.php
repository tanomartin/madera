<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Menu Nomenclador Nacional :.</title>

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

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="button" name="volver" value="Volver" onclick="location.href = '../menuNomenclador.php'" />
  </p>
  <p><span class="Estilo2">Men&uacute; Nomenclador Nacional </span></p>
  <table width="400" border="3">
    <tr>
	  <td width="200"><p align="center">Listador </p>
        <p align="center"><a class="enlace" href="listadorNacional.php"><img src="img/listador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
	   <td width="200"><p align="center">Cargar Propiedades </p>
         <p align="center"><a class="enlace" href="cargarPropiedadesNacional.php"><img src="img/propiedades.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
