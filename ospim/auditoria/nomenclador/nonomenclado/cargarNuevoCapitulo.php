<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['tipo'])) {	
	$tipo = $_POST['tipo'];
	if ($tipo == 2 or $tipo == 4) {
		$sqlCodigosCap = "SELECT * FROM capitulosdepracticas WHERE idtipopractica = 2 or idtipopractica = 4";
	} else {
		$sqlCodigosCap = "SELECT * FROM capitulosdepracticas WHERE idtipopractica = $tipo";
	}
	$resCodigosCap = mysql_query($sqlCodigosCap,$db);	
	$n=0;
	$canCodigosCap = mysql_num_rows($resCodigosCap);
	if ($canCodigosCap > 0) {
		while($rowCodigosCap = mysql_fetch_assoc($resCodigosCap)) {
			$codigosUsados[$n] = str_pad($rowCodigosCap['codigo'],2,'0',STR_PAD_LEFT);
			$n++;
		}
		$codPosibles = 1;
		for($i=0; $i<99; $i++) {
			$codigosHabilitados[$i] = str_pad($codPosibles,2,'0',STR_PAD_LEFT);
			$codPosibles++;
		
		}	
		$difCodigos = array_diff($codigosHabilitados, $codigosUsados);
		$codigoPropuesto = current($difCodigos);
	} else {
		$codigoPropuesto = '01';
	}
	$respuesta = "<h3>Carga Nuevo Capitulo</h3>
					 <p><b>Codigo Capitulo:</b> <input type='text' id='codigo' name='codigo' value='$codigoPropuesto' size='2'/></p>
					 <input type='text' id='tipo' name='tipo' value='$tipo' size='4' style='display:none'/>
					 <p><b>Descripcion:</b> <textarea id='descri' name='descri' cols='100' rows='3'></textarea></p>
					 <p><input type='submit' name='Submit' value='Guardar' sub/></p>";
	echo $respuesta;
}
?>