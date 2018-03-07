<?php  $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."claves.php"); 

$cuit = $_GET['cuit'];
$fechaBaja = date("Y-m-d H:i:s");
$usuarioBaja = $_SESSION['usuario'];

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
$sqlMinimo = "SELECT * FROM empresassinminimo m WHERE nrcuit = $cuit";
$resMinimo = mysql_query($sqlMinimo, $dbaplicativo);
$rowMinimo = mysql_fetch_array($resMinimo);
$fecha = $rowMinimo['fecha'];
$usuario = $rowMinimo['usuario'];

$sqlInsertHistorico = "INSERT INTO empresassinminimohistorico VALUES(DEFAULT,'$cuit','$fecha','$usuario','$fechaBaja','$usuarioBaja')";
$sqlEliminarMinimo = "DELETE FROM empresassinminimo WHERE nrcuit = '$cuit'";

try {
	$maquina = $_SERVER ['SERVER_NAME'];
	$hostaplicativo = $hostUsimra;
	if(strcmp("localhost",$maquina)==0) {
		$hostaplicativo = "localhost";
	}
	
	$hostname = $hostaplicativo;
	$dbname = $baseUsimraNewAplicativo;
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$usuarioUsimra,$claveUsimra);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $sqlInsertHistorico."<br>";
	$dbh->exec($sqlInsertHistorico);
	
	//echo $sqlEliminarMinimo."<br>";
	$dbh->exec($sqlEliminarMinimo);
	
	$dbh->commit();
	
	$pagina = "moduloMinimo.php";
	Header("Location: $pagina");
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>