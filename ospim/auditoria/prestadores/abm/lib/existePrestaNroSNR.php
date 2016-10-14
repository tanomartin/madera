<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$respuesta = 0;
if(isset($_POST['nroreg'])) {
	$nroreg = $_POST['nroreg'];
	if (isset($_POST['codigo'])) {
		$codigo = $_POST['codigo'];
		$sqlPresta = "SELECT codigoprestador FROM prestadores WHERE numeroregistrosnr = '$nroreg' and codigoprestador != '$codigo'";
	} else {
		$sqlPresta = "SELECT codigoprestador FROM prestadores WHERE numeroregistrosnr = '$nroreg'";
	}
	$resPresta = mysql_query($sqlPresta,$db);
	$canPresta = mysql_num_rows($resPresta);
	if ($canPresta != 0) {
		$rowPresta = mysql_fetch_assoc($resPresta);
		$respuesta = $rowPresta['codigoprestador'];
	}
}
echo $respuesta;
?>