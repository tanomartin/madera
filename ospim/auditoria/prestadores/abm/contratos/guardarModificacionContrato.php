<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$codigopresta = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
$fechaInicio = fechaParaGuardar($_POST['fechaInicio']);
$fechaFin = fechaParaGuardar($_POST['fechaFin']);
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$sqlCabContratoFin = "SELECT c.* FROM cabcontratoprestador c  WHERE c.codigoprestador = $codigopresta and c.fechafin >= '$fechaInicio' and c.idcontrato != $idcontrato";
$resCabContratoFin = mysql_query($sqlCabContratoFin,$db);
$numCabContratoFin = mysql_num_rows($resCabContratoFin);
if ($numCabContratoFin > 0) {
	$pagina = "modificarContrato.php?codigo=$codigopresta&err=1&idcontrato=$idcontrato";
	Header("Location: $pagina"); 
	exit(0);
} else {
	$sqlInsertProf = "UPDATE cabcontratoprestador SET fechainicio = '$fechaInicio', fechafin = '$fechaFin', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE idcontrato = $idcontrato and codigoprestador = $codigopresta";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//print($sqlInsertProf."<br>");
		$dbh->exec($sqlInsertProf);
		$dbh->commit();
		
		$pagina = "contratosPrestador.php?codigo=$codigopresta";
		Header("Location: $pagina"); 
	} catch (PDOException $e) {
		$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		Header($redire);
		exit(0);
	}
}
?>
