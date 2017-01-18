<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

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

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
});

</script>
</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" action="impBoletas.php">
  <p align="center">
  	<input type="button" name="volver" value="Volver" onclick="location.href = 'menuBoletas.php'" />
  </p>
  <p align="center" class="Estilo1">M&oacute;dulo Impresi&oacute;n de Bolestas</p>
   <?php 
   		if (isset($_GET['err'])) { 
	  		$err = $_GET['err'];
			if ($err == 1) {
				print("<p><div align='center' style='color:#FF0000'><b> CUIT SIN ACUERDOS CARGADOS </b></div></p>");
			}
			if ($err == 2) {
				print("<p><div align='center' style='color:#FF0000'><b> CUIT NO ENCONTRADO </b></div></p>");
			}
   		}
  ?>
   
  <div align="center"><label>CUIT <input name="cuit" type="text" id="cuit" size="10" /></label></div>
  
  <p align="center">
    <label>
    <input type="submit" name="Submit" value="Buscar" />
    </label>
  </p>
  <p>&nbsp;</p>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
