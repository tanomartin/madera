<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
//Para que se vea el blockUI
//print("-");
//*************************

function calcularPagoYFechas($resPagos) {
	//TODO
}

function estaVencido($cuit, $resPagos, $me, $ano, $db) {
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
	while ($rowPagos = mysql_fetch_array($resPagos)) {
		$fechaPago = $rowPagos['fechapago'];
		//sacar el total
		if (strcmp($fechaPago,$fechaStr) > 0) {
			$sqlDDJJ = "select totalremundeclarada, totalremundecreto, totalpersonal from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
			//print($sqlDDJJ."<br>");
			$resDDJJ = mysql_query($sqlDDJJ,$db);
			$CanDDJJ = mysql_num_rows($resDDJJ); 
			if ($CanDDJJ != 0) {
				$rowDDJJ = mysql_fetch_assoc($resDDJJ);
				$remuDDJJ = $rowDDJJ['totalremundeclarada'] + $rowDDJJ['totalremundecreto'];
				$importePago =  calcularPago($resPagos);
				print("IMPORTE PAGO: ".$importePago."<br>");
				//TEGNO QUE CALCULAR EL IMPORTE PAGADO
				$resultadoVto = array('remu' => $remuDDJJ, 'totper' => $rowDDJJ['totalpersonal'], 'importe' => 0);
			} else {
				$resultadoVto = array('remu' => 0.00, 'totper' => 0, 'importe' => 0);
			}
			return($resultadoVto);
		}
	}
	return(0);
}

function esAporteMenor($cuit, $resPagos, $me, $ano, $db) {
	$i = 0;
	$alicuota = 0.081;
	while ($rowPagos = mysql_fetch_array($resPagos)) {
		$pagos[$i] = $rowPagos;
		$i = $i + 1;
	}
	$total = 0;
	for ($n=0; $n < sizeof($pagos); $n++) {
		if ($pagos[$n]['debitocredito'] == 'D') { 
			$total = $total - $pagos[$n]['sum(importe)'];
		} else {
			$total = $total + $pagos[$n]['sum(importe)'];
		}
	}
	$sqlDDJJ = "select totalremundeclarada, totalremundecreto, totalpersonal from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
	//print($sqlDDJJ."<br>");
	$resDDJJ = mysql_query($sqlDDJJ,$db);
	$CanDDJJ = mysql_num_rows($resDDJJ); 
	if ($CanDDJJ != 0) {
		$rowDDJJ = mysql_fetch_assoc($resDDJJ);
		$remuDDJJ = $rowDDJJ['totalremundeclarada'] + $rowDDJJ['totalremundecreto'];
		$valor81 = (float)($remuDDJJ * $alicuota );
		if ($total < $valor81) {
			$resultadoMenor = array('remu' => $remuDDJJ, 'totper' => $rowDDJJ['totalpersonal'], 'importe' => $total);
			return($resultadoMenor);
		}
	}
	//print("COMPARAMOS PAGOS: ".$total." < ".$valor72."<br>");
	return(0);
}

function estado($ano, $me, $cuit, $db) {
	//VEO LOS PAGOS DE AFIP
	$sqlPagos = "select concepto, fechapago, sum(importe), debitocredito from afipprocesadas where cuit = $cuit and anopago = $ano and mespago = $me and concepto != 'REM' group by concepto, fechapago, debitocredito";
	$resPagos = mysql_query($sqlPagos,$db); 
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
		$resultado = esAporteMenor($cuit, $resPagos, $me, $ano, $db);
		if ($resultado != 0) {
			$pago = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'M', 'remu' => $resultado['remu'], 'totper' => (int)$resultado['totper'], 'importe' => $resultado['importe']);
			return($pago);
		} else {
			$resPagos = mysql_query($sqlPagos,$db); 
			$resultado = estaVencido($cuit, $resPagos, $me, $ano, $db);
			if ($resultado != 0) {
				$pago = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'F', 'remu' => $resultado['remu'], 'totper' => (int)$resultado['totper'], 'importe' => $resultado['importe']);
				return($pago);
			} else {
				return(0);
			}
		}
	} else { 
		// VEO LOS PERIODOS ABARCADOS POR ACUERDO
		$sqlAcuerdos = "select cuit from detacuerdosospim where cuit = $cuit and anoacuerdo = $ano and mesacuerdo = $me";
		$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
		$CantAcuerdos = mysql_num_rows($resAcuerdos); 
		if($CantAcuerdos > 0) {
			return(0);
		} else {
			//VEO LOS JUICIOS
			$sqlJuicio = "select c.nroorden from cabjuiciosospim c, detjuiciosospim d where c.cuit=$cuit and c.nroorden = d.nroorden and d.anojuicio = $ano and d.mesjuicio = $me";
			$resJuicio = mysql_query($sqlJuicio,$db); 
			$CantJuicio = mysql_num_rows($resJuicio); 
			if ($CantJuicio > 0) {
				return(0);
			} else {
				// VEO LOS REQ DE FISC
				$sqlReq = "select r.nrorequerimiento from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
				$resReq = mysql_query($sqlReq,$db); 
				$CantReq = mysql_num_rows($resReq); 
				if($CantReq > 0) {
					return(0);
				} else {
					// VEO LAS DDJJ REALIZADAS SIN PAGOS
					$sqlDDJJ = "select totalremundeclarada, totalremundecreto, totalpersonal from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
					$resDDJJ = mysql_query($sqlDDJJ,$db); 
					$CantDDJJ = mysql_num_rows($resDDJJ); 
					if($CantDDJJ > 0) {
						$rowDDJJ = mysql_fetch_assoc($resDDJJ);
						$totalRemu = $rowDDJJ['totalremundeclarada'] + $rowDDJJ['totalremundecreto'];
						$ddjj = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'A', 'remu' => $totalRemu, 'totper' => (int)$rowDDJJ['totalpersonal']);
						return($ddjj);
					} else {
						// NO HAY DDJJ SIN PAGOS
						$ddjj = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'S');
						return($ddjj);
					} //else DDJJ
				}//else REQ
			} //else JUICIOS
		}//else ACUERDOS
	} //else PAGOS 
	return(0);
} //function

