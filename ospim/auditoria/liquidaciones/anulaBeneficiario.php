<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$idfactura = 0;
$idfacturabeneficiarios = 0;
if(isset($_GET)) {
	//var_dump($_GET);
	$idfactura = $_GET['idFactura'];
	$idfacturabeneficiario = $_GET['idFacturabeneficiario'];
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlDeleteFacturasBeneficiarios = "DELETE FROM facturasbeneficiarios WHERE id = :id";
		$resDeleteFacturasBeneficiarios = $dbh->prepare($sqlDeleteFacturasBeneficiarios);
		if($resDeleteFacturasBeneficiarios->execute(array(':id' => $idfacturabeneficiario)))
		$dbh->commit();
		if(isset($_GET['origenAnulacion'])) {
			if(strcmp($_GET['origenAnulacion'], 'M')==0) {
				$pagina = "continuarLiquidacionMedicamento.php?idfactura=$idfactura";
			}
		} else {
			$pagina = "continuarLiquidacion.php?idfactura=$idfactura";
		}
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