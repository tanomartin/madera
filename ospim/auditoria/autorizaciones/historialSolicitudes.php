<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 

$sqlLeeAutorizacion = "SELECT a.nrosolicitud, a.fechasolicitud, a.codidelega, d.nombre, a.cuil, a.nroafiliado, a.codiparentesco, a.apellidoynombre, a.statusverificacion, a.statusautorizacion FROM autorizaciones a, delegaciones d WHERE a.statusverificacion != 0 AND a.statusautorizacion != 0 and a.codidelega = d.codidelega ORDER BY fechasolicitud DESC, nrosolicitud DESC";
$resultLeeAutorizacion = mysql_query($sqlLeeAutorizacion,$db);
$totalLeeAutorizacion = mysql_num_rows($resultLeeAutorizacion);
?>

<!DOCTYPE html>
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<title>.: M&oacute;dulo Autorizaciones :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
	$(function() {
		$("#historial")
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
		.tablesorterPager({container: $("#paginador")}); 
	});
</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
<h1>Historial de Autorizaciones</h1>
</div>
<div align="center">
<table id="historial" class="tablesorter">
	<thead>
		<tr>
			<th>Nro</th>
			<th>Fecha</th>
			<th class="filter-select" data-placeholder="Seleccione Delegación">Delegaci&oacute;n</th>
			<th>C.U.I.L.</th>
			<th>Afiliado</th>
			<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
			<th>Apellido y Nombre</th>
			<th class="filter-select" data-placeholder="Seleccione Estado">Verificaci&oacute;n</th>
			<th class="filter-select" data-placeholder="Seleccione Estado">Autorizaci&oacute;n</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
<?php
		while($rowLeeAutorizacion = mysql_fetch_array($resultLeeAutorizacion)) {
?>
		<tr>
			<td><?php echo $rowLeeAutorizacion['nrosolicitud'];?></td>
			<td><?php echo invertirFecha($rowLeeAutorizacion['fechasolicitud']);?></td>
			<td><?php echo $rowLeeAutorizacion['codidelega']." - ".$rowLeeAutorizacion['nombre'];?></td>
			<td><?php echo $rowLeeAutorizacion['cuil'];?></td>
			<td><?php if($rowLeeAutorizacion['nroafiliado']==0) echo "-"; else echo $rowLeeAutorizacion['nroafiliado'];?></td>
			<td><?php if($rowLeeAutorizacion['codiparentesco']<0) echo "-"; else { if($rowLeeAutorizacion['codiparentesco']==0) echo "Titular"; else echo "Familiar ".$rowLeeAutorizacion['codiparentesco'];};?></td>
			<td><?php echo $rowLeeAutorizacion['apellidoynombre'];?></td>
			<td><?php if($rowLeeAutorizacion['statusverificacion']==1) echo "Aprobada"; if($rowLeeAutorizacion['statusverificacion']==2) echo "Rechazada"; if($rowLeeAutorizacion['statusverificacion']==3) echo "No Reverificada";?></td>
			<td><?php if($rowLeeAutorizacion['statusautorizacion']==1) echo "Aprobada"; if($rowLeeAutorizacion['statusautorizacion']==2) echo "Rechazada";?></td>
			<td><a href="consultaAutorizacion.php?nroSolicitud=<?php echo $rowLeeAutorizacion['nrosolicitud']; ?>" target="popup" onClick="window.open(this.href, this.target, 'width=1100,height=800,scrollbars=yes'); return false;">Consultar</a></td>
		</tr>
<?php
		}
?>
	</tbody>
</table>
</div>
<div id="paginador" class="pager">
	<form>
		<p align="center">
			<img src="../img/first.png" width="16" height="16" class="first"/>
			<img src="../img/prev.png" width="16" height="16" class="prev"/>
			<input type="text" class="pagedisplay" size="8" readonly="readonly" style="background:#CCCCCC; text-align:center"/>
			<img src="../img/next.png" width="16" height="16" class="next"/>
			<img src="../img/last.png" width="16" height="16" class="last"/>
			<select class="pagesize">
				<option selected value="10">10 por pagina</option>
				<option value="20">20 por pagina</option>
				<option value="30">30 por pagina</option>
				<option value="50">50 por pagina</option>
				<option value="<?php echo $totalLeeAutorizacion;?>">Todos</option>
			</select>
		</p>
	</form>
</div>
<div align="center">
  <table style="width: 1229; border: 0">
    <tr>
      <td width="599">
        <div align="left">
          <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloAutorizaciones.php'"/>
        </div>
      <td width="620">
        <div align="right">
          <input type="button" name="imprimir" value="Imprimir" onClick="window.print();"/>
        </div>
    </tr>
  </table>
</div>
</body>
</html>