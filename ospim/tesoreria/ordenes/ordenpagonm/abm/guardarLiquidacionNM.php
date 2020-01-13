<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

//var_dump($_POST);

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$importeTotal = $_POST['monto'];
$id = $_POST['id'];
$lineas = $_POST['conceptoaver'];
$arrayConcepto = array();
$credito = 0;
$debito = 0;
for($i=1; $i<=$lineas; $i++) {
	$conceptoNombre = "concepto".$i;
	$concepto = $_POST[$conceptoNombre];
	$tipoNombre = "tipo".$i;
	$tipo = $_POST[$tipoNombre];
	$importeLineaNombre = "importe".$i;
	$importeLinea = $_POST[$importeLineaNombre];
	
	$sqlInsertConcepto = "INSERT INTO facturasconceptos VALUES($id, $i, '$concepto', '$tipo', $importeLinea)";
	$arrayConcepto[$i] = $sqlInsertConcepto;
	
	if ($tipo == 'C') {
		$credito += $importeLinea;
	} else {
		$debito += $importeLinea;
	}
}


$sqlUpdateFacturas = "UPDATE facturas SET 
						usuarioliquidacion = '$usuarioregistro', 
						fechainicioliquidacion = '$fecharegistro',
						fechacierreliquidacion = '$fecharegistro',
						totalcredito = $credito, 
						totaldebito = $debito,
						importeliquidado = $credito, 
						restoapagar = $credito, 
						autorizacionpago = 1 
					  WHERE id = $id";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $sqlUpdateFacturas."<br>";
	$dbh->exec($sqlUpdateFacturas);
	
	foreach ($arrayConcepto as $sqlconcepto) {
		//echo $sqlconcepto."<br>";
		$dbh->exec($sqlconcepto);
	}

	$dbh->commit();
	$pagina = "consultaFacturaNM.php?id=$id";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>