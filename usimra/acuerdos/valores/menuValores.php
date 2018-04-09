<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo OSPIM :.</title>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAcuerdos.php'" /></p>
  <h3>Menú Valores </h3>
  <table width="400" border="3" style="text-align: center">
    <tr>
      <td width="200">
      	<p>LISTADO DE VALORES</p>
        <p><a href="valoresRealizados.php"><img src="img/informes.png" width="90" height="90" border="0"/></a></p>
      </td>
      <td width="200" valign="top">
      	<p>NUEVO VALOR</p>
        <p><a href="listadoValores.php"><img src="img/valores.png" width="90" height="90" border="0"/></a></p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>