<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
if(isset($_POST['matricula'])) {
	$matricula = $_POST['matricula'];
	$sqlPresta = "SELECT codigoprestador FROM prestadores WHERE matriculanacional = '$matricula'";
	$resPresta = mysql_query($sqlPresta,$db);
	$canPresta = mysql_num_rows($resPresta);
	if ($canPresta == 0) {
		$respuesta = 0;
	} else {
		$respuesta = 1;
	}
	echo $respuesta;
}
?>