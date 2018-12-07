<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
set_time_limit(0);

if (isset($_POST['periodo']) && isset($_POST['delegacion'])) {
	$arrayPeriodo = explode("-",$_POST['periodo']);
	
	/*************************************************************************************************/
	$delegacion = $_POST['delegacion'];
	$sqlEmpresas100 = "SELECT e.cuit, e.nombre
						FROM jurisdiccion j, empresas e
						WHERE j.codidelega = $delegacion and j.cuit = e.cuit and j.disgdinero = 100.00";
	$resEmpresas100 = mysql_query($sqlEmpresas100,$db);
	$arrayEmpresas = array();
	while ($rowEmpresas100 = mysql_fetch_assoc($resEmpresas100)) {
		$arrayEmpresas[$rowEmpresas100['cuit']] = $rowEmpresas100['nombre'];
	}
	
	$sqlEmpresas100 = "SELECT e.cuit, e.nombre
						FROM jurisdiccion j, empresasdebaja e
						WHERE j.codidelega = $delegacion and j.cuit = e.cuit and j.disgdinero = 100.00";
	$resEmpresas100 = mysql_query($sqlEmpresas100,$db);
	while ($rowEmpresas100 = mysql_fetch_assoc($resEmpresas100)) {
		$arrayEmpresas[$rowEmpresas100['cuit']] = $rowEmpresas100['nombre'];
	}
	/*************************************************************************************************/
	
	/*************************************************************************************************/
	$whereIn = "(";
	$sqlEmpresas = "SELECT e.cuit, e.nombre, j.codidelega, disgdinero
					FROM jurisdiccion j, empresas e
					WHERE j.codidelega = $delegacion and j.cuit = e.cuit and j.disgdinero != 100.00";
	$resEmpresas = mysql_query($sqlEmpresas,$db);
	while($rowEmpresas = mysql_fetch_assoc($resEmpresas)) {
		$whereIn .= "'".$rowEmpresas['cuit']."',";
	}
	
	$sqlEmpresas = "SELECT e.cuit, e.nombre, j.codidelega, disgdinero
					FROM jurisdiccion j, empresasdebaja e
					WHERE j.codidelega = $delegacion and j.cuit = e.cuit and j.disgdinero != 100.00";
	$resEmpresas = mysql_query($sqlEmpresas,$db);
	while($rowEmpresas = mysql_fetch_assoc($resEmpresas)) {
		$whereIn .= "'".$rowEmpresas['cuit']."',";
	}	
	$whereIn = substr($whereIn, 0, -1);
	$whereIn .= ")";
	/*************************************************************************************************/
	
	/*************************************************************************************************/
	$arrayEmpreasControl = array();
	$sqlEmpresasDisg = "SELECT e.cuit, e.nombre, j.codidelega
						FROM jurisdiccion j, empresas e
						WHERE e.cuit in $whereIn and e.cuit = j.cuit ORDER BY j.codidelega";
	$resEmpresasDisg = mysql_query($sqlEmpresasDisg,$db);
	while($rowEmpresasDisg = mysql_fetch_assoc($resEmpresasDisg)) {
		$arrayEmpreasControl[$rowEmpresasDisg['cuit']] = array('delega' => $rowEmpresasDisg['codidelega'], 'nombre' => $rowEmpresasDisg['nombre']);
	}
	
	$sqlEmpresasDisg = "SELECT e.cuit, e.nombre, j.codidelega
						FROM jurisdiccion j, empresasdebaja e
						WHERE e.cuit in $whereIn and e.cuit = j.cuit ORDER BY j.codidelega";
	$resEmpresasDisg = mysql_query($sqlEmpresasDisg,$db);
	while($rowEmpresasDisg = mysql_fetch_assoc($resEmpresasDisg)) {
		$arrayEmpreasControl[$rowEmpresasDisg['cuit']] = array('delega' => $rowEmpresasDisg['codidelega'], 'nombre' => $rowEmpresasDisg['nombre']);
	}
	foreach ($arrayEmpreasControl as $cuit => $datos) {
		if ($datos['delega'] == $delegacion) {
			$arrayEmpresas[$cuit] = $datos['nombre'];
		}
	}
	/*************************************************************************************************/
	
	
	$cantidadTotalEmpresas = sizeof($arrayEmpresas);

	$whereIn = "(";
	foreach($arrayEmpresas as $cuit => $nombre) {
		$whereIn .= "'".$cuit."',";
	}
	$whereIn = substr($whereIn, 0, -1);
	$whereIn .= ")";
	
	$sqlOSPIM = "SELECT cuit, sum(totalremundeclarada)+sum(totalremundecreto) as totremun, sum(totalpersonal) as totalpersonal
				 FROM cabddjjospim c WHERE anoddjj = ".$arrayPeriodo[0]." and mesddjj = ".$arrayPeriodo[1]." and cuit in $whereIn GROUP BY cuit";
	$resOSPIM = mysql_query($sqlOSPIM,$db);
	$arrayResultOspim = array();
	while($rowOSPIM = mysql_fetch_assoc($resOSPIM)) {
		$arrayResultOspim[$rowOSPIM['cuit']] = array("totremunospim" => $rowOSPIM['totremun'], "totalpersonalospim" => $rowOSPIM['totalpersonal']);
	}

	$sqlUSIMRA = "SELECT cuit, sum(remuneraciones) as totremun, sum(cantidadpersonal) as totalpersonal
				 FROM cabddjjusimra c WHERE anoddjj = ".$arrayPeriodo[0]." and mesddjj = ".$arrayPeriodo[1]." and cuit in $whereIn GROUP BY cuit";
	$resUSIMRA = mysql_query($sqlUSIMRA,$db);

	$arrayCUIT = array();
	$arrayResultUsimra = array();
	while($rowUSIMRA = mysql_fetch_assoc($resUSIMRA)) {
		if (!isset($arrayCUIT[$rowUSIMRA['cuit']])) {
			$arrayCUIT[$rowUSIMRA['cuit']] = $rowUSIMRA['cuit'];
		}
		$arrayResultUsimra[$rowUSIMRA['cuit']] = array ("totremunusimra" => $rowUSIMRA['totremun'], "totalpersonalusimra" => $rowUSIMRA['totalpersonal']);
	}
	
	$sqlUSIMRANoValidas = "SELECT nrcuit, remune, nfilas FROM ddjjusimra c
						   WHERE perano = ".$arrayPeriodo[0]." and
								 permes = ".$arrayPeriodo[1]." and
								 nrcuil = '99999999999' and nrcuit in $whereIn order by id DESC";
	$resUSIMRANoValidas = mysql_query($sqlUSIMRANoValidas,$db);
	$arrayFiltro = array();
	while($rowUSIMRANoValidas = mysql_fetch_assoc($resUSIMRANoValidas)) {
		$arrayFiltro[$rowUSIMRANoValidas['nrcuit']] = array("remune" => $rowUSIMRANoValidas['remune'], "personal" => $rowUSIMRANoValidas['nfilas']);
	}
	
	foreach ($arrayFiltro as $cuit => $datos) {
		if (!array_key_exists($cuit,$arrayCUIT)) {
			$arrayResultUsimra[$cuit] = array ("totremunusimra" => $datos['remune'], "totalpersonalusimra" => $datos['personal']);
		}
	}
	
	$resultadoFinal = array_merge_recursive($arrayResultOspim, $arrayResultUsimra);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta de D.D.J.J. :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" />
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript">

$(function() {
	$("#listado")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,	
		}		
	});
});

