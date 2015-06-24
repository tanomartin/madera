<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Empresas :.</title>
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
<body style="background-color: <?php echo $bgcolor ?>">
<div align="center">
  <p><span class="Estilo2">Men&uacute; Empresas </span></p>
  <table width="600" border="1">
    <tr>
      <td width="200"><p align="center">Alta, Modificaci&oacute;n y Consulta </p>
        <p align="center"><a class="enlace" href="abm/moduloABM.php?origen=<?php echo $origen?>"><img src="img/abemp.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Buscador</p>
        <p align="center"><a class="enlace" href="buscador/buscador.php?origen=<?php echo $origen?>"><img src="img/buscar.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
	  <td width="200"><p align="center">Listado de Empresas </p>
        <p align="center"><a class="enlace" href="informes/listadoEmpresas.php?origen=<?php echo $origen?>"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
