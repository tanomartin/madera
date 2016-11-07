<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

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
$arrayTipoTitu = array();
$arrayFechaEmpresa = array();
$arrayTiposAceptados = array(0,2,4,5,8);

foreach ($_POST as $tipocuil => $datos) {
	$tipo = substr($tipocuil, 0, 1);
	$cuil = substr($tipocuil, 1, strlen($tipocuil));
	$datos = explode('-',$datos);
	$cuit = $datos[0];
	$tipoTitu = $datos[1];
	$opcion = $datos[2];
	if ($tipo == 'R') {
		$nroafil = $datos[3];
	}
	
	$sqlTipoTitu = "SELECT descrip FROM tipotitular where codtiptit = $tipoTitu";
	$resTipoTitu = mysql_query ( $sqlTipoTitu, $db );
	$rowTipoTitu = mysql_fetch_assoc($resTipoTitu);
	$arrayTipoTitu[$cuil] = array("tipo" => $tipoTitu, "descrip" => $rowTipoTitu['descrip']);
	
	if ($opcion != 0) {
		$arrayInfo[$cuil] = array("detalle" => "Opción", "cuit" => $cuit, "proceso" => $tipo, "tipotitular" => $arrayTipoTitu[$cuil]['descrip']);
	} else {
		if (!in_array($tipoTitu,$arrayTiposAceptados)) {		
			$arrayInfo[$cuil] = array("detalle" => "No es un tipo de titular manejado", "cuit" => $cuit, "proceso" => $tipo, "tipotitular" => $arrayTipoTitu[$cuil]['descrip']);
		} else {
			if (!array_key_exists ($cuit , $arrayCuit)) {
				if (!array_key_exists ($cuit , $arrayCuitBaja)) {
					$arrayInfo[$cuil] = array("detalle" => "La empresa no existe", "cuit" => $cuit, "proceso" => $tipo, "tipotitular" => $arrayTipoTitu[$cuil]['descrip']);
				} else {
					$arrayInfo[$cuil] = array("detalle" => "La empresa esta de baja", "cuit" => $cuit, "proceso" => $tipo, "tipotitular" => $arrayTipoTitu[$cuil]['descrip']);
				} 
			} else {
				if ($tipo == 'A') {
					$arrayProcTitu[$cuil] = array("cuit" => $cuit, "proceso" => $tipo, "tipotitular" => $tipoTitu, "tipodescrip" => $arrayTipoTitu[$cuil]['descrip']);	
				}
				if ($tipo == 'R') {
					$arrayProcTitu[$cuil] = array("cuit" => $cuit, "proceso" => $tipo, "nroafil" => $nroafil, "tipotitular" => $tipoTitu,  "tipodescrip" => $arrayTipoTitu[$cuil]['descrip']);
				}
			}
		}
	}
}

$whereIn = "(";
foreach ($arrayProcTitu as $cuil => $titu) {
	if ($titu['tipotitular'] == 2 || $titu['tipotitular'] == 8) {
		$sqlDesempleo = "SELECT anodesempleo, mesdesempleo FROM desempleoSSS WHERE cuilbeneficiario = '".$cuil."' order by anodesempleo ASC, mesdesempleo ASC LIMIT 1;";
		$resDesempleo = mysql_query($sqlDesempleo, $db);
		$canDesempleo = mysql_num_rows($resDesempleo);
		if ($canDesempleo != 0) {
			$rowDesempleo = mysql_fetch_assoc($resDesempleo);
			$arrayFechaEmpresa[$cuil] = $rowDesempleo['anodesempleo']."-".$rowDesempleo['mesdesempleo']."-01";
		}
	} else {
		if ($titu['tipotitular'] == 4) {
			$sqlPrimerAporte = "SELECT anopago, mespago FROM afiptransferencias WHERE cuil = '".$cuil."' order by anopago ASC, mespago ASC LIMIT 1;";
			$resPrimerAporte = mysql_query($sqlPrimerAporte, $db);
			$canPrimerAporte = mysql_num_rows($resPrimerAporte);
			if ($canPrimerAporte != 0) {
				$rowPrimerAporte = mysql_fetch_assoc($resPrimerAporte);
				$arrayFechaEmpresa[$cuil] = $rowPrimerAporte['anopago']."-".$rowPrimerAporte['mespago']."-01";
			}
		} else {
			$sqlPrimeraDDJJ = "SELECT anoddjj, mesddjj FROM detddjjospim WHERE cuit = '".$titu['cuit']."' and cuil = '".$cuil."' order by anoddjj ASC, mesddjj ASC LIMIT 1";
			$resPrimeraDDJJ = mysql_query($sqlPrimeraDDJJ, $db);
			$canPrimeraDDJJ = mysql_num_rows($resPrimeraDDJJ);
			if ($canPrimeraDDJJ != 0) {
				$rowPrimeraDDJJ = mysql_fetch_assoc($resPrimeraDDJJ);
				$arrayFechaEmpresa[$cuil] = $rowPrimeraDDJJ['anoddjj']."-".$rowPrimeraDDJJ['mesddjj']."-01";
			}
		}
	}
	
	if ($canPrimeraDDJJ != 0 || $canDesempleo != 0 || $canPrimerAporte != 0) {
		$whereIn .= "'".$cuil."',";
	} else {
		unset($arrayProcTitu[$cuil]);
		if ($titu['tipotitular'] == 2 || $titu['tipotitular'] == 8) {
			$arrayInfo[$cuil] = array("detalle" => "No se puede encontrar DESEMPLEO para el CUIL a procesar", "cuit" => $titu['cuit'], "proceso" => $titu['proceso'], "tipotitular" => $arrayTipoTitu[$cuil]['descrip']);
		} else {
			if ($titu['tipotitular'] == 4) {
				$arrayInfo[$cuil] = array("detalle" => "No se puede encontrar Aportes de la empresa declarada", "cuit" => $titu['cuit'], "proceso" => $titu['proceso'], "tipotitular" => $arrayTipoTitu[$cuil]['descrip']);
			} else {
				$arrayInfo[$cuil] = array("detalle" => "No se puede encontrar DDJJ de la empresa declarada", "cuit" => $titu['cuit'], "proceso" => $titu['proceso'], "tipotitular" => $arrayTipoTitu[$cuil]['descrip']);
			}
		}
	}
}
$whereIn = substr($whereIn, 0, -1);
$whereIn .= ")";

