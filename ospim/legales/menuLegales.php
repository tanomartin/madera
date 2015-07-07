<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Legales :.</title>

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
<body bgcolor="#CCCCCC">
<div align="center">
  <p><span class="Estilo2">Men&uacute; Legales </span></p>
  <table width="600" border="2">
    <tr>
     <td width="200"><p align="center">Juicios</p>
        <p align="center"><a class="enlace" href="juicios/moduloJuicios.php"><img src="img/juicios.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p>	
	</td>
	  <td width="200"><p align="center">Configuraci&oacute;n</p>
          <p align="center"><a class="enlace" href="configuracion/menuConfiguracion.php"><img src="img/configuracion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p>	  
	 </td>
      <td width="200"><p align="center">Informes</p>
        <p align="center"><a class="enlace" href="#"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p>	
	</td>
	  
    </tr>
  </table>
</div>
</body>
</html>
