<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");

$fecha = $_POST['fechareq'];
$nroreq = $_POST['nroreq'];
$fechaInsp = fechaParaGuardar($_POST['fechainsp']);
$isnpec = $_POST['inpector'];

$fechamodif = date("Y-m-d H:m:s");
$usuariomodif = $_SESSION['usuario'];

$sqlUpdateReq = "UPDATE reqfiscalizusimra
					SET procesoasignado = 2, fechamodificacion = '$fechamodif', usuariomodificacion= '$usuariomodif'
					WHERE nrorequerimiento =  $nroreq";
//print($sqlUpdateReq."<br>");
$sqlInsertInsp = "INSERT INTO inspecfiscalizusimra
VALUE('$nroreq','$isnpec','$fechaInsp',DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,'$fechamodif','$usuariomodif',DEFAULT,DEFAULT)";
//print($sqlInsertInsp."<br>");

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlInsertInsp);
	$dbh->exec($sqlUpdateReq);
	$dbh->commit();
	$pagina = "listarRequerimientos.php?fecha=$fecha";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>

