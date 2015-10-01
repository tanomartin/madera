<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");
if(isset($_GET['cuit']) && isset($_GET['mespago']) && isset($_GET['anopago']) && isset($_GET['nropago'])) {
	//var_dump($_GET);
	$cuit=$_GET['cuit'];
	$mespago=$_GET['mespago'];
	$anopago=$_GET['anopago'];
	$nropago=$_GET['nropago'];
	$poseebarra=0;
	$valapor060='0.00';
	$apor060=0;
	$valapor100='0.00';
	$apor100=0;	
	$valapor150='0.00';
	$apor150=0;
	$conciliapago=0;
	$conciliado=0;

	$sqlBuscaPago="SELECT s.*, p.descripcion AS mesnombre FROM seguvidausimra s, periodosusimra p WHERE s.cuit = '$cuit' AND s.anopago = '$anopago' AND s.mespago = '$mespago' AND s.nropago = '$nropago' AND s.anopago = p.anio AND s.mespago = p.mes";
	$resBuscaPago=mysql_query($sqlBuscaPago,$db);
    $rowBuscaPago=mysql_fetch_array($resBuscaPago);
	//var_dump($rowBuscaPago);
	if(strcmp($rowBuscaPago['codigobarra'],'')!=0) {
		$poseebarra=1;
	}

	$sqlBuscaApor060="SELECT importe FROM apor060usimra WHERE cuit = '$cuit' AND anopago = '$anopago' AND mespago = '$mespago' AND nropago = '$nropago'";
	$resBuscaApor060=mysql_query($sqlBuscaApor060,$db);
	if(mysql_num_rows($resBuscaApor060)!=0) {
    	$rowBuscaApor060=mysql_fetch_array($resBuscaApor060);
		//var_dump($rowBuscaApor060);
		$valapor060=$rowBuscaApor060['importe'];
		$apor060=1;
	}

	$sqlBuscaApor100="SELECT importe FROM apor100usimra WHERE cuit = '$cuit' AND anopago = '$anopago' AND mespago = '$mespago' AND nropago = '$nropago'";
	$resBuscaApor100=mysql_query($sqlBuscaApor100,$db);
	if(mysql_num_rows($resBuscaApor100)!=0) {
	    $rowBuscaApor100=mysql_fetch_array($resBuscaApor100);
		//var_dump($rowBuscaApor100);
		$valapor100=$rowBuscaApor100['importe'];
		$apor100=1;
	}

	$sqlBuscaApor150="SELECT importe FROM apor150usimra WHERE cuit = '$cuit' AND anopago = '$anopago' AND mespago = '$mespago' AND nropago = '$nropago'";
	$resBuscaApor150=mysql_query($sqlBuscaApor150,$db);
	if(mysql_num_rows($resBuscaApor150)!=0) {
    	$rowBuscaApor150=mysql_fetch_array($resBuscaApor150);
		//var_dump($rowBuscaApor150);
		$valapor150=$rowBuscaApor150['importe'];
		$apor150=1;
	}

	$sqlBuscaConcilia="SELECT nropago, cuentaboleta, cuentaremesa, DATE_FORMAT(fecharemesa, '%d-%m-%Y') AS fecharemesa, nroremesa, nroremitoremesa, cuentaremitosuelto, DATE_FORMAT(fecharemitosuelto, '%d-%m-%Y') AS fecharemitosuelto, nroremitosuelto, estadoconciliacion FROM conciliapagosusimra WHERE cuit = '$cuit' AND anopago = '$anopago' AND mespago = '$mespago' AND nropago = '$nropago'";
	$resBuscaConcilia=mysql_query($sqlBuscaConcilia,$db);
	if(mysql_num_rows($resBuscaConcilia)!=0) {
    	$rowBuscaConcilia=mysql_fetch_array($resBuscaConcilia);
		//var_dump($rowBuscaConcilia);
		$conciliapago=1;
		$conciliado=$rowBuscaConcilia['estadoconciliacion'];
	}
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
	$("#anopago").attr('readonly', true);
	$("#anopago").css({"background-color": "#cccccc"});
	$("#mesnombre").attr('readonly', true);
	$("#mesnombre").css({"background-color": "#cccccc"});
	var poseebarraphp = "<?php echo $poseebarra;?>";
	var conciliapagophp = "<?php echo $conciliapago;?>";
	var conciliadophp = "<?php echo $conciliado;?>";
	var cuentaboletaphp = "<?php echo $rowBuscaConcilia['cuentaboleta'];?>";
	var cuentaremesaphp = "<?php echo $rowBuscaConcilia['cuentaremesa'];?>";
	var cuentaremitosueltophp = "<?php echo $rowBuscaConcilia['cuentaremitosuelto'];?>";
	if(conciliapagophp==1) {
		$.ajax({
		type: "POST",
		dataType: "html",
		url: "buscaCuentas.php",
		data: {origen:1},
		}).done(function(respuesta){
			$("#selectCuenta").html(respuesta);
			$("#selectCuenta option[value='"+cuentaboletaphp+"']").prop('selected',true);
		});
		if(conciliadophp==1) {
			$("#selectCuenta").attr('disabled', true);
		} else {
			$("#selectCuenta").attr('disabled', false);
		}
		if(cuentaremesaphp!=0) {
			$.ajax({
			type: "POST",
			dataType: "html",
			url: "buscaCuentas.php",
			data: {origen:2},
			beforeSend:msgWaitRemesa,
			}).done(function(respuesta){
				$("#selectCuentaRemesa").html(respuesta);
				$("#selectCuentaRemesa option[value='"+cuentaremesaphp+"']").prop('selected',true);
				$("#fecharemesa").val('<?php echo $rowBuscaConcilia['fecharemesa'];?>');
				var cuentaremesa = $("#selectCuentaRemesa").val();
				var fecharemesa = $("#fecharemesa").val();
				$.ajax({
					type: "POST",
					dataType: "html",
					url: "buscaRemesas.php",
					data: {fecharemesa:fecharemesa, cuentaremesa:cuentaremesa},
				}).done(function(respuesta){
					$("#selectRemesa").html(respuesta);
					var remesaphp = "<?php echo $rowBuscaConcilia['nroremesa'];?>";
					$("#selectRemesa option[value='"+remesaphp+"']").prop('selected',true);
					var cuentaremesa = $("#selectCuentaRemesa").val();
					var fecharemesa = $("#fecharemesa").val();
					var nroremesa = $("#selectRemesa").val();
					$.ajax({
						type: "POST",
						dataType: "html",
						url: "buscaRemito.php",
						data: {nroremesa:nroremesa, cuentaremesa:cuentaremesa, fecharemesa:fecharemesa},
					}).done(function(respuesta){
						$("#selectRemito").html(respuesta);
						var remitoremesaphp = "<?php echo $rowBuscaConcilia['nroremitoremesa'];?>";
						$("#selectRemito option[value='"+remitoremesaphp+"']").prop('selected',true);
						$.unblockUI();
					});
				});
			});
			if(conciliadophp==1) {
				$("#selectCuentaRemesa").attr('disabled', true);
				$("#fecharemesa").attr('disabled', true);
				$("#selectRemesa").attr('disabled', true);
				$("#selectRemito").attr('disabled', true);
			} else {
				$("#selectCuentaRemesa").attr('disabled', false);
				$("#fecharemesa").attr('disabled', false);
				$("#selectRemesa").attr('disabled', false);
				$("#selectRemito").attr('disabled', false);
			}
		} else {
			if(conciliadophp==1) {
				$("#selectCuentaRemesa").attr('disabled', true);
				$("#fecharemesa").attr('disabled', true);
				$("#selectRemesa").attr('disabled', true);
				$("#selectRemito").attr('disabled', true);
			} else {
				$.ajax({
				type: "POST",
				dataType: "html",
				url: "buscaCuentas.php",
				data: {origen:2},
				}).done(function(respuesta){
					$("#selectCuentaRemesa").html(respuesta);
				});
				$("#selectCuentaRemesa").attr('disabled', false);
				$("#fecharemesa").attr('disabled', true);
				$("#selectRemesa").attr('disabled', true);
				$("#selectRemito").attr('disabled', true);
			}
		}
		if(cuentaremitosueltophp!=0) {
			$.ajax({
			type: "POST",
			dataType: "html",
			url: "buscaCuentas.php",
			data: {origen:3},
			beforeSend:msgWaitRemitoSuelto,
			}).done(function(respuesta){
				$("#selectCuentaRemito").html(respuesta);
				$("#selectCuentaRemito option[value='"+cuentaremitosueltophp+"']").prop('selected',true);
				$("#fecharemito").val('<?php echo $rowBuscaConcilia['fecharemitosuelto'];?>');
				var cuentaremito = $("#selectCuentaRemito").val();
				var fecharemito = $("#fecharemito").val();
				$.ajax({
					type: "POST",
					dataType: "html",
					url: "buscaRemitoSuelto.php",
					data: {fecharemito:fecharemito, cuentaremito:cuentaremito},
				}).done(function(respuesta){
					$("#selectRemitoSuelto").html(respuesta);
					var remitosueltophp = "<?php echo $rowBuscaConcilia['nroremitosuelto'];?>";
					$("#selectRemitoSuelto option[value='"+remitosueltophp+"']").prop('selected',true);
					$.unblockUI();
				});
			});
			if(conciliadophp==1) {
				$("#selectCuentaRemito").attr('disabled', true);
				$("#fecharemito").attr('disabled', true);
				$("#selectRemitoSuelto").attr('disabled', true);
			} else {
				$("#selectCuentaRemito").attr('disabled', false);
				$("#fecharemito").attr('disabled', false);
				$("#selectRemitoSuelto").attr('disabled', false);
			}
		} else {
			if(conciliadophp==1) {
				$("#selectCuentaRemito").attr('disabled', true);
				$("#fecharemito").attr('disabled', true);
				$("#selectRemitoSuelto").attr('disabled', true);
			} else {
				$.ajax({
				type: "POST",
				dataType: "html",
				url: "buscaCuentas.php",
				data: {origen:3},
				}).done(function(respuesta){
					$("#selectCuentaRemito").html(respuesta);
				});
				$("#selectCuentaRemito").attr('disabled', false);
				$("#fecharemito").attr('disabled', true);
				$("#selectRemitoSuelto").attr('disabled', true);
			}
		}
	} else {
		$.ajax({
		type: "POST",
		dataType: "html",
		url: "buscaCuentas.php",
		data: {origen:1},
		}).done(function(respuesta){
			$("#selectCuenta").html(respuesta);
		});
		$.ajax({
		type: "POST",
		dataType: "html",
		url: "buscaCuentas.php",
		data: {origen:2},
		}).done(function(respuesta){
			$("#selectCuentaRemesa").html(respuesta);
		});
		$.ajax({
		type: "POST",
		dataType: "html",
		url: "buscaCuentas.php",
		data: {origen:3},
		}).done(function(respuesta){
			$("#selectCuentaRemito").html(respuesta);
		});
		$("#fecharemesa").attr('disabled', true);
		$("#fecharemito").attr('disabled', true);
		$("#selectRemesa").attr('disabled', true);
		$("#selectRemitoSuelto").attr('disabled', true);
		$("#selectRemito").attr('disabled', true);
	}
	if(conciliadophp==1) {
		if(poseebarraphp==1) {
			$("#poseebarra option[value='1']").prop('selected',true);
		} else {
			$("#poseebarra option[value='0']").prop('selected',true);
		}
		$("#poseebarra").attr('disabled', true);
		$("#codigobarra").attr('disabled', true);
		$("#verificacodigobarra").attr('disabled', true);
		$("#ddjjvalidada").val('2');
		$("#cantidadpersonal").attr('readonly', true);
		$("#cantidadpersonal").css({"background-color": "#cccccc"});
		$("#remuneraciones").attr('readonly', true);
		$("#remuneraciones").css({"background-color": "#cccccc"});
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
	} else {
		var apor060php = "<?php echo $apor060;?>";
		var apor100php = "<?php echo $apor100;?>";
		var apor150php = "<?php echo $apor150;?>";
		if(poseebarraphp==1) {
			$("#poseebarra option[value='1']").prop('selected',true);
			$("#poseebarra").attr('disabled', true);
			$("#codigobarra").attr('readonly', true);
			$("#codigobarra").css({"background-color": "#cccccc"});
			$("#verificacodigobarra").attr('disabled', true);
			$("#ddjjvalidada").val('2');
			$("#cantidadpersonal").attr('readonly', true);
			$("#cantidadpersonal").css({"background-color": "#cccccc"});
			$("#remuneraciones").attr('readonly', true);
			$("#remuneraciones").css({"background-color": "#cccccc"});
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
		} else {
			$("#poseebarra option[value='0']").prop('selected',true);
			$("#codigobarra").attr('disabled', true);
			$("#codigobarra").val('');
			$("#verificacodigobarra").attr('disabled', true);
			$("#ddjjvalidada").val('0');
			var mensaje = '';
			$('#msgcodigobarra').html(mensaje);
			$("#msgcodigobarra").css({"color": "#000000"});
			$("#apor060").attr('readonly', true);
			$("#apor060").css({"background-color": "#cccccc"});
			if(apor060php==1) {
				$("#nocubre060").attr('disabled', false);
				$("#nocubre060").attr('checked',false);
			} else {
				$("#nocubre060").attr('disabled', false);
				$("#nocubre060").attr('checked',true);
			}
			$("#apor100").attr('readonly', true);
			$("#apor100").css({"background-color": "#cccccc"});
			if(apor100php==1) {
				$("#nocubre100").attr('disabled', false);
				$("#nocubre100").attr('checked',false);
			} else {
				$("#nocubre100").attr('disabled', false);
				$("#nocubre100").attr('checked',true);
			}
			$("#apor150").attr('readonly', true);
			$("#apor150").css({"background-color": "#cccccc"});
			if(apor150php==1) {
				$("#nocubre150").attr('disabled', false);
				$("#nocubre150").attr('checked',false);
			} else {
				$("#nocubre150").attr('disabled', false);
				$("#nocubre150").attr('checked',true);
			}
			$("#montopagado").attr('readonly', true);
			$("#montopagado").css({"background-color": "#cccccc"});
		}
		$("#poseebarra").change(function(){
			var poseebarra = $(this).val();
			if(poseebarra=="1") {
				var mensaje = '';
				$('#msgcodigobarra').html(mensaje);
				$("#msgcodigobarra").css({"color": "#000000"});
				$("#codigobarra").attr('disabled', false);
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
				$("#cancelapago").attr('disabled', true);
			} else {
				$("#codigobarra").attr('disabled', true);
				$("#codigobarra").val('');
				$("#verificacodigobarra").attr('disabled', true);
				var mensaje = '';
				$('#msgcodigobarra').html(mensaje);
				$("#msgcodigobarra").css({"color": "#000000"});
				$("#cantidadpersonal").attr('readonly', false);
				$("#cantidadpersonal").css({"background-color": "#ffffff"});
				$("#remuneraciones").attr('readonly', false);
				$("#remuneraciones").css({"background-color": "#ffffff"});
				$("#apor060").attr('readonly', true);
				$("#apor060").css({"background-color": "#cccccc"});
				if(apor060php==1) {
					$("#nocubre060").attr('disabled', false);
					$("#nocubre060").attr('checked',false);
				} else {
					$("#nocubre060").attr('disabled', false);
					$("#nocubre060").attr('checked',true);
				}
				$("#apor100").attr('readonly', true);
				$("#apor100").css({"background-color": "#cccccc"});
				if(apor100php==1) {
					$("#nocubre100").attr('disabled', false);
					$("#nocubre100").attr('checked',false);
				} else {
					$("#nocubre100").attr('disabled', false);
					$("#nocubre100").attr('checked',true);
				}
				$("#apor150").attr('readonly', true);
				$("#apor150").css({"background-color": "#cccccc"});
				if(apor150php==1) {
					$("#nocubre150").attr('disabled', false);
					$("#nocubre150").attr('checked',false);
				} else {
					$("#nocubre150").attr('disabled', false);
					$("#nocubre150").attr('checked',true);
				}
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
					var mensaje = 'La DDJJ asociada al c&oacute;digo de barra es v&aacute;lida. Corrobore la informaci&oacute;n antes de modificar.';
					$('#msgcodigobarra').html(mensaje);
					$("#msgcodigobarra").css({"color": "#000000"});
					$("#ddjjvalidada").val('1');
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
		$("#selectCuentaRemesa").change(function(){
			$("#selectCuentaRemito option[value='0']").prop('selected',true);
			$("#fecharemesa").val('');
			$("#fecharemesa").attr('disabled', false);
			$("#fecharemito").val('');
			$("#fecharemito").attr('disabled', true);
			$("#selectRemesa option[value='0']").prop('selected',true);
			$("#selectRemesa").attr('disabled', true);
			$("#selectRemitoSuelto option[value='0']").prop('selected',true);
			$("#selectRemitoSuelto").attr('disabled', true);
			$("#selectRemito option[value='0']").prop('selected',true);
			$("#selectRemito").attr('disabled', true);
			$("#fecharemesa").focus();
		});
		$("#selectCuentaRemito").change(function(){
			$("#selectCuentaRemesa option[value='0']").prop('selected',true);
			$("#fecharemesa").val('');
			$("#fecharemesa").attr('disabled', true);
			$("#fecharemito").val('');
			$("#fecharemito").attr('disabled', false);
			$("#selectRemesa option[value='0']").prop('selected',true);
			$("#selectRemesa").attr('disabled', true);
			$("#selectRemitoSuelto option[value='0']").prop('selected',true);
			$("#selectRemitoSuelto").attr('disabled', true);
			$("#selectRemito option[value='0']").prop('selected',true);
			$("#selectRemito").attr('disabled', true);
			$("#fecharemito").focus();
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
	}
});
function msgWaitRemesa()
{
  $.blockUI({ message: "<h1>Leyendo Datos de Remesa.<br>Aguarde por favor...</h1>" });
};
function msgWaitRemitoSuelto()
{
  $.blockUI({ message: "<h1>Leyendo Datos de Remito Suelto.<br>Aguarde por favor...</h1>" });
};
function msgWaitDDJJ()
{
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
	$.blockUI({ message: "<h1>Guardando Modificaci&oacute;n.<br>Aguarde por favor...</h1>" });
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
		<h1>Modificaci&oacute;n  Aporte</h1>
	</div>
	<form id="formularioModificaPago" name="formularioModificaPago" method="post" action="guardaModificaPago.php?cuit=<?php echo $cuit?>"  onSubmit="return validar(this)">
		<div align="center">
			<h3>Per&iacute;odo</h3>
			<span><strong>A&ntilde;o :</strong>
				<input name="anopago" type="text" id="anopago" value="<?php echo $rowBuscaPago['anopago']?>" size="4"/>
			</span>
			<span><strong>Mes :</strong>
				<input name="mesnombre" type="text" id="mesnombre" value="<?php echo $rowBuscaPago['mesnombre']?>" size="20"/>
			</span>
			<span>
				<input name="mespago" type="text" id="mespago" value="<?php echo $rowBuscaPago['mespago']?>" size="2" style="visibility:hidden"/>
			</span>
			<span>
				<input name="nropago" type="text" id="nropago" value="<?php echo $rowBuscaPago['nropago']?>" size="1" style="visibility:hidden"/>
			</span>
			<p></p>
			<hr size="4">
		</div>
		<div align="center">
			<p></p>
			<span><strong>Posee C&oacute;digo de Barra? :</strong>
				<select name="poseebarra" id="poseebarra">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<option title="Si" value="1">Si</option>
					<option title="No" value="0">No</option>
				</select>
			</span>
		  <span><strong>C&oacute;digo de Barra :</strong>
				<input name="codigobarra" type="text" id="codigobarra" value="<?php echo $rowBuscaPago['codigobarra']?>" size="30" maxlength="30"/>
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
			<span><strong>Fecha de Dep&oacute;sito :</strong>
				<input name="fechapago" type="text" id="fechapago" value="<?php echo invertirFecha($rowBuscaPago['fechapago'])?>" size="8"/>
			</span>
			<span><strong>Personal :</strong>
				<input name="cantidadpersonal" type="text" id="cantidadpersonal" value="<?php echo $rowBuscaPago['cantidadpersonal']?>" size="6" maxlength="5"/>
			</span>
		</div>
		<div align="center">
			<p></p>
			<table border="0">
			  <tr>
				<td><strong>Remuneraciones</strong></td>
				<td><input name="remuneraciones" type="text" id="remuneraciones" value="<?php echo $rowBuscaPago['remuneraciones']?>" size="14" maxlength="10"/></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td><strong>Aporte 0.60 %</strong></td>
				<td><input name="apor060" type="text" id="apor060" value="<?php echo $valapor060?>" size="14"/></td>
				<td><input name="nocubre060" type="checkbox" id="nocubre060"></td>
				<td><strong>No Cubre Aporte 0.60 %</strong></td>
			  </tr>
			  <tr>
				<td><strong>Aporte 1.00 %</strong></td>
				<td><input name="apor100" type="text" id="apor100" value="<?php echo $valapor100?>" size="14"/></td>
				<td><input name="nocubre100" type="checkbox" id="nocubre100"></td>
				<td><strong>No Cubre Aporte 1.00 %</strong></td>
			  </tr>
			  <tr>
				<td><strong>Aporte 1.50 %</strong></td>
				<td><input name="apor150" type="text" id="apor150" value="<?php echo $valapor150?>" size="14"/></td>
				<td><input name="nocubre150" type="checkbox" id="nocubre150"></td>
				<td><strong>No Cubre Aporte 1.50 %</strong></td>
			  </tr>
			  <tr>
				<td><strong>Recargo</strong></td>
				<td><input name="montorecargo" type="text" id="montorecargo" value="<?php echo $rowBuscaPago['montorecargo']?>" size="14"/></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td><strong>Total Depositado</strong></td>
				<td><input name="montopagado" type="text" id="montopagado" value="<?php echo $rowBuscaPago['montopagado']?>" size="14"/></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			</table>
			<p></p>
		</div>
		<div align="center">
			<span><strong>Observaciones :</strong>
				<textarea name="observaciones" cols="85" rows="3" id="observaciones"><?php echo $rowBuscaPago['observaciones']?></textarea>
			</span>
			<p></p>
		</div>
<div align="center">	
		<h3>Información Bancaria</h3>
		<p>Cuenta de la Boleta
        <label>
        <select name="selectCuenta"  id="selectCuenta">
				<option title="Seleccione una Cuenta" value="">Seleccione una Cuenta</option>
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
		 	<select name="selectCuentaRemesa" id="selectCuentaRemesa">
		          <option title="Seleccione Cuenta de Remesa" value="0">Seleccione Cuenta de Remesa</option>
           </select>
	     </td>
         <td width="143">
         <div align="right">Cuenta Remito Suelto</div></td>
         <td width="268">	
		    <select name="selectCuentaRemito" id="selectCuentaRemito">
		          <option title="Seleccione Cuenta de Remito" value="0">Seleccione Cuenta de Remito</option>
         </select></td>
       </tr>
       <tr>
         <td>
           <div align="right">Fecha de la Remesa</div></td>
         <td><label>
           <input name="fecharemesa" type="text" id="fecharemesa" size="8" value="">
           </label></td>
         <td>
           <div align="right">Fecha Remito Suelto</div></td>
         <td> <input name="fecharemito" type="text" id="fecharemito" size="8" value="">
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
			<p><input class="nover" type="submit" id="cancelapago" name="cancelapago" value="Confirmar Modificaci&oacute;n"/></p>
		</div>
	</form>
</body>
</html>
