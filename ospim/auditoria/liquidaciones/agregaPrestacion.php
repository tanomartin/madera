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

//  DESDE ACA VA A LA TABLA facturasestadisticas
	$agregaestadistica = false;
	$valorcomputo = 0;
	$cantidadcomputo = 0;
	$estadisticas = array();
	$estadindices = 0;
	 if(isset($_POST['calculoestadistico'])) {
		if($_POST['calculoestadistico']==1) {
			if(isset($_POST['estamb'])) {
				if($_POST['estamb']==1) {
					$agregaestadistica = true;
					if(isset($_POST['amb1'])) {
						if($_POST['amb1']!=NULL) {
							$valorcomputo = $_POST['amb1'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
					}
					if(isset($_POST['amb2'])) {
						if($_POST['amb2']!=NULL) {
							$valorcomputo = $_POST['amb2'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
						if(isset($_POST['amb21'])) {
							if($_POST['amb21']!=NULL) {
								$valorcomputo = $_POST['amb21'];
								$cantidadcomputo = 1;
								$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
								$estadindices++;
							}
						}
						if(isset($_POST['amb22'])) {
							if($_POST['amb22']!=NULL) {
								$valorcomputo = $_POST['amb22'];
								$cantidadcomputo = 1;
								$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
								$estadindices++;
							}
						}
					}
					if(isset($_POST['amb3'])) {
						if($_POST['amb3']!=NULL) {
							$valorcomputo = $_POST['amb3'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
						if(isset($_POST['amb31'])) {
							if($_POST['amb31']!=NULL) {
								$valorcomputo = $_POST['amb31'];
								$cantidadcomputo = 1;
								$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
								$estadindices++;
							}
						}
						if(isset($_POST['amb32'])) {
							if($_POST['amb32']!=NULL) {
								$valorcomputo = $_POST['amb32'];
								$cantidadcomputo = 1;
								$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
								$estadindices++;
							}
						}
						if(isset($_POST['amb33'])) {
							if($_POST['amb33']!=NULL) {
								$valorcomputo = $_POST['amb33'];
								$cantidadcomputo = 1;
								$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
								$estadindices++;
							}
						}
					}
					if(isset($_POST['amb4'])) {
						if($_POST['amb4']!=NULL) {
							$valorcomputo = $_POST['amb4'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
					}
					if(isset($_POST['amb5'])) {
						if($_POST['amb5']!=NULL) {
							$valorcomputo = $_POST['amb5'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
					}
					if(isset($_POST['amb6'])) {
						if($_POST['amb6']!=NULL) {
							$valorcomputo = $_POST['amb6'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
					}
					if(isset($_POST['amb7'])) {
						if($_POST['amb7']!=NULL) {
							$valorcomputo = $_POST['amb7'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
					}
					if(isset($_POST['amb8'])) {
						if($_POST['amb8']!=NULL) {
							$valorcomputo = $_POST['amb8'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
					}
				}
			}
			if(isset($_POST['estint'])) {
				if($_POST['estint']==1) {
					$agregaestadistica = true;
					if(isset($_POST['int9'])) {
						if($_POST['int9']!=NULL) {
							$valorcomputo = $_POST['int9'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
					}
					if(isset($_POST['int10'])) {
						if($_POST['int10']!=NULL) {
							$valorcomputo = $_POST['int10'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
					}
					if(isset($_POST['int11'])) {
						if($_POST['int11']!=NULL) {
							$valorcomputo = $_POST['int11'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
					}
					if(isset($_POST['int12'])) {
						if($_POST['int12']!=NULL) {
							$valorcomputo = $_POST['int12'];
							$cantidadcomputo = 1;
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
							$estadindices++;
						}
						if(isset($_POST['int121'])) {
							if($_POST['int121']!=NULL) {
								$valorcomputo = $_POST['int121'];
								$cantidadcomputo = 1;
								$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
								$estadindices++;
							}
						}
						if(isset($_POST['int122'])) {
							if($_POST['int122']!=NULL) {
								$valorcomputo = $_POST['int122'];
								$cantidadcomputo = 1;
								$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
								$estadindices++;
							}
						}
						if(isset($_POST['int123'])) {
							if($_POST['int123']!=NULL) {
								$valorcomputo = $_POST['int123'];
								$cantidadcomputo = 1;
								$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
								$estadindices++;
							}
						}
						if(isset($_POST['int124'])) {
							if($_POST['int124']!=NULL) {
								$valorcomputo = $_POST['int124'];
								$cantidadcomputo = 1;
								$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
								$estadindices++;
							}
						}
					}
					if(isset($_POST['int13'])) {
						if($_POST['int13']!=NULL) {
							$valorcomputo = $_POST['int13'];
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => 1);
							$estadindices++;
						}
					}
					if(isset($_POST['int141'])) {
						if($_POST['int141']!=NULL) {
							$valorcomputo = $_POST['int141'];
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => 1);
							$estadindices++;
						}
					}
					if(isset($_POST['int142'])) {
						if($_POST['int142']!=NULL) {
							$valorcomputo = $_POST['int142'];
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => 1);
							$estadindices++;
						}
					}
					if(isset($_POST['int143'])) {
						if($_POST['int143']!=NULL) {
							$valorcomputo = $_POST['int143'];
							$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => 1);
							$estadindices++;
						}
					}
					if(isset($_POST['diastotal'])) {
						$valorcomputo = 15;
						$cantidadcomputo = $_POST['diastotal'];
						$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
						$estadindices++;
					}
					if(isset($_POST['diascoronaria'])) {
						$valorcomputo = 151;
						$cantidadcomputo = $_POST['diascoronaria'];
						$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
						$estadindices++;
					}
					if(isset($_POST['diasintensiva'])) {
						$valorcomputo = 152;
						$cantidadcomputo = $_POST['diasintensiva'];
						$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
						$estadindices++;
					}
					if(isset($_POST['diasneonatologia'])) {
						$valorcomputo = 153;
						$cantidadcomputo = $_POST['diasneonatologia'];
						$estadisticas[$estadindices] = array('vcomputo' => $valorcomputo,'ccomputo' => $cantidadcomputo);
						$estadindices++;
					}
				}
			}
		}
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

		$lastidfacturaprestacion = $dbh->lastInsertId();

		if($agregaintegracion) {
			$sqlAddFacturasIntegracion = "INSERT INTO facturasintegracion(id,idFacturaprestacion,totalsolicitado,dependencia,tipoescuela,idEscuela) VALUES(:id,:idFacturaprestacion,:totalsolicitado,:dependencia,:tipoescuela,:idEscuela)";
			$resAddFacturasIntegracion = $dbh->prepare($sqlAddFacturasIntegracion);
			if($resAddFacturasIntegracion->execute(array(':id' => 'DEFAULT',':idFacturaprestacion' => $lastidfacturaprestacion,':totalsolicitado' => $totalsolicitado,':dependencia' => $dependencia,':tipoescuela' => $tipoescuela,':idEscuela' => $cueescuela))) {
			}
		}

		if($agregaestadistica) {
			foreach($estadisticas as $valestadistica) {
				$sqlAddFacturasEstadisticas = "INSERT INTO facturasestadisticas(id,idFacturaprestacion,valorcomputo,cantidadcomputo) VALUES(:id,:idFacturaprestacion,:valorcomputo,:cantidadcomputo)";
				$resAddFacturasEstadisticas = $dbh->prepare($sqlAddFacturasEstadisticas);
				if($resAddFacturasEstadisticas->execute(array(':id' => 'DEFAULT',':idFacturaprestacion' => $lastidfacturaprestacion,':valorcomputo' => $valestadistica['vcomputo'],':cantidadcomputo' => $valestadistica['ccomputo']))) {
				}
			}
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