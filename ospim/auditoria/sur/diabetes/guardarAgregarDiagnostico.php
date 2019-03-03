<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$totaldiagnosticos = 0;
if(isset($_POST)) {
	//var_dump($_POST);
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlAgregaDiagnostico = "INSERT INTO diabetesdiagnosticos (id,nroafiliado,nroorden,tipodiabetes,fechadiagnostico,edaddiagnostico,familiaresdbt,medicotratante,ddnmedico,telefonomedico,institucionasiste,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion) VALUES(:id,:nroafiliado,:nroorden,:tipodiabetes,:fechadiagnostico,:edaddiagnostico,:familiaresdbt,:medicotratante,:ddnmedico,:telefonomedico,:institucionasiste,:fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion)";
		$resAgregaDiagnostico = $dbh->prepare($sqlAgregaDiagnostico);
		if($resAgregaDiagnostico->execute(array(':id' => 'DEFAULT',':nroafiliado' => $_POST['nroafiliado'],':nroorden' => $_POST['nroorden'],':tipodiabetes' => $_POST['tipodiabetes'],':fechadiagnostico' => fechaParaGuardar($_POST['fechadiagnostico']),':edaddiagnostico' => $_POST['edaddiagnostico'],':familiaresdbt' => $_POST['familiaresdbt'],':medicotratante' => $_POST['medicotratante'],':ddnmedico' => $_POST['ddnmedico'],':telefonomedico' => $_POST['telefonomedico'],':institucionasiste' => $_POST['institucionasiste'],':fecharegistro' => $fecharegistro,':usuarioregistro' => $usuarioregistro,':fechamodificacion' => NULL,':usuariomodificacion' => NULL)))

		$sqlLeeBeneficiario = "SELECT diagnosticos FROM diabetesbeneficiarios WHERE nroafiliado = $_POST[nroafiliado] AND nroorden = $_POST[nroorden]";
		$resLeeBeneficiario = mysql_query($sqlLeeBeneficiario,$db);
		$rowLeeBeneficiario = mysql_fetch_array($resLeeBeneficiario);
		$totaldiagnosticos = $rowLeeBeneficiario['diagnosticos'] + 1;

		$sqlActualizaBeneficiario = "UPDATE diabetesbeneficiarios SET diagnosticos = :diagnosticos, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado AND nroorden = :nroorden";
		$resActualizaBeneficiario = $dbh->prepare($sqlActualizaBeneficiario);
		if($resActualizaBeneficiario->execute(array(':diagnosticos' => $totaldiagnosticos, ':fechamodificacion' => $fecharegistro, ':usuariomodificacion' => $usuarioregistro, ':nroafiliado' => $_POST['nroafiliado'], ':nroorden' => $_POST['nroorden'])))

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
