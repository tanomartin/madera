<?php

function descripcionPractica($codigoPractica, $db) {
	$codigoArray = explode(".",$codigoPractica);
	if (sizeof($codigoArray) == 1) {
		$tipoPractica = 1;
		$sqlTipoPractica = "SELECT descripcion FROM tipopracticas WHERE id = $tipoPractica";
		$resTipoPractica = mysql_query($sqlTipoPractica,$db);
		$rowTipoPractica = mysql_fetch_array($resTipoPractica);
		$resultado['tipo'] = $rowTipoPractica['descripcion'];
		$resultado['codigosubcapitulo'] = "";
		$resultado['subcapitulo'] = "";
		$resultado['codigocapitulo'] = "";
		$resultado['capitulo'] = "";	
	}
	
	if (sizeof($codigoArray) == 2) {
		$codCapitulo = $codigoArray[0];
		$sqlTipoPractica = "SELECT c.descripcion as cap, t.descripcion as tipoprac FROM tipopracticas t, capitulosdepracticas c WHERE c.codigo = '$codCapitulo' and c.idtipopractica != 2 and c.idtipopractica = t.id";
		$resTipoPractica = mysql_query($sqlTipoPractica,$db);
		$rowTipoPractica = mysql_fetch_array($resTipoPractica);
		$resultado['tipo'] = $rowTipoPractica['tipoprac'];
		$resultado['codigosubcapitulo'] = "";
		$resultado['subcapitulo'] = "";
		$resultado['codigocapitulo'] = $codCapitulo;
		$resultado['capitulo'] =  $rowTipoPractica['cap'];
	}
	if (sizeof($codigoArray) == 3) {
		$tipoPractica = 2;
		$codCapitulo = $codigoArray[0];
		$codSubCapitulo = $codigoArray[1];
		$sqlTipoPractica = "SELECT c.descripcion as cap, t.descripcion as tipoprac, s.descripcion as subcap FROM tipopracticas t, capitulosdepracticas c, subcapitulosdepracticas s WHERE s.codigo = '$codCapitulo.$codSubCapitulo' and s.idcapitulo = c.id and c.idtipopractica = t.id";
		$resTipoPractica = mysql_query($sqlTipoPractica,$db);
		$rowTipoPractica = mysql_fetch_array($resTipoPractica);
		$resultado['tipo'] = $rowTipoPractica['tipoprac'];
		$resultado['codigosubcapitulo'] = $codSubCapitulo;
		$resultado['subcapitulo'] = $rowTipoPractica['subcap'];
		$resultado['codigocapitulo'] = $codCapitulo;
		$resultado['capitulo'] = $rowTipoPractica['cap'];
	}
	return ($resultado);
}

?>