<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$codigopresta = $_GET['codigo'];
$fechaInicio = fechaParaGuardar($_POST['fechaInicio']);
if ($_POST['fechaFin'] != "") {
	$fechaFin = fechaParaGuardar($_POST['fechaFin']);
	$fechaFin = "'$fechaFin'";
} else {
	$fechaFin = "NULL";
}
$monto = $_POST['monto'];

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlArancelFin = "SELECT c.* FROM aranceles c  WHERE c.codigoprestador = $codigopresta and c.fechafin >= '$fechaInicio'";
$resArancelFin = mysql_query($sqlArancelFin,$db);
$numArancelFin = mysql_num_rows($resArancelFin);
if ($numArancelFin > 0) {
	$pagina = "nuevoArancel.php?codigo=$codigopresta&err=1&fi=".$_POST['fechaInicio']."&ff=".$_POST['fechaFin']."&mm=".$_POST['monto'];
	Header("Location: $pagina"); 
	exit(0);
} else {
	$sqlInsertArancel = "INSERT INTO aranceles VALUES(DEFAULT,'$codigopresta','$fechaInicio',$fechaFin,'$monto','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//print($sqlInsertArancel."<br>");
		$dbh->exec($sqlInsertArancel);
		
		$dbh->commit();
		$pagina = "arancelesPrestador.php?codigo=$codigopresta";
		Header("Location: $pagina"); 
	} catch (PDOException $e) {
		$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		//Header($redire);
		exit(0);
	}
}
?>
