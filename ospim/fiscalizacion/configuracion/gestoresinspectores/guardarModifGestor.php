<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigo = $_GET['codigo'];
$datos = array_values($_POST);

$sqlModifGestor = "UPDATE gestoresdeacuerdos SET apeynombre = '$datos[0]' where codigo = $codigo";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$dbh->exec($sqlModifGestor);
	$dbh->commit();
	
	$pagina = "gestores.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>

