<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlPresSSSFinalizadas = "SELECT *,
							DATE_FORMAT(d.fechadesde,'%d/%m/%Y') as fechadesde, 
							DATE_FORMAT(d.fechahasta,'%d/%m/%Y') as fechahasta,
							DATE_FORMAT(d.fechacancelacion,'%d/%m/%Y') as fechacancelacion, 
							DATE_FORMAT(d.fechapresentacion,'%d/%m/%Y') as fechapresentacion
							FROM diabetespresentacion d
							WHERE d.fechacancelacion is not null or d.fechadevolucion is not null order by id DESC";
$resPresSSSFinalizadas = mysql_query($sqlPresSSSFinalizadas,$db);
$canPresSSSFinalizadas = mysql_num_rows($resPresSSSFinalizadas);

$sqlPresSSSActiva = "SELECT d.*, 
						DATE_FORMAT(d.fechadesde,'%d/%m/%Y') as fechadesde, 
						DATE_FORMAT(d.fechahasta,'%d/%m/%Y') as fechahasta,
						DATE_FORMAT(d.fechapresentacion,'%d/%m/%Y') as fechapresentacion
						FROM diabetespresentacion d
						WHERE d.fechacancelacion is null and d.fechadevolucion is null order by id DESC";
$resPresSSSActiva = mysql_query($sqlPresSSSActiva,$db);
$canPresSSSActiva = mysql_num_rows($resPresSSSActiva);
if ($canPresSSSActiva == 1) {
	$rowPresSSSActiva = mysql_fetch_assoc($resPresSSSActiva);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript">

$(function() {
	$("#finalizadas")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,
		}
	})
});

</script>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Diabetes Presentacion S.S.S. :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloDiabetes.php'" /></p>
  	<h2>Módulo Presentacion Diabetes S.S.S.</h2>
  	<h3>Presentacion Activa</h3>
  	<?php if ($canPresSSSActiva != 0) { ?>
	  		<div class="grilla">
	  			<table style="width:1000px">
	  				<thead>
	  					<tr>
		  					<th>ID</th>
		  					<th>Fecha Desde - Hasta</th>
		  					<th># Bene</th>
		  					<th>Archivo</th>
		  					<th>Estado</th>
		  					<th>Acciones</th>
	  					</tr>
	  				</thead>
					<tbody>
			  			<tr>
			  				<td><?php echo $rowPresSSSActiva['id']?></td>
			  				<td><?php echo $rowPresSSSActiva['fechadesde']." - ".$rowPresSSSActiva['fechahasta']?></td>
			  				<td><?php echo $rowPresSSSActiva['cantidadbeneficiario']?></td>
			  				<td><?php echo substr($rowPresSSSActiva['patharchivo'],-22)?></td>
			  			  <?php $estado = "SIN PRESENTAR";
			  					if ($rowPresSSSActiva['fechapresentacion'] != NULL) {
			  						$estado = "PRESENTADA <br>FEC: ".$rowPresSSSActiva['fechapresentacion']." - EXP: ".$rowPresSSSActiva['nroexpediente'];
			  					} ?>
			  				<td><?php echo $estado ?></td>
			  				<td>
			  					<?php if ($rowPresSSSActiva['fechapresentacion'] == NULL) { ?> <input type="button" value="DESCARGAR" onclick="location.href = 'descargaArchivo.php?file=<?php echo $rowPresSSSActiva['patharchivo'] ?>'"/> <input type="button" value="PRESENTACION" onclick="location.href = 'presentacionSSS.php?id=<?php echo $rowPresSSSActiva['id'] ?>'"/> <?php } ?>
			  					<input type="button" value="CANCELAR" onclick="location.href = 'cancelarPresentacion.php?id=<?php echo $rowPresSSSActiva['id'] ?>'"/>
			  					<?php if ($rowPresSSSActiva['fechapresentacion'] != NULL) { ?><input type="button" value="DEVOLUCION" onclick="location.href = 'devolucionPresentacion.php?id=<?php echo $rowPresSSSActiva['id'] ?>'"/> <?php } ?>
			  				</td>
			  			</tr>
		  			</tbody>
	  			</table>
	  		</div>
  	<?php } else { ?>
  			<h3 style="color: blue">No Existe Presentacion Activas</h3>
  			<button onclick="location.href = 'nuevaPresentacion.php'">Nueva Presentacion</button>
  	<?php } ?>
  	<h3>Presentaciones Finalizadas</h3>
  	<?php if ($canPresSSSFinalizadas != 0) { ?>
  			<table style="text-align:center; width:1000px;" id="finalizadas" class="tablesorter">
  				<thead>
  					<tr>
	  					<th>ID</th>
	  					<th>Fecha Desde - Hasta</th>
	  					<th># Bene</th>
	  					<th>Estado</th>
  					</tr>
  				</thead>
  				<tbody>
  			<?php  while ($rowPresSSSFinalizadas = mysql_fetch_assoc($resPresSSSFinalizadas)) { ?>
  					<tr>
  						<td><?php echo $rowPresSSSFinalizadas['id'] ?></td>
  						<td><?php echo $rowPresSSSFinalizadas['fechadesde']." - ".$rowPresSSSFinalizadas['fechahasta']?></td>
  						<td><?php echo $rowPresSSSFinalizadas['cantidadbeneficiario']?></td>
  						 <?php  $estado = "";
			  					$color = "";
			  					if ($rowPresSSSFinalizadas['fechacancelacion'] != NULL) {
			  						$color = "red";
			  						$estado = "CANCELADA (".$rowPresSSSFinalizadas['fechacancelacion']." - MOTIVO: ".$rowPresSSSFinalizadas['motivocancelacion'].")";
			  					}
			  					if ($rowPresSSSFinalizadas['fechadevolucion'] != NULL) {
			  						$color = "blue";
			  						$estado = "FINALIZADA (".$rowPresSSSFinalizadas['fechadevolucion']." - MONTO: $ ".$rowPresSSSFinalizadas['monto'].")";
			  					}?>
			  				<td style="color: <?php echo $color ?>"><?php echo $estado ?></td>
  					</tr>
  			<?php } ?>
  				</tbody>
  			</table>
  	<?php } else { ?>
  			<h3 style="color: blue">No Existen Presentaciones Finalizadas</h3>
  	<?php } ?>
</div>
</body>
</html>