<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$id = $_POST['idpres'];
$i=0;
$arrayDelete = array();
foreach ($_POST as $key => $cuil) {
	if (strpos($key, 'bene') !== false) {
		$i++;
		$sqlDelete = "DELETE FROM diabetespresentaciondetalle WHERE idpresentacion = $id and cuil = $cuil";
		$arrayDelete[$i] = $sqlDelete;
	}
}
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	foreach ($arrayDelete as $sqlDel) {
		//echo $sqlDel."<br><br>";
		$dbh->exec($sqlDel);
	}
	
	$dbh->commit();
	$redire = "detallePresentacion.php?id=$id";
	Header("Location: $redire");

} catch (PDOException $e) {
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>