<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Sistemas :.</title>
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

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span class="Estilo2">Men&uacute; Sistemas </span></p>
  <table width="600" border="3">
    <tr>
      <td width="200"><p align="center">Fiscalizaci&oacute;n</p>
          <p align="center"><a class="enlace" href="fiscalizacion/menuFiscalizacion.php"><img src="img/fiscalizacion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Aplicativo DDJJ </p>
      <p align="center"><a class="enlace" href="aplicativoddjj/menuAplicativoddjj.php"><img src="img/aplicativoddjj.png" width="97" height="85" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Tratamiento A.F.I.P.</p>
        <p align="center"><a class="enlace" href="afip/menuAfip.php"><img src="img/afip.jpg" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
    <tr>
      <td><p align="center">Padrones</p>
        <p align="center"><a class="enlace" href="#"><img src="img/padrones.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td><p align="center">Stock</p>
      <p align="center"><a class="enlace" href="stock/menuStock.php"><img src="img/stock.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td><p align="center">Desempleo A.N.S.E.S.</p>
      <p align="center"><a class="enlace" href="desempleo/menuDesempleo.php"><img src="img/desempleo.jpg" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
    </tr>
    <tr>
      <td><p align="center">Intranet O.S.P.I.M. </p>
        <p align="center"><a class="enlace" href="intraospim/menuActualizacionOspim.php"><img src="img/intraospim.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
      <td><p align="center">Intranet U.S.I.M.R.A. </p>
        <p align="center"><a href="intrausimra/menuActualizacionUsimra.php"><img src="img/intrausimra.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
