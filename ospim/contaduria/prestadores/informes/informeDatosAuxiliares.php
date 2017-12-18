<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Informes Datos Auxiliares :.</title>
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/ui.datepicker-es.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$("#fechadesde").mask("99-99-9999");
	$("#fechadesde").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
});

function validar(formulario) {
	if(formulario.fechadesde.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar la Fecha Desde donde quiere el informe</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fecharecepcion').focus(); }});
		return false;
	} else {
		if(!esFechaValida(formulario.fechadesde.value)) {
			var cajadialogo = $('<div title="Aviso"><p>La Fecha Desde ingresada no es válida.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fecharecepcion').focus(); }});
			return false;
		}
	}
}

</script>
</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" onsubmit="return validar(this)" method="post" action="informeDatosAuxiliaresExcel.php" enctype="multipart/form-data" >
	<div align="center">
		<input type="button" name="volver" value="Volver" onclick="location.href = '../moduloPrestadores.php'" /> 
		<h3>Datos Auxiliares Prestadores</h3>
		<p>Desde: <input id="fechadesde" name="fechadesde" type="text" value="" size="6"/></p>
		<p><input type="submit" name="Submit" value="Generar Informe"/></p>
	</div>
</form>
</body>
</html>