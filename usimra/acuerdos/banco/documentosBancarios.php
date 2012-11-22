<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/usimra/lib/";
include($libPath."controlSession.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
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
  <p align="center"><font face="Verdana" color="#000000" size="2"><strong><a href="moduloBanco.php">VOLVER</a></strong></font><font color="#000000" size="2"><strong></strong></font></p>
  <p><span class="Estilo2">Documentacion Bancaria</span></p>
  <table width="614" height="340" border="3">
    <tr>
     <td width="196" height="164"><p align="center">Resumenes</p>
        <p align="center"><a class="enlace" href="resumenBancario.php"><img src="../img/resumen.jpg" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td width="196" height="164"><p align="center">Remesas / Remitos </p>
        <p align="center"><a class="enlace" href="remesasBancarias.php"><img src="../img/remesas.jpg" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td width="196" height="164"><p align="center">Remitos Sueltos</p>
        <p align="center"><a class="enlace" href="remitosSueltosBancarios.php"><img src="../img/remitos.jpg" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
    </tr>
    <tr>
      <td height="164" colspan="3"><p align="center">Conciliacion</p>
      <p align="center"><a class="enlace" href="conciliacionBancaria.php"><img src="../img/conciliacion.jpg" width="105" height="105" border="0" alt="enviar"/></a></p>        <p>&nbsp;</p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</div>
</body>
</html>
