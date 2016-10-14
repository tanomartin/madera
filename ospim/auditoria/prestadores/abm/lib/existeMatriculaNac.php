<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$respuesta = 0;
if(isset($_POST['matricula'])) {
	$matricula = $_POST['matricula'];
	$sqlPresta = "SELECT codigoprestador FROM prestadores WHERE matriculanacional = '$matricula'";
	$resPresta = mysql_query($sqlPresta,$db);
	$canPresta = mysql_num_rows($resPresta);
	if ($canPresta != 0) {
		$rowPresta = mysql_fetch_assoc($resPresta);
		$respuesta = $rowPresta['codigoprestador'];
	}
}
echo $respuesta;
?>