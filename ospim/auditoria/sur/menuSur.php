<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo SUR OSPIM :.</title>
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
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAuditoria.php'" align="center"/>
  </p>
  <p><span class="Estilo2">Men&uacute; S.U.R. </span></p>
  <table width="400" border="3">
    <tr>
	  <td width="200"><p align="center">A.B.M.C. Discapacitados </p>
	    <p align="center"><a class="enlace" href="abm/moduloABMDisca.php"><img src="img/abmdisca.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Informes</p>
          <p align="center"><a class="enlace" href="informes/moduloInformes.php"><img src="img/informesconsultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
          <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
