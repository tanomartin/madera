<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$codigopresta = $_GET['codigo'];
$id = $_GET['id'];
$fechaInicio = fechaParaGuardar($_POST['fechaInicio']);

if ($_POST['fechaFin'] != "") {
	$fechaFin = fechaParaGuardar($_POST['fechaFin']);
	$fechaFin = "'$fechaFin'";
} else {
	$fechaFin = "NULL";
}
$monto = $_POST['monto'];
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$sqlArancelFin = "SELECT c.* FROM aranceles c  WHERE c.codigoprestador = $codigopresta and c.fechafin >= '$fechaInicio' and c.id != $id";
$resArancelFin = mysql_query($sqlArancelFin,$db);
$numArancelFin = mysql_num_rows($resArancelFin);
if ($numArancelFin > 0) {
	$pagina = "modificarArancel.php?codigo=$codigopresta&err=1&id=$id";
	Header("Location: $pagina"); 
	exit(0);
} else {
	$sqlUpdateArancel = "UPDATE aranceles SET fechainicio = '$fechaInicio', fechafin = $fechaFin, monto = '$monto', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE id = $id and codigoprestador = $codigopresta";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//print($sqlUpdateArancel."<br>");
		$dbh->exec($sqlUpdateArancel);
		$dbh->commit();
		
		$pagina = "arancelesPrestador.php?codigo=$codigopresta";
		Header("Location: $pagina"); 
	} catch (PDOException $e) {
		$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		Header($redire);
		exit(0);
	}
}
?>
