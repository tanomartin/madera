<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Afiliados OSPIM :.</title>
</head>
<body bgcolor="#CCCCCC">
	<div align="center">
		<h2>Menú Afiliados</h2>
	  	<table width="600" border="1" style="text-align: center" >
		    <tr>
		      <td width="200">
		      	<p>ALTA, MODIFICACIÓN Y CONSULTA</p>
		        <p><a href="abm/moduloABM.php"><img src="img/abmafil.png" width="90" height="90" border="0" /></a></p>
		      </td>
		      <td width="200">
		      	<p>CARNETS</p>
		        <p><a href="carnets/moduloImpresion.php"><img src="img/carnet.png" width="90" height="90" border="0"/></a></p>
		      </td>
		      <td width="200">
		      	<p>AUTORIZACIONES</p>
		        <p><a href="verificaciones/buscaSolicitudes.php"><img src="img/autorizaciones.png" width="90" height="90" border="0" /></a></p>
		      <p align="center">&nbsp;</p></td>
		    </tr>
			<tr>
		      <td>
		      	<p>PROCESOS</p>
		      	<p><a class="enlace" href="procesos/moduloProcesos.php"><img src="img/procesos.png" width="90" height="90" border="0"/></a></p>
		      </td>
		      <td>
		      	<p>INFORMES</p>
		        <p><a class="enlace" href="informes/moduloInformes.php"><img src="img/informesconsultas.png" width="90" height="90" border="0" /></a></p>
		      </td>
		      <td>
		      	<p>PLAN MATERNO INFANTIL</p>
			  	<p><a href="pmi/moduloPMI.php"><img src="img/pmi.png" width="90" height="90" border="0" /></a></p>
			   </td>
		    </tr>
	  	</table>
	</div>
</body>
</html>
