<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

$arrayTipo = array();
$sqlTituSSS = "SELECT DISTINCT p.cuiltitular, p.nrodocumento, p.cuit, p.apellidoynombre, p.tipotitular, p.osopcion, t.descrip FROM padronsss p, tipotitular t where p.parentesco = 0 and p.tipotitular = t.codtiptit";
$resTituSSS = mysql_query ( $sqlTituSSS, $db );
$arrayTituSSS = array();
$arrayDNISSS = array();
while ($rowTituSSS = mysql_fetch_assoc ($resTituSSS)) {
	$arrayTituSSS[$rowTituSSS['cuiltitular']] = array('nrodoc' => $rowTituSSS['nrodocumento'], 'cuit' => $rowTituSSS['cuit'], 'nombre' => $rowTituSSS['apellidoynombre'], 'tipotitular' => $rowTituSSS['tipotitular'], 'osopcion' => $rowTituSSS['osopcion']);
	$arrayTipo[$rowTituSSS['cuiltitular']] = $rowTituSSS['descrip'];
}

$sqlTitu = "SELECT DISTINCT t.cuil, t.cuitempresa, t.nrodocumento, t.nroafiliado, p.descrip  FROM titulares t, tipotitular p WHERE t.situaciontitularidad = p.codtiptit";
$resTitu = mysql_query ( $sqlTitu, $db );
$arrayTitu = array();
while ($rowTitu = mysql_fetch_assoc ($resTitu)) {
	$arrayTitu[$rowTitu['cuil']] = $rowTitu['nrodocumento'];
	$arrayTipo[$rowTitu['cuil']] = $rowTitu['descrip'];
}

$sqlTitu = "SELECT DISTINCT t.cuil, t.nrodocumento, t.nroafiliado, p.descrip FROM titularesdebaja t, tipotitular p WHERE t.situaciontitularidad = p.codtiptit";
$resTitu = mysql_query ( $sqlTitu, $db );
$arrayTituBaja = array();
while ($rowTitu = mysql_fetch_assoc ($resTitu)) {
	$arrayTituBaja[$rowTitu['cuil']] = $rowTitu['nrodocumento'];
	$arrayTipo[$rowTitu['cuil']] = $rowTitu['descrip'];
}

$sqlEmpresasCuit = "SELECT e.cuit,j.codidelega FROM empresas e, jurisdiccion j WHERE e.cuit = j.cuit order by e.cuit, j.disgdinero;";
$resEmpresasCuit = mysql_query ( $sqlEmpresasCuit, $db );
$arrayCuit = array();
while ($rowEmpresasCuit = mysql_fetch_assoc ($resEmpresasCuit)) {
	$arrayCuit[$rowEmpresasCuit['cuit']] = $rowEmpresasCuit;
}

$sqlDelegaciones = "SELECT * FROM delegaciones WHERE codidelega not in (3500,4000,4001)";
$resDelegaciones = mysql_query ( $sqlDelegaciones, $db );
$arrayInforme = array();
while ($rowDelegaciones = mysql_fetch_assoc ($resDelegaciones)) {
	$arrayInforme[$rowDelegaciones['codidelega']]['nombre'] = $rowDelegaciones['nombre'];
	$arrayInforme[$rowDelegaciones['codidelega']]['cantidad'] = 0;
}

foreach ($arrayTituSSS as $cuil => $titu) {
	if (!array_key_exists ($cuil , $arrayTitu)) {
		if (!array_key_exists ($cuil , $arrayTituBaja)) {
			if(!in_array($titu['nrodoc'], $arrayTitu)) {
				if(!in_array($titu['nrodoc'], $arrayTituBaja)) {
					if (array_key_exists($titu['cuit'], $arrayCuit)) {
						$arrayInforme[$arrayCuit[$titu['cuit']]['codidelega']]['cantidad'] += 1; 
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Busqueda de Titulares en SSS :.</title>

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
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../menuDescInfoTituSSS.php'" />
		
		<div align="center">
			<h2>Men&uacute; Descarga Informacion de Titulares desde la S.S.S.</h2>
			<h2>Padrón SSS Periodo "<?php echo $rowMesPadron['mes'].'-'.$rowMesPadron['anio']?>" </h2>
		</div>
		
		<h2>Descarga Alta Titulares S.S.S.</h2>
		<h3>Informe de Titulares</h3>
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