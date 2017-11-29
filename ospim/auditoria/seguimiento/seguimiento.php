<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$orden = $_GET['orden'];
$nroafil = $_GET['nroafil'];
$nombre = $_GET['nombre'];
$delega = $_GET['delega'];

$arraySeguimiento = array();
$i = 0;
$sqlSeguimiento = "SELECT s.*, DATE_FORMAT(s.fecharegistro, '%d-%m-%Y %H:%i:%S') as fecharegistro  FROM seguimiento s WHERE nroafiliado = $nroafil and nroorden = $orden ORDER BY id DESC";
$resSeguimiento = mysql_query($sqlSeguimiento,$db);
$canSeguimiento = mysql_num_rows($resSeguimiento);
if ($canSeguimiento > 0) {
	while ($rowSeguimiento = mysql_fetch_assoc($resSeguimiento)) {
		$arraySeguimiento[$i] = $rowSeguimiento;
		if ($rowSeguimiento['seguimiento'] != 0) {
			$sqlSeguimientoEstado = "SELECT s.*, DATE_FORMAT(s.fecharegistro, '%d-%m-%Y %H:%i:%S') as fecharegistro FROM seguimientoestado s WHERE idseguimiento = ".$rowSeguimiento['id']." ORDER BY id DESC LIMIT 1";
			$resSeguimientoEstado = mysql_query($sqlSeguimientoEstado,$db);
			$rowSeguimientoEstado = mysql_fetch_assoc($resSeguimientoEstado);
			$arraySeguimiento[$i]['estado'] = $rowSeguimientoEstado;
		}
		$i++;
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: ABM Seguimiento :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>

<link rel="stylesheet" href="/madera/lib/tablas.css"/>

<style type="text/css" media="print">
.nover {display:none}
</style>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'moduloABM.php'" /></p>
	<h3>Listado de Entradas de Seguimiento</h3> 
	<h3>Afiliado: <?php echo $nroafil." - ".$nombre?></h3> 
	<h3>Delegacion: <?php echo $delega ?></h3> 
	<p><input class="nover" type="button" name="nueva" value="Nueva Entrada" onclick="location.href = 'seguimientoNuevo.php?nroafil=<?php echo $nroafil ?>&orden=<?php echo $orden?>&nombre=<?php echo $nombre ?>&delega=<?php echo $delega ?>'" /></p>
	<?php if (sizeof($arraySeguimiento) > 0) {?>
			<div class="grilla">
				<table style="width: 1000px">
					<thead>
						<tr>
							<th>Código</th>
							<th>Fecha</th>
							<th>Titulo</th>
							<th>Estado</th>
							<th class="nover">Acciones</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($arraySeguimiento as $seguimiento) { ?>
						<tr>
							<td><?php echo $seguimiento['id'] ?></td>	
							<td><?php echo $seguimiento['fecharegistro'] ?></td>	
							<td><?php echo $seguimiento['titulo'] ?></td>	
							<td><?php $estado = "-";
									  $modif = 1;
									  if (isset($seguimiento['estado'])) { 
										$estado = $seguimiento['estado']['estado']."<br>".$seguimiento['estado']['fecharegistro']; 
										if ($seguimiento['estado']['estado'] == "FINALIZADO") {
											$modif = 0;	
										}
									  } 
									  echo $estado; ?>
							</td>
							<td class="nover">
								<input type="button" name="ver" id="ver" value="+INFO" onclick="location='seguimientoDetalle.php?id=<?php echo $seguimiento['id'] ?>&nombre=<?php echo $nombre ?>&delega=<?php echo $delega ?>'" />
								<?php if ($modif == 1) { ?><input type="button" name="ver" id="ver" value="Modificar" onclick="location='seguimientoModificar.php?id=<?php echo $seguimiento['id'] ?>&nombre=<?php echo $nombre ?>&delega=<?php echo $delega ?>'" /> <?php } ?>
							</td>		
						</tr>
			    	<?php } ?>
					</tbody>
				</table>
			</div>
			<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	<?php } else {?>
		<p style="color: blue"><b>No Existen Seguimientos cargados para este Afiliado</b></p>
	<?php } ?>
	</div>
</body>
</html>