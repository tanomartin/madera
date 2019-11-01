<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

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
					$arrayDeBaja[$indexBene] = $rowListadoDiabetes;
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
		$delega = $arrayTitulares[$indexBusqueda]['delegacion'];
	}
	if (array_key_exists($indexBusqueda, $arrayTitularesDeBaja)) {
		$nombre = $arrayTitularesDeBaja[$indexBusqueda]['nombre'];
		$cuil = $arrayTitularesDeBaja[$indexBusqueda]['cuil'];
		$fechaBajaInco = $arrayTitularesDeBaja[$indexBusqueda]['fechabaja'];
		$tipoBene .= "TITULAR DE BAJA (F.B: <b>".invertirFecha($fechaBajaInco)."</b>)";
		$delega = $arrayTitularesDeBaja[$indexBusqueda]['delegacion'];
	}
	if (array_key_exists($indexBusqueda, $arrayFamiliares)) {
		$nombre = $arrayFamiliares[$indexBusqueda]['nombre'];
		$cuil = $arrayFamiliares[$indexBusqueda]['cuil'];
		$tipoBene .= "FAMILIAR";
		$delega = $arrayFamiliares[$indexBusqueda]['delegacion'];
	}
	if (array_key_exists($indexBusqueda, $arrayFamiliaresDeBaja)) {
		$nombre = $arrayFamiliaresDeBaja[$indexBusqueda]['nombre'];
		$cuil = $arrayFamiliaresDeBaja[$indexBusqueda]['cuil'];
		$fechaBajaInco = $arrayFamiliaresDeBaja[$indexBusqueda]['fechabaja'];
		$tipoBene .= "FAMILIAR DE BAJA (F.B: <b>".invertirFecha($fechaBajaInco)."</b>)";
		$delega = $arrayFamiliaresDeBaja[$indexBusqueda]['delegacion'];
	}
	if ($tipoBene == "") { $tipoBene = "-"; }	
	$arrayNuevo[$key] = $beneficiarios;
	$arrayNuevo[$key]['nombre'] = $nombre;
	$arrayNuevo[$key]['cuil'] = $cuil;
	$arrayNuevo[$key]['tipoBene'] = $tipoBene;
	$arrayNuevo[$key]['delega'] = $delega;
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
		$delega = $arrayTitulares[$indexBusqueda]['delegacion'];
	} 
	if (array_key_exists($indexBusqueda, $arrayTitularesDeBaja)) {
		$nombre = $arrayTitularesDeBaja[$indexBusqueda]['nombre']; 
		$cuil = $arrayTitularesDeBaja[$indexBusqueda]['cuil'];
		$fechaBajaInco = $arrayTitularesDeBaja[$indexBusqueda]['fechabaja'];
		$tipoBene .= "TITULAR DE BAJA (F.B: <b>".invertirFecha($fechaBajaInco)."</b>)";
		$delega = $arrayTitularesDeBaja[$indexBusqueda]['delegacion'];
	}
	if (array_key_exists($indexBusqueda, $arrayFamiliares)) { 
		$nombre = $arrayFamiliares[$indexBusqueda]['nombre']; 
		$cuil = $arrayFamiliares[$indexBusqueda]['cuil'];			
		$tipoBene .= "FAMILIAR";
		$delega = $arrayFamiliares[$indexBusqueda]['delegacion'];
	}
	if (array_key_exists($indexBusqueda, $arrayFamiliaresDeBaja)) { 
		$nombre = $arrayFamiliaresDeBaja[$indexBusqueda]['nombre']; 
		$cuil = $arrayFamiliaresDeBaja[$indexBusqueda]['cuil'];		
		$fechaBajaInco = $arrayFamiliaresDeBaja[$indexBusqueda]['fechabaja'];
		$tipoBene .= "FAMILIAR DE BAJA (F.B: <b>".invertirFecha($fechaBajaInco)."</b>)";
		$delega = $arrayFamiliaresDeBaja[$indexBusqueda]['delegacion'];
		
	} 
	if ($tipoBene == "") { $tipoBene = "-"; } 
	$arrayIncompletos[$indexBusqueda]['nombre'] = $nombre;
	$arrayIncompletos[$indexBusqueda]['cuil'] = $cuil;
	$arrayIncompletos[$indexBusqueda]['tipoBene'] = $tipoBene;
	$arrayIncompletos[$indexBusqueda]['delega'] = $delega;
	if ($fechaBajaInco != "") {
		$arrayIncoBaja[$indexBusqueda] = $beneInco;
		$arrayIncoBaja[$indexBusqueda]['nombre'] = $nombre;
		$arrayIncoBaja[$indexBusqueda]['cuil'] = $cuil;
		$arrayIncoBaja[$indexBusqueda]['tipoBene'] = $tipoBene;
		$arrayIncoBaja[$indexBusqueda]['delega'] = $delega;
		unset($arrayIncompletos[$indexBusqueda]);
	}
}
reset($arrayIncompletos);
//******************************************************************//

