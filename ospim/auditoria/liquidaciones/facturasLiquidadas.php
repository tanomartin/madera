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
					<?php while($rowFacturasAgrupadas = mysql_fetch_array($resFacturasAgrupadas)) { ?>
			<thead>
				<tr>
					<th colspan="12">Prestador</th>
				</tr>
				<tr>
					<th colspan="3">C.U.I.T.</th>
					<th colspan="3">Nombre</th>
					<th colspan="2">Cantidad Facturas</th>
					<th colspan="2">Total a Pagar</th>
					<th colspan="2">Accion</th>
				</tr>
				<tr>
					<th colspan="3"><?php echo $rowFacturasAgrupadas['cuit'];?></th>
					<th colspan="3"><?php echo $rowFacturasAgrupadas['nombre'];?></th>
					<th colspan="2"><?php echo $rowFacturasAgrupadas['cantidadfacturas'];?></th>
					<th colspan="2"><?php echo $rowFacturasAgrupadas['totalimporte'];?></th>
					<th colspan="2">-</th>
				</tr>
				<tr>
					<th colspan="2">Prestador</th>
					<th colspan="8">Factura</th>
					<th colspan="2">Acciones</th>
				</tr>
				<tr>
					<th>Nombre</th>
					<th>C.U.I.T.</th>
					<th>ID Interno</th>
					<th>Nro.</th>
					<th>Fecha</th>
					<th>Facturado</th>
					<th>Vencimiento</th>
					<th>Debitos</th>
					<th>Liquidado</th>
					<th>Fecha Cierre</th>
					<th>Autorizacion Pago</th>
					<th>Estadisticas</th>
				</tr>
			</thead>
			<tbody>
						<?php
							$sqlFacturasUsuario = "SELECT p.nombre, p.cuit, f.id, f.puntodeventa, f.nrocomprobante, f.fechacomprobante, f.importecomprobante, f.fechavencimiento, f.totaldebito, f.totalcredito, f.importeliquidado, f.fechacierreliquidacion FROM facturas f, prestadores p WHERE f.id = $rowFacturasAgrupadas[id] AND f.idPrestador = p.codigoprestador ORDER by p.cuit";
							$resFacturasUsuario = mysql_query($sqlFacturasUsuario,$db);
							$totalfacturasusuario = mysql_num_rows($resFacturasUsuario);
							while($rowFacturasUsuario = mysql_fetch_array($resFacturasUsuario)) { ?>
							<tr>
								<td><?php echo $rowFacturasUsuario['nombre'];?></td>
								<td><?php echo $rowFacturasUsuario['cuit'];?></td>
								<td><?php echo $rowFacturasUsuario['id'];?></td>
								<td><?php echo $rowFacturasUsuario['puntodeventa'].'-'.$rowFacturasUsuario['nrocomprobante'];?></td>
								<td><?php echo invertirFecha($rowFacturasUsuario['fechacomprobante']);?></td>
								<td><?php echo $rowFacturasUsuario['importecomprobante'];?></td>
								<td><?php echo invertirFecha($rowFacturasUsuario['fechavencimiento']);?></td>
								<td><?php echo $rowFacturasUsuario['totaldebito'];?></td>
								<td><?php echo $rowFacturasUsuario['importeliquidado'];?></td>
								<td><?php echo invertirFecha($rowFacturasUsuario['fechacierreliquidacion']);?></td>
								<td><input name="autorizacion[]" type="checkbox" id="autorizacion[]" value="<?php echo $rowFacturasUsuario['id'];?>"/></td>
								<td><input class="nover" type="button" id="consultarestadistica" name="consultarestadistica" value="Consultar" onclick="location.href = 'consultarEstadistica.php?idfactura=<?php echo $rowFacturasUsuario['id'];?>'"/></td>
							</tr>
						<?php } ?>
			</tbody>
					<?php } ?>
			</thead>
		</table>
	</div>
</div>
</body>
</html>
