<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Diabetes Presentacion S.S.S. :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fechadesde").mask("99-99-9999");
	$("#fechahasta").mask("99-99-9999");
});

function validar(formulario) {
	var fechadesde = formulario.fechadesde.value;
	var fechahasta = formulario.fechahasta.value;

	if (fechadesde == "") {
		alert("Debe ingresar un Fecha Desde tomar los diagnósticos");
		formulario.fechadesde.focus();
		return(false);
	} else {
		if (!esFechaValida(fechadesde)) {
			alert("La fecha Desde no es valida");
			formulario.fechadesde.focus();
			return(false);
		} 
	}
	if (fechahasta == "") {
		alert("Debe ingresar un Fecha Hasta tomar los diagnósticos");
		formulario.fechahasta.focus();
		return(false);
	} else {
		if (!esFechaValida(fechahasta)) {
			alert("La Fecha Hasta no es valida");
			formulario.fechahasta.focus();
			return(false);
		}
	}

	fechadesde = new Date(invertirFecha(fechadesde));
	fechahasta = new Date(invertirFecha(fechahasta));
	if (fechadesde >= fechahasta) {
		alert("La Fecha Desde debe ser superior a la Fecha Hasta");
		formulario.fechahasta.focus();
		return(false);
	}
	$.blockUI({ message: "<h1>Listado Beneficiarios entre las fechas dadas. Aguarde por favor...</h1>" });
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloPresSSS.php'" /></p>
	<h2>Nueva Presentacion Diabetes S.S.S.</h2>
	<form id="nuevaPresentacion" name="nuevaPresentacion" method="post" onsubmit="return validar(this)" action="nuevaPresentacionListado.php">
		<h3>Limite temporal de Diagnósticos</h3>
		<p><b>Fecha Desde: </b><input type="text" id="fechadesde" name="fechadesde" size="8"/></p>
		<p><b>Fecha Hasta: </b><input type="text" id="fechahasta" name="fechahasta" size="8"/></p>
		<button type="submit" name="Submit">Listar Beneficiarios</button>
	</form>
</div>
</body>
</html>