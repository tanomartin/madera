<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$arrayProcFami = array();
$arrayOrden = array();

foreach ($_POST as $cuil => $datos) {
	if ($cuil != 'text' && $cuil != 'select') {
		$datos = explode('-',$datos);
		$cuiltitular = $datos[0];
		$nroafiliado = $datos[1];
		$arrayProcFami[$cuil] = array("cuiltitular" => $cuiltitular, "nroafil" => $nroafiliado);
	}
}

$whereIn = "(";
$whereInAfil = "(";
foreach ($arrayProcFami as $cuil => $fami) {
	$whereIn .= "'".$cuil."',";
	$whereInAfil .= $fami['nroafil'].",";
}
$whereIn = substr($whereIn, 0, -1);
$whereIn .= ")";
$whereInAfil = substr($whereInAfil, 0, -1);
$whereInAfil .= ")";

$sqlAEjecutar = array();
if ($whereIn != ")") {	
	$sqlFamiliarOrden = "SELECT nroafiliado, nroorden FROM familiares WHERE nroafiliado in $whereInAfil order by nroafiliado, nroorden ASC";
	$resFamiliarOrden = mysql_query ($sqlFamiliarOrden, $db);
	$canFamiliarOrden = mysql_num_rows($resFamiliarOrden);
	if ($canFamiliarOrden != 0) {
		while ($rowFamiliarOrden = mysql_fetch_assoc ($resFamiliarOrden)) {
			$arrayOrden[$rowFamiliarOrden['nroafiliado']] = $rowFamiliarOrden['nroorden'];
		}
	}
	
	$sqlPadron = "SELECT * FROM padronsss WHERE cuilfamiliar in $whereIn";
	$resPadron = mysql_query ( $sqlPadron, $db );
	while ($rowPadron = mysql_fetch_assoc ($resPadron)) {
		$cuilfamiliar = $rowPadron['cuilfamiliar'];
		
		$arrayOrden[$arrayProcFami[$cuilfamiliar]['nroafil']] += 1;
		
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
				
		$codidelega = $_GET['codidelega'];
		
		if ($rowPadron['telefono'] == '') {
			$telefono = 0;
		} else {
			$telefono = intval(preg_replace('/[^0-9]+/', '', $rowPadron['telefono']), 10);
		}
		
		if ($rowPadron['parentesco'] == 4 || $rowPadron['parentesco'] == 6) {
			$estudia = 1;
		} else {
			$estudia = 0;
		}
			
		$index = $cuilfamiliar.'F';
		$arrayProcFami[$cuilfamiliar] += array("nombre" => $rowPadron['apellidoynombre']);
		$sqlAEjecutar[$index] = "INSERT INTO familiares VALUES("
								.$arrayProcFami[$cuilfamiliar]['nroafil'].",".$arrayOrden[$arrayProcFami[$cuilfamiliar]['nroafil']].","
								.$rowPadron['parentesco'].",'".$rowPadron['apellidoynombre']."','"
								.$rowPadron['tipodocumento']."',".$rowPadron['nrodocumento'].",'".$rowPadron['fechanacimiento']."',"
								.$rowPadron['nacionalidad'].",'".$rowPadron['sexo']."',0,".$telefono.",NULL,'".$rowPadron['fechaaltaos']."',"
								.$rowPadron['incapacidad'].",NULL,".$estudia.",NULL,NULL,NULL,'".$rowPadron['cuilfamiliar']
								."',0,1,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'".$fecharegistro."','".$usuarioregistro."',NULL,NULL,'N')";
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
			//print($sql."<br><br>");
			$dbh->exec($sql);
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
		headers:{3:{filter:false, sorter:false}},
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
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../menuDescInfoFamiSSS.php'" />
		<h2>Informe de Proceso de Alta de Familiares en SSS</h2>

		<h3>Familiares Procesados</h3>
		<?php if (sizeof($arrayProcFami) > 0) { ?>
			<table style="text-align: center; width: 900px" id="tablaProcesoFami" class="tablesorter">
				<thead>
					<tr>
						<th>C.U.I.L.</th>
						<th>Nombre</th>
						<th>C.U.I.L. Titular</th>
					</tr>
				</thead>
				<tbody>
			<?php foreach ($arrayProcFami as $cuil => $datos) { ?>
				<tr>	
					<td><?php echo $cuil ?></td>
					<td><?php echo $datos['nombre'] ?></td>
					<td><?php echo $datos['cuiltitular'] ?></td>
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