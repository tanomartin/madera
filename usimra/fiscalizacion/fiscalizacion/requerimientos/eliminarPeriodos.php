<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 

$cuit = $_POST['cuit'];
$fecha = $_POST['fecha'];
$nroreq = $_POST['nroreq'];
$datos = array_values($_POST);
if (isset($_POST['selecAll'])) {
	$inicio = 4;
} else {
	$inicio = 3;
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	for ($i=$inicio; $i<sizeof($datos)-1; $i++) {
		$periodo = explode ('-',$datos[$i]);
		$anofis = $periodo[0];
		$mesfis = $periodo[1];
		$sqlDeletePeriodo = "DELETE FROM detfiscalizusimra WHERE nrorequerimiento = $nroreq and anofiscalizacion = $anofis and mesfiscalizacion = $mesfis";
		//print($sqlDeletePeriodo."<br>");
		$dbh->exec($sqlDeletePeriodo);
	}
	$dbh->commit();
	$pagina = "detalleRequerimiento.php?nroreq=$nroreq&cuit=$cuit&fecha=$fecha";
	Header("Location: $pagina");
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}	

?>