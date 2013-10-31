<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$hostname = 'ospim.com.ar';
$usuario = 'uv0472';
$clave = 'trozo299tabea';
$db =  mysql_connect($hostname, $usuario, $clave);
if (!$db) {
    die('No pudo conectarse: ' . mysql_error());
}
$dbname = 'uv0472_newaplicativo';
mysql_select_db($dbname);

$sqlEmpresas = "select * from empresa where nrcuit = '21212121212'";
print($sqlEmpresas."<br>");
$resEmpresas = mysql_query($sqlEmpresas,$db); 
$canEmpresas = mysql_num_rows($resEmpresas); 
print($canEmpresas."<br>");
if ($canEmpresas > 0) {
	$rowEmpresas = mysql_fetch_assoc($resEmpresas);
	var_dump($rowEmpresas);
} else {
	print('NO HAY EMPRESAS A IMPORTAR<br>');
}

$sqlEmpleados = "select * from empleados where bajada = 0";
print($sqlEmpleados."<br>");
$resEmpleados = mysql_query($sqlEmpleados,$db); 
$canEmpleados = mysql_num_rows($resEmpleados); 
if ($canEmpleados > 0) {
	$rowEmpleados = mysql_fetch_assoc($resEmpleados);
	var_dump($rowEmpleados);
} else {
	print('NO HAY EMPLEADOS A IMPORTAR<br>');
}

$sqlFamiliar = "select * from empleados where bajada = 0";
print($sqlFamiliar."<br>");
$resFamiliar = mysql_query($sqlFamiliar,$db); 
$canFamiliar = mysql_num_rows($resFamiliar); 
if ($canFamiliar > 0) {
	$rowFamiliar = mysql_fetch_assoc($resFamiliar);
	var_dump($rowFamiliar);
} else {
	print('NO HAY FAMILIARES A IMPORTAR<br>');
}


?>