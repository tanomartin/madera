<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fechacancelacion = date("Y-m-d H:m:s");
$usuariocancelacion = $_SESSION['usuario'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Recaudaci&oacute;n Bancaria :.</title>
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
</head>
<body bgcolor="#CCCCCC">
<?php
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

	$sqlControlImputar="SELECT COUNT(*) FROM banacuerdosospim WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";
	$sqlLeeAImputar="SELECT * FROM banacuerdosospim WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";

	$resultControlImputar = $dbh->query($sqlControlImputar);

	if (!$resultControlImputar)
	{
		print ("<p>&nbsp;</p>\n");
		print ("<table width=769 border=1 align=center>");
		print ("<tr>");
		print ("<td width=769><div align=center class=Estilo1>Error en la consulta de BANACUERDOSOSPIM. Comuniquese con el Depto. de Sistemas.</div></td>");
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
				print ("</tr>");

        		foreach ($resultLeeAImputar as $imputar)
				{
					$controlbanco = $imputar[nrocontrol];
					$estado = $imputar[estadomovimiento];
					$importebanco = $imputar[importe];
					$cuitbanco = $imputar[cuit];
					$recaudabanco = $imputar[fecharecaudacion];
					$acreditabanco = $imputar[fechaacreditacion];
					$codbarrabanco = $imputar[codigobarra];
					$validadabanco = $imputar[fechavalidacion];
					
					$sqlBuscaValida="SELECT * FROM validasospim WHERE cuit = :cuit AND nrocontrol = :nrocontrol";
					//echo $sqlBuscaValida; echo "<br>";
					$resultBuscaValida = $dbh->prepare($sqlBuscaValida);
					$resultBuscaValida->execute(array(':cuit' => $cuitbanco, ':nrocontrol' => $controlbanco));
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
												$sqlActualizaCuota="UPDATE cuoacuerdosospim SET tipocancelacion = :tipocancelacion, observaciones = :observaciones, boletaimpresa = :boletaimpresa, montopagada = :montopagada, fechapagada = :fechapagada, fechacancelacion = :fechacancelacion, sistemacancelacion = :sistemacancelacion, codigobarra = :codigobarra, fechaacreditacion = :fechaacreditacion WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
												$resultActualizaCuota = $dbh->prepare($sqlActualizaCuota);
												//echo $sqlActualizaCuota; echo "<br>";
												if ($resultActualizaCuota->execute(array(':tipocancelacion' => $tiposcanc, ':observaciones' => $observaci, ':boletaimpresa' => $boletaimp, ':montopagada' => $montopago, ':fechapagada' => $fechapago, ':fechacancelacion' => $fechacanc, ':sistemacancelacion' => $sistecanc, ':codigobarra' => $codibarra, ':fechaacreditacion' => $acreditabanco, ':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo, ':nrocuota' => $cuota)))
												{
													if($tiposcanc=='10')
														print ("<td><div align=center><font size=1 face=Verdana>Cheque Rechazado</font></div></td>");
													else
													{
														$totacanc=$totacanc+$montopago;
														$cantcanc++;
														print ("<td><div align=center><font size=1 face=Verdana>Cuota Cancelada</font></div></td>");
													}
												}
												else
												{
													print ("<td><div align=center><font size=1 face=Verdana>ERROR URC - Avise al Depto. Sistemas.</font></div></td>");
												}

												$sqlActualizaBanco="UPDATE banacuerdosospim SET fechaimputacion = :fechaimputacion, usuarioimputacion = :usuarioimputacion WHERE nrocontrol = :nrocontrol and estadomovimiento = :estadomovimiento";
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

												$sqlLeeCuotas="SELECT * FROM cuoacuerdosospim WHERE cuit = $cuitboleta and nroacuerdo = $acuerdo";
												$resultLeeCuotas = $dbh->query($sqlLeeCuotas);
												foreach ($resultLeeCuotas as $leidas)
												{
													if($leidas[montopagada]!=0 && $leidas[fechapagada]!='00000000')
													{
														$cantidadcuotaspagas = $cantidadcuotaspagas + 1;
														$montocuotaspagas = $montocuotaspagas + $leidas[montopagada];
													}
												}

												$sqlActualizaCabecera="UPDATE cabacuerdosospim SET cuotaspagadas = :cuotaspagadas, montopagadas = :montopagadas, fechapagadas = :fechapagadas WHERE cuit = :cuit and nroacuerdo = :nroacuerdo";
												$resultActualizaCabecera = $dbh->prepare($sqlActualizaCabecera);
												if ($resultActualizaCabecera->execute(array(':cuotaspagadas' => $cantidadcuotaspagas, ':montopagadas' => $montocuotaspagas, ':fechapagadas' => $fechacancela,':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo)))
												{
													//print "<p>Registro Cabecera Acuerdo actualizado correctamente.</p>\n";
												}
												else
												{
													//print "<p>Error al actualizar el registro Cabecera Acuerdo.</p>\n";
												}

												$sqlLeeCabecera="SELECT * FROM cabacuerdosospim WHERE cuit = $cuitboleta and nroacuerdo = $acuerdo";
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

												$sqlActualizaCabecera="UPDATE cabacuerdosospim SET estadoacuerdo = :estadoacuerdo, saldoacuerdo = :saldoacuerdo WHERE cuit = :cuit and nroacuerdo = :nroacuerdo";
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
											}
										}
									}
								}
								else
								{
									print ("<td><div align=center><font size=1 face=Verdana>CUIT BANCO ".$cuitbanco." Erroneo - Pago No Imputado</font></div></td>");
								}
							}
							else
							{
								print ("<td><div align=center><font size=1 face=Verdana>IMPORTE BANCO ".$importebanco." Erroneo - Pago No Imputado</font></div></td>");
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
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistros.php'" align="left"/>
		</div>
		</td>
	    <td width="384" valign="middle">
		<div align="right">
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/>
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
	    <td width="769" valign="middle"><input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistros.php'"/>
		</td>
		</tr>
		</table>
	<?php
	}

}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>
</body>
</html>