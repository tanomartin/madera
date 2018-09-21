<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 

$sqlLeeAutorizacion = "SELECT * FROM autorizaciones a, delegaciones d 
						WHERE a.codidelega = d.codidelega ORDER BY nrosolicitud DESC";
$resultLeeAutorizacion = mysql_query($sqlLeeAutorizacion,$db);
$totalLeeAutorizacion = mysql_num_rows($resultLeeAutorizacion);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: M&oacute;dulo Autorizaciones :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script type="text/javascript" src="/madera/lib/jquery.js"></script>
<script type="text/javascript" src="/madera/lib/jquery-ui.min.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
$(function() {
	$("#listadorSolicitudes")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		headers:{9:{sorter:false, filter: false},10:{sorter:false, filter: false}},
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
</head>
<body bgcolor="#CCCCCC">
	<div align=center>
		<p><input type="button" name="volver" value="Volver" onClick="location.href = 'moduloAutorizaciones.php'"/></p>
		<h3>Solicitudes Sin Atención</h3>
<?php 	if ($totalLeeAutorizacion !=0) { ?>
		<table id="listadorSolicitudes" class="tablesorter" style="width:90%; font-size:14px; text-align: center;">
			<thead>
				<tr>
					<th>Nro</th>
					<th>Fecha</th>
					<th class="filter-select" data-placeholder="Seleccione Delegación">Delegacion</th>
					<th>C.U.I.L.</th>
					<th>Afiliado</th>
					<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo Afil.</th>
					<th>Apellido y Nombre</th>
					<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo Solicitud.</th>
					<th class="filter-select" data-placeholder="Seleccione Estado">Verificacion</th>
					<th>Accion</th>
					<th>Vista</th>
				</tr>
			</thead>
			<tbody>
<?php	while($rowLeeAutorizacion = mysql_fetch_array($resultLeeAutorizacion)) {  ?>
				<tr>
					<td><?php echo $rowLeeAutorizacion['nrosolicitud'];?></td>
					<td><?php echo invertirFecha($rowLeeAutorizacion['fechasolicitud']);?></td>
					<td><?php echo $rowLeeAutorizacion['codidelega'];?></td>
					<td><?php echo $rowLeeAutorizacion['cuil'];?></td>
<?php		if($rowLeeAutorizacion['nroafiliado']==0) { ?>
					<td>-</td>
<?php		} else { ?>
					<td><?php echo $rowLeeAutorizacion['nroafiliado'];?></td>
<?php		}
			if ($rowLeeAutorizacion['codiparentesco']<0) { ?>
					<td>-</td>
<?php		} else { 
				if($rowLeeAutorizacion['codiparentesco']==0) { ?>
					<td>Titular</td>
	<?php		} else { ?>
					<td><?php echo 'Familiar '.$rowLeeAutorizacion['codiparentesco']?></td>			
	<?php		} 
			} ?>
					<td><?php echo $rowLeeAutorizacion['apellidoynombre'];?></td>
	<?php 	$tipo = "-";
			if ($rowLeeAutorizacion['practica'] == 1) { 
				$tipo = "Practica";
			}
			if ($rowLeeAutorizacion['material'] == 1) {
				$tipo = "Material";
			}
			if ($rowLeeAutorizacion['medicamento'] == 1) {
				$tipo = "Medicamento";
			} ?>
				<td><?php echo $tipo; ?></td>
	<?php	if($rowLeeAutorizacion['statusverificacion']==0) { ?>
					<td>No Verificada</td>
					<td>-</td>
	<?php	} 
			if($rowLeeAutorizacion['statusverificacion']==1) { ?>
					<td>Aprobada</td>
					<td><input type="button" value="Atender" onClick="window.location.href='atiendeAutorizacion.php?nroSolicitud=<?php echo $rowLeeAutorizacion['nrosolicitud'];?>'"/></td>
	<?php	} 
			if($rowLeeAutorizacion['statusverificacion']==2) { ?>
					<td>Rechazada</td>
					<td><input type="button" value="Consultar" onClick="window.location.href='consultaVerificacion.php?nroSolicitud=<?php echo $rowLeeAutorizacion['nrosolicitud'];?>'"/></td>
	<?php	} 
			if($rowLeeAutorizacion['statusverificacion']==3) { ?>
					<td>No Reverificada</td>
					<td>-</td>
	<?php	}   ?>
					<td>
					  <?php if(isset($_COOKIE[$rowLeeAutorizacion['nrosolicitud']])) {?> 
								<img src="img/visited.png" height="20" width="20" style="vertical-align: middle;" id="visited<?php echo  $rowLeeAutorizacion['nrosolicitud'] ?>" name="visited<?php echo  $rowLeeAutorizacion['nrosolicitud'] ?>" /> 
					  <?php } ?>
					</td>
				</tr>
	<?php	}	?>
			</tbody>
		</table>
<?php } else { ?>
		  <h3 style="color: blue">No existen solicitudes que atender</h3>
<?php } ?>
         <p><input type="button" name="imprimir" value="Imprimir" onClick="window.print();" /></p>
	</div>
</body>
</html>
