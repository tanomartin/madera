<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$cuilafiliado=$_GET['cuiAfi'];
$noexiste = 0;
$anoini = (date("Y")-1);
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

$sqlTituDDJJ = "SELECT anoddjj, mesddjj, cuit, remundeclarada FROM detddjjospim WHERE cuil = '$cuilafiliado' AND anoddjj >= '$anoini'";
$resTituDDJJ = mysql_query($sqlTituDDJJ,$db);
$i=0;
$ddjj = array();
while($rowTituDDJJ = mysql_fetch_array($resTituDDJJ)) {
	$indTituDDJJ = $rowTituDDJJ['anoddjj'].$rowTituDDJJ['mesddjj'];
	$ddjj[$indTituDDJJ][$i] = array('cuit' => $rowTituDDJJ['cuit'], 'remu' => $rowTituDDJJ['remundeclarada'],'ano' => $rowTituDDJJ['anoddjj'], 'mes' => $rowTituDDJJ['mesddjj']);
	$i++;
}
//echo("DDJJ <br>");
//var_dump($ddjj);
//echo("<br><br>");

$i=0;
$sqlTituApor = "SELECT anopago, mespago, cuit, importe FROM afiptransferencias WHERE cuil = '$cuilafiliado' AND anopago >= '$anoini' AND (concepto = '381' OR concepto = 'C14' OR concepto = 'O02' OR concepto = 'T14' OR concepto = 'T55') ORDER BY anopago DESC, mespago DESC, cuit ASC";
$resTituApor = mysql_query($sqlTituApor,$db);
$apor = array();
while($rowTituApor = mysql_fetch_array($resTituApor)) {
	$indTituApor = $rowTituApor['anopago'].$rowTituApor['mespago'];
	$apor[$indTituApor][$i] = array('cuit' => $rowTituApor['cuit'], 'impo' => $rowTituApor['importe'],'ano' => $rowTituApor['anopago'], 'mes' => $rowTituApor['mespago']);
	$i++;
}
//echo("APORTES <br>");
//var_dump($apor);
//echo("<br><br>");

$i=0;
$sqlTituDese = "SELECT anodesempleo, mesdesempleo, fechainformesss, clave FROM desempleosss WHERE cuiltitular = '$cuilafiliado' AND parentesco = 0 AND anodesempleo >= '$anoini' ORDER BY anodesempleo DESC, mesdesempleo DESC";
$resTituDese = mysql_query($sqlTituDese,$db);
$dese = array();
while($rowTituDese = mysql_fetch_array($resTituDese)) {
	$indTituDese = $rowTituDese['anodesempleo'].$rowTituDese['mesdesempleo'];
	$dese[$indTituDese][$i] = array('fech' => $rowTituDese['fechainformesss'], 'clav' => $rowTituDese['clave'],'ano' => $rowTituDese['anodesempleo'], 'mes' => $rowTituDese['mesdesempleo']);
	$i++;
}
//echo("DESEMPLEO <br>");
//var_dump($dese);
//echo("<br><br>");
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
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<title>.: DDJJ y Aportes :.</title>
<script type="text/javascript" src="/madera/lib/jquery.js"></script>
<script type="text/javascript" src="/madera/lib/jquery-ui.min.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script language="javascript" type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{0:{sorter:false}, 2:{sorter:false}, 3:{sorter:false}, 4:{sorter:false}, 5:{sorter:false}, 6:{sorter:false}, 7:{sorter:false}, 8:{sorter:false}, 9:{sorter:false}, 10:{sorter:false}}
		})
	});
</script>
</head>
<body bgcolor="#CCCCCC" >
<div align="center"><h2>DDJJ / Aportes</h2></div>
<div><h3>C.U.I.L.: <?php echo $cuilafiliado;?></h3></div>
<div><h3>Afiliado: <?php if ($noexiste) { echo "NO EMPADRONADO"; } else { echo $nroafiliado." - ".$nomafiliado." - ".$estafiliado; }?></h3></div>
<?php
if(count($ddjj) == 0 && count($apor) == 0 && count($dese) == 0) {
?>
<div align="center"><h3>No existen DDJJ ni APORTES en los Últimos 12 Meses</h3></div>
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
			<th colspan="2">Subsidio Desempleo</th>
		</tr>
		<tr>
			<th>C.U.I.T.</th>
			<th>Remuneracion</th>
			<th>C.U.I.T.</th>
			<th>Importe</th>
			<th>Informado</th>
			<th>Clave</th>
		</tr>
	</thead>
	<tbody>
<?php
$ano=$anofin;
$mes=$mesfin;
while($ano>=$anoini) {
	while($mes>=1) {
		$lcuiddjj = '';
		$lremddjj = '';
		
		$indice = $ano.$mes;
		if (isset($ddjj[$indice])) {
			foreach($ddjj[$indice] as $dj) {
				if($dj['ano'] == $ano && $dj['mes'] == $mes) {
					$lcuiddjj .= (string)$dj['cuit']."<br>";
					$lremddjj .= (string)$dj['remu']."<br>";
				}
			} 
		} else {
			$lcuiddjj = '-';
			$lremddjj = '-';
		}
		
		$lcuiapor = '';
		$limpapor = '';
	
		if (isset($apor[$indice])) {
			foreach($apor[$indice] as $ap) {
				if($ap['ano'] == $ano && $ap['mes'] == $mes) {
					$lcuiapor .= (string)$ap['cuit']."<br>";
					$limpapor .= (string)$ap['impo']."<br>";
				}
			}
		} else {
			$lcuiapor = '-';
			$limpapor = '-';
		}
		
		$lfecdese = '';
		$lcladese = '';
		
		if (isset($dese[$indice])) {
			foreach($dese[$indice] as $de) {
				if($de['ano'] == $ano && $de['mes'] == $mes) {
					$lfecdese .= (string)invertirFecha($de['fech'])."<br>";
					$lcladese .= (string)$de['clav']."<br>";
				}
			}
		} else {
			$lfecdese = '-';
			$lcladese = '-';
		}
?>
		<tr align="center">
			<td><?php echo $mes ?></td>
			<td><?php echo $ano ?></td>
			<td><?php echo $lcuiddjj ?></td>
			<td><?php echo $lremddjj ?></td>
			<td><?php echo $lcuiapor ?></td>
			<td><?php echo $limpapor ?></td>
			<td><?php echo $lfecdese ?></td>
			<td><?php echo $lcladese ?></td>
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
<p><input class="nover" type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="right"/></p>
</div>
<?php
}
?>
</body>
</html>
