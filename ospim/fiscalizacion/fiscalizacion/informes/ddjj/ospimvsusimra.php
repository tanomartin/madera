<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

if (isset($_POST['periodo'])) {	
	$arrayPeriodo = explode("-",$_POST['periodo']);
	
	$arrayPeriodo[0] = 2016;
	$arrayPeriodo[1] = 9;
	
	$sqlOSPIM = "SELECT count(DISTINCT cuit) as cantidad, sum(totalremundeclarada)+sum(totalremundecreto) as totremun 
				 FROM cabddjjospim c WHERE anoddjj = ".$arrayPeriodo[0]." and mesddjj = ".$arrayPeriodo[1];
	$resOSPIM = mysql_query($sqlOSPIM,$db);
	$rowOSPIM = mysql_fetch_assoc($resOSPIM);
	
	$sqlUSIMRA = "SELECT cuit, remuneraciones
				 FROM cabddjjusimra c WHERE anoddjj = ".$arrayPeriodo[0]." and mesddjj = ".$arrayPeriodo[1];
	$resUSIMRA = mysql_query($sqlUSIMRA,$db);
	$arrayCUIT = array();
	$arrayResultValidas['cantidad'] = 0;
	$arrayResultValidas['totremun'] = 0;
	while($rowUSIMRA = mysql_fetch_assoc($resUSIMRA)) {
		if (!isset($arrayCUIT[$rowUSIMRA['cuit']])) {
			$arrayCUIT[$rowUSIMRA['cuit']] = $rowUSIMRA['cuit'];
			$arrayResultValidas['cantidad']++;
		}
		$arrayResultValidas['totremun'] += $rowUSIMRA['remuneraciones'];
	}
	
	$sqlUSIMRANoValidas = "SELECT nrcuit, remune FROM ddjjusimra c 
							WHERE perano = ".$arrayPeriodo[0]." and 
								  permes = ".$arrayPeriodo[1]." and 
								  nrcuil = '99999999999' order by id DESC";
	$resUSIMRANoValidas = mysql_query($sqlUSIMRANoValidas,$db);
	$arrayFiltro = array();
	while($rowUSIMRANoValidas = mysql_fetch_assoc($resUSIMRANoValidas)) {
		$arrayFiltro[$rowUSIMRANoValidas['nrcuit']] = $rowUSIMRANoValidas['remune'];
	}
	
	$arrayResultNoValidas['cantidad'] = 0;
	$arrayResultNoValidas['totremun'] = 0;
	foreach ($arrayFiltro as $cuit => $remun) {
		if (!array_key_exists($cuit,$arrayCUIT)) {
			$arrayResultNoValidas['cantidad']++;
			$arrayResultNoValidas['totremun'] += $remun;
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: DDJJ OSPIM-USIMRA :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	if (formulario.periodo.value == 0) {
		alert("Debe seleccionar un Periodo");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Informe... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>
<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloInformes.php'" class="nover"/></p>
		<h3>Cantidad de DDJJ OSPIM y USIMRA</h3>
		<form id="consultaOSUS" name="consultaOSUS" method="post" action="ospimvsusimra.php" onsubmit="return validar(this)" class="nover">
			<b>Periodo: </b>
			<select name="periodo" id="periodo" >
				<option value="0">Selecciones Periodo</option>
		<?php $mesmenos = date('Y-m-d');
			  for ($i = 1; $i <= 12; $i++) {
			  	  $mesmenos = strtotime($mesmenos);
	 			  $mesmenos = date("Y-m", strtotime("-1 month", $mesmenos));?>
	 			  <option value="<?php echo $mesmenos?>"><?php echo $mesmenos?></option>
	 	<?php } ?>
			</select>
			<p><input type="submit" value="Buscar" class="nover"/></p>
		</form>
  <?php if (isset($_POST['periodo'])) { ?>
  			<h3>Resultado de busqueda periodo '<?php echo $_POST['periodo'] ?>'</h3>
			<table border="1" style="text-align: center; width: 800px">
				<thead>
					<tr>
						<th rowspan="2">TIPO</th>
						<th colspan="2">OSPIM</th>
						<th colspan="2">USIMRA</th>
					</tr>
					<tr>
						<th>Cantidad</th>
						<th>Tot. Remun.</th>
						<th>Cantidad</th>
						<th>Tot. Remun.</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><b>Validas</b></td>
						<td><?php echo $rowOSPIM['cantidad'] ?></td>
						<td><?php echo number_format($rowOSPIM['totremun'],2,',','.') ?></td>
						<td><?php echo $arrayResultValidas['cantidad'] ?></td>
						<td><?php echo number_format($arrayResultValidas['totremun'],2,',','.') ?></td>
					</tr>
					<tr>
						<td><b>No Validadas</b></td>
						<td>0</td>
						<td><?php echo number_format(0,2,',','.') ?></td>
						<td><?php echo $arrayResultNoValidas['cantidad'] ?></td>
						<td><?php echo number_format($arrayResultNoValidas['totremun'],2,',','.') ?></td>
					</tr>
					<tr>
						<td><b>Total</b></td>
						<td><?php echo $rowOSPIM['cantidad'] ?></td>
						<td><?php echo number_format($rowOSPIM['totremun'],2,',','.') ?></td>
						<td><?php echo $arrayResultValidas['cantidad']+$arrayResultNoValidas['cantidad'] ?></td>
						<td><?php echo number_format($arrayResultValidas['totremun']+$arrayResultNoValidas['totremun'],2,',','.') ?></td>
					</tr>
				</tbody>
			</table>
			<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
  <?php } ?>
	</div>
</body>
</html>
