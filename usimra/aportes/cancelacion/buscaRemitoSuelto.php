<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
if(isset($_POST) && !empty($_POST) && isset($_POST['fecharemito']) && isset($_POST['cuentaremito'])) {
	$cuentaRemito=$_POST['cuentaremito'];
	$fechaInvertida=fechaParaGuardar($_POST['fecharemito']);
	$sqlRemitoSuelto = "select * from remitossueltosusimra where codigocuenta = $cuentaRemito and sistemaremito = 'M' and fecharemito = '$fechaInvertida'";
	$respuesta="<option title='Seleccione un valor' value='0'>Seleccione un valor</option>";
	$resRemitoSuelto=mysql_query($sqlRemitoSuelto,$db);
	while($rowRemitoSuelto=mysql_fetch_array($resRemitoSuelto)) {
		$respuesta.="<option title ='$rowRemitoSuelto[nroremito]' value='$rowRemitoSuelto[nroremito]'>".$rowRemitoSuelto['nroremito']."</option>";
	}
	echo $respuesta;
}
?>