<?php  $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$importeTotal = $_POST['total'];
$impDebito = $_POST['totaldebito'];
$codigo = $_POST['codigo'];
$fechaorden = date("Y-m-d");
$tipoPago = $_POST['tipopago'];
$nroPago = 'NULL';
if ($tipoPago != 'E') {
	$nroPago =  "'".$_POST['numero']."'";
}
$fechaPago = fechaParaGuardar($_POST['fecha']);
$retencion = $_POST['retencion'];
$impRetencion = 0;
if ($retencion != 0) {
	$impRetencion = $_POST['rete'];
}
$impApagar = $_POST['apagar'];
$email = "";
if (isset($_POST['enviomail'])) {
	$envioEmail = $_POST['enviomail'];
	if ($envioEmail != 0) {
		$email = $_POST['email'];
	}
}
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$sqlCabeceraOrden = "INSERT INTO ordencabecera VALUE(DEFAULT, $codigo,'$fechaorden','$tipoPago', $nroPago, '$fechaPago', $impRetencion, $impDebito, $impApagar, NULL, NULL, NULL, '$fecharegistro', '$usuarioregistro',NULL,NULL)";

if ($impDebito > 0) {
	$today = date("Y-m-d");
	$sqlUltimo = "SELECT * FROM ordendebitolote WHERE fechainicio <= '$today' and fechavto >= '$today'";
	$resUltimo = mysql_query($sqlUltimo,$db);
	$canUltimo = mysql_num_rows($resUltimo);
	if ($canUltimo == 0) {
		$error = "No existe lote de notas de debito disponibles para realizar la misma";
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		Header($redire);
		exit(0);
	} else {
		$rowUltimo = mysql_fetch_array($resUltimo);
		$ptoVenta = $rowUltimo['ptoventa'];
		$nronota = $rowUltimo['ultimousado'] + 1;
		if ($nronota > $rowUltimo['nrofin']) {
			$error = "Se supero el último Nro. de Nota de Debito del lote actual";
			$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
			Header($redire);
			exit(0);
		}
		$sqlDebito = "INSERT INTO ordendebito VALUE(nroorden, $ptoVenta, $nronota, $codigo, '$fechaorden', $impDebito)";
		$sqlUpdateLote = "UPDATE ordendebitolote SET ultimousado = $nronota WHERE id = ".$rowUltimo['id'];
	}
}

$arrayDetalle = array();
$arrayUpdateFactura = array();
$arrayDetalleDebito = array();
foreach ($_POST as $key => $facturas) {
	$pos = strpos($key, "id");
	if ($pos !== false) {
		$id = intval(preg_replace('/[^0-9]+/', '', $key), 10); 
		$indexTipo = "tipo".$id;
		$tipoPagoFactura = $_POST[$indexTipo];
		$indexValor = "valor".$id;
		$valorPagoFactura = $_POST[$indexValor];
		$sqlDetalleOrden = "INSERT INTO ordendetalle VALUE(nroorden, $id, '$tipoPagoFactura', $valorPagoFactura, NULL, NULL, NULL)";
		$arrayDetalle[$id] = $sqlDetalleOrden;
		$sqlUpdateFactura = "UPDATE facturas SET totalpagado = totalpagado + $valorPagoFactura, restoapagar = restoapagar - $valorPagoFactura, fechapago = '$fechaPago' WHERE id = $id";
		$arrayUpdateFactura[$id] = $sqlUpdateFactura;
		
		$paname = "pa".$id;
		$pagoanterior = $_POST[$paname];
		if ($pagoanterior == 0) {
			$debname = "debito".$id;
			$importeDebito = $_POST[$debname];
			if ($importeDebito > 0) { 
				$sqlInsertDetalleDebito = "INSERT INTO ordendebitodetalle VALUE(DEFAULT, nroorden, $id, $importeDebito)";
				$arrayDetalleDebito[$id] = $sqlInsertDetalleDebito;
			}
		}
	}
}

try {
	if (sizeof($arrayDetalle) == 0) {
		throw new Exception("Error al guardar el detalle de la orden de pago. Comuniquese con el Dpto. de Sistemas");
	}
} catch (Exception $e) {
		$error = $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		Header($redire);
		exit(0);
}
	
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlCabeceraOrden."<br>");
	$dbh->exec($sqlCabeceraOrden);
	$lastId = $dbh->lastInsertId();

	foreach ($arrayDetalle as $detalleOrden) {
		$detalleOrden = str_replace("nroorden", $lastId, $detalleOrden);
		//print($detalleOrden."<br>");
		$dbh->exec($detalleOrden);
	}
	
	if ($impDebito > 0) {
		$sqlDebito = str_replace("nroorden", $lastId, $sqlDebito);
		//print($sqlDebito."<br>");
		$dbh->exec($sqlDebito);
		
		foreach ($arrayDetalleDebito as $detalleDebito) {
			$detalleDebito = str_replace("nroorden", $lastId, $detalleDebito);
			//print($detalleDebito."<br>");
			$dbh->exec($detalleDebito);
		}
		
		//print($sqlUpdateLote."<br>");
		$dbh->exec($sqlUpdateLote);
	}
	
	foreach ($arrayUpdateFactura as $updateFactura) {
		//print($updateFactura."<br>");
		$dbh->exec($updateFactura);
	}
	
	$dbh->commit();
	$pagina = "documentoOrdenPago.php?nroorden=$lastId&email=$email";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>