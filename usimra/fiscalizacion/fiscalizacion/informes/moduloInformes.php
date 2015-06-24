<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");  ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalisador :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>

<body bgcolor="#B2A274">
	<div align="center">
	  <p>
	    <input type="button" name="volver" value="Volver" onclick="location.href = '../menuFiscalizaciones.php'" />
      </p>
	  <p><span class="Estilo1">Men&uacute; Consultas </span></p>
	  <table width="626" border="3">
        <tr>
          <td width="200"><p align="center">Aportes</p>
              <p align="center"><a class="enlace" href="aportes/aportesCuit.php"><img src="img/consultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
            <p align="center">&nbsp;</p></td>
          <td width="200"><p align="center">DDJJ</p>
              <p align="center"><a class="enlace" href="ddjj/ddjjCuit.php"><img src="img/consultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
            <p align="center">&nbsp;</p></td>
          <td width="200"><p align="center">Requerimientos </p>
              <p align="center"><a class="enlace" href="requerimientos/filtrosBusqueda.php"><img src="img/consultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
            <p align="center">&nbsp;</p></td>
        </tr>
        <tr>
          <td><p align="center">&nbsp;</p>
              <p align="center">&nbsp;</p></td>
          <td><p align="center">Liquidaciones</p>
              <p align="center"><a class="enlace" href="liquidaciones/liquiListado.php"><img src="img/consultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
            <p>&nbsp;</p></td>
          <td>&nbsp;</td>
        </tr>
      </table>
	  </div>
</body>
</html>
