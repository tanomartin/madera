<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."claves.php"); 

$maquina = $_SERVER ['SERVER_NAME'];
$hostaplicativo = $hostUsimra;
if(strcmp("localhost",$maquina)==0) {
	$hostaplicativo = "localhost";
}
$usuarioaplicativo = $usuarioUsimra;
$claveaplicativo = $claveUsimra;
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
	die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = $baseUsimraNewAplicativo;
mysql_select_db($dbnameaplicativo);

$cuit = $_POST['cuit'];
$sqlEmpresa = "SELECT * FROM empresa WHERE nrcuit = '$cuit'";
$resEmpresa = mysql_query($sqlEmpresa, $dbaplicativo);
$canEmpresa = mysql_num_rows($resEmpresa);

if ($canEmpresa == 0) {
	echo 1;
} else {
	$sqlEmpresaMinimo = "SELECT * FROM empresassinminimo WHERE nrcuit = '$cuit'";
	$resEmpresaMinimo = mysql_query($sqlEmpresaMinimo, $dbaplicativo);
	$canEmpresaMinimo = mysql_num_rows($resEmpresaMinimo);
	if ($canEmpresaMinimo != 0) {
		echo 2;
	} else {
		$rowEmpresa = mysql_fetch_array($resEmpresa);
		echo ($rowEmpresa['nombre']);
	}
}
?>
