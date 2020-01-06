<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nroorden = $_GET['nroorden'];

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoPDF/";
else
	$carpetaOrden="/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/";

$sqlCabecera = "SELECT *, DATE_FORMAT(o.fechaorden, '%d-%m-%Y') as fecha, pr.descrip as provincia
				FROM ordencabecera o, prestadores p, provincia pr
				WHERE o.nroordenpago = $nroorden and o.codigoprestador = p.codigoprestador and p.codprovin = pr.codprovin";
$resCabecera = mysql_query($sqlCabecera,$db);
$rowCabecera = mysql_fetch_assoc($resCabecera);

$sqlDetalle = "SELECT f.*, o.importepago, o.tipocancelacion, 
					  ordencabecera.nroordenpago as anterior, 
					  ordencabecera.fechacancelacion as fechacancelacionanterior
				FROM ordendetalle o, facturas f
				LEFT JOIN ordendebitodetalle on ordendebitodetalle.idFactura = f.id and ordendebitodetalle.nroordenpago != $nroorden
			    LEFT JOIN ordencabecera on ordencabecera.nroordenpago = ordendebitodetalle.nroordenpago
				WHERE o.nroordenpago = $nroorden and  o.idfactura = f.id
				ORDER BY fechacancelacion DESC";
$resDetalle = mysql_query($sqlDetalle,$db);
$arrayDetalle = array();
while($rowDetalle = mysql_fetch_assoc($resDetalle)) {
	if (array_key_exists($rowDetalle['id'],$arrayDetalle)) {
		if ($arrayDetalle[$rowDetalle['id']]['fechacancelacionanterior'] != NULL) {
			$arrayDetalle[$rowDetalle['id']] = $rowDetalle;
		} 
	} else {
		$arrayDetalle[$rowDetalle['id']] = $rowDetalle;
	}
}
sort($arrayDetalle);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Ordenes Pago :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/ui.datepicker-es.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function reenviarMail(nroorden, idmail, boton, mail) {
	var r = confirm("Desea reenviar la orden de pago a la siguiente direccion "+mail);
	if (r == true) {
		boton.disabled = true;
		var redireccion = "reenviarOrden.php?idmail="+idmail+"&nroorden="+nroorden;
		location.href=redireccion;
	}
}