function fiscalizador($empresa, $db) {
	$cuit = $empresa['cuit'];
	$s=0;
	for ($m=0; $m < sizeof($empresa['deudas']); $m++) {
		$estado = $empresa['deudas'][$m]['estado'];
		$ano = $empresa['deudas'][$m]['anio'];
		$me = $empresa['deudas'][$m]['mes'];
		if ($estado != 'A' && $estado != 'S') {			
			//print("TRABAJO CON: ".$me."-".$ano."<br>");
			$resultado = estado($ano, $me, $cuit, $db);
			if ($resultado != 0) {
				$deuda[$s] = $resultado;
				$s = $s + 1;
			}
		} else {
			//print("LISTO : ".$me."-".$ano."<br>");
			$deuda[$s] = $empresa['deudas'][$m];
			$s = $s + 1;
		}
	}
	
	if (sizeof($deuda) != 0) {
		return($deuda);
	} else {
		return(0);
	}
}

function estadoArrayDirecto($ano, $me, $anoinicio, $mesinicio, $anofin, $mesfin) {
	if ($ano == $anoinicio) {
		if ($me < $mesinicio) {
			return(0);
		}
	}
	if ($ano == $anofin) {
		if ($me > $mesfin) {
			return(0);
		}
	}
	$res = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'X');
	return($res);
}

function crearArrayDirecto($anoinicio, $mesinicio, $anofin, $mesfin) {
	$ano = $anoinicio;
	$n = 0;
	while($ano<=$anofin) {
		for ($i=1;$i<13;$i++){
			$res = estadoArrayDirecto($ano, $i, $anoinicio, $mesinicio, $anofin, $mesfin);
			if ($res != 0) {
				$datosDeuda[$n] = $res;
				$n = $n + 1;
			}
		}
		$ano++;
	}
	if (sizeof($datosDeuda) > 0) {
		return $datosDeuda;
	} else {
		return(0);
	}	
}

if (isset($_GET['cuit'])) {
 	//print("VINO DE EMPRESA DIRECTA<br>");
	$cuit = $_GET['cuit'];
	$sqlEmpresasInicioActividad = "select iniobliosp from empresas where cuit = $cuit ";
	$resEmpresasInicioActividad = mysql_query($sqlEmpresasInicioActividad,$db);
	$rowEmpresasInicioActividad = mysql_fetch_assoc($resEmpresasInicioActividad);
	$fechaInicio = $rowEmpresasInicioActividad['iniobliosp'];
	include($_SERVER['DOCUMENT_ROOT']."/lib/limitesTemporalesEmpresas.php");
	$deuda = crearArrayDirecto($anoinicio, $mesinicio, $anofin, $mesfin);
	if ($deuda != 0) {
		$listadoEmpresas[0] = array('cuit' => $cuit, 'deudas' => $deuda);
	}
	$origen = $_GET['origen'];
	$solicitante = $_GET['soli'];
	$motivo = $_GET['motivo'];
} else {
	$listadoSerializado=$_POST['empresas'];
	$listadoEmpresas = unserialize(urldecode($listadoSerializado));
	$solicitante=$_POST['solicitante'];
	$motivo = "Selección Automática"; 
	$origen = 1;
}

$f = 0;
for($i=0; $i < sizeof($listadoEmpresas); $i++) {
	$resultado = fiscalizador($listadoEmpresas[$i], $db);
	if ($resultado != 0) {
		$listadoFinal[$f] = array('cuit' => $listadoEmpresas[$i]['cuit'], 'deudas' => $resultado);
		$f = $f + 1;
	}
}

if(sizeof($listadoFinal) == 0) {
	header ("Location: menuFiscalizador.php?err=5");
} else {
	$datosReque['origen'] = $origen;
	$datosReque['motivo'] = $motivo;
	$datosReque['solicitante'] = $solicitante;
	
	$listadoSerializado = serialize($listadoFinal);
	$listadoSerializado = urlencode($listadoSerializado);
	
	$listadoDatosReq = serialize($datosReque);
	$listadoDatosReq = urlencode($listadoDatosReq);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizador OSPIM :.</title>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	//$.blockUI({ message: "<h1>Grabando Requerimientos de Fiscalización... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		//document.getElementById("fiscalizador").submit();
	}
</script>

<body onload="formSubmit();">
<form action="grabaRequerimientos.php" id="fiscalizador" method="POST"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>">
   <input name="datosReq" type="hidden" value="<?php echo $listadoDatosReq ?>">
</form> 
</body>