<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
if(isset($_POST) && !empty($_POST) && isset($_POST['fecharemesa']) && isset($_POST['cuentaremesa']) && isset($_POST['nroremesa'])) {
	$cuentaRemesa=$_POST['cuentaremesa'];
	$fechaInvertida=fechaParaGuardar($_POST['fecharemesa']);
	$nroremesa=$_POST['nroremesa'];
	$sqlRemito="select * from remitosremesasusimra where codigocuenta = $cuentaRemesa and sistemaremesa = 'M' and fecharemesa = '$fechaInvertida' and nroremesa = $nroremesa";
	$respuesta="<option title='Seleccione un valor' value='0'>Seleccione un valor</option>";
	$resRemito=mysql_query($sqlRemito,$db);
	while($rowRemito=mysql_fetch_array($resRemito)) {
		$respuesta.="<option title ='$rowRemito[nroremito]' value='$rowRemito[nroremito]'>".$rowRemito['nroremito']."</option>";
	}
	echo $respuesta;
}
?>