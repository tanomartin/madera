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
<div align="center"><input type="reset" name="volver" value="Volver" onclick="location.href = '../moduloBanco.php'"/></div>
<div align="center">
  <p><span class="Estilo2">Procesamiento Convenio 0XO0 / Link Pagos</span></p>
  <table width="400" border="3">
    <tr>
      <td width="200"><p align="center">Archivos Transferidos</p>
          <p align="center"><a class="enlace" href="archivos/procesamientoArchivosLinkpagos.php"><img src="img/archivosBanco.png" width="105" height="105" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Imputaciones</p>
          <p align="center"><a class="enlace" href="imputacion/procesamientoRegistrosLinkpagos.php"><img src="img/imputacion.png" width="105" height="105" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
  </div>
</body>
</html>
