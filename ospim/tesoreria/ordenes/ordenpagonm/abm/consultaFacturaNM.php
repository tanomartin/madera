<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$id = $_GET['id'];
$sqlFactura = "SELECT *, t.descripcion as tipocomprobante, c.descripcioncorta
				FROM facturas f, prestadores p, tipocomprobante t, codigoautorizacion c
				WHERE f.id= $id AND 
					  f.idPrestador = p.codigoprestador AND 
					  f.idTipocomprobante = t.id AND
 					  f.idCodigoautorizacion = c.id";
$resFactura = mysql_query($sqlFactura,$db);
$rowFactura = mysql_fetch_assoc($resFactura);

$sqlConceptos = "SELECT * FROM facturasconceptos WHERE idfactura = $id";
$resConceptos = mysql_query($sqlConceptos,$db);
$numConcpetos = mysql_num_rows($resConceptos); 

$sqlOrdenPago = "SELECT c.nroordenpago 
					FROM ordendetalle d, ordencabecera c 
					WHERE d.idfactura = $id and  d.nroordenpago = c.nroordenpago and c.fechacancelacion is null";

$resOrdenPago = mysql_query($sqlOrdenPago,$db);
$numOrdenPago = mysql_num_rows($resOrdenPago);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Ordenes Pago :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>

<style type="text/css" media="print">
	.nover {display:none}
</style>

<script>

function borrarDatosLiquidacion(id) {
	var r = confirm("Desea reliquiedar la factura con ID " + id + ". Se eliminaran el Detalle de la Liquidacion de la misma");
	if (r == true) {
		$.blockUI({ message: "<h1>Eliminando Liquidacion de Factura No Médica... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
		var redireccion = "eliminarLiquidacionNM.php?id="+id;
		location.href=redireccion;
	}
}

function abrirPop(dire, titulo){	
	window.open(dire,titulo,'width=800, height=500,resizable=yes');
}

</script>

</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'listadoFacturasNM.php'" /></p>
		<h3>Detalle Factura No Médica</h3>
		<div class="grilla" style="margin-top:10px; margin-bottom:10px; width: 50%">
			<table>
				<tr>
					<td colspan="2" class="title">Prestador No Medico</td>
				</tr>
				<tr>
					<td align="right" width="18%">Codigo: </td>
					<td align="left"><?php echo $rowFactura['idPrestador'];?></td>
				</tr>
				<tr>
					<td align="right">Razon Social: </td>
					<td align="left"><?php echo $rowFactura['nombre'];?></td>
				</tr>
				<tr>
					<td align="right">C.U.I.T.: </td>
					<td align="left"><?php echo $rowFactura['cuit'];?></td>
				</tr>
			</table>
		</div>
		<div class="grilla" style="margin-top:10px; margin-bottom:10px; width: 50%">
			<table>
				<tr>
					<td colspan="4" class="title">Comprobante</td>
				</tr>
				<tr>
					<td><?php echo $rowFactura['tipocomprobante'].' Nro.: '.$rowFactura['puntodeventa'].'-'.$rowFactura['nrocomprobante'];?></td>
					<td align="right">Fecha: </td>
					<td align="left"><?php echo $rowFactura['fechacomprobante'];?></td>
					<td><?php echo $rowFactura['descripcioncorta'].' Nro.: '.$rowFactura['nroautorizacion'];?></td>
				</tr>
				<tr>
					<td align="right" colspan="2"> Vencimiento a <?php echo $rowFactura['diasvencimiento'].' dias';?></td>
					<td align="right">Fecha Vto.: </td>
					<td align="left"><?php echo $rowFactura['fechavencimiento'];?></td>
				</tr>
				<tr>
					<td align="right" colspan="3">Importe: </td>
					<td align="left"><?php echo number_format($rowFactura['importecomprobante'],2,",",".");?></td>
				</tr>
			</table>
		</div>
  <?php if ($numConcpetos == 0) {  ?>
 			<h3>Factura sin liquidacion de conceptos</h3>
  <?php } else { ?>
  			<h3>Detalle Liquidación</h3>
  			<?php if ($numOrdenPago == 0) { ?>
  				<input class="nover" id="reliquidar" type="button" value="Borrar Liquidacion" onclick="borrarDatosLiquidacion(<?php echo $id ?>)"/>
			<?php } else {
				 	$rowOrden = mysql_fetch_assoc($resOrdenPago);?>
				<input class="nover" id="verorden" type="button" value="Ver Orden" onclick="abrirPop('verDocumento.php?documento=OP<?php echo str_pad($rowOrden['nroordenpago'], 8, '0', STR_PAD_LEFT) ?>NM.pdf', 'Orden Pago No Medica');"/>
			<?php } ?>
  <?php }?>
		<div class="grilla" style="margin-top:10px; margin-bottom:10px; width: 40%">
			<table>
				<tr>
					<td colspan="5" class="title">Totalizador</td>
				</tr>
				<tr>
					<td class="title">Credito</td>
					<td class="title">Debitos</td>
					<td class="title">Imp. Liquidado</td>
					<td class="title">A Pagar</td>
					<td class="title">Pago</td>
				</tr>
				<tr>
					<td><?php echo number_format($rowFactura['totalcredito'],2,",",".");?></td>
					<td><?php echo number_format($rowFactura['totaldebito'],2,",",".");?></td>
					<td><?php echo number_format($rowFactura['importeliquidado'],2,",",".");?></td>
					<td><?php echo number_format($rowFactura['restoapagar'],2,",",".");?></td>
					<td><?php echo number_format($rowFactura['totalpagado'],2,",",".");?></td>
				</tr>
			</table>
		</div>
  <?php if ($numConcpetos > 0) {  ?>
			<div class="grilla" style="margin-top:10px; margin-bottom:10px; width: 60%">
				<table>
					<tr>
						<td colspan="3" class="title">Conceptos</td>
					</tr>
					<tr>
						<td class="title" width="70%">Detalle</td>
						<td class="title">Tipo</td>
						<td class="title">Importe</td>
					</tr>
			  <?php while ($rowConceptos= mysql_fetch_assoc($resConceptos)) { ?>
						<tr>
							<td><?php echo $rowConceptos['detalle'];?></td>
							<td><?php echo $rowConceptos['tipo'];?></td>
							<td><?php echo number_format($rowConceptos['importe'],2,",",".");?></td>
						</tr>
			  <?php } ?>
				</table>
			</div>
	<?php } ?>
		<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /> </p>
	</div>
</body>
</html>
