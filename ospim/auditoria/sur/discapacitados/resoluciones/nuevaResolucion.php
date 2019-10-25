<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Carga Resoluciones :.</title>

<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fechaemision").mask("99-99-9999");
	$("#fechainicio").mask("99-99-9999");
	$("#fechafin").mask("99-99-9999");
});

function validar(formulario) {	
	if (formulario.nombre.value == "") {
		alert("El nombre de la resolución es obligatoria");
		return false;
	}
	if (formulario.emisor.value == "") {
		alert("El Emisor de la resolución es obligatoria");
		return false;
	}
	if (formulario.fechaemision.value == "") {
		alert("La fecha emision de la resolución es obligatoria");
		return false;
	} else {
		if (!esFechaValida(formulario.fechaemision.value)) {
			alert("La fecha emision de la resolución no es valida");
			return false;
		}
	}
	if (formulario.fechainicio.value == "") {
		alert("La fecha de inicio de la resolución es obligatoria");
		return false;
	} else {
		if (!esFechaValida(formulario.fechainicio.value)) {
			alert("La fecha inicio de la resolución no es valida");
			return false;
		}
	}
	if (formulario.fechafin.value != "") {
		if (!esFechaValida(formulario.fechafin.value)) {
			alert("La fecha fin de la resolución no es valida");
			return false;
		}
	}
	$.blockUI({ message: "<h1>Guardando Nueva cabecera de la Resolución...<br> Aguarde por favor</h1>" });
	formulario.Submit.disabled = true;
	return true;
}
</script>


</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'resoluciones.php'"/></p>
  	<h3>Nueva Resolucion</h3>
	<form name="nuevoResolucion" id="nuevoResolucion" method="post" onsubmit="return validar(this)" action="guardarNuevaResolucion.php">
		<p><b>Nombre: </b><input type="text" name="nombre" size="60" id="nombre"/></p>
		<p><b>Emisor: </b><input type="text" name="emisor" size="60" id="emisor"/></p>
		<p><b>Fecha Emision: </b><input type="text" name="fechaemision" id="fechaemision" size="10"/></p>
		<p><b>Fecha Inicio: </b><input type="text" name="fechainicio" id="fechainicio" size="10" /></p>
		<p><b>Fecha Fin: </b><input type="text" name="fechafin" id="fechafin" size="10" /></p>
		<p><b>Observcacion</b></p>
		<p><textarea rows="5" cols="60" id="obs" name="obs"></textarea></p>
		<p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
	</form>
</div>
</body>
</html>