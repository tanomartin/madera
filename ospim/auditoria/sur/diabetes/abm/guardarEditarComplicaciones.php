<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];
if(isset($_POST)) {
	//var_dump($_POST);
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlActualizaComplicaciones = "UPDATE diabetescomplicaciones  SET hipoglucemia = :hipoglucemia, nivelhipoglucemia = :nivelhipoglucemia, retinopatia = :retinopatia, ceguera = :ceguera, nefropatia = :nefropatia, neuropatiaperiferica = :neuropatiaperiferica, hipertrofiaventricular  = :hipertrofiaventricular, vasculopatiaperiferica = :vasculopatiaperiferica, infartomiocardio = :infartomiocardio, insuficienciacardiaca = :insuficienciacardiaca, accidentecerebrovascular = :accidentecerebrovascular, amputacion = :amputacion, dialisis = :dialisis, transplanterenal = :transplanterenal, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE idDiagnostico = :idDiagnostico";
		$resActualizaComplicaciones = $dbh->prepare($sqlActualizaComplicaciones);
		if($resActualizaComplicaciones->execute(array(':idDiagnostico' => $_POST['iddiagnostico'],':hipoglucemia' => $_POST['hipoglucemia'],':nivelhipoglucemia' => $_POST['nivelhipoglucemia'],':retinopatia' => $_POST['retinopatia'],':ceguera' => $_POST['ceguera'],':nefropatia' => $_POST['nefropatia'],':neuropatiaperiferica' => $_POST['neuropatiaperiferica'],':hipertrofiaventricular' => $_POST['hipertrofiaventricular'],':vasculopatiaperiferica' => $_POST['vasculopatiaperiferica'],':infartomiocardio' => $_POST['infartomiocardio'],':insuficienciacardiaca' => $_POST['insuficienciacardiaca'],':accidentecerebrovascular' => $_POST['accidentecerebrovascular'],':amputacion' => $_POST['amputacion'],':dialisis' => $_POST['dialisis'],':transplanterenal' => $_POST['transplanterenal'],':fechamodificacion' => $fechamodificacion,':usuariomodificacion' => $usuariomodificacion)))
		$dbh->commit();
		$pagina ="listarDiagnosticos.php?nroAfi=$_POST[nroafiliado]&nroOrd=$_POST[nroorden]&estAfi=$_POST[estafiliado]";
		header("Location: $pagina");
	}
	catch (PDOException $e) {
		$error = $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header($redire);
		exit(0);
	}
}
?>
