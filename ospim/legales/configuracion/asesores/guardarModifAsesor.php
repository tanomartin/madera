<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 

$codigo = $_GET['codigo'];
$datos = array_values($_POST);

if (sizeof($datos) == 2) {
	$pagina = "modificarAsesor.php?error=1&codigo=$codigo";
	Header("Location: $pagina"); 
}

$apeynombre = $datos[0];
$sqlDeleteAsesor = "DELETE FROM asesoreslegales where codigo = $codigo";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $sqlDeleteAsesor; echo "<br>";
	$dbh->exec($sqlDeleteAsesor);

	for ( $i = 1 ; $i < sizeof($datos); $i ++) {
		$delega = $datos[$i];
		$sqlInsertInspector = "INSERT INTO asesoreslegales VALUE($codigo,'$apeynombre',$delega)";
		$dbh->exec($sqlInsertInspector);
		//echo $sqlInsertInspector;echo "<br>";
	}
	$dbh->commit();
	
	$pagina = "asesores.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>

