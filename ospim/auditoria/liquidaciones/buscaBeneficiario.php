<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['getBeneficiaro'])) {
	$busqueda = $_GET['getBeneficiaro'];
	$noencontro = TRUE;
	$beneficiarios = array();  
	if(is_numeric($busqueda)) {
		$sqlLeeTitulares="SELECT nroafiliado, apellidoynombre, cuil, codidelega FROM titulares WHERE cuil like '%$busqueda%' OR nroafiliado = $busqueda";
		$sqlLeeFamiliares="SELECT f.nroafiliado, f.tipoparentesco, p.descrip, f.nroorden, f.apellidoynombre, f.cuil, t.codidelega FROM familiares f, titulares t, parentesco p WHERE (f.cuil like '%$busqueda%' OR f.nroafiliado = $busqueda) AND f.nroafiliado = t.nroafiliado AND f.tipoparentesco = p.codparent";
		//$sqlLeeBeneficiarios="SELECT nroafiliado, apellidoynombre, cuil FROM titulares WHERE nroafiliado = $busqueda";
	} else {
		$sqlLeeTitulares="SELECT nroafiliado, apellidoynombre, cuil, codidelega FROM titulares WHERE apellidoynombre like '%$busqueda%'";
		$sqlLeeFamiliares="SELECT f.nroafiliado, f.tipoparentesco, p.descrip, f.nroorden, f.apellidoynombre, f.cuil, t.codidelega FROM familiares f, titulares t, parentesco p WHERE f.apellidoynombre like '%$busqueda%' AND f.nroafiliado = t.nroafiliado AND f.tipoparentesco = p.codparent";
	}
	$resLeeTitulares=mysql_query($sqlLeeTitulares,$db);
	if(mysql_num_rows($resLeeTitulares)!=0) {
		while($rowLeeTitulares=mysql_fetch_array($resLeeTitulares)) {
			$noencontro = FALSE;
			$beneficiarios[] = array(
				'label' => $rowLeeTitulares['apellidoynombre'].' | CUIL: '.$rowLeeTitulares['cuil'].' | Nro. Afiliado: '.$rowLeeTitulares['nroafiliado'].' | Tipo: Titular',
				'nroafiliado' => $rowLeeTitulares['nroafiliado'],
				'tipoafiliado' => 0,
				'nroorden' => 0,
				'delegacion' => $rowLeeTitulares['codidelega'],
			);
		}
	}
	$resLeeFamiliares=mysql_query($sqlLeeFamiliares,$db);
	if(mysql_num_rows($resLeeFamiliares)!=0) {
		while($rowLeeFamiliares=mysql_fetch_array($resLeeFamiliares)) {
			$noencontro = FALSE;
			$tipo = utf8_encode($rowLeeFamiliares['descrip']);
			$beneficiarios[] = array(
				'label' => $rowLeeFamiliares['apellidoynombre'].' | CUIL: '.$rowLeeFamiliares['cuil'].' | Nro. Afiliado: '.$rowLeeFamiliares['nroafiliado'].' | Tipo: '.$tipo,
				'nroafiliado' => $rowLeeFamiliares['nroafiliado'],
				'tipoafiliado' => $rowLeeFamiliares['tipoparentesco'],
				'nroorden' => $rowLeeFamiliares['nroorden'],
				'delegacion' => $rowLeeFamiliares['codidelega'],
			);
		}
	}
	if($noencontro) {
		$beneficiarios[] = array(
			'label' => 'No se encontraron resultados para la busqueda intentada',
			'nroafiliado' => NULL,
			'tipoafiliado' => NULL,
			'nroorden' => NULL,
			'delegacion' => NULL,
		);
	}
	echo json_encode($beneficiarios);
	return; 
}  
?>