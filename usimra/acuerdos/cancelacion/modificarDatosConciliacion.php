<?php include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/controlSession.php");
include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/fechas.php"); 
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
//if ($esremesa == 1) {
 	//echo "REMESA"; //echo "<br>";//echo "<br>";
	//echo "Cuenta Remesa: "; //echo $cuentaRemesa;//echo "<br>";
	//echo "FECHA Remesa: "; //echo $fechaRemesa;//echo "<br>";
	//echo "NRO Remesa: "; //echo $nroremesa;//echo "<br>";
	//echo "NRO Remito: "; //echo $nroremito;//echo "<br>";
	//echo "OBS: "; //echo $observ;//echo "<br>";
//}
//if ($esremito == 1) {
	//echo "REMITO SUELTO"; //echo "<br>";//echo "<br>";
	//echo "Cuenta REMITO: "; //echo $cuentaRemito;//echo "<br>";
	//echo "FECHA REMITO: "; //echo $fechaRemito;//echo "<br>";
	//echo "NRO REMITO: "; //echo $nroRemitoSuelto;//echo "<br>";
	//echo "OBS: "; //echo $observ;//echo "<br>";
//}
//echo "<br>";//echo "<br>";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$sqlModifConcila = "UPDATE conciliacuotasusimra set cuentaboleta = '$cuentaBoleta', cuentaremesa='$cuentaRemesa', fecharemesa='$fechaRemesa', nroremesa='$nroremesa', nroremitoremesa='$nroremito', cuentaremitosuelto='$cuentaRemito', fecharemitosuelto='$fechaRemito', nroremitosuelto='$nroRemitoSuelto', fechamodificacion='$fechamodificacion', usuariomodificacion='$usuariomodificacion' where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota ";
	//echo $sqlModifConcila;//echo "<br>";
	$dbh->exec($sqlModifConcila); 
	
	$dbh->commit();
	$pagina = "selecCanCuotas.php?cuit=$cuit&acuerdo=$acuerdo";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>