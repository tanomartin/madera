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
<title>.: Cancelaci&oacute;n Manual de Cuota Extraordinaria :.</title>
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
});
$(document).ready(function(){
	$("#codigobarra").attr('disabled', true);
	$("#verificacodigobarra").attr('disabled', true);
	$("#ddjjvalidada").val('0');
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
			$("#cantidadaportantes").attr('readonly', true);
			$("#cantidadaportantes").css({"background-color": "#cccccc"});
			$("#totalaporte").attr('readonly', true);
			$("#totalaporte").css({"background-color": "#cccccc"});
			$("#montorecargo").attr('readonly', true);
			$("#montorecargo").css({"background-color": "#cccccc"});
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
			$("#cantidadaportantes").attr('readonly', false);
			$("#cantidadaportantes").css({"background-color": "#ffffff"});
			$("#totalaporte").attr('readonly', false);
			$("#totalaporte").css({"background-color": "#ffffff"});
			$("#montorecargo").attr('readonly', false);
			$("#montorecargo").css({"background-color": "#ffffff"});
			$("#cancelapago").attr('disabled', false);
		}
	});
	$("#codigobarra").change(function(){
		var mensaje = '';
		$('#msgcodigobarra').html(mensaje);
		$("#msgcodigobarra").css({"color": "#000000"});
		var codigobarra = $(this).val();
		if(codigobarra!="") {
			if($("#codigobarra").val().length == 39) {
				if(esEnteroPositivo(codigobarra)) {
					var convenio = codigobarra.substring(0,4);
					if(convenio == '5866') {
						var cuitphp = "<?php echo $cuit;?>";
						var cuitjvs = codigobarra.substring(4,15);
						if(cuitphp == cuitjvs) {
							var idboljvs = codigobarra.substring(15,38);
							var dverijvs = codigobarra.substring(38,39);
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
				var mensaje = 'El c&oacute;digo de barra debe contener 39 caracteres.';
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
		var controlbarra = $("#codigobarra").val().substring(15,38);
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
				$("#cantidadaportantes").val(respuesta.nfilas);
				$("#totalaporte").val(respuesta.totapo);
				$("#montorecargo").val(respuesta.recarg);
				$("#montopagado").val(respuesta.totpag);
				$("#observaciones").val(respuesta.observ);
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
				$("#cantidadaportantes").attr('readonly', false);
				$("#cantidadaportantes").css({"background-color": "#ffffff"});
				$("#totalaporte").attr('readonly', false);
				$("#totalaporte").css({"background-color": "#ffffff"});
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
	$("#totalaporte").change(function(){
		var monto = new Number($(this).val());
		if(!isNaN(monto) && monto > 0) {
			$("#montorecargo").val('0.00');
			var recargo = new Number($("#montorecargo").val());
			var calctot = new Number(monto+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
			$("#montorecargo").attr("readonly", false);
			$("#montorecargo").css({"background-color": "#ffffff"});
		} else {
			$("#montorecargo").val('');
			$("#montopagado").val('');
			$("#montorecargo").attr('readonly', true);
			$("#montorecargo").css({"background-color": "#cccccc"});
		}
	});
	$("#montorecargo").change(function(){
		var monto = new Number($("#totalaporte").val());
		var recargo = new Number($(this).val());
		if(!isNaN(recargo) && recargo > 0) {
			var calctot = new Number(monto+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
		} else {
			$("#montorecargo").val('0.00');
			var recargo = new Number($("#montorecargo").val());
			var calctot = new Number(monto+recargo);
			var totpago = calctot.toFixed(2);
			$("#montopagado").val(totpago);
		}
	});
});

function msgWaitDDJJ() {
  $.blockUI({ message: "<h1>Verificando DDJJ.<br>Aguarde por favor...</h1>" });
};

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
			if(formulario.cantidadaportantes.value == "") {
				alert("Debe ingresar la cantidad de aportantes del pago a cancelar");
				document.getElementById("cantidadaportantes").focus();
				return false;
			} else {
				if(formulario.cantidadaportantes.value == "0") {
					alert("La cantidad de aportantes deber ser mayor que 0");
					document.getElementById("cantidadaportantes").focus();
					return false;
				}
			}
		}
	}
	if(formulario.codigobarra.value == "") {
		if(formulario.ddjjvalidada.value == "0") {
			if(formulario.totalaporte.value == "") {
				alert("Debe ingresar el total de aporte del pago a cancelar");
				document.getElementById("totalaporte").focus();
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
		<h1>Cancelaci&oacute;n Manual de Cuota Extraordinaria</h1>
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
				<input name="codigobarra" type="text" id="codigobarra" value="" size="39" maxlength="39"/>
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
				<input name="cantidadaportantes" type="text" id="cantidadaportantes" value="" size="6" maxlength="5"/>
			</span>
		</div>
		<div align="center">
			<p></p>
			<table border="0">
			  <tr>
				<td><strong>Total Aporte</strong></td>
				<td><input name="totalaporte" type="text" id="totalaporte" value="" size="14" maxlength="10"/></td>
			  </tr>
			  <tr>
				<td><strong>Recargo</strong></td>
				<td><input name="montorecargo" type="text" id="montorecargo" value="" size="14"/></td>
			  </tr>
			  <tr>
				<td><strong>Total Depositado</strong></td>
				<td><input name="montopagado" type="text" id="montopagado" value="" size="14"/></td>
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
			<p><input class="nover" type="submit" id="cancelapago" name="cancelapago" value="Cancelar Período"/></p>
		</div>
	</form>
</body>
</html>
