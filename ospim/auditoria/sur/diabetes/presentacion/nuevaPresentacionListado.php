<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$fechaDesde = fechaParaGuardar($_POST['fechadesde']);
$fechaHasta = fechaParaGuardar($_POST['fechahasta']);


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

$sqlTitularesDeBaja = "SELECT nroafiliado, apellidoynombre, cuil FROM titularesdebaja WHERE nroafiliado in $whereIn";
$resTitularesDeBaja = mysql_query($sqlTitularesDeBaja,$db);
$arrayTitularesDeBaja = array();
while ($rowTitularesDeBaja = mysql_fetch_assoc($resTitularesDeBaja)) {
	$index = $rowTitularesDeBaja['nroafiliado']."-0";
	$arrayTitularesDeBaja[$index] = array("nombre" => $rowTitularesDeBaja['apellidoynombre'], "cuil" => $rowTitularesDeBaja['cuil']);
}

$sqlFamiliar = "SELECT nroafiliado, nroorden, apellidoynombre, cuil FROM familiares WHERE nroafiliado in $whereIn";
$resFamiliar = mysql_query($sqlFamiliar,$db);
$arrayFamiliares = array();
while ($rowFamiliar = mysql_fetch_assoc($resFamiliar)) {
	$index = $rowFamiliar['nroafiliado']."-".$rowFamiliar['nroorden'];
	$arrayFamiliares[$index] = array("nombre" => $rowFamiliar['apellidoynombre']);
}

$sqlFamiliarDeBaja = "SELECT nroafiliado, nroorden, apellidoynombre, cuil FROM familiaresdebaja WHERE nroafiliado in $whereIn";
$resFamiliarDeBaja = mysql_query($sqlFamiliarDeBaja,$db);
$arrayFamiliaresDeBaja = array();
while ($rowFamiliarDeBaja = mysql_fetch_assoc($resFamiliarDeBaja)) {
	$index = $rowFamiliarDeBaja['nroafiliado']."-".$rowFamiliarDeBaja['nroorden'];
	$arrayFamiliaresDeBaja[$index] = array("nombre" => $rowFamiliarDeBaja['apellidoynombre']);
}
//*****************************************************//

//VEO COMO ESTAN CON RESPECTO A LA INFORMACION//
$sqlListadoDiabetes = "SELECT d.id, d.nroafiliado, d.nroorden, d.tipodiabetes, d.fechadiagnostico, 
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
						WHERE fechadiagnostico >= '$fechaDesde' and fechadiagnostico <= '$fechaHasta'
						ORDER BY fechadiagnostico ASC";
$resListadoDiabetes = mysql_query($sqlListadoDiabetes,$db);
$canListadoDiabetes = mysql_num_rows($resListadoDiabetes);
$arrayCompletos = array();
$arrayIncompletos = array();
$arrayAfiliados = array();
if ($canListadoDiabetes != 0) {
	while ($rowListadoDiabetes = mysql_fetch_assoc($resListadoDiabetes)) {
		$indexBene = $rowListadoDiabetes['nroafiliado']."-".$rowListadoDiabetes['nroorden'];
		$arrayAfiliados[$indexBene] = $indexBene;
		if ($rowListadoDiabetes['comorbilidad'] != NULL && $rowListadoDiabetes['complicaciones'] != NULL && 
			$rowListadoDiabetes['estudios'] != NULL && $rowListadoDiabetes['tratamiento'] != NULL && 
			$rowListadoDiabetes['farmacos'] != NULL) {
				$arrayCompletos[$indexBene] = $rowListadoDiabetes;
				$arrayCompletos[$indexBene]['diagnosticos'] = 1;
				if (array_key_exists($indexBene,$arrayIncompletos)) {
					unset($arrayIncompletos[$indexBene]);		
				}
		} else {
			if (!array_key_exists($indexBene,$arrayCompletos)) {
				$arrayIncompletos[$indexBene] = $rowListadoDiabetes;
				$arrayIncompletos[$indexBene]['diagnosticos'] = 1;
			}
		}
	}
}

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

