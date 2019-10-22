<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['codigo'])) {
	$codigo = $_POST['codigo'];
	$respuesta = "lala";
	$sqlPrestador = "SELECT nombre FROM prestadores WHERE codigoprestador = $codigo";
	$resPrestador = mysql_query($sqlPrestador,$db);
	$canPrestador = mysql_num_rows($resPrestador);
	if ($canPrestador > 0) { 	
		$sqlContratos = "SELECT idcontrato, 
								DATE_FORMAT(fechainicio, '%d-%m-%Y') as fechainicio, 
							    DATE_FORMAT(fechafin, '%d-%m-%Y') as fechafin
							FROM cabcontratoprestador c
							WHERE c.codigoprestador = $codigo and c.idcontratotercero = 0";
		$resContratos = mysql_query($sqlContratos,$db);
		$canContratos = mysql_num_rows($resContratos);
		if ($canContratos > 0) {
			$rowPrestador = mysql_fetch_assoc($resPrestador);
			$respuesta = "<h3 style='color: blue'>".$rowPrestador['nombre']."</h3>";
			while($rowContratos = mysql_fetch_assoc($resContratos)) {
				$fechas = "'".$rowContratos['fechainicio']."','".$rowContratos['fechafin']."'";
				$respuesta .= "<p><input type='radio' id=".$rowContratos['idcontrato']." name='contratoTercero' value=".$rowContratos['idcontrato']." onclick=cargarFechas($fechas) ></input><b>".$rowContratos['idcontrato']."</b> (".$rowContratos['fechainicio']." | ".$rowContratos['fechafin'].")</p>";
			}
		} else {
			$respuesta = "<h3 style='color: blue'>No existen contratos posibles de ser relacionados en este prestador</h3>";
		}
	} else {
		$respuesta = "<h3 style='color: red'>No existen prestador con el codigo $codigo</h3>";
	}
	echo $respuesta;
}
?>