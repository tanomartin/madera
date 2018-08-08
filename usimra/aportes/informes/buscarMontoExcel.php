<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
set_time_limit(0);
$sqlDDJJMonto = "SELECT d.*, p.descripcion as periodo, e.nombre FROM ddjjusimra d, periodosusimra p, empresas e
						WHERE d.nrcuil = '99999999999' and d.totapo + d.recarg = ".$_GET['monto']." and 
							  d.perano = p.anio and d.permes = p.mes and d.nrcuit = e.cuit";
$resDDJJMonto = mysql_query($sqlDDJJMonto,$db); 

$file= "DDJJ_POR_MONTO.xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");
?>

<body>
	<table border="1">
		<thead>
			<tr>
				<th>ID</th>
				<th>Periodo</th>
				<th>CUIT</th>
				<th>Razón Social</th>
				<th>Cant. Remu.</th>
				<th>Remuneracion</th>
				<th>Aporte 0.6</th>
				<th>Aporte 1.0</th>
				<th>Aporte 1.5</th>
				<th>Recargo</th>
				<th>A pagar</th>
				<th>Doc. para Pagar</th>
			</tr>
		</thead>
		<tbody>
<?php 	while($rowEmpleados = mysql_fetch_assoc($resDDJJMonto)) { ?>
			<tr>
				<td><?php echo $rowEmpleados['id']?></td>
				<td><?php echo $rowEmpleados['periodo']."<br>(".$rowEmpleados['permes']."-".$rowEmpleados['perano'].")";?></td>
				<td><?php echo $rowEmpleados['nrcuit']?></td>
				<td><?php echo $rowEmpleados['nombre']?></td>
				<td><?php echo $rowEmpleados['nfilas']?></td>
				<td><?php echo number_format($rowEmpleados['remune'],"2",",",".")?></td>
				<td><?php echo number_format($rowEmpleados['apo060'],"2",",",".")?></td>
				<td><?php echo number_format($rowEmpleados['apo100'],"2",",",".")?></td>
				<td><?php echo number_format($rowEmpleados['apo150'],"2",",",".")?></td>
				<td><?php echo number_format($rowEmpleados['recarg'],"2",",",".")?></td>
				<td><?php echo number_format($rowEmpleados['recarg']+$rowEmpleados['totapo'],"2",",",".")?></td>
				<td><?php if ($rowEmpleados['instrumento'] == "B") { echo "BOLETA DE PAGO"; } 
						  if ($rowEmpleados['instrumento'] == "T") { echo "LINK PAGOS"; } ?>
				</td>
			</tr>
<?php	} ?>
		</tbody>
	</table>
</body>