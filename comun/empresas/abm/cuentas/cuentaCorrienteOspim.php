<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
set_time_limit(0);
include($libPath."fechas.php");
$cuit=$_GET['cuit'];
include($libPath."cabeceraEmpresaConsulta.php");
$fechaInicio= $row['iniobliosp'];
include($libPath."limitesTemporalesEmpresas.php");

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

function reverificaPeriodo($estado, $ano, $me, $db) {
	global $cuit;
	global $arrayAcuerdos, $arrayJuicios, $arrayRequerimientos;

	// VEO LOS PERIODOS ABARCADOS POR ACUERDO
	$idArray = $ano.$me;
	if(array_key_exists($idArray, $arrayAcuerdos)) {
		$nroacuerdo = $arrayAcuerdos[$idArray]['nroacuerdo'];
		$des = "ACUER.-".$nroacuerdo;
		if ($arrayAcuerdos[$idArray]['estadoacuerdo'] == 0 ) {
			$des = "P. ACUER.-".$nroacuerdo;
		} else {
			if ($arrayAcuerdos[$idArray]['estadoacuerdo'] == 2) {
				$des = "ACU. INC.-".$nroacuerdo;
			}
		}
		return($des);
	} else {
		//VEO LOS JUICIOS
		if (array_key_exists($idArray, $arrayJuicios)) {
			$statusDeuda = $arrayJuicios[$idArray]['statusdeuda'];
			$nrocertificado = $arrayJuicios[$idArray]['nrocertificado'];
			$nroorden = $arrayJuicios[$idArray]['nroorden'];
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
			if(array_key_exists($idArray, $arrayRequerimientos)) {
				$nroreq = $arrayRequerimientos[$idArray]['nrorequerimiento'];
				$des = "REQ. (".$nroreq.")";
				return($estado."<br>".$des);
			} // IF REQUERMINETOS
		} // ELSE JUICIOS
	} // ELSE ACUERDOS
	return ($estado);
}

