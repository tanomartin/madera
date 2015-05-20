<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Empleados Por Empresa :.</title>
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

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
});

</script>

<body bgcolor="#B2A274">
<form id="form1" name="form1" method="post" action="listadoTitularesPorEmpresa.php">
<div align="center">
<input type="reset" name="volver" value="Volver" onClick="location.href = 'menuInformes.php'" align="center"/> 
</div>
  <p align="center" class="Estilo1"> Titulares por Empresa </p>
  <?php 
		$err = $_GET['err'];
		if ($err == 2) {
			print("<div align='center' style='color:#FF0000'><b> C.U.I.T. NO ENCONTRADO </b></div><br>");
		}
		if ($err == 1) {
			$cuit = $_GET['cuit'];
			print("<div align='center' style='color:#FF0000'><b> NO EXISTEN EMPLEADOS PARA EL C.U.I.T. '$cuit' </b></div><br>");
		}
  ?>
  <label>
  <div align="center">C.U.I.T. 
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
