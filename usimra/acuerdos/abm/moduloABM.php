<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo ABM Acuerdos :.</title>
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

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
});

</script>

<body bgcolor="#B2A274">
<form id="form1" name="form1" method="post" action="acuerdos.php">
<div align="center">
<input type="reset" name="volver" value="Volver" onClick="location.href = '../menuAcuerdos.php'" align="center"/> 
</div>
  <p align="center" class="Estilo1">M&oacute;dulo De ABM de Acuerdos U.S.I.M.R.A </p>
  <?php 
		$err = $_GET['err'];
		if ($err == 1) {
			print("<p><div align='center' style='color:#FF0000'><b> CUIT NO ENCONTRADO</b></div></p>");
		}
  ?>
  <label>
  <div align="center">CUIT 
    <input name="cuit" id="cuit" type="text" size="10" /></div>
  </label>
    <p align="center">
    <label>
    <input type="submit" name="Submit" value="Buscar" />
    </label>
  </p>
</form>
</body>
</html>