function validar(formulario) {
	if (formulario.periodo.value == 0) {
		alert("Debe seleccionar un Periodo");
		return false;
	}
	if (formulario.delegacion.value == 0) {
		alert("Debe seleccionar una Delegacion");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Informe... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" class="nover" value="Volver" onclick="location.href = '../moduloInformes.php'" /></p>
	<h3>DETALLE CUIT DDJJ OSPIM y USIMRA</h3>
	<form id="consultaOSUS" class="nover" name="consultaOSUS" method="post" action="ddjjospimusimradel.php" onsubmit="return validar(this)" class="nover">
		<p>
			<b>Periodo: </b>
			<select name="periodo" id="periodo" >
				<option value="0">Selecciones Periodo</option>
			<?php $mesmenos = date('Y-m-d');
				  for ($i = 1; $i <= 12; $i++) {
				  	  $mesmenos = strtotime($mesmenos);
		 			  $mesmenos = date("Y-m", strtotime("-1 month", $mesmenos));?>
		 			  <option value="<?php echo $mesmenos?>"><?php echo $mesmenos?></option>
		 	<?php } ?>
			</select>
		</p>
		<p>
			<b>Delegacion: </b>
			<select name="delegacion" id="delegacion" class="nover">
				<option value="0" selected="selected">Seleccione un Valor</option>
				<?php $sqlDele="SELECT codidelega,nombre FROM delegaciones WHERE codidelega not in (1000,1001,3500,4000,4001)";
					  $resDele= mysql_query($sqlDele,$db);
					  while ($rowDele=mysql_fetch_array($resDele)) { 	?>
						<option value="<?php echo $rowDele['codidelega'] ?>"><?php echo $rowDele['nombre']  ?></option>
				<?php } ?>
			</select>
		</p>
		<p><input type="submit" value="Buscar" class="nover"/></p>
	</form>
	<?php if (isset($_POST['periodo']) && isset($_POST['delegacion'])) { ?>
			<h3>Resultado de busqueda delegacion '<?php echo $_POST['delegacion'] ?>' periodo '<?php echo $_POST['periodo'] ?>' </h3>
			<h3>TOTAL DE EMPRESAS ACTIVAS <font color="blue"> '<?php echo $cantidadTotalEmpresas ?>' </font></h3>
			<table class="tablesorter" id="listado" style="text-align: center; width: 1100px">
				<thead>
					<tr>
						<th rowspan="2">CUIT</th>
						<th rowspan="2">RAZON SOCIAL</th>
						<th colspan="2">OSPIM</th>
						<th colspan="2">USIMRA</th>
					</tr>
					<tr>
						<th>PERSONAL</th>
						<th>REMUNERACION</th>
						<th>PERSONAL</th>
						<th>REMUNERACION</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$cantEmpresasDatos = 0;
						$totalPersonalOspim = 0;
						$totalRemuneraOspim = 0;
						$totalPersonalUsimra = 0;
						$totalRemuneraUsimra = 0;
						foreach($resultadoFinal as $cuit => $datos) {					
							if (!isset($datos['totalpersonalospim'])) { $personalOspim = 0; } else { $personalOspim = $datos['totalpersonalospim']; }
							if (!isset($datos['totremunospim'])) { $remuneOspim = 0.00; } else { $remuneOspim = $datos['totremunospim']; }
							if (!isset($datos['totalpersonalusimra'])) { $peronalUsimra = 0; } else { $peronalUsimra = $datos['totalpersonalusimra']; }
							if (!isset($datos['totremunusimra'])) { $remuneUsimra = 0.00; } else { $remuneUsimra = $datos['totremunusimra']; }
							
							$totalPersonalOspim += $personalOspim;
							$totalRemuneraOspim +=  $remuneOspim;
							$totalPersonalUsimra += $peronalUsimra;
							$totalRemuneraUsimra +=  $remuneUsimra; ?>
							<tr>
								<td><?php echo $cuit ?></td>
								<td><?php echo $arrayEmpresas[$cuit] ?></td>
								<td><?php echo $personalOspim; ?></td>
								<td><?php echo number_format($remuneOspim,"2",",","."); ?></td>
								<td><?php echo $peronalUsimra; ?></td>
								<td><?php echo number_format($remuneUsimra,"2",",","."); ?></td>
							</tr>
					<?php } ?>
				</tbody>
				<tr>
					<th colspan="2">TOTAL <?php echo sizeof($resultadoFinal) ?>  (OSPIM: <?php echo sizeof($arrayResultOspim) ?> - USIMRA: <?php echo sizeof($arrayResultUsimra) ?>)</th>
					<th><?php echo $totalPersonalOspim ?></th>
					<th><?php echo number_format($totalRemuneraOspim,"2",",","."); ?></th>
					<th><?php echo $totalPersonalUsimra ?></th>
					<th><?php echo number_format($totalRemuneraUsimra,"2",",","."); ?></th>
				</tr>
			</table>
			<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	<?php } ?>
</div>
</body>
</html>