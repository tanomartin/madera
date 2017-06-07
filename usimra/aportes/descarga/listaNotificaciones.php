<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."claves.php");
include($libPath."fechas.php");
set_time_limit(0);
$hostaplicativo = $hostUsimra;
$usuarioaplicativo = $usuarioUsimra;
$claveaplicativo = $claveUsimra;
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
    die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = $baseUsimraNewAplicativo;
mysql_select_db($dbnameaplicativo);	

$sqlLeeNotificaciones="SELECT id, nrcuit, tiponotificacion, fechanotificacion, asunto, leida, DATE(fechalectura) AS dialectura, DATE_FORMAT(fechalectura,'%h:%i:%s') AS horalectura, eliminada FROM notificaciones;";
$resultLeeNotificaciones=mysql_query($sqlLeeNotificaciones,$dbaplicativo); 
$canLeeNotificaciones = mysql_num_rows($resultLeeNotificaciones); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Descarga Aplicativo :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
}
</style>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#resultados")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{4:{sorter:false}, 5:{sorter:false}, 6:{sorter:false}}
		});
});
</script>
</head>
<body bgcolor="#B2A274">
	<div align="center">
    	<input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloDescarga.php'" />
	</div>
	<div align="center">
		<h1>Listado de Notificaciones del Aplicativo DDJJ Online</h1>
	</div>

<?php
if ($canLeeNotificaciones > 0) {
?>
	<table id="resultados" class="tablesorter" style="font-size:14px; text-align:center">
		<thead>
			<tr>
				<th>Nro.</th>
				<th>C.U.I.T.</th>
				<th>Tipo</th>
				<th>Fecha</th>
				<th>Asunto</th>
				<th>Leida</th>
				<th>Eliminada</th>
			</tr>
		</thead>
		<tbody>
<?php
	while($rowLeeNotificaciones = mysql_fetch_assoc($resultLeeNotificaciones)) {
		if($rowLeeNotificaciones['tiponotificacion']==1) {
			$tipoerror="Diferencia entre el importe pagado y el importe emitido por el ticket.";
		}

		if($rowLeeNotificaciones['tiponotificacion']==2) {
			$tipoerror="Ticket utilizado con anterioridad para efectivizar otro pago.";
		}		

		if($rowLeeNotificaciones['tiponotificacion']==3) {
			$tipoerror="No existe ningun ticket emitido correspondiente a ese Nro. de Liquidacion.";
		}

		if($rowLeeNotificaciones['leida']==1) {
			$notileida="El ".invertirFecha($rowLeeNotificaciones['dialectura'])." a las ".$rowLeeNotificaciones['horalectura'];
		} else {
			$notileida="No Leida";
		}

		if($rowLeeNotificaciones['eliminada']==1) {
			$notieliminada="SI";
		} else {
			$notieliminada="No";
		}
?>
			<tr>
				<td><?php echo $rowLeeNotificaciones['id']; ?></td>
				<td><?php echo $rowLeeNotificaciones['nrcuit']; ?></td>
				<td><?php echo $tipoerror; ?></td>
				<td><?php echo invertirFecha($rowLeeNotificaciones['fechanotificacion']); ?></td>
				<td><?php echo $rowLeeNotificaciones['asunto']; ?></td>
				<td><?php echo $notileida; ?></td>
				<td><?php echo $notieliminada; ?></td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
<?php
} else {
?>
	<div align="center">
		<h2>No existen notificaciones emitidas por el Aplicativo DDJJ Online.</h2>
	</div>
<?php
}
?>
	<div align="center">
    	<input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloDescarga.php'" />
	</div>
</body>
</html>