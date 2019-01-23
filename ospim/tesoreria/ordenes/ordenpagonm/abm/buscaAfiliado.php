<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['getAfiliado'])) {
	$busqueda = $_GET['getAfiliado'];
	$codidelega = $_GET['codidelega'];
	if ($codidelega != "") {
		$sqlAfiliado = "SELECT nroafiliado, apellidoynombre, cuil FROM titulares WHERE codidelega = $codidelega and (nroafiliado like '%$busqueda%' or apellidoynombre like '%$busqueda%' or cuil like '%$busqueda%')";
	} else {
		$sqlAfiliado = "SELECT nroafiliado, apellidoynombre, cuil FROM titulares WHERE nroafiliado like '%$busqueda%' or apellidoynombre like '%$busqueda%' or cuil like '%$busqueda%'";
	}
	$resAfiliado = mysql_query($sqlAfiliado,$db);
	$canAfiliado = mysql_num_rows($resAfiliado);
	$afiliados = array();
	if ($canAfiliado > 0) {
		$whereIn = "(";
		while($rowAfiliado = mysql_fetch_array($resAfiliado)) {
			$whereIn .= "'".$rowAfiliado['nroafiliado']."',";
			$afiliados[] = array(
				'label' => $rowAfiliado['nroafiliado'].' | T | '.$rowAfiliado['apellidoynombre'].' | '.$rowAfiliado['cuil'],
				'nroafiliado' => $rowAfiliado['nroafiliado']."-0",
			);
		}
		$whereIn = substr($whereIn, 0, -1);
		$whereIn .= ")";
		
		$sqlAfiFami = "SELECT nroafiliado, nroorden, apellidoynombre, cuil FROM familiares WHERE nroafiliado in $whereIn";
		$resAfiFami = mysql_query($sqlAfiFami,$db);
		$canAfiFami = mysql_num_rows($resAfiFami);
		if ($canAfiFami > 0) {
			while($rowAfiFami = mysql_fetch_array($resAfiFami)) {
				$afiliados[] = array(
						'label' => $rowAfiFami['nroafiliado'].' | F |'.$rowAfiFami['apellidoynombre'].' | '.$rowAfiFami['cuil'],
						'nroafiliado' => $rowAfiFami['nroafiliado']."-".$rowAfiFami['nroorden'],
				);
			}
		}
	} else {
		$afiliados[] = array(
				'label' => 'SIN RESULTADOS',
				'nroafiliado' => 0, 'sql' => $sqlAfiliado,
		);
	}
	
	sort($afiliados);
	
  	echo json_encode($afiliados);  
  	return; 
}  
