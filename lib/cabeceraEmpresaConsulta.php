<?php
$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db);
$cant = mysql_num_rows($result);
if ($cant == 0) {
	$sql = "select * from empresasdebaja where cuit = $cuit";
	$result = mysql_query($sql,$db);
	$row = mysql_fetch_array($result); 
	$fechaBaja = $row['fechabaja'];
	$tipo = "baja";
} else { 
	$row = mysql_fetch_array($result); 
	$tipo = "activa";
}

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query($sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query($sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);
?>