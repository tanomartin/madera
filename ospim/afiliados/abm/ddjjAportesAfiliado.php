<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$cuilafiliado=$_GET['cuiAfi'];
$noexiste = 0;
$anoini = (date("Y")-10);
$anofin = date("Y");
$mesini = (int)date("m");
if($mesini == 1) {
	$mesfin	= 12;
	$anofin = (date("Y")-1);
} else {
	$mesfin = ((int)date("m")-1);
}

$sqlTituActi = "SELECT nroafiliado, apellidoynombre FROM titulares WHERE cuil = '$cuilafiliado'";
$resTituActi = mysql_query($sqlTituActi,$db);
if (mysql_num_rows($resTituActi)==0) {
	$sqlTituBaja = "SELECT nroafiliado, apellidoynombre FROM titularesdebaja WHERE cuil = '$cuilafiliado'";
	$resTituBaja = mysql_query($sqlTituBaja,$db);
	if (mysql_num_rows($resTituBaja)==0) {
		$noexiste = 1;
	}
	else {
		$estafiliado = "De Baja";
		$rowTituBaja = mysql_fetch_array($resTituBaja);
		$nroafiliado = $rowTituBaja['nroafiliado'];
		$nomafiliado = $rowTituBaja['apellidoynombre'];
	}
} else {
	$estafiliado = "Activo";
	$rowTituActi = mysql_fetch_array($resTituActi);
	$nroafiliado = $rowTituActi['nroafiliado'];
	$nomafiliado = $rowTituActi['apellidoynombre'];
}

(int)$indTituDDJJ = 0;
$sqlTituDDJJ = "SELECT anoddjj, mesddjj, cuit, remundeclarada FROM detddjjospim WHERE anoddjj >= '$anoini' AND cuil = '$cuilafiliado' ORDER BY anoddjj DESC, mesddjj DESC, cuit ASC";
$resTituDDJJ = mysql_query($sqlTituDDJJ,$db);
while($rowTituDDJJ = mysql_fetch_array($resTituDDJJ)) {
	$ddjj[$rowTituDDJJ['anoddjj']][$rowTituDDJJ['mesddjj']] = array('cuit' => $rowTituDDJJ['cuit'], 'remu' => $rowTituDDJJ['remundeclarada']);
	$perd[$indTituDDJJ] = array('ano' => $rowTituDDJJ['anoddjj'], 'mes' => $rowTituDDJJ['mesddjj']);
	$indTituDDJJ++;
}
//var_dump($ddjj);
//var_dump($perd);

(int)$indTituApor = 0;
$sqlTituApor = "SELECT anopago, mespago, cuit, importe FROM afiptransferencias WHERE anopago >= '$anoini' AND (concepto = '381' OR concepto = 'C14' OR concepto = 'O02' OR concepto = 'T14' OR concepto = 'T55') AND cuil = '$cuilafiliado' ORDER BY anopago DESC, mespago DESC, cuit ASC";
$resTituApor = mysql_query($sqlTituApor,$db);
while($rowTituApor = mysql_fetch_array($resTituApor)) {
	$apor[$rowTituApor['anopago']][$rowTituApor['mespago']] = array('cuit' => $rowTituApor['cuit'], 'impo' => $rowTituApor['importe']);
	$pera[$indTituApor] = array('ano' => $rowTituApor['anopago'], 'mes' => $rowTituApor['mespago']);
	$indTituApor++;
}
//var_dump($apor);
//var_dump($pera);

if(count($ddjj) != 0) {
	foreach($perd as $dj) {
		$cuitddjj = $ddjj[$dj[ano]][$dj[mes]]['cuit'];
		$remuddjj = $ddjj[$dj[ano]][$dj[mes]]['remu'];
		$cuitapor = $apor[$dj[ano]][$dj[mes]]['cuit'];
		$impoapor = $apor[$dj[ano]][$dj[mes]]['impo'];
		$listado[$dj[ano]][$dj[mes]] = array('cuitddjj' => $cuitddjj, 'remuddjj' => $remuddjj, 'cuitapor' => $cuitapor, 'impoapor' => $impoapor);
	}
}

