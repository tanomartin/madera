<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Informes de Acuerdos :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#fechahasta").mask("99-99-9999");
});

function validar(formulario) {
	if (!esFechaValida(formulario.fechahasta.value)) {
		alert("La fecha no es valida");
		document.getElementById("fechahasta").focus();
		return(false);
	} 
	$.blockUI({ message: "<h1>Generando Informe. Aguarde por favor...</h1>" });
	return true;
}
</script>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" onsubmit="return validar(this)" method="post" action="chequesCarteraExcel.php" enctype="multipart/form-data" >
<div align="center">
	<input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /> 
</div>
<p align="center" class="Estilo1">Cheques en Cartera</p>
<p align="center">Hasta el:<label><input id="fechahasta" name="fechahasta" type="text" value="<?php echo date("d/m/Y",time());?>" size="10"/></label></p>
<p align="center"><label><input type="submit" name="Submit" value="Generar Informe"/></label></p>
</form>
</body>
</html>