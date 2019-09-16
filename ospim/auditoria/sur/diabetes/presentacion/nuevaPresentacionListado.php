<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$periodo = $_POST['periodo'];
$fechaDesde = substr($periodo,0,4)."-".substr($periodo,4,2)."-01";
$fechaHasta = $fechaDesde;
$fechaHasta = strtotime ('+1 month' , strtotime ($fechaHasta));
$fechaHasta = date('Y-m-d',$fechaHasta);


//BUSCO LA CANTIDAD TOTAL DE BENEFICIAIRIOS MARCADOS COMO DAIBETICOS//
$sqlCantidadBeneDiab = "SELECT * FROM diabetesbeneficiarios";
$resCantidadBeneDiab = mysql_query($sqlCantidadBeneDiab,$db);
$canCantidadBeneDiab = mysql_num_rows($resCantidadBeneDiab);
$arrayBeneDiab = array();
$whereIn = "(";
while ($rowCantidadBeneDiab = mysql_fetch_assoc($resCantidadBeneDiab)) {
	$indexBene = $rowCantidadBeneDiab['nroafiliado']."-".$rowCantidadBeneDiab['nroorden'];
	$arrayBeneDiab[$indexBene] = $indexBene;
	$whereIn .= $rowCantidadBeneDiab['nroafiliado'].",";
}
$whereIn = substr($whereIn, 0, -1);
$whereIn .= ")";
//*******************************************************************//

//BUSCO TODOS LOS BENE Y LOS AGRUPO EN ARRAYS X TIPO//
$sqlTitulares = "SELECT nroafiliado, apellidoynombre, cuil FROM titulares WHERE nroafiliado in $whereIn";
$resTitulares = mysql_query($sqlTitulares,$db);
$arrayTitulares = array();
while ($rowTitulares = mysql_fetch_assoc($resTitulares)) {
	$index = $rowTitulares['nroafiliado']."-0";
	$arrayTitulares[$index] = array("nombre" => $rowTitulares['apellidoynombre'], "cuil" => $rowTitulares['cuil']);
}

$sqlTitularesDeBaja = "SELECT nroafiliado, apellidoynombre, cuil, fechabaja FROM titularesdebaja WHERE nroafiliado in $whereIn";
$resTitularesDeBaja = mysql_query($sqlTitularesDeBaja,$db);
$arrayTitularesDeBaja = array();
while ($rowTitularesDeBaja = mysql_fetch_assoc($resTitularesDeBaja)) {
	$index = $rowTitularesDeBaja['nroafiliado']."-0";
	$arrayTitularesDeBaja[$index] = array("nombre" => $rowTitularesDeBaja['apellidoynombre'], "cuil" => $rowTitularesDeBaja['cuil'], "fechabaja" =>  $rowTitularesDeBaja['fechabaja']);
}

$sqlFamiliar = "SELECT nroafiliado, nroorden, apellidoynombre, cuil FROM familiares WHERE nroafiliado in $whereIn";
$resFamiliar = mysql_query($sqlFamiliar,$db);
$arrayFamiliares = array();
while ($rowFamiliar = mysql_fetch_assoc($resFamiliar)) {
	$index = $rowFamiliar['nroafiliado']."-".$rowFamiliar['nroorden'];
	$arrayFamiliares[$index] = array("nombre" => $rowFamiliar['apellidoynombre'], "cuil" => $rowFamiliar['cuil']);
}

$sqlFamiliarDeBaja = "SELECT nroafiliado, nroorden, apellidoynombre, cuil, fechabaja FROM familiaresdebaja WHERE nroafiliado in $whereIn";
$resFamiliarDeBaja = mysql_query($sqlFamiliarDeBaja,$db);
$arrayFamiliaresDeBaja = array();
while ($rowFamiliarDeBaja = mysql_fetch_assoc($resFamiliarDeBaja)) {
	$index = $rowFamiliarDeBaja['nroafiliado']."-".$rowFamiliarDeBaja['nroorden'];
	$arrayFamiliaresDeBaja[$index] = array("nombre" => $rowFamiliarDeBaja['apellidoynombre'], "fechabaja" =>  $rowFamiliarDeBaja['fechabaja'], "cuil" => $rowFamiliarDeBaja['cuil']);
}
//*****************************************************//

