<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Legales Configuracion :.</title>
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
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuLegales.php'" align="center"/>
  </p>
  <p><span class="Estilo2">Men&uacute; Configuracion Legales </span></p>
  <table width="600" border="2">
    <tr>
     <td width="200"><p align="center">Juzgados</p>
        <p align="center"><a class="enlace" href="#"><img src="img/juzgado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p>	</td>
	  <td width="200"><p align="center">Secretarias</p>
          <p align="center"><a class="enlace" href="#"><img src="img/secretaria.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p>	 </td>
      <td width="200"><p align="center">Estados Procesales </p>
        <p align="center"><a class="enlace" href="#"><img src="img/estados.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p>	</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><p align="center">Asesores Legales </p>
      <p align="center"><a class="enlace" href="asesores/asesores.php"><img src="img/asesores.png" alt="enviar" width="90" height="90" border="0"/></a></p>
      <p>&nbsp;</p></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
