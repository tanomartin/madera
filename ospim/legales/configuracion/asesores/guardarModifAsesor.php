<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigo = $_GET['codigo'];
$datos = array_values($_POST);
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
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>

