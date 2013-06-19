<?php 
function invertirFecha($fecha) {
	$dia = substr($fecha,8,2);
	$mes = substr($fecha,5,2);
	$anio = substr($fecha,0,4);
	$fechainv = $dia."/".$mes."/".$anio;
	return($fechainv);
}

function getDia($fecha) {
	$dia = substr($fecha,8,2);
	return($dia);
}

function getMes($fecha) {
	$mes = substr($fecha,5,2);
	return($dia);
}

function getAnio($fecha) {
	$anio = substr($fecha,0,4);
	return($dia);
}

function fechaParaGuardar($fecha) {
	$dia = substr($fecha,0,2);
	$mes = substr($fecha,3,2);
	$anio = substr($fecha,6,4);
	$fechaLista = $anio."-".$mes."-".$dia;
	return($fechaLista);
}
?>