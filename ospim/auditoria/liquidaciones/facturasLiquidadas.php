<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
	$sqlFacturasAgrupadas="SELECT f.id, p.nombre, p.cuit, COUNT(f.id) AS cantidadfacturas, SUM(f.importeliquidado) AS totalimporte FROM facturas f, prestadores p WHERE f.fechacierreliquidacion != '0000-00-00 00:00:00' AND f.fechapago = '0000-00-00' AND f.usuarioliquidacion = '$_SESSION[usuario]' AND f.idPrestador = p.codigoprestador GROUP BY p.cuit ORDER BY p.cuit";
	$resFacturasAgrupadas = mysql_query($sqlFacturasAgrupadas,$db);
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
<script type="text/javascript">
$(document).ready(function(){
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
		<input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAuditoria.php'" />
</div>
<div align="center">
	<h1>Liquidacion Prestadores</h1>
</div>
<div id="facturasusuario" align="center">
	<h2>Facturas Liquidadas por el Usuario @<?php echo $_SESSION['usuario'];?></h2>
	<div class="grilla" style="margin-top:10px; margin-bottom:10px">
		<table style="text-align:center; width:1000px" id="listaFacturasUsuario">
					<?php while($rowFacturasAgrupadas = mysql_fetch_array($resFacturasAgrupadas)) { 
						$botonocultar='ocultar'.$rowFacturasAgrupadas['cuit'];
						$botonmostrar='mostrar'.$rowFacturasAgrupadas['cuit'];
						$trTitulo1='tit1'.$rowFacturasAgrupadas['cuit'];
						$trTitulo2='tit2'.$rowFacturasAgrupadas['cuit'];
					?>
			<thead>
				<tr>
					<th colspan="9" style="color:#00557F">Prestador</th>
				</tr>
				<tr>
					<th colspan="2" style="color:#00557F">C.U.I.T.</th>
					<th colspan="2" style="color:#00557F">Nombre</th>
					<th colspan="2" style="color:#00557F">Cantidad Facturas</th>
					<th colspan="2" style="color:#00557F">Total a Pagar</th>
					<th colspan="1" style="color:#00557F">Accion</th>
				</tr>
				<tr>
					<th colspan="2"><?php echo $rowFacturasAgrupadas['cuit'];?></th>
					<th colspan="2"><?php echo $rowFacturasAgrupadas['nombre'];?></th>
					<th colspan="2"><?php echo $rowFacturasAgrupadas['cantidadfacturas'];?></th>
					<th colspan="2"><?php echo $rowFacturasAgrupadas['totalimporte'];?></th>
					<th colspan="1"><input name="<?php echo $botonocultar;?>" type="button" id="<?php echo $botonocultar;?>" value="Ocultar Facturas" style="display:none" onclick="javascript:ocultaDetalle(<?php echo $rowFacturasAgrupadas['cuit'];?>, <?php echo $rowFacturasAgrupadas['cantidadfacturas'];?>)"/><input name="<?php echo $botonmostrar;?>" type="button" id="<?php echo $botonmostrar;?>" value="Mostrar Facturas" onclick="javascript:muestraDetalle(<?php echo $rowFacturasAgrupadas['cuit'];?>, <?php echo $rowFacturasAgrupadas['cantidadfacturas'];?>)"/></th>
				</tr>
				<tr id="<?php echo $trTitulo1;?>" style="display:none">
					<th colspan="8">Factura</th>
					<th colspan="1">Accion</th>
				</tr>
				<tr id="<?php echo $trTitulo2;?>" style="display:none">
					<th>ID Interno</th>
					<th>Nro.</th>
					<th>Fecha</th>
					<th>Facturado</th>
					<th>Vencimiento</th>
					<th>Debitos</th>
					<th>Liquidado</th>
					<th>Fecha Cierre</th>
					<th>Autorizacion Pago</th>
				</tr>
			</thead>
			<tbody>
						<?php
							$sqlFacturasUsuario = "SELECT p.nombre, p.cuit, f.id, f.puntodeventa, f.nrocomprobante, f.fechacomprobante, f.importecomprobante, f.fechavencimiento, f.totaldebito, f.totalcredito, f.importeliquidado, f.fechacierreliquidacion FROM facturas f, prestadores p WHERE f.id = $rowFacturasAgrupadas[id] AND f.idPrestador = p.codigoprestador ORDER by p.cuit";
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
								<td><input name="autorizacion[]" type="checkbox" id="autorizacion[]" value="<?php echo $rowFacturasUsuario['id'];?>"/></td>
							</tr>
						<?php
								$i++;
							} ?>
			</tbody>
			<?php } ?>
		</table>
	</div>
</div>
</body>
</html>
