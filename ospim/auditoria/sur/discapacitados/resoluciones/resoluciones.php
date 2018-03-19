<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlResolucion = "SELECT r.*, DATE_FORMAT(r.fecha, '%d-%m-%Y') as fecha FROM resolucioncabecera r ORDER BY id";
$resResolucion = mysql_query($sqlResolucion,$db);
$canResolucion = mysql_num_rows($resResolucion);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Carga Resoluciones :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloResoluciones.php'"/></p>
  <h3>Men&uacute; Carga Resoluciones</h3>
  <p><input type="button" value="Nueva Resolución" onclick="location.href = 'nuevaResolucion.php'"/></p>
  <?php if ($canResolucion > 0) { ?>
  	<div class="grilla">
	  	<table style="width: 900px">
	  		<thead>
	  			<tr>
	  				<th>Codigo</th>
	  				<th>Nombre</th>
	  				<th>Emisor</th>
	  				<th>Fecha Emisión</th>
	  				<th>Acciones</th>
	  			</tr>
	  		</thead>
	  		<tbody>
	  <?php while ($rowResolucion = mysql_fetch_assoc($resResolucion)) { ?>
	  			<tr>
	  				<td><?php echo $rowResolucion['id'] ?></td>
	  				<td><?php echo $rowResolucion['nombre'] ?></td>
	  				<td><?php echo $rowResolucion['emisor'] ?></td>
	  				<td><?php echo $rowResolucion['fecha'] ?></td>
	  				<td>
	  					<input type="button" value="Detalle" onclick="location.href = 'detalleResolucion.php?id=<?php echo $rowResolucion['id']?>'"/> |
	  					<input type="button" value="Modificar Cabecera" onclick="location.href = 'modificarResolucion.php?id=<?php echo $rowResolucion['id']?>'"/> |
	  					<input type="button" value="Modificar Practicas" onclick="location.href = 'modificarPracticas.php?id=<?php echo $rowResolucion['id']?>'"/>
	  				</td>
	  			</tr>
	  <?php } ?>
		   </tbody>
	  	</table>
	</div>
  <?php } else {?>
  	<h3 style="color: blue">No hay resoluciones cargadas</h3>
  <?php } ?>
</div>
</body>
</html>
