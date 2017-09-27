<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$id = $_GET['id'];
$nombre =  $_GET['nombre'];

$sqlSeguimiento = "SELECT s.*, DATE_FORMAT(s.fecharegistro, '%d-%m-%Y %H:%i:%S') as fecharegistro, DATE_FORMAT(s.fechamodificacion, '%d-%m-%Y %H:%i:%S') as fechamodificacion 
					FROM seguimiento s WHERE id = $id";
$resSeguimiento = mysql_query($sqlSeguimiento,$db);
$rowSeguimiento = mysql_fetch_assoc($resSeguimiento);
$nroafil = $rowSeguimiento['nroafiliado'];
$orden = $rowSeguimiento['nroorden'];

if ($rowSeguimiento['seguimiento'] == 1) {
	$sqlSeguimientoEstado = "SELECT s.*, DATE_FORMAT(s.fecharegistro, '%d-%m-%Y %H:%i:%S') as fecharegistro FROM seguimientoestado s WHERE idseguimiento = $id ORDER BY id DESC";
	$resSeguimientoEstado = mysql_query($sqlSeguimientoEstado,$db);
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
	<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'seguimiento.php?nroafil=<?php echo $nroafil ?>&orden=<?php echo $orden?>&nombre=<?php echo $nombre ?>'" /></p>
	<h3>Afiliado: <?php echo $nroafil." - ".$nombre?></h3> 
	<p><b>Código: </b> <?php echo $rowSeguimiento['id']?></p>
	<p><b>Fecha Alta: </b> <?php echo $rowSeguimiento['fecharegistro']?></p>
	<p><b>Fecha U.M.: </b> <?php echo $rowSeguimiento['fechamodificacion']?></p>
	<p><b>Titulo: </b> <?php echo $rowSeguimiento['titulo']?></p>
	<p><b>Descripcion</b> </p> 
	<div style="border: groove; width: 800px"><?php echo $rowSeguimiento['descripcion']?></div>
	<?php if ($rowSeguimiento['seguimiento'] == 1) { ?>
		<p><b>Seguimiento</b></p>
		<div class="grilla">
			<table style="width: 1000px">
				<thead>
					<tr>
						<th>Estado</th>
						<th>Fecha Registro</th>
						<th>Usuario Registro</th>
					</tr>
				</thead>
				<tbody>
				<?php while ($rowSeguimientoEstado = mysql_fetch_assoc($resSeguimientoEstado)) { ?>
					<tr>
						<td><?php echo $rowSeguimientoEstado['estado'] ?></td>	
						<td><?php echo $rowSeguimientoEstado['fecharegistro'] ?></td>	
						<td><?php echo $rowSeguimientoEstado['usuarioregistro'] ?></td>	
					</tr>
		    	<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } ?>
	<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>