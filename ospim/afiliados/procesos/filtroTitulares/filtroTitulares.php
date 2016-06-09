<?php

$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");

$fecha = date ( 'Y-m-j' );
$fechaInicio = strtotime ( '-4 month', strtotime ( $fecha ) );
$fechaInicio = date ( 'Y-m-j', $fechaInicio );
echo $fechaInicio . "<br>";

$fechaDesempleo = strtotime ( '-1 month', strtotime ( $fecha ) );
$fechaDesempleo = date ( 'Y-m-j', $fechaDesempleo );
echo $fechaDesempleo . "<br>";

$sqlTitulares = "SELECT DISTINCT cuil FROM titulares t where fechacarnet <= '" . $fechaInicio . "' and tipoafiliado != 'U'";
echo $sqlTitulares . "<br>";

$sqlDDJJ = "SELECT DISTINCT cuil FROM detddjjospim d where (anoddjj = " . date ( "Y", strtotime ( $fechaInicio ) ) . " and mesddjj > " . date ( "n", strtotime ( $fechaInicio ) ) . ") or (anoddjj = " . date ( "Y", strtotime ( $fecha ) ) . " and mesddjj < " . date ( "n", strtotime ( $fecha ) ) . ")";
echo $sqlDDJJ . "<br>";

$sqlPagos = "SELECT DISTINCT cuil FROM afiptransferencias d where (anopago = " . date ( "Y", strtotime ( $fechaInicio ) ) . " and mespago > " . date ( "n", strtotime ( $fechaInicio ) ) . ") or (anopago = " . date ( "Y", strtotime ( $fecha ) ) . " and mespago < " . date ( "n", strtotime ( $fecha ) ) . ")";
echo $sqlPagos . "<br>";

//$sqlDesempleo = "SELECT DISTINCT cuilbeneficiario FROM desempleosss d where anodesempleo = " . date ( "Y", strtotime ( $fechaDesempleo ) ) . " and mesdesempleo = " . date ( "n", strtotime ( $fechaDesempleo ) ) . " and parentesco = 0";
$sqlDesempleo = "SELECT DISTINCT cuilbeneficiario FROM desempleosss d where (anodesempleo = ".date("Y", strtotime($fechaInicio))." and mesdesempleo > ".date("n", strtotime($fechaInicio)).") or (anodesempleo = ".date("Y", strtotime($fecha))." and mesdesempleo < ".date("n", strtotime($fecha)).")";
echo $sqlDesempleo . "<br><br>";

$resTitulares = mysql_query ( $sqlTitulares, $db );
$arrayTitulares = array ();
while ( $rowTitulares = mysql_fetch_assoc ( $resTitulares ) ) {
	array_push ( $arrayTitulares, $rowTitulares ['cuil'] );
}
echo "Titualres: " . count ( $arrayTitulares ) . "<br>";

$arrayDDJJ = array ();
$resDDJJ = mysql_query ( $sqlDDJJ, $db );
while ( $rowDDJJ = mysql_fetch_assoc ( $resDDJJ ) ) {
	array_push ( $arrayDDJJ, $rowDDJJ ['cuil'] );
}
echo "DDJJ: " . count ( $arrayDDJJ ) . "<br>";
$resPagos = mysql_query ( $sqlPagos, $db );
$arrayPagos = array ();
while ( $rowPagos = mysql_fetch_assoc ( $resPagos ) ) {
	array_push ( $arrayPagos, $rowPagos ['cuil'] );
}
echo "Pagos: " . count ( $arrayPagos ) . "<br>";

$resDesempleo = mysql_query ( $sqlDesempleo, $db );
$arrayDesempleo = array ();
while ( $rowDesempleo = mysql_fetch_assoc ( $resDesempleo ) ) {
	array_push ( $arrayDesempleo, $rowDesempleo ['cuilbeneficiario'] );
}
echo "Desempelo: " . count ( $arrayDesempleo ) . "<br>";

$arraySuma = array_merge ( $arrayDDJJ, $arrayPagos, $arrayDesempleo );
unset ( $arrayDDJJ );
unset ( $arrayPagos );
unset ( $arrayDesempleo );
echo "Suma: " . count ( $arraySuma ) . "<br>";

$arrayFinal = array_unique ( $arraySuma );
echo "Final: " . count ( $arrayFinal ) . "<br>";

$tituParaBajar = array_diff ( $arrayTitulares, $arrayFinal );
unset ( $arrayTitulares );
unset ( $arrayFinal );
echo "Resta: " . count ( $tituParaBajar ) . "<br>";

$wherein = "(";
foreach ( $tituParaBajar as $titu ) {
	$wherein .= "'" . $titu . "',";
}
$wherein = substr ( $wherein, 0, - 1 );
$wherein .= ")";

// $sqlTituParaBajar = "SELECT nroafiliado,cuil,apellidoynombre,cuitempresa,DATE_FORMAT(fechacarnet,'%d/%m/%Y') as fechacarnet,codidelega FROM titulares WHERE cuil IN ".$wherein;
$sqlTituParaBajar = "SELECT nroafiliado,cuil,apellidoynombre,cuitempresa,fechacarnet,codidelega FROM titulares  WHERE cuil IN " . $wherein ." LIMIT 1000";
$resTituParaBajar = mysql_query ( $sqlTituParaBajar, $db );
$canTituParaBajar = mysql_num_rows ( $resTituParaBajar );
echo $canTituParaBajar . "<br>";

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Filtro de Titualres :.</title>

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
			headers:{6:{filter:false, sorter:false}},
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
		<p><span class="Estilo2">Titulares para dar de Baja</span></p>
		<p><span class="Estilo2"><?php echo $canTituParaBajar ?> Titulares de <?php echo count ( $tituParaBajar )?> a Bajar </span></p>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="bajarTitulares.php">
			<table style="text-align: center; width: 800px" id="tabla"
				class="tablesorter">
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th class="filter-select" data-placeholder="Seleccione Delegacion">Delegacion</th>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>C.U.I.T. Empresa</th>
						<th>Fecha Carnet</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				 <?php while ( $rowTituParaBajar = mysql_fetch_assoc ( $resTituParaBajar ) ) { ?>
		            	<tr>
							<td><?php echo $rowTituParaBajar['nroafiliado'] ?></td>
							<td><?php echo $rowTituParaBajar['codidelega'] ?></td>
							<td><?php echo $rowTituParaBajar['cuil']   ?></td>
							<td><?php echo $rowTituParaBajar['apellidoynombre']   ?></td>
							<td><?php echo $rowTituParaBajar['cuitempresa']   ?></td>
							<td><?php echo $rowTituParaBajar['fechacarnet']   ?></td>
							<td><input type="checkbox" name="<?php echo $rowTituParaBajar['nroafiliado'] ?>" id="baja" value="<?php echo $rowTituParaBajar['cuil'] ?>" /></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
			<table style="width: 800px">
				<tr>
					<td align="left"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></td>
					<td align="right"><input class="nover" type="submit" name="submit" value="Bajar" /></td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>