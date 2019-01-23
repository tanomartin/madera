<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$nroorden = $_GET['nroorden'];

$sqlOrdenAImputar = "SELECT *, DATE_FORMAT(o.fecha, '%d/%m/%Y') as fecha, p.dirigidoa as beneficiario 
						FROM ordennmcabecera o, prestadoresnm p 
						WHERE o.nroorden = $nroorden and o.codigoprestador = p.codigo";
$resOrdenAImputar = mysql_query($sqlOrdenAImputar,$db);
$rowOrdenAImputar = mysql_fetch_array($resOrdenAImputar);

$sqlDetalleAImputar = "SELECT * FROM ordennmdetalle o WHERE o.nroorden = $nroorden";
$resDetalleAImputar = mysql_query($sqlDetalleAImputar,$db);
$canDetalleAImputar = mysql_num_rows($resDetalleAImputar);

$LIMITEIMPUTA = 5;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>.: Imputacion Ordenes de Pago :.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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

function vermasimputa(linea, limite) {
	var nombreImputa = "imputaaver"+linea;
	var imputaValue = document.getElementById(nombreImputa).value;
	if (imputaValue < limite) {
		imputaValue++

		var nombrecuenta = "impucuenta"+linea+"-"+imputaValue;
		var inputcuenta = document.getElementById(nombrecuenta);
		inputcuenta.style.display = "table-row";

		var nombreidcuenta = "idimpucuenta"+linea+"-"+imputaValue;
		var inputidcuenta = document.getElementById(nombreidcuenta);
		inputidcuenta.style.display = "table-row";
		inputidcuenta.style.backgroundColor  = "silver";

		var nombreafil = "impuafil"+linea+"-"+imputaValue;
		var inputafil = document.getElementById(nombreafil);
		inputafil.style.display = "table-row";

		var nombreanrofil = "nroafil"+linea+"-"+imputaValue;
		var inputanrofil = document.getElementById(nombreanrofil);
		inputanrofil.style.display = "table-row";
		inputanrofil.style.marginLeft = "4px";
		
		var nombreimporte = "impusaldo"+linea+"-"+imputaValue;
		var inputimporte = document.getElementById(nombreimporte);
		inputimporte.style.display = "table-row";
		
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

		var nombreidcuenta = "idimpucuenta"+linea+"-"+imputaValue;
		var inputidcuenta = document.getElementById(nombreidcuenta);
		inputidcuenta.value = "";
		inputidcuenta.style.display = "none";

		var nombreafil = "impuafil"+linea+"-"+imputaValue;
		var inputafil = document.getElementById(nombreafil);
		inputafil.value = "";
		inputafil.disabled = true;
		inputafil.style.display = "none";

		var nombredeleafil = "dele"+linea+"-"+imputaValue;
		var inputdele = document.getElementById(nombredeleafil);
		inputdele.value = "";
		inputdele.disabled = true;
		
		var nombrenroafil = "nroafil"+linea+"-"+imputaValue;
		var inputnroafil = document.getElementById(nombrenroafil);
		inputnroafil.value = "";
		inputnroafil.disabled = true;
		inputnroafil.style.display = "none";
		
		var nombreimporte = "impusaldo"+linea+"-"+imputaValue;
		var inputimporte = document.getElementById(nombreimporte);
		inputimporte.value = "";
		inputimporte.style.display = "none";

		imputaValue--
		document.getElementById(nombreImputa).value = imputaValue;
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

function cambioDatosPago(nroorden,tipocarga) {
	var formulario = document.getElementById("imputaOrdenNM");
	if (tipocarga == 'C') {
		var nropago = formulario.nropago.value;
		var tipo = formulario.tipo.value;
		if (nropago == "" || nropago == 0 || !esEnteroPositivo(nropago)) {
			alert("El Nro de Cheque o Transferencia es obligatorio y debe ser un numero entero positivo");
			formulario.nropago.focus();	
		} else {
			formulario.guardarInfoPago.disabled = true;
			var redireccion = "guardarDatosPagoOrdenNM.php?nroorden="+nroorden+"&nropago="+nropago+"&tipo="+tipo;
			location.href=redireccion;
		}
	} else {
		formulario.guardarInfoPago.disabled = true;
		var redireccion = "eliminarDatosPagoOrdenNM.php?nroorden="+nroorden;
		location.href=redireccion;
	}
}

function validar(formulario, limiteImputa) {
	if (formulario.nropago) {
		if (formulario.nropago.value == "" || formulario.nropago.value == 0 || !esEnteroPositivo(formulario.nropago.value)) {
			alert("El Nro de Cheque o Transferencia es obligatorio y debe ser un numero entero positivo");
			formulario.nropago.focus();
			return false;	
		}
	}
	if (formulario.codigocuentapago.value == "" || formulario.codigocuentapago.value == 0) {
		alert("El Nro de Cuenta es obligatorio, debe seleccionar una cuenta");
		formulario.cuentapago.focus();
		return false;	
	}
	
	var totalLineas = 0;
	var datosControl = document.getElementById("conceptoaver").value;
	for (var i=1; i <= datosControl; i++) {		
		var concepto = "concepto"+i;
		var conceptoValue = document.getElementById(concepto).value;
		var lineasConcepto = Math.ceil(conceptoValue.length / 58);

		var nombreImporte = "importe"+i;
		var importeValueConcepto = parseFloat(document.getElementById(nombreImporte).value);
		
		var nombreImputa = "imputaaver"+i;
		var valorImputaAver = document.getElementById(nombreImputa).value;

		var totalLineasImpu = 0;
		var totalPorConcepto = 0;
		for (var n=1; n <= valorImputaAver; n++) {		
			var nombreidcuenta = "idimpucuenta"+i+"-"+n;
			var nombrecuenta = "impucuenta"+i+"-"+n; 
			var inputidcuenta = document.getElementById(nombreidcuenta);
			if (inputidcuenta.value == "") {
				alert("La cuenta de imputacion contable es obligatoria");
				document.getElementById(nombrecuenta).focus();
				return false;
			}

			var nombreAfil = "impuafil"+i+"-"+n;
			var inputAfil =  document.getElementById(nombreAfil);
			if (!inputAfil.readOnly) {
				var inputNroAfil = "nroafil"+i+"-"+n;
				var inputNroAfil =  document.getElementById(inputNroAfil);
				if (inputNroAfil.value == "") {
					alert("La cuenta elegida necesita la carga de un afiliado");
					inputAfil.focus();
					return false;
				}
			}

			var nombreimporte = "impusaldo"+i+"-"+n;
			var inputimporte = document.getElementById(nombreimporte);
			if (inputimporte.value == "" || inputimporte.value == 0 || !isNumberPositivo(inputimporte.value) || inputimporte.value >= 10000000) {
				alert("El importe de la imputacion contable debe ser un numero positivo menor a 10 millones");
				inputimporte.focus();
				return false;
			} else {
				totalPorConcepto += parseFloat(inputimporte.value);
			}
			totalLineasImpu++
		}	
		if (totalPorConcepto != importeValueConcepto) {
			alert("La suma de los importes de imputar es diferente al total del Concepto");
			document.getElementById(nombreImporte).focus();
			return false;
		}
		totalLineas += Math.max(lineasConcepto, totalLineasImpu);
	}	
	if (totalLineas > 33) {
		alert("La cantidad total de lineas supera el limite por hoja.");
		return false;
	}

	for (var il = 1; il <= datosControl; il++) {
		var nombreImputa = "imputaaver"+il;
		var valorImputaAver = document.getElementById(nombreImputa).value;
		for (var nc = limiteImputa; nc > valorImputaAver; nc--) {
			var nombrecuenta = "impucuenta"+il+"-"+nc;
			var nombreidcuenta = "idimpucuenta"+il+"-"+nc;
			var inputDele = "dele"+il+"-"+nc;
			var nombreAfil = "impuafil"+il+"-"+nc;
			var inputNroAfil = "nroafil"+il+"-"+nc;
			var nombreimporte = "impusaldo"+il+"-"+nc;
			document.getElementById(nombrecuenta).disabled = true;
			document.getElementById(nombreidcuenta).disabled = true;
			document.getElementById(inputDele).disabled = true;
			document.getElementById(nombreAfil).disabled = true;
			document.getElementById(inputNroAfil).disabled = true;
			document.getElementById(nombreimporte).disabled = true;
		}
	}
	
	$.blockUI({ message: "<h1>Generando Orden de Pago... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}


$(document).ready(function(){
	$("#cuentapago").autocomplete({  
		source: function(request, response) {
			$.ajax({
				url: "buscaCuenta.php",
				dataType: "json",
				data: {getCuenta:request.term},
				success: function(data) {
					response(data);
				}
			});
		},
        minLength: 4,
		select: function(event, ui) {
			$("#codigocuentapago").val(ui.item.codigocuenta);
		},
	});

	cantidadConceptos = $('#conceptoaver').val()
	for (var c = 1; c <= cantidadConceptos; c++) {
		for (var i = 1; i < 6; i++ ) {
			var nombreBusqueda = "#impucuenta"+c+"-"+i;
			$(nombreBusqueda).autocomplete({  
				source: function(request, response) {
					$.ajax({
						url: "buscaCuentaImputa.php",
						dataType: "json",
						data: {getCuenta:request.term},
						success: function(data) {
							response(data);
						}
					});
				},
		        minLength: 4,
		        select: function(event, ui) {
			        var id = event.target.name.substring(10);  	
		        	var nombreId = "#idimpucuenta"+id;
			        $(nombreId).val(ui.item.codigocuenta);
			        var nomdele = "#dele"+id;
			        var nombene = "#impuafil"+id;
			        var nomnroa = "#nroafil"+id;
			        if (ui.item.pidebene == 1) {
			        	$(nomdele).attr("disabled", false);
			        	$(nomdele).val(ui.item.codidelega); 
			        	$(nombene).val(""); 	
			        	$(nombene).attr("readonly", false);  
			        	$(nombene).attr("disabled", false);
			        	$(nomnroa).val("");
			        	$(nomnroa).attr("disabled", false);
			        	$(nomnroa).css("background-color", "silver");
				    } else {
				    	$(nomdele).val("");
				    	$(nomdele).attr("disabled", true);
				    	$(nombene).attr("readonly", true);  
				    	$(nombene).attr("disabled", true);
				    	$(nomnroa).attr("disabled", true);	
				    	$(nomnroa).css("background-color", "");
				    }
				},
			});

			var nombreAfil = "#impuafil"+c+"-"+i;
			$(nombreAfil).autocomplete({  
				source: function(request, response) {
					var nameToId = ($(this)[0].element)[0].id;
					var id = nameToId.substring(8);
					var nombreDel = "#dele"+id;
					var codidelega = $(nombreDel).val();
					$.ajax({
						url: "buscaAfiliado.php",
						dataType: "json",
						data: {getAfiliado:request.term, codidelega:codidelega},
						success: function(data) {
							response(data);
						}
					});
				},
		        minLength: 4,
				select: function(event, ui) {
			        var id = event.target.name.substring(8);
			        var nombreAfil = "#nroafil"+id;
					$(nombreAfil).val(ui.item.nroafiliado);
				},
			});
			
		}
	}
});

function submitForm(limiteImputa) {
	var formulario = document.getElementById("imputaOrdenNM");
	if (validar(formulario, limiteImputa)) {
		formulario.submit();
	}
}

function limpiarIdPago(inputPago) {
	if (inputPago.value == "") {
		document.getElementById("codigocuentapago").value = "";
	}
}

function limpiarIdCuenta(inputCuenta) {
	var id = inputCuenta.name.substring(10);  	
	var nombreId = "idimpucuenta"+id;
	var nombredele = "dele"+id;
	if (inputCuenta.value == "") {
		document.getElementById(nombreId).value = "";
		document.getElementById(nombredele).value = "";
		document.getElementById(nombredele).disabled = true;
		
		var nombreafil = "impuafil"+id;
		var inputafil = document.getElementById(nombreafil);
		inputafil.value = "";
		inputafil.disabled = true;
		inputafil.readOnly = true;

		var nombrenroafil = "nroafil"+id;
		var inputanroafil = document.getElementById(nombrenroafil);
		inputanroafil.value = "";
		inputanroafil.disabled = true;
		inputanroafil.style.backgroundColor = "";
	}
}

function limpiarNroAfil(inputAfiliado) {
	var id = inputAfiliado.name.substring(8);  	
	var nombreAfil = "#nroafil"+id;
	if (inputAfiliado.value == "") {
		var nombrenroafil = "nroafil"+id;
		var inputanroafil = document.getElementById(nombrenroafil);
		inputanroafil.value = "";
		inputanroafil.backgroundColor = "silver";
	}
}


</script>
<style>
.ui-menu .ui-menu-item a{ font-size:10px; }
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'listadoImputaOrdenPagoNM.php'" /></p>
	<h3>Carga Datos del Pago Ordenes de Pago No Médicas </h3>
	<form id="imputaOrdenNM" name="imputaOrdenNM" method="post" action="guardarImputarOrdenNM.php">
		<input type="text" style="display: none" name="nroorden" id="nroorden" size="1" value="<?php echo $nroorden ?>"/>
		<div style="border-style: solid; border-width: 1px; width: 1000px;">
			<div style="text-align: left; width: 950px; ">
				<p>
					<b>Fecha: <?php echo $rowOrdenAImputar['fecha'] ?></b>
					<b style="float: right; font-size: x-large;">Nº <u style="color: maroon;"><?php echo $nroorden ?></u></b>
				</p>
				<p><b>Beneficiario: <?php echo $rowOrdenAImputar['beneficiario'] ?></b></p>
				<p><b>$: <?php echo number_format($rowOrdenAImputar['importe'],2,",",".") ?></b></p>
			</div>
		</div>
		<div style="border-style: solid; border-width: 1px; width: 1000px;">
			<div style="text-align: left; width: 950px; ">
     <?php if ($rowOrdenAImputar['nropago'] == NULL && $rowOrdenAImputar['tipopago'] == NULL) { ?>		
				<p>
					<b>Tipo Pago: 
						<input type="radio" id="tipo" name="tipo" value="T" checked="checked" /><label>Transferencia</label>
						<input type="radio" id="tipo" name="tipo" value="C" /><label>Cheque</label>
					</b>
				</p>
				<p>
					<b>Nro: <input type="text" name="nropago" id="nropago" size="25" /></b>
					<input type="button" value="CARGAR INFO FORMA DE PAGO" id="guardarInfoPago" name="guardarInfoPago" style="float: right;" onclick="cambioDatosPago(<?php echo $nroorden ?>,'C')"/>
				</p>
	 <?php  } else { ?>
	  			<p><b>Tipo Pago: <?php if ($rowOrdenAImputar['tipopago'] == "C") { echo "CHEQUE"; } else { echo "TRANSFERENCIA";} ?></b></p>
	  			<p>
	  				<b>Nro: <?php echo $rowOrdenAImputar['nropago'] ?></b>
	  				<input type="button" value="LIMPIAR INFO FORMA DE PAGO" id="guardarInfoPago" name="guardarInfoPago" style="float: right;" onclick="cambioDatosPago(<?php echo $nroorden ?>,'E')"/>
	  			</p>
	  <?php } ?>		
			</div>
		</div>
		<div style="border-style: solid; border-width: 1px; width: 1000px;">
			<h3>Información Imputación Contable</h3>
			<div style="text-align: left; width: 950px; ">
				<p>
					<b>IMP. CONT. PAGO: <input type="text" name="cuentapago" id="cuentapago" size="80" onfocusout="limpiarIdPago(this)"/></b>
					<input name="codigocuentapago" type="text" id="codigocuentapago" size="5" style="background-color: silver; text-align: center" readonly="readonly" />
				</p>
			</div>
		</div>
		<input size="1" style="display: none" type="text" value="<?php echo $canDetalleAImputar ?>" id="conceptoaver" name="conceptoaver"/>
<?php 	$i = 0;
		while ($rowDetalleAImputar = mysql_fetch_array($resDetalleAImputar)) { 
			$i++; ?>
			<table border="1" style="width: 1000px; text-align: center; margin-top: 20px">
				<tbody>
					<tr>
						<th width="570px" colspan="3">CONCEPTO</th>
						<th width="320px">TIPO</th>
						<th width="110px">IMPORTE</th>
					</tr>
					<tr>
						<td colspan="3">
							<?php echo $rowDetalleAImputar['detalle']?>
							<input type="text" value="<?php echo $rowDetalleAImputar['detalle']?>" style="display: none" name="concepto<?php echo $i?>" id="concepto<?php echo $i?>" />
						</td>
						<td><?php echo $rowDetalleAImputar['tipo']?></td>
						<td><input type="text" size="10" style="text-align: center; font-style:oblique; background-color: silver;" readonly="readonly" name="importe<?php echo $i?>" id="importe<?php echo $i?>" value="<?php echo $rowDetalleAImputar['importe'] ?>"/></td>
					</tr>
					<tr>
						<th width="60px"></th>
						<th width="460px">CUENTA</th>
						<th width="50px">ID</th>
						<th>AFILIADO</th>
						<th>IMPORTE</th>
					</tr>
					<tr>	
						<td style="vertical-align: top">
							<input type="button" value="+" onclick="vermasimputa(<?php echo $i ?>, <?php echo $LIMITEIMPUTA?>)"/>
							<input type="button" value="-" onclick="vermenosimputa(<?php echo $i ?>)"/>
							<input size="1" style="display: none" type="text" value="1" id="imputaaver<?php echo $i ?>" name="imputaaver<?php echo $i ?>"/>
						</td>
						<td style="text-align: center">
				   <?php for ($n = 1; $n<=$LIMITEIMPUTA; $n++) {
							$display = 'style="display: none"';
							if ($n == 1) { $display = ''; } ?>
							<input <?php echo $display?> name="impucuenta<?php echo $i."-".$n ?>" id="impucuenta<?php echo $i."-".$n ?>" size="58" onfocusout="limpiarIdCuenta(this)"/>	
				   <?php } ?>		
						</td>
						<td style="">
			      <?php for ($n = 1; $n<=$LIMITEIMPUTA; $n++) {
							$display = 'style="display: none"';
							if ($n == 1) { $display = ''; } ?>	
							<input <?php echo $display?> name="idimpucuenta<?php echo $i."-".$n ?>" id="idimpucuenta<?php echo $i."-".$n ?>" size="3" style="background-color: silver;" readonly="readonly"/>
			      <?php } ?>		
						</td>
						<td style="text-align: left;">
				   <?php for ($n = 1; $n<=$LIMITEIMPUTA; $n++) {
							$display = 'style="display: none"';
						 	if ($n == 1) { $display = ''; } ?>
							<input style="display: none;" disabled="disabled" name="dele<?php echo $i."-".$n ?>" id="dele<?php echo $i."-".$n ?>" size="2"  readonly="readonly"/>
							<input <?php echo $display?> disabled="disabled" name="impuafil<?php echo $i."-".$n ?>" id="impuafil<?php echo $i."-".$n ?>" size="28" onfocusout="limpiarNroAfil(this)" readonly="readonly"/>
							<input <?php echo $display?> disabled="disabled" name="nroafil<?php echo $i."-".$n ?>" id="nroafil<?php echo $i."-".$n ?>" size="4" readonly="readonly"/>
				   <?php } ?>
						</td>
						<td>
				 <?php	for ($n = 1; $n<=$LIMITEIMPUTA; $n++) {
							$display = 'style="display: none; text-align: center; font-weight: bold;"';
							if ($n == 1) { $display = 'style="text-align: center; font-weight: bold;"'; } ?>
							<input <?php echo $display?> name="impusaldo<?php echo $i."-".$n ?>" id="impusaldo<?php echo $i."-".$n?>" size="10" maxlength="10" onchange="verificarImporte(this)" autocomplete="off"/>
				  <?php } ?>
						</td>		
					</tr>
				</tbody>
			</table>
  <?php } ?>
		<p><input type="button" value="Guardar Imputación" onclick="submitForm(<?php echo $LIMITEIMPUTA ?>)" /></p>
	</form>
</div>
</body>
</html>