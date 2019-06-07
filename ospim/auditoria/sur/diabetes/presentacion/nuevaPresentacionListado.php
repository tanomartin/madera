<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$periodo = $_POST['periodo'];
$fechaHasta = substr($periodo,0,4)."-".substr($periodo,4,2)."-01";
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
						$arrayIncompletos[$indexBene] = $rowListadoDiabetes;
						$arrayIncompletos[$indexBene]['diagnosticos'] = -2;
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
	<h2>Nueva Presentación Listado Beneficiarios (<?php echo "# ".$canCantidadBeneDiab?>)</h2>
	<h3>Periodo a Generar '<?php echo $periodo ?>'</h3>
	<h3>Beneficiarios a Presentar (<?php echo "# ".sizeof($arrayCompletos) ?>)</h3>
<?php if (sizeof($arrayCompletos) > 0) { ?>
		<form id="nuevaPresentacionListado" name="nuevaPresentacionListado" method="post" onsubmit="return validar(this)" action="nuevaPresentacionArchivo.php?periodo=<?php echo $periodo ?>">	
			<table style="text-align:center; width:1000px;" id="completos" class="tablesorter" >
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th>Nombre y Apellido</th>
						<th>CUIL Titular</th>
						<th>Tipo Bene.</th>
						<th>Fecha Ficha</th>
						<th>Tipo</th>
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
							$nombre = $arrayTitularesDeBaja[$indexBusqueda]['nombre']; 
							$cuil = $arrayTitularesDeBaja[$indexBusqueda]['cuil'];
							$tipoBene = "TITULAR DE BAJA<br> (F.B: <b>".invertirFecha($arrayTitularesDeBaja[$indexBusqueda]['fechabaja'])."</b>)";
						}
						if (array_key_exists($indexBusqueda, $arrayFamiliares)) { 
							$nombre = $arrayFamiliares[$indexBusqueda]['nombre']; 
							$cuil = $arrayFamiliares[$indexBusqueda]['cuil'];
							$tipoBene = "FAMILIAR";
						} 
						if (array_key_exists($indexBusqueda, $arrayFamiliaresDeBaja)) { 
							$nombre = $arrayFamiliaresDeBaja[$indexBusqueda]['nombre']; 
							$cuil = $arrayFamiliaresDeBaja[$busquedaCUILTitu]['cuil'];
							$tipoBene = "FAMILIAR DE BAJA (F.B: <b>".invertirFecha($arrayFamiliaresDeBaja[$indexBusqueda]['fechabaja'])."</b>)";
						} ?>
						<tr>
							<td>
								<input style="display: none" type="text" id="datos<?php echo $indexBusqueda?>" name="datos<?php echo $indexBusqueda?>" value="<?php echo $completo["id"]."-".$cuil?>"/>
								<?php echo $completo['nroafiliado'] ?>
							</td>
							<td><?php echo $nombre; ?></td>
							<td><?php echo $cuil; ?></td>
							<td><?php echo $tipoBene; ?></td>
							<td><?php echo $completo['fechaficha'] ?></td>
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
	<h3>Beneficiarios con información Incompleta (<?php echo "# ".sizeof($arrayIncompletos) ?>)</h3>
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
							$tipoBene .= "TITULAR DE BAJA<br> (F.B: <b>".invertirFecha($arrayTitularesDeBaja[$indexBusqueda]['fechabaja'])."</b>)";
						}
						if (array_key_exists($indexBusqueda, $arrayFamiliares)) { 
							$nombre = $arrayFamiliares[$indexBusqueda]['nombre']."<br>"; 
							$cuil = $arrayFamiliares[$indexBusqueda]['cuil'];			
							$tipoBene .= "FAMILIAR<br>";
						} 
				 		if (array_key_exists($indexBusqueda, $arrayFamiliaresDeBaja)) { 
							$nombre = $arrayFamiliaresDeBaja[$indexBusqueda]['nombre']; 
							$cuil = $arrayFamiliaresDeBaja[$indexBusqueda]['cuil'];		
							$tipoBene .= "FAMILIAR DE BAJA<br> (F.B: <b>".invertirFecha($arrayFamiliaresDeBaja[$indexBusqueda]['fechabaja'])."</b>)";
						} 
						if ($tipoBene == "") { $tipoBene = "-"; } ?>
						<tr>
							<td><?php echo $incompleto['nroafiliado'] ?></td>
							<td><?php echo $nombre; ?></td>
							<td><?php echo $cuil; ?></td>
							<td><?php echo $tipoBene; ?></td>
							<td>
						<?php if ($incompleto['diagnosticos'] == 0) {
									echo "SIN DIAGNOSTICO";
							  } else {
							  		if ($incompleto['diagnosticos'] < 0) {
							  			if ($incompleto['diagnosticos'] == -1) { echo "CON DIAGNOSTICO <br> FUERA DE PERIODO"; }
							  			if ($incompleto['diagnosticos'] == -2) { echo "CON DIAGNOSTICO <br> AFIL. DE BAJA"; }
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
			<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	<?php } else { ?>
			<h3 style="color: blue">No existen beneficiarios con información inconmpleta</h3>
	<?php }?>
</div>
</body>
</html>