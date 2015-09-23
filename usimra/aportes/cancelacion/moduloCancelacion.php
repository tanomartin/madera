<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Cancelacion USIMRA :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
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
<body bgcolor="#B2A274">
<form id="form1" name="form1" method="post" action="listaPagos.php">
	<div align="center">
		<input type="button" name="volver" value="Volver" onclick="location.href = '../menuAportes.php'" /> 
	</div>
	<div align="center">
		<h1>M&oacute;dulo de Cancelaci&oacute;n Manual de Aportes</h1>
	</div>
<?php 
	$err = $_GET['err'];
	if($err == 1) { ?>
	<div align='center' style='color:#FF0000'>
		<p><b><h2> CUIT NO ENCONTRADO </h2></b></p>
	</div>
<?php 
	}
?>
	<div align="center">
		<label><h3>CUIT <input name="cuit" id="cuit" type="text" size="10" /></h3></label>
	</div>
	<p align="center"><input type="submit" name="Submit" value="Buscar" /></p>
</form>
</body>
</html>
