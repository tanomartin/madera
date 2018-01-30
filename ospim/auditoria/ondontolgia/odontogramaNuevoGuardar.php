<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
	
$nroafil = $_POST['nroafil'];
$nroorden = $_POST['nroorden'];
$fecha = fechaParaGuardar($_POST['fechaprestacion']);
$codigoprestador = $_POST['codigoprestador'];
$idpractica = $_POST['idPractica'];
$piezaArray = explode("-",$_POST['pieza']);
$idPieza = $piezaArray[0];
$idCara = $_POST['caras'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
	
$insertOdonto = "INSERT INTO odontograma VALUE(DEFAULT, $nroafil, $nroorden, $idpractica , '$fecha', $idPieza, $idCara, $codigoprestador, '$fecharegistro', '$usuarioregistro')";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($insertOdonto."<br>");
	$dbh->exec($insertOdonto);

	$dbh->commit();
	$pagina = "odontograma.php?nroafil=$nroafil&nroorden=$nroorden&tipo=A";
	Header("Location: $pagina");

}catch (PDOException $e) {
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>