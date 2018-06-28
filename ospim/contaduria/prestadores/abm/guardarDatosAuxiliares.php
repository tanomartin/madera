<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$codigo = $_POST['codigo'];
$cbu = "NULL";
if ($_POST['cbu'] != "") {
	$cbu = "'".$_POST['cbu']."'";
}
$banco = "NULL";
if ($_POST['banco'] != "") {
	$banco = "'".$_POST['banco']."'";
}
$cuenta = "NULL";
if ($_POST['cuenta'] != "") {
	$cuenta = "'".$_POST['cuenta']."'";
}
if (isset($_POST['inter'])) {
	$interbanking = $_POST['inter'];
} else {
	$interbanking = 1;
}
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$sqlControlCBU = "SELECT * FROM prestadoresauxiliar WHERE cbu = $cbu and codigoprestador != $codigo";
$resControlCBU = mysql_query($sqlControlCBU,$db);
$canControlCBU = mysql_num_rows($resControlCBU);
if ($canControlCBU != 0) {
	$rowControlCBU = mysql_fetch_array($resControlCBU);
	$redire = "abmPrestadores.php?codigo=$codigo&codigoRepe=".$rowControlCBU['codigoprestador']."&cbu=$cbu";
	header("Location: $redire");
} else {
	$updateAuxiliares = "UPDATE prestadoresauxiliar SET cbu = $cbu, banco = $banco, cuenta = $cuenta, interbanking = $interbanking, fechainterbanking = NULL, fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE codigoprestador = $codigo";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//echo $updateAuxiliares."<br>";
		$dbh->exec($updateAuxiliares);
	
		$dbh->commit();
		$pagina = "abmPrestadores.php?codigo=$codigo";
		Header("Location: $pagina");
	
	}catch (PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
}
?>	