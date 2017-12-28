<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$idcomprobante = 0;
$totalbeneficiarios = 0;
$totalconsumos = 0;
$totalcarencias = 0;
$totalfacturado = 0.00;
$totaldebito = 0.00;
$totalcredito = 0.00;
if(isset($_POST['idfactura'])) {
	//var_dump($_POST);
	$idcomprobante = $_POST['idfactura'];
}
if(isset($_GET['idfactura'])) {
	//var_dump($_GET);
	$idcomprobante = $_GET['idfactura'];
}
	$sqlConsultaFactura = "SELECT f.id, f.fecharecepcion, f.idPrestador, p.nombre, p.cuit, f.idTipocomprobante, t.descripcion, f.puntodeventa, f.nrocomprobante, f.fechacomprobante, f.idCodigoautorizacion, c.descripcioncorta, f.nroautorizacion, f.fechacorreo, f.diasvencimiento, f.fechavencimiento, f.importecomprobante, f.fechainicioliquidacion, f.usuarioliquidacion FROM facturas f, prestadores p, tipocomprobante t, codigoautorizacion c WHERE f.id = $idcomprobante AND f.idPrestador = p.codigoprestador AND f.idTipocomprobante = t.id AND f.idCodigoautorizacion = c.id";
	$resConsultaFactura = mysql_query($sqlConsultaFactura,$db);
	$rowConsultaFactura = mysql_fetch_array($resConsultaFactura);

	$sqlConsultaJurisdiccionPrestador = "SELECT p.codidelega, d.nombre FROM prestadorjurisdiccion p, delegaciones d WHERE p.codigoprestador = $rowConsultaFactura[idPrestador] and p.codidelega = d.codidelega";
	$resConsultaJurisdiccionPrestador = mysql_query($sqlConsultaJurisdiccionPrestador,$db);

	$sqlConsultaFacturasBeneficiarios = "SELECT f.*, t.apellidoynombre, t.cuil, t.codidelega FROM facturasbeneficiarios f, titulares t WHERE f.idFactura = $idcomprobante AND f.nroafiliado = t.nroafiliado";
	$resConsultaFacturasBeneficiarios = mysql_query($sqlConsultaFacturasBeneficiarios,$db);

	$sqlConsultaCarenciasBeneficiarios = "SELECT * FROM facturascarenciasbeneficiarios WHERE idFactura = $idcomprobante";
	$resConsultaCarenciasBeneficiarios = mysql_query($sqlConsultaCarenciasBeneficiarios,$db);
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
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#agregarbeneficiario").attr('disabled', true);
	$("#agregarcarencia").attr('disabled', true);
	$("#cerrarliquidacion").attr('disabled', true);
	$("#beneficiario").autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "buscaBeneficiario.php",
				dataType: "json",
				data: {getBeneficiaro:request.term},
				success: function(data) {
					response(data);
				}
			});
		},
        minLength: 4,
		select: function(event, ui) {
			var valordevuelto = ui.item.nroafiliado;
			$("#nroAfiliado").val(ui.item.nroafiliado);
			$("#tipoAfiliado").val(ui.item.tipoafiliado);
			$("#nroOrden").val(ui.item.nroorden);
			$("#codiDelega").val(ui.item.delegacion);
			if(valordevuelto==null) {
				$("#agregarcarencia").attr('disabled', false);
				$("#agregarbeneficiario").attr('disabled', true);
			} else {
				$("#agregarcarencia").attr('disabled', true);
				$("#agregarbeneficiario").attr('disabled', false);
			}
		}  
	});
	$("#agregarbeneficiario").on("click", function() {
		var idcomprobante = $("#idComprobante").val();
		var nroafiliado = $("#nroAfiliado").val();
		var tipoafiliado = $("#tipoAfiliado").val();
		var nroorden = $("#nroOrden").val();
		var codidelega = $("#codiDelega").val();
		$.blockUI({ message: "<h1>Agregando Beneficiario a la Liquidacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		$.ajax({
			url: "agregaBeneficiario.php",
			dataType: "json",
			data: {idComprobante:idcomprobante,nroAfiliado:nroafiliado,tipoAfiliado:tipoafiliado,nroOrden:nroorden,codiDelega:codidelega},
			success: function(data) {
				if(data.result == true){
					location.reload();
					$.unblockUI();
				} else {
					$.unblockUI();
					var cajadialogo = $('<div title="Aviso"><p>Ocurrio un error al intentar agregar beneficiario. Comuniquelo al Depto. de Sistemas.</p></div>');
					cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $("#agregarbeneficiario").attr('disabled', true); $("#agregarcarencia").attr('disabled', true);}});
				}
			}
		});
	});
	$("#listaBeneficiarios")
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
				6:{sorter:false, filter: false}
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
	var identidad = $( "#identidadbeneficiario" ), facturado = $( "#totalfacturado" ),  motivo = $( "#motivocarencia" ),
	allFields = $( [] ).add( identidad ).add( facturado ).add( motivo ),
	tips = $(".validateTips" );
	$("#agregarcarencia").on("click", function() {
		$("#totalfacturado").inputmask('decimal', {digits: 2});
		$("#totalfacturado").change(function(){
			 $("#totaldebito").val($("#totalfacturado").val());
			 $("#totalcredito").val(($("#totalfacturado").val()-$("#totaldebito").val()));
		});
		$("#formularioModal").dialog({
			modal: true,
			height: 580,
			width: 600,
			resizable: false,
			show: {effect: "blind",duration: 250},
			hide: {effect: "blind",duration: 250},
			buttons: {
				Ingresar: function() {
					validaCamposYAgregaCarencia();
				},
				Cancelar: function() {
					$(this).dialog("close");
				}
			},
			closeOnEscape: false,
			close: function(event, ui) {
				$("#agregarbeneficiario").attr('disabled', true);
				$("#agregarcarencia").attr('disabled', true);
			}
		});
	});
	function validaCamposYAgregaCarencia() {
		var validados = true;
		allFields.removeClass( "ui-state-error" );
		validados = validados && checkLength( identidad, "Identificacion", 1 );
		validados = validados && checkLength( facturado, "Total Facturado", 1 );
		validados = validados && checkLength( motivo, "Motivo", 1 );
		if(validados) {
			var idcomprobante = $("#idComprobante").val();
			var identidadbeneficiario = $("#identidadbeneficiario").val();
			var totalfacturado = $("#totalfacturado").val();
			var totaldebito = $("#totaldebito").val();
			var totalcredito = $("#totalcredito").val();
			var motivocarencia = $("#motivocarencia").val();
			$.blockUI({ message: "<h1>Agregando Carencia de Beneficiario a la Liquidacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
			$.ajax({
				url: "agregaCarencia.php",
				dataType: "json",
				data: {idComprobante:idcomprobante,identidadBeneficiario:identidadbeneficiario,totalFacturado:totalfacturado,totalDebito:totaldebito,totalCredito:totalcredito,motivoCarencia:motivocarencia},
				success: function(data) {
					if(data.result == true){
						location.reload();
						$.unblockUI();
					} else {
						$.unblockUI();
						var cajadialogo = $('<div title="Aviso"><p>Ocurrio un error al intentar agregar una carencia de beneficiario. Comuniquelo al Depto. de Sistemas.</p></div>');
						cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $("#formularioModal").dialog("close"); }});
					}
				}
			});
		}
	};
	function checkLength( o, n, min ) {
		if ( o.val().length < min ) {
			o.addClass( "ui-state-error" );
			updateTips( n + " no puede estar vacio." );
			return false;
		} else {
			return true;
		}
	};
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	};
	if($("#totalconsumos").val() >= $("#totalbeneficiarios").val()) {
		$("#cerrarliquidacion").attr('disabled', false);
		if(parseFloat($("#facturadototal").val()) != parseFloat($("#importecomprobante").val())) {
			$("#cerrarliquidacion").attr('disabled', true);
		} else {
			$("#cerrarliquidacion").attr('disabled', false);
			if(parseFloat($("#debitototal").val()) > parseFloat($("#importecomprobante").val())) {
				$("#cerrarliquidacion").attr('disabled', true);
			} else {
				$("#cerrarliquidacion").attr('disabled', false);
				if(parseFloat($("#creditototal").val()) > parseFloat($("#importecomprobante").val())) {
					$("#cerrarliquidacion").attr('disabled', true);
				} else {
					$("#cerrarliquidacion").attr('disabled', false);
				}
			}
		}
	} else {
		if($("#totalcarencias").val()!=0) {
			$("#cerrarliquidacion").attr('disabled', false);
			if(parseFloat($("#facturadototal").val()) != parseFloat($("#importecomprobante").val())) {
				$("#cerrarliquidacion").attr('disabled', true);
			} else {
				$("#cerrarliquidacion").attr('disabled', false);
				if(parseFloat($("#debitototal").val()) > parseFloat($("#importecomprobante").val())) {
					$("#cerrarliquidacion").attr('disabled', true);
				} else {
					$("#cerrarliquidacion").attr('disabled', false);
					if(parseFloat($("#creditototal").val()) > parseFloat($("#importecomprobante").val())) {
						$("#cerrarliquidacion").attr('disabled', true);
					} else {
						$("#cerrarliquidacion").attr('disabled', false);
					}
				}
			}
		} else {
			$("#cerrarliquidacion").attr('disabled', true);
		}
	}
});
function cargaConsumo(idfactura, idfacturabeneficiario) {
	param = "idFactura="+idfactura+"&idFacturabeneficiario="+idfacturabeneficiario;
	window.location.href="consumoBeneficiario.php?"+param;
};

