<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Auditoria Medica :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <h2>Menú Auditoria Medica </h2>
  <table width="600" border="1" style="text-align: center;vertical-align: middle;">
    <tr>
      <td width="200">
      	  <p>PRESTADORES</p>
          <p><a href="prestadores/menuPrestadores.php"><img src="img/prestador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td> 	
      <td width="200">
      	  <p>AUTORIZACIONES</p>
          <p><a href="autorizaciones/moduloAutorizaciones.php"><img src="img/auditoria.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	  </td>
	  <td width="200">
	  	  <p>NOMENCLADORES</p>
          <p><a href="nomenclador/menuNomenclador.php"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>  
    </tr>
    <tr>
      <td>
        <p>S.U.R. </p>
        <p><a href="sur/menuSur.php"><img src="img/sur.jpg" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>PROG. DE PREVENCION </p>
      	<p><a href="../moduloNoDisponible.php"><img src="img/prevencion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>MEDICAMENTOS (ALFABETA)</p>
      	<p><a href="medicamentos/menuMedicamentos.php"><img src="img/medicamentos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
    </tr>
    <tr>
	  <td>
	   	<p>GESTION Y SEGUIMIENTO </p>
      	<p><a href="seguimiento/menuSeguimiento.php"><img src="img/seguimiento.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
	  <td>
	   	<p>LIQUIDACION PRESTADORES</p>
		<p><a href="liquidaciones/moduloLiquidaciones.php"><img src="img/liquidacion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	  </td>
	  <td>
	  	<p>ODONTOLOGIA</p>
	  	<p><a href="../moduloNoDisponible.php"><img src="img/odonto.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	  </td>
    </tr>
  </table>
</div>
</body>
</html>
