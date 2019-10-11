<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

if(isset($_POST['acuerdo'])) {
	$cuit = $_POST['nrcuit'];
	$acuerdo = $_POST['acuerdo'];
	$sqlPeriodos = "select * from detacuerdosospim where cuit = $cuit and nroacuerdo = $acuerdo";
	$resPeriodos = mysql_query($sqlPeriodos,$db); 
	$periodos = array();
	$i=0;
	while ($rowPeriodos = mysql_fetch_assoc($resPeriodos)) {
		$periodos[$i] = $rowPeriodos;
		$i++;
	}
	echo json_encode($periodos);
}
?>
