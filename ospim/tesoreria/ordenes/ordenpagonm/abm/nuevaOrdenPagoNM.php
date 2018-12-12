<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$nroProximo = 24075;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Ordenes Pago :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fecha").mask("99-99-9999");
});

function vermasconcepto() {
	var importeValue = document.getElementById("conceptoaver").value;
	if (importeValue < 14) { 
		var datos = "datos"+importeValue;
		var datosLinea = document.getElementById(datos);
		datosLinea.style.display = "table-row";
		document.getElementById("conceptoaver").value++;
	}
}

function validar(formulario) {
	if (!esFechaValida(formulario.fecha.value)) {
		alert("La fecha es obligatoria");
		return false;	
	}
	if (formulario.beneficiario.value == "") {
		alert("El beneficiario es obligatorio");
		return false;	
	}
	if (formulario.monto.value == "" || formulario.monto.value == 0 || !isNumberPositivo(formulario.monto.value)) {
		alert("El Monto es obligatorio y debe ser un numero positivo");
		return false;	
	}
	if (formulario.cheque.value == "" || formulario.cheque.value == 0 || !esEnteroPositivo(formulario.cheque.value)) {
		alert("El Nro de Cheque es obligatorio y debe ser un numero entero positivo");
		return false;	
	}
	if (formulario.facturas.value == "") {
		alert("El detalle de facturas es obligatorio");
		return false;	
	}

	var datosControl = document.getElementById("conceptoaver").value;
	for (var i=1; i < datosControl; i++) {
		var concepto = "concepto"+i;
		var importe = "importe"+i;
		var imputacion = "imputacion"+i;

		var conceptoValue = document.getElementById(concepto).value;
		if (conceptoValue == "") {
			alert("El concepto es obligatorio");
			document.getElementById(concepto).focus();
			return false;
		}
		var importeValue = document.getElementById(importe).value;
		if (importeValue == "" || importeValue == 0 || !isNumberPositivo(importeValue)) {
			alert("El importe es obligatorio  y debe ser un numero positivo");
			document.getElementById(importe).focus();
			return false;
		}
		var imputacionValue = document.getElementById(imputacion).value;
		if (imputacionValue == "") {
			alert("La imputación contable es obligatoria");
			document.getElementById(imputacion).focus();
			return false;
		}
	}

	if (formulario.aclaracion.value == "") {
		alert("Debe ingresar la Aclaración de la firma Contabilizado");
		return false;	
	}
	
	$.blockUI({ message: "<h1>Generando Orden de Pago... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloOrdenPagoNM.php'" /></p>	
	<form id="nuevoOrdenNM" name="nuevoOrdenNM" method="post" onsubmit="return validar(this)" action="guardarOrdenNM.php">
	  	<h3>Nueva Orden de Pago No Médica</h3>
	  	<div style="border-style: solid; border-width: 1px; width: 900px;">
			<div style="text-align: left; width: 850px; ">
				<p>
					<b>Fecha: </b><input name="fecha" id="fecha" size="8"/>
					<b style="float: right; font-size: x-large;">Nº <?php echo $nroProximo ?></b>
				</p>
				<p><b>Beneficiario: </b><input name="beneficiario" id="beneficiario" size="102"/></p>
				<p><b>$: </b><input name="monto" id="monto" size="20"/></p>
				<p><b>Cheque Nro: <input name="cheque" id="cheque" size="25"/> C/Banco Nacion Argentina Suc. Caballito </b></p>
				<p><b>Factura/s Nro: </b><input name="facturas" id="facturas" size="101"/></p>
				<p><b>Aclaracion Firma Contabilizado: </b><input name="aclaracion" id="aclaracion" size="40" /></p>
			</div>
		</div>
		<table border="1" style="width: 900px; text-align: center">
			<thead>
				<tr>
					<th width="40%">CONCEPTO</th>
					<th width="10%">IMPORTE</th>
					<th width="50%">IMPUTACION CONTABLE</th>
				</tr>
			</thead>
			<tbody>
			<?php
				for ($i = 1; $i<15; $i++) {
					$display = 'style="display: none"';
					if ($i == 1) { $display = ""; } ?>
					<tr <?php echo $display?> id="datos<?php echo $i?>">
						<td><input name="concepto<?php echo $i?>" id="concepto<?php echo $i?>" size="39" maxlength="48"/></td>
						<td><input name="importe<?php echo $i?>" id="importe<?php echo $i?>" size="10"/></td>
						<td><input name="imputacion<?php echo $i?>" id="imputacion<?php echo $i?>" size="52" maxlength="60"/></td>
					</tr>
			<?php } ?>
			</tbody>	
		</table>
		<p><input style="display: none" type="text" value="2" id="conceptoaver" name="conceptoaver"/></p>
		<p><input type="button" value="Ver + Conceptos" onclick="vermasconcepto()"/></p>
		<p><input type="submit" value="Generar" /></p>
	</form>
</div>
</body>
</html>