if ($whereIn != ")") {
	$sqlPadron = "SELECT * FROM padronsss where cuiltitular in $whereIn";
	$resPadron = mysql_query ( $sqlPadron, $db );
	$orden = 0;
	while ($rowPadron = mysql_fetch_assoc ($resPadron)) {
		$cuiltitular = $rowPadron['cuiltitular'];
			
		$sqlProvin = "SELECT indpostal FROM provincia WHERE codprovin = ".$rowPadron['codprovin'];
		$resProvin = mysql_query ( $sqlProvin, $db );
		$canProvin = mysql_num_rows($resProvin);
		if ($canProvin != 0) {
			$rowProvin = mysql_fetch_assoc ($resProvin);
			$indpostal = $rowProvin['indpostal'];
		} else {
			$indpostal = '';
		}
			
		$codpostal = intval(preg_replace('/[^0-9]+/', '', $rowPadron['codigopostal']), 10);
			
		$sqlLocali = "SELECT codlocali FROM localidades p where codprovin = ".$rowPadron['codprovin']." and numpostal = ".$codpostal." and nomlocali like '".$rowPadron['localidad']."'"; 
		$resLocali = mysql_query ( $sqlLocali, $db );
		$canLocali = mysql_num_rows($resLocali);
		if ($canLocali != 0) {
			$rowLocali = mysql_fetch_assoc ($resLocali);
			$codlocali = $rowLocali['codlocali'];
		} else {
			$codlocali = 0;
		}
			
		$domicilio = $rowPadron['calledomicilio']." ".$rowPadron['puertadomicilio']." ". $rowPadron['pisodomicilio']." ".$rowPadron['deptodomicilio'];
				
		if ($rowPadron['tipotitular'] == 2 || $rowPadron['tipotitular'] == 8) {
			$codidelega = 3200;
		} else {
			$sqlJuris = "SELECT codidelega FROM jurisdiccion WHERE cuit = ".$rowPadron['cuit']." order by disgdinero DESC LIMIT 1";
			$resJuris = mysql_query ( $sqlJuris, $db );
			$rowJuris = mysql_fetch_assoc ($resJuris);
			$codidelega = $rowJuris['codidelega'];
		}
		
		if ($rowPadron['telefono'] == '') {
			$telefono = 'NULL';
		} else {
			$telefono =  intval(preg_replace('/[^0-9]+/', '', $rowPadron['telefono']), 10);;
		}
			
		if ($arrayProcTitu[$cuiltitular]['proceso'] == 'A') {
			if ($rowPadron['parentesco'] == 0) {
				$arrayProcTitu[$cuiltitular] += array("nombre" => $rowPadron['apellidoynombre']);
				$index = $cuiltitular.'T'.$orden;		
				$sqlAEjecutar[$index] = "INSERT INTO titulares VALUES(DEFAULT,'".$rowPadron['apellidoynombre']."','".$rowPadron['tipodocumento']."',"
												.$rowPadron['nrodocumento'].",'".$rowPadron['fechanacimiento']."',".$rowPadron['nacionalidad'].
												",'".$rowPadron['sexo']."',".$rowPadron['estadocivil'].",".$rowPadron['codprovin'].",'".$indpostal.
												"',".$codpostal.",'',".$codlocali.",'".$domicilio."',NULL,".$telefono.",NULL,'".$rowPadron['fechaaltaos']."',
												'R',NULL,".$rowPadron['tipotitular'].",".$rowPadron['incapacidad'].",NULL,'".$cuiltitular."',
												'".$rowPadron['cuit']."','".$arrayFechaEmpresa[$cuiltitular]."',".$codidelega.",NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,
												'".$fecharegistro."','".$usuarioregistro."',NULL,NULL,'N')";
			} else {
				if ($rowPadron['parentesco'] == 4 || $rowPadron['parentesco'] == 6) {
					$estudia = 1;
				} else {
					$estudia = 0;
				}
				
				$index = $cuiltitular.'F'.$orden;
				$arrayProcFami[$rowPadron['cuilfamiliar']] = array("cuiltitular" => $cuiltitular, "nombre" => $rowPadron['apellidoynombre']);
				$sqlAEjecutar[$index] = "INSERT INTO familiares VALUES(#afi,#ord,".$rowPadron['parentesco'].",'".$rowPadron['apellidoynombre']."','"
												.$rowPadron['tipodocumento']."',".$rowPadron['nrodocumento'].",'".$rowPadron['fechanacimiento']."',"
												.$rowPadron['nacionalidad'].",'".$rowPadron['sexo']."',NULL,".$telefono.",NULL,'".$rowPadron['fechaaltaos']."',"
												.$rowPadron['incapacidad'].",NULL,".$estudia.",NULL,NULL,NULL,'".$rowPadron['cuilfamiliar']
												."',0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'".$fecharegistro."','".$usuarioregistro."',NULL,NULL,'N')";
			}
		}
		if ($arrayProcTitu[$cuiltitular]['proceso'] == 'R' && $rowPadron['parentesco'] == 0) {
			$arrayProcTitu[$cuiltitular] += array("nombre" =>$rowPadron['apellidoynombre'], "nroafil" => $arrayProcTitu[$cuiltitular]['nroafil']);
			$index = $cuiltitular.'T'.$orden;
			$sqlAEjecutar[$index] = "INSERT INTO titulares VALUES(".$arrayProcTitu[$cuiltitular]['nroafil'].",'".$rowPadron['apellidoynombre']."','".$rowPadron['tipodocumento']."',"
												.$rowPadron['nrodocumento'].",'".$rowPadron['fechanacimiento']."',".$rowPadron['nacionalidad'].
												",'".$rowPadron['sexo']."',".$rowPadron['estadocivil'].",".$rowPadron['codprovin'].",'".$indpostal.
												"',".$codpostal.",'',".$codlocali.",'".$domicilio."',NULL,".$telefono.",NULL,'".$rowPadron['fechaaltaos']."',
												'R',NULL,".$rowPadron['tipotitular'].",".$rowPadron['incapacidad'].",NULL,'".$cuiltitular."',
												'".$rowPadron['cuit']."','".$arrayFechaEmpresa[$cuiltitular]."',".$codidelega.",NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,
												'".$fecharegistro."','".$usuarioregistro."',NULL,NULL,'N')";
			$orden++;
			$index = $cuiltitular.'D'.$orden;
			$sqlAEjecutar[$index] = "DELETE FROM titularesdebaja WHERE nroafiliado = ".$arrayProcTitu[$cuiltitular]['nroafil'];
		}
		$orden++;
	}
} 

