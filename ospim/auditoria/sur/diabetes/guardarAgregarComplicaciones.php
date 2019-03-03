<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
if(isset($_POST)) {
	//var_dump($_POST);
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlAgregaComplicaciones = "INSERT INTO diabetescomplicaciones (idDiagnostico,hipoglucemia,nivelhipoglucemia,retinopatia,ceguera,nefropatia,neuropatiaperiferica,hipertrofiaventricular,vasculopatiaperiferica,infartomiocardio,insuficienciacardiaca,accidentecerebrovascular,amputacion,dialisis,transplanterenal,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion) VALUES(:idDiagnostico,:hipoglucemia,:nivelhipoglucemia,:retinopatia,:ceguera,:nefropatia,:neuropatiaperiferica,:hipertrofiaventricular,:vasculopatiaperiferica,:infartomiocardio,:insuficienciacardiaca,:accidentecerebrovascular,:amputacion,:dialisis,:transplanterenal,:fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion)";
		$resAgregaComplicaciones = $dbh->prepare($sqlAgregaComplicaciones);
		if($resAgregaComplicaciones->execute(array(':idDiagnostico' => $_POST['iddiagnostico'],':hipoglucemia' => $_POST['hipoglucemia'],':nivelhipoglucemia' => $_POST['nivelhipoglucemia'],':retinopatia' => $_POST['retinopatia'],':ceguera' => $_POST['ceguera'],':nefropatia' => $_POST['nefropatia'],':neuropatiaperiferica' => $_POST['neuropatiaperiferica'],':hipertrofiaventricular' => $_POST['hipertrofiaventricular'],':vasculopatiaperiferica' => $_POST['vasculopatiaperiferica'],':infartomiocardio' => $_POST['infartomiocardio'],':insuficienciacardiaca' => $_POST['insuficienciacardiaca'],':accidentecerebrovascular' => $_POST['accidentecerebrovascular'],':amputacion' => $_POST['amputacion'],':dialisis' => $_POST['dialisis'],':transplanterenal' => $_POST['transplanterenal'],':fecharegistro' => $fecharegistro,':usuarioregistro' => $usuarioregistro,':fechamodificacion' => NULL,':usuariomodificacion' => NULL)))
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
