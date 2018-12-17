<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlProximo = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'madera' AND TABLE_NAME = 'ordennmcabecera'";
$resProximo = mysql_query($sqlProximo,$db);
$rowProximo = mysql_fetch_array($resProximo);
$nroProximo = $rowProximo['AUTO_INCREMENT'];

//VARIABLES PARA CONTROL DE LIMITE//
$LIMITECONCEPTO = 11;
$LIMITEIMPUTA = 5;
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

function vermasconcepto(limite) {
	var importeValue = document.getElementById("conceptoaver").value;
	if (importeValue <= limite) { 
		importeValue++
		var datos = "datos"+importeValue;
		var datosLinea = document.getElementById(datos);
		datosLinea.style.display = "table-row";
		document.getElementById("conceptoaver").value = importeValue;
	}
}

function vermenosconcepto(limiteImput) {
	var linea = document.getElementById("conceptoaver").value;
	if (linea > 1) { 
		var datos = "datos"+linea;
		var datosLinea = document.getElementById(datos);
		datosLinea.style.display = "none";
		limpiaImputacion(linea, limiteImput);

		var concepto = "concepto"+linea;
		var inputconcepto = document.getElementById(concepto);
		inputconcepto.value = "";

		var importe = "importe"+linea;
		var inputimporte = document.getElementById(importe);
		inputimporte.value = "0.00";
		
		linea--
		document.getElementById("conceptoaver").value = linea;
		calcularSaldos();
	}
}

function limpiaImputacion(linea, limiteImput) {
	var nombreimputaver = "imputaaver"+linea;
	var inputaver = document.getElementById(nombreimputaver);
	inputaver.value = 1;

	for(var i=limiteImput; i>0; i--) {
		var nombrecuenta = "impucuenta"+linea+"-"+i;
		var inputcuenta = document.getElementById(nombrecuenta);
		inputcuenta.value = "";
		if (i != 1) { inputcuenta.style.display = "none"; }

		var nombreimporte = "impusaldo"+linea+"-"+i;
		var inputimporte = document.getElementById(nombreimporte);
		inputimporte.value = "";
		if (i != 1) { inputimporte.style.display = "none"; }

		var nombredc = "impudc"+linea+"-"+i;
		var selectdc = document.getElementById(nombredc);
		selectdc.value = 'C';
		if (i != 1) { selectdc.style.display = "none"; }
	}
}

function vermasimputa(linea, limite) {
	var nombreImputa = "imputaaver"+linea;
	var imputaValue = document.getElementById(nombreImputa).value;
	if (imputaValue < limite) {
		imputaValue++
		
		var nombrecuenta = "impucuenta"+linea+"-"+imputaValue;
		var inputcuenta = document.getElementById(nombrecuenta);
		inputcuenta.style.display = "table-row";

		var nombreimporte = "impusaldo"+linea+"-"+imputaValue;
		var inputimporte = document.getElementById(nombreimporte);
		inputimporte.style.display = "table-row";

		var nombredc = "impudc"+linea+"-"+imputaValue;
		var selectdc = document.getElementById(nombredc);
		selectdc.style.display = "table-row";
		
		document.getElementById(nombreImputa).value = imputaValue;
	}
}

function vermenosimputa(linea) {
	var nombreImputa = "imputaaver"+linea;
	var imputaValue = document.getElementById(nombreImputa).value;
	if (imputaValue > 1) {
		
		var nombrecuenta = "impucuenta"+linea+"-"+imputaValue;
		var inputcuenta = document.getElementById(nombrecuenta);
		inputcuenta.value = "";
		inputcuenta.style.display = "none";

		var nombreimporte = "impusaldo"+linea+"-"+imputaValue;
		var inputimporte = document.getElementById(nombreimporte);
		inputimporte.value = "";
		inputimporte.style.display = "none";

		var nombredc = "impudc"+linea+"-"+imputaValue;
		var selectdc = document.getElementById(nombredc);
		selectdc.value = 'C';
		selectdc.style.display = "none";
		
		imputaValue--
		document.getElementById(nombreImputa).value = imputaValue;
		calcularSaldos();
	}
}

