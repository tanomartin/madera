<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php"); 

$datos = array_values($_POST);

$cuit = $datos[0];
$fecha = $datos[1];
$nroreq = $datos[2];

for ($i=3; $i<sizeof($datos)-1; $i++) {
	$periodo = explode ('-',$datos[$i]);
	$anofis = $periodo[0];
	$mesfis = $periodo[1];
	$sqlDeletePeriodo = "DELETE FROM detfiscalizusimra WHERE nrorequerimiento = $nroreq and anofiscalizacion = $anofis and mesfiscalizacion = $mesfis";
	//print($sqlDeletePeriodo."<br>");
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sqlDeletePeriodo);
		$dbh->commit();
		$pagina = "detalleRequerimiento.php?nroreq=$nroreq&cuit=$cuit&fecha=$fecha";
		Header("Location: $pagina"); 
	}catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}	
}

?>