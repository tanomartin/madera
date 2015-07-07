<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigo = $_GET['codigo'];
$datos = array_values($_POST);

if (sizeof($datos) == 1) {
	$pagina = "nuevoInspector.php?error=1&nombre=$datos[0]";
	Header("Location: $pagina"); 
}

$apeynombre = $datos[0];

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	for ( $i = 1 ; $i < sizeof($datos) ; $i ++) {
		$delega = $datos[$i];
		$sqlInsertInspector = "INSERT INTO inspectores VALUE($codigo,'$apeynombre',$delega)";
		$dbh->exec($sqlInsertInspector);
		//echo $sqlInsertInspector."<br>";
	}
	$dbh->commit();
	
	$pagina = "inspectores.php";
	Header("Location: $pagina");
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>

