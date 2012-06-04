<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");
$fechacancelacion = date("Y-m-d H:m:s");
$usuariocancelacion = $_SESSION['usuario'];

//conexion y creacion de transaccion.
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	echo "$hostname"; echo "<br>";
	echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	echo 'Connected to database<br/>';
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$sqlControlImputar="SELECT COUNT(*) FROM banacuerdosospim WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";
	$sqlLeeAImputar="SELECT * FROM banacuerdosospim WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";

	$resultControlImputar = $dbh->query($sqlControlImputar);

	if (!$resultControlImputar)
    	print "<p>Error en la consulta de BANACUERDOSOSPIM.</p>\n";
	else
	{
		//Verifica si hay registros a validar
		if ($resultControlImputar->fetchColumn()==0)
    		print "<p>No hay pagos que deban ser imputados.</p>\n";
		else
		{
    		$resultLeeAImputar = $dbh->query($sqlLeeAImputar);
    		if (!$resultLeeAImputar)
      		  	print "<p>Error en la consulta de BANACUERDOSOSPIM.</p>\n";
			else
			{
        		foreach ($resultLeeAImputar as $imputar)
				{
            		print "<p>Movimiento: $imputar[nromovimiento] - Sucursal: $imputar[sucursalorigen] - Recaudacion: $imputar[fecharecaudacion] - Acreditacion: $imputar[fechaacreditacion] - Estado: $imputar[estadomovimiento] - Control: $imputar[nrocontrol]</p>\n";

					$controlbanco = $imputar[nrocontrol];
					$estado = $imputar[estadomovimiento];
					$importebanco = $imputar[importe];
					$cuitbanco = $imputar[cuit];
					$recaudabanco = $imputar[fecharecaudacion];
					$acreditabanco = $imputar[fechaacreditacion];
					$codbarrabanco = $imputar[codigobarra];
					
					$sqlBuscaValida="SELECT * FROM validasospim WHERE nrocontrol = :nrocontrol";
					echo $sqlBuscaValida; echo "<br>";
					$resultBuscaValida = $dbh->prepare($sqlBuscaValida);
					$resultBuscaValida->execute(array(':nrocontrol' => $controlbanco));
					if ($resultBuscaValida)
					{
		        		foreach ($resultBuscaValida as $validas)
						{
							print "<p>Boleta ID: $validas[idboleta] - CUIT: $validas[cuit] - Acuerdo: $validas[nroacuerdo] - Cuota: $validas[nrocuota] - Importe: $validas[importe] - Control: $validas[nrocontrol] - Usuario: $validas[usuarioregistro]</p>\n";
							$cuitboleta = $validas[cuit];
							$acuerdo = $validas[nroacuerdo];
							$cuota = $validas[nrocuota];
							$importeboleta = $validas[importe];
							$controlboleta = $validas[nrocontrol];

							if($importebanco=$importeboleta)
							{
								print "<p>Importe Boleta : $importeboleta - Importe Banco: $importebanco</p>\n";

								if($cuitbanco=$cuitboleta)
								{
									print "<p>CUIT Boleta : $cuitboleta - Cuit Banco: $cuitbanco</p>\n";
									$sqlVerificaCuota="SELECT * from cuoacuerdosospim WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
									$resultVerificaCuota = $dbh->prepare($sqlVerificaCuota);
									if ($resultVerificaCuota->execute(array(':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo, ':nrocuota' => $cuota)))
									{
						        		foreach ($resultVerificaCuota as $cuotas)
										{
											$cuitcuota = $cuotas[cuit];
											$acuerdocuota = $cuotas[nroacuerdo];
											$cuotacuota = $cuotas[nrocuota];
											$importecuota = $cuotas[montocuota];
											$pagada = $cuotas[montopagada];
											$cancelacion = $cuotas[tipocancelacion];
											$sistema = $cuotas[sistemacancelacion];
											$boleta = $cuotas[boletaimpresa];

											$puedecancelar = 0;

											if($estado=='R' && $cancelacion==1)
											{
												$tiposcanc = '10';
												$observaci = "Rechazo Electronico-Cheque: ".$cuotas[chequenro]." ".$cuotas[chequebanco]." ".invertirFecha($cuotas[chequefecha]);
												$boletaimp = '0';
												$montopago = '0.00';
												$fechapago = '00000000';
												$fechacanc = '00000000';
												$sistecanc = '';
												$codibarra = '';
												$puedecancelar = 1;
											}

											if($estado=='L' && $cancelacion==1)
											{
												$tiposcanc = $cancelacion;
												$observaci = '';
												$boletaimp = $boleta;
												$montopago = $importebanco;
												$fechapago = $recaudabanco;
												$fechacanc = $fechacancelacion;
												$sistecanc = 'E';
												$codibarra = $codbarrabanco;
												$puedecancelar = 1;
											}

											if($estado=='L' && $cancelacion==3)
											{
												//lee valoresalcobro
												$sqlLeeValorAlCobro = "SELECT * from valoresalcobro where cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
												$resultLeeValorAlCobro = $dbh->prepare($sqlLeeValorAlCobro); 
												if ($resultLeeValorAlCobro->execute(array(':cuit' => $cuitcuota, ':nroacuerdo' => $acuerdocuota, ':nrocuota' => $cuotacuota)))
												{
						        					foreach ($resultLeeValorAlCobro as $valoralcobro)
													{
														$nrocheque = $valoralcobro[chequenroospim];
														$fechaChe = invertirFecha($valoralcobro[chequefechaospim]);
													}

													$tiposcanc = $cancelacion;
													$observaci = "Cancelada cheque OSPIM Nro. ".$nrocheque." de Fecha ".$fechaChe;
													$boletaimp = $boleta;
													$montopago = $importebanco;
													$fechapago = $recaudabanco;
													$fechacanc = $fechacancelacion;
													$sistecanc = 'E';
													$codibarra = $codbarrabanco;
													$puedecancelar = 1;
												}
												else
												{
													$puedecancelar = 0;
												}
											}

											if($estado=='E' && $cancelacion==2)
											{
												$tiposcanc = $cancelacion;
												$observaci = '';
												$boletaimp = $boleta;
												$montopago = $importebanco;
												$fechapago = $recaudabanco;
												$fechacanc = $fechacancelacion;
												$sistecanc = 'E';
												$codibarra = $codbarrabanco;
												$puedecancelar = 1;
											}

											if($estado=='E' && $cancelacion==3)
											{
												//lee valoresalcobro
												$sqlLeeValorAlCobro = "SELECT * from valoresalcobro where cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
												$resultLeeValorAlCobro = $dbh->prepare($sqlLeeValorAlCobro); 
												if ($resultLeeValorAlCobro->execute(array(':cuit' => $cuitcuota, ':nroacuerdo' => $acuerdocuota, ':nrocuota' => $cuotacuota)))
												{
						        					foreach ($resultLeeValorAlCobro as $valoralcobro)
													{
														$nrocheque = $valoralcobro[chequenroospim];
														$fechaChe = invertirFecha($valoralcobro[chequefechaospim]);
													}

													$tiposcanc = $cancelacion;
													$observaci = "Cancelada cheque OSPIM Nro. ".$nrocheque." de Fecha ".$fechaChe;
													$boletaimp = $boleta;
													$montopago = $importebanco;
													$fechapago = $recaudabanco;
													$fechacanc = $fechacancelacion;
													$sistecanc = 'E';
													$codibarra = $codbarrabanco;
													$puedecancelar = 1;
												}
												else
												{
													$puedecancelar = 0;
												}
											}


											$cancelacuota = 0;

											if($importecuota=$importebanco)
											{
												if($cancelacion<='3')
												{
													if($pagada=='0')
													{
														if($sistema=='')
															$cancelacuota = 1;
													}
												}
											}

											if($cancelacuota==1 && $puedecancelar==1)
											{
												print "<p>Estado Mov.: $estado - T. Cancelacion: $tiposcanc - Observ: $observaci - Boleta Imp.: $boletaimp - M. Pagado: $montopago - F. Pago: $fechapago - F. Cancel: $fechacanc - S. Cancel: $sistecanc - C. Barra: $codibarra</p>\n";

												$sqlActualizaCuota="UPDATE cuoacuerdosospim SET tipocancelacion = :tipocancelacion, observaciones = :observaciones, boletaimpresa = :boletaimpresa, montopagada = :montopagada, fechapagada = :fechapagada, fechacancelacion = :fechacancelacion, sistemacancelacion = :sistemacancelacion, codigobarra = :codigobarra, fechaacreditacion = :fechaacreditacion WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
												$resultActualizaCuota = $dbh->prepare($sqlActualizaCuota);
												echo $sqlActualizaCuota; echo "<br>";
												if ($resultActualizaCuota->execute(array(':tipocancelacion' => $tiposcanc, ':observaciones' => $observaci, ':boletaimpresa' => $boletaimp, ':montopagada' => $montopago, ':fechapagada' => $fechapago, ':fechacancelacion' => $fechacanc, ':sistemacancelacion' => $sistecanc, ':codigobarra' => $codibarra, ':fechaacreditacion' => $acreditabanco, ':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo, ':nrocuota' => $cuota)))
												{
													print "<p>Registro Cuota actualizado correctamente.</p>\n";
												}
												else
												{
												    print "<p>Error al actualizar el registro Cuota.</p>\n";
												}

												$sqlActualizaBanco="UPDATE banacuerdosospim SET fechaimputacion = :fechaimputacion, usuarioimputacion = :usuarioimputacion WHERE nrocontrol = :nrocontrol and estadomovimiento = :estadomovimiento";
												$resultActualizaBanco = $dbh->prepare($sqlActualizaBanco);
												echo $sqlActualizaBanco; echo "<br>";
												if ($resultActualizaBanco->execute(array(':fechaimputacion' => $fechacancelacion, ':usuarioimputacion' => $usuariocancelacion, ':nrocontrol' => $controlboleta, ':estadomovimiento' => $estado)))
												{
													print "<p>Registro Banco actualizado correctamente.</p>\n";
												}
												else
												{
													print "<p>Error al actualizar el registro Banco.</p>\n";
												}
											}
										}
									}
								}
							}
						}
					}
        		}
    		}
		}
	}
	
	$dbh->commit();
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>