function encuentroPagos($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlPagos = "select anopago, mespago, fechapago, concepto from afipprocesadas where cuit = $cuit and (concepto = '381' or concepto = '401') and ((anopago > $anoinicio and anopago <= $anofin) or (anopago = $anoinicio and mespago >= $mesinicio)) group by anopago, mespago, fechapago, concepto";
	$resPagos = mysql_query($sqlPagos,$db);
	$CantPagos = mysql_num_rows($resPagos);
	$arrayConc = array();
	if($CantPagos > 0) {
		while ($rowPagos = mysql_fetch_assoc($resPagos)) {
			$id = $rowPagos['anopago'].$rowPagos['mespago'];
			$arrayConc[$id][$rowPagos['concepto']] = array('anio' => $rowPagos['anopago'], 'mes' => $rowPagos['mespago'], 'fechapago' =>  $rowPagos['fechapago']);	
		}
		$resPagos = array();
		foreach($arrayConc as $concepto) {
			if (isset($concepto['401']) && isset($concepto['381'])) {
				$id = $concepto['401']['anio'].$concepto['401']['mes'];
				$arrayPagos[$id] = array('anio' => $concepto['401']['anio'], 'mes' => $concepto['401']['mes'], 'fechapago' =>  $concepto['401']['fechapago']);
			}
		}
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

function encuentroAcuerdos($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlAcuerdos = "select d.anoacuerdo, d.mesacuerdo, d.nroacuerdo, c.estadoacuerdo from detacuerdosospim d, cabacuerdosospim c where c.cuit = $cuit and c.cuit = d.cuit and c.nroacuerdo = d.nroacuerdo and ((d.anoacuerdo > $anoinicio and d.anoacuerdo <= $anofin) or (d.anoacuerdo = $anoinicio and d.mesacuerdo >= $mesinicio)) group by d.anoacuerdo, d.mesacuerdo order by d.anoacuerdo, d.mesacuerdo";
	$resAcuerdos = mysql_query($sqlAcuerdos,$db);
	$canAcuerdos = mysql_num_rows($resAcuerdos);
	if($canAcuerdos > 0) {
		while ($rowAcuerdos = mysql_fetch_assoc($resAcuerdos)) {
			$id=$rowAcuerdos['anoacuerdo'].$rowAcuerdos['mesacuerdo'];
			$arrayAcuerdos[$id] = array('anio' => (int)$rowAcuerdos['anoacuerdo'], 'mes' => (int)$rowAcuerdos['mesacuerdo'], 'nroacuerdo' => (int)$rowAcuerdos['nroacuerdo'], 'estadoacuerdo' => (int)$rowAcuerdos['estadoacuerdo']);
		}
	} else {
		return 0;
	}
	return($arrayAcuerdos);
}

function encuentroJuicios($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlJuicios = "select d.anojuicio, d.mesjuicio, c.nrocertificado, c.statusdeuda, c.nroorden from cabjuiciosospim c, detjuiciosospim d  where c.cuit = $cuit and c.nroorden = d.nroorden and ((d.anojuicio > $anoinicio and d.anojuicio <= $anofin) or (d.anojuicio = $anoinicio and d.mesjuicio >= $mesinicio)) group by d.anojuicio, d.mesjuicio order by d.anojuicio, d.mesjuicio";
	$resJuicios = mysql_query($sqlJuicios,$db);
	$canJuicios = mysql_num_rows($resJuicios);
	if($canJuicios > 0) {
		while ($rowJuicios = mysql_fetch_assoc($resJuicios)) {
			$id=$rowJuicios['anojuicio'].$rowJuicios['mesjuicio'];
			$arrayJuicios[$id] = array('anio' => (int)$rowJuicios['anojuicio'], 'mes' => (int)$rowJuicios['mesjuicio'], 'nrocertificado' => $rowJuicios ['nrocertificado'], 'statusdeuda' => $rowJuicios ['statusdeuda'],'nroorden' => $rowJuicios ['nroorden']);
		}
	} else {
		return 0;
	}
	return($arrayJuicios);
}

function encuentroRequerimientos($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlRequerimientos = "select d.anofiscalizacion, d.mesfiscalizacion, r.nrorequerimiento from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and ((d.anofiscalizacion > $anoinicio and d.anofiscalizacion <= $anofin) or (d.anofiscalizacion = $anoinicio and d.mesfiscalizacion >= $mesinicio)) group by d.anofiscalizacion, d.mesfiscalizacion order by d.anofiscalizacion, d.mesfiscalizacion";
	$resRequerimientos = mysql_query($sqlRequerimientos,$db);
	$canRequerimientos = mysql_num_rows($resRequerimientos);
	if($canRequerimientos > 0) {
		while ($rowRequerimientos = mysql_fetch_assoc($resRequerimientos)) {
			$id=$rowRequerimientos['anofiscalizacion'].$rowRequerimientos['mesfiscalizacion'];
			$arrayRequerimientos[$id] = array('anio' => (int)$rowRequerimientos['anofiscalizacion'], 'mes' => (int)$rowRequerimientos['mesfiscalizacion'], 'nrorequerimiento' => ( int ) $rowRequerimientos ['nrorequerimiento'] );
		}
	} else {
		return 0;
	}
	return($arrayRequerimientos);
}

function encuentroDdjj($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlDdjj = "select anoddjj, mesddjj from cabddjjospim where cuit = $cuit and ((anoddjj > $anoinicio and anoddjj <= $anofin) or (anoddjj = $anoinicio and mesddjj >= $mesinicio)) group by anoddjj, mesddjj order by anoddjj, mesddjj";
	$resDdjj = mysql_query($sqlDdjj,$db);
	$canDdjj = mysql_num_rows($resDdjj);
	if($canDdjj > 0) {
		while ($rowDdjj = mysql_fetch_assoc($resDdjj)) {
			$id=$rowDdjj['anoddjj'].$rowDdjj['mesddjj'];
			$arrayDdjj[$id] = array('anio' => (int)$rowDdjj['anoddjj'], 'mes' => (int)$rowDdjj['mesddjj']);
		}
	} else {
		return 0;
	}
	return($arrayDdjj);
}

function estado($ano, $me, $db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	global $arrayAcuerdos, $arrayJuicios, $arrayRequerimientos, $arrayDdjj;
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

	$idArray = $ano.$me;
	// VEO LOS PERIODOS ABARCADOS POR ACUERDO
	if(array_key_exists($idArray, $arrayAcuerdos)) {
		$nroacuerdo = $arrayAcuerdos[$idArray]['nroacuerdo'];
		$des = "ACUER.-".$nroacuerdo;
		if ($arrayAcuerdos[$idArray]['estadoacuerdo'] == 0) {
			$des = "P. ACUER.-" . $nroacuerdo;
		} else {
			if ($arrayAcuerdos[$idArray]['estadoacuerdo'] == 2) {
				$des = "ACU. INC.-".$nroacuerdo;
			}
		}
	} else {
		//VEO LOS JUICIOS
		if (array_key_exists($idArray, $arrayJuicios)) {
			$statusDeuda = $arrayJuicios[$idArray]['statusdeuda'];
			$nrocertificado = $arrayJuicios[$idArray]['nrocertificado'];
			$nroorden = $arrayJuicios[$idArray]['nroorden'];
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
			// VEO LAS DDJJ REALIZADAS SIN PAGOS
			if(array_key_exists($idArray, $arrayDdjj)) {
				$des = "NO PAGO";
			} else {
				// NO HAY DDJJ SIN PAGOS
				$des = "S.DJ.";
			} //else DDJJ
		} //else JUICIOS
	}//else ACUERDOS
	return $des;
} //function

function imprimeTabla($periodo) {
	global $cuit;
	$estado = $periodo['estado'];
	$ano = $periodo['anio'];
	$me = $periodo['mes'];
	print("<td>");
	if (strpos($estado, 'NO PAGO') !== false) {
		print ("<a href=javascript:abrirInfo('detalleDDJJ.php?cuit=".$cuit."&anio=".$ano."&mes=".$me."')>".$estado."</a>");
	} else {
		if (strpos($estado, 'P.F.T.') !== false or strpos($estado, 'PAGO') !== false) {
			print ("<a href=javascript:abrirInfo('detallePagos.php?cuit=".$cuit."&anio=".$ano."&mes=".$me."')>".$estado."</a>");
		} else {
			$pacuerdo = explode('-',$estado);
			if ($pacuerdo[0] == 'P. ACUER.' or $pacuerdo[0] == 'ACUER.' or $pacuerdo[0] == 'ACU. INC.') {
				print ("<a href=javascript:abrirInfo('/madera/ospim/acuerdos/abm/consultaAcuerdo.php?cuit=".$cuit."&nroacu=".$pacuerdo[1]."&origen=empresa')>".$estado."</a>");
			} else {
				$juicioEstado = explode('-',$estado);
				$pjuicio = explode('(',$juicioEstado[0]);
				if ($pjuicio[0] == 'J.CONV ' or $pjuicio[0] == 'J.QUIEB ' or $pjuicio[0] == 'J.EJEC ') {
					$nroorden = $juicioEstado[1];
					print ("<a href=javascript:abrirInfo('/madera/ospim/legales/juicios/consultaJuicio.php?cuit=".$cuit."&nroorden=".$nroorden."&origen=empresa')>".$juicioEstado[0]."</a>");
				} else {
					print ($estado);
				}
			}
		}
	}
	print("</td>");
}


$arrayPagos = encuentroPagos($db);
if ($arrayPagos==0){
	$arrayPagos = array();
}

$arrayAcuerdos = encuentroAcuerdos($db);
if ($arrayAcuerdos == 0){
	$arrayAcuerdos = array();
}

$arrayJuicios = encuentroJuicios($db);
if ($arrayJuicios == 0){
	$arrayJuicios = array();
}

$arrayRequerimientos = encuentroRequerimientos($db);
if ($arrayRequerimientos == 0){
	$arrayRequerimientos = array();
}

$arrayDdjj = encuentroDdjj($db);
if ($arrayDdjj == 0){
	$arrayDdjj = array();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>.: Cuenta Corriente Empresa :.</title>
<script language="javascript">
function abrirInfo(dire) {
	a= window.open(dire,"InfoPeriodoCuentaCorrienteEmpresa",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
</script>
<style type="text/css" media="print">
	.nover {display:none}
</style>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<p>
<?php if ($tipo == "activa") { ?>
		<input type="button" class="nover" name="volver" value="Volver" onclick="location.href = '../empresa.php?origen=ospim&cuit=<?php echo $cuit ?>'" /> 
<?php } else { ?>
		<input type="button" class="nover" name="volver" value="Volver" onclick="location.href = '../empresaBaja.php?origen=ospim&cuit=<?php echo $cuit ?>'" /> 
<?php } ?>
	</p>
	<p>
    <?php include($libPath."cabeceraEmpresa.php"); ?>
  	</p>
    <p><b>Cuenta Corriente </b></p>
    <p><b>Inicio Actividad: <?php if ($fechaInicio == "" || $fechaInicio == "0000-00-00") { echo  "Sin Datos"; } else { echo invertirFecha($fechaInicio); }?> </b></p>
  	<?php if ($tipo == "baja") {?>
   		<p><b>Fecha Baja Empresa: <?php echo invertirFecha($fechaBaja) ?></b></p>
	<?php } ?>	
	
    <table width="1024" border="1" style="text-align:center; font-size:12px">
	  <tr>
	    <td width="52" rowspan="2"><b>AÑOS</b></td>
	    <td colspan="12"><b>MESES</b></td>
	  </tr>
	  <tr> 
		<td width="81"><b>Enero</b></td>
	    <td width="81"><b>Febrero</b></td>
	    <td width="81"><b>Marzo</b></td>
	    <td width="81"><b>Abril</b></td>
	    <td width="81"><b>Mayo</b></td>
	    <td width="81"><b>Junio</b></td>
	    <td width="81"><b>Julio</b></td>
	    <td width="81"><b>Agosto</b></td>
	    <td width="81"><b>Setiembre</b></td>
	    <td width="81"><b>Octubre</b></td>
	    <td width="81"><b>Noviembre</b></td>
	    <td width="81"><b>Diciembre</b></td>
	  </tr>
<?php while($ano<=$anofin) { ?>
	  <tr>
		<td width='52'><b><?php echo $ano ?></b></td>
	<?php	for ($i=1;$i<13;$i++) {
				$idArray = $ano.$i;
				if (!array_key_exists($idArray, $arrayPagos)) {
					$estado = estado($ano, $i, $db);
					if ($estado == 'NO PAGO' || $estado == 'S.DJ.') {
						$resultado = reverificaPeriodo ( $estado, $ano, $i, $db );
					} else {
						$resultado = $estado;
					}
					$arrayPagos[$idArray] =  array('anio' => $ano, 'mes' => $i, 'estado' => $resultado);
				} else {
					$estado = $arrayPagos[$idArray]['estado'];
					if($estado == 'P.F.T.') {
						$resultado = reverificaPeriodo($estado, $ano, $i, $db);
						$arrayPagos[$idArray] =  array('anio' => $ano, 'mes' => $i, 'estado' => $resultado);
					}
				}
				imprimeTabla($arrayPagos[$idArray]);
			}
			$ano++; ?>
		</tr>
<?php } ?>
	</table>
	<table width="1024" border="0" style="font-size:12px; margin-top: 15px">
	  <tr>
	  	<td>*PAGO =  PAGO CON DDJJ</td>
		<td>*P. ACUER. =  PAGO POR ACUERDO </td>
	    <td>*P.F.T. = PAGO FUERA DE TERMINO </td>
		<td>*ACUER. =  EN ACUERDO</td>
	  </tr>
	  <tr>
	    <td>*ACU. INC. = ACUERDO INCOBRABLE</td>
	    <td>*NO PAGO =  NO PAGO CON DDJJ</td>
		<td>*S. DJ.=  NO PAGO SIN DDJJ</td>
		<td>*REQ. (nro. requerimiento) = FISCALIZADO</td>
	  </tr>
	  <tr>
	  	<td>*J.EJEC (nro. orden) = EN JUICIO EJECUCIÓN </td>
	    <td>*J.CONV (nro. orden) = EN JUICIO CONVOCATORIA </td>
	    <td>*J.QUIEB (nro. orden) = EN JUICIO QUIEBRA </td>
	    <td>&nbsp;</td>
	  </tr>
	</table>
	<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>
