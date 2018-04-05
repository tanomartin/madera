<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 
$fechamodificacion = date("Y-m-d H:i:s");
$fechaCancela = date("Y-m-d");
$usuariomodificacion = $_SESSION['usuario'];

$cuit = $_GET["cuit"];
$acuerdo = $_GET["acuerdo"];
$cuota = $_GET["cuota"];	

$datos = array_values($_POST);

$fechapagada = $datos[0];
$observ = $datos[1];

$sqlCab = "select * from cabacuerdosospim where cuit = $cuit and nroacuerdo = $acuerdo";
$resCab = mysql_query($sqlCab,$db); 
$rowCab = mysql_fetch_array($resCab);

$montoAcuerdo = $rowCab['montoacuerdo'];
 //echo "MONTO ACUERDO: ".$montoAcuerdo;  //echo "<br>";

 //echo "<br>";

$montoAPagar = $rowCab['montoapagar'];
 //echo "MONTO A PAGAR: ".$montoAPagar;  //echo "<br>";
$cuotasAcuerdo = $rowCab['cuotasapagar'];
 //echo "CUOTAS ACUERDO: ".$cuotasAcuerdo;  //echo "<br>";

 //echo "<br>";

$montoPagado = $rowCab['montopagadas'];
 //echo "MONTO PAGADO: ".$montoPagado;  //echo "<br>";
$cuotasPagadas = $rowCab['cuotaspagadas'];
 //echo "CUOTAS PAGADAS: ".$cuotasPagadas;  //echo "<br>";

 //echo "<br>";

$sqlCuo = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
$resCuo = mysql_query($sqlCuo,$db); 
$rowCuo = mysql_fetch_array($resCuo);

$montoCuota = $rowCuo['montocuota'];
$fechaPagada = fechaParaGuardar($fechapagada);
 //echo "MONTO CUOTA A CANCELAR: ".$montoCuota;  //echo "<br>";
 //echo "FECHA DE PAGO: ".$fechaPagada;  //echo "<br>";
 //echo "OBSERVACIONES: ".$observ;  //echo "<br>";

 //echo "<br>";

$montoPagadoUpdate = $montoPagado + $montoCuota;
$cuotasPagasUpdate = $cuotasPagadas + 1;

 //echo "MONTO PAGADO A UPDETEAR: ".$montoPagadoUpdate;  //echo "<br>";
 //echo "CUOTAS PAGADAS A UPDETEAR: ".$cuotasPagasUpdate;  //echo "<br>";

 //echo "<br>";

$estadoAcuerdo = 1;
if ($cuotasPagasUpdate == $cuotasAcuerdo && $montoPagadoUpdate == $montoAPagar) {
	$estadoAcuerdo = 0;
}

$saldoAcuerdo = $montoAPagar -  $montoPagadoUpdate;

 //echo "SALDO ACUERDO A UPDETEAR: ".$saldoAcuerdo;  //echo "<br>";  //echo "<br>";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$sqlUpdateCuota = "UPDATE cuoacuerdosospim set montopagada = '$montoCuota', observaciones = '$observ', fechapagada = '$fechaPagada', fechacancelacion = '$fechaCancela', sistemacancelacion = 'M', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
	 //echo $sqlUpdateCuota; //echo "<br>";
	$dbh->exec($sqlUpdateCuota); 
	$sqlUpdateCabe = "UPDATE cabacuerdosospim set montopagadas = '$montoPagadoUpdate', cuotaspagadas = '$cuotasPagasUpdate', estadoacuerdo = '$estadoAcuerdo', fechapagadas = '$fechaCancela', saldoacuerdo = '$saldoAcuerdo' where cuit = $cuit and nroacuerdo = $acuerdo";
	 //echo $sqlUpdateCabe;  //echo "<br>";
	$dbh->exec($sqlUpdateCabe); 
	
	$dbh->commit();
	$pagina = "selecCanCuotas.php?cuit=$cuit&acuerdo=$acuerdo";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>