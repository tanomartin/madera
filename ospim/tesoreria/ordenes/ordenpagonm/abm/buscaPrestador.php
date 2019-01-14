<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['getPresta'])) {
	$busqueda = $_GET['getPresta'];
	$prestadores = array();  
	$sqlPresta = "SELECT * FROM prestadoresnm WHERE codigo like '%$busqueda%' or nombre like '%$busqueda%' or dirigidoa like '%$busqueda%'";
	$resPresta = mysql_query($sqlPresta,$db);
	$canPresta = mysql_num_rows($resPresta);
	if ($canPresta > 0) {
		while($rowPresta = mysql_fetch_array($resPresta)) {
			$prestadores[] = array(
				'label' => 'COD: '.$rowPresta['codigo'].' | R.S:'.$rowPresta['nombre'].' | D:'.$rowPresta['dirigidoa'],
				'codigoprestador' => $rowPresta['codigo'],  
			);
		}
	} else {
		$prestadores[] = array(
				'label' => 'SIN RESULTADOS',
				'codigoprestador' => "",
		);
	}
  	echo json_encode($prestadores);  
  	return; 
}  
?>
