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

		$sqlConsultaConsumoCarencia = "SELECT totalfacturado, totaldebito, totalcredito FROM facturasprestaciones WHERE id = $idconsumocarencia";
		$resConsultaConsumoCarencia = mysql_query($sqlConsultaConsumoCarencia,$db);
		$rowConsultaConsumoCarencia = mysql_fetch_array($resConsultaConsumoCarencia);

		$sqlConsultaTotalesBeneficiario = "SELECT totalfacturado, totaldebito, totalcredito, consumoprestacional FROM facturasbeneficiarios WHERE id = $idfacturabeneficiario AND idFactura = $idFactura";
		$resConsultaTotalesBeneficiario = mysql_query($sqlConsultaTotalesBeneficiario,$db);
		$rowConsultaTotalesBeneficiario = mysql_fetch_array($resConsultaTotalesBeneficiario);
		$facturado = $rowConsultaTotalesBeneficiario['totalfacturado'] - $rowConsultaConsumoCarencia['totalfacturado'];
		$debito  = $rowConsultaTotalesBeneficiario['totaldebito'] - $rowConsultaConsumoCarencia['totaldebito'];
		$credito = $rowConsultaTotalesBeneficiario['totalcredito'] - $rowConsultaConsumoCarencia['totalcredito'];
		$consumo = $rowConsultaTotalesBeneficiario['consumoprestacional'] - 1;
		$exceptuado = 0;

		$sqlUpdateTotalesBeneficiario = "UPDATE facturasbeneficiarios SET totalfacturado = :totalfacturado, totaldebito = :totaldebito, totalcredito = :totalcredito, exceptuado = :exceptuado, consumoprestacional = :consumoprestacional WHERE id = :id AND idFactura = :idfactura";
		$resUpdateTotalesBeneficiario = $dbh->prepare($sqlUpdateTotalesBeneficiario);
		if($resUpdateTotalesBeneficiario->execute(array(':totalfacturado' => $facturado, ':totaldebito' => $debito, ':totalcredito' => $credito, ':exceptuado' => $exceptuado, ':consumoprestacional' => $consumo, ':id' => $idfacturabeneficiario, ':idfactura' => $idFactura))) {
		}

		$sqlDeleteConsumoCarencia = "DELETE FROM facturasprestaciones WHERE id = :id";
		$resDeleteConsumoCarencia = $dbh->prepare($sqlDeleteConsumoCarencia);
		if($resDeleteConsumoCarencia->execute(array(':id' => $idconsumocarencia))){
		}

		$sqlDeleteIntegracion = "DELETE FROM facturasintegracion WHERE idFacturaprestacion = :idFacturaprestacion";
		$resDeleteIntegracion = $dbh->prepare($sqlDeleteIntegracion);
		if($resDeleteIntegracion->execute(array(':idFacturaprestacion' => $idconsumocarencia))){
		}

		$sqlDeleteEstadisctica = "DELETE FROM facturasestadisticas WHERE idFacturaprestacion = :idFacturaprestacion";
		$resDeleteEstadisctica = $dbh->prepare($sqlDeleteEstadisctica);
		if($resDeleteEstadisctica->execute(array(':idFacturaprestacion' => $idconsumocarencia))){
		}

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