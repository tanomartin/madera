<?php
$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

function mayorFecha($fechaDDJJ, $fechaPago, $fechaDesempleo) {
	if (($fechaDDJJ>=$fechaPago) and ($fechaDDJJ>=$fechaDesempleo)) {
		return $fechaDDJJ;
	}
	if (($fechaPago>=$fechaDDJJ) and ($fechaPago>=$fechaDesempleo)) {
		return $fechaPago;
	}
	if (($fechaDesempleo>=$fechaPago) and ($fechaDesempleo>=$fechaDDJJ)) {
		return $fechaDesempleo;
	}
}

$fecha = date ( 'Y-m-j' );
$fechaInicio = strtotime ( '-24 month', strtotime ( $fecha ) );
$fechaInicio = date ( 'Y-m-j', $fechaInicio );
echo $fechaInicio . "<br>";

$fechaDesempleo = strtotime ( '-1 month', strtotime ( $fecha ) );
$fechaDesempleo = date ( 'Y-m-j', $fechaDesempleo );
//echo $fechaDesempleo . "<br>";

$sqlTitulares = "SELECT DISTINCT cuil FROM titulares t where fechacarnet <= '" . $fechaInicio . "' and tipoafiliado != 'U'";
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
unset($resTitulares);

$arrayDDJJ = array ();
$resDDJJ = mysql_query ( $sqlDDJJ, $db );
while ( $rowDDJJ = mysql_fetch_assoc ( $resDDJJ ) ) {
	array_push ( $arrayDDJJ, $rowDDJJ ['cuil'] );
}
//echo "DDJJ: " . count ( $arrayDDJJ ) . "<br>";
unset($resDDJJ);

$resPagos = mysql_query ( $sqlPagos, $db );
$arrayPagos = array ();
while ( $rowPagos = mysql_fetch_assoc ( $resPagos ) ) {
	array_push ( $arrayPagos, $rowPagos ['cuil'] );
}
//echo "Pagos: " . count ( $arrayPagos ) . "<br>";
unset($resPagos);

$resDesempleo = mysql_query ( $sqlDesempleo, $db );
$arrayDesempleo = array ();
while ( $rowDesempleo = mysql_fetch_assoc ( $resDesempleo ) ) {
	array_push ( $arrayDesempleo, $rowDesempleo ['cuilbeneficiario'] );
}
//echo "Desempelo: " . count ( $arrayDesempleo ) . "<br>";
unset($resDesempleo);

$arraySuma = array_merge ( $arrayDDJJ, $arrayPagos, $arrayDesempleo );
unset ( $arrayDDJJ );
unset ( $arrayPagos );
unset ( $arrayDesempleo );
//echo "Suma: " . count ( $arraySuma ) . "<br>";

$arrayFinal = array_unique ( $arraySuma );
//echo "Final: " . count ( $arrayFinal ) . "<br>";

$tituParaBajar = array_diff ( $arrayTitulares, $arrayFinal );
unset ( $arrayTitulares );
unset ( $arrayFinal );
$cantidadTotal = count ( $tituParaBajar );
//echo "Resta: " . count ( $tituParaBajar ) . "<br>";

$wherein = "(";
foreach($tituParaBajar as $titu) {
	$wherein .= "'".$titu."',";
}
$wherein = substr ( $wherein, 0, - 1 );
$wherein .= ")";
unset($tituParaBajar);

// $sqlTituParaBajar = "SELECT nroafiliado,cuil,apellidoynombre,cuitempresa,DATE_FORMAT(fechacarnet,'%d/%m/%Y') as fechacarnet,codidelega FROM titulares WHERE cuil IN ".$wherein;
$sqlTituParaBajar = "SELECT nroafiliado,cuil,apellidoynombre,cuitempresa,fechacarnet,codidelega FROM titulares  WHERE cuil IN " . $wherein ." and codidelega not in (1000,1001) order by fecharegistro ASC LIMIT 500";
$resTituParaBajar = mysql_query ( $sqlTituParaBajar, $db );
$canTituParaBajar = mysql_num_rows ( $resTituParaBajar );
//echo "CANT TITULARES: ".$canTituParaBajar . "<br>";

$arrayInforme = array();
if ($canTituParaBajar != 0) {
	while ( $rowTituBajar = mysql_fetch_assoc ( $resTituParaBajar ) ) {
		$arrayInforme[$rowTituBajar['nroafiliado']]['nroafiliado'] = $rowTituBajar['nroafiliado'];
		$arrayInforme[$rowTituBajar['nroafiliado']]['codidelega'] = $rowTituBajar['codidelega'];
		$arrayInforme[$rowTituBajar['nroafiliado']]['cuil'] = $rowTituBajar['cuil'];
		$arrayInforme[$rowTituBajar['nroafiliado']]['apellidoynombre'] = $rowTituBajar['apellidoynombre'];
		$arrayInforme[$rowTituBajar['nroafiliado']]['cuitempresa'] = $rowTituBajar['cuitempresa'];
		$arrayInforme[$rowTituBajar['nroafiliado']]['fechacarnet'] = $rowTituBajar['fechacarnet'];
	}
}
unset($sqlTituParaBajar);
unset($resTituParaBajar);

