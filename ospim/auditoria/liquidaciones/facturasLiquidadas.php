<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$facturasintegracion = 0;
$notintegracion = '0,';
$sqlFacturasIntegracion="SELECT f.id as idintegracion FROM facturas f, facturasprestaciones p, facturasintegracion i WHERE f.fechacierreliquidacion != '0000-00-00 00:00:00' AND f.autorizacionpago = 0 AND f.usuarioliquidacion = '$_SESSION[usuario]' AND f.id = p.idFactura AND p.id = i.idFacturaprestacion group by idintegracion order by idintegracion";
$resFacturasIntegracion = mysql_query($sqlFacturasIntegracion,$db);
$facturasintegracion = mysql_num_rows($resFacturasIntegracion);
if($facturasintegracion != 0) {
	while($rowFacturasIntegracion=mysql_fetch_array($resFacturasIntegracion)) {
		$notintegracion .= $rowFacturasIntegracion['idintegracion'].',';
	}
}
$notintegracion = 'not in('.substr($notintegracion, 0, -1).')';
$totalfacturasagrupadas = 0;
$sqlFacturasAgrupadas="SELECT f.id, p.nombre, p.cuit, COUNT(f.id) AS cantidadfacturas, SUM(f.importeliquidado) AS totalimporte FROM facturas f, prestadores p WHERE f.id $notintegracion AND f.fechacierreliquidacion != '0000-00-00 00:00:00' AND f.autorizacionpago = 0 AND f.usuarioliquidacion = '$_SESSION[usuario]' AND f.idPrestador = p.codigoprestador GROUP BY p.cuit ORDER BY p.cuit";
$resFacturasAgrupadas = mysql_query($sqlFacturasAgrupadas,$db);
$totalfacturasagrupadas = mysql_num_rows($resFacturasAgrupadas);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Liquidaciones :.</title>
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#procesar").attr('disabled', true);
	var totalfacturasagrupadas = $("#totalfacturasagrupadas").val();
	if(totalfacturasagrupadas==0) {
		$("#procesar").attr('disabled', true);
	} else {
		$("#procesar").attr('disabled', false);
	}
	$("#procesar").on("click", function() {
		var datosform = $("form#generaAutoriCaratu").serialize();
		$.blockUI({ message: "<h1>Procesando Autorizaciones y Caratulas... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		$.ajax({
			type: "POST",
			url: "generaAutoriCaratu.php",
			data: datosform,
			dataType: 'json',
			success: function(data) {
				if(data.result == true){
					$.unblockUI();
					var contenidopdf = "<object id='pdfObject' type='application/pdf' data='mostrarCaratula.php?rutaarchivo="+data.archivopdf+"'width='100%' height='100%'></object>";
					var cajadialogo = $('<div title="Caratulas"></div>').html(contenidopdf);
					cajadialogo.dialog({modal: true, width: 920, height: 920, show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) {location.reload();}});
				} else {
					$.unblockUI();
					var cajadialogo = $('<div title="Aviso"><p>No hubo selecciones para la generacion de Caratulas.</p></div>');
					cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) {location.reload();}});
				}
			}
		});
	});
});

function muestraDetalle(cuit,cantidad) {
	var ocultar = '#ocultar'+cuit;
	var mostrar = '#mostrar'+cuit;
	$(mostrar).hide();
	$(ocultar).show();
	var tit1 = '#tit1'+cuit;
	var tit2 = '#tit2'+cuit;
	$(tit1).css('display', '');
	$(tit2).css('display', '');
	var i=0;
	while(i<cantidad) {
		var factura = '#fact'+i+cuit;
		$(factura).css('display', '');
		i++;
	};
};

function ocultaDetalle(cuit,cantidad) {
	var ocultar = '#ocultar'+cuit;
	var mostrar = '#mostrar'+cuit;
	$(mostrar).show();
	$(ocultar).hide();
	var tit1 = '#tit1'+cuit;
	var tit2 = '#tit2'+cuit;
	$(tit1).css('display', 'none');
	$(tit2).css('display', 'none');
	var i=0;
	while(i<cantidad) {
		var factura = '#fact'+i+cuit;
		$(factura).css('display', 'none');
		i++;
	};
};
</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'menuLiquidaciones.php'" />
</div>
<div align="center">
	<h1>Liquidacion Prestadores</h1>
</div>
<div id="facturasusuario" align="center">
	<h2>Facturas Liquidadas por el Usuario @<?php echo $_SESSION['usuario'];?></h2><input name="totalfacturasagrupadas" type="hidden" id="totalfacturasagrupadas" size="10" value="<?php echo $totalfacturasagrupadas;?>"/>
