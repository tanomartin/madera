<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 

$codigo = $_GET['codigo'];
$datos = array_values($_POST);
$denomi = strtoupper($datos[0]);

$sqlModifJuzgado = "UPDATE juzgados SET denominacion = '$denomi', fueros = '$datos[1]' where codigojuzgado = $codigo";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$dbh->exec($sqlModifJuzgado);
	$dbh->commit();
	
	$pagina = "juzgados.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>