//AGREGO INFORMACION DE BENE A LOS QUE TIENEN DIAGNOSTICO Y ESTAN DE BAJA//
foreach($arrayDeBaja as $debaja) {
	$indexBusqueda = $debaja['nroafiliado']."-".$debaja['nroorden'];
	$tipoBene = "";
	if (array_key_exists($indexBusqueda, $arrayTitularesDeBaja)) {
		$nombre = $arrayTitularesDeBaja[$indexBusqueda]['nombre'];
		$cuil = $arrayTitularesDeBaja[$indexBusqueda]['cuil'];
		$tipoBene .= "TITULAR DE BAJA (F.B: <b>".invertirFecha($arrayTitularesDeBaja[$indexBusqueda]['fechabaja'])."</b>)";
		$delega = $arrayTitularesDeBaja[$indexBusqueda]['delegacion'];;
	}
	if (array_key_exists($indexBusqueda, $arrayFamiliaresDeBaja)) {
		$nombre = $arrayFamiliaresDeBaja[$indexBusqueda]['nombre'];
		$cuil = $arrayFamiliaresDeBaja[$indexBusqueda]['cuil'];
		$tipoBene .= "FAMILIAR DE BAJA (F.B: <b>".invertirFecha($arrayFamiliaresDeBaja[$indexBusqueda]['fechabaja'])."</b>)";
		$delega = $arrayFamiliaresDeBaja[$indexBusqueda]['delegacion'];
	}
	$arrayDeBaja[$indexBusqueda]['nombre'] = $nombre;
	$arrayDeBaja[$indexBusqueda]['cuil'] = $cuil;
	$arrayDeBaja[$indexBusqueda]['tipoBene'] = $tipoBene;
	$arrayDeBaja[$indexBusqueda]['delega'] = $delega;
}
reset($arrayDeBaja);
//*****************************************************************//
$today = date("d-m-y");
$file= "DIABETICOS al ".$today.".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file"); ?>

<body>
	<table border="1">
		<thead>
			<tr>
				<th>Nro. Afiliado</th>
				<th>Nombre y Apellido</th>
				<th>CUIL</th>
				<th>Tipo Bene.</th>
				<th class="filter-select" data-placeholder="Seleccione Dele">Dele</th>
				<th>Fecha Ficha</th>
				<th>Tipo</th>
			</tr>
		</thead>
		<tbody>
		<?php $index = 0;
			  if (sizeof($arrayDeBaja) > 0) {
			  	foreach($arrayNuevo as $completo) {  
				$index++; ?>
				<tr>
					<td><?php echo $completo['nroafiliado'] ?></td>
					<td><?php echo $completo['nombre']; ?></td>
					<td><?php echo $completo['cuil']; ?></td>
					<td><?php echo $completo['tipoBene']; ?></td>
					<td><?php echo $completo['delega']; ?></td>
					<td><?php echo $completo['fechaficha'] ?></td>
					<td><?php echo $completo['tipodiabetes'] ?></td>
				</tr>
		<?php   } 
			  }
			  if (sizeof($arrayDeBaja) > 0) { 
				 foreach($arrayDeBaja as $debaja) {   ?>
				 <tr>
					<td><?php echo $debaja['nroafiliado'] ?></td>
					<td><?php echo $debaja['nombre']; ?></td>
					<td><?php echo $debaja['cuil']; ?></td>
					<td><?php echo $debaja['tipoBene']; ?></td>
					<td><?php echo $debaja['delega']; ?></td>
					<td><?php echo $debaja['fechaficha'] ?></td>
					<td><?php echo $debaja['tipodiabetes'] ?></td>
				</tr>
		<?php   } 
			  }  
			  if (sizeof($arrayIncompletos) > 0) {
			  	foreach($arrayIncompletos as $incompleto) {?>	
			  	<tr>
			  		<td><?php echo $incompleto['nroafiliado'] ?></td>
					<td><?php echo $incompleto['nombre']; ?></td>
					<td><?php echo $incompleto['cuil']; ?></td>
					<td><?php echo $incompleto['tipoBene']; ?></td>
					<td><?php echo $incompleto['delega']; ?></td>  		
			  		<td><?php if (isset($incompleto['fechaficha'])) { echo $incompleto['fechaficha'];} ?></td>  		
			  		<td><?php if ($incompleto['diagnosticos'] == 0) {
								echo "SIN DIAGNOSTICO";
							  } else {
							  	echo $incompleto['tipodiabetes']." - DIAG. INCOMPLETO";
							  }	?>
				  	</td>
				 </tr>
			<?php   } 
			  } if (sizeof($arrayIncoBaja) > 0) {
			  		foreach($arrayIncoBaja as $incompleto) { ?>
			  			<tr>
							<td><?php echo $incompleto['nroafiliado'] ?></td>
							<td><?php echo $incompleto['nombre']; ?></td>
							<td><?php echo $incompleto['cuil']; ?></td>
							<td><?php echo $incompleto['tipoBene']; ?></td>
							<td><?php echo $incompleto['delega']; ?></td>
							<td><?php if (isset($incompleto['fechaficha'])) { echo $incompleto['fechaficha'];} ?></td>  		
							<td>
						<?php if ($incompleto['diagnosticos'] == 0) {
									echo "SIN DIAGNOSTICO";
							  } else {
							  		echo $incompleto['tipodiabetes']." - DIAG. INCOMPLETO";
							  }	?>
							</td>
						</tr>
			  <?php }
			  } ?>
		</tbody>
	</table>
</body>