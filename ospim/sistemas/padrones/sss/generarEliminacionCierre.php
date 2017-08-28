<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
set_time_limit(0);

$idDelete = $_POST['idDelete'];
$idClose = $_POST['idClose'];
$fechaproceso = date("Y-m-d");
$usuarioproceso = $_SESSION['usuario'];

$sqlDeletePadron = "DELETE FROM padronsss";
$sqlUpdateCabeceraCierre = "UPDATE padronssscabecera SET fechacierre = '$fechaproceso', usuariocierre = '$usuarioproceso' WHERE id = $idClose";

$sqlDeletePadronHistorico = "DELETE FROM padronssshistorico WHERE idcabecera = $idDelete";
$sqlUpdateCabeceraDelete = "UPDATE padronssscabecera SET fechadelete = '$fechaproceso', usuariodelete = '$usuarioproceso' WHERE id = $idDelete";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	
	$dbh->exec($sqlDeletePadron);
	//echo $sqlDeletePadron."<br>";
	$dbh->exec($sqlUpdateCabeceraCierre);
	//echo $sqlUpdateCabeceraCierre."<br>";
	$dbh->exec($sqlDeletePadronHistorico);
	//echo $sqlDeletePadronHistorico."<br>";
	$dbh->exec($sqlUpdateCabeceraDelete);
	//echo $sqlUpdateCabeceraDelete."<br>";
	
	$dbh->commit ();
	
}  catch ( PDOException $e ) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Padron SSS :.</title>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloSSS.php'" /></p>
  <h2>Elimnacion y Cierre de Padron SSS</h2>
  <h3><font color="blue">Proceso finalizado con existo</font></h3>
  </div>
</body>
</html>
