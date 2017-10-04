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
  <table width="600" border="3">
    <tr>
      <td width="200"><p align="center">Prestadores</p>
          <p align="center"><a class="enlace" href="../moduloNoDisponible.php"><img src="img/prestadores.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Autorizaciones</p>
          <p align="center"><a class="enlace" href="autorizaciones/moduloAutorizaciones.php"><img src="img/auditoria.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
		<td width="200"><p align="center">Nomencladores</p>
          <p align="center"><a class="enlace" href="../moduloNoDisponible.php"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
    <tr>
      <td><p align="center">S.U.R. </p>
        <p align="center"><a class="enlace" href="sur/menuSur.php"><img src="img/sur.jpg" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td><p align="center">Programa de Prevenci&oacute;n </p>
      <p align="center"><a class="enlace" href="../moduloNoDisponible.php"><img src="img/prevencion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
      <td><?php if ($_SESSION['usuario'] == 'sistemas' || $_SESSION['usuario'] == 'sgiraudo' || $_SESSION['usuario'] == 'gflongo') { ?>  
     	 	<p align="center">Gestión y Seguimiento </p>
      		<p align="center"><a class="enlace" href="../moduloNoDisponible.php"><img src="img/seguimiento.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      		<p>&nbsp;</p>
      	 <?php } ?>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
