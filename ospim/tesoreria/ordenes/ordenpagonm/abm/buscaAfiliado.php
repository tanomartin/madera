<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['getAfiliado'])) {
	$busqueda = $_GET['getAfiliado'];
	$codidelega = $_GET['codidelega'];
	if ($codidelega != "") {
		$sqlAfiliado = "SELECT * FROM titulares WHERE codidelega = $codidelega and (nroafiliado like '%$busqueda%' or apellidoynombre like '%$busqueda%' or cuil like '%$busqueda%')";
	} else {
		$sqlAfiliado = "SELECT * FROM titulares WHERE nroafiliado like '%$busqueda%' or apellidoynombre like '%$busqueda%' or cuil like '%$busqueda%'";
	}
	$resAfiliado = mysql_query($sqlAfiliado,$db);
	$canAfiliado = mysql_num_rows($resAfiliado);
	$afiliados = array();
	if ($canAfiliado > 0) {
		while($rowAfiliado = mysql_fetch_array($resAfiliado)) {
			$afiliados[] = array(
				'label' => "T | ". $rowAfiliado['nroafiliado'].' | '.$rowAfiliado['apellidoynombre'].' | '.$rowAfiliado['cuil'],
				'nroafiliado' => $rowAfiliado['nroafiliado']."-0", 'sql' => $sqlAfiliado,
			);
		}
	} else {
		$afiliados[] = array(
				'label' => 'SIN RESULTADOS',
				'nroafiliado' => 0, 'sql' => $sqlAfiliado,
		);
	}
  	echo json_encode($afiliados);  
  	return; 
}  
