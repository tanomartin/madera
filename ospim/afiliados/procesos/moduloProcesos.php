<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Afiliados OSPIM :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script>
function mostrar(dire) {
	$.blockUI({ message: "<h1>Realizando Proceso Pedido... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	window.location = dire;
}
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<input type="button" name="volver" value="Volver" onclick="location.href = '../menuAfiliados.php'" /> 
</div>
<div align="center">
	<h2>Men&uacute; Procesos</h2>
</div>
<div align="center">
  <table width="400" border="3">
    <tr>
       <td width="200"><p align="center">Filtro Titulares</p>
        <p align="center"><a class="enlace" href="javascript:mostrar('filtroTitulares/filtroTitulares.php')"><img src="../img/filtro.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p>&nbsp;</p></td>
      <td width="200"><p align="center">Filtro Familiares</p>
          <p align="center"><a class="enlace" href="javascript:mostrar('filtroFamiliares/filtroFamiliares.php')"><img src="../img/filtro.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p>&nbsp;</p></td>
    </tr>
    <tr>
       <td width="200"><p align="center">Reactivación Masiva</p>
          <p align="center"><a class="enlace" href="javascript:mostrar('reactivaTitulares/reactivaTitulares.php')"><img src="../img/reactivacion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p>&nbsp;</p></td>
       <td width="200"><p align="center">Comparación Padron SSS</p>
          <p align="center"><a class="enlace" href="cruceSSS/menuCruceSSS.php"><img src="../img/mirroring.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
