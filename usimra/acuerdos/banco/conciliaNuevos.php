<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fechaCargada=$_GET['fecEmision'];
$fechaEmision=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$fechaEmision=substr($fechaCargada, 0, 4).substr($fechaCargada, 5, 2).substr($fechaCargada, 8, 2);
$fechaconciliacion = date("Y-m-d H:i:s");
$usuarioconciliacion = $_SESSION['usuario'];

//echo $fechaEmision;

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

	print ("<table width=769 border=1 align=center>");
	print ("<tr>");
	print ("<td width=769><div align=center class=Estilo1>Resultados de la Conciliacion</div></td>");
	print ("</tr>");
	print ("</table>");

	$sqlControlConciliado="SELECT COUNT(*) FROM resumenusimra WHERE fechaemision = $fechaEmision AND estadoconciliacion = 0";
	$sqlLeeResumen="SELECT * FROM resumenusimra WHERE fechaemision = $fechaEmision AND estadoconciliacion = 0 ORDER BY codigocuenta, fechaemision, nroordenimputacion, fechaimputacion";

	$resultControlConciliado = $dbh->query($sqlControlConciliado);

	if (!$resultControlConciliado)
	{
		print ("<p>&nbsp;</p>\n");
		print ("<table width=769 border=1 align=center>");
		print ("<tr>");
		print ("<td width=769><div align=center class=Estilo1>Error en la consulta de RESUMENUSIMRA. Comuniquese con el Depto. de Sistemas.</div></td>");
		print ("</tr>");
		print ("</table>");
	}
	else
	{
		//Verifica si hay registros a conciliar
		if ($resultControlConciliado->fetchColumn()==0)
		{
			$hayimputacion=0;
			
			print ("<p>&nbsp;</p>\n");
			print ("<table width=769 border=1 align=center>");
			print ("<tr>");
			print ("<td width=769><div align=center class=Estilo1>No hay imputaciones que deban ser conciliadas.</div></td>");
			print ("</tr>");
			print ("</table>");
		}
		else
		{
			$hayimputacion=1;

    		$resultLeeResumen = $dbh->query($sqlLeeResumen);
    		if (!$resultLeeResumen)
			{
				print ("<p>&nbsp;</p>\n");
				print ("<table width=769 border=1 align=center>");
				print ("<tr>");
				print ("<td width=769><div align=center class=Estilo1>Error en la consulta de Imputaciones a conciliar. Comuniquese con el Depto. de Sistemas.</div></td>");
				print ("</tr>");
				print ("</table>");
			}
			else
			{
				set_time_limit(0);
				print ("<table width=769 border=1 align=center>");
				print ("<tr>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Cuenta</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Emision Resumen</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Orden</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Fecha</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Importe</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Tipo</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Estado</font></strong></div></td>");
				print ("</tr>");

				foreach ($resultLeeResumen as $resumen)
				{
					print ("<tr>");
					print ("<td><div align=center><font size=1 face=Verdana>".$resumen[codigocuenta]."</font></div></td>");
					print ("<td><div align=center><font size=1 face=Verdana>".invertirFecha($resumen[fechaemision])."</font></div></td>");
					print ("<td><div align=center><font size=1 face=Verdana>".$resumen[nroordenimputacion]."</font></div></td>");
					print ("<td><div align=center><font size=1 face=Verdana>".invertirFecha($resumen[fechaimputacion])."</font></div></td>");
					print ("<td><div align=center><font size=1 face=Verdana>".$resumen[importeimputado]."</font></div></td>");
					if($resumen[tipoimputacion]=='C')
					{
						print ("<td><div align=center><font size=1 face=Verdana>Credito</font></div></td>");

						if($resumen[estadoconciliacion]==0)
						{
							$remesaencontrada=0;
							for($i=0;$i<60;$i++)
							{
								$cuenta=$resumen[codigocuenta];
								$fechaemi=$resumen[fechaemision];
								$orden=$resumen[nroordenimputacion];
								$fechaimputacion = $resumen[fechaimputacion];
								$fechabuscada = date("Y-m-d", strtotime($fechaimputacion."-".$i." day"));
								//echo "Fecha Imputacion: ".$fechaimputacion." - Dia: ".$i." - Fecha Buscada: ".$fechabuscada." - Importe Imputado: ".round($resumen[importeimputado],2); echo "<br>";
								$estado=$resumen[estadoconciliacion];
								$importebuscado=0.00;

								if($remesaencontrada==0)
								{
									$sqlBuscaRemesa="SELECT * FROM remesasusimra WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and estadoconciliacion = :estadoconciliacion";
									$resultBuscaRemesa = $dbh->prepare($sqlBuscaRemesa);
									if ($resultBuscaRemesa->execute(array(':codigocuenta' => $cuenta, ':fecharemesa' => $fechabuscada, ':estadoconciliacion' => $estado)))
									{
										$indiceremesa=0;
										
										foreach ($resultBuscaRemesa as $remesas)
										{
											if($remesaencontrada==0)
											{
												$importebuscado=round($remesas[importebruto],2);
												if(round($resumen[importeimputado],2) == round($importebuscado,2))
												{
													$arrayremesa[$indiceremesa]=$remesas[nroremesa];
													$arraysistema[$indiceremesa]=$remesas[sistemaremesa];
													$remesaencontrada=1;
													$fechaencontrada=$fechabuscada;
												}
//												else
//												{
//													$arrayremesa[$indiceremesa]=$remesas[nroremesa];
//													$arraysistema[$indiceremesa]=$remesas[sistemaremesa];
//													$indiceremesa++;
//												}
												//echo "Fecha Imputacion 1: ".$fechaimputacion." - Importe Buscado: ".$importebuscado." - Fecha Encontrada 1: ".$fechaencontrada; echo "<br>";
											}
										}
									}
								}
								else
								{
									$i=60;
								}
							}

							//echo "Fecha Imputacion 2: ".$fechaimputacion." - Fecha Encontrada 2: ".$fechaencontrada; echo "<br>";

							$totalconciliadas=0;

							for($z=0;$z<=$indiceremesa;$z++)
							{
								$cuentaremesa=$cuenta;
								$sistemaremesa=$arraysistema[$z];
								$fecharemesa=$fechaencontrada;
								$nroremesa=$arrayremesa[$z];
								$estadoconciliacion=$estado;

								$sqlLeeRemesa="SELECT * FROM remesasusimra WHERE codigocuenta = :codigocuenta and sistemaremesa = :sistemaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion";
								$resultLeeRemesa = $dbh->prepare($sqlLeeRemesa);
								if ($resultLeeRemesa->execute(array(':codigocuenta' => $cuentaremesa, ':sistemaremesa' => $sistemaremesa, ':fecharemesa' => $fecharemesa, ':nroremesa' => $nroremesa, ':estadoconciliacion' => $estadoconciliacion)))
								{
									foreach ($resultLeeRemesa as $remesa)
									{
										$remesaimporte=round($remesa[importebruto],2);
										$remitosencontrados=0;
										//echo "Cuenta ".$remesa[codigocuenta]." Sistema ".$remesa[sistemaremesa]." Fecha ".$remesa[fecharemesa]." Nro ".$remesa[nroremesa]." Importe ".$remesa[importebruto].""; echo "<br>";
										$sqlLeeRemitos="SELECT * FROM remitosremesasusimra WHERE codigocuenta = :codigocuenta and sistemaremesa = :sistemaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion";
										$resultLeeRemitos = $dbh->prepare($sqlLeeRemitos);
										if ($resultLeeRemitos->execute(array(':codigocuenta' => $cuentaremesa, ':sistemaremesa' => $sistemaremesa, ':fecharemesa' => $fecharemesa, ':nroremesa' => $nroremesa, ':estadoconciliacion' => $estadoconciliacion)))
										{
											$totalnetoremitos=0.00;
											foreach ($resultLeeRemitos as $remitos)
												$totalnetoremitos=$totalnetoremitos+round($remitos[importebruto],2);
										}

										//echo " Total Remitos ".$totalnetoremitos.""; echo "<br>";

										if(round($remesaimporte,2)==round($totalnetoremitos,2))
										{
											//echo " Total Remitos ".$totalnetoremitos.""; echo "<br>";
											$remitosencontrados=1;
										}
										else
											$remitosencontrados=0;

										if($remitosencontrados==1)
											$totalconciliadas++;
									}
								}
							}

							if(($indiceremesa+1)==$totalconciliadas)
							{
								$concilia=1;
								//Actualiza REMITOSREMESASUSIMRA
								$sqlActualizaRemitos="UPDATE remitosremesasusimra SET estadoconciliacion = :nuevoestadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion, fechaacreditacion = :fechaacreditacion WHERE codigocuenta = :codigocuenta and sistemaremesa = :sistemaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion";
								$resultActualizaRemitos = $dbh->prepare($sqlActualizaRemitos);
								if ($resultActualizaRemitos->execute(array(':codigocuenta' => $cuentaremesa, ':sistemaremesa' => $sistemaremesa, ':fecharemesa' => $fecharemesa, ':nroremesa' => $nroremesa, ':estadoconciliacion' => $estadoconciliacion, ':nuevoestadoconciliacion' => $concilia, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion, ':fechaacreditacion' => $fechaimputacion)))
								{
									//echo "ACTUALIZA REMITOSREMESA"; echo "<br>";
								}

								//Actualiza CONCILIACUOTASUSIMRA
								$sqlActConciliaCuotas="UPDATE conciliacuotasusimra SET estadoconciliacion = :nuevoestadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion WHERE cuentaboleta = :cuentaboleta and cuentaremesa = :cuentaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion";
								$resultActConciliaCuotas = $dbh->prepare($sqlActConciliaCuotas);
								if ($resultActConciliaCuotas->execute(array(':cuentaboleta' => $cuenta, ':cuentaremesa' => $cuentaremesa, ':fecharemesa' => $fecharemesa, ':nroremesa' => $nroremesa, ':estadoconciliacion' => $estadoconciliacion, ':nuevoestadoconciliacion' => $concilia, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
								{
									//echo "ACTUALIZA CONCILIACUOTAS"; echo "<br>";
								}

								//Actualiza CONCILIAPAGOSUSIMRA
								$sqlActConciliaPagos="UPDATE conciliapagosusimra SET estadoconciliacion = :nuevoestadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion WHERE cuentaboleta = :cuentaboleta and cuentaremesa = :cuentaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion";
								$resultActConciliaPagos = $dbh->prepare($sqlActConciliaPagos);
								if ($resultActConciliaPagos->execute(array(':cuentaboleta' => $cuenta, ':cuentaremesa' => $cuentaremesa, ':fecharemesa' => $fecharemesa, ':nroremesa' => $nroremesa, ':estadoconciliacion' => $estadoconciliacion, ':nuevoestadoconciliacion' => $concilia, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
								{
									//echo "ACTUALIZA CONCILIAPAGOS"; echo "<br>";
								}

								//Actualiza REMESASUSIMRA
								$sqlActualizaRemesa="UPDATE remesasusimra SET estadoconciliacion = :nuevoestadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion, fechaacreditacion = :fechaacreditacion WHERE codigocuenta = :codigocuenta and sistemaremesa = :sistemaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion";
								$resultActualizaRemesa = $dbh->prepare($sqlActualizaRemesa);
								if ($resultActualizaRemesa->execute(array(':codigocuenta' => $cuentaremesa, ':sistemaremesa' => $sistemaremesa, ':fecharemesa' => $fecharemesa, ':nroremesa' => $nroremesa, ':estadoconciliacion' => $estadoconciliacion, ':nuevoestadoconciliacion' => $concilia, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion, ':fechaacreditacion' => $fechaimputacion)))
								{
									//echo "ACTUALIZA REMESA"; echo "<br>";
								}

								//Agrega ORIGENCOMPROBANTEUSIMRA
								$comprobanteorigen="Remesa";
								$sqlAddOrigen="INSERT INTO origencomprobanteusimra (codigocuenta, fechaemision, nroordenimputacion, sistemacomprobante, fechacomprobante, nrocomprobante, comprobante) VALUES (:codigocuenta, :fechaemision, :nroordenimputacion, :sistemacomprobante, :fechacomprobante, :nrocomprobante, :comprobante)";
								$resultAddOrigen = $dbh->prepare($sqlAddOrigen);
								if ($resultAddOrigen->execute(array(':codigocuenta' => $cuenta, ':fechaemision' => $fechaemi, ':nroordenimputacion' => $orden, ':sistemacomprobante' => $sistemaremesa, ':fechacomprobante' => $fecharemesa, ':nrocomprobante' => $nroremesa, ':comprobante' => $comprobanteorigen)))
								{
									//echo "AGREGA ORIGENCOMPROBANTE"; echo "<br>";
								}

								//Actualiza RESUMENUSIMRA
								$sqlActualizaResumen="UPDATE resumenusimra SET estadoconciliacion = :nuevoestadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion WHERE codigocuenta = :codigocuenta and fechaemision = :fechaemision and nroordenimputacion = :nroordenimputacion";
								$resultActualizaResumen = $dbh->prepare($sqlActualizaResumen);
								if ($resultActualizaResumen->execute(array(':codigocuenta' => $cuenta, ':fechaemision' => $fechaemi, ':nroordenimputacion' => $orden, ':nuevoestadoconciliacion' => $concilia, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
								{
									//echo "ACTUALIZA RESUMEN"; echo "<br>";
								}
								print ("<td><div align=center><font size=1 face=Verdana>Conciliado</font></div></td>");
								$remesasconciliadas=1;
							}
							else
							{
								$remesasconciliadas=0;
							}
								
							if($remesaencontrada==0)
							{
								$remitosueltoencontrado=0;
								$cuentasuelto=$resumen[codigocuenta];
								$fechaemisuelto=$resumen[fechaemision];
								$ordensuelto=$resumen[nroordenimputacion];
								$fechaimputacionsuelto=$resumen[fechaimputacion];
								$estadosuelto=$resumen[estadoconciliacion];
								$importebuscadosuelto=0.00;

								if($remitosueltoencontrado==0)
								{
									$sqlBuscaRemitoSuelto="SELECT * FROM remitossueltosusimra WHERE codigocuenta = :codigocuentasuelto and fecharemito = :fecharemitosuelto and estadoconciliacion = :estadoconciliacionsuelto";
									$resultBuscaRemitoSuelto = $dbh->prepare($sqlBuscaRemitoSuelto);
									if ($resultBuscaRemitoSuelto->execute(array(':codigocuentasuelto' => $cuentasuelto, ':fecharemitosuelto' => $fechaimputacionsuelto, ':estadoconciliacionsuelto' => $estadosuelto)))
									{
										foreach ($resultBuscaRemitoSuelto as $remitossueltos)
										{
											if($remitosueltoencontrado==0)
											{
												$importebuscadosuelto=round($remitossueltos[importebruto],2);
												if(round($resumen[importeimputado],2) == round($importebuscadosuelto,2))
												{
													$remitosueltoencontrado=1;
													$sistemaremitosuelto=$remitossueltos[sistemaremito];
													$nroremitosuelto=$remitossueltos[nroremito];
													$conciliasuelto=1;

													//Actualiza REMITOSSUELTOSUSIMRA
													$sqlActualizaRemitosSueltos="UPDATE remitossueltosusimra SET estadoconciliacion = :nuevoestadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion, fechaacreditacion = :fechaacreditacion WHERE codigocuenta = :codigocuentasuelto and sistemaremito = :sistemaremito and fecharemito = :fecharemitosuelto and nroremito = :nroremitosuelto and estadoconciliacion = :estadoconciliacionsuelto";
													$resultActualizaRemitosSueltos = $dbh->prepare($sqlActualizaRemitosSueltos);
													if ($resultActualizaRemitosSueltos->execute(array(':codigocuentasuelto' => $cuentasuelto, ':sistemaremito' => $sistemaremitosuelto, ':fecharemitosuelto' => $fechaimputacionsuelto, ':nroremitosuelto' => $nroremitosuelto, ':estadoconciliacionsuelto' => $estadosuelto, ':nuevoestadoconciliacion' => $conciliasuelto, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion, ':fechaacreditacion' => $fechaimputacionsuelto)))
													{
														//echo "ACTUALIZA REMITOSSUELTOS"; echo "<br>";
													}

													//Actualiza CONCILIACUOTASUSIMRA
													$sqlActConciliaCuotasSueltos="UPDATE conciliacuotasusimra SET estadoconciliacion = :nuevoestadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion WHERE cuentaboleta = :cuentaboletasuelto and cuentaremitosuelto = :cuentaremitosuelto and fecharemitosuelto = :fecharemitosuelto and nroremitosuelto = :nroremitosuelto and estadoconciliacion = :estadoconciliacionsuelto";
													$resultActConciliaCuotasSueltos = $dbh->prepare($sqlActConciliaCuotasSueltos);
													if ($resultActConciliaCuotasSueltos->execute(array(':cuentaboletasuelto' => $cuentasuelto, ':cuentaremitosuelto' => $cuentasuelto, ':fecharemitosuelto' => $fechaimputacionsuelto, ':nroremitosuelto' => $nroremitosuelto, ':estadoconciliacionsuelto' => $estadosuelto, ':nuevoestadoconciliacion' => $conciliasuelto, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
													{
														//echo "ACTUALIZA CONCILIACUOTAS"; echo "<br>";
													}

													//Actualiza CONCILIAPAGOSUSIMRA
													$sqlActConciliaPagosSueltos="UPDATE conciliapagosusimra SET estadoconciliacion = :nuevoestadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion WHERE cuentaboleta = :cuentaboletasuelto and cuentaremitosuelto = :cuentaremitosuelto and fecharemitosuelto = :fecharemitosuelto and nroremitosuelto = :nroremitosuelto and estadoconciliacion = :estadoconciliacionsuelto";
													$resultActConciliaPagosSueltos = $dbh->prepare($sqlActConciliaPagosSueltos);
													if ($resultActConciliaPagosSueltos->execute(array(':cuentaboletasuelto' => $cuentasuelto, ':cuentaremitosuelto' => $cuentasuelto, ':fecharemitosuelto' => $fechaimputacionsuelto, ':nroremitosuelto' => $nroremitosuelto, ':estadoconciliacionsuelto' => $estadosuelto, ':nuevoestadoconciliacion' => $conciliasuelto, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
													{
														//echo "ACTUALIZA CONCILIAPAGOS"; echo "<br>";
													}

													//Agrega ORIGENCOMPROBANTEUSIMRA
													$comprobanteorigensuelto="Remito Suelto";
													$sqlAddOrigenSuelto="INSERT INTO origencomprobanteusimra (codigocuenta, fechaemision, nroordenimputacion, sistemacomprobante, fechacomprobante, nrocomprobante, comprobante) VALUES (:codigocuenta, :fechaemision, :nroordenimputacion, :sistemacomprobante, :fechacomprobante, :nrocomprobante, :comprobante)";
													$resultAddOrigenSuelto = $dbh->prepare($sqlAddOrigenSuelto);
													if ($resultAddOrigenSuelto->execute(array(':codigocuenta' => $cuentasuelto, ':fechaemision' => $fechaemi, ':nroordenimputacion' => $orden, ':sistemacomprobante' => $sistemaremitosuelto, ':fechacomprobante' => $fechaimputacionsuelto, ':nrocomprobante' => $nroremitosuelto, ':comprobante' => $comprobanteorigensuelto)))
													{
														//echo "AGREGA ORIGENCOMPROBANTE"; echo "<br>";
													}

													//Actualiza RESUMENUSIMRA
													$sqlActualizaResumenSuelto="UPDATE resumenusimra SET estadoconciliacion = :nuevoestadoconciliacion, fechaconciliacion = :fechaconciliacion, usuarioconciliacion = :usuarioconciliacion WHERE codigocuenta = :codigocuenta and fechaemision = :fechaemision and nroordenimputacion = :nroordenimputacion";
													$resultActualizaResumenSuelto = $dbh->prepare($sqlActualizaResumenSuelto);
													if ($resultActualizaResumenSuelto->execute(array(':codigocuenta' => $cuentasuelto, ':fechaemision' => $fechaemi, ':nroordenimputacion' => $orden, ':nuevoestadoconciliacion' => $conciliasuelto, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
													{
														//echo "ACTUALIZA RESUMEN"; echo "<br>";
													}
													print ("<td><div align=center><font size=1 face=Verdana>Conciliado</font></div></td>");
												}
											}
										}
									}
								}

								if($remitosueltoencontrado==0)
									print ("<td><div align=center><font size=1 face=Verdana>No Conciliado - Diferencia Remesa/Rto. Suelto</font></div></td>");
							}
							else
							{
								if($remesasconciliadas==0)
									print ("<td><div align=center><font size=1 face=Verdana>No Conciliado - Diferencia en Remitos de la Remesa</font></div></td>");
							}
						}
						

						$ajusteestadoconciliacion = 1;

						$sqlBuscaResumen="SELECT * FROM resumenusimra WHERE estadoconciliacion = :estadoconciliacion and fechaconciliacion = :fechaconciliacion and usuarioconciliacion = :usuarioconciliacion ORDER BY codigocuenta, fechaemision, nroordenimputacion, fechaimputacion";
						$resultBuscaResumen = $dbh->prepare($sqlBuscaResumen);
						if ($resultBuscaResumen->execute(array(':estadoconciliacion' => $ajusteestadoconciliacion, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
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
										$ajustacuentaremesa=$origen[codigocuenta];
										$ajustafecharemesa=$origen[fechacomprobante];
										$ajustanroremesa=$origen[nrocomprobante];

										$sqlBuscaRemitos="SELECT * FROM remitosremesasusimra WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion and fechaconciliacion = :fechaconciliacion and usuarioconciliacion = :usuarioconciliacion ORDER BY codigocuenta, sistemaremesa, fecharemesa, nroremesa, nroremito";
										$resultBuscaRemitos = $dbh->prepare($sqlBuscaRemitos);
										if ($resultBuscaRemitos->execute(array(':codigocuenta' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':estadoconciliacion' => $ajusteestadoconciliacion, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
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

												$sqlLeeBoletasAcu="SELECT * FROM conciliacuotasusimra WHERE cuentaboleta = :cuentaboleta and cuentaremesa = :cuentaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and nroremitoremesa = :nroremito and estadoconciliacion = :estadoconciliacion";
												$resultLeeBoletasAcu = $dbh->prepare($sqlLeeBoletasAcu);
												if ($resultLeeBoletasAcu->execute(array(':cuentaboleta' => $ajustacuenta, ':cuentaremesa' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':nroremito' => $ajustanroremito, ':estadoconciliacion' => $ajusteestadoconciliacion)))
												{
													//echo "LEE CONCILIACUOTAS"; echo "<br>";
													foreach ($resultLeeBoletasAcu as $acuerdos)
													{
														$cuiacu=$acuerdos[cuit];
														$nroacu=$acuerdos[nroacuerdo];
														$nrocuo=$acuerdos[nrocuota];
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
													}
												}

												$sqlLeeBoletasApo="SELECT * FROM conciliapagosusimra WHERE cuentaboleta = :cuentaboleta and cuentaremesa = :cuentaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa and nroremitoremesa = :nroremito and estadoconciliacion = :estadoconciliacion";
												$resultLeeBoletasApo = $dbh->prepare($sqlLeeBoletasApo);
												if ($resultLeeBoletasApo->execute(array(':cuentaboleta' => $ajustacuenta, ':cuentaremesa' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':nroremito' => $ajustanroremito, ':estadoconciliacion' => $ajusteestadoconciliacion)))
												{
													//echo "LEE CONCILIAPAGOS"; echo "<br>";
													foreach ($resultLeeBoletasApo as $aportes)
													{
														$cuiapo=$aportes[cuit];
														$mesapo=$aportes[mespago];
														$anoapo=$aportes[anopago];
														$nroapo=$aportes[nropago];

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
													}
												}

												$importeboletasbru=$importeboletasacu+$importeboletaspag;
												$totalboletas=$totalboletasacu+$totalboletaspag;

												$sqlAjustaRemitos="UPDATE remitosremesasusimra SET importeboletasaporte = :importeboletasaporte, importeboletasrecargo = :importeboletasrecargo, importeboletasvarios = :importeboletasvarios, importeboletaspagos = :importeboletaspagos, importeboletascuotas = :importeboletascuotas, importeboletasbruto = :importeboletasbruto, cantidadboletas = :cantidadboletas WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and nroremesa = :nroremesa and nroremito = :nroremito and estadoconciliacion = :estadoconciliacion and fechaconciliacion = :fechaconciliacion and usuarioconciliacion = :usuarioconciliacion";
												$resultAjustaRemitos = $dbh->prepare($sqlAjustaRemitos);
												if ($resultAjustaRemitos->execute(array(':importeboletasaporte' => $importeboletasapo, ':importeboletasrecargo' => $importeboletasrec, ':importeboletasvarios' => $importeboletasvar, ':importeboletaspagos' => $importeboletaspag, ':importeboletascuotas' => $importeboletasacu, ':importeboletasbruto' => $importeboletasbru, ':cantidadboletas' => $totalboletas, ':codigocuenta' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':nroremito' => $ajustanroremito, ':estadoconciliacion' => $ajusteestadoconciliacion, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
												{
													//echo "AJUSTA REMITOSREMESA"; echo "<br>";
												}
											}
										}

										$sqlBuscaRemesa="SELECT * FROM remesasusimra WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion and fechaconciliacion = :fechaconciliacion and usuarioconciliacion = :usuarioconciliacion ORDER BY codigocuenta, sistemaremesa, fecharemesa, nroremesa";
										$resultBuscaRemesa = $dbh->prepare($sqlBuscaRemesa);
										if ($resultBuscaRemesa->execute(array(':codigocuenta' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':estadoconciliacion' => $ajusteestadoconciliacion, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
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

												$sqlBuscaRemitos="SELECT * FROM remitosremesasusimra WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion and fechaconciliacion = :fechaconciliacion and usuarioconciliacion = :usuarioconciliacion ORDER BY codigocuenta, sistemaremesa, fecharemesa, nroremesa, nroremito";
												$resultBuscaRemitos = $dbh->prepare($sqlBuscaRemitos);
												if ($resultBuscaRemitos->execute(array(':codigocuenta' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':estadoconciliacion' => $ajusteestadoconciliacion, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
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
												
												$sqlAjustaRemesa="UPDATE remesasusimra SET importebrutoremitos = :importebrutoremitos, importecomisionesremitos = :importecomisionesremitos, importenetoremitos = :importenetoremitos, importeboletasaporte = :importeboletasaporte, importeboletasrecargo = :importeboletasrecargo, importeboletasvarios = :importeboletasvarios, importeboletaspagos = :importeboletaspagos, importeboletascuotas = :importeboletascuotas, importeboletasbruto = :importeboletasbruto, cantidadboletas = :cantidadboletas WHERE codigocuenta = :codigocuenta and fecharemesa = :fecharemesa and nroremesa = :nroremesa and estadoconciliacion = :estadoconciliacion and fechaconciliacion = :fechaconciliacion and usuarioconciliacion = :usuarioconciliacion";
												$resultAjustaRemesa = $dbh->prepare($sqlAjustaRemesa);
												if ($resultAjustaRemesa->execute(array(':importebrutoremitos' => $totalremitosbruto, ':importecomisionesremitos' => $totalremitoscomision, ':importenetoremitos' => $totalremitosneto, ':importeboletasaporte' => $importeremitosapo, ':importeboletasrecargo' => $importeremitosrec, ':importeboletasvarios' => $importeremitosvar, ':importeboletaspagos' => $importeremitospag, ':importeboletascuotas' => $importeremitosacu, ':importeboletasbruto' => $importeremitosbru, ':cantidadboletas' => $totalremitosboletas, ':codigocuenta' => $ajustacuentaremesa, ':fecharemesa' => $ajustafecharemesa, ':nroremesa' => $ajustanroremesa, ':estadoconciliacion' => $ajusteestadoconciliacion, ':fechaconciliacion' => $fechaconciliacion, ':usuarioconciliacion' => $usuarioconciliacion)))
												{
													//echo "AJUSTA REMESA"; echo "<br>";
												}
											}
										}
									}
								}
							}
						}
					}
					else
					{
						print ("<td><div align=center><font size=1 face=Verdana>Debito</font></div></td>");
						print ("<td><div align=center><font size=1 face=Verdana>No Conciliado</font></div></td>");
					}
					print ("</tr>");
				}
				print ("</table>");
			}
		}
		
	}
	
	$dbh->commit();
	
	if($hayimputacion==1) { 
	?>
		<p>&nbsp;</p>
		<table width="771" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="257" valign="middle">
		<div align="left">
		<input type="reset" name="volver" value="Volver" onClick="location.href = 'listaAConciliar.php'" align="left"/>
		</div>
		</td>
	    <td width="257" valign="middle">
		<div align="center">
        <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="center">
	    </div>
		</td>
		<td width="257" valign="middle">
		<div align="right">
        <input type="button" name="ajustar" value="Ajustar Conciliados" onClick="location.href = 'conciliacionBancaria.php'" align="right">
	    </div>
		</td>
		</tr>
		</table>
	<?php
	}
	else
	{ ?>
		<p>&nbsp;</p>
		<table width="771" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="771" valign="middle"><input type="reset" name="volver" value="Volver" onClick="location.href = 'documentosBancarios.php'" align="center"/>
		</td>
		</tr>
		</table>
	<?php
	}

}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
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