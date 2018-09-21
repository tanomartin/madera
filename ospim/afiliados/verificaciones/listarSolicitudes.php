<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 

$sqlLeeAutorizacion = "SELECT * FROM autorizaciones a, delegaciones d 
						WHERE 
							(a.statusverificacion = 0 or a.statusverificacion = 3) and 
							a.codidelega = d.codidelega 
						ORDER BY nrosolicitud DESC";
$resultLeeAutorizacion = mysql_query($sqlLeeAutorizacion,$db);
$totalLeeAutorizacion = mysql_num_rows($resultLeeAutorizacion);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: M&oacute;dulo Solicitudes de Autorizacion :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script type="text/javascript" src="/madera/lib/jquery.js"></script>
<script type="text/javascript" src="/madera/lib/jquery-ui.min.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
$(function() {
	$("#listadorSolicitudes")
	.tablesorter({theme: 'blue', widthFixed: true, widgets:['zebra'], headers:{3:{sorter:false}, 5:{sorter:false}}})
});
</script>
</head>
<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onClick="location.href = '../menuAfiliados.php'" /></p>
		<h3>Solicitudes No Verificadas</h3>
<?php if ($totalLeeAutorizacion !=0) { ?>
		<table id="listadorSolicitudes" class="tablesorter"  style="width:900px; font-size:14px; text-align: center;">
			<thead>
				<tr>
					<th>Nro</th>
					<th>Fecha</th>
					<th>Delegacion</th>
					<th>C.U.I.L.</th>
					<th>Afiliado</th>
					<th>Tipo</th>
					<th>Apellido y Nombre</th>
					<th>Accion</th>
				</tr>
			</thead>
			<tbody>
<?php while($rowLeeAutorizacion = mysql_fetch_array($resultLeeAutorizacion)) { ?>
			<tr>
				<td><?php echo $rowLeeAutorizacion['nrosolicitud'];?></td>
				<td><?php echo invertirFecha($rowLeeAutorizacion['fechasolicitud']);?></td>
				<td><?php echo $rowLeeAutorizacion['codidelega'];?></td>
				<td><?php echo $rowLeeAutorizacion['cuil'];?></td>
	  <?php if($rowLeeAutorizacion['nroafiliado']==0) {	?>
				<td>-</td>
	  <?php } else { ?>
				<td><?php echo $rowLeeAutorizacion['nroafiliado'];?></td>
	  <?php }
			if($rowLeeAutorizacion['codiparentesco']<0) { ?>
				<td>-</td>
	  <?php } else {
				if($rowLeeAutorizacion['codiparentesco']==0) { ?>
					<td>Titular</td>
		  <?php } else { ?>
					<td><?php echo 'Familiar '.$rowLeeAutorizacion['codiparentesco']?></td>
		  <?php }
			} ?>
				<td><?php echo $rowLeeAutorizacion['apellidoynombre'];?></td>
	<?php   if($rowLeeAutorizacion['statusverificacion']==0) { ?>
				<td><input type="button" value="Verificar" onClick="window.location.href='verificaSolicitud.php?nroSolicitud=<?php echo $rowLeeAutorizacion['nrosolicitud'];?>'"/></td>
	  <?php } else { ?>
				<td><input type="button" value="Reverificar" onClick="window.location.href='reVerificaSolicitud.php?nroSolicitud=<?php echo $rowLeeAutorizacion['nrosolicitud'];?>'"/></td>
	  <?php } ?>
			</tr>
	<?php } ?>
			</tbody>
		</table>
	<?php
	} else { ?>
		<h3 style="color: blue">No existen solicitudes para atender</h3>
	<?php
	} ?>
		<p><input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="right"/></p>
	</div>
</body>
</html>