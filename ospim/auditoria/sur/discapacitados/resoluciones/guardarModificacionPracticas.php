<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$idcabecera = $_GET['id'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$sqlDelete = "DELETE FROM practicasvaloresresolucion WHERE idresolucion = $idcabecera";
$sqlInsert = "INSERT INTO practicasvaloresresolucion VALUES";
$codpractica = "";
$importe = "";
foreach ($_POST as $key => $dato) {
	$pos = strpos($key, "id");
	if ($pos !== false) {
		$codpractica = $dato;
	} 
	$pos = strpos($key, "imp");
	if ($pos !== false) {
		$importe = $dato;
		if ($importe != "") { 
			$sqlInsert .= "($codpractica,$idcabecera,'$importe','0,00','0,00','0,00','0,00','0,00','$fecharegistro','$usuarioregistro'),";		
		}
	}
}
$sqlInsert = substr($sqlInsert, 0, -1);
$sqlInsert .= ";";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $sqlDelete."<br>";
	$dbh->exec($sqlDelete);
	
	//echo $sqlInsert."<br>";
	$dbh->exec($sqlInsert);

	$dbh->commit();
	$pagina = "detalleResolucion.php?id=$idcabecera";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
} ?>
