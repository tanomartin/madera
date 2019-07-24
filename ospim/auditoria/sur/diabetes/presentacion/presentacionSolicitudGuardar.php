<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$id = $_POST['id'];
$fechamodif = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];
$fecha = fechaParaGuardar($_POST['fecha']);
$solicitud = strtoupper(trim($_POST['solicitud']));
$cantidad =  $_POST['cantidad'];
$periodo = $_POST['periodo'];
$obs = strtoupper(trim($_POST['obs']));

if ($_FILES['nota']['tmp_name'] != "") {
	$archivoNota = $_FILES['nota']['tmp_name'];
	try {
		$maquina = $_SERVER['SERVER_NAME'];
		if(strcmp("localhost",$maquina) == 0)
			$archivodestino="archivos/DIAB-$periodo-$solicitud.pdf";
		else
			$archivodestino="/home/sistemas/Documentos/Diabetes/DIAB-$periodo-$solicitud.pdf";
		copy($archivoNota, $archivodestino);
	} catch (Exception $e) {
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		Header($redire);
		exit -1;
	}
}

$presentacionSSS = "UPDATE diabetespresentacion 
						SET fechasolicitud = '$fecha', nrosolicitud = '$solicitud',  observacion = '$obs',
							cantbenesolicitados = $cantidad, pathSolicitud = '$archivodestino',
							fechamodificacion = '$fechamodif', usuariomodificacion = '$usuariomodif'
						WHERE id = $id";
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $presentacionSSS."<br><br>";
	$dbh->exec($presentacionSSS);

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