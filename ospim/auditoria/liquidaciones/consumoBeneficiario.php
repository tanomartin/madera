<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$idfactura = 0;
$idfacturabeneficiarios = 0;
if(isset($_GET)) {
	//var_dump($_GET);
	$idfactura = $_GET['idFactura'];
	$idfacturabeneficiario = $_GET['idFacturabeneficiario'];

	$sqlConsultaFactura = "SELECT * FROM facturas WHERE id = $idfactura";
	$resConsultaFactura = mysql_query($sqlConsultaFactura,$db);
	$rowConsultaFactura = mysql_fetch_array($resConsultaFactura);

	$sqlConsultaPrestador = "SELECT p.codigoprestador, p.nombre, p.cuit, p.personeria, t.descripcion FROM prestadores p, tipoprestador t WHERE p.codigoprestador = $rowConsultaFactura[idPrestador] AND p.personeria = t.id";
	$resConsultaPrestador = mysql_query($sqlConsultaPrestador,$db);
	$rowConsultaPrestador = mysql_fetch_array($resConsultaPrestador);

	$sqlConsultaNomenclador = "SELECT n.id, n.nombre, n.contrato FROM prestadornomenclador p, nomencladores n WHERE p.codigoprestador = $rowConsultaFactura[idPrestador] AND p.codigonomenclador = n.id";
	$resConsultaNomenclador = mysql_query($sqlConsultaNomenclador,$db);

	$sqlConsultaServicios = "SELECT t.descripcion FROM prestadorservicio p, tiposervicio t WHERE p.codigoprestador = $rowConsultaFactura[idPrestador] AND p.codigoservicio = t.codigoservicio";
	$resConsultaServicios = mysql_query($sqlConsultaServicios,$db);

	$sqlConsultaContratos = "SELECT * FROM cabcontratoprestador c WHERE codigoprestador = $rowConsultaFactura[idPrestador]";
	$resConsultaContratos = mysql_query($sqlConsultaContratos,$db);
	$numConsultaContratos = mysql_num_rows($resConsultaContratos);

	$sqlConsultaFacturasBeneficiarios = "SELECT * FROM facturasbeneficiarios WHERE id = $idfacturabeneficiario";
	$resConsultaFacturasBeneficiarios = mysql_query($sqlConsultaFacturasBeneficiarios,$db);
	$rowConsultaFacturasBeneficiarios = mysql_fetch_array($resConsultaFacturasBeneficiarios);
	if($rowConsultaFacturasBeneficiarios['tipoafiliado']==0) {
		$sqlConsultaTitular = "SELECT apellidoynombre, cuil, codidelega FROM titulares WHERE nroafiliado = $rowConsultaFacturasBeneficiarios[nroafiliado]";
		$resConsultaTitular = mysql_query($sqlConsultaTitular,$db);
		if(mysql_num_rows($resConsultaTitular)!=0) {
			$rowConsultaTitular = mysql_fetch_array($resConsultaTitular);
		} else {
			$sqlConsultaTitular = "SELECT apellidoynombre, cuil, codidelega FROM titularesdebaja WHERE nroafiliado = $rowConsultaFacturasBeneficiarios[nroafiliado]";
			$resConsultaTitular = mysql_query($sqlConsultaTitular,$db);
			$rowConsultaTitular = mysql_fetch_array($resConsultaTitular);
		}
		$descripcionTipo = 'Titular';
		$nombreBeneficiario = $rowConsultaTitular['apellidoynombre'];
		$cuilBeneficiario = $rowConsultaTitular['cuil'];
		$deleBeneficiario = $rowConsultaTitular['codidelega'];
	} else {
		$sqlConsultaFamiliar = "SELECT f.apellidoynombre, f.cuil, p.descrip, t.codidelega FROM familiares f, parentesco p, titulares t WHERE f.nroafiliado = $rowConsultaFacturasBeneficiarios[nroafiliado] AND f.nroorden = $rowConsultaFacturasBeneficiarios[nroorden] AND f.tipoparentesco = p.codparent AND f.nroafiliado = t.nroafiliado";
		$resConsultaFamiliar = mysql_query($sqlConsultaFamiliar,$db);
		if(mysql_num_rows($resConsultaFamiliar)!=0) {
			$rowConsultaFamiliar = mysql_fetch_array($resConsultaFamiliar);
		} else {
			$sqlConsultaFamiliar = "SELECT f.apellidoynombre, f.cuil, p.descrip, t.codidelega FROM familiaresdebaja f, parentesco p, titularesdebaja t WHERE f.nroafiliado = $rowConsultaFacturasBeneficiarios[nroafiliado] AND f.nroorden = $rowConsultaFacturasBeneficiarios[nroorden] AND f.tipoparentesco = p.codparent AND f.nroafiliado = t.nroafiliado";
			$resConsultaFamiliar = mysql_query($sqlConsultaFamiliar,$db);
			if(mysql_num_rows($resConsultaFamiliar)!=0) {
				$rowConsultaFamiliar = mysql_fetch_array($resConsultaFamiliar);
			} else {
				$sqlConsultaFamiliar = "SELECT f.apellidoynombre, f.cuil, p.descrip, t.codidelega FROM familiaresdebaja f, parentesco p, titulares t WHERE f.nroafiliado = $rowConsultaFacturasBeneficiarios[nroafiliado] AND f.nroorden = $rowConsultaFacturasBeneficiarios[nroorden] AND f.tipoparentesco = p.codparent AND f.nroafiliado = t.nroafiliado";
				$resConsultaFamiliar = mysql_query($sqlConsultaFamiliar,$db);
				$rowConsultaFamiliar = mysql_fetch_array($resConsultaFamiliar);
			}
		}
		$descripcionTipo = $rowConsultaFamiliar['descrip'];
		$nombreBeneficiario = $rowConsultaFamiliar['apellidoynombre'];
		$cuilBeneficiario = $rowConsultaFamiliar['cuil'];
		$deleBeneficiario = $rowConsultaFamiliar['codidelega'];
	}

	$sqlConsultaFacturasPrestacionesConsumo = "SELECT f.*, p.codigopractica FROM facturasprestaciones f, practicas p WHERE idFactura = $idfactura AND idFacturabeneficiario = $idfacturabeneficiario AND tipomovimiento = 1 AND f.idPractica = p.idpractica ORDER BY f.id DESC";
	$resConsultaFacturasPrestacionesConsumo = mysql_query($sqlConsultaFacturasPrestacionesConsumo,$db);

	$sqlConsultaFacturasPrestacionesCarencia = "SELECT f.*, p.codigopractica FROM facturasprestaciones f, practicas p WHERE idFactura = $idfactura AND idFacturabeneficiario = $idfacturabeneficiario AND tipomovimiento = 2 AND f.idPractica = p.idpractica ORDER BY f.id DESC";
	$resConsultaFacturasPrestacionesCarencia = mysql_query($sqlConsultaFacturasPrestacionesCarencia,$db);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Liquidaciones :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<link rel="stylesheet" href="/madera/lib/inputmask/css/inputmask.css"/>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/ui.datepicker-es.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$("#fechaprestacion").inputmask("date");
	$("#importeAcreditacion").inputmask('decimal', {digits: 2});
	$("#galenoHonorario").inputmask('decimal', {digits: 2});
	$("#galenoEspecialista").inputmask('decimal', {digits: 2});
	$("#galenoAyudante").inputmask('decimal', {digits: 2});
	$("#galenoAnestesista").inputmask('decimal', {digits: 2});
	$("#galenoGastos").inputmask('decimal', {digits: 2});
	$("#internacionLaboratorio").inputmask('decimal', {digits: 2});
	$("#internacionMedicamentos").inputmask('decimal', {digits: 2});
	$("#internacionDescartables").inputmask('decimal', {digits: 2});
	$("#internacionOtros").inputmask('decimal', {digits: 2});
	$("#valorCoseguro").inputmask('decimal', {digits: 2});
	$("#referenciaunitario").inputmask('decimal', {digits: 2});
	$("#referenciaacreditacion").inputmask('decimal', {digits: 2});
	$("#referenciaconformado").inputmask('decimal', {digits: 2});
	$("#referenciainternacion").inputmask('decimal', {digits: 2});
	$("#referenciacoseguro").inputmask('decimal', {digits: 2});
	$("#cantidad").inputmask('decimal', {digits: 3});
	$("#referenciatotal").inputmask('decimal', {digits: 2});
	$("#totalfacturado").inputmask('decimal', {digits: 2});
	$("#totaldebito").inputmask('decimal', {digits: 2});
	$("#totalcredito").inputmask('decimal', {digits: 2});
	$("#solicitadointegracion").inputmask('decimal', {digits: 2});
	$("#escuelaintegracion").inputmask('integer');
	$("#diastotal").inputmask('integer');
	$("#diascoronaria").inputmask('integer');
	$("#diasintensiva").inputmask('integer');
	$("#diasneonatologia").inputmask('integer');
	$("#buscaprestacion").attr('disabled', true);
	$("#efectorpractica").attr('disabled', true);
	$("#efectorprofesional").attr('disabled', true);
	$("#acreditacioncalidad").prop("checked",false);
	$("#acreditacioncalidad").attr('disabled', true);
	$("#importeAcreditacion").val('');
	$("#importeAcreditacion").attr('disabled', true);
	$("#gastosinternacion").prop("checked",false);
	$("#internacionLaboratorio").attr('disabled', true);
	$("#internacionMedicamentos").attr('disabled', true);
	$("#internacionDescartables").attr('disabled', true);
	$("#internacionOtros").attr('disabled', true);
	$("#incluyecoseguro").prop("checked",false);
	$("#incluyecoseguro").attr('disabled', true);
	$("#valorCoseguro").attr('disabled', true);
	$("#motivodebito").attr('disabled', true);
	$("#integracion").hide();
	$("#cancelaintegracion").prop("checked",false);
	$('#datosintegracion').hide();
	$("#escuelaintegracion").prop("checked",false);
	$("#escuelaintegracion").attr('disabled', true);
	$("#tipoescuelaintegracion option[value='']").prop('selected',true);
	$("#tipoescuelaintegracion").attr('disabled', true);
	$("#cueescuelaintegracion option[value='']").prop('selected',true);
	$("#cueescuelaintegracion").attr('disabled', true);
	$("#agregarprestacion").attr('disabled', true);
	$("#agregarcarencia").attr('disabled', true);
	var infoconformado = 'Info';
	$('#infoconformado').attr('title', infoconformado);
	var personeria = $("#personeria").val();
	$("#estadisticas").hide();
	$("#computoautomatico").hide();
	$("#computomanual").hide();
	$("#eligeestamb").hide();
	$("#eligeestint").hide();
	$("#tablaambulatoria").hide();
	$("#tablainternacion").hide();
	$("#fechaprestacion").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#fechaprestacion").change(function(){
		if($("#fechaprestacion").val()!='') {
			$("#buscaprestacion").attr('disabled', false);
		} else {
			$("#buscaprestacion").attr('disabled', true);
		}
	});
	$("#buscaprestacion").autocomplete({
		source: function(request, response) {
			var idprestador = $("#idprestador").val();
			var fechaprestacion = $("#fechaprestacion").val();
			var contrato = $("#contrato").val();
			var nomencladorresolucion = $("#nomencladorresolucion").val();
			$.ajax({
				url: "buscaPrestacion.php",
				dataType: "json",
				data: {getPrestacion:request.term,idPrestador:idprestador,fechaPrestacion:fechaprestacion,contratoPrestador:contrato,nomencladorResolucion:nomencladorresolucion},
				success: function(data) {
					response(data);
				}
			});
		},
        minLength: 3,
		select: function(event, ui) {
			var idpracticadevuelta = ui.item.idpractica;
			var galenodevuelto = ui.item.galeno;
			var referenciadevuelto = ui.item.valor;
			var integraciondevuelto = ui.item.integracion;
			var complejidaddevuelto = ui.item.complejidad;
			var internaciondevuelto = ui.item.internacion;
			var cosegurodevuelto = ui.item.coseguro;
			$("#idPractica").val(ui.item.idpractica);
			$("#esGaleno").val(ui.item.galeno);
			$("#esIntegracion").val(ui.item.integracion);
			$("#clasificacionComplejidad").val(ui.item.complejidad);
			$("#esInternacion").val(ui.item.internacion);
			$("#efectorpractica").val('');
			$("#efectorpractica").attr('disabled', true);
			$("#efectorprofesional").val('');
			$("#efectorprofesional").attr('placeholder','');
			$("#efectorprofesional").attr('disabled', true);
			$("#acreditacioncalidad").prop("checked",false);
			$("#acreditacioncalidad").attr('disabled', true);
			$("#importeAcreditacion").val('');
			$("#importeAcreditacion").attr('disabled', true);
			$("#calidadestablecimiento").css('display', 'none');
			$("#cantidad").val('');
			$("#referenciaunitario").val('');
			$("#referenciaacreditacion").val('');
			$("#referenciaconformado").val('');
			$("#referenciainternacion").val('');
			$("#referenciacoseguro").val('0.00');
			$("#referenciatotal").val('');
			$("#totalfacturado").val('');
			$("#totaldebito").val('');
			$("#totalcredito").val('');
			if(personeria > 2) {
				if(personeria == 3) {
					$("#efectorpractica").attr('placeholder','Ingrese un minimo de 4 caracteres para iniciar la busqueda del Profesional del Circulo que efectuo la prestacion');
				}
				if(personeria == 4) {
					$("#efectorpractica").attr('placeholder','Ingrese un minimo de 4 caracteres para iniciar la busqueda del Establecimiento de la Entidad Agrupadora que efectuo la prestacion');
				}
				$("#efectorpractica").attr('disabled', false);
			} else {
				$("#efectorpractica").attr('disabled', true);
			}
			infoconformado = infoconformado.replace(': Prestacion','');
			infoconformado = infoconformado.replace(': Conformacion Galeno','');
			infoconformado = infoconformado.replace(' + Acreditacion Calidad','');
			infoconformado = infoconformado.replace(' + Coseguro','');
			$('#infoconformado').attr('title', infoconformado);
			if(idpracticadevuelta==null) {
				$("#agregarcarencia").attr('disabled', false);
				$("#agregarprestacion").attr('disabled', true);
			} else {
				$("#referenciaunitario").val(ui.item.valor);
				$("#referenciaconformado").val(ui.item.valor);
				infoconformado = infoconformado+': Prestacion';
				$('#infoconformado').attr('title', infoconformado);
				if(referenciadevuelto==0.00) {
					$("#agregarcarencia").attr('disabled', false);
					$("#agregarprestacion").attr('disabled', true);
				} else {
					$("#agregarcarencia").attr('disabled', true);
					$("#agregarprestacion").attr('disabled', false);
				}
			}
			if(galenodevuelto==1) {
				infoconformado = infoconformado.replace(': Prestacion','');
				infoconformado = infoconformado+': Conformacion Galeno';
				$('#infoconformado').attr('title', infoconformado);
				$("#honorario").prop("checked",true);
				$("#galenoHonorario").val(ui.item.honorario);
				$("#especialista").prop("checked",true);
				$("#galenoEspecialista").val(ui.item.especialista);
				$("#ayudante").prop("checked",true);
				$("#galenoAyudante").val(ui.item.ayudante);
				$("#anestesista").prop("checked",true);
				$("#galenoAnestesista").val(ui.item.anestesista);
				$("#gastos").prop("checked",true);
				$("#galenoGastos").val(ui.item.gastos);
				$("#conformaciongaleno").css('display', '');
			} else {
				$('#infoconformado').attr('title', infoconformado);
				$("#honorario").prop("checked",false);
				$("#galenoHonorario").val('');
				$("#especialista").prop("checked",false);
				$("#galenoEspecialista").val('');
				$("#ayudante").prop("checked",false);
				$("#galenoAyudante").val('');
				$("#anestesista").prop("checked",false);
				$("#galenoAnestesista").val('');
				$("#gastos").prop("checked",false);
				$("#galenoGastos").val('');
				$("#conformaciongaleno").css('display', 'none');
			}
			if(internaciondevuelto==1) {
				$("#gastosinternacion").prop("checked",false);
				$("#gastosinternacion").attr('disabled', false);
				$("#internacionLaboratorio").attr('disabled', true);
				$("#internacionMedicamentos").attr('disabled', true);
				$("#internacionDescartables").attr('disabled', true);
				$("#internacionOtros").attr('disabled', true);
				$("#internacion").css('display', '');
			} else {
				$("#gastosinternacion").prop("checked",false);
				$("#gastosinternacion").attr('disabled', true);
				$("#internacionLaboratorio").attr('disabled', true);
				$("#internacionMedicamentos").attr('disabled', true);
				$("#internacionDescartables").attr('disabled', true);
				$("#internacionOtros").attr('disabled', true);
				$("#internacion").css('display', 'none');
			}
			if(cosegurodevuelto!=0.00) {
				infoconformado = infoconformado+' + Coseguro';
				$("#incluyecoseguro").prop("checked",false);
				$("#incluyecoseguro").attr('disabled', false);
				$("#valorCoseguro").val(cosegurodevuelto);
				$("#coseguro").css('display', '');
			} else {
				$("#incluyecoseguro").prop("checked",false);
				$("#incluyecoseguro").attr('disabled', true);
				$("#valorCoseguro").val('');
				$("#coseguro").css('display', 'none');
			}
			if(integraciondevuelto==1) {
				$("#cancelaintegracion").prop("checked",false);
				$("#integracion").show();
				$("#datosintegracion").hide();
				$("#solicitadointegracion").val('');
				$("#dependenciaintegracion").prop("checked",false);
				$("#tipoescuelaintegracion option[value='']").prop('selected',true);
				$("#cueescuelaintegracion").val('');
			} else {
				$("#cancelaintegracion").prop("checked",false);
				$("#integracion").hide();
				$("#datosintegracion").hide();
				$("#solicitadointegracion").val('');
				$("#dependenciaintegracion").prop("checked",false);
				$("#tipoescuelaintegracion option[value='']").prop('selected',true);
				$("#cueescuelaintegracion").val('');
			}
			$("#estadisticas").hide();
			$("#computoautomatico").hide();
			$("#computomanual").hide();
			$("#eligeestamb").hide();
			$("#eligeestint").hide();
			$("#tablaambulatoria").hide();
			$("#tablainternacion").hide();
			if(complejidaddevuelto==99) {
				$("#estamb").prop('checked', false);
				$("#estamb").prop('disabled', false);
				$("#amb1").prop('checked', false);
				$("#amb1").prop('disabled', false);
				$("#amb2").prop('checked', false);
				$("#amb2").prop('disabled', false);
				$("#amb21").prop('checked', false);
				$("#amb21").prop('disabled', false);
				$("#amb22").prop('checked', false);
				$("#amb22").prop('disabled', false);
				$("#amb3").prop('checked', false);
				$("#amb3").prop('disabled', false);
				$("#amb31").prop('checked', false);
				$("#amb31").prop('disabled', false);
				$("#amb32").prop('checked', false);
				$("#amb32").prop('disabled', false);
				$("#amb33").prop('checked', false);
				$("#amb33").prop('disabled', false);
				$("#amb4").prop('checked', false);
				$("#amb4").prop('disabled', false);
				$("#amb5").prop('checked', false);
				$("#amb5").prop('disabled', false);
				$("#amb6").prop('checked', false);
				$("#amb6").prop('disabled', false);
				$("#amb7").prop('checked', false);
				$("#amb7").prop('disabled', false);
				$("#amb8").prop('checked', false);
				$("#amb8").prop('disabled', false);
				$("#estint").prop('checked', false);
				$("#estint").prop('disabled', false);
				$("#int9").prop('checked', false);
				$("#int9").prop('disabled', false);
				$("#int10").prop('checked', false);
				$("#int10").prop('disabled', false);
				$("#int11").prop('checked', false);
				$("#int11").prop('disabled', false);
				$("#int12").prop('checked', false);
				$("#int12").prop('disabled', false);
				$("#int121").prop('checked', false);
				$("#int121").prop('disabled', false);
				$("#int122").prop('checked', false);
				$("#int122").prop('disabled', false);
				$("#int123").prop('checked', false);
				$("#int123").prop('disabled', false);
				$("#int124").prop('checked', false);
				$("#int124").prop('disabled', false);
				$("#diastotal").val('0');
				$("#diastotal").prop('disabled', false);
				$("#diascoronaria").val('0');
				$("#diascoronaria").prop('disabled', false);
				$("#diasintensiva").val('0');
				$("#diasintensiva").prop('disabled', false);
				$("#diasneonatologia").val('0');
				$("#diasneonatologia").prop('disabled', false);
				$("#int13").prop('checked', false);
				$("#int13").prop('disabled', false);
				$("#int141").prop('checked', false);
				$("#int141").prop('disabled', false);
				$("#int142").prop('checked', false);
				$("#int142").prop('disabled', false);
				$("#int143").prop('checked', false);
				$("#int143").prop('disabled', false);
				$("#estadisticas").hide();
				$("#computoautomatico").hide();
				$("#computomanual").hide();
				$("#eligeestamb").hide();
				$("#eligeestint").hide();
				$("#tablaambulatoria").hide();
				$("#tablainternacion").hide();
			} else {
				$("#estadisticas").show();
				if(complejidaddevuelto==0) {
					$("#computoautomatico").show();
					if(integraciondevuelto==1) {
						$("#calculoestadistico").prop("checked",false);
						$("#calculoestadistico").prop('disabled',false);
					} else {
						$("#calculoestadistico").prop("checked",true);
						$("#calculoestadistico").prop('disabled',false);
					}
					$("#computomanual").show();
					$("#eligeestamb").show();
					$("#eligeestint").show();
				} else {
					//Momentaneo hasta que clasifiquemos automatico realmente
					$("#computoautomatico").show();
					if(integraciondevuelto==1) {
						$("#calculoestadistico").prop("checked",false);
						$("#calculoestadistico").prop('disabled',false);
					} else {
						$("#calculoestadistico").prop("checked",true);
						$("#calculoestadistico").prop('disabled',false);
					}
					$("#computomanual").show();
					$("#eligeestamb").show();
					$("#eligeestint").show();
				}
			}
		}  
	});
	$("#efectorpractica").autocomplete({
		source: function(request, response) {
			var idprestador = $("#idprestador").val();
			var idpersoneria = $("#personeria").val();
			var fechaprestacion = $("#fechaprestacion").val();
			$.ajax({
				url: "buscaEfector.php",
				dataType: "json",
				data: {getPersoneria:request.term,idPrestador:idprestador,idPersoneria:personeria,fechaPrestacion:fechaprestacion},
				success: function(data) {
					response(data);
				}
			});
		},
        minLength: 4,
		select: function(event, ui) {
			var escirculo = ui.item.circulo;
			var tienecalidad = ui.item.calidad;
			$("#idEfector").val(ui.item.idefector);
			$("#establecimientoCirculo").val(ui.item.circulo);
			$("#establecimientoCalidad").val(ui.item.calidad);
			if(escirculo==1) {
				$("#efectorprofesional").attr('placeholder','El Establecimiento es un Circulo, por favor ingrese el profesional que efectuo la prestacion');
				$("#efectorprofesional").attr('disabled', false);
			} else {
				$("#efectorprofesional").val('');
				$("#efectorprofesional").attr('placeholder','');
				$("#efectorprofesional").attr('disabled', true);
			}
			if(tienecalidad==1) {
				infoconformado = infoconformado+' + Acreditacion Calidad';
				$('#infoconformado').attr('title', infoconformado);
				var valoracreditacion = (parseFloat($("#referenciaconformado").val()) * 1.07) -  parseFloat($("#referenciaconformado").val());
				$("#acreditacioncalidad").attr('disabled', false);
				$("#acreditacioncalidad").prop("checked",true);
				$("#importeAcreditacion").attr('disabled', false);
				$("#importeAcreditacion").val(valoracreditacion);
				$("#referenciaacreditacion").val(valoracreditacion);
				var valorconformado = parseFloat($("#referenciaconformado").val()) + parseFloat($("#referenciaacreditacion").val());
				$("#referenciaconformado").val(valorconformado);
				$("#calidadestablecimiento").css('display', '');
			} else {
				$("#acreditacioncalidad").prop("checked",false);
				$("#acreditacioncalidad").attr('disabled', true);
				$("#importeAcreditacion").val('');
				$("#importeAcreditacion").attr('disabled', true);
				$("#calidadestablecimiento").css('display', 'none');
			}
		}
	});
	$("#honorario").change(function(){
		if($("#honorario").prop('checked') ) {
			var nuevovalor = parseFloat($("#referenciaconformado").val()) + parseFloat($("#galenoHonorario").val());
		} else {
			var nuevovalor = parseFloat($("#referenciaconformado").val()) - parseFloat($("#galenoHonorario").val());
		}
		$("#referenciaconformado").val(nuevovalor);
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#especialista").change(function(){
		if($("#especialista").prop('checked') ) {
			var nuevovalor = parseFloat($("#referenciaconformado").val()) + parseFloat($("#galenoEspecialista").val());
		} else {
			var nuevovalor = parseFloat($("#referenciaconformado").val()) - parseFloat($("#galenoEspecialista").val());
		}
		$("#referenciaconformado").val(nuevovalor);
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#ayudante").change(function(){
		if($("#ayudante").prop('checked') ) {
			var nuevovalor = parseFloat($("#referenciaconformado").val()) + parseFloat($("#galenoAyudante").val());
		} else {
			var nuevovalor = parseFloat($("#referenciaconformado").val()) - parseFloat($("#galenoAyudante").val());
		}
		$("#referenciaconformado").val(nuevovalor);
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#anestesista").change(function(){
		if($("#anestesista").prop('checked') ) {
			var nuevovalor = parseFloat($("#referenciaconformado").val()) + parseFloat($("#galenoAnestesista").val());
		} else {
			var nuevovalor = parseFloat($("#referenciaconformado").val()) - parseFloat($("#galenoAnestesista").val());
		}
		$("#referenciaconformado").val(nuevovalor);
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#gastos").change(function(){
		if($("#gastos").prop('checked') ) {
			var nuevovalor = parseFloat($("#referenciaconformado").val()) + parseFloat($("#galenoGastos").val());
		} else {
			var nuevovalor = parseFloat($("#referenciaconformado").val()) - parseFloat($("#galenoGastos").val());
		}
		$("#referenciaconformado").val(nuevovalor);
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#gastosinternacion").change(function(){
		if($("#gastosinternacion").prop('checked') ) {
			infoconformado = infoconformado+' + Gastos Internacion';
			$('#infoconformado').attr('title', infoconformado);
			$("#internacionLaboratorio").val('0.00');
			$("#internacionLaboratorio").attr('disabled', false);
			$("#internacionMedicamentos").val('0.00');
			$("#internacionMedicamentos").attr('disabled', false);
			$("#internacionDescartables").val('0.00');
			$("#internacionDescartables").attr('disabled', false);
			$("#internacionOtros").val('0.00');
			$("#internacionOtros").attr('disabled', false);
			$("#referenciainternacion").val('0.00');
			var valorconformado = parseFloat($("#referenciaconformado").val()) + parseFloat($("#referenciainternacion").val());
			$("#referenciaconformado").val(valorconformado);
		} else {
			infoconformado = infoconformado.replace(' + Gastos Internacion','');
			$('#infoconformado').attr('title', infoconformado);
			$("#internacionLaboratorio").val('0.00');
			$("#internacionLaboratorio").attr('disabled', true);
			$("#internacionMedicamentos").val('0.00');
			$("#internacionMedicamentos").attr('disabled', true);
			$("#internacionDescartables").val('0.00');
			$("#internacionDescartables").attr('disabled', true);
			$("#internacionOtros").val('0.00');
			$("#internacionOtros").attr('disabled', true);
			var valorconformado = parseFloat($("#referenciaconformado").val()) - parseFloat($("#referenciainternacion").val());
			$("#referenciainternacion").val('0.00');
			$("#referenciaconformado").val(valorconformado);
		}
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#internacionLaboratorio").change(function(){
		if($("#internacionLaboratorio").val()=='') {
			$("#internacionLaboratorio").val('0.00');
		}
		var valorconformado = parseFloat($("#referenciaconformado").val()) - parseFloat($("#referenciainternacion").val());
		$("#referenciaconformado").val(valorconformado);
		var nuevovalor = parseFloat($("#internacionLaboratorio").val()) + parseFloat($("#internacionMedicamentos").val()) + parseFloat($("#internacionDescartables").val()) + parseFloat($("#internacionOtros").val());
		$("#referenciainternacion").val(nuevovalor);
		var valorconformado = parseFloat($("#referenciaconformado").val()) + parseFloat($("#referenciainternacion").val());
		$("#referenciaconformado").val(valorconformado);
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#internacionMedicamentos").change(function(){
		if($("#internacionMedicamentos").val()=='') {
			$("#internacionMedicamentos").val('0.00');
		}
		var valorconformado = parseFloat($("#referenciaconformado").val()) - parseFloat($("#referenciainternacion").val());
		$("#referenciaconformado").val(valorconformado);
		var nuevovalor = parseFloat($("#internacionLaboratorio").val()) + parseFloat($("#internacionMedicamentos").val()) + parseFloat($("#internacionDescartables").val()) + parseFloat($("#internacionOtros").val());
		$("#referenciainternacion").val(nuevovalor);
		var valorconformado = parseFloat($("#referenciaconformado").val()) + parseFloat($("#referenciainternacion").val());
		$("#referenciaconformado").val(valorconformado);
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#internacionDescartables").change(function(){
		if($("#internacionDescartables").val()=='') {
			$("#internacionDescartables").val('0.00');
		}
		var valorconformado = parseFloat($("#referenciaconformado").val()) - parseFloat($("#referenciainternacion").val());
		$("#referenciaconformado").val(valorconformado);
		var nuevovalor = parseFloat($("#internacionLaboratorio").val()) + parseFloat($("#internacionMedicamentos").val()) + parseFloat($("#internacionDescartables").val()) + parseFloat($("#internacionOtros").val());
		$("#referenciainternacion").val(nuevovalor);
		var valorconformado = parseFloat($("#referenciaconformado").val()) + parseFloat($("#referenciainternacion").val());
		$("#referenciaconformado").val(valorconformado);
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#internacionOtros").change(function(){
		if($("#internacionOtros").val()=='') {
			$("#internacionOtros").val('0.00');
		}
		var valorconformado = parseFloat($("#referenciaconformado").val()) - parseFloat($("#referenciainternacion").val());
		$("#referenciaconformado").val(valorconformado);
		var nuevovalor = parseFloat($("#internacionLaboratorio").val()) + parseFloat($("#internacionMedicamentos").val()) + parseFloat($("#internacionDescartables").val()) + parseFloat($("#internacionOtros").val());
		$("#referenciainternacion").val(nuevovalor);
		var valorconformado = parseFloat($("#referenciaconformado").val()) + parseFloat($("#referenciainternacion").val());
		$("#referenciaconformado").val(valorconformado);
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#incluyecoseguro").change(function(){
		if($("#incluyecoseguro").prop('checked') ) {
			infoconformado = infoconformado.replace(' + Coseguro','');
			$('#infoconformado').attr('title', infoconformado);
			var nuevovalor = parseFloat($("#referenciacoseguro").val()) + parseFloat($("#valorCoseguro").val());
			$("#referenciacoseguro").val(nuevovalor);			
			var valorconformado = parseFloat($("#referenciaconformado").val()) - parseFloat($("#referenciacoseguro").val());
			$("#referenciaconformado").val(valorconformado);
			$("#valorCoseguro").attr('disabled', true);
		} else {
			infoconformado = infoconformado+' + Coseguro';
			$('#infoconformado').attr('title', infoconformado);
			var nuevovalor = parseFloat($("#referenciacoseguro").val()) - parseFloat($("#valorCoseguro").val());
			var valorconformado = parseFloat($("#referenciaconformado").val()) + parseFloat($("#referenciacoseguro").val());
			$("#referenciacoseguro").val(nuevovalor);
			$("#referenciaconformado").val(valorconformado);
			$("#valorCoseguro").attr('disabled', false);
		}
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#acreditacioncalidad").change(function(){
		if($("#acreditacioncalidad").prop('checked') ) {
			infoconformado = infoconformado+' + Acreditacion Calidad';
			$('#infoconformado').attr('title', infoconformado);
			var nuevovalor = parseFloat($("#referenciaacreditacion").val()) + parseFloat($("#importeAcreditacion").val());
			$("#referenciaacreditacion").val(nuevovalor);
			var valorconformado = parseFloat($("#referenciaconformado").val()) + parseFloat($("#referenciaacreditacion").val());
			$("#referenciaconformado").val(valorconformado);
			$("#importeAcreditacion").attr('disabled', false);
		} else {
			infoconformado = infoconformado.replace(' + Acreditacion Calidad','');
			$('#infoconformado').attr('title', infoconformado);
			var nuevovalor = parseFloat($("#referenciaacreditacion").val()) - parseFloat($("#importeAcreditacion").val());
			var valorconformado = parseFloat($("#referenciaconformado").val()) - parseFloat($("#referenciaacreditacion").val());
			$("#referenciaacreditacion").val(nuevovalor);
			$("#referenciaconformado").val(valorconformado);
			$("#importeAcreditacion").attr('disabled', true);
		}
		$("#cantidad").val('');
		$("#referenciatotal").val('');
		$("#totalfacturado").val('');
		$("#totaldebito").val('');
		$("#totalcredito").val('');
		$("#motivodebito").val('');
		$("#motivodebito").attr('disabled', true);
	});
	$("#cantidad").change(function(){
		if($("#cantidad").val()!='' && $("#cantidad").val()!=0) {
			var totalreferencia = $("#cantidad").val()*$("#referenciaconformado").val();
			if(totalreferencia==0.00) {
				$("#referenciatotal").val('0.00');
			} else {
				$("#referenciatotal").val(totalreferencia);
			}
			if($("#totalfacturado").val()!='' && $("#totalfacturado").val()!=0.00) {
				var calculodebito = $("#totalfacturado").val()-$("#referenciatotal").val();
				if(calculodebito > 0.00) {
					$("#totaldebito").val(calculodebito);
					$("#motivodebito").attr('disabled', false);
				} else {
					$("#totaldebito").val(0.00);
					$("#motivodebito").attr('disabled', true);
				}
				$("#totalcredito").val(($("#totalfacturado").val()-$("#totaldebito").val()));
			} else {
				$("#totaldebito").val('0.00');
				$("#totalcredito").val('0.00');
				$("#motivodebito").attr('disabled', true);
			}
		} else {
			$("#referenciatotal").val('0.00');
		}
	});
	$("#totalfacturado").change(function(){
		if($("#totalfacturado").val()!='' && $("#totalfacturado").val()!=0.00) {
			var calculodebito = $("#totalfacturado").val()-$("#referenciatotal").val();
			if(calculodebito > 0.00) {
				$("#totaldebito").val(calculodebito);
				$("#motivodebito").attr('disabled', false);
			} else {
				$("#totaldebito").val(0.00);
				$("#motivodebito").attr('disabled', true);
			}
			$("#totalcredito").val(($("#totalfacturado").val()-$("#totaldebito").val()));
		} else {
			$("#totaldebito").val('0.00');
			$("#totalcredito").val('0.00');
			$("#motivodebito").attr('disabled', true);
		}
	});
	$("#totaldebito").change(function(){
		if($("#totaldebito").val()!='' && $("#totaldebito").val()!=0.00) {
			$("#motivodebito").attr('disabled', false);
		} else {
			$("#totaldebito").val(0.00);
			$("#motivodebito").val('');
			$("#motivodebito").attr('disabled', true);
		}
		var calculocredito = $("#totalfacturado").val()-$("#totaldebito").val();
		if(calculocredito > 0.00) {
			$("#agregarprestacion").attr('disabled', false);
			$("#agregarcarencia").attr('disabled', true);
		} else {
			$("#agregarprestacion").attr('disabled', true);
			$("#cancelaintegracion").prop("checked",false);
			$("#integracion").hide();
			$("#agregarcarencia").attr('disabled', false);
		}
		$("#totalcredito").val(calculocredito);
	});
	$("#cancelaintegracion").change(function(){
		if($("#cancelaintegracion").prop('checked') ) {
			$("#solicitadointegracion").val('');
			$("#datosintegracion").show();
			var idpracticadevuelta = $("#idPractica").val();
			if(idpracticadevuelta==2400 || idpracticadevuelta==2409) {
				$("#escuelaintegracion").prop("checked",false);
				$("#escuelaintegracion").attr('disabled', false);
				$("#tipoescuelaintegracion option[value='']").prop('selected',true);
				$("#tipoescuelaintegracion").attr('disabled', true);
				$("#cueescuelaintegracion option[value='']").prop('selected',true);
				$("#cueescuelaintegracion").attr('disabled', true);
			} else {
				$("#escuelaintegracion").prop("checked",false);
				$("#escuelaintegracion").attr('disabled', true);
				$("#tipoescuelaintegracion option[value='']").prop('selected',true);
				$("#tipoescuelaintegracion").attr('disabled', true);
				$("#cueescuelaintegracion option[value='']").prop('selected',true);
				$("#cueescuelaintegracion").attr('disabled', true);
			}
		} else {
			$("#solicitadointegracion").val('');
			$("#dependenciaintegracion").prop("checked",false);
			$("#escuelaintegracion").prop("checked",false);
			$("#escuelaintegracion").attr('disabled', true);
			$("#tipoescuelaintegracion option[value='']").prop('selected',true);
			$("#tipoescuelaintegracion").attr('disabled', true);
			$("#cueescuelaintegracion option[value='']").prop('selected',true);
			$("#cueescuelaintegracion").attr('disabled', true);
			$("#datosintegracion").hide();
		}
	});
	$("#solicitadointegracion").change(function(){
		if($("#solicitadointegracion").val() > $("#totalcredito").val()) {
			var cajadialogo = $('<div title="Aviso"><p>El importe solicitado por integracion no es un monto admisible.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#solicitadointegracion').focus(); }});
		}
	});
	$("#escuelaintegracion").change(function(){
		if($("#escuelaintegracion").prop('checked') ) {
			$("#tipoescuelaintegracion option[value='']").prop('selected',true);
			$("#tipoescuelaintegracion").attr('disabled', false);
		} else {
			$("#tipoescuelaintegracion option[value='']").prop('selected',true);
			$("#tipoescuelaintegracion").attr('disabled', true);
			$("#cueescuelaintegracion option[value='']").prop('selected',true);
			$("#cueescuelaintegracion").attr('disabled', true);
		}
	});
	$("#tipoescuelaintegracion").change(function(){
		var tipoescuela = $(this).val();
		if(tipoescuela!='') {
			$.ajax({
				dataType: 'html',
				url: "buscaEscuelas.php",
				data: {tipoEscuela:tipoescuela},
			}).done(function(respuesta){
				$("#cueescuelaintegracion").html(respuesta);
			});
			$("#cueescuelaintegracion").attr('disabled', false);
		} else {
			$("#cueescuelaintegracion option[value='']").prop('selected',true);
			$("#cueescuelaintegracion").attr('disabled', true);
		}
	});
	$("#calculoestadistico").change(function(){
		if($("#calculoestadistico").prop('checked') ) {
			$("#estamb").prop('checked',false);
			$("#eligeestamb").show();
			$("#tablaambulatoria").hide();
			$("#estint").prop('checked', false);
			$("#eligeestint").show();
			$("#tablainternacion").hide();
		} else {
			$("#estamb").prop('checked',false);
			$("#eligeestamb").hide();
			$("#tablaambulatoria").hide();
			$("#estint").prop('checked', false);
			$("#eligeestint").hide();
			$("#tablainternacion").hide();
		}
	});
	$("#estamb").change(function(){
		if($("#estamb").prop('checked') ) {
			$("#estint").prop('checked', false);
			$("#estint").prop('disabled', true);
			$("#int9").prop('checked', false);
			$("#int9").prop('disabled', true);
			$("#int10").prop('checked', false);
			$("#int10").prop('disabled', true);
			$("#int11").prop('checked', false);
			$("#int11").prop('disabled', true);
			$("#int12").prop('checked', false);
			$("#int12").prop('disabled', true);
			$("#int121").prop('checked', false);
			$("#int121").prop('disabled', true);
			$("#int122").prop('checked', false);
			$("#int122").prop('disabled', true);
			$("#int123").prop('checked', false);
			$("#int123").prop('disabled', true);
			$("#int124").prop('checked', false);
			$("#int124").prop('disabled', true);
			$("#diastotal").val('0');
			$("#diastotal").prop('disabled', true);
			$("#diascoronaria").val('0');
			$("#diascoronaria").prop('disabled', true);
			$("#diasintensiva").val('0');
			$("#diasintensiva").prop('disabled', true);
			$("#diasneonatologia").val('0');
			$("#diasneonatologia").prop('disabled', true);
			$("#int13").prop('checked', false);
			$("#int13").prop('disabled', true);
			$("#int141").prop('checked', false);
			$("#int141").prop('disabled', true);
			$("#int142").prop('checked', false);
			$("#int142").prop('disabled', true);
			$("#int143").prop('checked', false);
			$("#int143").prop('disabled', true);
			$("#eligeestint").hide();
			$("#tablaambulatoria").show();
			$("#tablainternacion").hide();
		} else {
			$("#estint").prop('checked', false);
			$("#estint").prop('disabled', false);
			$("#int9").prop('checked', false);
			$("#int9").prop('disabled', false);
			$("#int10").prop('checked', false);
			$("#int10").prop('disabled', false);
			$("#int11").prop('checked', false);
			$("#int11").prop('disabled', false);
			$("#int12").prop('checked', false);
			$("#int12").prop('disabled', false);
			$("#int121").prop('checked', false);
			$("#int121").prop('disabled', false);
			$("#int122").prop('checked', false);
			$("#int122").prop('disabled', false);
			$("#int123").prop('checked', false);
			$("#int123").prop('disabled', false);
			$("#int124").prop('checked', false);
			$("#int124").prop('disabled', false);
			$("#diastotal").val('0');
			$("#diastotal").prop('disabled', false);
			$("#diascoronaria").val('0');
			$("#diascoronaria").prop('disabled', false);
			$("#diasintensiva").val('0');
			$("#diasintensiva").prop('disabled', false);
			$("#diasneonatologia").val('0');
			$("#diasneonatologia").prop('disabled', false);
			$("#int13").prop('checked', false);
			$("#int13").prop('disabled', false);
			$("#int141").prop('checked', false);
			$("#int141").prop('disabled', false);
			$("#int142").prop('checked', false);
			$("#int142").prop('disabled', false);
			$("#int143").prop('checked', false);
			$("#int143").prop('disabled', false);
			$("#eligeestamb").show();
			$("#eligeestint").show();
			$("#tablaambulatoria").hide();
			$("#tablainternacion").hide();
		}
	});
	$("#estint").change(function(){
		if($("#estint").prop('checked') ) {
			$("#estamb").prop('checked', false);
			$("#estamb").prop('disabled', true);
			$("#amb1").prop('checked', false);
			$("#amb1").prop('disabled', true);
			$("#amb2").prop('checked', false);
			$("#amb2").prop('disabled', true);
			$("#amb21").prop('checked', false);
			$("#amb21").prop('disabled', true);
			$("#amb22").prop('checked', false);
			$("#amb22").prop('disabled', true);
			$("#amb3").prop('checked', false);
			$("#amb3").prop('disabled', true);
			$("#amb31").prop('checked', false);
			$("#amb31").prop('disabled', true);
			$("#amb32").prop('checked', false);
			$("#amb32").prop('disabled', true);
			$("#amb33").prop('checked', false);
			$("#amb33").prop('disabled', true);
			$("#amb4").prop('checked', false);
			$("#amb4").prop('disabled', true);
			$("#amb5").prop('checked', false);
			$("#amb5").prop('disabled', true);
			$("#amb6").prop('checked', false);
			$("#amb6").prop('disabled', true);
			$("#amb7").prop('checked', false);
			$("#amb7").prop('disabled', true);
			$("#amb8").prop('checked', false);
			$("#amb8").prop('disabled', true);
			$("#eligeestamb").hide();
			$("#tablaambulatoria").hide();
			$("#tablainternacion").show();
		} else {
			$("#estamb").prop('checked', false);
			$("#estamb").prop('disabled', false);
			$("#amb1").prop('checked', false);
			$("#amb1").prop('disabled', false);
			$("#amb2").prop('checked', false);
			$("#amb2").prop('disabled', false);
			$("#amb21").prop('checked', false);
			$("#amb21").prop('disabled', false);
			$("#amb22").prop('checked', false);
			$("#amb22").prop('disabled', false);
			$("#amb3").prop('checked', false);
			$("#amb3").prop('disabled', false);
			$("#amb31").prop('checked', false);
			$("#amb31").prop('disabled', false);
			$("#amb32").prop('checked', false);
			$("#amb32").prop('disabled', false);
			$("#amb33").prop('checked', false);
			$("#amb33").prop('disabled', false);
			$("#amb4").prop('checked', false);
			$("#amb4").prop('disabled', false);
			$("#amb5").prop('checked', false);
			$("#amb5").prop('disabled', false);
			$("#amb6").prop('checked', false);
			$("#amb6").prop('disabled', false);
			$("#amb7").prop('checked', false);
			$("#amb7").prop('disabled', false);
			$("#amb8").prop('checked', false);
			$("#amb8").prop('disabled', false);
			$("#eligeestamb").show();
			$("#eligeestint").show();
			$("#tablaambulatoria").hide();
			$("#tablainternacion").hide();
		}
	});
	$("#diastotal").change(function(){
		if($("#diastotal").val()=='') {
			$("#diastotal").val('0');
		}
	});
	$("#diascoronaria").change(function(){
		if($("#diascoronaria").val()=='') {
			$("#diascoronaria").val('0');
		}
	});
	$("#diasintensiva").change(function(){
		if($("#diasintensiva").val()=='') {
			$("#diasintensiva").val('0');
		}
	});
	$("#diasneonatologia").change(function(){
		if($("#diasneonatologia").val()=='') {
			$("#diasneonatologia").val('0');
		}
	});
	$("#agregarprestacion").on("click", function() {
		if($("#cantidad").val()=='') {
			var cajadialogo = $('<div title="Aviso"><p>Debe ingresar la Cantidad/Unidades de la Prestacion.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close:function(event, ui) { $('#cantidad').focus(); }});
			return;
		}
		var datosform = $("form#consumoPrestacional").serialize();
		$.blockUI({ message: "<h1>Agregando Prestacion a la Liquidacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		$.ajax({
			type: "POST",
			url: "agregaPrestacion.php",
			data: datosform,
			dataType: 'json',
			success: function(data) {
				if(data.result == true){
					location.reload();
					$.unblockUI();
				} else {
					$.unblockUI();
					var cajadialogo = $('<div title="Aviso"><p>Ocurrio un error al intentar agregar una prestacion. Comuniquelo al Depto. de Sistemas.</p></div>');
					cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $("#agregarprestacion").attr('disabled', true); $("#agregarcarencia").attr('disabled', true); }});
				}
			}
		});
	});
	$("#agregarcarencia").on("click", function() {
		var datosform = $("form#consumoPrestacional").serialize();
		$.blockUI({ message: "<h1>Agregando Carencia a la Liquidacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		$.ajax({
			type: "POST",
			url: "agregaCarenciaPrestacion.php",
			data: datosform,
			dataType: 'json',
			success: function(data) {
				if(data.result == true){
					location.reload();
					$.unblockUI();
				} else {
					$.unblockUI();
					var cajadialogo = $('<div title="Aviso"><p>Ocurrio un error al intentar agregar una carencia. Comuniquelo al Depto. de Sistemas.</p></div>');
					cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $("#agregarprestacion").attr('disabled', true); $("#agregarcarencia").attr('disabled', true); }});
				}
			}
		});
	});
	$("#listaConsumos")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers: {
				0:{sorter:false, filter: false},
				9:{sorter:false, filter: false}
			},
			widgets: ["zebra", "filter"], 
			widgetOptions: { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
	});
	$("#listaCarencias")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers: {
				0:{sorter:false, filter: false},
				9:{sorter:false, filter: false}
			},
			widgets: ["zebra", "filter"], 
			widgetOptions: { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
	});
});
function consultaContratos(dire) {
	a=window.open(dire,'',
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
};
function anulaConsumoCarencia(idconsumocarencia, idfactura, idfacturabeneficiario) {
	param = "idConsumoCarencia="+idconsumocarencia+"&idFactura="+idfactura+"&idFacturaBeneficiario="+idfacturabeneficiario;
	$.blockUI({ message: "<h1>Anulando Consumo/Carencia de la Liquidacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	window.location.href = "anulaConsumoCarencia.php?"+param;
};
</script>
<style>
.ui-autocomplete-loading { background: white url("../img/ui-anim_basic_16x16.gif") right center no-repeat; }
.ui-menu .ui-menu-item a{ font-size:10px; }
</style>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'continuarLiquidacion.php?idfactura=<?php echo $rowConsultaFactura['id'];?>'" />
</div>
<fieldset style="border-top:none;border-left:none;border-right:none">
<div align="center">
	<h2 style="margin-top:1px;margin-bottom:1px">Liquidacion</h2>
</div>
<div align="center">
	<h3 style="margin-bottom:1px">ID Comprobante Interno: <?php echo $rowConsultaFactura['id'];?> - Fecha de Recepcion: <?php echo invertirFecha($rowConsultaFactura['fecharecepcion']);?> - Importe: <?php echo $rowConsultaFactura['importecomprobante'];?></h3>
</div>
<div align="center">
	<h3 style="margin-bottom:1px">Liquidacion del Usuario @<?php echo $rowConsultaFactura['usuarioliquidacion'];?> - Fecha de Inicio: <?php echo invertirFecha($rowConsultaFactura['fechainicioliquidacion']);?></h3>
</div>
<div align="center" >
	<div class="grilla" style="margin-top:10px; margin-bottom:10px">
	<table>
		<tr>
			<td colspan="3" class="title">Prestador</td>
		</tr>
		<tr>
			<td align="right">Codigo: </td>
			<td align="left"><?php echo $rowConsultaPrestador['codigoprestador'];?></td>
			<td align="center">Servicios</td>
		</tr>
		<tr>
			<td align="right">Nombre / Razon Social: </td>
			<td align="left"><?php echo $rowConsultaPrestador['nombre'];?></td>
			<td rowspan="2" align="center">
			<?php while ($rowConsultaServicios = mysql_fetch_assoc($resConsultaServicios)) {
				echo $rowConsultaServicios['descripcion']."<br>";
			} ?>
			</td>
		</tr>
		<tr>
			<td align="right">C.U.I.T.: </td>
			<td align="left"><?php echo $rowConsultaPrestador['cuit'];?></td>
		</tr>
		<tr>
			<td align="right">Personeria: </td>
			<td align="left"><?php echo $rowConsultaPrestador['descripcion'];?></td>
			<td align="center">Nomencladores</td>
		</tr>
		<tr>
			<td align="right">Contratos: </td>
			<td align="left">
			<?php if($numConsultaContratos == 0) {
				echo 'Sin Contratos Cargados';
				$contratoprestador = 0;
			}  else {
				$contratoprestador = 1; ?>
				<input name="consultacontrato" type="button" id="consultacontrato" value="Ver Contratos" onclick="consultaContratos('../prestadores/abm/contratos/consultaContratosPrestador.php?codigo=<?php echo $rowConsultaFactura['idPrestador']; ?>')"/>
			<?php
			} ?>
				<input name="contrato" type="hidden" id="contrato" size="2" value="<?php echo $contratoprestador; ?>"/>
			</td>
			<td align="center">
			<?php $existeresolucion = 0;
			while ($rowConsultaNomenclador = mysql_fetch_assoc($resConsultaNomenclador)) {
				echo $rowConsultaNomenclador['nombre']."<br>";
				if($rowConsultaNomenclador['contrato']==0) {
					$existeresolucion = $rowConsultaNomenclador['id'];
				}
			} ?>
				<input name="nomencladorresolucion" type="hidden" id="nomencladorresolucion" size="2" value="<?php echo $existeresolucion; ?>"/>
			</td>
		</tr>
	</table>
	</div>
</div>
<div align="center" >
	<div class="grilla" style="margin-top:10px; margin-bottom:10px">
	<table>
		<tr>
			<td colspan="4" class="title">Beneficiario</td>
		</tr>
		<tr>
			<td align="right">Nro. Afiliado: </td>
			<td align="left"><?php echo $rowConsultaFacturasBeneficiarios['nroafiliado'];?></td>
			<td align="right">Tipo: </td>
			<td align="left"><?php echo $descripcionTipo;?></td>
		</tr>
		<tr>
			<td align="right">Apellido y Nombre: </td>
			<td align="left"><?php echo $nombreBeneficiario;?></td>
			<td  rowspan="2"align="right">Delegacion: </td>
			<td align="left"><?php echo $deleBeneficiario;?></td>
		</tr>
		<tr>
			<td align="right">C.U.I.L.: </td>
			<td align="left"><?php echo $cuilBeneficiario;?></td>
			<td align="right">
			<?php if($rowConsultaFacturasBeneficiarios['excepcionjurisdiccion'] == 1) {
				echo 'Excepcion Jurisdiccional';
			} ?>
			</td>
		</tr>
	</table>
	</div>
</div>
</fieldset>
<div align="center">
	<h2 style="margin-top:1px;margin-bottom:1px">Consumo Prestacional</h2>
</div>
<form id="consumoPrestacional">
	<div align="center">
		<table border="0">
			<tr>
				<td align="right" valign="bottom"><strong>Fecha</strong></td>
				<td align="left" colspan="5"><input name="fechaprestacion" type="text" id="fechaprestacion" size="6" value=""/>
					<input name="idFactura" type="hidden" id="idFactura" size="10" value="<?php echo $idfactura; ?>"/>
					<input name="idFacturabeneficiario" type="hidden" id="idFacturabeneficiario" size="10" value="<?php echo $idfacturabeneficiario; ?>"/>
					<input name="idprestador" type="hidden" id="idprestador" size="6" value="<?php echo $rowConsultaPrestador['codigoprestador'];?>"/>
					<input name="personeria" type="hidden" id="personeria" size="2" value="<?php echo $rowConsultaPrestador['personeria']; ?>"/>
				</td>
			</tr>
			<tr>
				<td align="right"><strong>Buscar Prestacion</strong></td>
				<td colspan="5"><textarea name="buscaprestacion" rows="3" cols="125" id="buscaprestacion" placeholder="Ingrese un minimo de 3 caracteres para que se inicie la busqueda"></textarea>
				<input name="idPractica" type="hidden" id="idPractica" size="5" value=""/>
					<input name="esGaleno" type="hidden" id="esGaleno" size="2" value=""/>
					<input name="esIntegracion" type="hidden" id="esIntegracion" size="2" value=""/>
					<input name="clasificacionComplejidad" type="hidden" id="clasificacionComplejidad" size="2" value=""/>
					<input name="esInternacion" type="hidden" id="esInternacion" size="2" value=""/>
					<input name="referenciaunitario" type="hidden" id="referenciaunitario" size="5" value="0.00"/>
				</td>
			</tr>
			<tr>
				<td align="right"><strong>Efector</strong></td>
				<td colspan="5" align="left"><textarea name="efectorpractica" rows="3" cols="125" id="efectorpractica" placeholder=""></textarea>
											<input name="idEfector" type="hidden" id="idEfector" size="5" value=""/>
											<input name="establecimientoCirculo" type="hidden" id="establecimientoCirculo" size="2" value=""/>
											<input name="establecimientoCalidad" type="hidden" id="establecimientoCalidad" size="2" value=""/>
				</td>
			</tr>
			<tr>
				<td align="right"><strong>Prof. del Efector</strong></td>
				<td colspan="5" align="left"><input name="efectorprofesional" type="text" id="efectorprofesional" size="100" maxlength="100" placeholder="" value=""></td>
			</tr>
			<tr id="conformaciongaleno" style="display:none">
				<td align="right" colspan="2"><strong>Conformacion Galeno</strong></td>
				<td align="left" colspan="4">
					 | Honorario <input name="honorario" type="checkbox" id="honorario" value="1"/> <input name="galenoHonorario" type="text" id="galenoHonorario" size="5" readonly="readonly" style="background-color:#CCCCCC" value=""/>
					 | Especialista <input name="especialista" type="checkbox" id="especialista" value="1"/> <input name="galenoEspecialista" type="text" id="galenoEspecialista" size="5" readonly="readonly" style="background-color:#CCCCCC" value=""/>
					 | Ayudante <input name="ayudante" type="checkbox" id="ayudante" value="1"/> <input name="galenoAyudante" type="text" id="galenoAyudante" size="5" readonly="readonly" style="background-color:#CCCCCC" value=""/>
					 | Anestesista <input name="anestesista" type="checkbox" id="anestesista" value="1"/> <input name="galenoAnestesista" type="text" id="galenoAnestesista" size="5" readonly="readonly" style="background-color:#CCCCCC" value=""/>
					 | Gastos / U.B. 
					 <input name="gastos" type="checkbox" id="gastos" value="1"/> <input name="galenoGastos" type="text" id="galenoGastos" size="5" readonly="readonly" style="background-color:#CCCCCC" value=""/> |
				</td>
			</tr>
			<tr id="internacion" style="display:none">
				<td align="right" colspan="2"><strong>Gastos Internacion ?</strong></td>
				<td align="left" colspan="4"><input name="gastosinternacion" type="checkbox" id="gastosinternacion" value="1"/>
					| Laboratorio <input name="internacionLaboratorio" type="text" id="internacionLaboratorio" size="5" maxlength="9" value="0.00"/>
					| Medicamentos <input name="internacionMedicamentos" type="text" id="internacionMedicamentos" maxlength="9" size="5" value="0.00"/>
					| Descartables <input name="internacionDescartables" type="text" id="internacionDescartables" maxlength="9" size="5" value="0.00"/>
					| Otros <input name="internacionOtros" type="text" id="internacionOtros" size="5" maxlength="9" value="0.00"/> |
					<input name="referenciainternacion" type="hidden" id="referenciainternacion" size="5" value="0.00"/>
				</td>
			</tr>
			<tr id="coseguro" style="display:none">
				<td align="right" colspan="2"><strong>Coseguro a Cargo Beneficiario?</strong></td>
				<td align="left" colspan="4"><input name="incluyecoseguro" type="checkbox" id="incluyecoseguro" value="1"/>
					| Valor Coseguro <input name="valorCoseguro" type="text" id="valorCoseguro" size="5" readonly="readonly" style="background-color:#CCCCCC" value=""/> |<input name="referenciacoseguro" type="hidden" id="referenciacoseguro" size="5" value="0.00"/>
				</td>
			</tr>
			<tr id="calidadestablecimiento" style="display:none">
				<td align="right" colspan="2"><strong>Acred. de Calidad del Est. ?</strong></td>
				<td align="left" colspan="4"><input name="acreditacioncalidad" type="checkbox" id="acreditacioncalidad" value="1"/>
					| Importe Acreditacion <input name="importeAcreditacion" type="text" id="importeAcreditacion" size="5" readonly="readonly" style="background-color:#CCCCCC" value=""/> |<input name="referenciaacreditacion" type="hidden" id="referenciaacreditacion" size="5" value="0.00"/>
				</td>
			</tr>
			<tr>
				<td align="right" colspan="5"><i id="infoconformado" style="font-size: 15px" title="" class="ui-icon ui-icon-info"></i></td>
				<td align="left"><strong>Ref. Valor Unitario Prestacion</strong><input name="referenciaconformado" type="text" id="referenciaconformado" size="5" readonly="readonly" style="background-color:#CCCCCC" value="0.00"/></td>
			</tr>
			<tr>
				<td align="right"><strong>Cant. / Unidades</strong></td>
				<td align="left"><input name="cantidad" type="text" id="cantidad" size="5" value="" maxlength="7"/></td>
				<td align="right"><strong>Facturado</strong></td>
				<td align="left"><input name="totalfacturado" type="text" id="totalfacturado" size="5" maxlength="9" value=""/></td>
				<td align="right"><i id="infototal" style="font-size: 15px" title="" class="ui-icon ui-icon-info"></i></td>
				<td align="left"><strong>Ref. Valor Total Prestacion</strong><input name="referenciatotal" type="text" id="referenciatotal" size="5" readonly="readonly" style="background-color:#CCCCCC" value="0.00"/></td>

			</tr>
			<tr>
				<td align="right"><strong>Debito</strong></td>
				<td align="left"><input name="totaldebito" type="text" id="totaldebito" size="5" maxlength="9" value=""/></td>
				<td align="right"><strong>Credito</strong></td>
				<td align="left" colspan="3"><input name="totalcredito" type="text" id="totalcredito" size="5" readonly="readonly" style="background-color:#CCCCCC" value="0.00"/>			</td>
			</tr>
			<tr>
	<td align="right"><strong>Motivo Debito</strong></td>
				<td colspan="5" align="left"><textarea name="motivodebito" rows="3" cols="125" id="motivodebito" placeholder="Motivo del Debito / Comentario / Observacion"></textarea></td>
			</tr>
			<tr id="integracion">
				<td align="right"><strong>Paga por Integracion ?</strong></td>
				<td colspan="5" align="left"><input name="cancelaintegracion" type="checkbox" id="cancelaintegracion" value="1"/>
			</tr>
		</table>
	</div>
	<div id="datosintegracion" align="center">
		<table border="0">
			<tr>
				<td align="right"><strong>Importe Solicitado</strong></td>
				<td align="left"><input name="solicitadointegracion" type="text" id="solicitadointegracion" size="10" value="" autocomplete="off"/>
				<td align="right"><strong>Dependencia ?</strong></td>
				<td align="left"><input name="dependenciaintegracion" type="checkbox" id="dependenciaintegracion" value="1"/>
				<td align="right"><strong>Escuela ?</strong></td>
				<td align="left"><input name="escuelaintegracion" type="checkbox" id="escuelaintegracion" value="1"/>
				<td align="left"><select name="tipoescuelaintegracion" id="tipoescuelaintegracion">
									<option title="Seleccione tipo" value="">Seleccione tipo</option>
									<option title="ESCUELA COMUN (MENSUAL)" value="2410">ESCUELA COMUN (MENSUAL)</option>
									<option title="ESCUELA ESPECIAL PRE PRIMARIA (MENSUAL)" value="2411">ESCUELA ESPECIAL PRE PRIMARIA (MENSUAL)</option>
									<option title="ESCUELA ESPECIAL PRIMARIA (MENSUAL)" value="2412">ESCUELA ESPECIAL PRIMARIA (MENSUAL)</option>
								</select>
				</td>
			</tr>
			<tr>
				<td colspan="6" align="right"></td>
				<td align="left"><select name="cueescuelaintegracion" id="cueescuelaintegracion">
									<option title="Seleccione CUE" value="">Seleccione CUE</option>
								</select>
				</td>
			</tr>
		</table>
	</div>
	<div id="estadisticas" align="center">
		<h3 style="margin-bottom:1px">Calculo Estadistico Resol. 650</h3>
		<div id="computoautomatico">
			<strong>Prestacion no Clasificada. No hay computo estadistico automatico.</strong>
		</div>
		<div id="computomanual">
			<strong>Computo Estadistico Manual: </strong><input name="calculoestadistico" type="checkbox" id="calculoestadistico" value="1"/>
		</div>
		<div id="eligeestamb">
			<h3 style="margin-bottom:1px">Estadistica Ambulatoria <input name="estamb" type="checkbox" id="estamb" value="1"/></h3>
		</div>
		<div id="estambulatoria" align="center">
			<table width="55%" border="2" id="tablaambulatoria">
			  <tr>
				<td width="16%" rowspan="2">Consulta</td>
				<td width="22%">Ambulatoria</td>
				<td colspan="3"><input name="amb4" type="checkbox" id="amb4" value="4"/></td>
			  </tr>
			  <tr>
				<td>Psiquiatrica</td>
				<td colspan="3"><input name="amb5" type="checkbox" id="amb5" value="5"/></td>
			  </tr>
			  <tr>
				<td rowspan="3">Paciente Terapia</td>
				<td>Rehabilitacion</td>
				<td colspan="3"><input name="amb6" type="checkbox" id="amb6" value="6"/></td>
			  </tr>
			  <tr>
				<td>Citostatica</td>
				<td colspan="3"><input name="amb7" type="checkbox" id="amb7" value="7"/></td>
			  </tr>
			  <tr>
				<td>Hemodialisis</td>
				<td colspan="3"><input name="amb8" type="checkbox" id="amb8" value="8"/></td>
			  </tr>
			  <tr>
				<td rowspan="6">Practica</td>
				<td>Baja Complejidad</td>
				<td colspan="3"><input name="amb1" type="checkbox" id="amb1" value="1"/></td>
			  </tr>
			  <tr>
				<td rowspan="2">Media Complejidad</td>
				<td width="9%" rowspan="2"><input name="amb2" type="checkbox" id="amb2" value="2"/></td>
				<td width="34%">Ecografia</td>
				<td width="19%"><input name="amb21" type="checkbox" id="amb21" value="21"/></td>
			  </tr>
			  <tr>
				<td>Tomografia</td>
				<td><input name="amb22" type="checkbox" id="amb22" value="22"/></td>
			  </tr>
			  <tr>
				<td rowspan="3">Alta Complejidad</td>
				<td rowspan="3"><input name="amb3" type="checkbox" id="amb3" value="3"/></td>
				<td>Medicina Nuclear </td>
				<td><input name="amb31" type="checkbox" id="amb31" value="31"/></td>
			  </tr>
			  <tr>
				<td>Hemodinamia</td>
				<td><input name="amb32" type="checkbox" id="amb32" value="32"/></td>
			  </tr>
			  <tr>
				<td>Resonancia Magnetica</td>
				<td><input name="amb33" type="checkbox" id="amb33" value="33"/></td>
			  </tr>
		  </table>
		</div>
		<div id="eligeestint">
			<h3 style="margin-bottom:1px">Estadistica Internacion / Egresos <input name="estint" type="checkbox" id="estint" value="1"/></h3>
		</div>
		<div id="estinternacionegreso" align="center">
			<table width="55%" border="2" id="tablainternacion">
			  <tr>
				<td width="12%" rowspan="7">Egreso</td>
				<td width="19%" rowspan="2">Parto</td>
				<td width="18%">Normal</td>
				<td colspan="2"><input name="int9" type="checkbox" id="int9" value="9"/></td>
			  </tr>
			  <tr>
			    <td width="18%">Cesarea</td>
				<td colspan="2"><input name="int10" type="checkbox" id="int10" value="10"/></td>
			  </tr>
			  <tr>
				<td>Clinico</td>
				<td colspan="3"><input name="int11" type="checkbox" id="int11" value="11"/></td>
				</tr>
			  <tr>
				<td rowspan="4">Quirurgico</td>
				<td rowspan="4"><input name="int12" type="checkbox" id="int12" value="12"/></td>
				<td width="32%">Cirugia Cardiovascular </td>
				<td width="19%"><input name="int121" type="checkbox" id="int121" value="121"/></td>
			  </tr>
			  <tr>
				<td>Prostactectomia</td>
				<td><input name="int122" type="checkbox" id="int122" value="122"/></td>
			  </tr>
			  <tr>
				<td>Cirugia Cancer de Cuello Uterino </td>
				<td><input name="int123" type="checkbox" id="int123" value="123"/></td>
			  </tr>
			  <tr>
				<td>Cirugia Cancer de Mama </td>
				<td><input name="int124" type="checkbox" id="int124" value="124"/></td>
			  </tr>
			  <tr>
				<td rowspan="4">Internacion</td>
				<td>Total de Dias </td>
				<td colspan="3"><input name="diastotal" type="text" id="diastotal" size="4" value="0"/></td>
				</tr>
			  <tr>
				<td rowspan="3">En Area Critica </td>
				<td>Unidad Coronaria </td>
				<td colspan="2"><input name="diascoronaria" type="text" size="4" id="diascoronaria" value="0"/></td>
			  </tr>
			  <tr>
				<td>Terapia Intensiva </td>
				<td colspan="2"><input name="diasintensiva" type="text" size="4" id="diasintensiva" value="0"/></td>
				</tr>
			  <tr>
				<td>Neonatologia</td>
				<td colspan="2"><input name="diasneonatologia" type="text" size="4" id="diasneonatologia" value="0"/></td>
				</tr>
			  <tr>
				<td rowspan="4">Alta</td>
				<td>Domiciliaria</td>
				<td colspan="3"><input name="int13" type="checkbox" id="int13" value="13"/></td>
				</tr>
			  <tr>
				<td rowspan="3">Defuncion</td>
				<td>Perinatal</td>
				<td colspan="2"><input name="int141" type="checkbox" id="int141" value="141"/></td>
			  </tr>
			  <tr>
			    <td>Infantil</td>
				<td colspan="2"><input name="int142" type="checkbox"  id="int142" value="142"/></td>
			  </tr>
			  <tr>
			    <td>Adulto</td>
				<td colspan="2"><input name="int143" type="checkbox"  id="int143" value="143"/></td>
			  </tr>
		  </table>
		</div>
	</div>
	<div align="center">
		<table border="0">
			<tr>
				<td><input type="button" name="agregarprestacion" id="agregarprestacion" value="Agregar Prestacion"/></td>
				<td><input type="button" name="agregarcarencia" id="agregarcarencia" value="Agregar Carencia"/></td>
			</tr>
		</table>
	</div>
</form> 
<div align="center">
	<table id="listaConsumos" class="tablesorter" style="font-size:14px; text-align:center">
		<thead>
			<tr>
				<th colspan="10">Prestaciones Ingresadas</th>
			</tr>
			<tr>
				<th>Fecha</th>
				<th>Prestacion</th>
				<th>Cantidad</th>
				<th>Facturado</th>
				<th>Debito</th>
				<th>Credito</th>
				<th>Motivo</th>
				<th>Efector</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
		<?php while($rowConsultaFacturasPrestacionesConsumo = mysql_fetch_array($resConsultaFacturasPrestacionesConsumo)) {
					if($rowConsultaFacturasPrestacionesConsumo['tipoefectorpractica']==1) {
						$efectorpractica = $rowConsultaPrestador['nombre'];
					} else {
						if($rowConsultaFacturasPrestacionesConsumo['tipoefectorpractica']==2) {
							$sqlConsultaProfesionalCirculo="SELECT nombre FROM profesionales WHERE codigoprofesional = $rowConsultaFacturasPrestacionesConsumo[efectorpractica] AND codigoprestador = $rowConsultaPrestador[codigoprestador]";
							$resConsultaProfesionalCirculo = mysql_query($sqlConsultaProfesionalCirculo,$db);
							$rowConsultaProfesionalCirculo = mysql_fetch_array($resConsultaProfesionalCirculo);
							$efectorpractica = $rowConsultaProfesionalCirculo['nombre'];
						} else {
							if($rowConsultaFacturasPrestacionesConsumo['profesionalestablecimientocirculo']!=NULL) {
								$efectorpractica = $rowConsultaFacturasPrestacionesConsumo['profesionalestablecimientocirculo'];
							} else {
								$sqlConsultaEstablecimientoEntidad="SELECT nombre FROM establecimientos WHERE codigo = $rowConsultaFacturasPrestacionesConsumo[efectorpractica] AND codigoprestador = $rowConsultaPrestador[codigoprestador]";
								$resConsultaEstablecimientoEntidad = mysql_query($sqlConsultaEstablecimientoEntidad,$db);
								$rowConsultaEstablecimientoEntidad = mysql_fetch_array($resConsultaEstablecimientoEntidad);
								$efectorpractica = $rowConsultaEstablecimientoEntidad['nombre'];
							}
						}
					}?>
			<tr>
				<td><?php echo invertirFecha($rowConsultaFacturasPrestacionesConsumo['fechapractica']);?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['codigopractica'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['cantidad'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['totalfacturado'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['totaldebito'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['totalcredito'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['motivodebito'];?></td>
				<td><?php echo $efectorpractica;?> </td>
				<td><input type="button" name="anulaconsumo" id="anulaconsumo" value="Anular Consumo" style="font-size:10px" onclick="javascript:anulaConsumoCarencia(<?php echo $rowConsultaFacturasPrestacionesConsumo['id'];?>,<?php echo $idfactura;?>,<?php echo $idfacturabeneficiario;?>)"/></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
<div align="center">
	<table id="listaCarencias" class="tablesorter" style="font-size:14px; text-align:center">
		<thead>
			<tr>
				<th colspan="10">Carencias Ingresadas</th>
			</tr>
			<tr>
				<th>Fecha</th>
				<th>Prestacion</th>
				<th>Cantidad</th>
				<th>Facturado</th>
				<th>Debito</th>
				<th>Credito</th>
				<th>Motivo</th>
				<th>Efector</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
		<?php while($rowConsultaFacturasPrestacionesCarencia = mysql_fetch_array($resConsultaFacturasPrestacionesCarencia)) {
					if($rowConsultaFacturasPrestacionesCarencia['tipoefectorpractica']==1) {
						$efectorpractica = $rowConsultaPrestador['nombre'];
					} else {
						if($rowConsultaFacturasPrestacionesCarencia['tipoefectorpractica']==2) {
							$sqlConsultaProfesionalCirculo="SELECT nombre FROM profesionales WHERE codigoprofesional = $rowConsultaFacturasPrestacionesCarencia[efectorpractica] AND codigoprestador = $rowConsultaPrestador[codigoprestador]";
							$resConsultaProfesionalCirculo = mysql_query($sqlConsultaProfesionalCirculo,$db);
							$rowConsultaProfesionalCirculo = mysql_fetch_array($resConsultaProfesionalCirculo);
							$efectorpractica = $rowConsultaProfesionalCirculo['nombre'];
						} else {
							if($rowConsultaFacturasPrestacionesCarencia['profesionalestablecimientocirculo']!=NULL) {
								$efectorpractica = $rowConsultaFacturasPrestacionesCarencia['profesionalestablecimientocirculo'];
							} else {
								$sqlConsultaEstablecimientoEntidad="SELECT nombre FROM establecimientos WHERE codigo = $rowConsultaFacturasPrestacionesCarencia[efectorpractica] AND codigoprestador = $rowConsultaPrestador[codigoprestador]";
								$resConsultaEstablecimientoEntidad = mysql_query($sqlConsultaEstablecimientoEntidad,$db);
								$rowConsultaEstablecimientoEntidad = mysql_fetch_array($resConsultaEstablecimientoEntidad);
								$efectorpractica = $rowConsultaEstablecimientoEntidad['nombre'];
							}
						}
					}?>
			<tr>
				<td><?php echo invertirFecha($rowConsultaFacturasPrestacionesCarencia['fechapractica']);?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['codigopractica'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['cantidad'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['totalfacturado'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['totaldebito'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['totalcredito'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['motivodebito'];?></td>
				<td><?php echo $efectorpractica;?></td>
				<td><input type="button" name="anulacarencia" id="anulacarencia" value="Anular Carencia" style="font-size:10px" onclick="javascript:anulaConsumoCarencia(<?php echo $rowConsultaFacturasPrestacionesCarencia['id'];?>,<?php echo $idfactura;?>,<?php echo $idfacturabeneficiario;?>)"/></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
</body>
</html>