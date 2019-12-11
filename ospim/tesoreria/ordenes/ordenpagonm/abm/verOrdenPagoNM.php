<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$nroorden = $_GET['nroorden'];

$sqlCabeceraOrden = "SELECT *, DATE_FORMAT(o.fecha, '%d/%m/%Y') as fecha, p.nombre as prestador,  c.*
						FROM ordennmcabecera o, prestadores p, cuentasospim c
						WHERE o.nroorden = $nroorden and o.codigoprestador = p.codigoprestador and o.idcuenta = c.id";
$resCabeceraOrden = mysql_query($sqlCabeceraOrden,$db);
$rowCabeceraOrden = mysql_fetch_assoc($resCabeceraOrden);

$sqlDetalleOrden = "SELECT * FROM ordennmdetalle o WHERE o.nroorden = $nroorden";
$resDetalleOrden = mysql_query($sqlDetalleOrden,$db); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>.: Ver Imputa Ordenes de Pago :.</title>
<script type="text/javascript">

function reimputar(nroorden, boton) {
	var r = confirm("Desea reimputar la orden de pago Nro " + nroorden + ". Se eliminaran todos los datos cargado de imputacion para cada concepto");
	if (r == true) {
		boton.disabled = true;
		var redireccion = "elminarDatosImputacion.php?nroorden="+nroorden;
		location.href=redireccion;
	}
}

function generar(nroorden) {
	var r = confirm("Desea generar el documento PDF la orden de pago Nro " + nroorden);
	if (r == true) {
		document.getElementById("tablaBotones").style.display = "none";
		var redireccion = "documentoOrdenPagoNM.php?nroorden="+nroorden;
		var titulo = "PDF ORDEN "+nroorden;
		window.open(redireccion,titulo,"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=900, height=650, top=10, left=10");
	}
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'listadoImputaOrdenPagoNM.php'" /></p>
	<h3>Ordenes de Pago No Médicas Imputacion </h3>
	<div style="border-style: solid; border-width: 1px; width: 1000px;">
		<div style="text-align: left; width: 950px; ">
			<p>
				<b>Fecha: <?php echo $rowCabeceraOrden['fecha'] ?></b>
				<b style="float: right; font-size: x-large;">Nº <u style="color: maroon;"><?php echo $nroorden ?></u></b>
			</p><p><b>Prestador: <?php echo $rowCabeceraOrden['prestador'] ?></b></p>
			<p><b>$: <?php echo number_format($rowCabeceraOrden['importe'],2,",",".") ?></b></p>
		</div>
	</div>
	<div style="border-style: solid; border-width: 1px; width: 1000px;">
		<div style="text-align: left; width: 950px; ">
			<p><b>Tipo Pago: <?php if ($rowCabeceraOrden['tipopago'] == "C") { echo "CHEQUE"; } else { echo "TRANSFERENCIA";} ?></b></p>
	  		<p><b>Nro: <?php echo $rowCabeceraOrden['nropago'] ?></b></p>
		</div>
	</div>
	<div style="border-style: solid; border-width: 1px; width: 1000px;">
		<h3>Información Imputación Contable</h3>
		<div style="text-align: left; width: 950px; ">
			<p>
				<b>IMP. CONT. PAGO: <?php echo $rowCabeceraOrden['nrocta']." | ".$rowCabeceraOrden['titulo']." | ".$rowCabeceraOrden['descripcion'] ?></b>
			</p>
		</div>
	</div>
<?php while ($rowDetalleOrden = mysql_fetch_assoc($resDetalleOrden)) { ?>
		<table border="1" style="width: 1000px; text-align: center; margin-top: 20px">
			<tbody>
				<tr>
					<th width="570px">CONCEPTO</th>
					<th width="320px">TIPO</th>
					<th width="110px">IMPORTE</th>
				</tr>
				<tr>
					<td>
						<?php echo $rowDetalleOrden['detalle']?>
					</td>
					<td><?php echo $rowDetalleOrden['tipo']?></td>
					<td><?php echo number_format($rowDetalleOrden['importe'],2,",",".") ?></td>
				</tr>
				<tr>
					<th>CUENTA</th>
					<th>AFILIADO | TIPO</th>
					<th>IMPORTE</th>
				</tr>
	<?php 	$concepto = $rowDetalleOrden['concepto'];
			$sqlDetalleImputacion = "SELECT o.*, c.nrocta, c.titulo, c.descripcion FROM ordennmimputacion o, cuentasospim c
										WHERE o.nroorden = $nroorden and o.concepto = $concepto and o.idcuenta = c.id";
			$resDetalleImputacion = mysql_query($sqlDetalleImputacion,$db);
			while ($rowDetalleImputacion = mysql_fetch_assoc($resDetalleImputacion)) {  ?>
				<tr>
					<td><?php echo $rowDetalleImputacion['nrocta']." | ".$rowDetalleImputacion['titulo']." | ".$rowDetalleImputacion['descripcion'];  ?></td>
					<?php 
					      $datosAfil = " - ";
						  if ($rowDetalleImputacion['nroafiliado'] != NULL ) {
						      $datosAfil = $rowDetalleImputacion['nroafiliado']." | TITULAR";
							  if ($rowDetalleImputacion['nroordenfami'] != 0) { $datosAfil = "FAMILIAR [".$rowDetalleImputacion['nroordenfami']."]"; }
			 			  } ?>  
					<td><?php echo $datosAfil; ?></td>
					<td><?php echo number_format($rowDetalleImputacion['importe'],2,",",".") ?></td>
					
				</tr>
	<?php } ?>
			</tbody>
		</table>
<?php } ?>
<?php if ($rowCabeceraOrden['fechageneracion'] == NULL) { ?>
		<table style="width: 1000px; margin-top: 15px" id="tablaBotones">
			<tr>
				<td align="left"><input type="button" value="RE-IMPUTAR" onclick="reimputar(<?php echo $nroorden ?>, this)" /></td>
				<td align="right"><input type="button" value="GENERAR PDF" onclick="generar(<?php echo $nroorden ?>)" /></td>
			</tr>
		</table>
<?php } ?>
</div>
</body>
</html>