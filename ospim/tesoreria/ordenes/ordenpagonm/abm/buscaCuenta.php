<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['getCuenta'])) {
	$busqueda = $_GET['getCuenta'];
	$cuentas = array();  
	$sqlCuenta = "SELECT * FROM cuentasospim WHERE titulo = 'BANCO NACION ARGENTINA' and (nrocta like '%$busqueda%' or titulo like '%$busqueda%' or descripcion like '%$busqueda%')";
	$resCuenta = mysql_query($sqlCuenta,$db);
	$canCuenta = mysql_num_rows($resCuenta);
	if ($canCuenta > 0) {
		while($rowCuenta = mysql_fetch_array($resCuenta)) {
			$cuentas[] = array(
				'label' => 'CTA: '.$rowCuenta['nrocta'].' | TITULO: '.$rowCuenta['titulo'].' | DESC: '.$rowCuenta['descripcion'],
				'codigocuenta' => $rowCuenta['id'],  
			);
		}
	} else {
		$cuentas[] = array(
				'label' => 'SIN RESULTADOS',
				'codigocuenta' => "",
		);
	}
  	echo json_encode($cuentas);  
  	return; 
}  
?>