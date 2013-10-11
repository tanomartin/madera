<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

function encuentroPagos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlPagos = "select anopago, mespago, fechapago, debitocredito, sum(importe) from afipprocesadas where cuit = $cuit and concepto != 'REM' and ((anopago > $anoinicio and anopago <= $anofin) or (anopago = $anoinicio and mespago >= $mesinicio)) group by anopago, mespago, debitocredito, fechapago order by anopago, mespago, fechapago";
	$resPagos = mysql_query($sqlPagos,$db);
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
		$idanterior = "";
		while ($rowPagos = mysql_fetch_assoc($resPagos)) { 
			$id=$rowPagos['anopago'].$rowPagos['mespago'];
			if ($rowPagos['debitocredito'] == 'C') {
				$importe = (float)$rowPagos['sum(importe)'];
			} else {
				$importe = (float)("-".$rowPagos['sum(importe)']);
			}
			if ($id == $idanterior) {
				$importeArray = $arrayPagos[$id]['importe'];
				$importe = $importeArray + $importe;
			} else {
				$idanterior = $id;
			}
			$arrayPagos[$id] = array('anio' => (int)$rowPagos['anopago'], 'mes' => (int)$rowPagos['mespago'], 'importe' => $importe, 'fechapago' => $rowPagos['fechapago'], 'estado' => 'PAGO');
		}
		return($arrayPagos);
	} else {
		return(0);
	}
}

function estado($ano, $me, $cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
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
	
	// VEO LOS PERIODOS ABARCADOS POR ACUERDO
	$sqlAcuerdos = "select cuit from detacuerdosospim where cuit = $cuit and anoacuerdo = $ano and mesacuerdo = $me";
	$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
	$CantAcuerdos = mysql_num_rows($resAcuerdos); 		
	if($CantAcuerdos == 0) {
		//VEO LOS JUICIOS
		$sqlJuicio = "select c.nroorden from cabjuiciosospim c, detjuiciosospim d where c.cuit = $cuit and c.nroorden = d.nroorden and d.anojuicio = $ano and d.mesjuicio = $me";
		$resJuicio = mysql_query($sqlJuicio,$db); 
		$CantJuicio = mysql_num_rows($resJuicio); 
		if ($CantJuicio == 0) {
			// VEO LOS REQ DE FISC
			$sqlReq = "select r.nrorequerimiento from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
			$resReq = mysql_query($sqlReq,$db); 
			$CantReq = mysql_num_rows($resReq); 
			if($CantReq == 0) {
				// VEO LAS DDJJ REALIZADAS SIN PAGOS
				$sqlDDJJ = "select totalremundeclarada, totalremundecreto, totalpersonal from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
				$resDDJJ = mysql_query($sqlDDJJ,$db); 
				$CantDDJJ = mysql_num_rows($resDDJJ); 
				if($CantDDJJ > 0) {
					$rowDDJJ = mysql_fetch_assoc($resDDJJ);
					$totalRemu = $rowDDJJ['totalremundeclarada'] + $rowDDJJ['totalremundecreto'];
					$res = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'A', 'remu' => $totalRemu, 'totper' => (int)$rowDDJJ['totalpersonal'] );
					return($res);
				} else {
					// NO HAY DDJJ SIN PAGOS
					$res = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'S');
					return($res);
				} //else DDJJ
			}//if REQ
		} //if JUICIOS
	}//if ACUERDOS
	return(0);
} //function

function deudaNominal($arrayPagos) {
	$totalDeuda = 0;
	$alicuota = 0.081;
	foreach ($arrayPagos as $pago){
		if($pago['estado'] == "A") {
			$totalDeuda = $totalDeuda + $pago['remu'];
		}
	}
	return($totalDeuda * $alicuota);
}

/****************************************************************************************/

$listadoSerializado=$_POST['empresas'];
$filtrosSerializado=$_POST['filtros'];

$listadoEmpresas = unserialize(urldecode($listadoSerializado));
$filtros = unserialize(urldecode($filtrosSerializado));

//var_dump($listadoEmpresas);
//var_dump($filtros);


$empre = 0;
for ($i=0; $i < sizeof($listadoEmpresas); $i++) {
	if ($empre < $filtros['empresas']) {
		$cuit = $listadoEmpresas[$i]['cuit'];
		$fechaInicio = $listadoEmpresas[$i]['iniobliosp'];
		include($_SERVER['DOCUMENT_ROOT']."/lib/limitesTemporalesEmpresas.php");
		$arrayPagos = encuentroPagos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
		if ($arrayPagos == 0){ 
			$arrayPagos = array();
		}
		while($ano<=$anofin) {
			for ($m=1;$m<13;$m++){
				$idArray = $ano.$m;
				if (!array_key_exists($idArray, $arrayPagos)) {
					$resultado = estado($ano, $m, $cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
					if ($resultado != 0) {
						$arrayPagos[$idArray] =  $resultado;
					}
				}
			}
			$ano++;
		}
		$deudaNominal = deudaNominal($arrayPagos);
		//print("CUIT: ".$cuit." - DEUDA: ".$deudaNominal."<br>");
		//var_dump($arrayPagos);
		if($deudaNominal > $filtros['deuda']) {
			$empresasDefinitivas[$empre] = array('cuit' => $cuit, 'deudas' => $arrayPagos);
			$empre = $empre + 1;
		}
	} else {
		$i = sizeof($listadoEmpresas);
	}
}


if (sizeof($empresasDefinitivas) == 0) {
	header ("Location: menuFiscalizador.php?err=4");
} else {
	$listadoSerializado = serialize($empresasDefinitivas);
	$listadoSerializado = urlencode($listadoSerializado);
}

//var_dump($empresasDefinitivas);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizador OSPIM :.</title>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Fiscalizando empresas Filtradas... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("filtroDeudaNominal").submit();
	}
</script>

<body onload="formSubmit();">
<form action="fiscalizadorGlobal.php" id="filtroDeudaNominal" method="POST"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>">
   <input name="solicitante" type="hidden" value="<?php echo $filtros['solicitante'] ?>">
</form> 
</body>