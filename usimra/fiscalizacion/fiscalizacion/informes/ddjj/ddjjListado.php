<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 


$cuit = $_POST['cuit'];
$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$canEmpresa = mysql_num_rows($resEmpresa);
if ($canEmpresa == 0) {
	header ("Location: ddjjCuit.php?err=2");
} else {
	$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	$sqlDdjjValidas = "SELECT c.*, p.descripcion as periodo FROM cabddjjusimra c, periodosusimra p where c.cuit = $cuit and c.anoddjj = p.anio and c.mesddjj = p.mes order by c.id DESC";
	$resDdjjValidas = mysql_query($sqlDdjjValidas,$db);
	$canDdjjValidas = mysql_num_rows($resDdjjValidas);
	
	$sqlDdjjTemp = "SELECT d.*, p.descripcion as periodo FROM ddjjusimra d, periodosusimra p where d.nrcuit = $cuit and d.nrcuil = '99999999999' and d.perano = p.anio and d.permes = p.mes order by d.id DESC";
	$resDdjjTemp = mysql_query($sqlDdjjTemp,$db);
	$canDdjjTemp = mysql_num_rows($resDdjjTemp);
}
if ($canDdjjValidas == 0 && $canDdjjTemp == 0) {
	header ("Location: ddjjCuit.php?err=1");
}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de DDJJ por C.U.I.T. :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script>
	$(function() {
		$("#listadoValidas")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra","filter"],
			headers:{2:{sorter:false, filter: false},3:{sorter:false, filter: false},4:{sorter:false, filter: false},5:{sorter:false, filter: false},6:{sorter:false, filter: false},7:{sorter:false, filter: false},8:{sorter:false, filter: false},9:{sorter:false, filter: false},10:{sorter:false, filter: false}},
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
		
		$("#listadoTemp")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra","filter"],
			headers:{2:{sorter:false, filter: false},3:{sorter:false, filter: false},4:{sorter:false, filter: false},5:{sorter:false, filter: false},6:{sorter:false, filter: false},7:{sorter:false, filter: false},8:{sorter:false, filter: false},9:{sorter:false, filter: false},10:{sorter:false, filter: false}},
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
	
	function detalleDdjj(dire) {
		c= window.open(dire,"Detalle DDJJ","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
	}
	
</script>
<body bgcolor="#B2A274">
<div align="center">
	 <input type="reset" name="volver" class="nover" value="Volver" onclick="location.href = 'ddjjCuit.php'" align="center"/>
	
	<p><span class="Estilo2">D.D.J.J. Empresa "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $rowEmpresa['cuit'] ?> </span></p>
	<p><span class="Estilo2">DDJJ Validas</span></p>
	
	<?php if ($canDdjjValidas != 0) { ?>
		<table class="tablesorter" id="listadoValidas" style="width:800px; font-size:14px">
		<thead>
			<tr>
				<th class="filter-select" data-placeholder="Seleccion Año">Año</th>
				<th class="filter-select" data-placeholder="Seleccion Mes">Período</th>
				<th>Personal</th>
				<th>Remuneracion</th>
				<th>Aporte 0.6%</th>
				<th>Contri 1%</th>
				<th>Aporte 1.5%</th>
				<th>Recargo</th>
				<th>Pagado</th>
				<th>Observ</th>
				<th class="nover">Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
			while($rowDdjjValidas = mysql_fetch_assoc($resDdjjValidas)) {
				$linkDetalle = "ddjjDetalleValida.php?anoddjj=".$rowDdjjValidas['anoddjj']."&mesddjj=".$rowDdjjValidas['mesddjj']."&cuit=".$rowDdjjValidas['cuit']."&control=".$rowDdjjValidas['nrocontrol'];
			?>
			<tr align="center">
				<td><?php echo $rowDdjjValidas['anoddjj'];?></td>
				<td><?php echo $rowDdjjValidas['periodo'];?></td>
				<td><?php echo $rowDdjjValidas['cantidadpersonal'];?></td>
				<td align="right"><?php print(number_format($rowDdjjValidas['remuneraciones'],2,',','.')) ?></td>
				<td align="right"><?php print(number_format($rowDdjjValidas['apor060'],2,',','.')) ?></td>
				<td align="right"><?php print(number_format($rowDdjjValidas['apor100'],2,',','.')) ?></td>
				<td align="right"><?php print(number_format($rowDdjjValidas['apor150'],2,',','.')) ?></td>
				<td align="right"><?php print(number_format($rowDdjjValidas['recargo'],2,',','.')) ?></td>
				<td align="right"><?php print(number_format($rowDdjjValidas['totalaporte'],2,',','.')) ?></td>
				<td align="center"><?php print($rowDdjjValidas['observacion']) ?></td>
				<td class="nover"><input type="button" value="Detalle" onclick="detalleDdjj('<?php echo $linkDetalle ?>')" /></td>
			</tr>
			<?php
			}
			?>
		</tbody>
	  </table>
  <?php } else { ?>
  		<p style="color:#0000FF"><strong>La empresa no tiene DDJJ Validas</strong></p>
  <?php } ?>
  <p><span class="Estilo2">DDJJ No Pagas</span></p>
  <?php if ($canDdjjTemp != 0) { ?>
	  <table class="tablesorter" id="listadoTemp" style="width:800px; font-size:14px">
		<thead>
			<tr>
				<th class="filter-select" data-placeholder="Seleccion Año">Año</th>
				<th class="filter-select" data-placeholder="Seleccion Mes">Período</th>
				<th>Personal</th>
				<th>Remuneracion</th>
				<th>Aporte 0.6%</th>
				<th>Contri 1%</th>
				<th>Aporte 1.5%</th>
				<th>Recargo</th>
				<th>Pagado</th>
				<th>Observ</th>
				<th class="nover">Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
			while($rowDdjjTemp = mysql_fetch_assoc($resDdjjTemp)) {
				$linkDetalle = "ddjjDetalleTemp.php?anoddjj=".$rowDdjjTemp['perano']."&mesddjj=".$rowDdjjTemp['permes']."&cuit=".$rowDdjjTemp['nrcuit']."&control=".$rowDdjjTemp['nrctrl'];
			?>
			<tr align="center">
				<td><?php echo $rowDdjjTemp['perano'];?></td>
				<td><?php echo $rowDdjjTemp['periodo'];?></td>
				<td><?php echo $rowDdjjTemp['nfilas'];?></td>
				<td align="right"><?php print(number_format($rowDdjjTemp['remune'],2,',','.')) ?></td>
				<td align="right"><?php print(number_format($rowDdjjTemp['apo060'],2,',','.')) ?></td>
				<td align="right"><?php print(number_format($rowDdjjTemp['apo100'],2,',','.')) ?></td>
				<td align="right"><?php print(number_format($rowDdjjTemp['apo150'],2,',','.')) ?></td>
				<td align="right"><?php print(number_format($rowDdjjTemp['recarg'],2,',','.')) ?></td>
				<td align="right"><?php print(number_format($rowDdjjTemp['totapo'],2,',','.')) ?></td>
				<td align="center"><?php print($rowDdjjTemp['observ']) ?></td>
				<td class="nover"><input type="button" value="Detalle" onclick="detalleDdjj('<?php echo $linkDetalle ?>')" /></td>
			</tr>
			<?php
			}
			?>
		</tbody>
	  </table>
  <?php } else { ?>
  		<p><strong>La empresa no tiene DDJJ No Pagas</strong></p>
  <?php } ?>
  <p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
</div>
</body>
</html>