function verificarImporte(importe) {
	valor = importe.value;
	if (!isNumberPositivo(valor)) {
		alert("El importe debe ser un numero positivo");
		importe.value = "";
		importe.focus();
	} 
	if (importe.value != "") {
		importe.value = parseFloat(importe.value).toFixed(2);
	}
	calcularSaldos();
}

function calcularSaldos() {
	var linea = document.getElementById("conceptoaver").value;
	var saldoTotal = 0;
	for(var i=linea; i>0; i--) {
		var nombreImputa = "imputaaver"+i;
		var valorImputaAver = document.getElementById(nombreImputa).value;
		var saldoLinea = 0;
		for (var n=1; n <= valorImputaAver; n++) {
			var nombreimporte = "impusaldo"+i+"-"+n;
			var valorimporte = document.getElementById(nombreimporte).value;		
			if (valorimporte != "") {
				var nombredc = "impudc"+i+"-"+n;
				var selectdc = document.getElementById(nombredc);	
				if (selectdc.value == 'C') {
 					saldoLinea += parseFloat(valorimporte);
				} else {
					saldoLinea -= parseFloat(valorimporte);
				}
			}
		}
		var nombreImporte = "importe"+i;
		var inputimportelinea = document.getElementById(nombreImporte);
		inputimportelinea.value = saldoLinea.toFixed(2);

		saldoTotal += parseFloat(saldoLinea);
	}
	document.getElementById("monto").value = saldoTotal.toFixed(2);
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
	if (formulario.ropago.value == "" || formulario.ropago.value == 0 || !esEnteroPositivo(formulario.ropago.value)) {
		alert("El Nro de Cheque o Transferencia es obligatorio y debe ser un numero entero positivo");
		return false;	
	}

	var datosControl = document.getElementById("conceptoaver").value;

	var totalLineas = 0;
	for (var i=1; i <= datosControl; i++) {		
		var concepto = "concepto"+i;
		var importe = "importe"+i;
		var lineasConcepto = 0;
		var conceptoValue = document.getElementById(concepto).value;
		if (conceptoValue == "" || conceptoValue.length > 170) {
			alert("El concepto es obligatorio y debe tener menos de 170 caracteres");
			document.getElementById(concepto).focus();
			return false;
		}
		lineasConcepto = Math.ceil(conceptoValue.length / 58);
				
		var nombreImputa = "imputaaver"+i;
		var valorImputaAver = document.getElementById(nombreImputa).value;
		var totalLineasImpu = 0;
		for (var n=1; n <= valorImputaAver; n++) {		
			var nombrecuenta = "impucuenta"+i+"-"+n;
			var inputcuenta = document.getElementById(nombrecuenta);
			if (inputcuenta.value == "") {
				alert("La cuenta de imputacion contable es obligatoria");
				inputcuenta.focus();
				return false;
			}
			var nombreimporte = "impusaldo"+i+"-"+n;
			var inputimporte = document.getElementById(nombreimporte);
			if (inputimporte.value == "" || inputimporte.value == 0 || !isNumberPositivo(inputimporte.value) || inputimporte.value >= 10000000) {
				alert("El importe de la imputacion contable debe ser un numero positivo menor a 10 millones");
				inputimporte.focus();
				return false;
			}
			totalLineasImpu++
		}
		totalLineas += Math.max(lineasConcepto, totalLineasImpu);
	}

	if (totalLineas > 33) {
		alert("La cantidad total de lineas supera el limite por hoja.");
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
					<b style="float: right; font-size: x-large;">Nº <u style="color: maroon;"><?php echo $nroProximo ?></u></b>
				</p>
				<p><b>Beneficiario: </b><input name="beneficiario" id="beneficiario" size="75" maxlength="75"/></p>
				<p><b>$: </b><input value="0.00" name="monto" id="monto" size="12" readonly="readonly" style="background: silver; text-align: center; font-weight: bold;"/></p>
				<p>
					<b>Tipo Pago: 
						<input type="radio" id="tipo" name="tipo" value="T" checked="checked" /><label>Transferencia</label>
						<input type="radio" id="tipo" name="tipo" value="C" /><label>Cheque</label>
					</b>
				</p>
				<p><b>Nro: <input name="nropago" id="ropago" size="25"/></b></p>		
				<p align="center">
					<input type="button" value="+ Conceptos" onclick="vermasconcepto(<?php echo $LIMITECONCEPTO?>)"/>
					<input type="button" value="- Conceptos" onclick="vermenosconcepto(<?php echo $LIMITEIMPUTA?>)"/>
					<input size="1" style="display: none" type="text" value="1" id="conceptoaver" name="conceptoaver"/>
				</p>
			</div>
		</div>
		<table border="1" style="width: 900px; text-align: center">
			<thead>
				<tr>
					<th width="40%" rowspan="2">CONCEPTO</th>
					<th width="10%" rowspan="2">IMPORTE</th>
					<th width="50%" colspan="4">IMPUTACION CONTABLE</th>
				</tr>
				<tr>
					<th width="30%">CUENTA</th>
					<th>IMPORTE</th>
					<th>D/C</th>
					<th width="10%"></th>
				</tr>
			</thead>
			<tbody>
		<?php	for ($i = 1; $i<=$LIMITECONCEPTO; $i++) {
					$display = 'style="display: none"';
					if ($i == 1) { $display = ""; } ?>
					<tr <?php echo $display?> id="datos<?php echo $i?>">
						<td><textarea name="concepto<?php echo $i?>" id="concepto<?php echo $i?>" rows="3" cols="50"></textarea></td>
						<td><input name="importe<?php echo $i?>" id="importe<?php echo $i?>" value="0.00" size="10" readonly="readonly" style="background: silver; text-align: center; font-weight: bold;"/></td>
						<td>
				<?php	for ($n = 1; $n<=$LIMITEIMPUTA; $n++) {
							$display = 'style="display: none"';
							if ($n == 1) { $display = ''; } ?>
							<input <?php echo $display?> name="impucuenta<?php echo $i."-".$n ?>" id="impucuenta<?php echo $i."-".$n ?>" size="15" maxlength="10"/>
				 <?php } ?>		
						</td>
						<td>
				<?php	for ($n = 1; $n<=$LIMITEIMPUTA; $n++) {
							$display = 'style="display: none; text-align: center; font-weight: bold;"';
							if ($n == 1) { $display = 'style="text-align: center; font-weight: bold;"'; } ?>
							<input <?php echo $display?> name="impusaldo<?php echo $i."-".$n ?>" id="impusaldo<?php echo $i."-".$n?>" size="10" maxlength="10" onchange="verificarImporte(this)"/>
				  <?php } ?>
				 	   </td>		
						<td>
				<?php	for ($n = 1; $n<=$LIMITEIMPUTA; $n++) {
							$display = 'style="display: none"';
							if ($n == 1) { $display = ""; } ?>
							<select <?php echo $display?> name="impudc<?php echo $i."-".$n  ?>" id="impudc<?php echo $i."-".$n  ?>" onchange="calcularSaldos()">
								<option value="C" selected="selected">C</option>
								<option value="D">D</option>
							</select>
				 <?php } ?>
						</td>
						<td>
							<input type="button" value="+" onclick="vermasimputa(<?php echo $i ?>, <?php echo $LIMITEIMPUTA?>)"/>
							<input type="button" value="-" onclick="vermenosimputa(<?php echo $i ?>)"/>
							<input size="1" style="display: none" type="text" value="1" id="imputaaver<?php echo $i ?>" name="imputaaver<?php echo $i ?>"/>
						</td>
					</tr>
			<?php } ?>
			</tbody>	
		</table>
		<p><input type="submit" value="Generar Orden" /></p>
	</form>
</div>
</body>
</html>
