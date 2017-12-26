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
	$tipomovimiento = 1;

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
		if($_POST['personeria']==3 || $_POST['personeria']==4) {
			if($_POST['personeria']==3) {
				//tipoefectorpractica
				$tipoefectorpractica = 2;
				//profesionalestablecimientocirculo
				$profesionalestablecimientocirculo = NULL;
			} else {
				//tipoefectorpractica
				$tipoefectorpractica = 3;
				if(isset($_POST['establecimientoCirculo'])) {
					if($_POST['establecimientoCirculo']==1) {
						if(isset($_POST['efectorprofesional'])) {
							//profesionalestablecimientocirculo
							$profesionalestablecimientocirculo = $_POST['efectorprofesional'];
						}
					} else {
						//profesionalestablecimientocirculo
						$profesionalestablecimientocirculo = NULL;
					}
				} else {
					//profesionalestablecimientocirculo
					$profesionalestablecimientocirculo = NULL;
				}
			}
			if(isset($_POST['idEfector'])) {
				//efectorpractica
				$efectorpractica = $_POST['idEfector'];
			}
		} else {
			//tipoefectorpractica
			$tipoefectorpractica = 1;
			$profesionalestablecimientocirculo = NULL;
		}
	}

//  DESDE ACA VA A LA TABLA facturasintegracion
	if(isset($_POST['esIntegracion'])) {
		if($_POST['esIntegracion']==1) {
			if(isset($_POST['cancelaintegracion'])) {
				if($_POST['cancelaintegracion']==1) {
					$agregaintegracion = true;
					if(isset($_POST['solicitadointegracion'])) {
						$totalsolicitado = $_POST['solicitadointegracion'];
					}
					if(isset($_POST['dependenciaintegracion'])) {
						$dependencia = $_POST['dependenciaintegracion'];
					} else {
						$dependencia = 0;
					}
					if(isset($_POST['tipoescuelaintegracion'])) {
						$tipoescuela = $_POST['tipoescuelaintegracion'];
					} else {
						$tipoescuela = NULL;
					}
					if(isset($_POST['cueescuelaintegracion'])) {
						$cueescuela = $_POST['cueescuelaintegracion'];
					} else {
						$cueescuela = NULL;
					}
				} else {
					$agregaintegracion = false;
				}
			} else {
				$agregaintegracion = false;
			}
		} else {
			$agregaintegracion = false;
		}
	} else {
		$agregaintegracion = false;
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
		if($agregaintegracion) {
			$sqlAddFacturasIntegracion = "INSERT INTO facturasintegracion(id,idFactura,idFacturabeneficiario,totalsolicitado,dependencia,tipoescuela,cueescuela) VALUES(:id,:idFactura,:idFacturabeneficiario,:totalsolicitado,:dependencia,:tipoescuela,:cueescuela)";
			$resAddFacturasIntegracion = $dbh->prepare($sqlAddFacturasIntegracion);
			if($resAddFacturasIntegracion->execute(array(':id' => 'DEFAULT',':idFactura' => $idfactura,':idFacturabeneficiario' => $idfacturabeneficiario,':totalsolicitado' => $totalsolicitado,':dependencia' => $dependencia,':tipoescuela' => $tipoescuela,':cueescuela' => $cueescuela))) {
			}
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