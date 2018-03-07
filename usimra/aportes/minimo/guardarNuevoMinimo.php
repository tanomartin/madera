<?php  $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."claves.php"); 

$cuit = $_POST['cuit'];
$fecha = date("Y-m-d H:i:s");
$usuario = $_SESSION['usuario'];

$sqlInsertMinimo = "INSERT INTO empresassinminimo VALUES('$cuit','$fecha','$usuario')";

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

	//echo $sqlInsertMinimo."<br>";
	$dbh->exec($sqlInsertMinimo);
	$dbh->commit();
	
	$pagina = "moduloMinimo.php";
	Header("Location: $pagina");
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>