$sqlDDJJ = "SELECT cuil, anoddjj, mesddjj FROM detddjjospim d where cuil in $wherein order by cuil, anoddjj ASC ,mesddjj ASC";
//echo $sqlDDJJ . "<br><br>";
$resDDJJ = mysql_query ( $sqlDDJJ, $db );
while ( $rowDDJJ = mysql_fetch_assoc ( $resDDJJ ) ) {
	$fecha = $rowDDJJ['anoddjj']."-".$rowDDJJ['mesddjj']."-1";
	$fecha = strtotime ( '+1 month' , strtotime ($fecha)) ;
	$fecha = strtotime ( '-1 day' , strtotime (date ( 'Y-m-j' , $fecha ))) ;
	$fechaDDJJ[$rowDDJJ['cuil']] = date ( 'Y-m-j' , $fecha );
}
unset($sqlDDJJ);
unset($resDDJJ);
//var_dump($fechaDDJJ);echo"<br><br>";

$sqlPagos = "SELECT cuil, anopago, mespago FROM afiptransferencias d where cuil in $wherein order by cuil, anopago ASC ,mespago ASC";
//echo $sqlPagos . "<br><br>";
$resPagos = mysql_query ( $sqlPagos, $db );
while ( $rowPagos = mysql_fetch_assoc ( $resPagos ) ) {
	$fecha = $rowPagos['anopago']."-".$rowPagos['mespago']."-1";
	$fecha = strtotime ( '+1 month' , strtotime ($fecha)) ;
	$fecha = strtotime ( '-1 day' , strtotime (date ( 'Y-m-j' , $fecha ))) ;
	$fechaPago[$rowPagos['cuil']] = date ( 'Y-m-j' , $fecha );
}
unset($sqlPagos);
unset($resPagos);
//var_dump($fechaPago);echo"<br><br>";

$sqlDesempleo = "SELECT cuilbeneficiario, anodesempleo, mesdesempleo FROM desempleosss d where cuilbeneficiario in $wherein order by cuilbeneficiario, anodesempleo ASC ,mesdesempleo ASC";
//echo $sqlDesempleo . "<br><br>";
$resDesempleo = mysql_query ( $sqlDesempleo, $db );
while ( $rowDesempleo = mysql_fetch_assoc ( $resDesempleo ) ) {
	$fecha = $rowDesempleo['anodesempleo']."-".$rowDesempleo['mesdesempleo']."-1";
	$fecha = strtotime ( '+1 month' , strtotime ($fecha)) ;
	$fecha = strtotime ( '-1 day' , strtotime (date ( 'Y-m-j' , $fecha ))) ;
	$fechaDesemp[$rowDesempleo['cuilbeneficiario']] = date ( 'Y-m-j' , $fecha );
}
unset($sqlDesempleo);
unset($resDesempleo);
//var_dump($fechaDesemp);echo"<br><br>";

$ahora = date("Y-n-j H:i:s");
$_SESSION["ultimoAcceso"] = $ahora;

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Filtro de Titulares :.</title>

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
		<p><span class="Estilo2">Titulares para dar de Baja</span></p>
		<p><span class="Estilo2"><?php echo $canTituParaBajar ?> Titulares de <?php echo $cantidadTotal ?> a Bajar </span></p>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="bajarTitulares.php">
			<table style="text-align: center; width: 800px" id="tabla"
				class="tablesorter">
				<thead>
					<tr>
						<th>Nro.<br>Afil.</th>
						<th class="filter-select" data-placeholder="Seleccione Delegacion">Cod. <br >Dele.</th>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>C.U.I.T. Empresa</th>
						<th>Fecha Carnet</th>
						<th>Fecha De baja</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				 <?php 	 foreach($arrayInforme as $linea) { 
				 			$cuil = $linea['cuil'];
				 			$fechaBaja = mayorFecha($fechaDDJJ[$cuil],$fechaPago[$cuil],$fechaDesemp[$cuil]);
		            		if ($fechaBaja != null) {
								if ($fechaBaja < $linea['fechacarnet']) {
									$fechaBaja = $linea['fechacarnet'];
								}
							} else {
								$fechaBaja = $linea['fechacarnet'];
							}?>
		            	<tr>
							<td><?php echo $linea['nroafiliado'] ?></td>
							<td><?php echo $linea['codidelega'] ?></td>
							<td><?php echo $linea['cuil']   ?></td>
							<td><?php echo $linea['apellidoynombre']   ?></td>
							<td><?php echo $linea['cuitempresa']   ?></td>
							<td><?php echo $linea['fechacarnet']   ?></td>
							<td><?php echo $fechaBaja   ?></td>
							<?php if ($fechaBaja != '0000-00-00') { ?>
								<td><input type="checkbox" name="<?php echo $linea['nroafiliado'] ?>" id="baja" value="<?php echo $linea['cuil']."|".$fechaBaja ?> " /></td>
							<?php } else { ?>
								<td></td>
							<?php }?>
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