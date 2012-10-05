<?php include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/controlSession.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo OSPIM :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<body bgcolor="#B2A274">
<div align="center">
  <p><span class="Estilo2">Men&uacute; Acuerdos </span></p>
  <table width="614" border="3">
    <tr>
      <td width="196"><p align="center">Alta, Modificaci&oacute;n y Consulta</p>
        <p align="center"><a class="enlace" href="abm/moduloABM.php"><img src="img/abmacuerdo.jpg" width="97" height="78" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196"><p align="center"> Boletas de Pago </p>
          <p align="center"><a class="enlace" href="impresion/menuBoletas.php"><img src="img/impresora.jpg" width="98" height="84" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196"><p align="center">Banco</p>
	  	  <!-- href="banco/moduloBanco.php" -->
           <p align="center"><a class="enlace" href="banco/moduloBanco.php"><img src="img/banco.jpg" width="107" height="81" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><p align="center">Informes</p>
	  <!-- href="informes/moduloInformes.php" -->
        <p align="center"><a href="../moduloNoDisponible.php"><img src="img/informes.jpg" width="120" height="80" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><p align="center">Cancelaci&oacute;n Manual de Cuotas </p>
        <p align="center"><a class="enlace" href="cancelacion/moduloCancelacion.php"><img src="img/cancelado.jpg" width="97" height="76" border="0" /></a></p>
      <p align="center">&nbsp;</p></td>
      <td><p align="center">Fiscalizaci&oacute;n</p>
	  	  <!--  href="impresion/fiscalizacionImpresion.php" -->
        <p align="center"><a class="enlace" href="../moduloNoDisponible.php"><img src="img/fiscalizacion.jpg" width="97" height="76" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td><p align="center">Valores al Cobro</p>
	   <!-- href="valores/moduloValores.php" -->
      <p align="center"><a href="../moduloNoDisponible.php"><img src="img/valores.jpg" width="96" height="76" border="0" /></a></p>
      <p align="center">&nbsp;</p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
</body>
</html>
