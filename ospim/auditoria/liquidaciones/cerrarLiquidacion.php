<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['idFactura'])) {
	var_dump($_GET);
	$idcomprobante = $_GET['idFactura'];
	$fechacierreliquidacion = date("Y-m-d H:i:s");
	$totalcredito = 0.00;
	$totaldebito = 0.00;
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlConsultaFacturasBeneficiarios = "SELECT totalcredito, totaldebito FROM facturasbeneficiarios WHERE idFactura = $idcomprobante";
		$resConsultaFacturasBeneficiarios = mysql_query($sqlConsultaFacturasBeneficiarios,$db);
		while($rowConsultaFacturasBeneficiarios = mysql_fetch_array($resConsultaFacturasBeneficiarios)) {
			$totalcredito = $totalcredito + $rowConsultaFacturasBeneficiarios['totalcredito'];
			$totaldebito = $totaldebito + $rowConsultaFacturasBeneficiarios['totaldebito'];
		}

		$sqlConsultaCarenciasBeneficiarios = "SELECT totalcredito, totaldebito FROM facturascarenciasbeneficiarios WHERE idFactura = $idcomprobante";
		$resConsultaCarenciasBeneficiarios = mysql_query($sqlConsultaCarenciasBeneficiarios,$db);
		while($rowConsultaCarenciasBeneficiarios = mysql_fetch_array($resConsultaCarenciasBeneficiarios)) {
			$totalcredito = $totalcredito + $rowConsultaCarenciasBeneficiarios['totalcredito'];
			$totaldebito = $totaldebito + $rowConsultaCarenciasBeneficiarios['totaldebito'];
		}

		$sqlUpdateFacturas = "UPDATE facturas SET fechacierreliquidacion = :fechacierreliquidacion, totalcredito = :totalcredito, totaldebito = :totaldebito, importeliquidado = :importeliquidado, restoapagar = :restoapagar WHERE id = :id";
		$resUpdateFacturas = $dbh->prepare($sqlUpdateFacturas);
		if($resUpdateFacturas->execute(array(':fechacierreliquidacion' => $fechacierreliquidacion, ':totalcredito' => $totalcredito, ':totaldebito' => $totaldebito, ':importeliquidado' => $totalcredito, ':restoapagar' => $totalcredito, ':id' => $idcomprobante))) {
		}

		$dbh->commit();
		$pagina = "facturasLiquidadas.php";
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