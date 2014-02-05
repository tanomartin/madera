<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo OSPIM :.</title>
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
   <input type="reset" name="volver" value="Volver" onClick="location.href = '../menuAcuerdos.php'" align="center"/> 
  </p>
  <p><span class="Estilo2">Men&uacute; Valores </span></p>
  <table width="412" border="3">
    <tr>
      <td width="196"><p align="center">Listado de Valores </p>
        <p align="center"><a class="enlace" href="valoresRealizados.php"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196" valign="top"><p align="center">Nuevo Valor </p>
        <p align="center"><a class="enlace" href="listadoValores.php"><img src="img/valores.png" width="90" height="90" border="0" alt="enviar"/>	</a></p></td>
    </tr>
  </table>
</div>
</body>
</html>