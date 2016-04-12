<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$fechaExceptuar = explode("-",$_POST['fecha']);
$ano = $fechaExceptuar[2];
$mes = $fechaExceptuar[1];
$dia = $fechaExceptuar[0];
$convenio = $_POST['selectConvenio'];
$motivo = $_POST['motivo'];
$fechaModif = date("Y-m-d H:i:s");
$usuarioModif = $_SESSION['usuario'];

$sqlUpdateDia = "UPDATE diasbanco SET exceptuado = 1, observacion = '$motivo', fechamodificacion = '$fechaModif', usuariomodificacion = '$usuarioModif' WHERE nroconvenio = $convenio AND ano = $ano AND mes = $mes AND dia = $dia AND procesado = 0";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	//print($sqlUpdateDia);
	$dbh->exec($sqlUpdateDia);
	$dbh->commit();
	$pagina = "../procesamientoArchivos.php";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}	

?>