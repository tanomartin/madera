<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$id = $_POST['id'];
$fechamodif = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];
$fecha = fechaParaGuardar($_POST['fecha']);
$motivo = strtoupper(trim($_POST['motivo']));
$archivo = $_POST['archivo'];
$cancelarPresentacion = "UPDATE diabetespresentacion 
							SET fechacancelacion = '$fecha', motivocancelacion = '$motivo',
							    fechamodificacion = '$fechamodif', usuariomodificacion = '$usuariomodif'
							WHERE id = $id";

try {
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina) == 0) {
		$archivoCancalada = "archivos/canceladas/$archivo";	
		$archivo = "archivos/$archivo";
	} else {
		$archivoCancalada="/home/sistemas/Documentos/Diabetes/cancleadas/$archivo";
		$archivo = "/home/sistemas/Documentos/Diabetes/cancleadas/$archivo";
	}
	rename($archivo, $archivoCancalada);
} catch (Exception $e) {
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit -1;
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $cancelarPresentacion."<br><br>";
	$dbh->exec($cancelarPresentacion);

	$dbh->commit();
	$redire = "moduloPresSSS.php";
	Header("Location: $redire");
	
} catch (PDOException $e) {
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>