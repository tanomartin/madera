<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
if(isset($_POST['valor'])) {
	$codigosHabilitados = array();
	$codigo=$_POST['valor'];
	$n=0;
	
	if ($codigo == -1) {
		$codPosibles = 1000;
		for($i=0; $i<1000; $i++) {
			$codigosHabilitados[$i] = $codPosibles;
			$codPosibles++;
		}
		$sqlCodigosUsados = "SELECT codigopractica FROM practicas WHERE `codigopractica` not like '%.%' and `codigopractica` not like '%.%.%'";
		$resCodigosUsados = mysql_query($sqlCodigosUsados,$db);
		$codigosUsados = array();
		while($rowCodigosUsados = mysql_fetch_array($resCodigosUsados)) {
			$codigosUsados[$n] = $rowCodigosUsados['codigopractica'];
			$n++;
		}
	} else {
		$codPosibles = 1;
		for($i=0; $i<99; $i++) {
			$codigosHabilitados[$i] = str_pad($codPosibles,2,'0',STR_PAD_LEFT);
			$codPosibles++;
		}
		$cantidaPuntos = substr_count($codigo,'.');
		if ($cantidaPuntos == 0) {
			$sqlCodigosUsados="SELECT codigopractica FROM practicas WHERE `codigopractica` like '$codigo.%' and `codigopractica` not like '$codigo.%.%'";
		}
		if ($cantidaPuntos == 1) {
			$sqlCodigosUsados="SELECT codigopractica FROM practicas WHERE `codigopractica` like '$codigo.%'";
		}
		$resCodigosUsados = mysql_query($sqlCodigosUsados,$db);
		$codigosUsados = array();
		while($rowCodigosUsados = mysql_fetch_array($resCodigosUsados)) {
			if ($cantidaPuntos == 0) {
				$codigoCompleto = $rowCodigosUsados['codigopractica'];
				$codigoUsadoArray = explode('.',$codigoCompleto);
				$codigoUsado = $codigoUsadoArray[1];
				
			} 
			if ($cantidaPuntos == 1) {
				$codigoCompleto = $rowCodigosUsados['codigopractica'];
				$codigoUsadoArray = explode('.',$codigoCompleto);
				$codigoUsado = $codigoUsadoArray[2];
			} 
			$codigosUsados[$n] = $codigoUsado;
			$n++;
		}
	}
	$difCodigos = array_diff($codigosHabilitados, $codigosUsados);
	$codigoPropuesto = current($difCodigos);
	
	if ($codigo == -1) {
		$inptuCodigo = "<p>Codigo Practica: <input type='text' id='codigo' name='codigo' value='$codigoPropuesto' size='4'/></p>";
	} else {
		$inptuCodigo = "<p>Codigo Practica: <b>$codigo</b>.<input type='text' id='codigo' name='codigo' value='$codigoPropuesto' size='2'/></p>";
	}
	$respuesta = "<p><span class='Estilo2'>Carga Nueva Practica</span><input type='text' id='tipo' name='tipo' value='$codigo' size='4' readonly/></p>
				  $inptuCodigo
				  <label>Descripcion: <textarea id='descri' name='descri' cols='100' rows='3'></textarea></label>
				  <p><input type='submit' value='Guardar'></p>";
	echo $respuesta;
}
?>