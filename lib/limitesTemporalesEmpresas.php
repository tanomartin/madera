<?php
//$ano = 2004;
//$anofin = 2014;
//$mesactual = 2;
//$diahoy = 1;
$ano = date("Y")-10;
$anofin = date("Y");
$mesactual = date("n");
$diahoy = date("j");

if ($diahoy > 15) {
	$mesfin = $mesactual - 1;
} else {
	if ($mesactual == 1) {
		$anofin = $anofin - 1;
		$ano = $anofin - 11;
		$anoinicio = $ano;
		$mesfin = 11;
	} else {
		$mesfin = $mesactual - 2;
	}
} 
$mesinicio = $mesfin + 1;
/*print("ANIO INICIO COMIENZO: ".$ano."<br>");
print("MES INICIO COMIENZO: ".$mesinicio."<br>");
print("ANIO FIN COMIENZO: ".$anofin."<br>");
print("MES FIN COMIENZO: ".$mesfin."<br>");*/

if ($fechaInicio != "0000-00-00") {
	$anioInicioActi = substr($fechaInicio,0,4);
	$mesInicioActi = substr($fechaInicio,5,2);
	//print("ANIO INICIO ACTIVIDAD: ".$anioInicioActi."<br>");
	//print("MES INICIO ACTIVIDAD: ".$mesInicioActi."<br>");
	if ($anioInicioActi > $ano) {
		$ano = $anioInicioActi;
		if ($mesInicioActi < $mesinicio) {
			$mesinicio = (int)$mesInicioActi;
		}
	}
	if ($anioInicioActi == $ano) {
		if ($mesInicioActi > $mesinicio) {
			$mesinicio = (int)$mesInicioActi;
		}
	}
}

if (isset($tipo)) {
	if ($tipo == "baja") {
		if ($fechaBaja != "0000-00-00") {
			$anioBajaActi = substr($fechaBaja,0,4);
			$mesBajaActi = substr($fechaBaja,5,2);
			//print("ANIO FIN ACTIVIDAD: ".$anioBajaActi."<br>");
			//print("MES FIN ACTIVIDAD: ".$mesBajaActi."<br>");
			if ($anioBajaActi <= $anofin) {
				$anofin = $anioBajaActi;
				if ($mesBajaActi < $mesfin) {
					$mesfin = (int)$mesBajaActi;
				} 
			}
		}
	}
}

$anoinicio = $ano;
/*print("<br>::::::::::::::PARA TRABAJAR QUEDO ESTO:::::::::::<br>");
print("ANIO INICIO: ".$anoinicio."<br>");
print("MES INICIO: ".$mesinicio."<br>");
print("ANIO FIN: ".$anofin."<br>");
print("MES FIN: ".$mesfin."<br>");*/

?>