//VEO COMO ESTAN CON RESPECTO A LA INFORMACION//
$sqlListadoDiabetes = "SELECT d.id, d.nroafiliado, d.nroorden, d.tipodiabetes, fechaficha, 
							  diabetescomorbilidad.idDiagnostico as comorbilidad,
							  diabetescomplicaciones.idDiagnostico as complicaciones,
							  diabetesestudios.idDiagnostico as estudios,
							  diabetestratamientos.idDiagnostico as tratamiento,
							  diabetesfarmacos.idDiagnostico as farmacos
						FROM diabetesdiagnosticos d
						LEFT JOIN diabetescomorbilidad on diabetescomorbilidad.idDiagnostico = d.id
						LEFT JOIN diabetescomplicaciones on diabetescomplicaciones.idDiagnostico = d.id
						LEFT JOIN diabetesestudios on diabetesestudios.idDiagnostico = d.id
						LEFT JOIN diabetestratamientos on diabetestratamientos.idDiagnostico = d.id
						LEFT JOIN diabetesfarmacos on diabetesfarmacos.idDiagnostico = d.id
						WHERE fechaficha < '$fechaHasta'
						ORDER BY fechaficha ASC";
$resListadoDiabetes = mysql_query($sqlListadoDiabetes,$db);
$canListadoDiabetes = mysql_num_rows($resListadoDiabetes);
$arrayCompletos = array();
$arrayIncompletos = array();
$arrayAfiliados = array();
$arrayDeBaja = array();
if ($canListadoDiabetes != 0) {
	while ($rowListadoDiabetes = mysql_fetch_assoc($resListadoDiabetes)) {
		$indexBene = $rowListadoDiabetes['nroafiliado']."-".$rowListadoDiabetes['nroorden'];
		$arrayAfiliados[$indexBene] = $indexBene;
		if ($rowListadoDiabetes['comorbilidad'] != NULL && $rowListadoDiabetes['complicaciones'] != NULL && 
			$rowListadoDiabetes['estudios'] != NULL && $rowListadoDiabetes['tratamiento'] != NULL && 
			$rowListadoDiabetes['farmacos'] != NULL) {
				$fechaBaja = "";
				if (array_key_exists($indexBene,$arrayTitularesDeBaja)) {
					$fechaBaja = $arrayTitularesDeBaja[$indexBene]['fechabaja'];
				}
				if (array_key_exists($indexBene,$arrayFamiliaresDeBaja)) {
					$fechaBaja = $arrayFamiliaresDeBaja[$indexBene]['fechabaja'];
				}
				if ($fechaBaja != "") {
					if (strtotime($fechaBaja) >= strtotime($fechaHasta)) {
						$arrayCompletos[$indexBene] = $rowListadoDiabetes;
						$arrayCompletos[$indexBene]['diagnosticos'] = 1;
						if (array_key_exists($indexBene,$arrayIncompletos)) {
							unset($arrayIncompletos[$indexBene]);
						}
					} else {
						$arrayDeBaja[$indexBene] = $rowListadoDiabetes;
					}
				} else {
					$arrayCompletos[$indexBene] = $rowListadoDiabetes;
					$arrayCompletos[$indexBene]['diagnosticos'] = 1;
					if (array_key_exists($indexBene,$arrayIncompletos)) {
						unset($arrayIncompletos[$indexBene]);		
					}
				}
		} else {
			if (!array_key_exists($indexBene,$arrayCompletos)) {
				$arrayIncompletos[$indexBene] = $rowListadoDiabetes;
				$arrayIncompletos[$indexBene]['diagnosticos'] = 1;
			}
		}
	}
}
//**********************************************************************//

//SEPARO LOS DIAGNOSTICOS COMPLETOS VIEJOS Y NUEVOS Y PONGO INFO DEL AFILIADO
$arrayNuevo = array();
$arrayViejos = array();
foreach ($arrayCompletos as $key => $beneficiarios) {
	$indexBusqueda = $beneficiarios['nroafiliado']."-".$beneficiarios['nroorden'];
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
	if (strtotime($beneficiarios['fechaficha']) >= strtotime($fechaDesde)) {
		$arrayNuevo[$key] = $beneficiarios;
		$arrayNuevo[$key]['nombre'] = $nombre;
		$arrayNuevo[$key]['cuil'] = $cuil;
		$arrayNuevo[$key]['tipoBene'] = $tipoBene;
	} else {
		$arrayViejos[$key] = $beneficiarios;
		$arrayViejos[$key]['nombre'] = $nombre;
		$arrayViejos[$key]['cuil'] = $cuil;
		$arrayViejos[$key]['tipoBene'] = $tipoBene;
	}
}
//**********************************************************************//

