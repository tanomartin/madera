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
$arrayCuit = array();
while ($rowTitu = mysql_fetch_assoc ($resTitu)) {
	$arrayTitu[$rowTitu['cuil']] = $rowTitu['nrodocumento'];
	$arrayCuit[$rowTitu['cuil']] = $rowTitu['cuitempresa'];
	$arrayTipo[$rowTitu['cuil']] = $rowTitu['descrip'];
}

$sqlTitu = "SELECT DISTINCT t.cuil, t.nrodocumento, t.nroafiliado, p.descrip FROM titularesdebaja t, tipotitular p WHERE t.situaciontitularidad = p.codtiptit";
$resTitu = mysql_query ( $sqlTitu, $db );
$arrayTituBaja = array();
while ($rowTitu = mysql_fetch_assoc ($resTitu)) {
	$arrayTituBaja[$rowTitu['cuil']] = $rowTitu['nrodocumento'];
	$arrayTituNroAfil[$rowTitu['cuil']] = $rowTitu['nroafiliado'];
	$arrayTipo[$rowTitu['cuil']] = $rowTitu['descrip'];
}

$arrayInforme = array();
$arrayTipoAceptados = array(0,2,4,5,8);
foreach ($arrayTituSSS as $cuil => $titu) {
	if (!array_key_exists ($cuil , $arrayTitu)) {
		if (!array_key_exists ($cuil , $arrayTituBaja)) {
			if(!in_array($titu['nrodoc'], $arrayTitu)) {
				if(!in_array($titu['nrodoc'], $arrayTituBaja)) {
					if ($titu['osopcion'] != 0) {
						$arrayInforme[$cuil] = array('titu' => $titu, 'motivo' => "Titular por Opcion no empadronado informado desde la S.S.S");
					} else {
						if (!in_array($titu['tipotitular'], $arrayTipoAceptados)) {
							$arrayInforme[$cuil] = array('titu' => $titu, 'motivo' => "Titular con tipo de titular no aceptado por la O.S. no empadronado informado desde la S.S.S");
						}
					}
				} else {
					$arrayInforme[$cuil] = array('titu' => $titu, 'motivo' => "Titular encontrado por D.N.I. con diferente C.U.I.L. informado desde la S.S.S");
				}
			} else {
				$arrayInforme[$cuil] = array('titu' => $titu, 'motivo' => "Titular encontrado por D.N.I. con diferente C.U.I.L. informado desde la S.S.S");
			}
		} 
	} else {
		if ($arrayCuit[$cuil] != $arrayTituSSS[$cuil]['cuit']) {
			$arrayInforme[$cuil] = array('titu' => $titu, 'motivo' => "Diferente C.U.I.T. informado desde la S.S.S.");
		} else {
			if ($arrayTitu[$cuil] != $arrayTituSSS[$cuil]['nrodoc']) {
				$arrayInforme[$cuil] = array('titu' => $titu, 'motivo' => "Diferente D.N.I. informado desde la S.S.S.");
			}
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Busqueda de Inconsitencias en Titulares en SSS :.</title>

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
	.tablesorterPager({container: $("#paginador")}); 
});


</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../menuDescInfoTituSSS.php'" />
		<h3>Informe de Inconsistencias de Titulares</h3>
		<table style="text-align: center; width: 1000px" id="tablaInforme" class="tablesorter">	
			<thead>
				<tr>
					<th>C.U.I.L.</th>
					<th>Apellido y Nombre</th>
					<th>C.U.I.T.</th>
					<th>Nro. Documento</th>
					<th class="filter-select" data-placeholder="Seleccion">Motivo</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($arrayInforme as $cuil => $titu) { ?>
					<tr>	
						<td><?php echo $cuil ?></td>
						<td><?php echo $titu['titu']['nombre']?></td>
						<td><?php echo $titu['titu']['cuit']?></td>
						<td><?php echo $titu['titu']['nrodoc']?></td>
						<td><?php echo $titu['motivo']?></td>
					</tr>
			<?php } ?>
			</tbody>
		</table>	
		<table class="nover">
			<tr>
				<td width="239">
					<div id="paginador" class="pager">
						<form>
							<p align="center">
								<img src="../../img/first.png" width="16" height="16" class="first"/> <img src="../../img/prev.png" width="16" height="16" class="prev"/>
								<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
								<img src="../../img/next.png" width="16" height="16" class="next"/> <img src="../../img/last.png" width="16" height="16" class="last"/>
								<select name="select" class="pagesize">
									<option selected="selected" value="50">50 por pagina</option>
									<option value="100">100 por pagina</option>
									<option value="200">200 por pagina</option>
									<option value="<?php echo sizeof($arrayInforme) ?>">Todos</option>
								</select>
							</p>
						</form>	
					</div>
				</td>
			</tr>
		</table>
		<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
	</div>
</body>
</html>