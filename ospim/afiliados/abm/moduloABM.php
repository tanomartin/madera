<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo ABM Afiliados :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" action="afiliado.php">
<p align="center"><font face="Verdana" color="#000000" size="2"><strong><a href="../menuAfiliados.php">VOLVER</a></strong></font><font color="#000000" size="2"><strong></strong></font></p>
  <p align="center" class="Estilo1">M&oacute;dulo De ABM de Afiliados</p>
  <p>
    <?php 
		$err = $_GET['err'];
		if ($err == 1) {
			print("<div align='center' style='color:#FF0000'><b> CUIT NO ENCONTRADO </b></div>");
		}

  ?></p>
  <div align="center">
    <table width="137" border="0">
      <tr>
        <td width="23"><input name="radiobutton" type="radio" value="nroafil" /></td>
        <td width="104"> <div align="left">Nro Afiliado</div></td>
      </tr>
      <tr>
        <td><label>
          <input name="radiobutton" type="radio" value="nrodocu" />
        </label></td>
        <td><div align="left">Nro Documento</div></td>
      </tr>
      <tr>
        <td><label>
          <input name="radiobutton" type="radio" value="nrocuil" />
        </label></td>
        <td><div align="left">Nro CUIL</div></td>
      </tr>
    </table>
    <p>
      <label>
      <input type="text" name="textfield" />
      </label>
    </p>
  </div>
  <label></label>
  <p align="center">
    <label>
    <input type="submit" name="Submit" value="Enviar" />
    </label>
  </p>
  <p align="center"><input type="button" value="Nuevo Afiliado" onclick="location.href='nuevoAfiliado.php'"/></p>
  <p>&nbsp;</p>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