//SEPARO LOS DIAGNOSTICO INCOMPLETO SI ESTA DE BAJA EN BENE Y AGREGO INFORMACION//
$sqlListadoSinDiagnostico = "SELECT nroafiliado,nroorden,diagnosticos FROM diabetesbeneficiarios WHERE diagnosticos = 0";
$resListadoSinDiagnostico = mysql_query($sqlListadoSinDiagnostico,$db);
$canListadoSinDiagnostico = mysql_num_rows($resListadoSinDiagnostico);
if ($canListadoSinDiagnostico != 0) {
	while ($rowListadoSinDiagnostico = mysql_fetch_assoc($resListadoSinDiagnostico)) {
		$indexBene = $rowListadoSinDiagnostico['nroafiliado']."-".$rowListadoSinDiagnostico['nroorden'];
		$arrayIncompletos[$indexBene] = $rowListadoSinDiagnostico;
		$arrayAfiliados[$indexBene] = $indexBene;
	}
}

foreach ($arrayBeneDiab as $beneTodos) {
	if (!array_key_exists($beneTodos, $arrayAfiliados))	{
		$arrayIndexAfil = explode("-",$beneTodos);
		$arrayIncompletos[$beneTodos]['nroafiliado'] = $arrayIndexAfil[0];
		$arrayIncompletos[$beneTodos]['nroorden'] = $arrayIndexAfil[1];
		$arrayIncompletos[$beneTodos]['diagnosticos'] = -1;
	}
}

$arrayIncoBaja = array();
foreach ($arrayIncompletos as $beneInco) {
	$indexBusqueda = $beneInco['nroafiliado']."-".$beneInco['nroorden']; 
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
	$arrayIncompletos[$indexBusqueda]['nombre'] = $nombre;
	$arrayIncompletos[$indexBusqueda]['cuil'] = $cuil;
	$arrayIncompletos[$indexBusqueda]['tipoBene'] = $tipoBene;
	if ($fechaBajaInco != "") {
		if (strtotime($fechaBajaInco) < strtotime($fechaHasta)) {
			$arrayIncoBaja[$indexBusqueda] = $beneInco;
			$arrayIncoBaja[$indexBusqueda]['nombre'] = $nombre;
			$arrayIncoBaja[$indexBusqueda]['cuil'] = $cuil;
			$arrayIncoBaja[$indexBusqueda]['tipoBene'] = $tipoBene;
			unset($arrayIncompletos[$indexBusqueda]);
 		}
	}
}
reset($arrayIncompletos);
//******************************************************************//

//AGREGO INFORMACION DE BENE A LOS QUE TIENEN DIAGNOSTICO Y ESTAN DE BAJA//
foreach($arrayDeBaja as $debaja) {
	$indexBusqueda = $debaja['nroafiliado']."-".$debaja['nroorden'];
	$tipoBene = "";
	if (array_key_exists($indexBusqueda, $arrayTitularesDeBaja)) {
		$nombre = $arrayTitularesDeBaja[$indexBusqueda]['nombre']."<br>";
		$cuil = $arrayTitularesDeBaja[$indexBusqueda]['cuil'];
		$tipoBene .= "TITULAR DE BAJA<br> (F.B: <b>".invertirFecha($arrayTitularesDeBaja[$indexBusqueda]['fechabaja'])."</b>)";
	}
	if (array_key_exists($indexBusqueda, $arrayFamiliaresDeBaja)) {
		$nombre = $arrayFamiliaresDeBaja[$indexBusqueda]['nombre'];
		$cuil = $arrayFamiliaresDeBaja[$indexBusqueda]['cuil'];
		$tipoBene .= "FAMILIAR DE BAJA<br> (F.B: <b>".invertirFecha($arrayFamiliaresDeBaja[$indexBusqueda]['fechabaja'])."</b>)";
	}
	$arrayDeBaja[$indexBusqueda]['nombre'] = $nombre;
	$arrayDeBaja[$indexBusqueda]['cuil'] = $cuil;
	$arrayDeBaja[$indexBusqueda]['tipoBene'] = $tipoBene;
}
reset($arrayDeBaja);
//*****************************************************************//

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Diabetes Presentacion S.S.S. :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

