<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo OSPIM :.</title>
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
<form id="form1" name="form1" method="post" action="selecCanCuotas.php">
  <p align="center" class="Estilo1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="../menuAcuerdos.php">VOLVER</a></strong></font></p>
  <p align="center" class="Estilo1">M&oacute;dulo de Cancelaci&oacute;n de Cuotas </p>
   <?php 
  		$err = $_GET['err'];
		if ($err == 1) {
			print("<div align='center' style='color:#FF0000'><b> CUIT SIN ACUERDOS CARGADOS </b></div>");
		}
  ?>
  <p>
  <label> 
  <div align="center">CUIT 
    <input name="cuit" type="text" />
  </div>
  </label>
  <p align="center">
    <label>
    <input type="submit" name="Submit" value="Enviar" />
    </label>
  </p>
  <p>&nbsp;</p>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
