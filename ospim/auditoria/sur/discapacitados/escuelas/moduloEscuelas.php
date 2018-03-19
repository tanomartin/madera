<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Escuelas :.</title>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloDisca.php'"/></p>
  <h3>Men&uacute; Escuelas</h3>
  <table width="400" border="3" style="text-align: center">
    <tr>
	  	<td width="200">
	  		<p>A.B.M</p>
          	<p><a class="enlace" href="abmEscuela.php"><img src="img/escuelas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        </td>
      	<td width="200">
      		<p>LISTADO ESCUELAS </p>
          	<p><a class="enlace" href="listadoEscuelas.php"><img src="img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        </td>
    </tr>
  </table>
</div>
</body>
</html>

