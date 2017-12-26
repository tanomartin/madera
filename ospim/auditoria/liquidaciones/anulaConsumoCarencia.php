<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET)) {
	//var_dump($_GET);
	$idconsumocarencia = $_GET['idConsumoCarencia'];
	$idFactura = $_GET['idFactura'];
	$idfacturabeneficiario = $_GET['idFacturaBeneficiario'];
		try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlDeleteConsumoCarencia = "DELETE FROM facturasprestaciones WHERE id = :id";
		$resDeleteConsumoCarencia = $dbh->prepare($sqlDeleteConsumoCarencia);
		if($resDeleteConsumoCarencia->execute(array(':id' => $idconsumocarencia)))
		$dbh->commit();
		$pagina = "consumoBeneficiario.php?idFactura=$idFactura&idFacturabeneficiario=$idfacturabeneficiario";
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