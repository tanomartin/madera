<?php

$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");

$fecha = date ( 'Y-m-j' );
$fechaInicio = strtotime ( '-4 month', strtotime ( $fecha ) );
$fechaInicio = date ( 'Y-m-j', $fechaInicio );
//echo $fechaInicio . "<br>";

$fechaDesempleo = strtotime ( '-1 month', strtotime ( $fecha ) );
$fechaDesempleo = date ( 'Y-m-j', $fechaDesempleo );
//echo $fechaDesempleo . "<br>";

$sqlTitulares = "SELECT DISTINCT cuil FROM titularesdebaja t where tipoafiliado != 'U'";
//echo $sqlTitulares . "<br>";

$sqlDDJJ = "SELECT DISTINCT cuil FROM detddjjospim d where (anoddjj = " . date ( "Y", strtotime ( $fechaInicio ) ) . " and mesddjj > " . date ( "n", strtotime ( $fechaInicio ) ) . ") or (anoddjj = " . date ( "Y", strtotime ( $fecha ) ) . " and mesddjj < " . date ( "n", strtotime ( $fecha ) ) . ")";
//echo $sqlDDJJ . "<br>";

$sqlPagos = "SELECT DISTINCT cuil FROM afiptransferencias d where (anopago = " . date ( "Y", strtotime ( $fechaInicio ) ) . " and mespago > " . date ( "n", strtotime ( $fechaInicio ) ) . ") or (anopago = " . date ( "Y", strtotime ( $fecha ) ) . " and mespago < " . date ( "n", strtotime ( $fecha ) ) . ")";
//echo $sqlPagos . "<br>";

//$sqlDesempleo = "SELECT DISTINCT cuilbeneficiario FROM desempleosss d where anodesempleo = " . date ( "Y", strtotime ( $fechaDesempleo ) ) . " and mesdesempleo = " . date ( "n", strtotime ( $fechaDesempleo ) ) . " and parentesco = 0";
$sqlDesempleo = "SELECT DISTINCT cuilbeneficiario FROM desempleosss d where (anodesempleo = ".date("Y", strtotime($fechaInicio))." and mesdesempleo > ".date("n", strtotime($fechaInicio)).") or (anodesempleo = ".date("Y", strtotime($fecha))." and mesdesempleo < ".date("n", strtotime($fecha)).")";
//echo $sqlDesempleo . "<br><br>";

$resTitulares = mysql_query ( $sqlTitulares, $db );
$arrayTitulares = array ();
while ( $rowTitulares = mysql_fetch_assoc ( $resTitulares ) ) {
	array_push ( $arrayTitulares, $rowTitulares ['cuil'] );
}
//echo "Titualres: " . count ( $arrayTitulares ) . "<br>";

$arrayDDJJ = array ();
$resDDJJ = mysql_query ( $sqlDDJJ, $db );
while ( $rowDDJJ = mysql_fetch_assoc ( $resDDJJ ) ) {
	array_push ( $arrayDDJJ, $rowDDJJ ['cuil'] );
}
//echo "DDJJ: " . count ( $arrayDDJJ ) . "<br>";
$resPagos = mysql_query ( $sqlPagos, $db );
$arrayPagos = array ();
while ( $rowPagos = mysql_fetch_assoc ( $resPagos ) ) {
	array_push ( $arrayPagos, $rowPagos ['cuil'] );
}
//echo "Pagos: " . count ( $arrayPagos ) . "<br>";

$resDesempleo = mysql_query ( $sqlDesempleo, $db );
$arrayDesempleo = array ();
while ( $rowDesempleo = mysql_fetch_assoc ( $resDesempleo ) ) {
	array_push ( $arrayDesempleo, $rowDesempleo ['cuilbeneficiario'] );
}
//echo "Desempelo: " . count ( $arrayDesempleo ) . "<br>";

