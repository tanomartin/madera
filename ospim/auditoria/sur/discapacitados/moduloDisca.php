<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Discapacitados :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuSur.php'" /></p>
  <h2>M�dulo Discapacitados</h2>
  <table width="600" border="3" style="text-align: center">
    <tr>
	  	<td width="200">
	  		<p>A.B.M.C</p>
			<p><a class="enlace" href="abm/moduloABMDisca.php"><img src="img/discapacitados.png" width="90" height="90" border="0" alt="enviar"/></a></p>
		</td>
		<td width="200">
			<p>SERVICIOS PRESTADORES</p>
			<p><a class="enlace" href="serviciopresta/moduloServicioPresta.php"><img src="img/prestador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
		</td>
	  	<td  width="200">
	  		<p>INFORMES</p>
          	<p><a class="enlace" href="informes/moduloInformes.php"><img src="img/informesconsultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        </td>
    </tr>  
	<tr>  
		<td>
			<p>RESOLUCIONES</p>
          	<p><a class="enlace" href="resoluciones/moduloResoluciones.php"><img src="img/resoluciones.png" width="90" height="90" border="0" alt="enviar"/></a></p>
		</td>
		<td>
			<p>ESCUELAS</p>
			<p><a class="enlace" href="escuelas/moduloEscuelas.php"><img src="img/escuelas.png" width="90" height="90" border="0"/></a></p>
		</td>
		<td></td>
	</tr>
  </table>
</div>
</body>
</html>
