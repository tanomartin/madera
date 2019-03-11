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

		$sqlActualizaDiagnostico = "UPDATE diabetesdiagnosticos SET tipodiabetes = :tipodiabetes, fechadiagnostico = :fechadiagnostico, edaddiagnostico = :edaddiagnostico, familiaresdbt = :familiaresdbt, medicotratante = :medicotratante, ddnmedico = :ddnmedico, telefonomedico = :telefonomedico, institucionasiste = :institucionasiste, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE id = :id";
		$resActualizaDiagnostico = $dbh->prepare($sqlActualizaDiagnostico);
		if($resActualizaDiagnostico->execute(array(':id' => $_POST['iddiagnostico'],':tipodiabetes' => $_POST['tipodiabetes'],':fechadiagnostico' => fechaParaGuardar($_POST['fechadiagnostico']),':edaddiagnostico' => $_POST['edaddiagnostico'],':familiaresdbt' => $_POST['familiaresdbt'],':medicotratante' => $_POST['medicotratante'],':ddnmedico' => $_POST['ddnmedico'],':telefonomedico' => $_POST['telefonomedico'],':institucionasiste' => $_POST['institucionasiste'],':fechamodificacion' => $fechamodificacion,':usuariomodificacion' => $usuariomodificacion)))

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