$(function() {
	$("#completos")
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
	
	}),
	$("#viejos")
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
	
	}),
	$("#debaja")
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
	
	}),
	$("#incompletosbaja")
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
	}),
	$("#incompletos")
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

function validar(formulario) {
	formulario.Submit.disabled = true;
	$.blockUI({ message: "<h1>Generando Archivo de Exportacion de Diabetes. Aguarde por favor...</h1>" });
	return true; 
}

</script>
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'nuevaPresentacion.php'" class="nover"  /></p>
	<h2>Nueva Presentaci�n Diabetes</h2>
	<h3>Periodo a Generar '<?php echo $periodo ?>' (<?php echo "# ".$canCantidadBeneDiab?>)</h3>
	<form id="nuevaPresentacionListado" name="nuevaPresentacionListado" method="post" onsubmit="return validar(this)" action="nuevaPresentacionArchivo.php?periodo=<?php echo $periodo ?>">	
	  <h3>Diagnosticos a Subir (<?php echo "# ".sizeof($arrayNuevo) ?>)</h3>
<?php if (sizeof($arrayNuevo) > 0) { ?>
		<table style="text-align:center; width:1000px;" id="completos" class="tablesorter" >
			<thead>
				<tr>
					<th>Nro. Afiliado</th>
					<th>Nombre y Apellido</th>
					<th>CUIL</th>
					<th>Tipo Bene.</th>
					<th>Fecha Ficha</th>
					<th>Tipo</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				$index = 0;
				foreach($arrayNuevo as $completo) {  
					$index++; ?>
					<tr>
						<td>
							<input style="display: none" type="text" id="datos<?php echo $index?>" name="datos<?php echo $index ?>" value="<?php echo $completo["id"]."-".$completo['cuil']?>"/>
							<?php echo $completo['nroafiliado'] ?>
						</td>
						<td><?php echo $completo['nombre']; ?></td>
						<td><?php echo $completo['cuil']; ?></td>
						<td><?php echo $completo['tipoBene']; ?></td>
						<td><?php echo $completo['fechaficha'] ?></td>
						<td><?php echo $completo['tipodiabetes'] ?></td>
					</tr>
			<?php } ?>
			</tbody>
		</table>
<?php } else { ?>
		<h3 style="color: blue">No existen beneficiarios nuevos para subir</h3>
<?php }?>
	  <h3>Diagnosticos Importados (<?php echo "# ".sizeof($arrayViejos) ?>)</h3>
