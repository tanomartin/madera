<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Diabetes :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuSur.php'" /></p>
  <h2>Módulo Diabetes</h2>
  <table width="400" border="3" style="text-align: center">
    <tr>
	  	<td width="200">
	  		<p>A.B.M.C</p>
			<p><a href="abm/moduloDiabetes.php"><img src="img/diabetes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
		</td>
		<td width="200">
			<p>PRESENTACION S.S.S.</p>
			<p><a href="presentacion/moduloPresSSS.php"><img src="img/logosss.png" width="90" height="90" border="0" alt="enviar"/></a></p>
		</td>
    </tr>  
    <tr>
    	<td>
	  		<p>INFORMES</p>
			<p><a href="informes/moduloInformes.php"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
		</td>
		<td></td>
    </tr>
  </table>
</div>
</body>
</html>