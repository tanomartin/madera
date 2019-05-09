<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
set_time_limit(0);

$cuil = $_GET['cuil'];
$nombre = $_GET['nombre'];
$cuit = $_GET['cuit'];

$sqlDDJJPagas = "SELECT d.*, p.descripcion
					FROM detddjjusimra d, periodosusimra p
					WHERE d.cuil = '$cuil' and d.cuit = '$cuit' and d.anoddjj = p.anio and d.mesddjj = p.mes
					ORDER by d.anoddjj, d.mesddjj";
$resDDJJPagas = mysql_query($sqlDDJJPagas,$db);
$canDDJJPagas = mysql_num_rows($resDDJJPagas);
$periodosPagos = array();
$arrayPagos = array();
$indexPagos = 0;
if ($canDDJJPagas > 0) {
	while($rowDDJJPagas = mysql_fetch_assoc($resDDJJPagas)) {
		$periodosPagos[$rowDDJJPagas['anoddjj'].$rowDDJJPagas['mesddjj']] = $rowDDJJPagas['anoddjj'].$rowDDJJPagas['mesddjj'];
		$arrayPagos[$indexPagos] = $rowDDJJPagas;
		$indexPagos++;
	}	
}

$sqlDDJJ = "SELECT d.*, p.descripcion
					FROM ddjjusimra d, periodosusimra p
					WHERE d.nrcuil = '$cuil' and d.nrcuit = '$cuit' and d.perano = p.anio and d.permes = p.mes
					ORDER by d.perano, d.permes";
$resDDJJ = mysql_query($sqlDDJJ,$db);
$canDDJJ = mysql_num_rows($resDDJJ);
$arrayNoPagos = array();
$indexNoPagos = 0;
if ($canDDJJ > 0) {
	while($rowDDJJ = mysql_fetch_assoc($resDDJJ)) {
		$indexBuscador = $rowDDJJ['perano'].$rowDDJJ['permes'];
		if (!array_key_exists($indexBuscador,$periodosPagos)) {
			$arrayNoPagos[$indexNoPagos] = $rowDDJJ;
			$indexNoPagos++;
		}
	}
}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style type="text/css" media="print">
.nover {display:none}
</style>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<title>.: DDJJ y Aportes :.</title>
<script type="text/javascript" src="/madera/lib/jquery.js"></script>
<script type="text/javascript" src="/madera/lib/jquery-ui.min.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script language="javascript" type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets:  ["zebra", "filter"], 
		})
		.tablesorterPager({container: $("#paginador")}); 

		$("#listadoNoPago")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets:  ["zebra", "filter"], 
		})
		.tablesorterPager({container: $("#paginadorNoPago")}); 
	});
</script>
</head>

<body bgcolor="#B2A274" >
	<div align="center">
		<h2>DDJJ / Aportes</h2>
		<h3>C.U.I.L.: <?php echo $cuil ?> - Empleado: <?php echo $nombre ?></h3>
		<h3>C.U.I.T.: <?php echo $cuit ?></h3>
  <?php if($canDDJJPagas > 0 ) { ?>
			<h3>DDJJ Pagas</h3>
			<table id="listado" class="tablesorter" style="width:800px; font-size:14px; text-align:center">
				<thead>
					<tr>	
						<th class="filter-select" data-placeholder="Seleccion">Año</th>
						<th class="filter-select" data-placeholder="Seleccion">Mes</th>
						<th>Descripcion</th>
						<th>Remuneracion</th>
						<th>Aporte 0.6 %</th>
						<th>Aporte 1.0 %</th>
						<th>Aporte 1.5 %</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
		  <?php foreach ($arrayPagos as $pagos) { ?>
					<tr align="center">
						<td><?php echo $pagos['anoddjj'] ?></td>
						<td><?php echo $pagos['mesddjj'] ?></td>
						<td><?php echo $pagos['descripcion'] ?></td>
						<td><?php echo $pagos['remuneraciones'] ?></td>
						<td><?php echo $pagos['apor060'] ?></td>
						<td><?php echo $pagos['apor100'] ?></td>
						<td><?php echo $pagos['apor150'] ?></td>
						<td><?php echo $pagos['apor060']+$pagos['apor100']+$pagos['apor150'] ?></td>
					</tr>
		  <?php } ?>
				</tbody>
			</table>
			<table class="nover" align="center" width="245" border="0">
				<tr>
					<td width="239">
						<div id="paginador" class="pager">
							<form>
								<p align="center">
									<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
									<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
									<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
									<select name="select" class="pagesize">
										<option selected="selected" value="12">12 por pagina</option>
										<option value="24">24 por pagina</option>
										<option value="36">36 por pagina</option>
										<option value="60">60 por pagina</option>
										<option value="120">Todos</option>
									</select>
								</p>
							</form>	
						</div>
					</td>
				</tr>
			</table>
	<?php } else { ?>
	 		<h3>No Existen DDJJ Pagas</h3>
	<?php }
		  if(sizeof($arrayNoPagos) > 0 ) { ?>
			<h3>DDJJ No Pagas</h3>
			<table id="listadoNoPago" class="tablesorter" style="width:800px; font-size:14px; text-align:center">
				<thead>
					<tr>	
						<th class="filter-select" data-placeholder="Seleccion">Año</th>
						<th class="filter-select" data-placeholder="Seleccion">Mes</th>
						<th>Descripcion</th>
						<th>Remuneracion</th>
						<th>Aporte 0.6 %</th>
						<th>Aporte 1.0 %</th>
						<th>Aporte 1.5 %</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
		  <?php foreach ($arrayNoPagos as $nopagas) { ?>
					<tr align="center">
						<td><?php echo $nopagas['perano'] ?></td>
						<td><?php echo $nopagas['permes'] ?></td>
						<td><?php echo $nopagas['descripcion'] ?></td>
						<td><?php echo $nopagas['remune'] ?></td>
						<td><?php echo $nopagas['apo060'] ?></td>
						<td><?php echo $nopagas['apo100'] ?></td>
						<td><?php echo $nopagas['apo150'] ?></td>
						<td><?php echo $nopagas['apo060']+$nopagas['apo100']+$nopagas['apo150'] ?></td>
					</tr>
		  <?php } ?>
				</tbody>
			</table>
			<table class="nover" align="center" width="245" border="0">
				<tr>
					<td width="239">
						<div id="paginadorNoPago" class="pager">
							<form>
								<p align="center">
									<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
									<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
									<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
									<select name="select" class="pagesize">
										<option selected="selected" value="12">12 por pagina</option>
										<option value="24">24 por pagina</option>
										<option value="36">36 por pagina</option>
										<option value="60">60 por pagina</option>
										<option value="120">Todos</option>
									</select>
								</p>
							</form>	
						</div>
					</td>
				</tr>
			</table>
	<?php } else { ?>
	 		<h3>No Existen DDJJ No Pagas</h3>
	<?php }?>
		<p><input class="nover" type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="right"/></p>
	</div>
</body>
</html>
