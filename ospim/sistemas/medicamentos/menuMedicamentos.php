<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Sistemas Medicamentos :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'" />
  </span></p>
  <h3>Men&uacute; Medicamentos Alfa Beta</h3>
  <table width="400" border="3" style="text-align: center;vertical-align: middle;">
    <tr>
      <td width="200">
      	<p>Actualizacion Mensual</p>
        <p><a href="cargaArchivo.php?tipo=M"><img src="img/mensual.png" width="90" height="90" border="0" /></a></p>
      </td>
       <td width="200">
      	<p>Actualizacion Semanal</p>
        <p><a href="cargaArchivo.php?tipo=S"><img src="img/semanal.png" width="90" height="90" border="0" /></a></p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
