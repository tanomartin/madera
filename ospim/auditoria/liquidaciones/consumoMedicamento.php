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
	$benetotalfacturado = 0.00;
	$benetotaldebito = 0.00;
	$benetotalcredito = 0.00;
	$sqlConsultaFacturasPrestacionesConsumo = "SELECT f.*, m.codigo, m.nombre FROM facturasprestaciones f, medicamentos m WHERE idFactura = $idfactura AND idFacturabeneficiario = $idfacturabeneficiario AND tipomovimiento = 3 AND f.idPractica = m.codigo ORDER BY f.id DESC";
	$resConsultaFacturasPrestacionesConsumo = mysql_query($sqlConsultaFacturasPrestacionesConsumo,$db);
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
	$("#referenciaunitario").inputmask('decimal', {digits: 2});
	$("#referenciaconformado").inputmask('decimal', {digits: 2});
	$("#cantidad").inputmask('decimal', {digits: 3});
	$("#referenciatotal").inputmask('decimal', {digits: 2});
	$("#referenciabonipesos").inputmask('decimal', {digits: 2});
	$("#referenciaboniporce").inputmask('decimal', {digits: 2});
	$("#totalfacturado").inputmask('decimal', {digits: 2});
	$("#totaldebito").inputmask('decimal', {digits: 2});
	$("#totalcredito").inputmask('decimal', {digits: 2});
	$("#buscamedicamento").attr('disabled', true);
	$("#efectorpractica").attr('disabled', true);
	$("#motivodebito").attr('disabled', true);
	$("#agregarmedicamento").attr('disabled', true);
	var infoconformado = 'Info';
	$('#infoconformado').attr('title', infoconformado);
	var personeria = $("#personeria").val();
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
			$("#buscamedicamento").attr('disabled', false);
		} else {
			$("#buscamedicamento").attr('disabled', true);
		}
	});
	$("#buscamedicamento").autocomplete({
		source: function(request, response) {
			var idprestador = $("#idprestador").val();
			var fechaprestacion = $("#fechaprestacion").val();
			$.ajax({
				url: "buscaMedicamento.php",
				dataType: "json",
				data: {getPrestacion:request.term,idPrestador:idprestador,fechaPrestacion:fechaprestacion},
				success: function(data) {
					response(data);
				}
			});
		},
        minLength: 3,
		select: function(event, ui) {
			var idpracticadevuelta = ui.item.idpractica;
			var referenciadevuelto = ui.item.valor;
			$("#idPractica").val(ui.item.idpractica);
			$("#efectorpractica").val('');
			$("#efectorpractica").attr('disabled', true);
			$("#cantidad").val('');
			$("#referenciaunitario").val('');
			$("#referenciaconformado").val('');
			$("#referenciatotal").val('');
			$("#referenciabonipesos").val('');
			$("#referenciaboniporce").val('');
			$("#totalfacturado").val('');
			$("#totaldebito").val('');
			$("#totalcredito").val('');
			if(personeria == 6) {
				$("#efectorpractica").attr('placeholder','Ingrese un minimo de 4 caracteres para iniciar la busqueda de la Farmacia de la red que expendio el medicamento');
				$("#efectorpractica").attr('disabled', false);
			} else {
				$("#efectorpractica").attr('placeholder','');
				$("#efectorpractica").attr('disabled', true);
			}
			infoconformado = infoconformado.replace(': Medicamento','');
			$('#infoconformado').attr('title', infoconformado);
			if(idpracticadevuelta==null) {
				$("#agregarmedicamento").attr('disabled', true);
			} else {
				$("#referenciaunitario").val(ui.item.valor);
				$("#referenciaconformado").val(ui.item.valor);
				infoconformado = infoconformado+': Medicamento';
				$('#infoconformado').attr('title', infoconformado);
				if(referenciadevuelto==0.00) {
					$("#agregarmedicamento").attr('disabled', true);
				} else {
					$("#agregarmedicamento").attr('disabled', false);
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
			$("#idEfector").val(ui.item.idefector);
		}
	});
	$("#cantidad").change(function(){
		if($("#cantidad").val()!='' && $("#cantidad").val()!=0) {
			var totalreferencia = $("#cantidad").val()*$("#referenciaconformado").val();
			if(totalreferencia==0.00) {
				$("#referenciatotal").val('0.00');
				$("#referenciabonipesos").val('0.00');
				$("#referenciaboniporce").val('0.00');
			} else {
				$("#referenciatotal").val(totalreferencia);
				$("#referenciabonipesos").val('0.00');
				$("#referenciaboniporce").val('0.00');
			}
			if($("#totalfacturado").val()!='' && $("#totalfacturado").val()!=0.00) {
				var calculodebito = $("#totalfacturado").val()-$("#referenciatotal").val();
				var calculobonifi = $("#referenciatotal").val()-$("#totalfacturado").val();
				var calculobonifiporc = parseFloat((calculobonifi*100.00)) / parseFloat($("#referenciatotal").val());
				if(calculodebito > 0.00) {
					$("#totaldebito").val(calculodebito);
					if($("#idPractica").val()=='') {
						$("#totaldebito").attr('readonly',"readonly");
					} else {
						$("#totaldebito").removeAttr('readonly');
					}
					$("#motivodebito").attr('disabled', false);
				} else {
					$("#totaldebito").val(0.00);
					$("#motivodebito").attr('disabled', true);
				}
				if(calculobonifi > 0.00) {
					$("#referenciabonipesos").val(calculobonifi);
					$("#referenciaboniporce").val(calculobonifiporc);
				} else {
					$("#referenciabonipesos").val(0.00);
					$("#referenciaboniporce").val(0.00);
				}
				$("#totalcredito").val(($("#totalfacturado").val()-$("#totaldebito").val()));
			} else {
				$("#totaldebito").val('0.00');
				$("#totalcredito").val('0.00');
				$("#referenciabonipesos").val('0.00');
				$("#referenciaboniporce").val('0.00');
				$("#motivodebito").attr('disabled', true);
			}
		} else {
			$("#referenciatotal").val('0.00');
			$("#referenciabonipesos").val('0.00');
			$("#referenciaboniporce").val('0.00');
		}
	});
	$("#totalfacturado").change(function(){
		if($("#totalfacturado").val()!='' && $("#totalfacturado").val()!=0.00) {
			var calculodebito = $("#totalfacturado").val()-$("#referenciatotal").val();
			var calculobonifi = $("#referenciatotal").val()-$("#totalfacturado").val();
			var calculobonifiporc = parseFloat((calculobonifi*100.00)) / parseFloat($("#referenciatotal").val());
			if(calculodebito > 0.00) {
				$("#totaldebito").val(calculodebito);
				if($("#idPractica").val()=='') {
					$("#totaldebito").attr('readonly',"readonly");
				} else {
					$("#totaldebito").removeAttr('readonly');
				}
				$("#motivodebito").attr('disabled', false);
			} else {
				$("#totaldebito").val(0.00);
				$("#motivodebito").attr('disabled', true);
			}
			if(calculobonifi > 0.00) {
				$("#referenciabonipesos").val(calculobonifi);
				$("#referenciaboniporce").val(calculobonifiporc);
			} else {
				$("#referenciabonipesos").val(0.00);
				$("#referenciaboniporce").val(0.00);
			}
			$("#totalcredito").val(($("#totalfacturado").val()-$("#totaldebito").val()));
		} else {
			$("#totaldebito").val('0.00');
			$("#totalcredito").val('0.00');
			$("#referenciabonipesos").val('0.00');
			$("#referenciaboniporce").val('0.00');
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
			if($("#idPractica").val()!='') {
				$("#agregarmedicamento").attr('disabled', false);
			} else {
				$("#agregarmedicamento").attr('disabled', true);
			}
		} else {
			$("#agregarmedicamento").attr('disabled', true);
		}
		$("#totalcredito").val(calculocredito);
	});

	$("#agregarmedicamento").on("click", function() {
		if($("#cantidad").val()=='') {
			var cajadialogo = $('<div title="Aviso"><p>Debe ingresar la Cantidad/Unidades del Medicamento.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close:function(event, ui) { $('#cantidad').focus(); }});
			return;
		}
		if($("#totalfacturado").val()=='') {
			var cajadialogo = $('<div title="Aviso"><p>Debe ingresar el Total Facturado del Medicamento.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close:function(event, ui) { $('#totalfacturado').focus(); }});
			return;
		}
		if($("#totaldebito").val()!=0) {
			if($("#motivodebito").val()=='') {
				var cajadialogo = $('<div title="Aviso"><p>Debe ingresar el Motivo de Debito del Medicamento.</p></div>');
				cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close:function(event, ui) { $('#motivodebito').focus(); }});
				return;
			}
		}
		var datosform = $("form#consumoPrestacional").serialize();
		$.blockUI({ message: "<h1>Agregando Medicamento a la Liquidacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		$.ajax({
			type: "POST",
			url: "agregaMedicamento.php",
			data: datosform,
			dataType: 'json',
			success: function(data) {
				if(data.result == true){
					location.reload();
					$.unblockUI();
				} else {
					$.unblockUI();
					var cajadialogo = $('<div title="Aviso"><p>Ocurrio un error al intentar agregar un medicamento. Comuniquelo al Depto. de Sistemas.</p></div>');
					cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $("#agregarmedicamento").attr('disabled', true); }});
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
	$("#parcialesBeneficiario")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers: {
				0:{sorter:false, filter: false},
				1:{sorter:false, filter: false},
				2:{sorter:false, filter: false},
				3:{sorter:false, filter: false},
				4:{sorter:false, filter: false},
				5:{sorter:false, filter: false},
				6:{sorter:false, filter: false},
				7:{sorter:false, filter: false},
				8:{sorter:false, filter: false},
				9:{sorter:false, filter: false}
			},
			widgets: ["zebra"], 
	});
});
function consultaPrestador(dire) {
	a=window.open(dire,'',
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1000, height=850, top=10, left=10");
};
function anulaConsumoCarencia(idconsumocarencia, idfactura, idfacturabeneficiario) {
	param = "idConsumoCarencia="+idconsumocarencia+"&idFactura="+idfactura+"&idFacturaBeneficiario="+idfacturabeneficiario+"&origenAnulacion=M";
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
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'continuarLiquidacionMedicamento.php?idfactura=<?php echo $rowConsultaFactura['id'];?>'" />
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
			<td colspan="3" class="title">Prestador
			<input name="consultaprestador" type="button" id="consultaprestador" value="Ficha" onclick="consultaPrestador('../prestadores/abm/prestador.php?codigo=<?php echo $rowConsultaPrestador['codigoprestador']; ?>')"/>
			</td>
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
			<td align="right"></td>
			<td align="left"></td>
			<td align="center">
			<?php while ($rowConsultaNomenclador = mysql_fetch_assoc($resConsultaNomenclador)) {
				echo $rowConsultaNomenclador['nombre']."<br>";
			} ?>
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
	<h2 style="margin-top:1px;margin-bottom:1px">Consumo Farmaceutico </h2>
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
				<td align="right"><strong>Buscar Medicamento </strong></td>
				<td colspan="5"><textarea name="buscamedicamento" rows="3" cols="125" id="buscamedicamento" placeholder="Ingrese un minimo de 3 caracteres para que se inicie la busqueda"></textarea>
				<input name="idPractica" type="hidden" id="idPractica" size="5" value=""/>
				<input name="referenciaunitario" type="hidden" id="referenciaunitario" size="5" value="0.00"/>
				</td>
			</tr>
			<tr>
				<td align="right"><strong>Farmacia Expendedora </strong></td>
				<td colspan="5" align="left"><textarea name="efectorpractica" rows="3" cols="125" id="efectorpractica" placeholder=""></textarea>
											<input name="idEfector" type="hidden" id="idEfector" size="5" value=""/>
				</td>
			</tr>
			<tr>
				<td align="right" colspan="4"></td>
				<td align="right"><i id="infoconformado" style="font-size: 15px" title="" class="ui-icon ui-icon-info"></i></td>
				<td align="left"><strong>Ref. Valor Unitario</strong>
			  <input name="referenciaconformado" type="text" id="referenciaconformado" size="5" readonly="readonly" style="background-color:#CCCCCC" value="0.00"/></td>
			</tr>
			<tr>
				<td align="right"><strong>Cantidad / Unidades</strong></td>
				<td align="left"><input name="cantidad" type="text" id="cantidad" size="5" value="" maxlength="7"/></td>
				<td align="right"><strong>Facturado</strong></td>
				<td align="left"><input name="totalfacturado" type="text" id="totalfacturado" size="5" maxlength="9" value=""/></td>
				<td align="right"><i id="infototal" style="font-size: 15px" title="" class="ui-icon ui-icon-info"></i></td>
				<td align="left"><strong>Ref. Valor Total</strong>
			  <input name="referenciatotal" type="text" id="referenciatotal" size="5" readonly="readonly" style="background-color:#CCCCCC" value="0.00"/></td>

			</tr>
			<tr>
				<td align="right"><strong>Debito</strong></td>
				<td align="left"><input name="totaldebito" type="text" id="totaldebito" size="5" maxlength="9" value=""/></td>
				<td align="right"><strong>Credito</strong></td>
				<td align="left"><input name="totalcredito" type="text" id="totalcredito" size="5" readonly="readonly" style="background-color:#CCCCCC" value="0.00"/></td>
				<td align="right"><i id="infobonificacion" style="font-size: 15px" title="" class="ui-icon ui-icon-info"></i></td>
				<td align="left"><strong>Ref. Bonificacion</strong>
				 - En Pesos 
				<input name="referenciabonipesos" type="text" id="referenciabonipesos" size="5" readonly="readonly" style="background-color:#CCCCCC" value="0.00"/>
				 - En Porcentaje Aprox.
				<input name="referenciaboniporce" type="text" id="referenciaboniporce" size="5" readonly="readonly" style="background-color:#CCCCCC" value="0.00"/></td>
			</tr>
			<tr>
	<td align="right"><strong>Motivo Debito</strong></td>
				<td colspan="5" align="left"><textarea name="motivodebito" rows="3" cols="125" id="motivodebito" placeholder="Motivo del Debito / Comentario / Observacion"></textarea></td>
			</tr>
		</table>
	</div>
	<div align="center">
		<table border="0">
			<tr>
				<td><input type="button" name="agregarmedicamento" id="agregarmedicamento" value="Agregar Medicamento"/></td>
			</tr>
		</table>
	</div>
</form> 
<div align="center">
	<table id="listaConsumos" class="tablesorter" style="font-size:14px; text-align:center">
		<thead>
			<tr>
				<th colspan="10">Medicamentos Ingresados</th>
			</tr>
			<tr>
				<th>Fecha</th>
				<th>Medicamento</th>
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
					$benetotalfacturado = $benetotalfacturado + $rowConsultaFacturasPrestacionesConsumo['totalfacturado'];;
					$benetotaldebito = $benetotaldebito + $rowConsultaFacturasPrestacionesConsumo['totaldebito'];
					$benetotalcredito = $benetotalcredito + $rowConsultaFacturasPrestacionesConsumo['totalcredito'];
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
				<td><?php echo $rowConsultaFacturasPrestacionesConsumo['codigo'].'-'.$rowConsultaFacturasPrestacionesConsumo['nombre'];?></td>
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
	<table id="parcialesBeneficiario" class="tablesorter" style="font-size:14px; text-align:center">
		<thead>
			<tr>
				<th colspan="3">Parciales Beneficiario</th>
			</tr>
			<tr>
				<th>Facturado: <?php echo $benetotalfacturado;?></th>
				<th>Debito: <?php echo $benetotaldebito;?></th>
				<th>Credito: <?php echo $benetotalcredito;?></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
</body>
</html>