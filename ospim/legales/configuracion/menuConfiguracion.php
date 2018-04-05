<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Legales Configuracion :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onclick="location.href = '../menuLegales.php'" /></p>
  <h3>Men&uacute; Configuracion Legales </h3>
  <table width="600" border="1" style="text-align: center">
  	<tr>
     	<td width="200">
     		<p>JUZGADOS</p>
        	<p><a href="juzgados/juzgados.php"><img src="img/juzgado.png" width="90" height="90" border="0"/></a></p>
       	</td>
	  	<td width="200">
	  		<p>SECRETARIAS</p>
          	<p><a href="secretarias/secretarias.php"><img src="img/secretaria.png" width="90" height="90" border="0" /></a></p>	 
     	</td>
      	<td width="200">
      		<p>ESTADOS PROCESALES</p>
        	<p><a href="estados/estados.php"><img src="img/estados.png" width="90" height="90" border="0" /></a></p>
      	</td>
    </tr>
    <tr>
      	<td>&nbsp;</td>
      	<td>
      		<p>ASESORES LEGALES</p>
      		<p><a href="asesores/asesores.php"><img src="img/asesores.png" alt="enviar" width="90" height="90" border="0"/></a></p>
      	</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
