<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");  ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalisador :.</title>
</head>

<body bgcolor="#B2A274">
	<div align="center">
	  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuFiscalizaciones.php'" /></p>
	  <h3>Menú Consultas </h3>
	  <table width="600" border="1" style="text-align: center">
        <tr>
          <td width="200">
          	<p>APORTES</p>
            <p><a href="aportes/aportesCuit.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
          </td>
          <td width="200">
          	<p>DDJJ</p>
            <p><a href="ddjj/menuddjj.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
          </td>
          <td width="200">
          	<p>CUOTAS EXCEPCIONALES</p>
            <p><a href="extraordinarias/extraordinariasCuit.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
          </td>
        </tr>
        <tr>
          <td>
           	<p>REQUERIMIENTOS </p>
            <p><a href="requerimientos/filtrosBusqueda.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
          </td>
          <td>
          	<p>LIQUIDACIONES</p>
            <p><a href="liquidaciones/filtrosBusqueda.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
          </td>
          <td>
          	<p>CANT. DDJJ OSPIM DESDE USIMRA</p>
            <p><a href="ddjj/ospimdesdeusimra.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
          </td>
        </tr>
      </table>
	  </div>
</body>
</html>
