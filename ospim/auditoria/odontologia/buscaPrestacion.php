<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
if(isset($_GET)) {
	$busqueda = $_GET['getPrestacion'];
	$idprestador = $_GET['idPrestador'];
	$noencontro = TRUE;
	$prestaciones = array();
	
	$sqlConsultaNomenclador = "SELECT codigonomenclador 
								FROM prestadornomenclador 
								WHERE codigoprestador = $idprestador AND codigonomenclador != 7";
	$resConsultaNomenclador = mysql_query($sqlConsultaNomenclador,$db);
	if(mysql_num_rows($resConsultaNomenclador)!=0) {
		$wherein = '';
		while($rowConsultaNomenclador = mysql_fetch_array($resConsultaNomenclador)) {
			$wherein .= $rowConsultaNomenclador['codigonomenclador'].',';
		}
		$wherein = substr($wherein, 0, -1);
		
		$sqlLeePracticasNomenclador="SELECT p.idpractica, p.codigopractica, SUBSTRING(p.descripcion,1,45) AS nombrepractica, n.nombre FROM tipopracticasnomenclador t, practicas p, nomencladores n
										WHERE
										t.codigonomenclador IN ($wherein) AND
										t.idtipo = 3 AND
										t.id = p.tipopractica AND
										(p.codigopractica LIKE '%$busqueda%' OR p.descripcion LIKE '%$busqueda%') AND
										p.nomenclador = n.id";
		$resLeePracticasNomenclador=mysql_query($sqlLeePracticasNomenclador,$db);
		if(mysql_num_rows($resLeePracticasNomenclador)!=0) {
			while($rowLeePracticasNomenclador = mysql_fetch_array($resLeePracticasNomenclador)) {
				$prestaciones[] = array(
						'label' => $rowLeePracticasNomenclador['nombrepractica'].' | Codigo: '.$rowLeePracticasNomenclador['codigopractica'].' | Origen: Nomenclador '.$rowLeePracticasNomenclador['nombre'],
						'idpractica' => $rowLeePracticasNomenclador['idpractica'],
				);
			}
		} else {
			$prestaciones[] = array(
					'label' => 'No se encontraron resultados para la busqueda intentada',
					'idpractica' => NULL,
			);
		}
	} else {
		$prestaciones[] = array(
				'label' => 'No se encontraron resultados para la busqueda intentada',
				'idpractica' => NULL,
		);
	}

	echo json_encode($prestaciones);
	return; 
}
?>