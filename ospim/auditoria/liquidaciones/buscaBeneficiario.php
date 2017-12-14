<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['getBeneficiaro'])) {
	$busqueda = $_GET['getBeneficiaro'];
	$beneficiarios = array();  
	if(is_numeric($busqueda)) {
		$sqlLeeBeneficiarios="SELECT nroafiliado, apellidoynombre, cuil, codidelega FROM titulares WHERE cuil like '%$busqueda%' OR nroafiliado = $busqueda";
		//$sqlLeeBeneficiarios="SELECT nroafiliado, apellidoynombre, cuil FROM titulares WHERE nroafiliado = $busqueda";
	} else {
		$sqlLeeBeneficiarios="SELECT nroafiliado, apellidoynombre, cuil, codidelega FROM titulares WHERE apellidoynombre like '%$busqueda%'";
	}
	$resLeeBeneficiarios=mysql_query($sqlLeeBeneficiarios,$db);
	if(mysql_num_rows($resLeeBeneficiarios)!=0) {
		while($rowLeeBeneficiarios=mysql_fetch_array($resLeeBeneficiarios)) {
			$beneficiarios[] = array(
				'label' => $rowLeeBeneficiarios['apellidoynombre'].' | CUIL: '.$rowLeeBeneficiarios['cuil'].' | Nro. Afiliado: '.$rowLeeBeneficiarios['nroafiliado'].' | Tipo: Titular',
				'nroafiliado' => $rowLeeBeneficiarios['nroafiliado'],
				'tipoafiliado' => 0,
				'nroorden' => 0,
				'delegacion' => $rowLeeBeneficiarios['codidelega'],
			);
		}
	} else {
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