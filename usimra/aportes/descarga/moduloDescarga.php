<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
if($_SERVER['SERVER_NAME'] != "localhost") {
	header('location: /madera/usimra/moduloNoDisponible.php');
	exit(0);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Descasrga Aplicativo :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
function importar() {
	$.blockUI({ message: "<h1>Descargando DDJJ... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	location.href='descargaDDJJ.php';
}
function notificaciones() {
	$.blockUI({ message: "<h1>Buscando Notificaciones... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	location.href='listaNotificaciones.php';
}
</script>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAportes.php'" />
  </span></p>
  <p><span class="Estilo2">M&oacute;dulo Descarga Aplicativo </span></p>
  <table width="414" border="3">
    <tr>
      <td width="200"><p align="center">Importaci&oacute;n de DDJJ, Empresas, Empleados y Familiares </p>
          <p align="center"><a href="javascript:importar()"><img src="img/Download.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Notificaciones a Empresas </p>
          <p align="center"><a href="javascript:notificaciones()"><img src="img/Envelope.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
