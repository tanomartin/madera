<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$id = $_GET['id'];
$sqlCabecera = "SELECT * FROM diabetespresentacion WHERE id = $id";
$resCabecera = mysql_query($sqlCabecera,$db);
$rowCabecera = mysql_fetch_assoc($resCabecera);

$sqlDetalle = "SELECT * FROM diabetespresentaciondetalle WHERE idpresentacion = $id";
$resDetalle = mysql_query($sqlDetalle,$db); 
$canDetalle = mysql_num_rows($resDetalle);
if ($canDetalle > 0) {
	$arrayBeneDiab = array();
	$whereIn = "(";
	while ($rowDetalle = mysql_fetch_assoc($resDetalle)) {
		$indexBene = $rowDetalle['nroafiliado']."-".$rowDetalle['nroorden'];
		$arrayBeneDiab[$indexBene] = $rowDetalle['codidelega'];
		$whereIn .= $rowDetalle['nroafiliado'].",";
	}
	$whereIn = substr($whereIn, 0, -1);
	$whereIn .= ")";

	//*******************************************************************//
	
	//BUSCO TODOS LOS BENE Y LOS AGRUPO EN ARRAYS X TIPO//
	$sqlTitulares = "SELECT nroafiliado, apellidoynombre, cuil, delegaciones.nombre as delegacion, delegaciones.codidelega as codidelega
	FROM titulares
	LEFT JOIN delegaciones ON titulares.codidelega = delegaciones.codidelega
	WHERE nroafiliado in $whereIn";
	$resTitulares = mysql_query($sqlTitulares,$db);
	$arrayTitulares = array();
	while ($rowTitulares = mysql_fetch_assoc($resTitulares)) {
		$index = $rowTitulares['nroafiliado']."-0";
		$arrayTitulares[$index] = array("nombre" => $rowTitulares['apellidoynombre'], "cuil" => $rowTitulares['cuil'], "delegacion" =>  $rowTitulares['codidelega']." - ".$rowTitulares['delegacion']);
	}
	
	$sqlTitularesDeBaja = "SELECT nroafiliado, apellidoynombre, cuil, fechabaja, delegaciones.nombre as delegacion, delegaciones.codidelega as codidelega
	FROM titularesdebaja
	LEFT JOIN delegaciones ON titularesdebaja.codidelega = delegaciones.codidelega
	WHERE nroafiliado in $whereIn";
	$resTitularesDeBaja = mysql_query($sqlTitularesDeBaja,$db);
	$arrayTitularesDeBaja = array();
	while ($rowTitularesDeBaja = mysql_fetch_assoc($resTitularesDeBaja)) {
		$index = $rowTitularesDeBaja['nroafiliado']."-0";
		$arrayTitularesDeBaja[$index] = array("nombre" => $rowTitularesDeBaja['apellidoynombre'], "cuil" => $rowTitularesDeBaja['cuil'], "fechabaja" =>  $rowTitularesDeBaja['fechabaja'], "delegacion" =>  $rowTitularesDeBaja['codidelega']." - ".$rowTitularesDeBaja['delegacion']);
	}
	
	$sqlFamiliar = "SELECT  f.nroafiliado, f.nroorden, f.apellidoynombre, f.cuil,
	IF(delegaciones.nombre is NULL,
	concat(delebaja.codidelega,' - ',delebaja.nombre),
	concat(delegaciones.codidelega,' - ',delegaciones.nombre)) as delegacion
	FROM familiares f
	LEFT JOIN titulares ON titulares.nroafiliado = f.nroafiliado
	LEFT JOIN delegaciones ON delegaciones.codidelega = titulares.codidelega
	LEFT JOIN titularesdebaja ON titularesdebaja.nroafiliado = f.nroafiliado
	LEFT JOIN delegaciones as delebaja ON delebaja.codidelega = titularesdebaja.codidelega
	WHERE f.nroafiliado in $whereIn";
	$resFamiliar = mysql_query($sqlFamiliar,$db);
	$arrayFamiliares = array();
	while ($rowFamiliar = mysql_fetch_assoc($resFamiliar)) {
		$index = $rowFamiliar['nroafiliado']."-".$rowFamiliar['nroorden'];
		$arrayFamiliares[$index] = array("nombre" => $rowFamiliar['apellidoynombre'], "cuil" => $rowFamiliar['cuil'], "delegacion" =>  $rowFamiliar['delegacion']);
	}
	
	$sqlFamiliarDeBaja = "SELECT f.nroafiliado, f.nroorden, f.apellidoynombre, f.cuil, f.fechabaja,
	IF(delegaciones.nombre is NULL,
	concat(delebaja.codidelega,' - ',delebaja.nombre),
	concat(delegaciones.codidelega,' - ',delegaciones.nombre)) as delegacion
	FROM familiaresdebaja f
	LEFT JOIN titulares ON titulares.nroafiliado = f.nroafiliado
	LEFT JOIN delegaciones ON delegaciones.codidelega = titulares.codidelega
	LEFT JOIN titularesdebaja ON titularesdebaja.nroafiliado = f.nroafiliado
	LEFT JOIN delegaciones as delebaja ON delebaja.codidelega = titularesdebaja.codidelega
	WHERE f.nroafiliado in $whereIn";
	$resFamiliarDeBaja = mysql_query($sqlFamiliarDeBaja,$db);
	$arrayFamiliaresDeBaja = array();
	while ($rowFamiliarDeBaja = mysql_fetch_assoc($resFamiliarDeBaja)) {
		$index = $rowFamiliarDeBaja['nroafiliado']."-".$rowFamiliarDeBaja['nroorden'];
		$arrayFamiliaresDeBaja[$index] = array("nombre" => $rowFamiliarDeBaja['apellidoynombre'], "fechabaja" =>  $rowFamiliarDeBaja['fechabaja'], "cuil" => $rowFamiliarDeBaja['cuil'], "delegacion" =>  $rowFamiliarDeBaja['delegacion']);
	}
	//*****************************************************//
	
	$arrayDetalle = array();
	foreach ($arrayBeneDiab as $key => $delega) {
		$arrayKey = explode("-",$key);
		$indexBusqueda = $arrayKey[0]."-".$arrayKey[1];
		$tipoBene = "";
		$nombre = "-";
		$cuil = "-";
		$fechaBajaInco = "";
		if (array_key_exists($indexBusqueda, $arrayTitulares)) {
			$nombre = $arrayTitulares[$indexBusqueda]['nombre'];
			$cuil = $arrayTitulares[$indexBusqueda]['cuil'];
			$tipoBene = "TITULAR";
		}
		if (array_key_exists($indexBusqueda, $arrayTitularesDeBaja)) {
			$nombre = $arrayTitularesDeBaja[$indexBusqueda]['nombre']."<br>";
			$cuil = $arrayTitularesDeBaja[$indexBusqueda]['cuil'];
			$fechaBajaInco = $arrayTitularesDeBaja[$indexBusqueda]['fechabaja'];
			$tipoBene .= "TITULAR DE BAJA<br> (F.B: <b>".invertirFecha($fechaBajaInco)."</b>)";
		}
		if (array_key_exists($indexBusqueda, $arrayFamiliares)) {
			$nombre = $arrayFamiliares[$indexBusqueda]['nombre']."<br>";
			$cuil = $arrayFamiliares[$indexBusqueda]['cuil'];
			$tipoBene .= "FAMILIAR<br>";
		}
		if (array_key_exists($indexBusqueda, $arrayFamiliaresDeBaja)) {
			$nombre = $arrayFamiliaresDeBaja[$indexBusqueda]['nombre'];
			$cuil = $arrayFamiliaresDeBaja[$indexBusqueda]['cuil'];
			$fechaBajaInco = $arrayFamiliaresDeBaja[$indexBusqueda]['fechabaja'];
			$tipoBene .= "FAMILIAR DE BAJA<br> (F.B: <b>".invertirFecha($fechaBajaInco)."</b>)";
		}
		if ($tipoBene == "") { $tipoBene = "-"; }
		$arrayDetalle[$key]['nombre'] = $nombre;
		$arrayDetalle[$key]['cuil'] = $cuil;
		$arrayDetalle[$key]['tipoBene'] = $tipoBene;
		$arrayDetalle[$key]['delega'] = $delega;
	}
	//**********************************************************************//
} 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js" type="text/javascript"></script> 
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
	})
});

