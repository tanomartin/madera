<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
$fecha = $_GET['fecha'];
$motivo = $_POST['motivo'];
$nroreq = $_POST['nroreq'];

$fechaanulacion = date("Y-m-d H:i:s");
$usuarioanulacion = $_SESSION['usuario'];

print($motivo."<br>");
print($nroreq."<br>");

$sqlUpdateAnula = "UPDATE reqfiscalizospim 
					SET requerimientoanulado = 1, motivoanulacion = '$motivo', fechaanulacion = '$fechaanulacion', usuarioanulacion = '$usuarioanulacion'
					WHERE nrorequerimiento =  $nroreq";
print($sqlUpdateAnula."<br>");

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlUpdateAnula);
	$dbh->commit();
	$pagina = "listarRequerimientos.php?fecha=$fecha";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>

