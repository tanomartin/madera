<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php");
set_time_limit(0);
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");
$cuit=$_GET['cuit'];
include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresaConsulta.php");
$fechaInicio= $row['iniobliosp'];
include($_SERVER['DOCUMENT_ROOT']."/lib/limitesTemporalesEmpresas.php");
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

function reverificaFueratTermino($ano, $me, $db) {
	global $cuit;
	// VEO LOS PERIODOS ABARCADOS POR ACUERDO
	$sqlAcuerdos = "select c.nroacuerdo, c.estadoacuerdo from cabacuerdosospim c, detacuerdosospim d where c.cuit = $cuit and c.cuit = d.cuit and c.nroacuerdo = d.nroacuerdo and d.anoacuerdo = $ano and d.mesacuerdo = $me";
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
		$sqlJuicio = "select c.nroorden, c.statusdeuda, c.nrocertificado from cabjuiciosospim c, detjuiciosospim d where c.cuit = $cuit and c.nroorden = d.nroorden and d.anojuicio = $ano and d.mesjuicio = $me";
		$resJuicio = mysql_query($sqlJuicio,$db); 
		$CantJuicio = mysql_num_rows($resJuicio); 
		if ($CantJuicio > 0) {
			$rowJuicio = mysql_fetch_array($resJuicio); 
			$statusDeuda = $rowJuicio['statusdeuda'];
			$nrocertificado = $rowJuicio['nrocertificado'];
			if ($statusDeuda == 1) {
				$des = "J.EJEC";
			}
			if ($statusDeuda == 2) {
				$des = "J.CONV";
			}
			if ($statusDeuda == 3) {
				$des = "J.QUIEB";
			}
			$des = $des." (".$nrocertificado.")";
		} else {
			// VEO LOS REQ DE FISC
			$sqlReq = "select r.nrorequerimiento from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
			$resReq = mysql_query($sqlReq,$db); 
			$CantReq = mysql_num_rows($resReq); 
			if($CantReq > 0) {
				$rowReq = mysql_fetch_array($resReq); 
				$nroreq = $rowReq['nrorequerimiento'];
				$des = "REQ. (".$nroreq.")";
			} // IF REQUERMINETOS
		} // ELSE JUICIOS
	} // ELSE ACUERDOS
	return (0);
}


