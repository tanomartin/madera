<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$id = $_GET['id'];
$sqlDeleteConceptos = "DELETE FROM facturasconceptos WHERE idfactura = $id";
$sqlUpdateFacturas = "UPDATE facturas SET 
						usuarioliquidacion = NULL, 
						fechainicioliquidacion = '0000-00-00 00:00:00',
						fechacierreliquidacion = '0000-00-00 00:00:00',
						totalcredito = 0, 
						totaldebito = 0,
						importeliquidado = 0,
						restoapagar = 0,
						autorizacionpago = 0
					  WHERE id = $id";
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $sqlDeleteConceptos."<br>";
	$dbh->exec($sqlDeleteConceptos);
	
	//echo $sqlUpdateFacturas."<br>";
	$dbh->exec($sqlUpdateFacturas);

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