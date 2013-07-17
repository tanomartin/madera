<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fechamodificacion = date("Y-m-d H:m:s");
$fechaCancela = date("Y-m-d");
$usuariomodificacion = $_SESSION['usuario'];

$cuit = $_GET["cuit"];
$acuerdo = $_GET["acuerdo"];
$cuota = $_GET["cuota"];		
$datos = array_values($_POST);
$fechapagada = $datos[0];
$cuentaBoleta = $datos[1];
$quees = $datos[2];

if ($quees == "remesa") {
	$cuentaRemesa = $datos[3];
	$fechaRemesa = fechaParaGuardar($datos[4]);
	$nroremesa = $datos[5];
	$nroremito = $datos[6];
	$observ = $datos[7];
	$cuentaRemito = 0;
	$fechaRemito = "0000-00-00";
	$nroRemitoSuelto = 0;
} 
if ($quees == "remito") {
	$cuentaRemito = $datos[3];
	$fechaRemito = fechaParaGuardar($datos[4]);
	$nroRemitoSuelto = $datos[5];
	$observ = $datos[6];
	$cuentaRemesa = 0;
	$fechaRemesa = "0000-00-00";
	$nroremesa = 0;
	$nroremito = 0;
}
//echo "DATOS DE LA PANTALLA ANTERIOR"; //echo "<br>";//echo "<br>";

//echo "Fecha de pago: ";//echo $fechapagada; //echo "<br>";
//echo "Cuenta Boleta: ";//echo $cuentaBoleta; //echo "<br>"; //echo "<br>";
//if ($quees == "remesa")  {
 	//echo "REMESA"; //echo "<br>";//echo "<br>";
	//echo "Cuenta Remesa: "; //echo $cuentaRemesa;//echo "<br>";
	//echo "FECHA Remesa: "; //echo $fechaRemesa;//echo "<br>";
	//echo "NRO Remesa: "; //echo $nroremesa;//echo "<br>";
	//echo "NRO Remito: "; //echo $nroremito;//echo "<br>";
	//echo "OBS: "; //echo $observ;//echo "<br>";
//}
//if ($quees == "remito")  {
	//echo "REMITO SUELTO"; //echo "<br>";//echo "<br>";
	//echo "Cuenta REMITO: "; //echo $cuentaRemito;//echo "<br>";
	//echo "FECHA REMITO: "; //echo $fechaRemito;//echo "<br>";
	//echo "NRO REMITO: "; //echo $nroRemitoSuelto;//echo "<br>";
	//echo "OBS: "; //echo $observ;//echo "<br>";
//}
//echo "<br>";//echo "<br>";


$sqlCab = "select * from cabacuerdosusimra where cuit = $cuit and nroacuerdo = $acuerdo";
$resCab = mysql_query($sqlCab,$db); 
$rowCab = mysql_fetch_array($resCab);

$montoAcuerdo = $rowCab['montoacuerdo'];
//echo "MONTO ACUERDO: ".$montoAcuerdo;//echo "<br>";//echo "<br>";

$montoAPagar = $rowCab['montoapagar'];
//echo "MONTO A PAGAR: ".$montoAPagar;//echo "<br>";
$cuotasAcuerdo = $rowCab['cuotasapagar'];
//echo "CUOTAS ACUERDO: ".$cuotasAcuerdo;//echo "<br>";//echo "<br>";

$montoPagado = $rowCab['montopagadas'];
//echo "MONTO PAGADO: ".$montoPagado;//echo "<br>";
$cuotasPagadas = $rowCab['cuotaspagadas'];
//echo "CUOTAS PAGADAS: ".$cuotasPagadas;//echo "<br>";//echo "<br>";

$sqlCuo = "select * from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
$resCuo = mysql_query($sqlCuo,$db); 
$rowCuo = mysql_fetch_array($resCuo);

$montoCuota = $rowCuo['montocuota'];
$fechaPagada = fechaParaGuardar($fechapagada);
//echo "MONTO CUOTA A CANCELAR: ".$montoCuota;
//echo "<br>";
//echo "FECHA DE PAGO: ".$fechaPagada;//echo "<br>";
//echo "OBSERVACIONES: ".$observ;//echo "<br>";//echo "<br>";

$montoPagadoUpdate = $montoPagado + $montoCuota;
$cuotasPagasUpdate = $cuotasPagadas + 1;
//echo "MONTO PAGADO A UPDETEAR: ".$montoPagadoUpdate;//echo "<br>";
//echo "CUOTAS PAGADAS A UPDETEAR: ".$cuotasPagasUpdate;//echo "<br>";//echo "<br>";

$estadoAcuerdo = 1;
if ($cuotasPagasUpdate == $cuotasAcuerdo && $montoPagadoUpdate == $montoAPagar) {
	$estadoAcuerdo = 0;
}

$saldoAcuerdo = $montoAPagar -  $montoPagadoUpdate;
//echo "SALDO ACUERDO A UPDETEAR: ".$saldoAcuerdo;//echo "<br>";//echo "<br>";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$sqlInsertConcila = "INSERT into conciliacuotasusimra VALUES('$cuit','$acuerdo','$cuota','$cuentaBoleta','$cuentaRemesa','$fechaRemesa','$nroremesa','$nroremito','$cuentaRemito','$fechaRemito','$nroRemitoSuelto','0','0000-00-00','','$fechamodificacion','$usuariomodificacion','0000-00-00','')";
	//echo $sqlInsertConcila;//echo "<br>";
	$dbh->exec($sqlInsertConcila); 
	
	$sqlUpdateCuota = "UPDATE cuoacuerdosusimra set montopagada = '$montoCuota', observaciones = '$observ', fechapagada = '$fechaPagada', fechacancelacion = '$fechaCancela', sistemacancelacion = 'M', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
	//echo $sqlUpdateCuota;//echo "<br>";
	$dbh->exec($sqlUpdateCuota); 
	
	$sqlUpdateCabe = "UPDATE cabacuerdosusimra set montopagadas = '$montoPagadoUpdate', cuotaspagadas = '$cuotasPagasUpdate', estadoacuerdo = '$estadoAcuerdo', fechapagadas = '$fechaCancela', saldoacuerdo = '$saldoAcuerdo' where cuit = $cuit and nroacuerdo = $acuerdo";
	//echo $sqlUpdateCabe;//echo "<br>";
	$dbh->exec($sqlUpdateCabe); 
	
	$dbh->commit();
	$pagina = "selecCanCuotas.php?cuit=$cuit&acuerdo=$acuerdo";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>