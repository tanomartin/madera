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

		$sqlActualizaComorbilidad = "UPDATE diabetescomorbilidad SET hta = :hta, dislipemia = :dislipemia, obesidad = :obesidad, tabaquismo = :tabaquismo, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion  WHERE idDiagnostico = :idDiagnostico";
		$resActualizaComorbilidad = $dbh->prepare($sqlActualizaComorbilidad);
		if($resActualizaComorbilidad->execute(array(':idDiagnostico' => $_POST['iddiagnostico'],':hta' => $_POST['hta'],':dislipemia' => $_POST['dislipemia'],':obesidad' => $_POST['obesidad'],':tabaquismo' => $_POST['tabaquismo'],':fechamodificacion' => $fechamodificacion,':usuariomodificacion' => $usuariomodificacion)))
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
