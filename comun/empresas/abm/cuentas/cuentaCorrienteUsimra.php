<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$cuit=$_GET['cuit'];
include($libPath."cabeceraEmpresaConsulta.php");
$fechaInicio= $row['iniobliusi'];
include($libPath."limitesTemporalesEmpresasUsimra.php");
// sumamos 5 años a los limites temporales.
set_time_limit(0);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>

<style type="text/css">
<!--
.Estilo6 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo7 {font-size: 14px}
-->
</style>
<script language="javascript">
function abrirInfo(dire) {
	a= window.open(dire,"InfoPeriodoCuentaCorrienteEmpresa",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

function abrirInfoCuotas(dire) {
	a= window.open(dire,"InfoCuotasCuentaCorrienteEmpresa",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}


</script>
</head>
<?php

function estaVencido($fechaPago, $me, $ano) {
	if ($me == 12) {
		$mesvto = 1;
		$anovto = $ano + 1;
	} else {
		$mesvto = $me + 1;
		$anovto = $ano;
	}
	if ($mesvto < 10) {
		$mesvto = "0".$mesvto;
	}
	$diavto = 15;
	$fechaStr = $anovto.'-'.$mesvto.'-'.$diavto;
	if (strcmp($fechaPago,$fechaStr) > 0) {
		return(1);
	}
	return(0);
}

function reverificaFueraTermino($ano, $me, $db) {
	global $cuit;
	// VEO LOS PERIODOS ABARCADOS POR ACUERDO
	$sqlAcuerdos = "select c.nroacuerdo, c.estadoacuerdo from cabacuerdosusimra c, detacuerdosusimra d where c.cuit = $cuit and c.cuit = d.cuit and c.nroacuerdo = d.nroacuerdo and d.anoacuerdo = $ano and d.mesacuerdo = $me";
	$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
	$CantAcuerdos = mysql_num_rows($resAcuerdos); 
	if($CantAcuerdos > 0) {
		$rowAcuerdos = mysql_fetch_array($resAcuerdos); 
		$nroacuerdo = $rowAcuerdos['nroacuerdo'];
		if ($rowAcuerdos['estadoacuerdo'] == 0 ) {
			$des = "P. ACUER.-".$nroacuerdo;
		} else {
			$des = "ACUER.-".$nroacuerdo;
		}
		return($des);
	} else {
		//VEO LOS JUICIOS
		$sqlJuicio = "select c.nroorden, c.statusdeuda, c.nrocertificado from cabjuiciosusimra c, detjuiciosusimra d where c.cuit = $cuit and c.nroorden = d.nroorden and d.anojuicio = $ano and d.mesjuicio = $me";
		$resJuicio = mysql_query($sqlJuicio,$db); 
		$CantJuicio = mysql_num_rows($resJuicio); 
		if ($CantJuicio > 0) {
			$rowJuicio = mysql_fetch_array($resJuicio); 
			$statusDeuda = $rowJuicio['statusdeuda'];
			$nrocertificado = $rowJuicio['nrocertificado'];
			$nroorden = $rowJuicio['nroorden'];
			if ($statusDeuda == 1) {
				$des = "J.EJEC";
			}
			if ($statusDeuda == 2) {
				$des = "J.CONV";
			}
			if ($statusDeuda == 3) {
				$des = "J.QUIEB";
			}
			$des = $des." (".$nrocertificado.")-".$nroorden;
			return($des);
		} else {
			// VEO LOS REQ DE FISC
			$sqlReq = "select r.nrorequerimiento from reqfiscalizusimra r, detfiscalizusimra d where r.cuit = $cuit and r.procesoasignado = 1 and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
			$resReq = mysql_query($sqlReq,$db); 
			$CantReq = mysql_num_rows($resReq); 
			if($CantReq > 0) {
				$rowReq = mysql_fetch_array($resReq); 
				$nroreq = $rowReq['nrorequerimiento'];
				$des = "REQ. (".$nroreq.")";
				return($des);
			} // IF REQUERMINETOS
		} // ELSE JUICIOS
	} // ELSE ACUERDOS
	return ('P.F.T.');
}


function encuentroPagos($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	//CAMBIAR A USIMRA
	$sqlPagos = "select anopago, mespago, fechapago, remuneraciones, montopagado from seguvidausimra where cuit = $cuit and ((anopago > $anoinicio and anopago <= $anofin) or (anopago = $anoinicio and mespago >= $mesinicio)) group by anopago, mespago, fechapago";
	$resPagos = mysql_query($sqlPagos,$db);
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
		while ($rowPagos = mysql_fetch_assoc($resPagos)) { 
			$id=$rowPagos['anopago'].$rowPagos['mespago'];
			$arrayPagos[$id] = array('anio' => $rowPagos['anopago'], 'mes' => $rowPagos['mespago'], 'fechapago' =>  $rowPagos['fechapago'], 'remuneraciones' =>  $rowPagos['remuneraciones'], 'montopagado' =>  $rowPagos['montopagado'], );
		}
		$resPagos = array(); 
		foreach ($arrayPagos as $pago) {
			$id=$pago['anio'].$pago['mes'];
			$pagoExacto = $pago['remuneraciones'] * 0.031;
			$diferencia = $pagoExacto - $pago['montopagado'];
			if ($diferencia < -1 || $diferencia > 1 ) {
				$resPagos[$id] = array('anio' => $pago['anio'], 'mes' => $pago['mes'], 'estado' => 'P.M.');
			} else {
				if (estaVencido($pago['fechapago'], $pago['mes'], $pago['anio'])) {
					$resPagos[$id] = array('anio' => $pago['anio'], 'mes' => $pago['mes'], 'estado' => 'P.F.T.');		
				} else {
					$resPagos[$id] = array('anio' => $pago['anio'], 'mes' => $pago['mes'], 'estado' => 'PAGO');
				}
			}
		}
		return($resPagos);
	} else {
		return(0);
	}
}

function encuentroPagosExtraor($db, &$arrayPagos) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlPagosExt = "SELECT s.anopago, s.mespago, e.relacionmes, s.fechapago, s.remuneraciones, s.montopagado
					FROM seguvidausimra s, extraordinariosusimra e
					WHERE s.cuit = $cuit and s.anopago = e.anio and s.mespago = e.mes and
					 ((s.anopago > $anoinicio and s.anopago <= $anofin) or (s.anopago = $anoinicio and e.relacionmes >= $mesinicio))
					group by anopago, mespago, fechapago";
	$resPagosExt = mysql_query($sqlPagosExt,$db);
	$canPagosExt = mysql_num_rows($resPagosExt);
	if($canPagosExt > 0) {
		while ($rowPagosExt = mysql_fetch_assoc($resPagosExt)) {
			$id=$rowPagosExt['anopago'].$rowPagosExt['relacionmes'];
			$arrayPagos[$id]['estado'] = $arrayPagos[$id]['estado']." | NR";
		}
	}
}

function estado($ano, $me, $db) {
		global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
		//VEO QUE EL MES Y EL AÑO ESTEND DENTRO DE LOS PERIODOS A MOSTRAR
		if ($ano == $anoinicio) {
			if ($me < $mesinicio) {
				$des = "-";
				return($des);
			}
		}
		if ($ano == $anofin) {
			if ($me > $mesfin) {
				$des = "-";
				return($des);
			}
		}
			
		//VEO PAGO DIFERENCIADO ANTES DE ACUERDOS
		$sqlPagDif = "select * from periodosanterioresusimra where cuit = $cuit and anoanterior = $ano and mesanterior = $me";
		$resPagDif = mysql_query($sqlPagDif,$db); 
		$CantPagDif = mysql_num_rows($resPagDif); 
		if($CantPagDif > 0) {
			$rowPagDif = mysql_fetch_array($resPagDif); 
			$perPago = $rowPagDif['mespago']."-".$rowPagDif['anopago'];
			$des = "P. DIF. <br/>(".$perPago.")";
		} else {
			// VEO LOS PERIODOS ABARCADOS POR ACUERDO
			$sqlAcuerdos = "select c.nroacuerdo, c.estadoacuerdo from cabacuerdosusimra c, detacuerdosusimra d where c.cuit = $cuit and c.cuit = d.cuit and c.nroacuerdo = d.nroacuerdo and d.anoacuerdo = $ano and d.mesacuerdo = $me";
			$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
			$CantAcuerdos = mysql_num_rows($resAcuerdos); 
			if($CantAcuerdos > 0) {
				$rowAcuerdos = mysql_fetch_array($resAcuerdos); 
				$nroacuerdo = $rowAcuerdos['nroacuerdo'];
				if ($rowAcuerdos['estadoacuerdo'] == 0 ) {
					$des = "P. ACUER.-".$nroacuerdo;
				} else {
					$des = "ACUER.-".$nroacuerdo;
				}
			} else {
				//VEO LOS JUICIOS
				$sqlJuicio = "select c.nroorden, c.statusdeuda, c.nrocertificado from cabjuiciosusimra c, detjuiciosusimra d where c.cuit = $cuit and c.nroorden = d.nroorden and d.anojuicio = $ano and d.mesjuicio = $me";
				$resJuicio = mysql_query($sqlJuicio,$db); 
				$CantJuicio = mysql_num_rows($resJuicio); 
				if ($CantJuicio > 0) {
					$rowJuicio = mysql_fetch_array($resJuicio); 
					$statusDeuda = $rowJuicio['statusdeuda'];
					$nrocertificado = $rowJuicio['nrocertificado'];
					$nroorden = $rowJuicio['nroorden'];
					if ($statusDeuda == 1) {
						$des = "J.EJEC";
					}
					if ($statusDeuda == 2) {
						$des = "J.CONV";
					}
					if ($statusDeuda == 3) {
						$des = "J.QUIEB";
					}
					$des = $des." (".$nrocertificado.")-".$nroorden;
				} else {
					// VEO LOS REQ DE FISC
					$sqlReq = "select r.nrorequerimiento from reqfiscalizusimra r, detfiscalizusimra d where r.cuit = $cuit and r.procesoasignado = 1 and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
					$resReq = mysql_query($sqlReq,$db); 
					$CantReq = mysql_num_rows($resReq); 
					if($CantReq > 0) {
						$rowReq = mysql_fetch_array($resReq); 
						$nroreq = $rowReq['nrorequerimiento'];
						$des = "REQ. (".$nroreq.")";
					} else {
						// VEO LAS DDJJ REALIZADAS SIN PAGOS
						$sqlDDJJ = "select * from ddjjusimra where nrcuit = $cuit and perano = $ano and permes = $me";
						$resDDJJ = mysql_query($sqlDDJJ,$db); 
						$CantDDJJ = mysql_num_rows($resDDJJ); 
						if($CantDDJJ > 0) {
							$des = "NO PAGO";
						} else {
							// NO HAY DDJJ SIN PAGOS
							$des = "S.DJ.";
						} //else DDJJ
					}//else REQ
				} //else JUICIOS
			}//else ACUERDOS
		} //else PAGO DIF
		return $des;
} //function

function imprimeTabla($periodo) {
	global $cuit;
	$estado = $periodo['estado'];
	$ano = $periodo['anio'];
	$me = $periodo['mes'];
	if ($estado == 'P.F.T.' or $estado == 'PAGO' or $estado == 'P.M.' or $estado == 'P.F.T. | NR' or $estado == 'PAGO | NR' or $estado == 'P.M. | NR') {
		print ("<td><a href=javascript:abrirInfo('detallePagosUsimra.php?origen=".$_GET['origen']."&cuit=".$cuit."&anio=".$ano."&mes=".$me."')>".$estado."</a></td>");
	} else {
		if ($estado == 'NO PAGO') {
			print ("<td><a href=javascript:abrirInfo('detalleDDJJUsimra.php?origen=".$_GET['origen']."&cuit=".$cuit."&anio=".$ano."&mes=".$me."')>".$estado."</a></td>");
		} else {
			$pacuerdo = explode('-',$estado);
			if ($pacuerdo[0] == 'P. ACUER.' or $pacuerdo[0] == 'ACUER.') {
				print ("<td><a href=javascript:abrirInfo('/madera/usimra/acuerdos/abm/consultaAcuerdo.php?cuit=".$cuit."&nroacu=".$pacuerdo[1]."&origen=empresa')>".$pacuerdo[0]."</a></td>"); 
			} else {
				$juicioEstado = explode('-',$estado);
				$pjuicio = explode('(',$juicioEstado[0]);
				if ($pjuicio[0] == 'J.CONV ' or $pjuicio[0] == 'J.QUIEB ' or $pjuicio[0] == 'J.EJEC ') {
					$nroorden = $juicioEstado[1];
					print ("<td><a href=javascript:abrirInfo('/madera/usimra/legales/juicios/consultaJuicio.php?cuit=".$cuit."&nroorden=".$nroorden."&origen=empresa')>".$juicioEstado[0]."</a></td>"); 
				} else {
					print ("<td>".$estado."</a></td>");
				}
			}
		}
	}
}

?>
<title>.: Cuenta Corriente Empresa :.</title>
<body bgcolor="#B2A274">
<div align="center">
	<?php if ($tipo == "activa") { ?>
			<input type="reset" class="nover" name="volver" value="Volver" onClick="location.href = '../empresa.php?origen=usimra&cuit=<?php echo $cuit ?>'" /> 
	<?php } else { ?>
			<input type="reset" class="nover" name="volver" value="Volver" onClick="location.href = '../empresaBaja.php?origen=usimra&cuit=<?php echo $cuit ?>'" /> 
	<?php } ?>
	 <p>
    <?php 
		include($libPath."cabeceraEmpresa.php"); 
	?>
  </p>
   <p><strong>Cuenta Corriente </strong></p>
   <p><strong>Inicio Actividad: <?php echo invertirFecha($fechaInicio) ?></strong></p>
  	<?php if ($tipo == "baja") {?>
   		<p><strong>Fecha Baja Empresa: <?php echo invertirFecha($fechaBaja) ?></strong></p>
	<?php } ?>	
	
<table width="1132" border="1" style="text-align:center; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px">
  <tr>
    <td  width='40' rowspan="2"><span class="Estilo6">A&Ntilde;OS</span></td>
    <td colspan="12"><span class="Estilo6">MESES</span></td>
  </tr>
  <tr> 
	<td width="91" class="Estilo6">Enero</td>
    <td width="91" class="Estilo6">Febrero</td>
    <td width="91" class="Estilo6">Marzo</td>
    <td width="91" class="Estilo6">Abril</td>
    <td width="91" class="Estilo6">Mayo</td>
    <td width="91" class="Estilo6">Junio</td>
    <td width="91" class="Estilo6">Julio</td>
    <td width="91" class="Estilo6">Agosto</td>
    <td width="91" class="Estilo6">Setiembre</td>
    <td width="91" class="Estilo6">Octubre</td>
    <td width="91" class="Estilo6">Noviembre</td>
    <td width="91" class="Estilo6">Diciembre</td>
  </tr>
<?php

$arrayPagos = encuentroPagos($db);
encuentroPagosExtraor($db, $arrayPagos);
if ($arrayPagos==0){ 
	$arrayPagos = array();
}
while($ano<=$anofin) { ?>
  	<tr>
  		<td><strong><?php echo $ano ?></strong></td>
<?php	for ($i=1;$i<13;$i++){
		$idArray = $ano.$i;
		if (!array_key_exists($idArray, $arrayPagos)) {
			$resultado = estado($ano, $i, $db);
			$arrayPagos[$idArray] =  array('anio' => $ano, 'mes' => $i, 'estado' => $resultado);
		} else {
			$estado = $arrayPagos[$idArray]['estado'];
			if($estado == 'P.F.T.') {
				$resultado = reverificaFueraTermino($ano, $i, $db);
				if ($resultado != 'P.F.T.') {
					$arrayPagos[$idArray] =  array('anio' => $ano, 'mes' => $i, 'estado' => $resultado);
				}
			}
		}
		imprimeTabla($arrayPagos[$idArray]);
	} ?>
	</tr>
<?php 	$ano++;
} ?>
</table>
<br>
<table width="1130" border="0" style="font-size:12px">
  <tr>
  	<td>*PAGO = PAGO</td>
    <td>*P.F.T. = PAGO FUERA DE TERMINO </td>
    <td>*P.M.. = PAGO MENOR</td>
    <td>*X | NR = [PAGO | P.F.T. | P.M ] CON NO REMUNERATIVO</td>
  </tr>
  <tr>
  	<td>*P. ACUER. =  PAGO POR ACUERDO </td>
  	<td>*P. DIF. (Per. Pago) = PAGO EN PERIODO POSTERIOR </td>
  	<td>*ACUER. =  EN ACUERDO</td>
    <td>*NO PAGO =  NO PAGO CON DDJJ</td>
  </tr>
  <tr>
  	<td>*S. DJ.=  NO PAGO SIN DDJJ</td>
  	<td>*REQ. (nro. req.) = FISCALIZADO</td>
  	<td>*J.EJEC (nro. orden) = EN JUICIO EJECUCI&Oacute;N </td>
    <td>*J.CONV (nro. orden) = EN JUICIO CONVOCATORIA </td>
  </tr>
  <tr>
  	 <td>*J.QUIEB (nro. orden) = EN JUICIO QUIEBRA </td>
  	 <td></td>
  	 <td></td>
  	 <td></td>
  </tr>
</table>

<?php 
	$sqlCuotasExcpecional = "SELECT * FROM  cuotaextraordinariausimra c, extraordinariosusimra e WHERE c.cuit = $cuit and e.anio = c.anopago and e.mes = c.mespago"; 
	$resCuotasExcpecional = mysql_query($sqlCuotasExcpecional,$db);
	$canCuotasExcpecional = mysql_num_rows($resCuotasExcpecional);
?>

<p><strong>Cuotas Excepcionales </strong></p>
<table width="800" border="1" style="text-align:center; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px">
	<tr>
		<th>Cuota</th>
		<th>Fecha</th>
		<th>Personal</th>
		<th>Monto</th>
		<th>Recargo</th>
		<th>Total</th>
		<th>+Info</th>
	<tr>
	<?php  if ($canCuotasExcpecional > 0) { 
				while ($rowCuotasExcpecional = mysql_fetch_assoc($resCuotasExcpecional)) { 
					dire ?>
					<tr>
						<td><?php echo $rowCuotasExcpecional['relacionmes']."-". $rowCuotasExcpecional['anio']." | ".$rowCuotasExcpecional['mensaje'] ?></td>
						<td><?php echo invertirfecha($rowCuotasExcpecional['fechapago']) ?></td>
						<td><?php echo $rowCuotasExcpecional['cantidadaportantes'] ?></td>
						<td><?php echo $rowCuotasExcpecional['totalaporte'] ?></td>
						<td><?php echo $rowCuotasExcpecional['montorecargo'] ?></td>
						<td><?php echo $rowCuotasExcpecional['montopagado'] ?></td>
						<td><input type="button" value="DDJJ" onclick='javascript:abrirInfoCuotas("detalleCuotaUsimra.php?cuit=<?php echo $cuit?>&anio=<?php echo $rowCuotasExcpecional['mes'] ?>&mes=<?php echo $rowCuotasExcpecional['anio'] ?>")'/></td>
					</tr>
		<?php  	} 
		   } else { ?>
				<tr><td colspan=7 align="center"><b>No tiene pagos de Cuotas Excepcionales</b></td></tr>
	<?php  } ?>
</table>

<br>
<input type="button" class="nover" name="imprimir" value="Imprimir" onClick="window.print();" />
</div>
</body>
</html>
