<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

//var_dump($_POST);
$codigo = $_POST['codigopresta'];
$sqlDelete = "DELETE FROM prestadorserviciodisca WHERE codigoprestador = $codigo";

$tieneServicio = 0;
$sqlInsertServicio = "INSERT INTO prestadorserviciodisca VALUES";
foreach ($_POST as $key => $servicio) {
	$pos = strpos($key, "servicio");	
	if ($pos !== false) {
		$tieneServicio = 1;
		$sqlInsertServicio .= "($codigo,$servicio),";
	}
}
$sqlInsertServicio = substr($sqlInsertServicio, 0, -1);
$sqlInsertServicio .= ";";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	//echo $sqlDelete."<br>";
	$dbh->exec($sqlDelete);
	if ($tieneServicio == 1) {
		//echo $sqlInsertServicio."<br>";
		$dbh->exec($sqlInsertServicio);
	}

	$dbh->commit();
	$pagina = "listadoPrestaServicios.php?codigo=$codigo";
	Header("Location: $pagina");
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>