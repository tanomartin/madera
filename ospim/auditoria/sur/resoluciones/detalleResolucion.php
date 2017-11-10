<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$id = $_GET['id'];
$sqlResolucion = "SELECT r.*, DATE_FORMAT(r.fecha, '%d-%m-%Y') as fecha FROM resolucioncabecera r 
					WHERE r.id = $id ORDER BY id";
$resResolucion = mysql_query($sqlResolucion,$db);
$rowResolucion = mysql_fetch_assoc($resResolucion);

$sqlResolucionDetalle = "SELECT r.*,p.*, 
							DATE_FORMAT(r.fechadesde, '%d-%m-%Y') as fechadesde,  
							DATE_FORMAT(r.fechahasta, '%d-%m-%Y') as fechahasta
							FROM resoluciondetalle r, practicas p 
							WHERE r.idpractica = p.idpractica AND r.idresolucion = $id ORDER BY r.idpractica";
$resResolucionDetalle = mysql_query($sqlResolucionDetalle,$db);
$canResolucionDetalle = mysql_num_rows($resResolucionDetalle);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Detalle Resoluciones :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<style type="text/css" media="print">
.nover {display:none}
</style>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<p><input type="button" class="nover" name="volver" value="Volver" onclick="location.href = 'resoluciones.php'"/></p>
  	<h3>Detalle Resolución</h3>
  	<div style="border: solid; width: 600px">
	  	<p><b>Nombre: </b> <?php echo $rowResolucion['nombre'] ?></p>
	  	<p><b>Emisor: </b> <?php echo $rowResolucion['emisor'] ?></p>
	    <p><b>Fecha Emisión: </b> <?php echo $rowResolucion['fecha'] ?></p>
	  	<p><b>Observación</b></p> 
	  	<p><?php echo $rowResolucion['observacion'] ?></p>
  	</div>
  	<h3>Detalle de Prácticas de la Resolución</h3>
  	<?php if ($canResolucionDetalle > 0) {  ?>
  			<h4 style="color: blue">NOTA: Si no hay Fecha Hasta, el importe está vigente</h4> 
  			<div class="grilla">
	  			<table style="width: 900px">
	  				<thead>
	  					<tr>
		  					<th>Código</th>
		  					<th>Nombre</th>
		  					<th>Fecha Desde</th>
		  					<th>Fecha Hasta</th>
		  					<th>Importe ($)</th>
	  					</tr>
	  				</thead>
	  				<tbody>
			 	 <?php  while ($rowResolucionDetalle = mysql_fetch_assoc($resResolucionDetalle)) { ?>
			  			 	<tr>
			  			 		<td><?php echo $rowResolucionDetalle['codigopractica'] ?></td>
			  			 		<td><?php echo $rowResolucionDetalle['descripcion'] ?></td>
			  			 		<td><?php echo $rowResolucionDetalle['fechadesde'] ?></td>
			  			 		<td><?php if ($rowResolucionDetalle['fechahasta']!=NULL) { echo $rowResolucionDetalle['fechahasta']; } else { echo "-"; } ?></td>
			  			 		<td><?php echo number_format($rowResolucionDetalle['importe'],2,',','.') ?></td>
			  			 	</tr>
			  	  <?php } ?>
	  	  			</tbody>
	  	   		</table>
  	   		</div>
  	<?php } else { ?>
  			<h3 style="color: blue">No hay practicas cargadas en esta resolución</h3>
  	<?php } ?>
	<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>
