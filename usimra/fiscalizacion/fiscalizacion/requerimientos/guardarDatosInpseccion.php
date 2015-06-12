<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php"); 

$fechamodif = date("Y-m-d H:m:s");
$usuariomodif = $_SESSION['usuario'];

$datos = array_values($_POST);
$nroreq = $datos[0];
$inspector = $datos[1];
$fechaasig = fechaParaGuardar($datos[2]);
$dias = $datos[3];
$addoc = $datos[4];
$detalledoc = $datos[5];
$formaenvio = $datos[6];
$fecharecibo = fechaParaGuardar($datos[7]);
$fechadevolu = fechaParaGuardar($datos[8]);
$inspefec = $datos[9];
$fechainsp = fechaParaGuardar($datos[10]);
//var_dump($datos);

$sqlUpdateInspec = "UPDATE inspecfiscalizusimra set inspectorasignado = $inspector, fechaasignado = '$fechaasig', diasefectivizacion = $dias, adjuntadocumentos = $addoc, detalledocumentos = '$detalledoc', formaenviodocumentos = $formaenvio, fecharecibodocumentos = '$fecharecibo',  fechadevoluciondocumentos = '$fechadevolu', inspeccionefectuada = $inspefec, fechainspeccion = '$fechainsp', fechamodificacion = '$fechamodif', usuariomodificacion = '$usuariomodif' WHERE nrorequerimiento = $nroreq";
//print($sqlUpdateInspec);

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlUpdateInspec);
	$dbh->commit();
	$pagina = "listarInspecciones.php";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>