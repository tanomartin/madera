<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco OSPIM :.</title>
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
<table width="137" border="0">
	<tr align="center" valign="top">
      <td width="137" valign="middle"><div align="center">
        <input type="reset" name="volver" value="Volver" onClick="location.href = '../menuAcuerdos.php'" align="center"/> 
        </div></td>
	</tr>
</table>
</div>
<div align="center">
  <p><span class="Estilo2">M&oacute;dulo De Pr</span><span class="Estilo2">ocesamiento Bancario</span></p>
  <table width="412" height="189" border="3">
    <tr>
      <td width="196" height="164"><p align="center">Archivos Transferidos</p>
          <p align="center"><a class="enlace" href="procesamientoArchivos.php"><img src="img/archivosBanco.ico" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196" height="164"><p align="center">Imputaciones</p>
          <p align="center"><a class="enlace" href="procesamientoRegistros.php"><img src="img/imputacion.ico" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</div>
</body>
</html>
