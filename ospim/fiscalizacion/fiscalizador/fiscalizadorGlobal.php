<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

function esMontoMenor($cuit, $importe, $me, $ano, $db) {
	$alicuota = 0.081;
	$sqlDDJJ = "select totalremundeclarada, totalremundecreto, totalpersonal from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
	$resDDJJ = mysql_query($sqlDDJJ,$db);
	$CanDDJJ = mysql_num_rows($resDDJJ); 
	if ($CanDDJJ != 0) {
		$rowDDJJ = mysql_fetch_assoc($resDDJJ);
		$remuDDJJ = $rowDDJJ['totalremundeclarada'] + $rowDDJJ['totalremundecreto'];
		$valor81 = (float)($remuDDJJ * $alicuota );
		if ($importe < $valor81) {
			$resultadoMenor = array('remu' => $remuDDJJ, 'totper' => $rowDDJJ['totalpersonal']);
			return($resultadoMenor);
		}
	}
	return(0);
}

function estaVencido($cuit, $fechaPago, $me, $ano, $db) {
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
		$sqlDDJJ = "select totalremundeclarada, totalremundecreto, totalpersonal from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
		$resDDJJ = mysql_query($sqlDDJJ,$db);
		$CanDDJJ = mysql_num_rows($resDDJJ); 
		if ($CanDDJJ != 0) {
			$rowDDJJ = mysql_fetch_assoc($resDDJJ);
			$remuDDJJ = $rowDDJJ['totalremundeclarada'] + $rowDDJJ['totalremundecreto'];
			$resultadoVto = array('remu' => $remuDDJJ, 'totper' => $rowDDJJ['totalpersonal']);
		} else {
			$resultadoVto = array('remu' => 0.00, 'totper' => 0);
		}
		return($resultadoVto);
	}
	return(0);
}

function fiscalizoPagos($cuit, $arrayPagos, $db) {
	$resultado = array();
	foreach ($arrayPagos as $pago){
		$anio = $pago['anio'];
		$mes = $pago['mes'];
		$id=$anio.$mes; 
		if ($pago['estado']	== 'PAGO') {
			$importe = $pago['importe'];
			$fechaPago = $pago['fechapago'];
			$resMenor = esMontoMenor($cuit, $importe, $mes, $anio, $db);
			if ($resMenor != 0) {
				$resultado[$id] = array('anio' => (int)$anio, 'mes' => (int)$mes, 'remu' => $resMenor['remu'],'totper' => $resMenor['totper'], 'importe' => $importe, 'estado' => 'M');
			} else {
				$resVto = estaVencido($cuit, $fechaPago, $mes, $anio, $db);
				if ($resVto != 0) {
					$resultado[$id] = array('anio' => (int)$anio, 'mes' => (int)$mes, 'remu' => $resVto['remu'],'totper' => $resVto['totper'], 'importe' => $importe, 'estado' => 'F');
				} else {
					$resultado[$id] = array('anio' => (int)$anio, 'mes' => (int)$mes, 'importe' => $importe, 'estado' => 'P');
				}
			}
		} else {
			$resultado[$id] = $pago;
		}
	}
	if (sizeof($resultado) != 0) {
		return($resultado);
	} else {
		return(0);
	}
}

/****************************************************************************************/

$listadoSerializado=$_POST['empresas'];
$listadoEmpresas = unserialize(urldecode($listadoSerializado));
$solicitante=$_POST['solicitante'];
$motivo = "Selección Automática"; 
$origen = 1;


$f = 0;
for($i=0; $i < sizeof($listadoEmpresas); $i++) {
	//print("CUIT: ".$listadoEmpresas[$i]['cuit']);
	$resultado = fiscalizoPagos($listadoEmpresas[$i]['cuit'], $listadoEmpresas[$i]['deudas'], $db);
	//var_dump($resultado);
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
	$.blockUI({ message: "<h1>Grabando Requerimientos de Fiscalización... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("fiscalizador").submit();
	}
</script>

<body onload="formSubmit();">
<form action="grabaRequerimientos.php" id="fiscalizador" method="POST"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>">
   <input name="datosReq" type="hidden" value="<?php echo $listadoDatosReq ?>">
</form> 
</body>