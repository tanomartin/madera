<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");

$sqlEmpresasCuit = "SELECT cuit FROM empresas";
$resEmpresasCuit = mysql_query ( $sqlEmpresasCuit, $db );
$arrayCuit = array();
while ($rowEmpresasCuit = mysql_fetch_assoc ($resEmpresasCuit)) {
	$arrayCuit[$rowEmpresasCuit['cuit']] = $rowEmpresasCuit['cuit'];
}

$sqlEmpresasCuit = "SELECT cuit FROM empresasdebaja";
$resEmpresasCuit = mysql_query ( $sqlEmpresasCuit, $db );
$arrayCuitBaja = array();
while ($rowEmpresasCuit = mysql_fetch_assoc ($resEmpresasCuit)) {
	$arrayCuitBaja[$rowEmpresasCuit['cuit']] = $rowEmpresasCuit['cuit'];
}

$arrayProcTitu = array();
$arrayProcFami = array();
$arrayInfo = array();
$arrayTiposAceptados = array(0,2,4,5,8);
$whereFamiIn = "(";

foreach ($_POST as $tipocuil => $datos) {
	$tipo = substr($tipocuil, 0, 1);
	$cuil = substr($tipocuil, 1, strlen($tipocuil));
	
	$datos = explode('-',$datos);
	$cuit = $datos[0];
	$tipoTitu = $datos[1];
	$opcion = $datos[2];
	
	if ($opcion != 0) {
		$arrayInfo[$cuil] = array("detalle" => "Opción", "cuit" => $cuit, "proceso" => $tipo);
	} else {
		if (!in_array($tipoTitu,$arrayTiposAceptados)) {
			$sqlTipoTitu = "SELECT descrip FROM tipotitular where codtiptit = $tipoTitu";
			$resTipoTitu = mysql_query ( $sqlTipoTitu, $db );
			$rowTipoTitu = mysql_fetch_assoc($resTipoTitu);
			$arrayInfo[$cuil] = array("detalle" => "No es un tipo de titular manejado (".$tipoTitu." - ".$rowTipoTitu['descrip'].")", "cuit" => $cuit, "proceso" => $tipo);
		} else {
			if (!array_key_exists ($cuit , $arrayCuit)) {
				if (!array_key_exists ($cuit , $arrayCuitBaja)) {
					$arrayInfo[$cuil] = array("detalle" => "La empresa no existe", "cuit" => $cuit, "proceso" => $tipo);
				} else {
					$arrayInfo[$cuil] = array("detalle" => "La empresa esta de baja", "cuit" => $cuit, "proceso" => $tipo);
				} 
			} else {
				if ($tipo == 'A') {
					$arrayProcTitu[$cuil] = array("cuit" => $cuit, "proceso" => $tipo);
					$whereFamiIn .= "'".$cuil."',";
				}
				if ($tipo == 'R') {
					$arrayProcTitu[$cuil] = array("cuit" => $cuit, "proceso" => $tipo);
				}
			}
		}
	}
}

$whereFamiIn = substr($whereFamiIn, 0, -1);
$whereFamiIn .= ")";

$sqlFamilia = "SELECT * FROM padronsss where cuiltitular in $whereFamiIn and parentesco != 0";
$resFamilia = mysql_query ( $sqlFamilia, $db );
while ($rowFamilia = mysql_fetch_assoc ($resFamilia)) {
	$arrayProcFami[$rowFamilia['cuilfamiliar']] = array("cuiltitular" => $rowFamilia['cuiltitular'], "nombre" => $rowFamilia['apellidoynombre']);
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Informe de Proceso de Titulares en SSS :.</title>

<style type="text/css" media="print">
.nover {
	display: none
}
</style>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" />
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

$(function() {
	$("#tablaInforme")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		headers:{3:{filter:false, sorter:false}},
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

	$("#tablaProcesoTitu")
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

	$("#tablaProcesoFami")
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
</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../menuCruceSSS.php'" />
		<h2>Informe de Proceso de Titulares en SSS</h2>
		<h3>Titulares sin procesar</h3>
		<?php if (sizeof($arrayInfo) > 0) { ?>
		<table style="text-align: center; width: 900px" id="tablaInforme" class="tablesorter">
			<thead>
				<tr>
					<th>C.U.I.L.</th>
					<th>C.U.I.T.</th>
					<th class="filter-select" data-placeholder="Seleccione Proceso">Proceso</th>
					<th>Detalle</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($arrayInfo as $cuil => $datos) { ?>
				<tr>	
					<td><?php echo $cuil ?></td>
					<td><?php echo $datos['cuit'] ?></td>
					<td><?php if ($datos['proceso'] == 'A') { echo 'ALTA'; } if ($datos['proceso'] == 'R') { echo 'REACTIVACION'; }  ?></td>
					<td><?php echo $datos['detalle'] ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php } else { ?>
			<h4>No hay Titulares para informar</h4>
		<?php } ?>
		
		<h3>Titulares Procesados</h3>
		<?php if (sizeof($arrayProcTitu) > 0) { ?>
		<table style="text-align: center; width: 900px" id="tablaProcesoTitu" class="tablesorter">
			<thead>
				<tr>
					<th>C.U.I.L.</th>
					<th>C.U.I.T.</th>
					<th class="filter-select" data-placeholder="Seleccione Proceso">Proceso</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($arrayProcTitu as $cuil => $datos) { ?>
				<tr>	
					<td><?php echo $cuil ?></td>
					<td><?php echo $datos['cuit'] ?></td>
					<td><?php if ($datos['proceso'] == 'A') { echo 'ALTA'; } if ($datos['proceso'] == 'R') { echo 'REACTIVACION'; }  ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php } else { ?>
			<h4>No hay Titulares para informar</h4>
		<?php } ?>
		
		<h3>Familiares Procesados</h3>
		<?php if (sizeof($arrayProcFami) > 0) { ?>
			<table style="text-align: center; width: 900px" id="tablaProcesoFami" class="tablesorter">
				<thead>
					<tr>
						<th>C.U.I.L.</th>
						<th>Nombre</th>
						<th>C.U.I.L. Titular</th>
						<th class="filter-select" data-placeholder="Seleccione Proceso">Proceso</th>
					</tr>
				</thead>
				<tbody>
			<?php foreach ($arrayProcFami as $cuil => $datos) { ?>
				<tr>	
					<td><?php echo $cuil ?></td>
					<td><?php echo $datos['nombre'] ?></td>
					<td><?php echo $datos['cuiltitular'] ?></td>
					<td><?php echo 'ALTA';  ?></td>
				</tr>
			<?php } ?>
			</tbody>
			</table>
		<?php } else { ?>
			<h4>No hay Familiares para informar</h4>
		<?php } ?>
		<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
	</div>
</body>
</html>