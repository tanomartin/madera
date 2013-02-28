<?php include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Empresas :.</title>
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

<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
  <p><span class="Estilo2">Men&uacute; Empresas </span></p>
  <table width="412" border="3">
    <tr>
      <td width="196"><p align="center">Alta, Modificaci&oacute;n y Consulta </p>
        <p align="center"><a class="enlace" href="abm/moduloABM.php?origen=<?php echo $origen?>"><img src="img/abemp.jpg" width="86" height="70" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196"><p align="center">Buscador</p>
        <p align="center"><a class="enlace" href="buscador/buscador.php?origen=<?php echo $origen?>"><img src="img/buscar.jpg" width="97" height="76" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
