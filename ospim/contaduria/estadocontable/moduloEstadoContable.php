<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Tesoreria OSPIM :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuContaduria.php'" /> </p>
  <h3>Estados Contables</h3>
  <table width="400" border="1" style="text-align: center">
	<tr>
	 	<td width="200">
	  		<p>ESTADOS CONTABLES</p>
         	<p><a href="ListadoEstadoContable.php"><img src="img/estadocontable.png" width="90" height="90" border="0" /></a></p>
        </td>
	  	<td width="200">
	  		<p>DATELLES ESTADOS CONTABLES </p>
          	<p><a href="filtrosDetalleContable.php"><img src="img/detalleestadocontable.png" width="90" height="90" border="0" /></a></p>
        </td>
    </tr>
  </table>
</div>
</body>
</html>