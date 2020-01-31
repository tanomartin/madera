<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

if(isset($_POST)) {
	if(isset($_POST['idFactura'])) {
		//idFactura
		$idfactura = $_POST['idFactura'];
	}

	if(isset($_POST['idFacturabeneficiario'])) {
		//idFacturabeneficiario
		$idfacturabeneficiario = $_POST['idFacturabeneficiario'];
	}

	if(isset($_POST['idprestador'])) {
		//efectorpractica
		$efectorpractica = $_POST['idprestador'];
	}

	//tipomovimiento
	$tipomovimiento = 3;

	if(isset($_POST['idPractica'])) {
		//idPractica
		$idpractica = $_POST['idPractica'];
	}

	if(isset($_POST['cantidad'])) {
		//cantidad
		$cantidad = $_POST['cantidad'];
	}

	if(isset($_POST['fechaprestacion'])) {
		//fechapractica
		$fechapractica = fechaParaGuardar($_POST['fechaprestacion']);
	}

	if(isset($_POST['totalfacturado'])) {
		//totalfacturado
		$totalfacturado = $_POST['totalfacturado'];
	}

	if(isset($_POST['totaldebito'])) {
		//totaldebito
		$totaldebito = $_POST['totaldebito'];
	}

	if(isset($_POST['motivodebito'])) {
		//motivodebito
		$motivodebito = $_POST['motivodebito'];
	} else {
		$motivodebito = NULL;
	}

	if(isset($_POST['totalcredito'])) {
		//totalcredito
		$totalcredito = $_POST['totalcredito'];
	}

	if(isset($_POST['personeria'])) {
		if($_POST['personeria']==6) {
			//tipoefectorpractica
			$tipoefectorpractica = 3;
			if(isset($_POST['idEfector'])) {
				//efectorpractica
				$efectorpractica = $_POST['idEfector'];
			}
			$profesionalestablecimientocirculo = NULL;
		} else {
			//tipoefectorpractica
			$tipoefectorpractica = 1;
			$profesionalestablecimientocirculo = NULL;
		}
	} else {
			//tipoefectorpractica
		$tipoefectorpractica = 1;
		$profesionalestablecimientocirculo = NULL;	
	}

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$sqlAddFacturasPrestacion = "INSERT INTO facturasprestaciones(id,idFactura,idFacturabeneficiario,tipomovimiento,idPractica,cantidad,fechapractica,totalfacturado,totaldebito,motivodebito,totalcredito,tipoefectorpractica,efectorpractica,profesionalestablecimientocirculo) VALUES(:id,:idFactura,:idFacturabeneficiario,:tipomovimiento,:idPractica,:cantidad,:fechapractica,:totalfacturado,:totaldebito,:motivodebito,:totalcredito,:tipoefectorpractica,:efectorpractica,:profesionalestablecimientocirculo)";
		$resAddFacturasPrestacion = $dbh->prepare($sqlAddFacturasPrestacion);
		if($resAddFacturasPrestacion->execute(array(':id' => 'DEFAULT',':idFactura' => $idfactura,':idFacturabeneficiario' => $idfacturabeneficiario,':tipomovimiento' => $tipomovimiento,':idPractica' => $idpractica,':cantidad' => $cantidad,':fechapractica' => $fechapractica,':totalfacturado' => $totalfacturado,':totaldebito' => $totaldebito,':motivodebito' => $motivodebito,':totalcredito' => $totalcredito,':tipoefectorpractica' => $tipoefectorpractica,':efectorpractica' => $efectorpractica,':profesionalestablecimientocirculo' => $profesionalestablecimientocirculo))) {
		}
		$sqlConsultaTotalesBeneficiario = "SELECT totalfacturado, totaldebito, totalcredito, excepcionjurisdiccion, consumoprestacional FROM facturasbeneficiarios WHERE id = $idfacturabeneficiario AND idFactura = $idfactura";
		$resConsultaTotalesBeneficiario = mysql_query($sqlConsultaTotalesBeneficiario,$db);
		$rowConsultaTotalesBeneficiario = mysql_fetch_array($resConsultaTotalesBeneficiario);
		(float)$facturado = (float)$rowConsultaTotalesBeneficiario['totalfacturado'] + (float)$totalfacturado;
		(float)$debito  = (float)$rowConsultaTotalesBeneficiario['totaldebito'] + (float)$totaldebito;
		(float)$credito = (float)$rowConsultaTotalesBeneficiario['totalcredito'] + (float)$totalcredito;
		$consumo = $rowConsultaTotalesBeneficiario['consumoprestacional'] + 1;
		$exceptuado = $rowConsultaTotalesBeneficiario['excepcionjurisdiccion'];

		$sqlUpdateTotalesBeneficiario = "UPDATE facturasbeneficiarios SET totalfacturado = :totalfacturado, totaldebito = :totaldebito, totalcredito = :totalcredito, exceptuado = :exceptuado, consumoprestacional = :consumoprestacional WHERE id = :id AND idFactura = :idfactura";
		$resUpdateTotalesBeneficiario = $dbh->prepare($sqlUpdateTotalesBeneficiario);
		if($resUpdateTotalesBeneficiario->execute(array(':totalfacturado' => $facturado, ':totaldebito' => $debito, ':totalcredito' => $credito, ':exceptuado' => $exceptuado, ':consumoprestacional' => $consumo, ':id' => $idfacturabeneficiario, ':idfactura' => $idfactura))) {
		}
		$dbh->commit();
		echo json_encode(array('result'=> true));
	}
	catch (PDOException $e) {
		$dbh->rollback();
		echo json_encode(array('result'=> false));
	}
	return;
}?>