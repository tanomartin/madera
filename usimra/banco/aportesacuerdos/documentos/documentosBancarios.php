<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Banco USIMRA :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>
<body bgcolor="#B2A274">
<div align="center"><input type="reset" name="volver" value="Volver" onclick="location.href = '../moduloAportes.php'"/></div>
<div align="center">
  <p><span class="Estilo2">Documentacion Bancaria</span></p>
  <table width="614" border="3">
    <tr>
     <td width="196" height="164"><p align="center">Resumenes</p>
        <p align="center"><a class="enlace" href="resumenes/resumenBancario.php"><img src="img/resumen.png" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td width="196" height="164"><p align="center">Remesas / Remitos </p>
        <p align="center"><a class="enlace" href="remesas/remesasBancarias.php"><img src="img/remesas.png" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td width="196" height="164"><p align="center">Remitos Sueltos</p>
        <p align="center"><a class="enlace" href="remitosSueltos/remitosSueltosBancarios.php"><img src="img/remitos.png" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
    </tr>
    <tr>
      <td height="164"><p align="center">Transferencias</p>
        <p align="center"><a class="enlace" href="trasnferencias/trasnferencias.php"><img src="img/transferencia.png" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
      <td height="164"><p align="center">Conciliacion</p>
        <p align="center"><a class="enlace" href="conciliacion/listaAConciliar.php"><img src="img/conciliacion.png" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td height="164"><p align="center">&nbsp;</p>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</div>
</body>
</html>
