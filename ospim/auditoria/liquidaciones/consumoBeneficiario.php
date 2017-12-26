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

	$sqlConsultaNomenclador = "SELECT n.id, n.nombre FROM prestadornomenclador p, nomencladores n WHERE p.codigoprestador = $rowConsultaFactura[idPrestador] AND p.codigonomenclador = n.id";
	$resConsultaNomenclador = mysql_query($sqlConsultaNomenclador,$db);

	$sqlConsultaServicios = "SELECT t.descripcion FROM prestadorservicio p, tiposervicio t WHERE p.codigoprestador = $rowConsultaFactura[idPrestador] AND p.codigoservicio = t.codigoservicio";
	$resConsultaServicios = mysql_query($sqlConsultaServicios,$db);

	$sqlConsultaContratos = "SELECT * FROM cabcontratoprestador c WHERE codigoprestador = $rowConsultaFactura[idPrestador]";
	$resConsultaContratos = mysql_query($sqlConsultaContratos,$db);
	$numConsultaContratos = mysql_num_rows($resConsultaContratos);

	$sqlConsultaFacturasBeneficiarios = "SELECT f.*, t.nroafiliado, t.apellidoynombre, t.cuil, t.codidelega FROM facturasbeneficiarios f, titulares t WHERE f.id = $idfacturabeneficiario AND f.nroafiliado = t.nroafiliado";
	$resConsultaFacturasBeneficiarios = mysql_query($sqlConsultaFacturasBeneficiarios,$db);
	$rowConsultaFacturasBeneficiarios = mysql_fetch_array($resConsultaFacturasBeneficiarios);

	if($rowConsultaFacturasBeneficiarios['tipoafiliado']==0) {
		$descripcionTipo = 'Titular';
		$nombreBeneficiario = $rowConsultaFacturasBeneficiarios['apellidoynombre'];
		$cuilBeneficiario = $rowConsultaFacturasBeneficiarios['cuil'];
	} else {
		$sqlConsultaFamiliar = "SELECT f.apellidoynombre, f.cuil, p.descrip FROM familiares f, parentesco p WHERE f.nroafiliado = $rowConsultaFacturasBeneficiarios[nroafiliado] AND f.nroorden = $rowConsultaFacturasBeneficiarios[nroorden] AND f.tipoparentesco = p.codparent";
		$resConsultaFamiliar = mysql_query($sqlConsultaFamiliar,$db);
		$rowConsultaFamiliar = mysql_fetch_array($resConsultaFamiliar);
		$descripcionTipo = $rowConsultaFamiliar['descrip'];
		$nombreBeneficiario = $rowConsultaFamiliar['apellidoynombre'];
		$cuilBeneficiario = $rowConsultaFamiliar['cuil'];
	}

	$sqlConsultaFacturasPrestacionesConsumo = "SELECT * FROM facturasprestaciones WHERE idFactura = $idfactura AND idFacturabeneficiario = $idfacturabeneficiario AND tipomovimiento = 1";
	$resConsultaFacturasPrestacionesConsumo = mysql_query($sqlConsultaFacturasPrestacionesConsumo,$db);

	$sqlConsultaFacturasPrestacionesCarencia = "SELECT * FROM facturasprestaciones WHERE idFactura = $idfactura AND idFacturabeneficiario = $idfacturabeneficiario AND tipomovimiento = 2";
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
	$("#buscaprestacion").attr('disabled', true);
	$("#fechaprestacion").inputmask("date");
	$("#referenciaunitario").inputmask('decimal', {digits: 2});
	$("#referenciatotal").inputmask('decimal', {digits: 2});
	$("#cantidad").inputmask('integer');
	$("#totalfacturado").inputmask('decimal', {digits: 2});
	$("#totaldebito").inputmask('decimal', {digits: 2});
	$("#totalcredito").inputmask('decimal', {digits: 2});
	$("#motivodebito").attr('disabled', true);
	$("#efectorpractica").attr('disabled', true);
	$("#efectorprofesional").attr('disabled', true);
	$("#integracion").hide();
	$("#cancelaintegracion").prop("checked",false);
	$('#datosintegracion').hide();
	$("#solicitadointegracion").inputmask('decimal', {digits: 2});
	$("#escuelaintegracion").inputmask('integer');
	$("#tipoescuelaintegracion").attr('disabled', true);
	$("#cueescuelaintegracion").attr('disabled', true);
	$("#agregarprestacion").attr('disabled', true);
	$("#agregarcarencia").attr('disabled', true);
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
			var nomencladorsur = $("#nomencladorsur").val();
			$.ajax({
				url: "buscaPrestacion.php",
				dataType: "json",
				data: {getPrestacion:request.term,idPrestador:idprestador,fechaPrestacion:fechaprestacion,contratoPrestador:contrato,nomencladorSur:nomencladorsur},
				success: function(data) {
					response(data);
				}
			});
		},
        minLength: 3,
		select: function(event, ui) {
			var idpracticadevuelta = ui.item.idpractica;
			var referenciadevuelto = ui.item.valor;
			var integraciondevuelto = ui.item.integracion;
			$("#idPractica").val(ui.item.idpractica);
			$("#esIntegracion").val(ui.item.integracion);
			$("#cantidad").val('');
			$("#referenciaunitario").val('0.00');
			$("#referenciatotal").val('0.00');
			$("#totalfacturado").val('');
			$("#totaldebito").val('0.00');
			$("#totalcredito").val('0.00');
			if(idpracticadevuelta==null) {
				$("#agregarcarencia").attr('disabled', false);
				$("#agregarprestacion").attr('disabled', true);
			} else {
				$("#referenciaunitario").val(ui.item.valor);
				if(referenciadevuelto==0.00) {
					$("#agregarcarencia").attr('disabled', false);
					$("#agregarprestacion").attr('disabled', true);
				} else {
					$("#agregarcarencia").attr('disabled', true);
					$("#agregarprestacion").attr('disabled', false);
				}
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
		}  
	});
	$("#cantidad").change(function(){
		if($("#cantidad").val()!='' && $("#cantidad").val()!=0) {
			var totalreferencia = $("#cantidad").val()*$("#referenciaunitario").val();
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
	var personeria = $("#personeria").val();
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

	$("#efectorpractica").autocomplete({
		source: function(request, response) {
			var idprestador = $("#idprestador").val();
			var idpersoneria = $("#personeria").val();
			$.ajax({
				url: "buscaEfector.php",
				dataType: "json",
				data: {getPersoneria:request.term,idPrestador:idprestador,idPersoneria:personeria},
				success: function(data) {
					response(data);
				}
			});
		},
        minLength: 4,
		select: function(event, ui) {
			var escirculo = ui.item.circulo;
			$("#idEfector").val(ui.item.idefector);
			$("#establecimientoCirculo").val(ui.item.circulo);
			if(escirculo==1) {
				$("#efectorprofesional").attr('placeholder','El Establecimiento es un Circulo, por favor ingrese el profesional que efectuo la prestacion');
				$("#efectorprofesional").attr('disabled', false);
			} else {
				$("#efectorprofesional").attr('disabled', true);
			}
		}
	});
	$("#cancelaintegracion").change(function(){
		if($("#cancelaintegracion").prop('checked') ) {
			$("#datosintegracion").show();
			var idpracticadevuelta = $("#idPractica").val();
			if(idpracticadevuelta==2462 || idpracticadevuelta==2471) {
				$("#tipoescuelaintegracion").attr('disabled', false);
				$("#cueescuelaintegracion option[value='']").prop('selected',true);
				$("#cueescuelaintegracion").attr('disabled', true);
			} else {
				$("#tipoescuelaintegracion").attr('disabled', true);
				$("#cueescuelaintegracion option[value='']").prop('selected',true);
				$("#cueescuelaintegracion").attr('disabled', true);
			}
		} else {
			$("#datosintegracion").hide();
			$("#solicitadointegracion").val('');
			$("#dependenciaintegracion").prop("checked",false);
			$("#tipoescuelaintegracion option[value='']").prop('selected',true);
			$("#cueescuelaintegracion option[value='']").prop('selected',true);
			$("#tipoescuelaintegracion").attr('disabled', true);
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
	$("#agregarprestacion").on("click", function() {
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
	<h3 style="margin-bottom:1px">ID Comprobante Interno: <?php echo $rowConsultaFactura['id'];?> - Fecha de Recepcion: <?php echo invertirFecha($rowConsultaFactura['fecharecepcion']);?></h3>
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
			<?php $existesur = 0;
			while ($rowConsultaNomenclador = mysql_fetch_assoc($resConsultaNomenclador)) {
				echo $rowConsultaNomenclador['nombre']."<br>";
				if($rowConsultaNomenclador['id']==7) {
					$existesur = 1;
				}
			} ?>
				<input name="nomencladorsur" type="hidden" id="nomencladorsur" size="2" value="<?php echo $existesur; ?>"/>
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
			<td align="left"><?php echo$descripcionTipo;?></td>
		</tr>
		<tr>
			<td align="right">Apellido y Nombre: </td>
			<td align="left"><?php echo $nombreBeneficiario;?></td>
			<td  rowspan="2"align="right">Delegacion: </td>
			<td align="left"><?php echo $rowConsultaFacturasBeneficiarios['codidelega'];?></td>
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
				<td colspan="5"><textarea name="buscaprestacion" rows="3" cols="100" id="buscaprestacion" placeholder="Ingrese un minimo de 3 caracteres para que se inicie la busqueda"></textarea>
								<input name="idPractica" type="hidden" id="idPractica" size="5" value=""/>
								<input name="esIntegracion" type="hidden" id="esIntegracion" size="2" value=""/></td>
			</tr>
			<tr>
				<td align="right"><strong>Cantidad</strong></td>
				<td align="left"><input name="cantidad" type="text" id="cantidad" size="3" value="" maxlength="2"/></td>
				<td align="right"><strong>Valor Ref. Unitario</strong></td>
				<td align="left"><input name="referenciaunitario" type="text" id="referenciaunitario" size="10" readonly="readonly" style="background-color:#CCCCCC" value=""/></td>
				<td align="right"><strong>Valor Ref. Total</strong></td>
				<td align="left"><input name="referenciatotal" type="text" id="referenciatotal" size="10" readonly="readonly" style="background-color:#CCCCCC" value=""/></td>
			</tr>
			<tr>
				<td align="right"><strong>Facturado</strong></td>
				<td align="left"><input name="totalfacturado" type="text" id="totalfacturado" size="10" value=""/></td>
				<td align="right"><strong>Debito</strong></td>
				<td align="left"><input name="totaldebito" type="text" id="totaldebito" size="10" value=""/></td>
				<td align="right"><strong>Credito</strong></td>
				<td align="left"><input name="totalcredito" type="text" id="totalcredito" size="10" readonly="readonly" style="background-color:#CCCCCC" value=""/></td>
			</tr>
			<tr>
	<td align="right"><strong>Motivo Debito</strong></td>
				<td colspan="5" align="left"><textarea name="motivodebito" rows="3" cols="100" id="motivodebito" placeholder="Motivo del Debito / Comentario / Observacion"></textarea></td>
			</tr>
			<tr>
				<td align="right"><strong>Efector</strong></td>
				<td colspan="5" align="left"><textarea name="efectorpractica" rows="3" cols="100" id="efectorpractica" placeholder=""></textarea>
											<input name="idEfector" type="hidden" id="idEfector" size="5" value=""/>
											<input name="establecimientoCirculo" type="hidden" id="establecimientoCirculo" size="2" value=""/></td>
			</tr>
			<tr>
				<td align="right"><strong>Prof. del Efector</strong></td>
				<td colspan="5" align="left"><input name="efectorprofesional" type="text" id="efectorprofesional" size="100" maxlength="100" placeholder="" value=""></td>
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
				<td align="left"><input name="solicitadointegracion" type="text" id="solicitadointegracion" size="10" value=""/>
				<td align="right"><strong>Dependencia ?</strong></td>
				<td align="left"><input name="dependenciaintegracion" type="checkbox" id="dependenciaintegracion" value="1"/>
				<td align="right"><strong>Escuela ?</strong></td>
				<td align="left"><select name="tipoescuelaintegracion" id="tipoescuelaintegracion">
									<option title="Seleccione tipo" value="">Seleccione tipo</option>
									<option title="ESCUELA COMUN (MENSUAL)" value="097">ESCUELA COMUN (MENSUAL)</option>
									<option title="ESCUELA ESPECIAL PRE PRIMARIA (MENSUAL)" value="098">ESCUELA ESPECIAL PRE PRIMARIA (MENSUAL)</option>
									<option title="ESCUELA ESPECIAL PRIMARIA (MENSUAL)" value="099">ESCUELA ESPECIAL PRIMARIA (MENSUAL)</option>
								</select>
				</td>
			</tr>
			<tr>
				<td colspan="5" align="right"></td>
				<td align="left"><select name="cueescuelaintegracion" id="cueescuelaintegracion">
									<option title="Seleccione CUE" value="">Seleccione CUE</option>
								</select>
				</td>
			</tr>
		</table>
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
		<?php while($rowConsultaFacturasPrestacionesConsumo = mysql_fetch_array($resConsultaFacturasPrestacionesConsumo)) { ?>
			<tr>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['fechapractica'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['idPractica'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['cantidad'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['totalfacturado'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['totaldebito'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['totalcredito'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['motivodebito'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['efectorpractica'];?></td>
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
		<?php while($rowConsultaFacturasPrestacionesCarencia = mysql_fetch_array($resConsultaFacturasPrestacionesCarencia)) { ?>
			<tr>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['fechapractica'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['idPractica'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['cantidad'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['totalfacturado'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['totaldebito'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['totalcredito'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['motivodebito'];?></td>
				<td><?php echo $rowConsultaFacturasPrestacionesCarencia['efectorpractica'];?></td>
				<td><input type="button" name="anulacarencia" id="anulacarencia" value="Anular Carencia" style="font-size:10px" onclick="javascript:anulaConsumoCarencia(<?php echo $rowConsultaFacturasPrestacionesCarencia['id'];?>,<?php echo $idfactura;?>,<?php echo $idfacturabeneficiario;?>)"/></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
</body>
</html>