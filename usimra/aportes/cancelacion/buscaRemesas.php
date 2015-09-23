<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
if(isset($_POST) && !empty($_POST) && isset($_POST['fecharemesa']) && isset($_POST['cuentaremesa'])) {
	$cuentaRemesa=$_POST['cuentaremesa'];
	$fechaInvertida=fechaParaGuardar($_POST['fecharemesa']);
	$respuesta="<option title='Seleccione un valor' value='0'>Seleccione un valor</option>";
	$sqlRemesa="select * from remesasusimra where codigocuenta = 2 and sistemaremesa = 'M' and fecharemesa = '$fechaInvertida'";
	$resRemesa=mysql_query($sqlRemesa,$db);
	while($rowRemesa=mysql_fetch_array($resRemesa)) {
		$respuesta.="<option title ='$rowRemesa[nroremesa]' value='$rowRemesa[nroremesa]'>".$rowRemesa['nroremesa']."</option>";
	}
	echo $respuesta;
}
?>