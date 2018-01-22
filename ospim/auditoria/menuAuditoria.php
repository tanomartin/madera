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
  <h2>Men&uacute; Auditoria Medica </h2>
  <table width="600" border="1" style="text-align: center;vertical-align: middle;">
    <tr>
      <td width="200">
      	  <p>Prestadores</p>
          <p><a class="enlace" href="prestadores/menuPrestadores.php"><img src="img/prestador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td> 	
      <td width="200">
      	  <p>Autorizaciones</p>
          <p><a class="enlace" href="autorizaciones/moduloAutorizaciones.php"><img src="img/auditoria.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	  </td>
	  <td width="200">
	  	  <p>Nomencladores de Practicas</p>
          <p><a class="enlace" href="nomenclador/menuNomenclador.php"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>  
    </tr>
    <tr>
      <td>
        <p>S.U.R. </p>
        <p><a class="enlace" href="sur/menuSur.php"><img src="img/sur.jpg" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>Programa de Prevenci&oacute;n </p>
      	<p><a class="enlace" href="../moduloNoDisponible.php"><img src="img/prevencion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>Medicamentos (Alfa Beta)</p>
      	<p><a class="enlace" href="medicamentos/menuMedicamentos.php"><img src="img/medicamentos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
    </tr>
    <tr>
	  <td>
	   	<p>Gestión y Seguimiento </p>
      	<p><a class="enlace" href="seguimiento/menuSeguimiento.php"><img src="img/seguimiento.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
	  <td>
	   	<p>Liquidacion Prestadores</p>
		<p><a class="enlace" href="../moduloNoDisponible.php"><img src="img/liquidacion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	  </td>
	  <td></td>
    </tr>
  </table>
</div>
</body>
</html>