if(count($apor) != 0) {
	foreach($pera as $ap) {
		$cuitddjj = $ddjj[$ap[ano]][$ap[mes]]['cuit'];
		$remuddjj = $ddjj[$ap[ano]][$ap[mes]]['remu'];
		$cuitapor = $apor[$ap[ano]][$ap[mes]]['cuit'];
		$impoapor = $apor[$ap[ano]][$ap[mes]]['impo'];
		$listado[$ap[ano]][$ap[mes]] = array('cuitddjj' => $cuitddjj, 'remuddjj' => $remuddjj, 'cuitapor' => $cuitapor, 'impoapor' => $impoapor);
	}
}
//var_dump($listado);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo4 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<title>.: DDJJ y Aportes :.</title>
<script type="text/javascript" src="/lib/jquery.js"></script>
<script type="text/javascript" src="/lib/jquery-ui.min.js"></script>
<script type="text/javascript" src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script language="javascript" type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{0:{sorter:false}, 2:{sorter:false}, 3:{sorter:false}, 4:{sorter:false}, 5:{sorter:false}, 6:{sorter:false}, 7:{sorter:false}}
		})
		.tablesorterPager({container: $("#paginador")}); 
	});
</script>
</head>
<body bgcolor="#CCCCCC" >
<div align="center"><h2>DDJJ / Aportes</h2></div>
<div><h3>C.U.I.L.: <?php echo $cuilafiliado;?></h3></div>
<div><h3>Afiliado: <?php if ($noexiste) { echo "NO EMPADRONADO"; } else { echo $nroafiliado." - ".$nomafiliado." - ".$estafiliado; }?></h3></div>
<?php
if(count($ddjj) == 0 && count($apor) == 0) {
?>
<div align="center"><h3>No existen DDJJ ni APORTES</h3></div>
<?php
} else {
?>
<div align="center">
<table id="listado" class="tablesorter" style="width:800px; font-size:14px; text-align:center">
	<thead>
		<tr>
			<th rowspan="2">Mes</th>
			<th rowspan="2">A&ntilde;o</th>
			<th colspan="2">DDJJ</th>
			<th colspan="2">Aporte</th>
		</tr>
		<tr>
			<th>C.U.I.T.</th>
			<th>Remuneracion</th>
			<th>C.U.I.T.</th>
			<th>Importe</th>
		</tr>
	</thead>
	<tbody>
<?php
$ano=$anofin;
$mes=$mesfin;
while($ano>=$anoini) {
	while($mes>=1) {
		if($listado[$ano][$mes]['cuitddjj']) {
			$lcuiddjj = $listado[$ano][$mes]['cuitddjj'];
		} else {
			$lcuiddjj = "-";
		}
		if($listado[$ano][$mes]['remuddjj']) {
			$lremddjj = $listado[$ano][$mes]['remuddjj'];
		} else {
			$lremddjj = "-";
		}
		if($listado[$ano][$mes]['cuitapor']) {
			$lcuiapor = $listado[$ano][$mes]['cuitapor'];
		} else {
			$lcuiapor = "-";
		}
		if($listado[$ano][$mes]['impoapor']) {
			$limpapor = $listado[$ano][$mes]['impoapor'];
		} else {
			$limpapor = "-";
		}
?>
		<tr align="center">
			<td><?php echo $mes ?></td>
			<td><?php echo $ano ?></td>
			<td><?php echo $lcuiddjj ?></td>
			<td><?php echo $lremddjj ?></td>
			<td><?php echo $lcuiapor ?></td>
			<td><?php echo $limpapor ?></td>
		</tr>
<?php
		$mes--;
		if($ano==$anoini) {
			if($mes<$mesini) {
				break;
			}
		}
	}
	$mes=12;
	$ano--;
	if($ano<$anoini) {
		break;
	}
}
?>
	</tbody>
</table>
</div>
<?php
}
?>
<table class="nover" align="center" width="245" border="0">
	<tr>
		<td width="239">
			<div id="paginador" class="pager">
				<form>
					<p align="center">
					<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
					<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
					<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
					<select name="select" class="pagesize">
						<option selected="selected" value="12">12 por pagina</option>
						<option value="24">24 por pagina</option>
						<option value="36">36 por pagina</option>
						<option value="60">60 por pagina</option>
						<option value="120">Todos</option>
						</select>
					</p>
					<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="right"/></p>
				</form>	
			</div>
		</td>
	</tr>
</table>
</body>
</html>