<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
if(isset($_GET)) {
	$busqueda = $_GET['getPersoneria'];
	$idprestador = $_GET['idPrestador'];
	$idpersoneria = $_GET['idPersoneria'];
	$fechaprestacion = fechaParaGuardar($_GET['fechaPrestacion']);
	$noencontro = TRUE;
	$efectores = array();  
	$circulo = NULL;
	$calidad = NULL;
	if($idpersoneria==3) {
		$tablabusqueda = 'profesionales';
		$campos = 'codigoprofesional AS idefector, nombre';
		$campoid = 'codigoprofesional';
	}
	if($idpersoneria==4 || $idpersoneria==6) {
		$tablabusqueda = 'establecimientos';
		$campos = 'codigo AS idefector, nombre, circulo, calidad, fechainiciocalidad, fechafincalidad';
		$campoid = 'codigo';
	}
	if(is_numeric($busqueda)) {
		$sqlLeeEfectores="SELECT $campos FROM $tablabusqueda WHERE codigoprestador = $idprestador AND $campoid = $busqueda";
	} else {
		$sqlLeeEfectores="SELECT $campos FROM $tablabusqueda WHERE codigoprestador = $idprestador AND nombre like '%$busqueda%'";
	}
	$resLeeEfectores=mysql_query($sqlLeeEfectores,$db);
	if(mysql_num_rows($resLeeEfectores)!=0) {
		while($rowLeeEfectores=mysql_fetch_array($resLeeEfectores)) {
			$noencontro = FALSE;
			$nombreefector = utf8_encode($rowLeeEfectores['nombre']);
			if($idpersoneria==4 || $idpersoneria==6) {
				$circulo = $rowLeeEfectores['circulo'];
				$fechainical=$rowLeeEfectores['fechainiciocalidad'];
				if($rowLeeEfectores['fechafincalidad']==NULL) {
					$fechafincal=date("Y-m-d");
				} else {
					$fechafincal=$rowLeeEfectores['fechafincalidad'];
				}
				if(strcmp($fechainical, $fechaprestacion) <= 0) {
					if(strcmp($fechafincal, $fechaprestacion) >= 0) {
						$calidad = $rowLeeEfectores['calidad'];
					}
				}
			}
			$efectores[] = array(
				'label' => $nombreefector.' | Codigo: '.$rowLeeEfectores['idefector'],
				'idefector' => $rowLeeEfectores['idefector'],
				'circulo' => $circulo,
				'calidad' => $calidad,
			);
		}
	}
	if($noencontro) {
		$efectores[] = array(
			'label' => 'No se encontraron resultados para la busqueda intentada',
			'idefector' => NULL,
			'circulo' => $circulo,
			'calidad' => $calidad,
		);
	}
	echo json_encode($efectores);
	return; 
}  
?>