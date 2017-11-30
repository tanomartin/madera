<?php

function descripcionPractica($codigoPractica, $tipoPractica, $db) {
	$codigoArray = explode(".",$codigoPractica);
	$resultado['codigosubcapitulo'] = "";
	$resultado['subcapitulo'] = "";
	$resultado['codigocapitulo'] = "";
	$resultado['capitulo'] =  "";
	if (sizeof($codigoArray) == 2) {
		$codCapitulo = $codigoArray[0];
		$sqlTipoPractica = "SELECT c.descripcion as cap FROM capitulosdepracticas c WHERE c.codigo = '$codCapitulo' and c.idtipopractica = $tipoPractica";
		$resTipoPractica = mysql_query($sqlTipoPractica,$db);
		$rowTipoPractica = mysql_fetch_array($resTipoPractica);
		$resultado['codigosubcapitulo'] = "";
		$resultado['subcapitulo'] = "";
		$resultado['codigocapitulo'] = $codCapitulo;
		$resultado['capitulo'] =  $rowTipoPractica['cap'];
	}
	if (sizeof($codigoArray) == 3) {
		$codCapitulo = $codigoArray[0];
		$codSubCapitulo = $codigoArray[1];
		$sqlTipoPractica = "SELECT c.descripcion as cap, s.descripcion as subcap FROM capitulosdepracticas c, subcapitulosdepracticas s WHERE s.codigo = '$codCapitulo.$codSubCapitulo' and s.idcapitulo = c.id and c.idtipopractica = $tipoPractica";
		$resTipoPractica = mysql_query($sqlTipoPractica,$db);
		$rowTipoPractica = mysql_fetch_array($resTipoPractica);
		$resultado['codigosubcapitulo'] = $codSubCapitulo;
		$resultado['subcapitulo'] = $rowTipoPractica['subcap'];
		$resultado['codigocapitulo'] = $codCapitulo;
		$resultado['capitulo'] = $rowTipoPractica['cap'];
	}
	return ($resultado);
}

?>