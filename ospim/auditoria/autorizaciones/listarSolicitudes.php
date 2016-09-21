<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 

$sqlLeeAutorizacion = "SELECT a.nrosolicitud,a.fechasolicitud,a.codidelega,d.nombre as delegacion,a.cuil,a.nroafiliado,a.codiparentesco,a.apellidoynombre,a.statusverificacion
						FROM autorizaciones a, delegaciones d WHERE a.statusautorizacion = 0 and a.codidelega = d.codidelega ORDER BY nrosolicitud DESC";
$resultLeeAutorizacion = mysql_query($sqlLeeAutorizacion,$db);
$totalLeeAutorizacion = mysql_num_rows($resultLeeAutorizacion);?>

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
		headers:{8:{sorter:false, filter: false}},
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
	.tablesorterPager({container: $("#paginador")}); 
});
</script>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>
<body bgcolor="#CCCCCC">
	<div style="text-align:center"><h1>Solicitudes</h1></div>
<?php if ($totalLeeAutorizacion !=0) { ?>
	<div align=center>
		<table id="listadorSolicitudes" class="tablesorter" style="width:900px; font-size:14px; text-align: center;">
			<thead>
				<tr>
					<th>Nro</th>
					<th>Fecha</th>
					<th class="filter-select" data-placeholder="Seleccione Delegación">Delegacion</th>
					<th>C.U.I.L.</th>
					<th>Afiliado</th>
					<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
					<th>Apellido y Nombre</th>
					<th class="filter-select" data-placeholder="Seleccione Estado">Verificacion</th>
					<th>Accion</th>
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
				</tr>
<?php	}	?>
			</tbody>
		</table>
	</div>
	<div id="paginador" class="pager">
		<form>
			<p align="center">
				<img src="../img/first.png" width="16" height="16" class="first"/>
				<img src="../img/prev.png" width="16" height="16" class="prev"/>
				<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
			    <img src="../img/next.png" width="16" height="16" class="next"/>
				<img src="../img/last.png" width="16" height="16" class="last"/>
			    <select name="select" class="pagesize">
			    	<option selected="selected" value="10">10 por pagina</option>
			    	<option value="20">20 por pagina</option>
			    	<option value="30">30 por pagina</option>
					<option value="50">50 por pagina</option>
			    	<option value="<?php echo $totalLeeAutorizacion;?>">Todos</option>
			    </select>
			</p>
		</form>	
	</div>
<?php } else { ?>
	<div style="text-align:center"><h3>No existen solicitudes que atender.</h3></div>
<?php } ?>
<div align="center">
  <table width="800" border="0">
    <tr>
      <td width="400">
        <div align="left">
          <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloAutorizaciones.php'" align="left"/>
        </div>
      <td width="400">
        <div align="right">
          <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="right"/>
        </div>
    </tr>
  </table>
</div>
</body>
</html>
