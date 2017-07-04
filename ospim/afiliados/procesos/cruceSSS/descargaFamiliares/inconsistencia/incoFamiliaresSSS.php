<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

$sqlFamiSSS = "SELECT DISTINCT cuilfamiliar, cuiltitular, nrodocumento, cuit, apellidoynombre, tipotitular, osopcion FROM padronsss p where parentesco != 0";
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

$arrayInforme = array();
foreach ($arrayFamiSSS as $cuil => $fami) {
	if (!array_key_exists ($cuil , $arrayFami)) {
		if (!array_key_exists ($cuil , $arrayFamiBaja)) {
			if(!in_array($fami['nrodoc'], $arrayFami)) {
				if(in_array($fami['nrodoc'], $arrayFamiBaja)) {
					$arrayInforme[$cuil] = $fami;
				}
			} else {
				$arrayInforme[$cuil] = $fami;
			}
		} 
	} 
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Busqueda de Inconsistencias Familiares en SSS :.</title>

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
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../menuDescInfoFamiSSS.php'" />
		<h2>Inconsistencias de Familiares desde la S.S.S.</h2>
			
		<h3>Informe de Familiares encontrados por D.N.I.</h3>
			<?php if (sizeof($arrayInforme) > 0) { ?>
			<table style="text-align: center; width: 900px" id="tablaInforme" class="tablesorter">	
				<thead>
					<tr>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>C.U.I.L. Titular</th>
						<th>C.U.I.T.</th>
						<th>Nro. Documento</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($arrayInforme as $cuil => $fami) { ?>
					<tr>	
						<td><?php echo $cuil ?></td>
						<td><?php echo $fami['nombre']?></td>
						<td><?php echo $fami['cuiltitular']?></td>
						<td><?php echo $fami['cuit']?></td>
						<td><?php echo $fami['nrodoc']?></td>
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
		<?php } else { ?>
		<h4>No hay Familiares para informar</h4>
		<?php } ?>

		<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
	</div>
</body>
</html>