</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Diabetes Presentacion Detalle S.S.S. :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloPresSSS.php'" /></p>
	<h2>Presentacion Diabetes Detalle S.S.S.</h2>
	<?php $estado = "ACTIVA"; 
		  if ($rowCabecera['fechacancelacion'] != NULL) { $estado = "CANCELADA"; } 
		  if ($rowCabecera['fechadevolucion'] != NULL) { $estado = "FINALIZADA"; } ?>
	<h3>ID: <?php echo $rowCabecera['id'] ?> - PERIODO: <?php echo $rowCabecera['periodo'] ?> - ESTADO: <?php echo $estado." [".$canDetalle."]" ?></h3>
<?php if ($canDetalle > 0) {?>	
		<table style="text-align:center; width:800px;" id="listado" class="tablesorter" >
			<thead>
				<tr>
					<th>Nro. Afiliado</th>
					<th>Nombre</th>
					<th>CUIL</th>
					<th>Tipo Bene.</th>
					<th class="filter-select" data-placeholder="Seleccione Dele">Dele</th>
				</tr>
			</thead>
			<tbody>
	  <?php foreach ($arrayDetalle as $key => $detalle) { $arrayKey = explode("-",$key); ?>
	  			<tr>
					<td><?php echo $arrayKey[0] ?></td>
					<td><?php echo $detalle['nombre'] ?></td>
					<td><?php echo $detalle['cuil']; ?></td>
					<td><?php echo $detalle['tipoBene'] ?></td>
					<td><?php echo $detalle['delega']; ?></td>
				</tr>
	  <?php } ?>
	  		</tbody>
		</table>
<?php } else { ?>
		<h3 style="color: red">No se encontró el detalle para esta presentación</h3>
<?php } ?>
</div>
</body>
</html>