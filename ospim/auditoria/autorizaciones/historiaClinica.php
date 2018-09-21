<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$cuil = $_GET['cuil'];
$nombre = $_GET['nombre'];
$soli = $_GET['nrosol'];

$sqlAutorizaciones = "SELECT a.nrosolicitud, a.statusautorizacion, a.rechazoautorizacion, a.usuarioautorizacion,
							DATE_FORMAT(a.fechasolicitud,'%d-%m-%Y') as fechasolicitud, 
							autorizaciondocumento.documentofinal, autorizacioneshistoria.detalle 
						FROM autorizacionesatendidas a
						LEFT JOIN autorizaciondocumento ON a.nrosolicitud = autorizaciondocumento.nrosolicitud
						LEFT JOIN autorizacioneshistoria ON a.nrosolicitud = autorizacioneshistoria.nrosolicitud
						WHERE a.cuil = '$cuil' ORDER BY a.nrosolicitud DESC";
$resAutorizaciones = mysql_query($sqlAutorizaciones,$db); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Historia Clinica Autorizaciones</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script type="text/javascript" src="/madera/lib/jquery.js"></script>
<script type="text/javascript" src="/madera/lib/jquery-ui.min.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
$(function() {
	$("#listador")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		headers:{9:{sorter:false, filter: false}},
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

function verDocumentoFinal(solicitud){	
	namevisited = "visited"+solicitud;
	document.getElementById(namevisited).style.display = "inline";
	param = "nroSolicitud=" + solicitud + "&archivo=10";
	opciones = "top=50,left=50,width=1205,height=800,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=no,resizable=no"
	window.open ("mostrarArchivo.php?" + param, "", opciones);
}
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<h3>Historia Clinica Autorizaciones</h3>
	<h3><?php echo $nombre." - ".$cuil ?></h3>
	<table id="listador" class="tablesorter" style="width:100%; font-size:14px; text-align: center;">
		<thead>
			<tr>
				<th>Nro Solicitud</th>
				<th>Fecha</th>
				<th>Detalle Historia</th>
				<th>Estado</th>
				<th>Doc Final / Rechazo</th>
			</tr>
		</thead>
		<tbody>
	<?php while($rowAutorizaciones = mysql_fetch_array($resAutorizaciones)) { 
			$color = "";
			if ($soli == $rowAutorizaciones['nrosolicitud']) {
				$color = "style='background: #99bfe6'";	
			}?>
			<tr>
				<td <?php echo $color?>><?php echo $rowAutorizaciones['nrosolicitud'] ?></td>
				<td <?php echo $color?>><?php echo $rowAutorizaciones['fechasolicitud'] ?></td>
				<td <?php echo $color?>><?php echo $rowAutorizaciones['detalle'] ?></td>
				<td <?php echo $color?>>
			<?php if($rowAutorizaciones['statusautorizacion']==0) echo "No Atendida";
				  if($rowAutorizaciones['statusautorizacion']==1) echo "Aprobada - ".$rowAutorizaciones['usuarioautorizacion'];
				  if($rowAutorizaciones['statusautorizacion']==2) echo "Rechazada - ".$rowAutorizaciones['usuarioautorizacion']; ?>
				</td>
				<td <?php echo $color?>>
		<?php 	if ($rowAutorizaciones['documentofinal'] != NULL) { ?>
					<input type="button" value="Documento Final" name="historia" id="historia" onclick="javascript:verDocumentoFinal(<?php echo  $rowAutorizaciones['nrosolicitud'] ?>)" />
					<img src="img/visited.png" height="20" width="20" style="display: none; vertical-align: middle;" id="visited<?php echo  $rowAutorizaciones['nrosolicitud'] ?>" name="visited<?php echo  $rowAutorizaciones['nrosolicitud'] ?>" />
		<?php 	} 
				if ($rowAutorizaciones['statusautorizacion'] == 2 ) { 
					echo $rowAutorizaciones['rechazoautorizacion'];
				} ?>		
				</td>
			</tr>
	<?php } ?>
		</tbody>
	</table>
</div>
</body>
</html>