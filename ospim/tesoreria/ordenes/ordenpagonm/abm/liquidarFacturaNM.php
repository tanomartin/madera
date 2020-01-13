<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$id = $_GET['id'];
$sqlFacturas = "SELECT *
				FROM facturas f, prestadores p
				WHERE f.id= $id and f.idPrestador = p.codigoprestador";
$resFacturas = mysql_query($sqlFacturas,$db);
$rowFacturas = mysql_fetch_assoc($resFacturas); 

$LIMITECONCEPTO = 10; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Ordenes Pago :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

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

function vermenosconcepto() {
	var linea = document.getElementById("conceptoaver").value;
	if (linea > 1) { 
		var datos = "datos"+linea;
		var datosLinea = document.getElementById(datos);
		datosLinea.style.display = "none";

		var concepto = "concepto"+linea;
		var inputconcepto = document.getElementById(concepto);
		inputconcepto.value = "";

		var importe = "importe"+linea;
		var inputimporte = document.getElementById(importe);
		inputimporte.value = "";
		
		linea--
		document.getElementById("conceptoaver").value = linea;
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
}


function validar(formulario) {
	var monto = formulario.monto.value;
	var datosControl = formulario.conceptoaver.value;
	var totalLineas = 0;
	var controlTotal = 0;
	for (var i=1; i <= datosControl; i++) {		
		var concepto = "concepto"+i;
		var lineasConcepto = 0;
		var conceptoValue = document.getElementById(concepto).value;
		if (conceptoValue == "" || conceptoValue.length > 170) {
			alert("El concepto es obligatorio y debe tener menos de 170 caracteres");
			document.getElementById(concepto).focus();
			return false;
		}

		var importe = "importe"+i;
		var importeValor = document.getElementById(importe).value;
		if (importeValor == "") {
			alert("El importe es obligatorio");
			document.getElementById(importe).focus();
			return false;
		} else {
			controlTotal = (parseFloat(controlTotal) + parseFloat(importeValor)).toFixed(2);
		} 		
		lineasConcepto = Math.ceil(conceptoValue.length / 58);
		totalLineas += lineasConcepto;
	}
	if (totalLineas > 33) {
		alert("La cantidad total de lineas supera el limite por hoja.");
		return false;
	}
	console.log(controlTotal);
	if (monto != controlTotal) {
		alert("El total de los importe de los conceptos no es igual la monto de la factura");
		return false;
	}
	
	$.blockUI({ message: "<h1>Guardando Liquidacion de Factura No Médica... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'listadoFacturasNM.php'" /></p>	
	<form id="nuevoOrdenNM" name="nuevoOrdenNM" method="post" onsubmit="return validar(this)" action="guardarLiquidacionNM.php">  	
	  	<h3>Liquidacion de Factura No Médica</h3>
	  	<div style="border-style: solid; border-width: 1px; width: 900px;">
			<div style="text-align: left; width: 850px;">
				<input type="text" value="<?php echo $id ?>" name="id" id="id" style="display: none"/>
				<p>Fecha: <b><?php echo $rowFacturas['fechacomprobante']?></b></p>
				<p>Prestador: <b> <?php echo $rowFacturas['nombre']?></b></p>
				<p><b>$: </b><input value="<?php echo $rowFacturas['importecomprobante']?>" name="monto" id="monto" size="12" readonly="readonly" style="background: silver; text-align: center; font-weight: bold;"/></p>
				<p align="center">
					<input type="button" value="+ Conceptos" onclick="vermasconcepto(<?php echo $LIMITECONCEPTO?>)"/>
					<input type="button" value="- Conceptos" onclick="vermenosconcepto()"/>
					<input size="1" style="display: none" type="text" value="1" id="conceptoaver" name="conceptoaver"/>
				</p>
			</div>
		</div>
		<table border="1" style="width: 900px; text-align: center">
			<thead>
				<tr>
					<th>CONCEPTO</th>
					<th>TIPO</th>
					<th>IMPORTE</th>
				</tr>
			</thead>
			<tbody>
		<?php	for ($i = 1; $i<=$LIMITECONCEPTO; $i++) {
					$display = 'style="display: none"';
					if ($i == 1) { $display = ""; } ?>
					<tr <?php echo $display?> id="datos<?php echo $i?>">
						<td><textarea name="concepto<?php echo $i?>" id="concepto<?php echo $i?>" rows="2" cols="100"></textarea></td>
						<td>
							<select name="tipo<?php echo $i ?>" id="tipo<?php echo $i ?>">
								<option value="C" selected="selected">C</option>
								<option value="D">D</option>
							</select>
						</td>
						<td><input autocomplete="off" name="importe<?php echo $i?>" id="importe<?php echo $i?>" size="10" style="text-align: center; font-weight: bold;" onchange="verificarImporte(this)"/></td>
					</tr>
			<?php } ?>
			</tbody>	
		</table>
		<p><input type="submit" value="Guardar Liquidacion" /></p>
	</form>
</div>
</body>
</html>