<?php if (sizeof($arrayViejos) > 0) { ?>
		<table style="text-align:center; width:1000px;" id="viejos" class="tablesorter" >
			<thead>
				<tr>
					<th>Nro. Afiliado</th>
					<th>Nombre y Apellido</th>
					<th>CUIL</th>
					<th>Tipo Bene.</th>
					<th>Fecha Ficha</th>
					<th>Tipo</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($arrayViejos as $completo) {  
					$index++; ?>
					<tr>
						<td><?php echo $completo['nroafiliado'] ?></td>
						<td><?php echo $completo['nombre']; ?></td>
						<td><?php echo $completo['cuil']; ?></td>
						<td><?php echo $completo['tipoBene']; ?></td>
						<td><?php echo $completo['fechaficha'] ?></td>
						<td><?php echo $completo['tipodiabetes'] ?></td>
						<td><input type="checkbox" id="check<?php echo $index?>" name="check<?php echo $index?>" value="<?php echo $completo["id"]."-".$completo['cuil']?>"/></td>
					</tr>
			<?php } ?>
			</tbody>
		</table>
<?php } else { ?>
		<h3 style="color: blue">No existen beneficiarios para presentar</h3>
<?php }?>
	  <button class="nover" type="submit" name="Submit">Generar Presentacion</button>
	</form>
	
	<hr style="margin-top: 25px"></hr>
	<h3>INFORMACION DE BENEFICIARIOS DIABETICOS QUE NO PUEDEN SER SUBIDOS</h3>
	
	<h3>Diagnosticos Completos Afiliados de Baja (<?php echo "# ".sizeof($arrayDeBaja) ?>)</h3>
	<?php if (sizeof($arrayDeBaja) > 0) { ?>
			<table style="text-align:center; width:1000px;" id="debaja" class="tablesorter" >
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th>Nombre y Apellido</th>
						<th>CUIL Titular</th>
						<th>Tipo Bene.</th>
						<th>Motivo</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($arrayDeBaja as $debaja) {   ?>
						<tr>
							<td><?php echo $debaja['nroafiliado'] ?></td>
							<td><?php echo $debaja['nombre']; ?></td>
							<td><?php echo $debaja['cuil']; ?></td>
							<td><?php echo $debaja['tipoBene']; ?></td>
							<td>CON DIAGNOSTICO</br><b>F.F: <?php echo $debaja['fechaficha'] ?></b></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
	<?php }?>
	<h3>Diagnosticos con informaci�n Incompleta (<?php echo "# ".sizeof($arrayIncompletos) ?>)</h3>
	<?php if (sizeof($arrayIncompletos) > 0) { ?>
			<table style="text-align:center; width:1000px;" id="incompletos" class="tablesorter" >
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th>Nombre y Apellido</th>
						<th>CUIL Titular</th>
						<th>Tipo Bene.</th>
						<th>Motivo</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($arrayIncompletos as $incompleto) { ?>
						<tr>
							<td><?php echo $incompleto['nroafiliado'] ?></td>
							<td><?php echo $incompleto['nombre']; ?></td>
							<td><?php echo $incompleto['cuil']; ?></td>
							<td><?php echo $incompleto['tipoBene']; ?></td>
							<td>
						<?php if ($incompleto['diagnosticos'] == 0) {
									echo "SIN DIAGNOSTICO";
							  } else {
							  		if ($incompleto['diagnosticos'] < 0) {
							  			if ($incompleto['diagnosticos'] == -1) { echo "CON DIAGNOSTICO <br> FUERA DE PERIODO"; }
							  		} else { 
							  			echo "<b>F.F: ".$incompleto['fechaficha']."</b><br>";
										if ($incompleto['comorbilidad'] == NULL) { echo "COMORBILIDAD<br>"; }  	
										if ($incompleto['complicaciones'] == NULL) { echo "COMPLICACIONES<br>"; }
										if ($incompleto['estudios'] == NULL) { echo "ESTUDIOS<br>"; }
										if ($incompleto['tratamiento'] == NULL) { echo "TRATAMIENTOS<br>"; }
										if ($incompleto['farmacos'] == NULL) { echo "FARMACOS"; }
							  		}
							  }	?>
							</td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
	<?php } else { ?>
			<h3 style="color: blue">No existen beneficiarios con informaci�n inconmpleta</h3>
	<?php }?>
	<h3>Diagnosticos con informaci�n Incompleta Beneficiarios de Baja (<?php echo "# ".sizeof($arrayIncoBaja) ?>)</h3>
	<?php if (sizeof($arrayIncoBaja) > 0) { ?>
			<table style="text-align:center; width:1000px;" id="incompletosbaja" class="tablesorter" >
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th>Nombre y Apellido</th>
						<th>CUIL Titular</th>
						<th>Tipo Bene.</th>
						<th>Motivo</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($arrayIncoBaja as $incompleto) { ?>
						<tr>
							<td><?php echo $incompleto['nroafiliado'] ?></td>
							<td><?php echo $incompleto['nombre']; ?></td>
							<td><?php echo $incompleto['cuil']; ?></td>
							<td><?php echo $incompleto['tipoBene']; ?></td>
							<td>
						<?php if ($incompleto['diagnosticos'] == 0) {
									echo "SIN DIAGNOSTICO";
							  } else {
							  		if ($incompleto['diagnosticos'] < 0) {
							  			if ($incompleto['diagnosticos'] == -1) { echo "CON DIAGNOSTICO <br> FUERA DE PERIODO"; }
							  		} else { 
							  			echo "<b>F.F: ".$incompleto['fechaficha']."</b><br>";
										if ($incompleto['comorbilidad'] == NULL) { echo "COMORBILIDAD<br>"; }  	
										if ($incompleto['complicaciones'] == NULL) { echo "COMPLICACIONES<br>"; }
										if ($incompleto['estudios'] == NULL) { echo "ESTUDIOS<br>"; }
										if ($incompleto['tratamiento'] == NULL) { echo "TRATAMIENTOS<br>"; }
										if ($incompleto['farmacos'] == NULL) { echo "FARMACOS"; }
							  		}
							  }	?>
							</td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
	<?php } else { ?>
			<h3 style="color: blue">No existen beneficiarios de baja con informaci�n inconmpleta</h3>
	<?php }?>
</div>
</body>
</html>