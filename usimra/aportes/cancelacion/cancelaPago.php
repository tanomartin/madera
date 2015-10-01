<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");
if (isset($_GET['cuit'])) {
	$cuit = $_GET['cuit'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Cancelaci&oacute;n Manual de Aportes :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechapago").mask("99-99-9999");
	$("#fecharemesa").mask("99-99-9999");
	$("#fecharemito").mask("99-99-9999");
});
$(document).ready(function(){
	$("#codigobarra").attr('disabled', true);
	$("#verificacodigobarra").attr('disabled', true);
	$("#ddjjvalidada").val('0');
	$("#apor060").attr('readonly', true);
	$("#apor060").css({"background-color": "#cccccc"});
	$("#nocubre060").attr('disabled', true);
	$("#nocubre060").attr('checked',false);
	$("#apor100").attr('readonly', true);
	$("#apor100").css({"background-color": "#cccccc"});
	$("#nocubre100").attr('disabled', true);
	$("#nocubre100").attr('checked',false);
	$("#apor150").attr('readonly', true);
	$("#apor150").css({"background-color": "#cccccc"});
	$("#nocubre150").attr('disabled', true);
	$("#nocubre150").attr('checked',false);
	$("#montorecargo").attr('readonly', true);
	$("#montorecargo").css({"background-color": "#cccccc"});
	$("#montopagado").attr('readonly', true);
	$("#montopagado").css({"background-color": "#cccccc"});
	$("#cancelapago").attr('disabled', true);
	$.ajax({
		type: "POST",
		dataType: "html",
		url: "buscaAnios.php",
	}).done(function(respuesta){
		$("#anopago").html(respuesta);
	});
	$("#poseebarra").change(function(){
		var poseebarra = $(this).val();
		if(poseebarra == "1") {
			var mensaje = '';
			$('#msgcodigobarra').html(mensaje);
			$("#msgcodigobarra").css({"color": "#000000"});
			$("#codigobarra").attr('disabled', false);
			$("#anopago").attr('disabled', true);
			$("#mespago").attr('disabled', true);
			$("#cantidadpersonal").attr('readonly', true);
			$("#cantidadpersonal").css({"background-color": "#cccccc"});
			$("#remuneraciones").attr('readonly', true);
			$("#remuneraciones").css({"background-color": "#cccccc"});
			$("#nocubre060").attr('disabled', true);
			$("#nocubre060").attr('checked',false);
			$("#nocubre100").attr('disabled', true);
			$("#nocubre100").attr('checked',false);
			$("#nocubre150").attr('disabled', true);
			$("#nocubre150").attr('checked',false);
			$("#montorecargo").attr('readonly', true);
			$("#montorecargo").css({"background-color": "#cccccc"});
			$("#selectCuenta").attr('disabled', true);
			$("#cancelapago").attr('disabled', true);
		} else {
			$("#codigobarra").attr('disabled', true);
			$("#codigobarra").val('');
			$("#verificacodigobarra").attr('disabled', true);
			var mensaje = '';
			$('#msgcodigobarra').html(mensaje);
			$("#msgcodigobarra").css({"color": "#000000"});
			$("#anopago").attr('disabled', false);
			$("#mespago").attr('disabled', false);
			$("#cantidadpersonal").attr('readonly', false);
			$("#cantidadpersonal").css({"background-color": "#ffffff"});
			$("#remuneraciones").attr('readonly', false);
			$("#remuneraciones").css({"background-color": "#ffffff"});
			$("#nocubre060").attr('disabled', false);
			$("#nocubre060").attr('checked',false);
			$("#nocubre100").attr('disabled', false);
			$("#nocubre100").attr('checked',false);
			$("#nocubre150").attr('disabled', false);
			$("#nocubre150").attr('checked',false);
			$("#montorecargo").attr('readonly', false);
			$("#montorecargo").css({"background-color": "#ffffff"});
			$("#selectCuenta").attr('disabled', false);
			$("#cancelapago").attr('disabled', false);
		}
	});
	$("#codigobarra").change(function(){
		var mensaje = '';
		$('#msgcodigobarra').html(mensaje);
		$("#msgcodigobarra").css({"color": "#000000"});
		var codigobarra = $(this).val();
		if(codigobarra!="") {
			if($("#codigobarra").val().length == 30) {
				if(esEnteroPositivo(codigobarra)) {
					var convenio = codigobarra.substring(0,4);
					if(convenio == '3617') {
						var cuitphp = "<?php echo $cuit;?>";
						var cuitjvs = codigobarra.substring(4,15);
						if(cuitphp == cuitjvs) {
							var idboljvs = codigobarra.substring(15,29);
							var dverijvs = codigobarra.substring(29,30);
							$.ajax({
								type: "POST",
								dataType: "json",
								url: "controlDVerificador.php",
								data: {convenio:convenio,cuitjvs:cuitjvs,idboljvs:idboljvs},
							}).done(function(respuesta){
								var dveriaja = respuesta;
								if(dverijvs==dveriaja) {
									var mensaje = 'El c&oacute;digo de barra esta correctamente conformado. Por favor valide la DDJJ.';
									$('#msgcodigobarra').html(mensaje);
									$("#msgcodigobarra").css({"color": "#003399"});
									$("#verificacodigobarra").attr('disabled', false);
									$("#verificacodigobarra").focus();
								} else {
									var mensaje = 'El ID de DDJJ y/o el d&iacute;gito verificador en el c&oacute;digo de barra son incorrectos.';
									$('#msgcodigobarra').html(mensaje);
									$("#msgcodigobarra").css({"color": "#990033"});
									$("#verificacodigobarra").attr('disabled', true);
									$("#verificacodigobarra").focus();
								}
							});
						} else {
							var mensaje = 'El C.U.I.T. en el c&oacute;digo de barra es incorrecto.';
							$('#msgcodigobarra').html(mensaje);
							$("#msgcodigobarra").css({"color": "#990033"});
							$("#verificacodigobarra").attr('disabled', true);
						}
					} else {
						var mensaje = 'El convenio en el c&oacute;digo de barra es incorrecto.';
						$('#msgcodigobarra').html(mensaje);
						$("#msgcodigobarra").css({"color": "#990033"});
						$("#verificacodigobarra").attr('disabled', true);
					}
				} else {
					var mensaje = 'El c&oacute;digo de barra debe contener solo numeros.';
					$('#msgcodigobarra').html(mensaje);
					$("#msgcodigobarra").css({"color": "#990033"});
					$("#verificacodigobarra").attr('disabled', true);
				}
			} else {
				var mensaje = 'El c&oacute;digo de barra debe contener 30 caracteres.';
				$('#msgcodigobarra').html(mensaje);
				$("#msgcodigobarra").css({"color": "#990033"});
				$("#verificacodigobarra").attr('disabled', true);
			}
		} else {
			$("#verificacodigobarra").attr('disabled', true);
		}
	});
	$("#verificacodigobarra").click(function(){
		var cuitbarra = $("#codigobarra").val().substring(4,15);
		var controlbarra = $("#codigobarra").val().substring(15,29);
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "verificaDDJJ.php",
			data: {cuitbarra:cuitbarra,controlbarra:controlbarra},
			beforeSend:msgWaitDDJJ,
		}).done(function(respuesta){
			if(respuesta.perano) {
				$("#poseebarra").attr('disabled', true);
				$("#codigobarra").attr('readonly', true);
				$("#codigobarra").css({"background-color": "#cccccc"});
				$("#verificacodigobarra").attr('disabled', true);
				var mensaje = 'La DDJJ asociada al c&oacute;digo de barra es v&aacute;lida. Corrobore la informaci&oacute;n antes de cancelar.';
				$('#msgcodigobarra').html(mensaje);
				$("#msgcodigobarra").css({"color": "#000000"});
				$("#ddjjvalidada").val('1');
				$("#anopago").remove();
				var $nuevoInputAnio = $("<input name='anopago' type='text' id='anopago' value='"+respuesta.perano+"' size='4'/>");
				$nuevoInputAnio.appendTo('#etiquetaanio');
				$("#anopago").attr('readonly', true);
				$("#anopago").css({"background-color": "#cccccc"});
				$("#mespago").remove();
				var $nuevoInputMes = $("<input name='mespago' type='text' id='mespago' value='"+respuesta.permes+"' size='1'/>");
				$nuevoInputMes.appendTo('#etiquetames');
				$("#mespago").attr('readonly', true);
				$("#mespago").css({"background-color": "#cccccc"});
				var $nuevoInputNombreMes = $("<input name='mesnombre' type='text' id='mesnombre' value='"+respuesta.mesnombre+"' size='20'/>");
				$nuevoInputNombreMes.appendTo('#etiquetanombremes');
				$("#mesnombre").attr('readonly', true);
				$("#mesnombre").css({"background-color": "#cccccc"});
				$("#cantidadpersonal").val(respuesta.nfilas);
				$("#remuneraciones").val(respuesta.remune);
				$("#apor060").val(respuesta.apo060);
				$("#nocubre060").attr('disabled', true);
				$("#nocubre060").attr('checked',false);
				$("#apor100").val(respuesta.apo100);
				$("#nocubre100").attr('disabled', true);
				$("#nocubre100").attr('checked',false);
				$("#apor150").val(respuesta.apo150);
				$("#nocubre150").attr('disabled', true);
				$("#nocubre150").attr('checked',false);
				$("#montorecargo").val(respuesta.recarg);
				$("#montopagado").val(respuesta.totapo);
				$("#observaciones").val(respuesta.observ);
				$("#selectCuenta").attr('disabled', false);
				$("#cancelapago").attr('disabled', false);
				$.unblockUI();
			} else {
				$("#ddjjvalidada").val('0');
				$("#codigobarra").attr('disabled', true);
				$("#codigobarra").val('');
				$("#verificacodigobarra").attr('disabled', true);
				var mensaje = 'La DDJJ asociada al c&oacute;digo de barra no existe.';
				$('#msgcodigobarra').html(mensaje);
				$("#msgcodigobarra").css({"color": "#990033"});
				$("#poseebarra option[value='']").prop('selected',true);
				$("#anopago").attr('disabled', false);
				$("#mespago").attr('disabled', false);
				$("#cantidadpersonal").attr('readonly', false);
				$("#cantidadpersonal").css({"background-color": "#ffffff"});
				$("#remuneraciones").attr('readonly', false);
				$("#remuneraciones").css({"background-color": "#ffffff"});
				$("#nocubre060").attr('disabled', false);
				$("#nocubre060").attr('checked',false);
				$("#nocubre100").attr('disabled', false);
				$("#nocubre100").attr('checked',false);
				$("#nocubre150").attr('disabled', false);
				$("#nocubre150").attr('checked',false);
				$.unblockUI();
			}
		});
	});
	$("#anopago").change(function(){
		var anio = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "html",
			url: "buscaMeses.php",
			data: {anio:anio},
		}).done(function(respuesta){
			$("#mespago").html(respuesta);
		});
	});
	$("#remuneraciones").change(function(){
		var monto = new Number($(this).val());
		if(!isNaN(monto) && monto > 0) {
			var calc060 = new Number(monto*0.006);
			var apor060 = calc060.toFixed(2);
			$("#apor060").val(apor060);
			var calc100 = new Number(monto*0.010);
			var apor100 = calc100.toFixed(2);
			$("#apor100").val(apor100);
			var calc150 = new Number(monto*0.015);
			var apor150 = calc150.toFixed(2);
			$("#apor150").val(apor150);
			$("#montorecargo").val('0.00');
			var recargo = new Number($("#montorecargo").val());
			var calctot = new Number((monto*0.006)+(monto*0.010)+(monto*0.015)+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
			$("#nocubre060").attr('disabled', false);
			$("#nocubre060").attr('checked',false);
			$("#nocubre100").attr('disabled', false);
			$("#nocubre100").attr('checked',false);
			$("#nocubre150").attr('disabled', false);
			$("#nocubre150").attr('checked',false);
			$("#montorecargo").attr("readonly", false);
			$("#montorecargo").css({"background-color": "#ffffff"});
		} else {
			$("#apor060").val('');
			$("#apor100").val('');
			$("#apor150").val('');
			$("#montorecargo").val('');
			$("#montopagado").val('');
			$("#nocubre060").attr('disabled', true);
			$("#nocubre060").attr('checked',false);
			$("#nocubre100").attr('disabled', true);
			$("#nocubre100").attr('checked',false);
			$("#nocubre150").attr('disabled', true);
			$("#nocubre150").attr('checked',false);
			$("#montorecargo").attr('readonly', true);
			$("#montorecargo").css({"background-color": "#cccccc"});
		}
	});
	$("#nocubre060").change(function(){
		var monto = new Number($("#remuneraciones").val());
		var apor100 = new Number($("#apor100").val());
		var apor150 = new Number($("#apor150").val());
		var recargo = new Number($("#montorecargo").val());
		if($("#nocubre060").is(':checked')) {
			$("#apor060").val('0.00');
			var apor060 = new Number($("#apor060").val());
			var calctot = new Number(apor060+apor100+apor150+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
		} else {
			var calc060 = new Number(monto*0.006);
			var apor060 = calc060.toFixed(2);
			$("#apor060").val(apor060);
			var apor060 = new Number($("#apor060").val());
			var calctot = new Number(apor060+apor100+apor150+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
			$("#nocubre060").attr('disabled', false);
			$("#nocubre060").attr('checked',false);
		}
	});
	$("#nocubre100").change(function(){
		var monto = new Number($("#remuneraciones").val());
		var apor060 = new Number($("#apor060").val());
		var apor150 = new Number($("#apor150").val());
		var recargo = new Number($("#montorecargo").val());
		if($("#nocubre100").is(':checked')) {
			$("#apor100").val('0.00');
			var apor100 = new Number($("#apor100").val());
			var calctot = new Number(apor060+apor100+apor150+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
		} else {
			var calc100 = new Number(monto*0.010);
			var apor100 = calc100.toFixed(2);
			$("#apor100").val(apor100);
			var apor100 = new Number($("#apor100").val());
			var calctot = new Number(apor060+apor100+apor150+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
			$("#nocubre100").attr('disabled', false);
			$("#nocubre100").attr('checked',false);
		}
	});
	$("#nocubre150").change(function(){
		var monto = new Number($("#remuneraciones").val());
		var apor060 = new Number($("#apor060").val());
		var apor100 = new Number($("#apor100").val());
		var recargo = new Number($("#montorecargo").val());
		if($("#nocubre150").is(':checked')) {
			$("#apor150").val('0.00');
			var apor150 = new Number($("#apor150").val());
			var calctot = new Number(apor060+apor100+apor150+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
		} else {
			var calc150 = new Number(monto*0.015);
			var apor150 = calc150.toFixed(2);
			$("#apor150").val(apor150);
			var apor150 = new Number($("#apor150").val());
			var calctot = new Number(apor060+apor100+apor150+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
			$("#nocubre150").attr('disabled', false);
			$("#nocubre150").attr('checked',false);
		}
	});
	$("#montorecargo").change(function(){
		var apor060 = new Number($("#apor060").val());
		var apor100 = new Number($("#apor100").val());
		var apor150 = new Number($("#apor150").val());
		var recargo = new Number($(this).val());
		if(!isNaN(recargo) && recargo > 0) {
			var calctot = new Number(apor060+apor100+apor150+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
		} else {
			$("#montorecargo").val('0.00');
			var recargo = new Number($("#montorecargo").val());
			var calctot = new Number(apor060+apor100+apor150+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
		}
	});
	$("#fecharemesa").change(function(){
		var fecharemesa = $(this).val();
		var cuentaremesa = $("#selectCuentaRemesa").val();
		$.ajax({
			type: "POST",
			dataType: "html",
			url: "buscaRemesas.php",
			data: {fecharemesa:fecharemesa, cuentaremesa:cuentaremesa},
		}).done(function(respuesta){
			$("#selectRemesa").attr('disabled', false);
			$("#selectRemesa").html(respuesta);
		});
	});
	$("#selectRemesa").change(function(){
		var nroremesa = $(this).val();
		var cuentaremesa = $("#selectCuentaRemesa").val();
		var fecharemesa = $("#fecharemesa").val();
		$.ajax({
			type: "POST",
			dataType: "html",
			url: "buscaRemito.php",
			data: {nroremesa:nroremesa, cuentaremesa:cuentaremesa, fecharemesa:fecharemesa},
		}).done(function(respuesta){
			$("#selectRemito").attr('disabled', false);
			$("#selectRemito").html(respuesta);
		});
	});
	$("#fecharemito").change(function(){
		var fecharemito = $(this).val();
		var cuentaremito = $("#selectCuentaRemito").val();
		$.ajax({
			type: "POST",
			dataType: "html",
			url: "buscaRemitoSuelto.php",
			data: {fecharemito:fecharemito, cuentaremito:cuentaremito},
		}).done(function(respuesta){
			$("#selectRemitoSuelto").attr('disabled', false);
			$("#selectRemitoSuelto").html(respuesta);
		});
	});
});

function msgWaitDDJJ() {
  $.blockUI({ message: "<h1>Verificando DDJJ.<br>Aguarde por favor...</h1>" });
};

//REMITOS Y REMESAS

function LogicaHabilitaDocu(cuentaBoleta){
	document.forms.formularioCancelaPago.selectCuentaRemesa.disabled = true;
	document.forms.formularioCancelaPago.selectCuentaRemesa.selectedIndex = '0';
	limpiarFechaRemesa();
	limpiarRemesas();
	document.forms.formularioCancelaPago.selectCuentaRemito.disabled = true;
	document.forms.formularioCancelaPago.selectCuentaRemito.selectedIndex = '0';
	limpiarFechaRemito();
	limpiarRemitoSuelto();
	if (cuentaBoleta != 0) {
		document.forms.formularioCancelaPago.selectCuentaRemesa.disabled = false;
		document.forms.formularioCancelaPago.selectCuentaRemito.disabled = false;
	} 
}

function limpiarFechaRemesa(){
	document.forms.formularioCancelaPago.fecharemesa.value = "";
	document.forms.formularioCancelaPago.fecharemesa.disabled = true;
}

function limpiarFechaRemito(){
	document.forms.formularioCancelaPago.fecharemito.value = "";
	document.forms.formularioCancelaPago.fecharemito.disabled = true;
}

function limpiarRemesas(){
	document.forms.formularioCancelaPago.selectRemesa.length = 0;
	document.forms.formularioCancelaPago.selectRemito.length = 0;
	document.forms.formularioCancelaPago.selectRemesa.disabled = true;
	document.forms.formularioCancelaPago.selectRemito.disabled = true;
}

function limpiarRemitoSuelto(){
	document.forms.formularioCancelaPago.selectRemitoSuelto.length = 0;
	document.forms.formularioCancelaPago.selectRemitoSuelto.disabled = true;
}

function LogicaCargaRemesa(Cuenta) {
	document.forms.formularioCancelaPago.selectCuentaRemito.disabled = false;
	limpiarFechaRemesa();
	limpiarRemesas();
	if (Cuenta != 0) {
		document.forms.formularioCancelaPago.selectCuentaRemito.disabled = true;
		document.forms.formularioCancelaPago.fecharemesa.value = "";
		document.forms.formularioCancelaPago.fecharemesa.disabled = false;
	}
}

function LogicaCargaRemito(Cuenta) {
	document.forms.formularioCancelaPago.selectCuentaRemesa.disabled = false;
	limpiarFechaRemito();
	limpiarRemitoSuelto();
	if (Cuenta != 0) {
		document.forms.formularioCancelaPago.selectCuentaRemesa.disabled = true;
		document.forms.formularioCancelaPago.fecharemito.value = "";		
		document.forms.formularioCancelaPago.fecharemito.disabled = false;
	}
}

function validarFechaHabilitaBoton(fecha) {
	document.forms.formularioCancelaPago.selectRemesa.length = 0;
	document.forms.formularioCancelaPago.selectRemito.length = 0;
	if (!esFechaValida(fecha)){
		document.forms.formularioCancelaPago.selectRemito.disabled = true;
	} 
}

function limpiarSelect(){
	document.forms.formularioCancelaPago.selectRemesa.length = 0;
	document.forms.formularioCancelaPago.selectRemito.length = 0;
	document.forms.formularioCancelaPago.selectRemesa.disabled = true;
	document.forms.formularioCancelaPago.selectRemito.disabled = true;
}

function validarFechaHabilitaBotonRemitoSuelto(fecha) {
	document.forms.formularioCancelaPago.selectRemitoSuelto.length = 0;
	esFechaValida(fecha);
}

function limpiarSelectRemitoSuelto(){
	document.forms.formularioCancelaPago.selectRemitoSuelto.length = 0;
	document.forms.formularioCancelaPago.selectRemitoSuelto.disabled = true;
}

function logicaHabilitacion() {
	if (document.forms.formularioCancelaPago.selectCuentaRemesa.value != 0) {
		document.forms.formularioCancelaPago.selectCuentaRemito.disabled = true;
		document.forms.formularioCancelaPago.fecharemesa.disabled = false;
		if (document.forms.formularioCancelaPago.fecharemesa.value != "") {
			document.forms.formularioCancelaPago.selectRemesa.disabled = false;
			if (document.forms.formularioCancelaPago.selectRemesa.value != 0) {
				document.forms.formularioCancelaPago.selectRemito.disabled = false;
			}
		}
	}
	if (document.forms.formularioCancelaPago.selectCuentaRemito.value != 0) {
		document.forms.formularioCancelaPago.selectCuentaRemesa.disabled = true;
		document.forms.formularioCancelaPago.fecharemito.disabled = false;
		if (document.forms.formularioCancelaPago.fecharemito.value != "") {
			document.forms.formularioCancelaPago.selectRemitoSuelto.disabled = false;
		}
	}
}

//************************************************************************************//

function validar(formulario) {
	if(formulario.poseebarra.options[formulario.poseebarra.selectedIndex].value == "") {
		alert("Debe seleccionar si posee o no Código de Barra");
		document.getElementById("poseebarra").focus();
		return false;
	}
	if(formulario.poseebarra.options[formulario.poseebarra.selectedIndex].value == "1") {
		if(formulario.codigobarra.value == "") {
			alert("Debe ingresar el Código de Barra");
			document.getElementById("codigobarra").focus();
			return false;
		}
	}
	if(formulario.codigobarra.value != "") {
		if(formulario.ddjjvalidada.value == "") {
			alert("Debe validar la DDJJ");
			document.getElementById("codigobarra").focus();
			return false;
		}
		if(formulario.ddjjvalidada.value == "0") {
			alert("La DDJJ asociada al código de barra no existe o no es válida");
			document.getElementById("codigobarra").focus();
			return false;
		}
	}
	if(formulario.codigobarra.value == "") {
		if(formulario.ddjjvalidada.value == "0") {
			if(formulario.anopago.options[formulario.anopago.selectedIndex].value == "") {
				alert("Debe seleccionar el año del periodo a cancelar");
				document.getElementById("anopago").focus();
				return false;
			}
		}
	}
	if(formulario.codigobarra.value == "") {
		if(formulario.ddjjvalidada.value == "0") {
			if(formulario.mespago.options[formulario.mespago.selectedIndex].value == "") {
				alert("Debe seleccionar el mes del periodo a cancelar");
				document.getElementById("mespago").focus();
				return false;
			}
		}
	}
	if(formulario.fechapago.value == "") {
		alert("Debe ingresar la fecha de deposito del pago a cancelar");
		document.getElementById("fechapago").focus();
		return false;
	} else {
		if(!esFechaValida(formulario.fechapago.value)) {
			alert("La fecha de deposito ingresada no es válida");
			document.getElementById("fechapago").focus();
			return false;
		}
	}
	if(formulario.codigobarra.value == "") {
		if(formulario.ddjjvalidada.value == "0") {
			if(formulario.cantidadpersonal.value == "") {
				alert("Debe ingresar la cantidad de personal del pago a cancelar");
				document.getElementById("cantidadpersonal").focus();
				return false;
			} else {
				if(formulario.cantidadpersonal.value == "0") {
					alert("La cantidad de personal deber ser mayor que 0");
					document.getElementById("cantidadpersonal").focus();
					return false;
				}
			}
		}
	}
	if(formulario.codigobarra.value == "") {
		if(formulario.ddjjvalidada.value == "0") {
			if(formulario.remuneraciones.value == "") {
				alert("Debe ingresar las remuneraciones del pago a cancelar");
				document.getElementById("remuneraciones").focus();
				return false;
			}
		}
	}
	if(formulario.codigobarra.value == "") {
		if(formulario.ddjjvalidada.value == "0") {
			if(formulario.montorecargo.value == "") {
				alert("Debe especificar un valor de recargo");
				document.getElementById("montorecargo").focus();
				return false;
			}
		}
	}
	if(formulario.codigobarra.value == "") {
		if(formulario.ddjjvalidada.value == "0") {
			if(formulario.montopagado.value <= 0.00) {
				alert("El monto del total depositado no puede ser 0");
				document.getElementById("montopagado").focus();
				return false;
			}
		}
	}
	var cuentaBoleta = formulario.selectCuenta.value;
	var cuentaRemesa = formulario.selectCuentaRemesa.value;
	var cuentaRemito = formulario.selectCuentaRemito.value;
	var fechaRemesa = formulario.fecharemesa.value;
	var fechaRemito = formulario.fecharemito.value;
	var nroRemesa = formulario.selectRemesa.value;
	var nroRemito = formulario.selectRemito.value;
	var nroRemitoSuelto = formulario.selectRemitoSuelto.value;
	if(cuentaBoleta != 0) {
		if(cuentaRemesa == 0 && cuentaRemito == 0) {
			alert("Debe elegir cuenta de remesa o de remito suelto");
			document.body.style.cursor = 'default';
			return false;
		}
		if(cuentaRemesa != 0) {
			if(fechaRemesa == "") {
				alert("Debe ingresar la fecha de la remesa");
				document.getElementById("fecharemesa").focus();
				return false;
			} else {
				if(!esFechaValida(fechaRemesa)) {
					alert("La fecha de remesa ingresada no es válida");
					document.getElementById("fecharemesa").focus();
					return false;
				}
			}
			if(nroRemesa == 0) {
				alert("Debe seleccionar un nro. de remesa");
				document.getElementById("selectRemesa").focus();
				return false;
			}
			if(nroRemito == 0) {
				alert("Debe seleccionar un nro. de remito");
				document.getElementById("selectRemito").focus();
				return false;
			}
		}
		if(cuentaRemito != 0) {
			if(fechaRemito == "") {
				alert("Debe ingresar la fecha del remito suelto");
				document.getElementById("fecharemito").focus();
				return false;
			} else {
				if(!esFechaValida(fechaRemito)) {
					alert("La fecha del remito suelto ingresada no es válida");
					document.getElementById("fecharemito").focus();
					return false;
				}
			}
			if(nroRemitoSuelto == 0) {
			  	alert("Debe seleccionar un nro. de remito suelto");
				document.getElementById("selectRemitoSuelto").focus();
				return false;
			}
		}
	}
	$.blockUI({ message: "<h1>Registrando Cancelaci&oacute;n.<br>Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>
<body bgcolor="#B2A274">
	<div align="center">
		<input type="button" name="volver" value="Volver" onClick="location.href = 'listaPagos.php?cuit=<?php echo $cuit?>'" />
<?php 	
include($libPath."cabeceraEmpresaConsulta.php");
include($libPath."cabeceraEmpresa.php"); 
?>
		<h1>Cancelaci&oacute;n Manual de Aportes</h1>
	</div>
	<form id="formularioCancelaPago" name="formularioCancelaPago" method="post" action="guardaCancelaPago.php?cuit=<?php echo $cuit?>"  onSubmit="return validar(this)">
		<div align="center">
			<span><strong>Posee C&oacute;digo de Barra? :</strong>
				<select name="poseebarra" id="poseebarra">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<option title="Si" value="1">Si</option>
					<option title="No" value="0">No</option>
				</select>
			</span>
			<span><strong>C&oacute;digo de Barra :</strong>
				<input name="codigobarra" type="text" id="codigobarra" value="" size="30" maxlength="30"/>
			</span>
			<p></p>
			<strong><span id="msgcodigobarra" style="font-size:18px"></span></strong>
			<p></p>
			<span>
				<input type="button" id="verificacodigobarra" name="verificacodigobarra" value="Validar DDJJ"/>
			</span>
			<span>
				<input name="ddjjvalidada" type="text" id="ddjjvalidada" value="" size="1" style="visibility:hidden"/>
			</span>
			<p></p>
		</div>
		<div align="center">
			<span id="etiquetaanio"><strong>A&ntilde;o :</strong>
				<select name="anopago" id="anopago">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
				</select>
			</span>
			<span id="etiquetames"><strong>Mes :</strong>
				<select name="mespago" id="mespago">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
				</select>
			</span>
			<span id="etiquetanombremes"><strong></strong></span>
			<span><strong>Fecha de Dep&oacute;sito :</strong>
				<input name="fechapago" type="text" id="fechapago" value="" size="8"/>
			</span>
			<span><strong>Personal :</strong>
				<input name="cantidadpersonal" type="text" id="cantidadpersonal" value="" size="6" maxlength="5"/>
			</span>
		</div>
		<div align="center">
			<p></p>
			<table border="0">
			  <tr>
				<td><strong>Remuneraciones</strong></td>
				<td><input name="remuneraciones" type="text" id="remuneraciones" value="" size="14" maxlength="10"/></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td><strong>Aporte 0.60 %</strong></td>
				<td><input name="apor060" type="text" id="apor060" value="" size="14"/></td>
				<td><input name="nocubre060" type="checkbox" id="nocubre060"></td>
				<td><strong>No Cubre Aporte 0.60 %</strong></td>
			  </tr>
			  <tr>
				<td><strong>Aporte 1.00 %</strong></td>
				<td><input name="apor100" type="text" id="apor100" value="" size="14"/></td>
				<td><input name="nocubre100" type="checkbox" id="nocubre100"></td>
				<td><strong>No Cubre Aporte 1.00 %</strong></td>
			  </tr>
			  <tr>
				<td><strong>Aporte 1.50 %</strong></td>
				<td><input name="apor150" type="text" id="apor150" value="" size="14"/></td>
				<td><input name="nocubre150" type="checkbox" id="nocubre150"></td>
				<td><strong>No Cubre Aporte 1.50 %</strong></td>
			  </tr>
			  <tr>
				<td><strong>Recargo</strong></td>
				<td><input name="montorecargo" type="text" id="montorecargo" value="" size="14"/></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td><strong>Total Depositado</strong></td>
				<td><input name="montopagado" type="text" id="montopagado" value="" size="14"/></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			</table>
			<p></p>
		</div>
		<div align="center">
			<span><strong>Observaciones :</strong>
				<textarea name="observaciones" cols="85" rows="3" id="observaciones"></textarea>
			</span>
			<p></p>
		</div>
		<div align="center">	
		<h3>Información Bancaria</h3>
		<p>Cuenta de la Boleta
        <label>
        <select disabled="disabled" name="selectCuenta"  id="selectCuenta" onChange="LogicaHabilitaDocu(document.forms.formularioCancelaPago.selectCuenta[selectedIndex].value)" >
		          <option value=0 selected="selected">Seleccione una Cuenta </option>
		          <?php 
					$query="select * from cuentasusimra";
					$result=mysql_query($query,$db);
					while ($rowcuentas=mysql_fetch_array($result)) { ?>
							 <option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>			
		            <?php } ?>
       </select>
       </label>
     </p>
	
	   <table width="834" border="0">
       <tr>
         <td colspan="2"><div align="center"><strong>REMESA </strong></div></td>
         <td colspan="2"><div align="center"><strong>REMITO SUELTO </strong></div></td>
       </tr>
       <tr>
         <td width="142"><div align="right">Cuenta de la Remesa
         </div></td>
         <td width="263">  
		 	<select disabled="disabled" name="selectCuentaRemesa" id="selectCuentaRemesa" onChange="LogicaCargaRemesa(document.forms.formularioCancelaPago.selectCuentaRemesa[selectedIndex].value);">
		          <option value=0 selected="selected">Seleccione Cuenta de Remesa </option>
		          <?php 
					$query="select * from cuentasusimra";
					$result=mysql_query($query,$db);
					while ($rowcuentas=mysql_fetch_array($result)) { ?>
		               <option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>	 
			<?php } ?>
           </select>
	     </td>
         <td width="143">
         <div align="right">Cuenta Remito Suelto</div></td>
         <td width="268">	
		    <select disabled="disabled" name="selectCuentaRemito" id="selectCuentaRemito" onChange="LogicaCargaRemito(document.forms.formularioCancelaPago.selectCuentaRemesa[selectedIndex].value);">
		          <option value=0 selected="selected">Seleccione Cuenta de Remito </option>
		          <?php 
					$query="select * from cuentasusimra";
					$result=mysql_query($query,$db);
					while ($rowcuentas=mysql_fetch_array($result)) {  ?>
				  		<option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
			<?php	} ?>
         </select></td>
       </tr>
       <tr>
         <td>
           <div align="right">Fecha de la Remesa</div></td>
         <td><label>
           <input name="fecharemesa" type="text" id="fecharemesa" size="8" disabled="disabled" value="" onFocus="limpiarSelect()">
           </label></td>
         <td>
           <div align="right">Fecha Remito Suelto</div></td>
         <td> <input name="fecharemito" type="text" id="fecharemito" size="8" disabled="disabled" value="" onFocus="limpiarSelectRemitoSuelto()">
       </tr>
       <tr>
		 <td><div align="right">Nro Remesa</div></td>
         <td><select name="selectRemesa" id="selectRemesa" disabled="disabled"> 
		  </select>
         <td>
           <div align="right">Nro Remito Suelto</div></td>
         <td><select name="selectRemitoSuelto" id="selectRemitoSuelto" disabled="disabled"> 
		  </select></td>
       </tr>
       <tr>
         <td>
          <div align="right">Nro Remito</div></td>
         <td>
         	<select name="selectRemito" id="selectRemito" disabled="disabled">
         	</select>
         </td>
         <td colspan="2">&nbsp;</td>
       </tr>
      </table>
		</div>
		<div align="center">
			<p><input class="nover" type="submit" id="cancelapago" name="cancelapago" value="Cancelar Período"/></p>
		</div>
	</form>
</body>
</html>