function eliminaBeneficiario(idfactura, idfacturabeneficiario) {
	param = "idFactura="+idfactura+"&idFacturabeneficiario="+idfacturabeneficiario;
	$.blockUI({ message: "<h1>Anulando Beneficiario de la Liquidacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	window.location.href="anulaBeneficiario.php?"+param;
};

function anulaCarencia(idcarencia, idfactura) {
	param = "idCarencia="+idcarencia+"&idFactura="+idfactura;
	$.blockUI({ message: "<h1>Anulando Carencia de Beneficiario de la Liquidacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	window.location.href = "anulaCarencia.php?"+param;
};

function verificaExcepcion(nombreconsumo,nombreexcepcion) {
	if($(nombreexcepcion).prop('checked') ) {
		$(nombreconsumo).removeAttr('disabled');
	} else {
		$(nombreconsumo).attr('disabled', 'disabled');
	}
};
</script>
<style>
	.ui-dialog .ui-state-error { padding: .3em; }
	.validateTips { border: 1px solid transparent; padding: 0.3em; }
	.ui-autocomplete-loading { background: white url("../img/ui-anim_basic_16x16.gif") right center no-repeat; }
	.ui-menu .ui-menu-item a{ font-size:12px; }
</style>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloLiquidaciones.php'" />
</div>
<fieldset style="border-top:none;border-left:none;border-right:none">
<div align="center">
	<h2 style="margin-top:1px;margin-bottom:1px">Comprobante</h2>
</div>
<div align="center">
	<h3 style="margin-bottom:1px">ID Interno: <?php echo $rowConsultaFactura['id'];?> - Fecha de Recepcion: <?php echo invertirFecha($rowConsultaFactura['fecharecepcion']);?> - Fecha de Correo: <?php echo invertirFecha($rowConsultaFactura['fechacorreo']);?></h3><input name="idComprobante" type="hidden" id="idComprobante" size="8" value="<?php echo $rowConsultaFactura['id'];?>"/>
</div>
<div align="center">
	<div class="grilla" style="margin-top:10px; margin-bottom:10px">
	<table>
		<tr>
			<td colspan="3" class="title">Prestador</td>
		</tr>
		<tr>
			<td align="right">Codigo: </td>
			<td align="left"><?php echo $rowConsultaFactura['idPrestador'];?></td>
			<td align="center">Jurisdiccion</td>
		</tr>
		<tr>
			<td align="right">Nombre / Razon Social: </td>
			<td align="left"><?php echo $rowConsultaFactura['nombre'];?></td>
			<td rowspan="2" align="center">
			<?php while ($rowConsultaJurisdiccionPrestador = mysql_fetch_assoc($resConsultaJurisdiccionPrestador)) {
				echo $rowConsultaJurisdiccionPrestador['codidelega']." - ".$rowConsultaJurisdiccionPrestador['nombre']."<br>";
			} ?>
			</td>
		</tr>
		<tr>
			<td align="right">C.U.I.T.: </td>
			<td align="left"><?php echo $rowConsultaFactura['cuit'];?></td>
		</tr>
	</table>
	</div>
</div>
<div align="center">
	<div class="grilla" style="margin-top:10px; margin-bottom:10px">
	<table>
		<tr>
			<td colspan="4" class="title">Comprobante</td>
		</tr>
		<tr>
			<td><?php echo $rowConsultaFactura['descripcion'].' Nro.: '.$rowConsultaFactura['puntodeventa'].'-'.$rowConsultaFactura['nrocomprobante'];?></td>
			<td align="right">Fecha: </td>
			<td align="left"><?php echo invertirFecha($rowConsultaFactura['fechacomprobante']);?></td>
			<td><?php echo $rowConsultaFactura['descripcioncorta'].' Nro.: '.$rowConsultaFactura['nroautorizacion'];?></td>
		</tr>
		<tr>
			<td align="right" colspan="2"> Vencimiento a <?php echo $rowConsultaFactura['diasvencimiento'].' dias';?></td>
			<td align="right">Fecha Vto.: </td>
			<td align="left"><?php echo invertirFecha($rowConsultaFactura['fechavencimiento']);?></td>
		</tr>
		<tr>
			<td align="right" colspan="3">Importe: </td>
			<td align="left"><?php echo $rowConsultaFactura['importecomprobante'];?><input name="importecomprobante" type="hidden" id="importecomprobante" size="10" value="<?php echo $rowConsultaFactura['importecomprobante'];?>"/></td>
		</tr>
	</table>
	</div>
</div>
</fieldset>
<div align="center">
	<h2 style="margin-top:1px;margin-bottom:1px">Liquidacion</h2>
</div>
<div align="center">
	<h3 style="margin-bottom:1px">Liquidacion del Usuario @<?php echo $rowConsultaFactura['usuarioliquidacion'];?> - Fecha de Inicio: <?php echo invertirFecha($rowConsultaFactura['fechainicioliquidacion']);?></h3>
</div>
<div align="center">
	<table border="0">
		<tr>
			<td align="right"><strong>Buscar Beneficiario</strong></td>
			<td><textarea name="beneficiario" rows="3" cols="100" id="beneficiario" placeholder="Ingrese un minimo de 4 caracteres para que se inicie la busqueda"></textarea><input name="nroAfiliado" type="hidden" id="nroAfiliado" size="5" value=""/><input name="tipoAfiliado" type="hidden" id="tipoAfiliado" size="5" value=""/><input name="nroOrden" type="hidden" id="nroOrden" size="5" value=""/><input name="codiDelega" type="hidden" id="codiDelega" size="5" value=""/></td>
		</tr>
	</table>
</div>
<div align="center">
	<table border="0">
		<tr>
			<td><input type="button" name="agregarbeneficiario" id="agregarbeneficiario" value="Agregar Beneficiario"/></td>
			<td><input type="button" name="agregarcarencia" id="agregarcarencia" value="Agregar Carencia Beneficiario"/></td>
		</tr>
	</table>
</div>
<div align="center">
	<table id="listaBeneficiarios" class="tablesorter" style="font-size:14px; text-align:center">
		<thead>
			<tr>
				<th colspan="10">Beneficiarios</th>
			</tr>
			<tr>
				<th>Nro. Afiliado</th>
				<th>Tipo</th>
				<th>Apellido y Nombre</th>
				<th>C.U.I.L.</th>
				<th>Delegacion</th>
				<th>Total Facturado</th>
				<th>Total Debitos</th>
				<th>Total Creditos</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
		<?php while($rowConsultaFacturasBeneficiarios = mysql_fetch_array($resConsultaFacturasBeneficiarios)) {
					$totalbeneficiarios++;
					$totalfacturado = $totalfacturado + $rowConsultaFacturasBeneficiarios['totalfacturado'];
					$totaldebito = $totaldebito + $rowConsultaFacturasBeneficiarios['totaldebito'];
					$totalcredito = $totalcredito + $rowConsultaFacturasBeneficiarios['totalcredito'];
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
					$botonexcepcion = 'excepcion'.$rowConsultaFacturasBeneficiarios['id'];
					$botonconsumo = 'consumo'.$rowConsultaFacturasBeneficiarios['id'];
					$botonanulacion = 'anula'.$rowConsultaFacturasBeneficiarios['id']; ?>
			<tr>
				<td><?php echo $rowConsultaFacturasBeneficiarios['nroafiliado'];?></td>
				<td><?php echo $descripcionTipo;?></td>
				<td><?php echo $nombreBeneficiario;?></td>
				<td><?php echo $cuilBeneficiario;?></td>
				<td><?php echo $rowConsultaFacturasBeneficiarios['codidelega'];?></td>
				<td><?php echo $rowConsultaFacturasBeneficiarios['totalfacturado'];?></td>
				<td><?php echo $rowConsultaFacturasBeneficiarios['totaldebito'];?></td>
				<td><?php echo $rowConsultaFacturasBeneficiarios['totalcredito'];?></td>
				<td>
				<?php
					if($rowConsultaFacturasBeneficiarios['consumoprestacional'] != 0) {
						$totalconsumos++;
					?>
					<input name="<?php echo $botonconsumo;?>" type="button" id="<?php echo $botonconsumo;?>" value="Consumo Prestacional" style="font-size:10px" onclick="javascript:cargaConsumo(<?php echo $idcomprobante;?>,<?php echo $rowConsultaFacturasBeneficiarios['id'];?>)"/>
				<?php
					} else {
						if($rowConsultaFacturasBeneficiarios['excepcionjurisdiccion'] == 0) { ?>
					<input name="<?php echo $botonconsumo;?>" type="button" id="<?php echo $botonconsumo;?>" value="Consumo Prestacional" style="font-size:10px" onclick="javascript:cargaConsumo(<?php echo $idcomprobante;?>,<?php echo $rowConsultaFacturasBeneficiarios['id'];?>)"/>
					<input name="<?php echo $botonanulacion;?>" type="button" id="<?php echo $botonanulacion;?>" value="Anular Beneficiario" style="font-size:10px" onclick="javascript:eliminaBeneficiario(<?php echo $idcomprobante;?>,<?php echo $rowConsultaFacturasBeneficiarios['id'];?>)"/>
				<?php
						} else { ?>
					Excepcion <input name="<?php echo $botonexcepcion;?>" type="checkbox" id="<?php echo $botonexcepcion;?>" value="<?php echo $rowConsultaFacturasBeneficiarios['id'];?>" onclick="javascript:verificaExcepcion(<?php echo $botonconsumo;?>, <?php echo $botonexcepcion;?>)"/>
					<input name="<?php echo $botonconsumo;?>" type="button" id="<?php echo $botonconsumo;?>" value="Consumo Prestacional" style="font-size:10px" disabled="disabled" onclick="javascript:cargaConsumo(<?php echo $idcomprobante;?>,<?php echo $rowConsultaFacturasBeneficiarios['id'];?>)"/>
					<input name="<?php echo $botonanulacion;?>" type="button" id="<?php echo $botonanulacion;?>" value="Anular Beneficiario" style="font-size:10px" onclick="javascript:eliminaBeneficiario(<?php echo $idcomprobante;?>,<?php echo $rowConsultaFacturasBeneficiarios['id'];?>)"/>
				<?php
						}
					} ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
<div align="center">
	<table id="listaCarencias" class="tablesorter" style="font-size:14px; text-align:center">
		<thead>
			<tr>
				<th colspan="6">Carencias de Beneficiarios</th>
			</tr>
			<tr>
				<th>Identificacion Beneficiario</th>
				<th>Total Facturado</th>
				<th>Total Debito</th>
				<th>Total Credito</th>
				<th>Motivo de Carencia / Comentario / Observacion</th>
				<th>Accion</th>
			</tr>
		</thead>
		<tbody>
		<?php while($rowConsultaCarenciasBeneficiarios = mysql_fetch_array($resConsultaCarenciasBeneficiarios)) { 
					$totalcarencias++;
					$totalfacturado = $totalfacturado + $rowConsultaCarenciasBeneficiarios['totalfacturado'];
					$totaldebito = $totaldebito + $rowConsultaCarenciasBeneficiarios['totaldebito'];
					$totalcredito = $totalcredito + $rowConsultaCarenciasBeneficiarios['totalcredito'];
		?>
			<tr>
				<td><?php echo $rowConsultaCarenciasBeneficiarios['identidadbeneficiario'];?></td>
				<td><?php echo $rowConsultaCarenciasBeneficiarios['totalfacturado'];?></td>
				<td><?php echo $rowConsultaCarenciasBeneficiarios['totaldebito'];?></td>
				<td><?php echo $rowConsultaCarenciasBeneficiarios['totalcredito'];?></td>
				<td><?php echo $rowConsultaCarenciasBeneficiarios['motivocarencia'];?></td>
				<td><input type="button" name="anulacarencia" id="anulacarencia" value="Anular Carencia" style="font-size:10px" onclick="javascript:anulaCarencia(<?php echo $rowConsultaCarenciasBeneficiarios['id'];?>,<?php echo $idcomprobante;?>)"/></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
<div id="formularioModal" title="Carencia de Beneficiario" style="display:none">
	<p class="validateTips">Todos los campos son obligatorios.</p>
	<form>
		<fieldset>
			<label for="identidadbeneficiario">Datos Identificacion</label>
			<textarea name="identidadbeneficiario" rows="2" cols="50" id="identidadbeneficiario" class="text ui-widget-content ui-corner-all" placeholder="Ingrese Apellido y Nombre y DNI o CUIL"></textarea>
			<p></p>
			<label for="totalfacturado">Total Facturado</label>
			<input name="totalfacturado" type="text" id="totalfacturado" class="text ui-widget-content ui-corner-all" size="10" value=""/>
			<p></p>
			<label for="totaldebito">Total Debito</label>
			<input name="totaldebito" type="text" id="totaldebito" class="text ui-widget-content ui-corner-all" size="10" readonly="readonly" value=""/>
			<p></p>
			<label for="totalcredito">Total Credito</label>
			<input name="totalcredito" type="text" id="totalcredito" class="text ui-widget-content ui-corner-all" size="10" readonly="readonly" value=""/>
			<p></p>
			<label for="motivocarencia">Motivo / Comentario / Observacion</label>
			<textarea name="motivocarencia" rows="2" cols="50" id="motivocarencia" class="text ui-widget-content ui-corner-all" placeholder="Ingrese el motivo de la carencia y/o Comentario u Observacion"></textarea>
		</fieldset>
	</form>
</div>
<div align="center">
	<input name="totalbeneficiarios" type="hidden" id="totalbeneficiarios" size="5" value="<?php echo $totalbeneficiarios;?>"/>
	<input name="totalconsumos" type="hidden" id="totalconsumos" size="5" value="<?php echo $totalconsumos;?>"/>
	<input name="totalcarencias" type="hidden" id="totalcarencias" size="5" value="<?php echo $totalcarencias;?>"/>
	<input name="facturadototal" type="hidden" id="facturadototal" size="5" value="<?php echo $totalfacturado;?>"/>
	<input name="debitototal" type="hidden" id="debitototal" size="5" value="<?php echo $totaldebito;?>"/>
	<input name="creditototal" type="hidden" id="creditototal" size="5" value="<?php echo $totalcredito;?>"/>
	<input type="button" name="cerrarliquidacion" id="cerrarliquidacion" value="Cerrar Liquidacion"/>
</div>
</body>
</html>