</div>
<form id="generaAutoriCaratu">
	<div align="center">
		<div class="grilla" style="margin-top:10px; margin-bottom:10px">
	
			<table width="1048" id="listaFacturasUsuario" style="text-align:center; width:1000px">
						<?php while($rowFacturasAgrupadas = mysql_fetch_array($resFacturasAgrupadas)) { 
							$botonocultar='ocultar'.$rowFacturasAgrupadas['cuit'];
							$botonmostrar='mostrar'.$rowFacturasAgrupadas['cuit'];
							$trTitulo1='tit1'.$rowFacturasAgrupadas['cuit'];
							$trTitulo2='tit2'.$rowFacturasAgrupadas['cuit'];
						?>
				<thead>
					<tr>
						<th colspan="10" style="color:#00557F">Prestador</th>
					</tr>
					<tr>
						<th colspan="2" style="color:#00557F">C.U.I.T.</th>
						<th colspan="2" style="color:#00557F">Nombre</th>
						<th colspan="2" style="color:#00557F">Cantidad Facturas</th>
						<th colspan="2" style="color:#00557F">Total a Pagar</th>
						<th colspan="2" style="color:#00557F">Accion</th>
					</tr>
					<tr>
						<th colspan="2"><?php echo $rowFacturasAgrupadas['cuit'];?></th>
						<th colspan="2"><?php echo $rowFacturasAgrupadas['nombre'];?></th>
						<th colspan="2"><?php echo $rowFacturasAgrupadas['cantidadfacturas'];?></th>
						<th colspan="2"><?php echo $rowFacturasAgrupadas['totalimporte'];?></th>
						<th colspan="2"><input name="<?php echo $botonocultar;?>" type="button" id="<?php echo $botonocultar;?>" value="Ocultar Facturas" style="display:none" onclick="javascript:ocultaDetalle(<?php echo $rowFacturasAgrupadas['cuit'];?>, <?php echo $rowFacturasAgrupadas['cantidadfacturas'];?>)"/><input name="<?php echo $botonmostrar;?>" type="button" id="<?php echo $botonmostrar;?>" value="Mostrar Facturas" onclick="javascript:muestraDetalle(<?php echo $rowFacturasAgrupadas['cuit'];?>, <?php echo $rowFacturasAgrupadas['cantidadfacturas'];?>)"/></th>
					</tr>
					<tr id="<?php echo $trTitulo1;?>" style="display:none">
						<th colspan="8">Factura</th>
						<th colspan="2">Acciones</th>
					</tr>
					<tr id="<?php echo $trTitulo2;?>" style="display:none">
						<th width="83">ID Interno</th>
						<th width="37">Nro.</th>
						<th width="50">Fecha</th>
						<th width="83">Facturado</th>
						<th width="103">Vencimiento</th>
						<th width="63">Debitos</th>
						<th width="81">Liquidado</th>
						<th width="96">Fecha Cierre</th>
						<th width="140">Autorizacion Pago</th>
						<th width="268">Caratula</th>
					</tr>
				</thead>
				<tbody>
							<?php
								$sqlFacturasUsuario = "SELECT p.nombre, p.cuit, f.id, f.puntodeventa, f.nrocomprobante, f.fechacomprobante, f.importecomprobante, f.fechavencimiento, f.totaldebito, f.totalcredito, f.importeliquidado, f.fechacierreliquidacion, f.autorizacionpago FROM facturas f, prestadores p WHERE f.fechacierreliquidacion != '0000-00-00 00:00:00' AND f.autorizacionpago = 0 AND f.usuarioliquidacion = '$_SESSION[usuario]' AND p.cuit = $rowFacturasAgrupadas[cuit] AND f.idPrestador = p.codigoprestador ORDER by p.cuit";
								$resFacturasUsuario = mysql_query($sqlFacturasUsuario,$db);
								$totalfacturasusuario = mysql_num_rows($resFacturasUsuario);
								$i=0;
								while($rowFacturasUsuario = mysql_fetch_array($resFacturasUsuario)) { 
									$trFactura='fact'.$i.$rowFacturasUsuario['cuit'];
								?>
								<tr id="<?php echo $trFactura;?>" style="display:none">
									<td><?php echo $rowFacturasUsuario['id'];?></td>
									<td><?php echo $rowFacturasUsuario['puntodeventa'].'-'.$rowFacturasUsuario['nrocomprobante'];?></td>
									<td><?php echo invertirFecha($rowFacturasUsuario['fechacomprobante']);?></td>
									<td><?php echo $rowFacturasUsuario['importecomprobante'];?></td>
									<td><?php echo invertirFecha($rowFacturasUsuario['fechavencimiento']);?></td>
									<td><?php echo $rowFacturasUsuario['totaldebito'];?></td>
									<td><?php echo $rowFacturasUsuario['importeliquidado'];?></td>
									<td><?php echo invertirFecha($rowFacturasUsuario['fechacierreliquidacion']);?></td>
									<td><input name="idFactura[]" type="hidden" id="idFactura[]" size="10" value="<?php echo $rowFacturasUsuario['id'];?>"/>
										<input name="autorizacion[]" type="checkbox" id="autorizacion[]" value="<?php echo $rowFacturasUsuario['id'];?>"/>
									</td>
									<td>
										<label><input type="radio" name="<?php echo 'caratula'.$rowFacturasUsuario['id'].'[]';?>" id="<?php echo 'caratula'.$rowFacturasUsuario['id'].'[]';?>" value="I" />Individual</label><br />
										<label><input type="radio" name="<?php echo 'caratula'.$rowFacturasUsuario['id'].'[]';?>" id="<?php echo 'caratula'.$rowFacturasUsuario['id'].'[]';?>" value="A" />Agrupada</label>
									</td>
								</tr>
							<?php
									$i++;
								} ?>
				</tbody>
				<?php } ?>
		  </table>
		</div>
	</div>
	<div align="center">
		<p bgcolor="#CCCCCC"><input type="button" name="procesar" id="procesar" value="Generar Autorizaciones / Caratulas"/></p>
	</div>
</form>
</body>
</html>
