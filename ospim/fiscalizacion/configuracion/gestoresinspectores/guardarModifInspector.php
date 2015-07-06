<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigo = $_GET['codigo'];
$datos = array_values($_POST);

if (sizeof($datos) == 2) {
	$pagina = "modificarInspector.php?error=1&codigo=$codigo";
	Header("Location: $pagina"); 
}

$apeynombre = $datos[0];

$sqlDeleteInpsector = "DELETE FROM inspectores where codigo = $codigo";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	//echo $sqlDeleteInpsector; echo "<br>";
	$dbh->exec($sqlDeleteInpsector);

	for ( $i = 1 ; $i < sizeof($datos) ; $i ++) {
		$delega = $datos[$i];
		$sqlInsertInspector = "INSERT INTO inspectores VALUE($codigo,'$apeynombre',$delega)";
		$dbh->exec($sqlInsertInspector);
		//echo $sqlInsertInspector;echo "<br>";
	}
	$dbh->commit();
	
	$pagina = "inspectores.php";
	Header("Location: $pagina");
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>

