<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo SSS OSPIM :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /> </p>
  <h2>Menu Padrón SSS </h2>
  <table width="400" border="3">
    <tr>
	  <td width="200"><p align="center">Información Histórica</p>
          <p align="center"><a class="enlace" href="infohistoricasss.php"><img src="../img/historicosss.png" width="90" height="90" border="0" alt="enviar"/></a></p>
          <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Busqueda</p>
          <p align="center"><a class="enlace" href="buscadorAfiliadoSSS.php"><img src="../img/buscar.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