//******************************************************//

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
	<h2>Nueva Presentaci�n Listado Beneficiarios (<?php echo "# ".$canCantidadBeneDiab?>)</h2>
	<h3>Diagn�sticos desde '<?php echo $_POST['fechadesde'] ?>' hasta '<?php echo $_POST['fechahasta'] ?>'</h3>
	<h3>Beneficiarios a Presentar (<?php echo "# ".sizeof($arrayCompletos) ?>)</h3>
<?php if (sizeof($arrayCompletos) > 0) { ?>
		<form id="nuevaPresentacionListado" name="nuevaPresentacionListado" method="post" onsubmit="return validar(this)" action="nuevaPresentacionArchivo.php?desde=<?php echo $fechaDesde ?>&hasta=<?php echo $fechaHasta ?>">	
			<table style="text-align:center; width:1000px;" id="completos" class="tablesorter" >
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th>Nombre y Apellido</th>
						<th>CUIL Titular</th>
						<th>Tipo Bene.</th>
						<th>Fecha Diagn�stico</th>
						<th>Tipo Diabetes</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($arrayCompletos as $completo) { 
						$indexBusqueda = $completo['nroafiliado']."-".$completo['nroorden'];
						$tipoBene = "";
						$nombre = "-"; 
						$cuil = "-";
						if (array_key_exists($indexBusqueda, $arrayTitulares)) { 
							$nombre = $arrayTitulares[$indexBusqueda]['nombre']; 
							$cuil = $arrayTitulares[$indexBusqueda]['cuil']; 
							$tipoBene = "TITULAR";
						} 
						if (array_key_exists($indexBusqueda, $arrayTitularesDeBaja)) {
							$nombre = $arrayTitularesDeBaja[$indexBusqueda]; 
							$cuil = $arrayTitularesDeBaja[$indexBusqueda]['cuil'];
							$tipoBene = "TITULAR DE BAJA";
						}
						if (array_key_exists($indexBusqueda, $arrayFamiliares)) { 
							$nombre = $arrayFamiliares[$indexBusqueda]; 
							
							$busquedaCUILTitu = $completo['nroafiliado']."-0";
							if (array_key_exists($busquedaCUILTitu,$arrayTitulares)) {
								$cuil = $arrayTitulares[$busquedaCUILTitu]['cuil'];
							} else {
								if (array_key_exists($busquedaCUILTitu,$arrayTitularesDeBaja)) {
									$cuil = $arrayTitularesDeBaja[$busquedaCUILTitu]['cuil'];
								}
							}
							
							$tipoBene = "FAMILIAR";
						} 
						if (array_key_exists($indexBusqueda, $arrayFamiliaresDeBaja)) { 
							$nombre = $arrayFamiliaresDeBaja[$indexBusqueda]; 
							
							$busquedaCUILTitu = $completo['nroafiliado']."-0";
							if (array_key_exists($busquedaCUILTitu,$arrayTitulares)) {
								$cuil = $arrayTitulares[$busquedaCUILTitu]['cuil'];
							} else {
								if (array_key_exists($busquedaCUILTitu,$arrayTitularesDeBaja)) {
									$cuil = $arrayTitularesDeBaja[$busquedaCUILTitu]['cuil'];
								}
							}
							
							$tipoBene = "FAMILIAR DE BAJA";
						} ?>
						<tr>
							<td>
								<input style="display: none" type="text" id="datos<?php echo $indexBusqueda?>" name="datos<?php echo $indexBusqueda?>" value="<?php echo $completo["id"]."-".$cuil?>"/>
								<?php echo $completo['nroafiliado'] ?>
							</td>
							<td><?php echo $nombre; ?></td>
							<td><?php echo $cuil; ?></td>
							<td><?php echo $tipoBene; ?></td>
							<td><?php echo $completo['fechadiagnostico'] ?></td>
							<td><?php echo $completo['tipodiabetes'] ?></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
			<button class="nover" type="submit" name="Submit">Generar Archivo CSV</button>
		</form>
<?php } else { ?>
		<h3 style="color: blue">No existen beneficiarios para presentar</h3>
<?php }?>
	<h3>Beneficiarios con informaci�n Incompleta (<?php echo "# ".sizeof($arrayIncompletos) ?>)</h3>
	<?php if (sizeof($arrayIncompletos) > 0) { ?>
			<table style="text-align:center; width:1000px;" id="incompletos" class="tablesorter" >
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th>Nombre y Apellido</th>
						<th>CUIL Titular</th>
						<th>Tipo Bene.</th>
						<th>Faltante</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($arrayIncompletos as $incompleto) { 
						$indexBusqueda = $incompleto['nroafiliado']."-".$incompleto['nroorden']; 
						$tipoBene = "";
						$nombre = "-";
						$cuil = "-";
						if (array_key_exists($indexBusqueda, $arrayTitulares)) { 
							$nombre = $arrayTitulares[$indexBusqueda]['nombre']; 
							$cuil = $arrayTitulares[$indexBusqueda]['cuil'];
							$tipoBene = "TITULAR";
						} 
						if (array_key_exists($indexBusqueda, $arrayTitularesDeBaja)) {
							$nombre = $arrayTitularesDeBaja[$indexBusqueda]['nombre']."<br>"; 
							$cuil = $arrayTitularesDeBaja[$indexBusqueda]['cuil'];
							$tipoBene .= "TITULAR DE BAJA<br>";
						}
						if (array_key_exists($indexBusqueda, $arrayFamiliares)) { 
							$nombre = $arrayFamiliares[$indexBusqueda]['nombre']."<br>"; 
							
							$busquedaCUILTitu = $incompleto['nroafiliado']."-0";
							if (array_key_exists($busquedaCUILTitu,$arrayTitulares)) {
								$cuil = $arrayTitulares[$busquedaCUILTitu]['cuil'];
							} else {
								if (array_key_exists($busquedaCUILTitu,$arrayTitularesDeBaja)) {
									$cuil = $arrayTitularesDeBaja[$busquedaCUILTitu]['cuil'];
								}
							}
							
							$tipoBene .= "FAMILIAR<br>";
						} 
				 		if (array_key_exists($indexBusqueda, $arrayFamiliaresDeBaja)) { 
							$nombre = $arrayFamiliaresDeBaja[$indexBusqueda]['nombre']; 
							
							$busquedaCUILTitu = $completo['nroafiliado']."-0";
							if (array_key_exists($busquedaCUILTitu,$arrayTitulares)) {
								$cuil = $arrayTitulares[$busquedaCUILTitu]['cuil'];
							} else {
								if (array_key_exists($busquedaCUILTitu,$arrayTitularesDeBaja)) {
									$cuil = $arrayTitularesDeBaja[$busquedaCUILTitu]['cuil'];
								}
							}
							
							$tipoBene .= "FAMILIAR DE BAJA<br>";
						} 
						if ($tipoBene == "") { $tipoBene = "-"; }?>
						<tr>
							<td><?php echo $incompleto['nroafiliado'] ?></td>
							<td><?php echo $nombre; ?></td>
							<td><?php echo $cuil; ?></td>
							<td><?php echo $tipoBene; ?></td>
							<td>
						<?php if ($incompleto['diagnosticos'] == 0) {
									echo "SIN DIAGNOSTICO";
							  } else {
							  		if ($incompleto['diagnosticos'] == -1) {
							  			echo "SIN DIAGNOSTICO EN FECHA";
							  		} else { 
							  			echo "<b>F.D: ".$incompleto['fechadiagnostico']."</b><br>";
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
			<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	<?php } else { ?>
			<h3 style="color: blue">No existen beneficiarios con informaci�n inconmpleta</h3>
	<?php }?>
</div>
</body>
</html>