function encuentroPagos($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlPagos = "select anopago, mespago, fechapago from afipprocesadas where cuit = $cuit and concepto != 'REM' and ((anopago > $anoinicio and anopago <= $anofin) or (anopago = $anoinicio and mespago >= $mesinicio)) group by anopago, mespago, fechapago";
	$resPagos = mysql_query($sqlPagos,$db);
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
		while ($rowPagos = mysql_fetch_assoc($resPagos)) { 
			$id=$rowPagos['anopago'].$rowPagos['mespago'];
			$arrayPagos[$id] = array('anio' => $rowPagos['anopago'], 'mes' => $rowPagos['mespago'], 'fechapago' =>  $rowPagos['fechapago']);
		}
		$resPagos = array(); 
		foreach ($arrayPagos as $pago) {
			$id=$pago['anio'].$pago['mes'];
			if (estaVencido($pago['fechapago'], $pago['mes'], $pago['anio'])) {
				$resPagos[$id] = array('anio' => $pago['anio'], 'mes' => $pago['mes'], 'estado' => 'P.F.T.');		
			} else {
				$resPagos[$id] = array('anio' => $pago['anio'], 'mes' => $pago['mes'], 'estado' => 'PAGO');
			}
		}
		return($resPagos);
	} else {
		return(0);
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
	
		// VEO LOS PERIODOS ABARCADOS POR ACUERDO
		$sqlAcuerdos = "select c.nroacuerdo, c.estadoacuerdo from cabacuerdosospim c, detacuerdosospim d where c.cuit = $cuit and c.cuit = d.cuit and c.nroacuerdo = d.nroacuerdo and d.anoacuerdo = $ano and d.mesacuerdo = $me";
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
			$sqlJuicio = "select c.nroorden, c.statusdeuda, c.nrocertificado from cabjuiciosospim c, detjuiciosospim d where c.cuit = $cuit and c.nroorden = d.nroorden and d.anojuicio = $ano and d.mesjuicio = $me";
			$resJuicio = mysql_query($sqlJuicio,$db); 
			$CantJuicio = mysql_num_rows($resJuicio); 
			if ($CantJuicio > 0) {
				$rowJuicio = mysql_fetch_array($resJuicio); 
				$statusDeuda = $rowJuicio['statusdeuda'];
				$nrocertificado = $rowJuicio['nrocertificado'];
				if ($statusDeuda == 1) {
					$des = "J.EJEC";
				}
				if ($statusDeuda == 2) {
					$des = "J.CONV";
				}
				if ($statusDeuda == 3) {
					$des = "J.QUIEB";
				}
				$des = $des." (".$nrocertificado.")";
			} else {
				// VEO LOS REQ DE FISC
				$sqlReq = "select r.nrorequerimiento from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
				$resReq = mysql_query($sqlReq,$db); 
				$CantReq = mysql_num_rows($resReq); 
				if($CantReq > 0) {
					$rowReq = mysql_fetch_array($resReq); 
					$nroreq = $rowReq['nrorequerimiento'];
					$des = "REQ. (".$nroreq.")";
				} else {
					// VEO LAS DDJJ REALIZADAS SIN PAGOS
					$sqlDDJJ = "select * from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
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
		return $des;
} //function

function imprimeTabla($periodo) {
	global $cuit;
	$estado = $periodo['estado'];
	$ano = $periodo['anio'];
	$me = $periodo['mes'];
	if ($estado == 'P.F.T.' or $estado == 'PAGO') {
		print ("<td width=81><a href=javascript:abrirInfo('pagosOspim.php?origen=".$_GET['origen']."&cuit=".$cuit."&anio=".$ano."&mes=".$me."')>".$estado."</a></td>");
	} else {
		if ($estado == 'NO PAGO') {
			print ("<td width=81><a href=javascript:abrirInfo('ddjjOspim.php?origen=".$_GET['origen']."&cuit=".$cuit."&anio=".$ano."&mes=".$me."')>".$estado."</a></td>");
		} else {
			$pacuerdo = explode('-',$estado);
			if ($pacuerdo[0] == 'P. ACUER.' or $pacuerdo[0] == 'ACUER.') {
				print ("<td width=81><a href=javascript:abrirInfo('/ospim/acuerdos/abm/consultaAcuerdo.php?cuit=".$cuit."&nroacu=".$pacuerdo[1]."&origen=empresa')>".$pacuerdo[0]."</a></td>"); 
			} else {
				print ("<td width=81>".$estado."</a></td>");
			}
		}
	}
}

?>
<title>.: Cuenta Corriente Empresa :.</title>
<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
	<?php if ($tipo == "activa") { ?>
			<input type="reset" class="nover" name="volver" value="Volver" onClick="location.href = '../empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'" align="center"/> 
	<?php } else { ?>
			<input type="reset" class="nover" name="volver" value="Volver" onClick="location.href = '../empresaBaja.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'" align="center"/> 
	<?php } ?>
	 <p>
    <?php 
		include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresa.php"); 
	?>
  </p>
   <p><strong>Cuenta Corriente </strong></p>
   <p><strong>Inicio Actividad: <?php echo invertirFecha($fechaInicio) ?></strong></p>
  	<?php if ($tipo == "baja") {?>
   		<p><strong>Fecha Baja Empresa: <?php echo invertirFecha($fechaBaja) ?></strong></p>
	<?php } ?>	
	
   <table width="1024" border="1" bordercolor="#000000" style="text-align:center; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px">
  <tr>
    <td width="52" rowspan="2"><span class="Estilo6">A&Ntilde;OS</span></td>
    <td colspan="12"><span class="Estilo6">MESES</span></td>
  </tr>
  <tr> 
	<td width="81" class="Estilo6">Enero</td>
    <td width="81" class="Estilo6">Febrero</td>
    <td width="81" class="Estilo6">Marzo</td>
    <td width="81" class="Estilo6">Abril</td>
    <td width="81" class="Estilo6">Mayo</td>
    <td width="81" class="Estilo6">Junio</td>
    <td width="81" class="Estilo6">Julio</td>
    <td width="81" class="Estilo6">Agosto</td>
    <td width="81" class="Estilo6">Setiembre</td>
    <td width="81" class="Estilo6">Octubre</td>
    <td width="81" class="Estilo6">Noviembre</td>
    <td width="81" class="Estilo6">Diciembre</td>
  </tr>
<?php

$arrayPagos = encuentroPagos($db);
if ($arrayPagos==0){ 
	$arrayPagos = array();
}
while($ano<=$anofin) {
  	print("<tr>");
  	print("<td width='52'><strong>".$ano."</strong></td>");
	for ($i=1;$i<13;$i++){
		$idArray = $ano.$i;
		if (!array_key_exists($idArray, $arrayPagos)) {
			$resultado = estado($ano, $i, $db);
			$arrayPagos[$idArray] =  array('anio' => $ano, 'mes' => $i, 'estado' => $resultado);
		} else {
			$estado = $arrayPagos[$idArray]['estado'];
			if($estado == 'P.F.T.') {
				$resultado = reverificaFueratTermino($ano, $i, $db);
				if ($resultado != 0) {
					$arrayPagos[$idArray] =  array('anio' => $ano, 'mes' => $i, 'estado' => $resultado);
				}
			}
		}
		imprimeTabla($arrayPagos[$idArray]);
	}
	print("</tr>");
	$ano++;
}

?>
</table>
<br>
<table width="1024" border="0" style="font-size:12px">
  <tr>
  	<td>*PAGO =  PAGO CON DDJJ</td>
	<td>*P. ACUER. =  PAGO POR ACUERDO </td>
    <td>*P.F.T. = PAGO FUERA DE TERMINO </td>
	<td>*ACUER. =  EN ACUERDO</td>
  </tr>
  <tr>
    <td>*NO PAGO =  NO PAGO CON DDJJ</td>
	<td>*S. DJ.=  NO PAGO SIN DDJJ</td>
	<td>*REQ. (nro. requerimiento) = FISCALIZADO</td>
    <td>*J.EJEC (nro. orden) = EN JUICIO EJECUCI&Oacute;N </td>
  </tr>
  <tr>
    <td>*J.CONV (nro. orden) = EN JUICIO CONVOCATORIA </td>
    <td>*J.QUIEB (nro. orden) = EN JUICIO QUIEBRA </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br>
<input type="button" class="nover" name="imprimir" value="Imprimir" onClick="window.print();" />
</div>
</body>
</html>
