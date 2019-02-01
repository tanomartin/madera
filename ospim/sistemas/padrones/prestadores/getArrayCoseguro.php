<?php 
$arrayCoseguro = array();
$sqlDisca = "SELECT nroafiliado, nroorden FROM discapacitados";
$resDisca = mysql_query($sqlDisca,$db);
while($rowDisca = mysql_fetch_assoc($resDisca)) {
	$index = $rowDisca['nroafiliado']."-".$rowDisca['nroorden'];
	$arrayCoseguro[$index] = $index;
}

$sqlHIV = "SELECT nroafiliado, nroorden FROM hivbeneficiarios";
$resHIV = mysql_query($sqlHIV,$db);
while($rowHIV = mysql_fetch_assoc($resHIV)) {
	$index = $rowHIV['nroafiliado']."-".$rowHIV['nroorden'];
	$arrayCoseguro[$index] = $index;
}

$sqlONCO = "SELECT nroafiliado, nroorden FROM oncologiabeneficiarios";
$resONCO = mysql_query($sqlONCO,$db);
while($rowONCO = mysql_fetch_assoc($resONCO)) {
	$index = $rowONCO['nroafiliado']."-".$rowONCO['nroorden'];
	$arrayCoseguro[$index] = $index;
}

$fechaLimitePMI = date('Y-m-d',strtotime('-1 month',strtotime (date('Y-m-d'))));
$sqlPMI = "SELECT nroafiliado, nroorden FROM pmibeneficiarios p 
			WHERE (p.fechanacimiento != '0000-00-00' and p.fechanacimiento >= '$fechaLimitePMI')
				  or (p.fechanacimiento = '0000-00-00' and p.fpp >= '$fechaLimitePMI')";
$resPMI = mysql_query($sqlPMI,$db);
while($rowPMI = mysql_fetch_assoc($resPMI)) {
	$index = $rowPMI['nroafiliado']."-".$rowPMI['nroorden'];
	$arrayCoseguro[$index] = $index;
}
?>