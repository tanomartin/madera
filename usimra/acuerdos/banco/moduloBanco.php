<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
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
<div align="center"><input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAcuerdos.php'"/></div>
<div align="center">
  <p><span class="Estilo2">M&oacute;dulo De Pr</span><span class="Estilo2">ocesamiento Bancario</span></p>
  <table width="600" border="3">
    <tr>
      <td width="200"><p align="center">Documentacion Bancaria</p>
        <p align="center"><a class="enlace" href="documentos/documentosBancarios.php"><img src="img/documentacion.png" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
      <td width="200"><p align="center">Archivos Transferidos</p>
          <p align="center"><a class="enlace" href="archivos/procesamientoArchivos.php"><img src="img/archivosBanco.png" width="105" height="105" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Imputaciones</p>
          <p align="center"><a class="enlace" href="imputacion/procesamientoRegistros.php"><img src="img/imputacion.png" width="105" height="105" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
	<tr>
      <td>&nbsp;</td>
	  <td width="200"><p align="center">Consultas</p>
          <p align="center"><a class="enlace" href="informes/moduloInformes.php"><img src="img/consultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
	  <td>&nbsp;</td>
	</tr>
  </table>
  </div>
</body>
</html>