if (sizeof($sqlAEjecutar) > 0) {
	krsort($sqlAEjecutar, SORT_ASC);
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		
		foreach($sqlAEjecutar as $key=>$sql) {
			$cuiltitular = substr($key, 0, 11); 
			$posT = strpos($key, 'T');
			if ($posT !== false) {
				$orden = 0;
				//print($sql."<br>");
				$dbh->exec($sql);
				$nroAfiliado = $dbh->lastInsertId();
				$arrayProcTitu[$cuiltitular] +=  array("nroafil" => $nroAfiliado);
			} else {
				$orden++;
				$posF = strpos($key, 'F');
				if ($posF !== false) {
					$sql = str_replace('#afi', $nroAfiliado, $sql);
					$sql = str_replace('#ord', $orden, $sql);
					//print($sql."<br>");
					$dbh->exec($sql);
				} else {
					//print($sql."<br>");
					$dbh->exec($sql);
				}
			}
		}
		$dbh->commit();
	} catch(PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}	
	
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
		headers:{4:{filter:false, sorter:false}},
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
		<h2>Informe de Proceso de descarga Titulares en SSS</h2>
		<h3>Titulares sin procesar</h3>
		<?php if (sizeof($arrayInfo) > 0) { ?>
		<table style="text-align: center; width: 900px" id="tablaInforme" class="tablesorter">
			<thead>
				<tr>
					<th>C.U.I.L.</th>
					<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo Titular </th>
					<th>C.U.I.T.</th>
					<th class="filter-select" data-placeholder="Seleccione Proceso">Proceso</th>
					<th>Detalle</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($arrayInfo as $cuil => $datos) { ?>
				<tr>	
					<td><?php echo $cuil ?></td>
					<td><?php echo $datos['tipotitular'] ?></td>
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
					<th>Nro Afiliado</th>
					<th>C.U.I.L.</th>
					<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo Titular </th>
					<th>Nombre y Apellido</th>
					<th>C.U.I.T.</th>
					<th class="filter-select" data-placeholder="Seleccione Proceso">Proceso</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($arrayProcTitu as $cuil => $datos) { ?>
				<tr>	
					<td><?php echo $datos['nroafil'] ?></td>
					<td><?php echo $cuil ?></td>
					<td><?php echo $datos['tipodescrip'] ?></td>
					<td><?php echo $datos['nombre'] ?></td>
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