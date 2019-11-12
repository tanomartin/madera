<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['getPrestador'])) {
	$busqueda = $_GET['getPrestador'];
	$prestadores = array();  
	if(is_numeric($busqueda)) {
		$sqlLeePrestadores="SELECT codigoprestador, nombre, cuit FROM prestadores WHERE cuit like '%$busqueda%' OR codigoprestador = $busqueda";
	} else {
		$sqlLeePrestadores="SELECT codigoprestador, nombre, cuit FROM prestadores WHERE nombre like '%$busqueda%'";
	}
	if($resLeePrestadores=mysql_query($sqlLeePrestadores,$db)) {
		while($rowLeePrestadores=mysql_fetch_array($resLeePrestadores)) {
			$nombre = utf8_encode($rowLeePrestadores['nombre']);
			$prestadores[] = array(
				'label' => $nombre.' | CUIT: '.$rowLeePrestadores['cuit'].' | Codigo: '.$rowLeePrestadores['codigoprestador'],
				'codigoprestador' => $rowLeePrestadores['codigoprestador'],  
			);
		}
	}
  echo json_encode($prestadores);  
  return; 
}  
?>