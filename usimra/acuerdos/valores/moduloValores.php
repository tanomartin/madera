<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Valores al Cobro :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<body bgcolor="#B2A274">
<p align="center" class="Estilo2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="../menuAcuerdos.php">VOLVER</a></strong></font></p>
<p align="center" class="Estilo2">M&oacute;dulo Valores al Cobro</p>
<form id="ordena" name="ordena" method="post" action="listadoValores.php">
  <label><br />
  <div align="center">
    <p><strong>Ordenamiento</strong></p>
    <table width="200" border="2">
    <tr>
      <td><input name="orden"  id="orden" type="radio" value="cuit" checked/></td>
      <td>CUIT (Acuerdo - Cuota) </td>
    </tr>
    <tr>
      <td><input name="orden"  id="orden" type="radio" value="chequefecha" /></td>
      <td>Fecha de Valor al Cobro </td>
    </tr>
  </table>
    <br />
    <input type="submit" name="Submit" value="Procesar" />
    <br />
  </div>
</form>
</body>
</html>
