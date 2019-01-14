<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['getCuenta'])) {
	$busqueda = $_GET['getCuenta'];
	$cuentas = array();  
	$sqlCuenta = "SELECT * FROM cuentasospim WHERE nrocta like '%$busqueda%' or titulo like '%$busqueda%' or descripcion like '%$busqueda%'";
	$resCuenta = mysql_query($sqlCuenta,$db);
	$canCuenta = mysql_num_rows($resCuenta);
	if ($canCuenta > 0) {
		while($rowCuenta = mysql_fetch_array($resCuenta)) {
			$cuentas[] = array(
				'label' => $rowCuenta['nrocta'].' | '.$rowCuenta['titulo'].' | '.$rowCuenta['descripcion'],
				'codigocuenta' => $rowCuenta['id'], 'pidebene' => $rowCuenta['pidebene'], 'codidelega' => $rowCuenta['codidelega'],
			);
		}
	} else {
		$cuentas[] = array(
				'label' => 'SIN RESULTADOS',
				'codigocuenta' => "", 'pidebene' => 0, 'codidelega' => 0,
		);
	}
  	echo json_encode($cuentas);  
  	return; 
}  
?>