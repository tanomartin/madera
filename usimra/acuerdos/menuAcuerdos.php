<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Acuerdo USIMRA:.</title>
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
        <p align="center"><a class="enlace" href="abm/moduloABM.php"><img src="img/abmacuerdo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196"><p align="center"> Boletas de Pago </p>
          <p align="center"><a class="enlace" href="impresion/menuBoletas.php"><img src="img/impresora.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196"><p align="center">Banco</p>
	  	  <!-- href="banco/moduloBanco.php" -->
           <p align="center"><a class="enlace" href="banco/moduloBanco.php"><img src="img/banco.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
    <tr>
      <td><p align="center">Cancelaci&oacute;n Manual de Cuotas </p>
        <p align="center"><a class="enlace" href="cancelacion/moduloCancelacion.php"><img src="img/cancelado.png" width="90" height="90" border="0" /></a></p>
      <p align="center">&nbsp;</p></td>
      <td><p align="center">Informes</p>
	  	  <p align="center"><a class="enlace" href="informes/moduloInformes.php"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	  	  <p align="center">&nbsp;</p></td>
      <td><p align="center">Valores al Cobro</p>
	   <!-- href="../moduloNoDisponible.php" -->
	   
	  
      <p align="center"><a href="valores/menuValores.php"><img src="img/valores.png" width="90" height="90" border="0" /></a></p>
      <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