function cancelarOrden(nroorden, boton, dato, filtro) {
	var r = confirm("Desea cancelar la orden de pago Nro " + nroorden);
	if (r == true) {
		boton.disabled = true;
		var redireccion = "cancelarOrden.php?nroorden="+nroorden+"&dato="+dato+"&filtro="+filtro;
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
<?php if (isset($_GET['dato'])) { ?>
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'buscarOrden.php?dato=<?php echo $_GET['dato'] ?>&filtro=<?php echo $_GET['filtro'] ?>'"/></p>
<?php } else { ?>
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'buscarOrden.php?dato=<?php echo $nroorden ?>&filtro=0'"/></p>
<?php } ?>
	<h3>Orden de Pago Nº <?php echo $nroorden ?></h3>
	<h4> Código: <font color='blue'><?php echo $rowCabecera['codigoprestador']?></font> - C.U.I.T.: <font color='blue'><?php echo $rowCabecera['cuit']?></font> 
	<br/> Razon Social: <font color='blue'><?php echo $rowCabecera['nombre'] ?></font></h4>
	<div class="grilla">
		<table>
			<thead>
				<tr>
					<th>Nro. Interno</th>
					<th>Nro. Factura</th>
					<th>Imp. Factura</th>
					<th>Debitos</th>
					<th>Pagado</th>
					<th>Tipo Pago</th>
					<th>Monto a Pagar</th>
					<th>Obs</th>
				</tr>
			</thead>
			<tbody>
	  <?php $totalFactura = 0;
	   		foreach ($arrayDetalle as $detalle) {
	  			$totalFactura += $detalle['importecomprobante']; ?>
				<tr>
					<td><?php echo $detalle['id'];?></td>
					<td><?php echo $detalle['puntodeventa']."-".$detalle['nrocomprobante'] ?></td>
					<td><?php echo number_format($detalle['importecomprobante'],2,',','.');?></td>
					<td><?php echo number_format($detalle['totaldebito'],2,',','.');?></td>
					<td><?php echo number_format($detalle['importepago'],2,',','.');?></td>
					<td><?php echo $detalle['tipocancelacion'] ?></td>
					<td><?php echo number_format($detalle['restoapagar'],2,',','.');?></td>
					<td> 
						<?php if ($detalle['fechacancelacionanterior'] == NULL and $detalle['anterior'] != NULL) { ?>
								<font>N.D. ya emitida en el 1er pago (<?php echo "O.P: ".$detalle['anterior'] ?>)</font>
					    <?php } ?>
					</td>
				</tr>
	  <?php } ?>	
	  		</tbody>
	  		<thead>
				<tr>
					<th colspan="2">TOTAL</th>
					<th><?php echo number_format($totalFactura,2,',','.'); ?></th>
					<th><?php echo number_format($rowCabecera['debito'],2,',','.'); ?></th>
					<th><?php echo number_format($rowCabecera['importe']+$rowCabecera['retencion'],2,',','.'); ?></th>
					<th colspan="3"></th>
				</tr>
				<tr>
					<th colspan="4">RETENCION</th>
					<th><?php echo number_format($rowCabecera['retencion'],2,',','.'); ?></th>
					<th colspan="3"></th>
				</tr>
				<tr>
					<th colspan="4">TOTAL PAGADO</th>
					<th><?php echo number_format($rowCabecera['importe'],2,',','.'); ?></th>
					<th colspan="3"></th>
				</tr>
		<?php if ($rowCabecera['idemail']) { 
					$sqlEmail = "SELECT * FROM bandejaenviados WHERE id = ".$rowCabecera['idemail']; 
					$resEmail = mysql_query($sqlEmail,$db);
					$canEmail = mysql_num_rows($resEmail);
					if ($canEmail != 0 ){ 
						$rowEmail = mysql_fetch_assoc($resEmail); ?>
						<tr>
							<th><b>Info. Email</b></th>
							<th colspan="5">Enviado a "<?php echo $rowEmail['address'] ?>" el día "<?php echo $rowEmail['fechaenvio'] ?>"</th>
							<th>	
							<?php if ($rowCabecera['fechacancelacion'] != null) { ?>
								<input type="button" value="Reenviar" onclick="reenviarMail(<?php echo $nroorden?>,<?php echo $rowEmail['id']?>, this, '<?php echo $rowEmail['address']?>')" />
							<?php } ?>
							</th>
						</tr>
			<?php 	} else { ?>
						<tr>
							<th><b>Info. Email</b></th>
							<th colspan="7">EN PROCESO DE ENVIO</th>
						</tr>
				<?php  }
					}?>
				<tr>
				<?php if ($rowCabecera['fechacancelacion'] == null) { ?>
						<th colspan="2">
							<input type="button" value="ORIGINAL" onclick="abrirPop('verDocumento.php?documento=OP<?php echo str_pad($nroorden, 8, '0', STR_PAD_LEFT) ?>O.pdf', 'Orden Pago Original');" />
						</th>
						<th colspan="3">
							<?php if ($rowCabecera['debito'] > 0 ) { ?>
								<input type="button" value="NOTA DEBITO" onclick="abrirPop('verDocumento.php?documento=OP<?php echo str_pad($nroorden, 8, '0', STR_PAD_LEFT) ?>DEB.pdf', 'Nota de Debito');" />
						<?php } ?>	
						</th>
						<th colspan="3">
							<input type="button" value="COPIAS" onclick="abrirPop('verDocumento.php?documento=OP<?php echo str_pad($nroorden, 8, '0', STR_PAD_LEFT) ?>C.pdf', 'Orden Pago Copias');" />
					    </th>
					<?php } else { ?>
							<th colspan="8" style="color: red">Orden de pago Cancelada el "<?php echo $rowCabecera['fechacancelacion'] ?>"</th>
					<?php }?>
				</tr>
			</thead>
		</table>
	</div>
	<?php if ($rowCabecera['fechacancelacion'] == null) { 
			
			if (!isset($_GET['dato'])) { $dato = $nroorden; $filtro = 0; } else { $dato = $_GET['dato']; $filtro = $_GET['filtro']; } ?>
			<p><input type="button" value="ANULAR ORDEN" onclick="cancelarOrden(<?php echo $nroorden?>, this, <?php echo $dato ?>, <?php echo $filtro ?>)" /></p>
	<?php } ?>
</div>
</body>
</html>
			