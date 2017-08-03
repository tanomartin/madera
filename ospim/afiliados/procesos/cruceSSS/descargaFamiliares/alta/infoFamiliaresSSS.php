<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

$sqlCuilTitular = "SELECT nroafiliado, cuil, codidelega FROM titulares";
$resCuilTitular = mysql_query ( $sqlCuilTitular, $db );
$arrayCuilTitular = array();
while ($rowCuilTitular = mysql_fetch_assoc ($resCuilTitular)) {
	$arrayCuilTitular[$rowCuilTitular['cuil']] = array("codidelega" => $rowCuilTitular['codidelega'], "nroafiliado" => $rowCuilTitular['nroafiliado']);
}

$sqlFamiSSS = "SELECT DISTINCT cuilfamiliar, cuiltitular, nrodocumento, cuit, apellidoynombre, tipotitular, osopcion 
					FROM padronsss p 
					WHERE parentesco != 0 and tipotitular in (0,2,4,5,8) and osopcion = 0";
$resFamiSSS = mysql_query ( $sqlFamiSSS, $db );
$arrayFamiSSS = array();
while ($rowFamiSSS = mysql_fetch_assoc ($resFamiSSS)) {
	$cuilfamiliar = preg_replace('/[^0-9]+/', '', $rowFamiSSS['cuilfamiliar']);
	if (strlen($cuilfamiliar) == 11) {
		$arrayFamiSSS[$cuilfamiliar] = array('cuiltitular'=> $rowFamiSSS['cuiltitular'], 'nrodoc' => $rowFamiSSS['nrodocumento'], 'cuit' => $rowFamiSSS['cuit'], 'nombre' => $rowFamiSSS['apellidoynombre'], 'tipotitular' => $rowFamiSSS['tipotitular'], 'osopcion' => $rowFamiSSS['osopcion']);
	}
}

$sqlFami = "SELECT DISTINCT cuil, nrodocumento, nroafiliado FROM familiares t";
$resFami = mysql_query ( $sqlFami, $db );
$arrayFami = array();
while ($rowFami = mysql_fetch_assoc ($resFami)) {
	$arrayFami[$rowFami['cuil']] = $rowFami['nrodocumento'];
}

$sqlFamiBaja = "SELECT DISTINCT cuil, nrodocumento, nroafiliado  FROM familiaresdebaja t";
$resFamiBaja = mysql_query ( $sqlFamiBaja, $db );
$arrayFamiBaja = array();
while ($rowFamiBaja = mysql_fetch_assoc ($resFamiBaja)) {
	$arrayFamiBaja[$rowFamiBaja['cuil']] = $rowFamiBaja['nrodocumento'];
	$arrayFamiNroAfil[$rowFamiBaja['cuil']] = $rowFamiBaja['nroafiliado'];
}

$sqlDelegaciones = "SELECT * FROM delegaciones WHERE codidelega not in (3500,4000,4001)";
$resDelegaciones = mysql_query ( $sqlDelegaciones, $db );
$arrayInforme = array();
while ($rowDelegaciones = mysql_fetch_assoc ($resDelegaciones)) {
	$arrayInforme[$rowDelegaciones['codidelega']]['nombre'] = $rowDelegaciones['nombre'];
	$arrayInforme[$rowDelegaciones['codidelega']]['cantidad'] = 0;
}

foreach ($arrayFamiSSS as $cuil => $fami) {
	if (array_key_exists ($fami['cuiltitular'] , $arrayCuilTitular)) {
		if (!array_key_exists ($cuil , $arrayFami)) {
			if (!array_key_exists ($cuil , $arrayFamiBaja)) {
				if(!in_array($fami['nrodoc'], $arrayFami)) {
					if(!in_array($fami['nrodoc'], $arrayFamiBaja)) {
						$delega = $arrayCuilTitular[$fami['cuiltitular']]['codidelega'];
						$arrayInforme[$delega]['cantidad'] += 1;;
					}
				} 
			} 
		} 
	}
}

$sqlMesPadron = "SELECT * FROM padronssscabecera c WHERE fechacierre is null ORDER BY c.id DESC LIMIT 1";
$resMesPadron = mysql_query ( $sqlMesPadron, $db );
$rowMesPadron = mysql_fetch_assoc ($resMesPadron);

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Alta de Titulares en SSS :.</title>

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
	$("#tablaInfo")
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
		<div align="center">
			<h2>Informe de Cantidad Alta Familiares S.S.S.</h2>
			<h2>Padrón SSS Periodo "<?php echo $rowMesPadron['mes'].'-'.$rowMesPadron['anio']?>" </h2>
		</div>
		
		<h3>Informe de Familiares</h3>
		<table style="text-align: center; width: 600px" id="tablaInfo" class="tablesorter">	
			<thead>
				<tr>
					<th>Codigo</th>
					<th>Delegacion</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$total = 0;
					foreach ($arrayInforme as $codidelega => $info) { 
						$total += $info['cantidad'];?>
						<tr>	
							<td><?php echo $codidelega ?></td>
							<td><?php echo $info['nombre']?></td>
							<td><?php echo $info['cantidad']?></td>
						</tr>
			 <?php } ?>
				<tr>
					<td colspan="2" style="background-color: aqua;"><b>TOTAL</b></td>
					<td style="background-color: aqua;"><b><?php echo $total ?></b></td>
				</tr>
			</tbody>
		</table>
		<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
	</div>
</body>
</html>