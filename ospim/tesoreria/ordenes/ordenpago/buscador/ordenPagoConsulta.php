<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nroorden = $_GET['nroorden'];
$sqlCabecera = "SELECT *, DATE_FORMAT(o.fechaorden, '%d-%m-%Y') as fecha, pr.descrip as provincia
				FROM ordencabecera o, prestadores p, provincia pr
				WHERE o.nroordenpago = $nroorden and o.codigoprestador = p.codigoprestador and p.codprovin = pr.codprovin";
$resCabecera = mysql_query($sqlCabecera,$db);
$rowCabecera = mysql_fetch_assoc($resCabecera);

$sqlDetalle = "SELECT * FROM ordendetalle o, facturas f WHERE o.nroordenpago = $nroorden and o.idfactura = f.id";
$resDetalle = mysql_query($sqlDetalle,$db);

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoPDF/";
else
	$carpetaOrden="/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Ordenes Pago :.</title>
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

function cancelarOrden(nroorden, boton) {
	var r = confirm("Desea cancelar la orden de pago Nro " + nroorden);
	if (r == true) {
		boton.disabled = true;
		var redireccion = "cancelarOrden.php?nroorden="+nroorden;
		location.href=redireccion;
	}
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'buscarOrden.php?nroroden=<?php echo $nroorden ?>'"/></p>
	<h3>Orden de Pago N� <?php echo $nroorden ?></h3>
	<h4> C�digo: <font color='blue'><?php echo $rowCabecera['codigoprestador']?></font> - C.U.I.T.: <font color='blue'><?php echo $rowCabecera['cuit']?></font> 
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
				</tr>
			</thead>
			<tbody>
	  <?php $totalFactura = 0;
	  		while($rowDetalle = mysql_fetch_array($resDetalle)) { 
	  			$totalFactura += $rowDetalle['importecomprobante']; ?>
				<tr>
					<td><?php echo $rowDetalle['id'];?></td>
					<td><?php echo $rowDetalle['puntodeventa']."-".$rowDetalle['nrocomprobante'] ?></td>
					<td><?php echo number_format($rowDetalle['importecomprobante'],2,',','.');?></td>
					<td><?php echo number_format($rowDetalle['totaldebito'],2,',','.');?></td>
					<td><?php echo number_format($rowDetalle['importepago'],2,',','.');?></td>
					<td><?php echo $rowDetalle['tipocancelacion'] ?></td>
					<td><?php echo number_format($rowDetalle['restoapagar'],2,',','.');?></td>
				</tr>
	  <?php } ?>	
	  		</tbody>
	  		<thead>
				<tr>
					<th colspan="2">TOTAL</th>
					<th><?php echo number_format($totalFactura,2,',','.'); ?></th>
					<th><?php echo number_format($rowCabecera['debito'],2,',','.'); ?></th>
					<th><?php echo number_format($rowCabecera['importe']+$rowCabecera['retencion'],2,',','.'); ?></th>
					<th colspan="2"></th>
				</tr>
				<tr>
					<th colspan="4">RETENCION</th>
					<th><?php echo number_format($rowCabecera['retencion'],2,',','.'); ?></th>
					<th colspan="2"></th>
				</tr>
				<tr>
					<th colspan="4">TOTAL PAGADO</th>
					<th><?php echo number_format($rowCabecera['importe'],2,',','.'); ?></th>
					<th colspan="2"></th>
				</tr>
		<?php if ($rowCabecera['idemail']) { 
					$sqlEmail = "SELECT * FROM bandejaenviados WHERE id = ".$rowCabecera['idemail']; 
					$resEmail = mysql_query($sqlEmail,$db);
					$canEmail = mysql_num_rows($resEmail);
					if ($canEmail != 0 ){ 
						$rowEmail = mysql_fetch_assoc($resEmail); ?>
						<tr>
							<th><b>Info. Email</b></th>
							<th colspan="5">Enviado a "<?php echo $rowEmail['address'] ?>" el d�a "<?php echo $rowEmail['fechaenvio'] ?>"</th>
							<th>	
							<?php if ($rowCabecera['fechacancelacion'] != null) { ?>
								<input type="button" value="Reenviar" onclick="reenviarMail(<?php echo $nroorden?>,<?php echo $rowEmail['id']?>, this, '<?php echo $rowEmail['address']?>')" />
							<?php } ?>
							</th>
						</tr>
			<?php 	} else { ?>
						<tr>
							<th><b>Info. Email</b></th>
							<th colspan="6">EN PROCESO DE ENVIO</th>
						</tr>
				<?php  }
					}?>
				<tr>
				<?php if ($rowCabecera['fechacancelacion'] == null) { ?>
						<th colspan="2">
							<input type="button" value="ORIGINAL" onclick="window.open('<?php echo $carpetaOrden ?>OP<?php echo str_pad($nroorden, 8, '0', STR_PAD_LEFT) ?>O.pdf', '_blank', 'fullscreen=yes');" />
						<?php if ($rowCabecera['debito'] > 0 ) { ?>
								<input type="button" value="NOTA DEBITO" onclick="window.open('<?php echo $carpetaOrden ?>OP<?php echo str_pad($nroorden, 8, '0', STR_PAD_LEFT) ?>DEB.pdf', '_blank', 'fullscreen=yes');" />
						<?php } ?>
						</th>
						<th colspan="3"><input type="button" value="CANCELAR ORDEN" onclick="cancelarOrden(<?php echo $nroorden?>, this)" /></th>
						<th colspan="2"><input type="button" value="COPIAS" onclick="window.open('<?php echo $carpetaOrden ?>OP<?php echo str_pad($nroorden, 8, '0', STR_PAD_LEFT) ?>C.pdf', '_blank', 'fullscreen=yes');" /></th>
					<?php } else { ?>
						<th colspan="7" style="color: red">Orden de pago Cancelada el "<?php echo $rowCabecera['fechacancelacion'] ?>"</th>
					<?php }?>
				</tr>
			</thead>
		</table>
	</div>
</div>
</body>
</html>
			