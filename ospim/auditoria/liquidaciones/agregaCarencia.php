<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET)) {
	$idcomprobante = $_GET['idComprobante'];
	$identidadbeneficiario = $_GET['identidadBeneficiario'];
	$totalfacturado = $_GET['totalFacturado'];
	$totaldebito = $_GET['totalDebito'];
	$totalcredito = $_GET['totalCredito'];
	$efectorcarencia = $_GET['efectorCarencia'];
	$motivocarencia = $_GET['motivoCarencia'];
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlAddCarenciasBeneficiarios = "INSERT INTO facturascarenciasbeneficiarios(id,idFactura,identidadbeneficiario,totalfacturado,totaldebito,totalcredito,efectorcarencia,motivocarencia) VALUES(:id,:idFactura,:identidadbeneficiario,:totalfacturado,:totaldebito,:totalcredito,:efectorcarencia,:motivocarencia)";
		$resAddCarenciasBeneficiarios = $dbh->prepare($sqlAddCarenciasBeneficiarios);
		if($resAddCarenciasBeneficiarios->execute(array(':id' => 'DEFAULT', ':idFactura' => $idcomprobante, ':identidadbeneficiario' => $identidadbeneficiario, ':totalfacturado' => $totalfacturado, ':totaldebito' => $totaldebito, ':totalcredito' => $totalcredito, ':efectorcarencia' => $efectorcarencia, ':motivocarencia' => $motivocarencia)))
		$dbh->commit();
		echo json_encode(array('result'=> true));
	}
	catch (PDOException $e) {
		$dbh->rollback();
		echo json_encode(array('result'=> false));
	}
	return; 
}  
?>