<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
if(isset($_POST) && !empty($_POST) && isset($_POST['fecharemito']) && isset($_POST['cuentaremito'])) {
	$cuentaRemito=$_POST['cuentaremito'];
	$fechaInvertida=fechaParaGuardar($_POST['fecharemito']);
	$respuesta="<option title='Seleccione un valor' value='0'>Seleccione un valor</option>";
	$sqlRemitoSuelto = "SELECT nroremito FROM remitossueltosusimra WHERE codigocuenta = $cuentaRemito AND sistemaremito = 'M' AND fecharemito = '$fechaInvertida'";
	$resRemitoSuelto=mysql_query($sqlRemitoSuelto,$db);
	while($rowRemitoSuelto=mysql_fetch_array($resRemitoSuelto)) {
		$respuesta.="<option title ='$rowRemitoSuelto[nroremito]' value='$rowRemitoSuelto[nroremito]'>".$rowRemitoSuelto['nroremito']."</option>";
	}
	echo $respuesta;
}
?>