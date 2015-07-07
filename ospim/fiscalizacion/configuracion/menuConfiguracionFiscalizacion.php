<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Configuracion Fiscalizacion :.</title>
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
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuFiscalizacion.php'" align="center"/>
</p>
  <p><span class="Estilo2">Men&uacute; Configuraci&oacute;n Fiscalzaci&oacute;n </span></p>
  <table width="412" border="3">
    <tr>
      <td width="196"><p align="center"> Inspectores </p>
          <p align="center"><a class="enlace" href="gestoresinspectores/inspectores.php"><img src="img/inspector.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196"><p align="center">Gestores</p>
          <p align="center"><a class="enlace" href="gestoresinspectores/gestores.php"><img src="img/gestor.png"" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
  </div>
</body>
</html>
