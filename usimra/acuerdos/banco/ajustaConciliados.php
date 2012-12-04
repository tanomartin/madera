<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/usimra/lib/";
include($libPath."controlSession.php");

//conexion y creacion de transaccion.
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$ajustefechainicio = "2012-01-01";
	$ajusteestadoconciliacion = 1;

	$sqlBuscaResumen="SELECT * FROM resumenusimra WHERE fechaemision >= :fechaemision and estadoconciliacion = :estadoconciliacion ORDER BY codigocuenta, fechaemision, nroordenimputacion, fechaimputacion";
	$resultBuscaResumen = $dbh->prepare($sqlBuscaResumen);
	if ($resultBuscaResumen->execute(array(':fechaemision' => $ajustefechainicio, ':estadoconciliacion' => $ajusteestadoconciliacion)))
	{
		//echo "BUSCA RESUMENUSIMRA"; echo "<br>";
		foreach ($resultBuscaResumen as $ajustaresumen)
		{
			$ajustacuenta=$ajustaresumen[codigocuenta];
			$ajustafechaemi=$ajustaresumen[fechaemision];
			$ajustaorden=$ajustaresumen[nroordenimputacion];

			$sqlLeeOrigen="SELECT * FROM origencomprobanteusimra WHERE codigocuenta = :codigocuenta AND fechaemision = :fechaemision AND nroordenimputacion = :nroordenimputacion";
			$resultLeeOrigen = $dbh->prepare($sqlLeeOrigen);
			if ($resultLeeOrigen->execute(array(':codigocuenta' => $ajustacuenta, ':fechaemision' => $ajustafechaemi, ':nroordenimputacion' => $ajustaorden)))
			{
				//echo "LEE ORIGENCOMPROBANTE"; echo "<br>";
				foreach ($resultLeeOrigen as $origen)
				{
					if(strcmp("Remesa", $origen[comprobante])==0)
					{
						//echo "ENCONTRO UNA REMESA SUELTO"; echo "<br>";
						$ajustacuentaremesa=$origen[codigocuenta];
						$ajustafecharemesa=$origen[fechacomprobante];
						$ajustanroremesa=$origen[nrocomprobante];

						$sqlBuscaRemitos="SELECT * FROM remitosremesasusimra WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion ORDER BY codigocuenta, sistemaremesa, fecharemesa, nroremesa, nroremito";
						$resultBuscaRemitos = $dbh->prepare($sqlBuscaRemitos);
						if ($resultBuscaRemitos->execute(array(':codigocuenta' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':estadoconciliacion' => $ajusteestadoconciliacion)))
						{
							//echo "BUSCA REMITOSREMESA"; echo "<br>";
							$importeboletasacu=0.00;
							$totalboletasacu=0;
							$importeboletasapo=0.00;
						 	$importeboletasrec=0.00;
							$importeboletasvar=0.00;
							$importeboletaspag=0.00;
							$totalboletaspag=0;
							$totalboletas=0;
							$importeboletasbru=0.00;

							foreach ($resultBuscaRemitos as $buscaremitos)
							{
								$ajustanroremito=$buscaremitos[nroremito];

								$sqlLeeBoletasAcu="SELECT * FROM conciliacuotasusimra WHERE cuentaboleta = :cuentaboleta and cuentaremesa = :cuentaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and nroremitoremesa = :nroremito";
								$resultLeeBoletasAcu = $dbh->prepare($sqlLeeBoletasAcu);
								if ($resultLeeBoletasAcu->execute(array(':cuentaboleta' => $ajustacuenta, ':cuentaremesa' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':nroremito' => $ajustanroremito)))
								{
									//echo "LEE CONCILIACUOTAS"; echo "<br>";
									foreach ($resultLeeBoletasAcu as $acuerdos)
									{
										$cuiacu=$acuerdos[cuit];
										$nroacu=$acuerdos[nroacuerdo];
										$nrocuo=$acuerdos[nrocuota];
										$ctabolacu=$acuerdos[cuentaboleta];
										$ctarsaacu=$acuerdos[cuentaremesa];
										$fecrsaacu=$acuerdos[fecharemesa];
										$nrorsaacu=$acuerdos[nroremesa];
										$nrortoacu=$acuerdos[nroremitoremesa];
										if($acuerdos[estadoconciliacion]==0)
										{
											$estconacu=1;
											$fecconacu=date("Y-m-d H:m:s");
											$usuconacu=$_SESSION['usuario'];
										}
										else
										{
											$estconacu=$acuerdos[estadoconciliacion];
											$fecconacu=$acuerdos[fechaconciliacion];
											$usuconacu=$acuerdos[usuarioconciliacion];
										}

										$sqlLeeCuotas="SELECT * FROM cuoacuerdosusimra WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
										$resultLeeCuotas = $dbh->prepare($sqlLeeCuotas);
										if ($resultLeeCuotas->execute(array(':cuit' => $cuiacu, ':nroacuerdo' => $nroacu, ':nrocuota' => $nrocuo)))
										{
											foreach ($resultLeeCuotas as $cuotas)
											{
												if($cuotas[montopagada]!=0.00)
												{
													$importeboletasacu=$importeboletasacu+$cuotas[montopagada];
													$totalboletasacu++;
												}
											}
										}
									
										$sqlAjustaConCuota="UPDATE conciliacuotasusimra SET estadoconciliacion = :estadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota and cuentaboleta = :cuentaboleta and cuentaremesa = :cuentaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and nroremitoremesa = :nroremito";
										$resultAjustaConCuota = $dbh->prepare($sqlAjustaConCuota);
										if ($resultAjustaConCuota->execute(array(':cuit' => $cuiacu, ':nroacuerdo' => $nroacu, ':nrocuota' => $nrocuo, ':cuentaboleta' => $ctabolacu, ':cuentaremesa' => $ctarsaacu, ':fecharemesa' => $fecrsaacu, ':nroremesa' => $nrorsaacu, ':nroremito' => $nrortoacu, ':estadoconciliacion' => $estconacu, ':fechaconciliacion' => $fecconacu, ':usuarioconciliacion' => $usuconacu)))
										{
										}
									}
								}

								$sqlLeeBoletasApo="SELECT * FROM conciliapagosusimra WHERE cuentaboleta = :cuentaboleta and cuentaremesa = :cuentaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and nroremitoremesa = :nroremito";
								$resultLeeBoletasApo = $dbh->prepare($sqlLeeBoletasApo);
								if ($resultLeeBoletasApo->execute(array(':cuentaboleta' => $ajustacuenta, ':cuentaremesa' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':nroremito' => $ajustanroremito)))
								{
									//echo "LEE CONCILIAPAGOS"; echo "<br>";
									foreach ($resultLeeBoletasApo as $aportes)
									{
										$cuiapo=$aportes[cuit];
										$mesapo=$aportes[mespago];
										$anoapo=$aportes[anopago];
										$nroapo=$aportes[nropago];
										$ctabolapo=$aportes[cuentaboleta];
										$ctarsaapo=$aportes[cuentaremesa];
										$fecrsaapo=$aportes[fecharemesa];
										$nrorsaapo=$aportes[nroremesa];
										$nrortoapo=$aportes[nroremitoremesa];
										if($aportes[estadoconciliacion]==0)
										{
											$estconapo=1;
											$fecconapo=date("Y-m-d H:m:s");
											$usuconapo=$_SESSION['usuario'];
										}
										else
										{
											$estconapo=$aportes[estadoconciliacion];
											$fecconapo=$aportes[fechaconciliacion];
											$usuconapo=$aportes[usuarioconciliacion];
										}

										//TODO: Aca iria el sqlLeePagos cuando se sume al sistema todo el modulo de aportes
										//$sqlLeePagos="SELECT * FROM xxxxxxxxxxxx WHERE cuit = :cuit and mespago = :mespago and anopago = :anopago and nropago = :nropago";
										//$resultLeePagos = $dbh->prepare($sqlLeePagos);
										//if ($resultLeePagos->execute(array(':cuit' => $cuiapo, ':mespago' => $mesapo, ':anopago' => $anoapo, ':nropago' => $nroapo)))
										//{
											//	foreach ($resultLeePagos as $pagos)
											//	{
											//		if($pagos[]!=0.00)
											//		{
											//			$importeboletasapo=$importeboletasapo+$pagos[];
											//		 	$importeboletasrec=$importeboletasrec+$pagos[];
											//			$importeboletasvar=$importeboletasvar+$pagos[];
											//			$importeboletaspag=$importeboletaspag+$pagos[];
											//			$totalboletaspag++;
											//		}
											//	}
										//}

										$sqlAjustaConPagos="UPDATE conciliapagosusimra SET estadoconciliacion = :estadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion WHERE cuit = :cuit and mespago = :mespago and anopago = :anopago and nropago = :nropago and cuentaboleta = :cuentaboleta and cuentaremesa = :cuentaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and nroremitoremesa = :nroremito";
										$resultAjustaConPagos = $dbh->prepare($sqlAjustaConPagos);
										if ($resultAjustaConPagos->execute(array(':cuit' => $cuiapo, ':mespago' => $mesapo, ':anopago' => $anoapo, ':nropago' => $nroapo, ':cuentaboleta' => $ctabolapo, ':cuentaremesa' => $ctarsaapo, ':fecharemesa' => $fecrsaapo, ':nroremesa' => $nrorsaapo, ':nroremito' => $nrortoapo, ':estadoconciliacion' => $estconapo, ':fechaconciliacion' => $fecconapo, ':usuarioconciliacion' => $usuconapo)))
										{
										}
									}
								}

								$importeboletasbru=$importeboletasacu+$importeboletaspag;
								$totalboletas=$totalboletasacu+$totalboletaspag;

								$sqlAjustaRemitos="UPDATE remitosremesasusimra SET importeboletasaporte = :importeboletasaporte, importeboletasrecargo = :importeboletasrecargo, importeboletasvarios = :importeboletasvarios, importeboletaspagos = :importeboletaspagos, importeboletascuotas = :importeboletascuotas, importeboletasbruto = :importeboletasbruto, cantidadboletas = :cantidadboletas WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and nroremesa = :nroremesa and nroremito = :nroremito and estadoconciliacion = :estadoconciliacion";
								$resultAjustaRemitos = $dbh->prepare($sqlAjustaRemitos);
								if ($resultAjustaRemitos->execute(array(':importeboletasaporte' => $importeboletasapo, ':importeboletasrecargo' => $importeboletasrec, ':importeboletasvarios' => $importeboletasvar, ':importeboletaspagos' => $importeboletaspag, ':importeboletascuotas' => $importeboletasacu, ':importeboletasbruto' => $importeboletasbru, ':cantidadboletas' => $totalboletas, ':codigocuenta' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':nroremito' => $ajustanroremito, ':estadoconciliacion' => $ajusteestadoconciliacion)))
								{
										//echo "AJUSTA REMITOSREMESA"; echo "<br>";
								}
							}
						}

						$sqlBuscaRemesa="SELECT * FROM remesasusimra WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion ORDER BY codigocuenta, sistemaremesa, fecharemesa, nroremesa";
						$resultBuscaRemesa = $dbh->prepare($sqlBuscaRemesa);
						if ($resultBuscaRemesa->execute(array(':codigocuenta' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':estadoconciliacion' => $ajusteestadoconciliacion)))
						{
							foreach ($resultBuscaRemesa as $buscaremesa)
							{
								//echo "BUSCA REMESA"; echo "<br>";
								$totalremitosbruto=0.00;
								$totalremitoscomision=0.00;
								$totalremitosneto=0.00;
								$importeremitosapo=0.00;
							 	$importeremitosrec=0.00;
								$importeremitosvar=0.00;
								$importeremitospag=0.00;
								$importeremitosacu=0.00;
								$importeremitosbru=0.00;
								$totalremitosboletas=0;

								$sqlBuscaRemitos="SELECT * FROM remitosremesasusimra WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion ORDER BY codigocuenta, sistemaremesa, fecharemesa, nroremesa, nroremito";
								$resultBuscaRemitos = $dbh->prepare($sqlBuscaRemitos);
								if ($resultBuscaRemitos->execute(array(':codigocuenta' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':estadoconciliacion' => $ajusteestadoconciliacion)))
								{
									//echo "BUSCA REMITOSREMESA"; echo "<br>";
									foreach ($resultBuscaRemitos as $buscaremitos)
									{
										$totalremitosbruto=$totalremitosbruto+$buscaremitos[importebruto];
										$totalremitoscomision=$totalremitoscomision+$buscaremitos[importecomision];
										$totalremitosneto=$totalremitosneto+$buscaremitos[importeneto];
										$importeremitosapo=$importeremitosapo+$buscaremitos[importeboletasaporte];
									 	$importeremitosrec=$importeremitosrec+$buscaremitos[importeboletasrecargo];
										$importeremitosvar=$importeremitosvar+$buscaremitos[importeboletasvarios];
										$importeremitospag=$importeremitospag+$buscaremitos[importeboletaspagos];
										$importeremitosacu=$importeremitosacu+$buscaremitos[importeboletascuotas];
										$importeremitosbru=$importeremitosbru+$buscaremitos[importeboletasbruto];
										$totalremitosboletas=$totalremitosboletas+$buscaremitos[cantidadboletas];
									}
								}
												
								$sqlAjustaRemesa="UPDATE remesasusimra SET importebrutoremitos = :importebrutoremitos, importecomisionesremitos = :importecomisionesremitos, importenetoremitos = :importenetoremitos, importeboletasaporte = :importeboletasaporte, importeboletasrecargo = :importeboletasrecargo, importeboletasvarios = :importeboletasvarios, importeboletaspagos = :importeboletaspagos, importeboletascuotas = :importeboletascuotas, importeboletasbruto = :importeboletasbruto, cantidadboletas = :cantidadboletas WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion";
								$resultAjustaRemesa = $dbh->prepare($sqlAjustaRemesa);
								if ($resultAjustaRemesa->execute(array(':importebrutoremitos' => $totalremitosbruto, ':importecomisionesremitos' => $totalremitoscomision, ':importenetoremitos' => $totalremitosneto, ':importeboletasaporte' => $importeremitosapo, ':importeboletasrecargo' => $importeremitosrec, ':importeboletasvarios' => $importeremitosvar, ':importeboletaspagos' => $importeremitospag, ':importeboletascuotas' => $importeremitosacu, ':importeboletasbruto' => $importeremitosbru, ':cantidadboletas' => $totalremitosboletas, ':codigocuenta' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':estadoconciliacion' => $ajusteestadoconciliacion)))
								{
									//echo "AJUSTA REMESA"; echo "<br>";
								}
							}
						}
					}//Cierre del IF de Comprobante Remesa
					else
					{
						//echo "ENCONTRO UN REMITO SUELTO"; echo "<br>";
						$ajustacuentaremitosuelto=$origen[codigocuenta];
						$ajustafecharemitosuelto=$origen[fechacomprobante];
						$ajustanroremitosuelto=$origen[nrocomprobante];

						$sqlBuscaRemitosSueltos="SELECT * FROM remitossueltossusimra WHERE codigocuenta = :codigocuenta and fecharemito = :fecharemito and nroremito = :nroremito and estadoconciliacion = :estadoconciliacion ORDER BY codigocuenta, sistemaremito, fecharemito, nroremito";
						$resultBuscaRemitosSueltos = $dbh->prepare($sqlBuscaRemitosSueltos);
						if ($resultBuscaRemitosSueltos ->execute(array(':codigocuenta' => $ajustacuentaremitosuelto, ':fecharemito ' => $ajustafecharemitosuelto, ':nroremito ' => $ajustanroremitosuelto, ':estadoconciliacion' => $ajusteestadoconciliacion)))
						{
							//echo "BUSCA REMITOSSUELTOS"; echo "<br>";
							$importeboletasacu=0.00;
							$totalboletasacu=0;
							$importeboletasapo=0.00;
						 	$importeboletasrec=0.00;
							$importeboletasvar=0.00;
							$importeboletaspag=0.00;
							$totalboletaspag=0;
							$totalboletas=0;
							$importeboletasbru=0.00;

							foreach ($resultBuscaRemitosSueltos as $buscaremitossueltos)
							{
								$sqlLeeBoletasAcu="SELECT * FROM conciliacuotasusimra WHERE cuentaboleta = :cuentaboleta and cuentaremitosuelto = :cuentaremitosuelto and fecharemitosuelto = :fecharemitosuelto and nroremitosuelto = :nroremitosuelto ";
								$resultLeeBoletasAcu = $dbh->prepare($sqlLeeBoletasAcu);
								if ($resultLeeBoletasAcu->execute(array(':cuentaboleta' => $ajustacuenta, ':cuentaremitosuelto ' => $ajustacuentaremitosuelto, ':fecharemitosuelto' => $ajustafecharemitosuelto, ':nroremitosuelto' => $ajustanroremitosuelto)))
								{
									//echo "LEE CONCILIACUOTAS"; echo "<br>";
									foreach ($resultLeeBoletasAcu as $acuerdos)
									{
										$cuiacu=$acuerdos[cuit];
										$nroacu=$acuerdos[nroacuerdo];
										$nrocuo=$acuerdos[nrocuota];
										$ctabolacu=$acuerdos[cuentaboleta];
										$ctarsuacu=$acuerdos[cuentaremitosuelto];
										$fecrsuacu=$acuerdos[fecharemitosuelto];
										$nrorsuacu=$acuerdos[nroremitosuelto];
										if($acuerdos[estadoconciliacion]==0)
										{
											$estconacu=1;
											$fecconacu=date("Y-m-d H:m:s");
											$usuconacu=$_SESSION['usuario'];
										}
										else
										{
											$estconacu=$acuerdos[estadoconciliacion];
											$fecconacu=$acuerdos[fechaconciliacion];
											$usuconacu=$acuerdos[usuarioconciliacion];
										}

										$sqlLeeCuotas="SELECT * FROM cuoacuerdosusimra WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
										$resultLeeCuotas = $dbh->prepare($sqlLeeCuotas);
										if ($resultLeeCuotas->execute(array(':cuit' => $cuiacu, ':nroacuerdo' => $nroacu, ':nrocuota' => $nrocuo)))
										{
											foreach ($resultLeeCuotas as $cuotas)
											{
												if($cuotas[montopagada]!=0.00)
												{
													$importeboletasacu=$importeboletasacu+$cuotas[montopagada];
													$totalboletasacu++;
												}
											}
										}
									
										$sqlAjustaConCuota="UPDATE conciliacuotasusimra SET estadoconciliacion = :estadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota and cuentaboleta = :cuentaboleta and cuentaremitosuelto = :cuentaremitosuelto and fecharemitosuelto = :fecharemitosuelto and nroremitosuelto = :nroremitosuelto";
										$resultAjustaConCuota = $dbh->prepare($sqlAjustaConCuota);
										if ($resultAjustaConCuota->execute(array(':cuit' => $cuiacu, ':nroacuerdo' => $nroacu, ':nrocuota' => $nrocuo, ':cuentaboleta' => $ctabolacu, ':cuentaremitosuelto' => $ctarsuacu, ':fecharemitosuelto' => $fecrsuacu, ':nroremitosuelto' => $nrorsuacu, ':estadoconciliacion' => $estconacu, ':fechaconciliacion' => $fecconacu, ':usuarioconciliacion' => $usuconacu)))
										{
										}
									}
								}

								$sqlLeeBoletasApo="SELECT * FROM conciliapagosusimra WHERE cuentaboleta = :cuentaboleta and cuentaremitosuelto = :cuentaremitosuelto and fecharemitosuelto = :fecharemitosuelto and nroremitosuelto = :nroremitosuelto ";
								$resultLeeBoletasApo = $dbh->prepare($sqlLeeBoletasApo);
								if ($resultLeeBoletasApo->execute(array(':cuentaboleta' => $ajustacuenta, ':cuentaremitosuelto ' => $ajustacuentaremitosuelto, ':fecharemitosuelto' => $ajustafecharemitosuelto, ':nroremitosuelto' => $ajustanroremitosuelto)))
								{
									//echo "LEE CONCILIAPAGOS"; echo "<br>";
									foreach ($resultLeeBoletasApo as $aportes)
									{
										$cuiapo=$aportes[cuit];
										$mesapo=$aportes[mespago];
										$anoapo=$aportes[anopago];
										$nroapo=$aportes[nropago];
										$ctabolapo=$aportes[cuentaboleta];
										$ctarsuapo=$aportes[cuentaremitosuelto];
										$fecrsuapo=$aportes[fecharemitosuelto];
										$nrorsuapo=$aportes[nroremitosuelto];
										if($aportes[estadoconciliacion]==0)
										{
											$estconapo=1;
											$fecconapo=date("Y-m-d H:m:s");
											$usuconapo=$_SESSION['usuario'];
										}
										else
										{
											$estconapo=$aportes[estadoconciliacion];
											$fecconapo=$aportes[fechaconciliacion];
											$usuconapo=$aportes[usuarioconciliacion];
										}

										//TODO: Aca iria el sqlLeePagos cuando se sume al sistema todo el modulo de aportes
										//$sqlLeePagos="SELECT * FROM xxxxxxxxxxxx WHERE cuit = :cuit and mespago = :mespago and anopago = :anopago and nropago = :nropago";
										//$resultLeePagos = $dbh->prepare($sqlLeePagos);
										//if ($resultLeePagos->execute(array(':cuit' => $cuiapo, ':mespago' => $mesapo, ':anopago' => $anoapo, ':nropago' => $nroapo)))
										//{
											//	foreach ($resultLeePagos as $pagos)
											//	{
											//		if($pagos[]!=0.00)
											//		{
											//			$importeboletasapo=$importeboletasapo+$pagos[];
											//		 	$importeboletasrec=$importeboletasrec+$pagos[];
											//			$importeboletasvar=$importeboletasvar+$pagos[];
											//			$importeboletaspag=$importeboletaspag+$pagos[];
											//			$totalboletaspag++;
											//		}
											//	}
										//}

										$sqlAjustaConPagos="UPDATE conciliapagosusimra SET estadoconciliacion = :estadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion WHERE cuit = :cuit and mespago = :mespago and anopago = :anopago and nropago = :nropago and cuentaboleta = :cuentaboleta and cuentaremitosuelto = :cuentaremitosuelto and fecharemitosuelto = :fecharemitosuelto nroremitosuelto = :nroremitosuelto";
										$resultAjustaConPagos = $dbh->prepare($sqlAjustaConPagos);
										if ($resultAjustaConPagos->execute(array(':cuit' => $cuiapo, ':mespago' => $mesapo, ':anopago' => $anoapo, ':nropago' => $nroapo, ':cuentaboleta' => $ctabolapo, ':cuentaremitosuelto' => $ctarsuapo, ':fecharemitosuelto' => $fecrsuapo, ':nroremitosuelto' => $nrorsuapo, ':estadoconciliacion' => $estconapo, ':fechaconciliacion' => $fecconapo, ':usuarioconciliacion' => $usuconapo)))
										{
										}
									}
								}

								$importeboletasbru=$importeboletasacu+$importeboletaspag;
								$totalboletas=$totalboletasacu+$totalboletaspag;

								$sqlAjustaRemitosSueltos="UPDATE remitossueltosusimra SET importeboletasaporte = :importeboletasaporte, importeboletasrecargo = :importeboletasrecargo, importeboletasvarios = :importeboletasvarios, importeboletaspagos = :importeboletaspagos, importeboletascuotas = :importeboletascuotas, importeboletasbruto = :importeboletasbruto, cantidadboletas = :cantidadboletas WHERE codigocuenta = :codigocuenta and fecharemito = :fecharemito and nroremito = :nroremito and estadoconciliacion = :estadoconciliacion";
								$resultAjustaRemitosSueltos = $dbh->prepare($sqlAjustaRemitosSueltos);
								if ($resultAjustaRemitosSueltos ->execute(array(':importeboletasaporte' => $importeboletasapo, ':importeboletasrecargo' => $importeboletasrec, ':importeboletasvarios' => $importeboletasvar, ':importeboletaspagos' => $importeboletaspag, ':importeboletascuotas' => $importeboletasacu, ':importeboletasbruto' => $importeboletasbru, ':cantidadboletas' => $totalboletas, ':codigocuenta' => $ajustacuentaremitosuelto, ':fecharemito' => $ajustafecharemitosuelto, ':nroremito' => $ajustanroremitosuelto, ':estadoconciliacion' => $ajusteestadoconciliacion)))
								{
										//echo "AJUSTA REMITOSSUELTOS"; echo "<br>";
								}
							} //Cierre del FOREACH de Busca Remito Suelto
						} //Cierre del IF de Busca Remito Suelto
					} //Cierre del ELSE de Comprobante Remesa
				} //Cierre del FOREACH de Lee Comprobante Origen
			} //Cierre del IF de Lee Comprobante Origen
		} //Cierre del FOREACH de Busca Resumen
	} //Cierre del IF de Busca Resumen
	
	$dbh->commit();
	$pagina = "conciliaNuevos.php";
	Header("Location: $pagina");

}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title></head>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
}
</style>
<body bgcolor="#B2A274">
</body>
</html>