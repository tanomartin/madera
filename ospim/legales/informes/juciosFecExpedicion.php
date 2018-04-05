<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Informes de Juicios :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechadesde").mask("99-99-9999");
	$("#fechahasta").mask("99-99-9999");
});

function validar(formulario) {
	if (!esFechaValida(formulario.fechadesde.value)) {
		alert("La fecha desde no es valida");
		document.getElementById("fechadesde").focus();
		return(false);
	}
	if (!esFechaValida(formulario.fechahasta.value)) {
		alert("La fecha hasta no es valida");
		document.getElementById("fechahasta").focus();
		return(false);
	} 
	$.blockUI({ message: "<h1>Generando Informe. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" onsubmit="return validar(this)" method="post" action="juiciosFecExpedicionExcel.php" enctype="multipart/form-data" >
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /></p> 
		<h3>Jucios por fecha de Expedicion</h3>
		<p>Desde el: <input id="fechadesde" name="fechadesde" type="text" value="<?php echo date("d/m/Y",time());?>" size="10"/></p>
		<p>Hasta el: <input id="fechahasta" name="fechahasta" type="text" value="<?php echo date("d/m/Y",time());?>" size="10"/></p>
		<p><input type="submit" name="Submit" value="Generar Informe"/></p>
	</div>
</form>
</body>
</html>