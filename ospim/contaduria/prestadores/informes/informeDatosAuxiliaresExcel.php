<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
set_time_limit(0);
$fechadesde = $_POST['fechadesde'];
$sqlPrestaAux = "SELECT 
					a.*, p.cuit, p.nombre, p.email1, p.email2, DATE_FORMAT(a.fechainterbanking, '%d-%m-%Y') as fechainterbanking
				FROM prestadores p, prestadoresauxiliar a
				WHERE
					(a.cbu is not null or
					a.banco is not null or
					cuenta is not null) and a.fechamodificacion > '".fechaParaGuardar($fechadesde)."' and a.codigoprestador = p.codigoprestador
				ORDER BY p.cuit";
$resPrestaAux = mysql_query($sqlPrestaAux);

$today = date("m-d-y");
$file= "Datos Axiliares de Prestadores al $today.xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");

?>
<body>
	<div align="center">
		<h2>Datos Auxiliares de Prestadores</h2>
		<table border="1">
			<thead>
			 <tr>
			 	<th>BANCO</th>
			 	<th>CBU</th>
			 	<th>C.U.I.T.</th>
			 	<th>RAZON SOCIAL</th>
			 	<th>CUENTA</th>
			 	<th>INTERBANKING</th>
			 	<th>EMAIL</th>
			 </tr>
			</thead>
			<tbody>
		<?php while ($rowPrestaAux = mysql_fetch_assoc($resPrestaAux)) {  ?>
				<tr>
					<td><?php if ($rowPrestaAux['banco'] != null) { echo $rowPrestaAux['banco'];} ?></td>
			 		<td><?php if ($rowPrestaAux['cbu'] != null) { echo "'".$rowPrestaAux['cbu']."'"; } ?></td>
			 		<td><?php echo $rowPrestaAux['cuit'] ?></td>
			 		<td><?php echo $rowPrestaAux['nombre'] ?></td>
			 		<td><?php if ($rowPrestaAux['cuenta'] != null) { echo "'".$rowPrestaAux['cuenta']."'"; } ?></td>
			 		<?php $fechaInterbanking =  $rowPrestaAux['fechainterbanking']; if ($fechaInterbanking == null) {  $fechaInterbanking = "No Subido"; }?>
			 		<td><?php if ($rowPrestaAux['interbanking'] == 1) { echo "SI ($fechaInterbanking)"; } else { "NO"; } ?></td>
			 		<td><?php echo $rowPrestaAux['email1']."<br>".$rowPrestaAux['email2'] ?></td>
			 	</tr>
		<?php } ?>
			</tbody>
		</table>
	</div>
</body>