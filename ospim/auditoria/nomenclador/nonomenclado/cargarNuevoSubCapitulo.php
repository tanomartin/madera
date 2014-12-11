<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
if(isset($_POST['tipo'])) {	
	$tipo = $_POST['tipo'];
	$capitulo = $_POST['capitulo'];
	$capituloArray = explode("-",$capitulo);
	$idCap = $capituloArray[0];
	$codCap = str_pad($capituloArray[1],2,'0',STR_PAD_LEFT);

	$sqlCodigosSubCap = "SELECT * FROM subcapitulosdepracticas WHERE idcapitulo = $idCap";
	$resCodigosSubCap = mysql_query($sqlCodigosSubCap,$db);	
	$n=0;
	$codigosUsados = array();
	while($rowCodigosCap = mysql_fetch_assoc($resCodigosSubCap)) {
		$codigoSoloArray = explode(".",$rowCodigosCap['codigo']);
		$codigosUsados[$n] = str_pad($codigoSoloArray[1],2,'0',STR_PAD_LEFT);
		$n++;
	}
	$codPosibles = 1;
	for($i=0; $i<99; $i++) {
		$codigosHabilitados[$i] = str_pad($codPosibles,2,'0',STR_PAD_LEFT);
		$codPosibles++;
	
	}	
	$difCodigos = array_diff($codigosHabilitados, $codigosUsados);
	$codigoPropuesto = current($difCodigos);
	$respuesta = "<p><span class='Estilo2'>Carga Nuevo SubCapitulo</span></p>
				  <p>Codigo Capitulo: <b> $codCap. </b> <input type='text' id='codigo' name='codigo' value='$codigoPropuesto' size='2'/></p>
				  <label> <input type='text' id='codcapitulo' name='codcapitulo' value='$codCap' size='4' readonly style='visibility:hidden'/>
				  		  <input type='text' id='idcapitulo' name='idcapitulo' value='$idCap' size='4' readonly style='visibility:hidden'/>	
				  		  Descripcion: <textarea id='descri' name='descri' cols='100' rows='3'></textarea>
				  </label>
				  <p><input type='submit' name='Submit' value='Guardar' sub/></p>";
	echo $respuesta;
}
?>