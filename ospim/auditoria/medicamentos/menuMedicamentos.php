<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Medicamentos :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = '../menuAuditoria.php'" />
  </span></p>
  <h3>Men&uacute; Medicamentos Alfa Beta</h3>
  <table width="400" border="3" style="text-align: center;vertical-align: middle;">
    <tr>
      <td width="200">
      	<p>Buscador</p>
        <p><a href="buscadorMedicamentos.php"><img src="img/buscar.png" width="90" height="90" border="0" /></a></p>
      </td>
       <td width="200">
      	<p>Listado Actualizaciones</p>
        <p><a href="controlActualizacion.php"><img src="img/listado.png" width="90" height="90" border="0" /></a></p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
