<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET)) {
	//var_dump($_GET);
	$idcarencia = $_GET['idCarencia'];
	$idcomprobante = $_GET['idFactura'];
		try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlDeleteCarenciasBeneficiarios = "DELETE FROM facturascarenciasbeneficiarios WHERE id = :id";
		$resDeleteCarenciasBeneficiarios = $dbh->prepare($sqlDeleteCarenciasBeneficiarios);
		if($resDeleteCarenciasBeneficiarios->execute(array(':id' => $idcarencia)))
		$dbh->commit();
		$pagina = "continuarLiquidacion.php?idfactura=$idcomprobante";
		header("Location: $pagina");
	}
	catch (PDOException $e) {
		$dbh->rollback();
		$error = $e->getMessage();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header($redire);
		exit(0);
	}
}
?>