$arraySuma = array_merge ( $arrayDDJJ, $arrayPagos, $arrayDesempleo );
unset ( $arrayDDJJ );
unset ( $arrayPagos );
unset ( $arrayDesempleo );
//echo "Suma: " . count ( $arraySuma ) . "<br>";

$arrayFinal = array_unique ( $arraySuma );
//echo "Final: " . count ( $arrayFinal ) . "<br>";

$tituParaSubir = array_intersect ( $arrayTitulares, $arrayFinal );
unset ( $arrayTitulares );
unset ( $arrayFinal );
//echo "Interseccion: " . count ( $tituParaSubir ) . "<br>";


if (sizeof($tituParaSubir) != 0) {
	$wherein = "(";
	foreach ( $tituParaSubir as $titu ) {
		$wherein .= "'" . $titu . "',";
	}
	$wherein = substr ( $wherein, 0, - 1 );
	$wherein .= ")";
	
	// $sqlTituParaBajar = "SELECT nroafiliado,cuil,apellidoynombre,cuitempresa,DATE_FORMAT(fechacarnet,'%d/%m/%Y') as fechacarnet,codidelega FROM titulares WHERE cuil IN ".$wherein;
	$sqlTituParaSubir = "SELECT nroafiliado,cuil,apellidoynombre,cuitempresa,fechabaja,motivobaja,codidelega FROM titularesdebaja  WHERE cuil IN " . $wherein ." LIMIT 1000";
	//print($sqlTituParaSubir);
	$resTituParaSubir = mysql_query ( $sqlTituParaSubir, $db );
	$canTituParaSubir = mysql_num_rows ( $resTituParaSubir );
	//echo $canTituParaSubir . "<br>";
} else {
	$canTituParaSubir = 0;
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Reactivacion de Titulares :.</title>

<style>
A:link {
	text-decoration: none;
	color: #0033FF
}

A:visited {
	text-decoration: none
}

A:hover {
	text-decoration: none;
	color: #00FFFF
}

.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {
	display: none
}
</style>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet"
	href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" />
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script
	src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script
	src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#tabla")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{7:{filter:false, sorter:false}},
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

	function validar(formulario) {
		formulario.submit.disabled = "true";
		return true;
	}
	
</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../moduloProcesos.php'" /></p>
		<p><span class="Estilo2">Titulares para Reactivar</span></p>
		<p><span class="Estilo2"><?php echo $canTituParaSubir ?> Titulares de <?php echo count ( $tituParaSubir )?> a Reactivar </span></p>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="reactivarTitulares.php">
			<table style="text-align: center; width: 900px" id="tabla"
				class="tablesorter">
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th class="filter-select" data-placeholder="Seleccione Delegacion">Delegacion</th>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>C.U.I.T. Empresa</th>
						<th width="80px">Fecha de Baja</th>
						<th>Motivo de Baja</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				 <?php if ($canTituParaSubir != 0) {
				 		while ( $rowTituParaSubir = mysql_fetch_assoc ( $resTituParaSubir ) ) { ?>
		            	<tr>
							<td><?php echo $rowTituParaSubir['nroafiliado'] ?></td>
							<td><?php echo $rowTituParaSubir['codidelega'] ?></td>
							<td><?php echo $rowTituParaSubir['cuil']   ?></td>
							<td><?php echo $rowTituParaSubir['apellidoynombre']   ?></td>
							<td><?php echo $rowTituParaSubir['cuitempresa']   ?></td>
							<td><?php echo $rowTituParaSubir['fechabaja']   ?></td>
							<td><?php echo $rowTituParaSubir['motivobaja']   ?></td>
							<td><input type="checkbox" name="<?php echo $rowTituParaSubir['nroafiliado'] ?>" id="reactiva" value="<?php echo $rowTituParaSubir['cuil'] ?>" /></td>
						</tr>
				<?php 	}
					} ?>
				</tbody>
			</table>
			<table style="width: 800px">
				<tr>
					<td align="left"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></td>
					<td align="right"><input class="nover" type="submit" name="submit" value="Reactivar" /></td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>