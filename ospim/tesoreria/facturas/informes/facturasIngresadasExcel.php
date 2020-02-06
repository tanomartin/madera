<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$fechaingreso = $_POST['fechaingreso'];
$fecharegistroIni = fechaParaGuardar($fechaingreso)." 00:00:00";
$fecharegistroFin = date('Y-m-d H:i:s',strtotime ('+1 day',strtotime($fecharegistroIni )));

$sqlFacturas = "SELECT f.*, p.nombre, p.codigoprestador, p.cuit, establecimientos.nombre as establecimient, c.descripcioncorta as autori
					FROM prestadores p, codigoautorizacion c, facturas f
					LEFT JOIN establecimientos on establecimientos.codigo = f.idestablecimiento
					WHERE f.fecharegistro >= '$fecharegistroIni' and 
						  f.fecharegistro < '$fecharegistroFin' and 
						  f.idPrestador = p.codigoprestador and f.idCodigoautorizacion = c.id";
$resFacturas = mysql_query($sqlFacturas,$db);
$canFacturas = mysql_num_rows($resFacturas);
if ($canFacturas == 0) {
	$pagina = "facturasIngresadas.php?err=1&fechaingreso=".$fechaingreso;
	header("Location: $pagina");
} 

$file= "Facturas ingresadas al $fechaingreso.xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file"); ?>

<body>
	<div align="center">
		<h2>Facturas Ingresadas al <?php echo $fechaingreso ?></h2>
		<table border="1">
			<thead>
				<tr>
				 	<th>ID</th>
				 	<th>Fecha<br>Recepcion</th>
				 	<th>Prestador</th>
				 	<th>C.U.I.T.</th>
				 	<th>Nro</th>
				 	<th>Fecha</th>
				 	<th>Autorizacion</th>
				 	<th>Fecha<br> Correo</th>
				 	<th>Dias<br> VTO</th>
				 	<th>Efector</th>
				 	<th>Importe</th>
				 	<th>Usuario<br> Registro</th>
				</tr>
			</thead>
			<tbody>
			<?php while ($rowFacturas = mysql_fetch_assoc($resFacturas)) {  ?>		
					 <tr>
					 	<td><?php echo $rowFacturas['id'] ?></td>
					 	<td><?php echo $rowFacturas['fecharecepcion'] ?></td>
					 	<td><?php echo $rowFacturas['idPrestador']." - ".$rowFacturas['nombre'] ?></td>
					 	<td><?php echo $rowFacturas['cuit'] ?></td>
					 	<td><?php echo $rowFacturas['puntodeventa']."-".$rowFacturas['nrocomprobante'] ?></td>
					 	<td><?php echo $rowFacturas['fechacomprobante'] ?></td>
					 	<td><?php echo $rowFacturas['autori']." ".$rowFacturas['nroautorizacion']; ?></td>
						<td><?php echo $rowFacturas['fechacorreo'] ?></td>
						<td><?php echo $rowFacturas['diasvencimiento'] ?></td>
						<td><?php if ($rowFacturas['idestablecimiento'] != 0) { echo $rowFacturas['idestablecimiento']." - ".$rowFacturas['establecimiento']; } ?></td>
						<td><?php echo number_format($rowFacturas['importecomprobante'],2,',','.'); ?></td>
						<td><?php echo $rowFacturas['usuarioregistro'] ?></td>
					 </tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</body>