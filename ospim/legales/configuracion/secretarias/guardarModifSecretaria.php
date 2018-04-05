<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigosecre = $_GET['codsecre'];
$codjuz = $_GET['codjuz'];
$datos = array_values($_POST);
$denomi = strtoupper($datos[0]);

$sqlModifSecretaria = "UPDATE secretarias SET denominacion = '$denomi' where codigojuzgado = $codjuz and codigosecretaria = $codigosecre";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$dbh->exec($sqlModifSecretaria);
	$dbh->commit();
	
	$pagina = "secretarias.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>

