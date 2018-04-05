<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$fechaAnulacion = date("Y-m-d H:i:s");
$usuarioAnulacion = $_SESSION['usuario'];

$idBoleta = $_GET['idboleta'];
$datos = array_values($_POST);
$docuMano = $datos[0];

if ($docuMano == "SI") {
	$docuMano = 1;
} else {
	$docuMano = 0;
}

$motivo = $datos[1];

$sqlBol = "select * from boletasospim where idboleta = $idBoleta";
$resBol = mysql_query($sqlBol,$db); 
$rowBol = mysql_fetch_array($resBol); 

$cuit = $rowBol['cuit'];
$nroacu = $rowBol['nroacuerdo'];
$nrocuo = $rowBol['nrocuota'];
$importe = $rowBol['importe'];
$nrocontrol = $rowBol['nrocontrol'];
$usuarioReg = $rowBol['usuarioregistro'];

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$sqlAnula = "INSERT INTO anuladasospim VALUES('$idBoleta','$cuit','$nroacu','$nrocuo',
				'$importe','$nrocontrol','$usuarioReg','$fechaAnulacion','$usuarioAnulacion',
				'$docuMano','$motivo') ";
	$dbh->exec($sqlAnula);
	
	$sqlUpdate = "UPDATE cuoacuerdosospim set boletaimpresa = 0 where cuit = ".$cuit." and nroacuerdo = ".$nroacu." and nrocuota = ".$nrocuo;
	$dbh->exec($sqlUpdate);
	
	$sqlDelete = "DELETE FROM boletasospim where idboleta = $idBoleta";
	$dbh->exec($sqlDelete);
	
	$dbh->commit();
	$pagina = "cargaAnulacion.php?err=2&control=".$nrocontrol;
	Header("Location: $pagina"); 

}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>