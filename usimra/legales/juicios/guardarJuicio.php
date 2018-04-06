<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");

$cuit = $_POST['cuit'];
$nroorden = $_POST['nroorden'];
$sqlCabecera = $_POST['insertCabeceraJui'];
$listadoPeriodosSerializado = $_POST['insertPeriodosJui'];
$sqlUpdateAcu = $_POST['updateCabeceraAcu'];
$listadoPeriodosAcuSerializado = $_POST['deletePeriodosAcu'];

$sqlPeriodos = unserialize(urldecode($listadoPeriodosSerializado));

$sqlDelPer = "";
if ($listadoPeriodosAcuSerializado != "") {
	$sqlDelPer = unserialize(urldecode($listadoPeriodosAcuSerializado));
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	//print($sqlCabecera."<br>");
	$dbh->exec($sqlCabecera);
	if (!empty($sqlPeriodos)) {
		for($i=0; $i<sizeof($sqlPeriodos); $i++) {
			//print($sqlPeriodos[$i]."<br>");
			$dbh->exec($sqlPeriodos[$i]);
		}
	}
	if (!empty($sqlUpdateAcu)) {
		//print($sqlUpdateAcu."<br>");
		$dbh->exec($sqlUpdateAcu);
		if (!empty($sqlDelPer)) {
			for($i=0; $i<sizeof($sqlDelPer); $i++) {
				//print($sqlDelPer[$i]."<br>");
				$dbh->exec($sqlDelPer[$i]);
			}
		}
	}
	
	$dbh->commit();
	$pagina = "consultaJuicio.php?cuit=$cuit&nroorden=$nroorden";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}


?>