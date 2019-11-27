<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor']) && isset($_POST['tipo'])) {
	$codigosHabilitados = array();
	$codigo=$_POST['valor'];
	$tipo = $_POST['tipo'];
	$n=0;
	
	if ($codigo == -1) {
		$codPosibles = 1000;
		for($i=0; $i<1000; $i++) {
			$codigosHabilitados[$i] = str_pad($codPosibles,4,'0',STR_PAD_LEFT);
			$codPosibles++;
		}
		$sqlCodigosUsados = "SELECT codigopractica FROM practicas WHERE `codigopractica` not like '%.%' and `codigopractica` not like '%.%.%'";
		$resCodigosUsados = mysql_query($sqlCodigosUsados,$db);
		$codigosUsados = array();
		while($rowCodigosUsados = mysql_fetch_array($resCodigosUsados)) {
			$codigosUsados[$n] = str_pad($rowCodigosUsados['codigopractica'],4,'0',STR_PAD_LEFT);
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
	
	$sqlComplejida = "SELECT * FROM tipocomplejidad";
	$resComplejida = mysql_query($sqlComplejida,$db);
	$tipoComplejidad = array();
	while($rowComplejida = mysql_fetch_assoc($resComplejida)) {
		$tipoComplejidad[$rowComplejida['codigocomplejidad']] = $rowComplejida['descripcion'];	
	}
	
	if ($codigo == -1) {
		$inptuCodigo = "<p>Codigo Practica: <input type='text' id='codigo' name='codigo' value='$codigoPropuesto' size='4'/></p>";
	} else {
		$inptuCodigo = "<p>Codigo Practica: <b>$codigo</b>.<input type='text' id='codigo' name='codigo' value='$codigoPropuesto' size='2'/></p>";
	}
	$respuesta = "<p><span class='Estilo2'>Carga Nueva Practica</span></p>
				  $inptuCodigo
				  <input type='text' id='tipopractica' name='tipopractica' value='$tipo' size='2' readonly style='visibility:hidden'/>	
				  <input type='text' id='tipo' name='tipo' value='$codigo' size='4' readonly style='visibility:hidden'/>
				  <label> Descripcion: <textarea id='descri' name='descri' cols='100' rows='3'></textarea> </label>
				  <p> Complejidad: <select name=\"complejidad\" id=\"complejidad\">";
				  while ($complejidad = current($tipoComplejidad)) {
						$respuesta.="<option value=".key($tipoComplejidad).">".$complejidad."</option>";
						next($tipoComplejidad);
				  }
				  $respuesta.= "</select></p>
				  <p> Interancion: 
				  		<select name=\"internacion\" id=\"internacion\">
				  	 		<option value=0 selected>NO</opction>
				  			<option value=1>SI</opction>
				  		</select>
				  </p>
				  <p><input type='submit' name='Submit' value='Guardar' sub/></p>";
	echo $respuesta;
}
?>