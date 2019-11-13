<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$id = $_GET['id'];
$sqlDetalle = "SELECT * FROM diabetespresentaciondetalle WHERE idpresentacion = $id ORDER BY nroafiliado";
$resDetalle = mysql_query($sqlDetalle,$db); 
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
			$nombre = $arrayTitularesDeBaja[$indexBusqueda]['nombre'];
			$cuil = $arrayTitularesDeBaja[$indexBusqueda]['cuil'];
			$fechaBajaInco = $arrayTitularesDeBaja[$indexBusqueda]['fechabaja'];
			$tipoBene .= "TITULAR DE BAJA (F.B: <b>".invertirFecha($fechaBajaInco)."</b>)";
		}
		if (array_key_exists($indexBusqueda, $arrayFamiliares)) {
			$nombre = $arrayFamiliares[$indexBusqueda]['nombre'];
			$cuil = $arrayFamiliares[$indexBusqueda]['cuil'];
			$tipoBene .= "FAMILIAR";
		}
		if (array_key_exists($indexBusqueda, $arrayFamiliaresDeBaja)) {
			$nombre = $arrayFamiliaresDeBaja[$indexBusqueda]['nombre'];
			$cuil = $arrayFamiliaresDeBaja[$indexBusqueda]['cuil'];
			$fechaBajaInco = $arrayFamiliaresDeBaja[$indexBusqueda]['fechabaja'];
			$tipoBene .= "FAMILIAR DE BAJA (F.B: <b>".invertirFecha($fechaBajaInco)."</b>)";
		}
		if ($tipoBene == "") { $tipoBene = "-"; }
		$arrayDetalle[$key]['nombre'] = $nombre;
		$arrayDetalle[$key]['cuil'] = $cuil;
		$arrayDetalle[$key]['tipoBene'] = $tipoBene;
		$arrayDetalle[$key]['delega'] = $delega;
	}
	//**********************************************************************//

$today = date("d-m-y");
$file= "DETALLE PRESENTACION ID ".$id.".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");?>

<body>
	<table border="1">
		<thead>
			<tr>
				<th>Nro. Afiliado</th>
				<th>Nombre</th>
				<th>CUIL</th>
				<th>Tipo Bene.</th>
				<th>Delegacion</th>
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
</body>