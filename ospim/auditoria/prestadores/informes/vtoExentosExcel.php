<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$fecha = fechaParaGuardar($_POST['fechahasta']);
$sqlPrestaVTO = "SELECT codigoprestador, nombre, cuit, descripcion, DATE_FORMAT(vtoexento, '%Y-%m-%d') as vtoexento
				FROM prestadores p, tiposituacionfiscal t
				WHERE p.vtoexento <= '$fecha' and p.situacionfiscal = t.id";
$resPrestaVTO =  mysql_query($sqlPrestaVTO);
$today = date("m-d-y");
$file= "Prestadores por fecha vto Excento al $today.xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");
?>
<body>
	<div align="center">
		<table border="1">
			<thead>
			 <tr>
			 	<th>CODIGO</th>
			 	<th>NOMBRE</th>
			 	<th>CUIT</th>
			 	<th>SIT. FISCAL</th>
			 	<th>FECHA VTO EXENTO</th>
			 </tr>
			</thead>
			<tbody>
		<?php while ($rowPrestaVTO = mysql_fetch_assoc($resPrestaVTO)) {  ?>
				<tr>
					<td><?php echo $rowPrestaVTO['codigoprestador'] ?></td>
					<td><?php echo $rowPrestaVTO['nombre'] ?></td>
					<td><?php echo $rowPrestaVTO['cuit'] ?></td>
					<td><?php echo $rowPrestaVTO['descripcion'] ?></td>
					<td><?php echo $rowPrestaVTO['vtoexento'] ?></td>
				</tr>
		<?php } ?>
			</tbody>
		</table>
	</div>
</body>
