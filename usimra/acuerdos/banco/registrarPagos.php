<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/usimra/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");
$fechacancelacion = date("Y-m-d H:m:s");
$usuariocancelacion = $_SESSION['usuario'];

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
	print ("<td width=769><div align=center class=Estilo1>Resultados de la Imputaci&oacute;n de Pagos</div></td>");
	print ("</tr>");
	print ("</table>");

	$sqlControlImputar="SELECT COUNT(*) FROM banacuerdosusimra WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";
	$sqlLeeAImputar="SELECT * FROM banacuerdosusimra WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";

	$resultControlImputar = $dbh->query($sqlControlImputar);

	if (!$resultControlImputar)
	{
		print ("<p>&nbsp;</p>\n");
		print ("<table width=769 border=1 align=center>");
		print ("<tr>");
		print ("<td width=769><div align=center class=Estilo1>Error en la consulta de BANACUERDOSUSIMRA. Comuniquese con el Depto. de Sistemas.</div></td>");
		print ("</tr>");
		print ("</table>");
	}
	else
	{
		//Verifica si hay registros a validar
		if ($resultControlImputar->fetchColumn()==0)
		{
			$haypago=0;
			
			print ("<p>&nbsp;</p>\n");
			print ("<table width=769 border=1 align=center>");
			print ("<tr>");
			print ("<td width=769><div align=center class=Estilo1>No hay Pagos que deban ser Imputados.</div></td>");
			print ("</tr>");
			print ("</table>");
		}
		else
		{
			$haypago=1;

    		$resultLeeAImputar = $dbh->query($sqlLeeAImputar);
    		if (!$resultLeeAImputar)
			{
				print ("<p>&nbsp;</p>\n");
				print ("<table width=769 border=1 align=center>");
				print ("<tr>");
				print ("<td width=769><div align=center class=Estilo1>Error en la consulta de Informacion del Banco. Comuniquese con el Depto. de Sistemas.</div></td>");
				print ("</tr>");
				print ("</table>");
			}
			else
			{
				$cuentaboleta = '2';
				$cuentaremesa = '2';
				$nroremesa = '1';
				$nroremitoremesa='0';
				$cuentaremitosuelto='0';
				$fecharemitosuelto='00000000';
				$nroremitosuelto='0';
				$estadoconciliacion='0';
				$fechaconciliacion='00000000000000';
				$usuarioconciliacion='';
				$fechamodificacion='00000000000000';
				$usuariomodificacion='';

				$totacanc=0.00;
				$cantcanc=0;

				print ("<table width=769 border=1 align=center>");
				print ("<tr>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Codigo de Barra</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>C.U.I.T.</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Acuerdo</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Cuota</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Importe</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Status</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Fecha</font></strong></div></td>");
				print ("</tr>");

        		foreach ($resultLeeAImputar as $imputar)
				{
					$controlbanco = $imputar[nrocontrol];
					$estado = $imputar[estadomovimiento];
					$importebanco = $imputar[importe];
					$cuitbanco = $imputar[cuit];
					$recaudabanco = $imputar[fecharecaudacion];
					$acreditabanco = $imputar[fechaacreditacion];
					$fechabanco = invertirFecha($imputar[fechaacreditacion]);
					$codbarrabanco = $imputar[codigobarra];
					$validadabanco = $imputar[fechavalidacion];
					
					$sqlBuscaValida="SELECT * FROM validasusimra WHERE nrocontrol = :nrocontrol";
					//echo $sqlBuscaValida; echo "<br>";
					$resultBuscaValida = $dbh->prepare($sqlBuscaValida);
					$resultBuscaValida->execute(array(':nrocontrol' => $controlbanco));
					if ($resultBuscaValida)
					{
		        		foreach ($resultBuscaValida as $validas)
						{
							$cuitboleta = $validas[cuit];
							$acuerdo = $validas[nroacuerdo];
							$cuota = $validas[nrocuota];
							$importeboleta = $validas[importe];
							$controlboleta = $validas[nrocontrol];

							print ("<tr>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$codbarrabanco."</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$cuitboleta."</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$acuerdo."</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$cuota."</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$importeboleta."</font></div></td>");

							if($importebanco==$importeboleta)
							{
								if($cuitbanco==$cuitboleta)
								{
									$sqlVerificaCuota="SELECT * from cuoacuerdosusimra WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
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
												$sqlLeeValorAlCobro = "SELECT * from valoresalcobrousimra where cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
												$resultLeeValorAlCobro = $dbh->prepare($sqlLeeValorAlCobro); 
												if ($resultLeeValorAlCobro->execute(array(':cuit' => $cuitcuota, ':nroacuerdo' => $acuerdocuota, ':nrocuota' => $cuotacuota)))
												{
						        					foreach ($resultLeeValorAlCobro as $valoralcobro)
													{
														$nrocheque = $valoralcobro[chequenrousimra];
														$fechaChe = invertirFecha($valoralcobro[chequefechausimra]);
													}

													$tiposcanc = $cancelacion;
													$observaci = "Cancelada cheque USIMRA Nro. ".$nrocheque." de Fecha ".$fechaChe;
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
												if($validadabanco!='00000000000000')
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
												else
												{
													$puedecancelar = 0;
												}
											}

											if($estado=='E' && $cancelacion==3)
											{
												if($validadabanco!='00000000000000')
												{
													//lee valoresalcobro
													$sqlLeeValorAlCobro = "SELECT * from valoresalcobrousimra where cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
													$resultLeeValorAlCobro = $dbh->prepare($sqlLeeValorAlCobro); 
													if ($resultLeeValorAlCobro->execute(array(':cuit' => $cuitcuota, ':nroacuerdo' => $acuerdocuota, ':nrocuota' => $cuotacuota)))
													{
							        					foreach ($resultLeeValorAlCobro as $valoralcobro)
														{
															$nrocheque = $valoralcobro[chequenrousimra];
															$fechaChe = invertirFecha($valoralcobro[chequefechausimra]);
														}

														$tiposcanc = $cancelacion;
														$observaci = "Cancelada cheque USIMRA Nro. ".$nrocheque." de Fecha ".$fechaChe;
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
												else
												{
													$puedecancelar = 0;
												}
											}

											$cancelacuota = 0;

											if($importecuota==$importebanco)
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
												$sqlActualizaCuota="UPDATE cuoacuerdosusimra SET tipocancelacion = :tipocancelacion, observaciones = :observaciones, boletaimpresa = :boletaimpresa, montopagada = :montopagada, fechapagada = :fechapagada, fechacancelacion = :fechacancelacion, sistemacancelacion = :sistemacancelacion, codigobarra = :codigobarra, fechaacreditacion = :fechaacreditacion WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
												$resultActualizaCuota = $dbh->prepare($sqlActualizaCuota);
												//echo $sqlActualizaCuota; echo "<br>";
												if ($resultActualizaCuota->execute(array(':tipocancelacion' => $tiposcanc, ':observaciones' => $observaci, ':boletaimpresa' => $boletaimp, ':montopagada' => $montopago, ':fechapagada' => $fechapago, ':fechacancelacion' => $fechacanc, ':sistemacancelacion' => $sistecanc, ':codigobarra' => $codibarra, ':fechaacreditacion' => $acreditabanco, ':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo, ':nrocuota' => $cuota)))
												{
													if($tiposcanc=='10')
													{
														print ("<td><div align=center><font size=1 face=Verdana>Cheque Rechazado</font></div></td>");
													    print ("<td><div align=center><font size=1 face=Verdana>".$fechabanco."</font></div></td>");
													}
													else
													{
														$sqlLeeRemitosRemesas="SELECT * FROM remitosremesasusimra WHERE codigocuenta = '$cuentaremesa' and sistemaremesa = 'E' and fecharemesa = '$acreditabanco' and nroremesa = '$nroremesa' and nrocontrol = '$controlboleta' and importebruto = '$montopago'";
														$resultLeeRemitosRemesas = $dbh->query($sqlLeeRemitosRemesas);
														foreach ($resultLeeRemitosRemesas as $remitos)
														{
															$nroremitoremesa = $remitos[nroremito];

															$sqlAddConcilia="INSERT INTO conciliacuotasusimra (cuit, nroacuerdo, nrocuota, cuentaboleta, cuentaremesa, fecharemesa, nroremesa, nroremitoremesa, cuentaremitosuelto, fecharemitosuelto, nroremitosuelto, estadoconciliacion, fechaconciliacion, usuarioconciliacion, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion) VALUES ('$cuitboleta','$acuerdo','$cuota','$cuentaboleta','$cuentaremesa','$acreditabanco','$nroremesa','$nroremitoremesa','$cuentaremitosuelto','$fecharemitosuelto','$nroremitosuelto','$estadoconciliacion','$fechaconciliacion','$usuarioconciliacion','$fechacancelacion','$usuariocancelacion','$fechamodificacion','$usuariomodificacion')";
															$resultAddConcilia = $dbh->query($sqlAddConcilia);
															//echo $sqlAddConcilia; echo "<br>";
														}

														$totacanc=$totacanc+$montopago;
														$cantcanc++;
														print ("<td><div align=center><font size=1 face=Verdana>Cuota Cancelada</font></div></td>");
													    print ("<td><div align=center><font size=1 face=Verdana>----------</font></div></td>");
													}
												}
												else
												{
													print ("<td><div align=center><font size=1 face=Verdana>ERROR URC - Avise al Depto. Sistemas.</font></div></td>");
												}

												$sqlActualizaBanco="UPDATE banacuerdosusimra SET fechaimputacion = :fechaimputacion, usuarioimputacion = :usuarioimputacion WHERE nrocontrol = :nrocontrol and estadomovimiento = :estadomovimiento";
												$resultActualizaBanco = $dbh->prepare($sqlActualizaBanco);
												//echo $sqlActualizaBanco; echo "<br>";
												if ($resultActualizaBanco->execute(array(':fechaimputacion' => $fechacancelacion, ':usuarioimputacion' => $usuariocancelacion, ':nrocontrol' => $controlboleta, ':estadomovimiento' => $estado)))
												{
													//print "<p>Registro Banco actualizado correctamente.</p>\n";
												}
												else
												{
													//print "<p>Error al actualizar el registro Banco.</p>\n";
												}

												$cantidadcuotaspagas = 0;
												$montocuotaspagas = 0.00;
												$fechacancela = date("Y-m-d"); 

												$sqlLeeCuotas="SELECT * FROM cuoacuerdosusimra WHERE cuit = $cuitboleta and nroacuerdo = $acuerdo";
												$resultLeeCuotas = $dbh->query($sqlLeeCuotas);
												foreach ($resultLeeCuotas as $leidas)
												{
													if($leidas[montopagada]!=0 && $leidas[fechapagada]!='00000000')
													{
														$cantidadcuotaspagas = $cantidadcuotaspagas + 1;
														$montocuotaspagas = $montocuotaspagas + $leidas[montopagada];
													}
												}

												$sqlActualizaCabecera="UPDATE cabacuerdosusimra SET cuotaspagadas = :cuotaspagadas, montopagadas = :montopagadas, fechapagadas = :fechapagadas WHERE cuit = :cuit and nroacuerdo = :nroacuerdo";
												$resultActualizaCabecera = $dbh->prepare($sqlActualizaCabecera);
												if ($resultActualizaCabecera->execute(array(':cuotaspagadas' => $cantidadcuotaspagas, ':montopagadas' => $montocuotaspagas, ':fechapagadas' => $fechacancela,':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo)))
												{
													//print "<p>Registro Cabecera Acuerdo actualizado correctamente.</p>\n";
												}
												else
												{
													//print "<p>Error al actualizar el registro Cabecera Acuerdo.</p>\n";
												}

												$sqlLeeCabecera="SELECT * FROM cabacuerdosusimra WHERE cuit = $cuitboleta and nroacuerdo = $acuerdo";
												$resultLeeCabecera = $dbh->query($sqlLeeCabecera);
												foreach ($resultLeeCabecera as $cabecera)
												{
													$estadodeacuerdo=$cabecera[estadoacuerdo];

													if($cabecera[cuotasapagar]==$cabecera[cuotaspagadas])
													{
														if($cabecera[montoapagar]==$cabecera[montopagadas])
															$estadodeacuerdo=0;
													}

													$saldodeacuerdo=$cabecera[montoapagar]-$cabecera[montopagadas];
												}

												$sqlActualizaCabecera="UPDATE cabacuerdosusimra SET estadoacuerdo = :estadoacuerdo, saldoacuerdo = :saldoacuerdo WHERE cuit = :cuit and nroacuerdo = :nroacuerdo";
												$resultActualizaCabecera = $dbh->prepare($sqlActualizaCabecera);
												if ($resultActualizaCabecera->execute(array(':estadoacuerdo' => $estadodeacuerdo, ':saldoacuerdo' => $saldodeacuerdo, ':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo)))
												{
													//print "<p>Registro Cabecera Acuerdo actualizado correctamente.</p>\n";
												}
												else
												{
													//print "<p>Error al actualizar el registro Cabecera Acuerdo.</p>\n";
												}
											}
											else
											{
												print ("<td><div align=center><font size=1 face=Verdana>La Cuota no puede ser Cancelada</font></div></td>");
											    print ("<td><div align=center><font size=1 face=Verdana>".$fechabanco."</font></div></td>");
											}
										}
									}
									else
									{
										print ("<td><div align=center><font size=1 face=Verdana>El ACUERDO/CUOTA No Existe</font></div></td>");
									    print ("<td><div align=center><font size=1 face=Verdana>".$fechabanco."</font></div></td>");
									}
								}
								else
								{
									print ("<td><div align=center><font size=1 face=Verdana>CUIT BANCO ".$cuitbanco." Erroneo - Pago No Imputado</font></div></td>");
								    print ("<td><div align=center><font size=1 face=Verdana>".$fechabanco."</font></div></td>");
								}
							}
							else
							{
								print ("<td><div align=center><font size=1 face=Verdana>IMPORTE BANCO ".$importebanco." Erroneo - Pago No Imputado</font></div></td>");
							    print ("<td><div align=center><font size=1 face=Verdana>".$fechabanco."</font></div></td>");
							}
							print ("</tr>");
						}
					}
        		}
				print ("</table>");

				if($totacanc!=0.00)
				{
					print ("<table width=769 border=1 align=center>");
					print ("<tr>");
					print ("<td width=769><div align=right class=Estilo1>".$cantcanc." Cuotas canceladas por un Total de $".$totacanc."</div></td>");
					print ("</tr>");
					print ("</table>");
				}
    		}
		}
	}
	
	$dbh->commit();
	
	if($haypago==1) { 
	?>
		<p>&nbsp;</p>
		<table width="769" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="385" valign="middle">
		<div align="left">
		<input type="reset" name="volver" value="Volver" onClick="location.href = 'procesamientoRegistros.php'" align="left"/>
		</div>
		</td>
	    <td width="384" valign="middle">
		<div align="right">
        <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="left">
	    </div>
		</td>
		</tr>
		</table>
	<?php
	}
	else
	{ ?>
		<p>&nbsp;</p>
		<table width="769" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="769" valign="middle"><input type="reset" name="volver" value="Volver" onClick="location.href = 'procesamientoRegistros.php'" align="center"/>
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
<title>.: Módulo Banco USIMRA :.</title></head>
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