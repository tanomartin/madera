<?php  $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = "";
$usuariomodificacion = "";
if(isset($_POST)) {
	//var_dump($_POST);
	if(isset($_POST['fechanacimiento'])) {
		$fechanacimiento=fechaParaGuardar($_POST['fechanacimiento']);
	} else {
		$fechanacimiento="0000-00-00";
	}
	if(isset($_POST['certificadonacimiento'])) {
		$certificadonacimiento=$_POST['certificadonacimiento'];
	} else {
		$certificadonacimiento=0;
	}

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
				
		$sqlAgregaFicha = "INSERT INTO pmibeneficiarios (nroafiliado,tipoparentesco,nroorden,emailfecha,emailfrom,fpp,nacimiento,fechanacimiento,certificadonacimiento,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion) VALUES (:nroafiliado,:tipoparentesco,:nroorden,:emailfecha,:emailfrom,:fpp,:nacimiento,:fechanacimiento,:certificadonacimiento,:fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion)";
		$resAgregaFicha = $dbh->prepare($sqlAgregaFicha);
		if($resAgregaFicha->execute(array(':nroafiliado' => $_POST['nroafiliado'], ':tipoparentesco' => $_POST['tipoparentesco'], ':nroorden' => $_POST['nroorden'], ':emailfecha' => fechaParaGuardar($_POST['emailfecha']), ':emailfrom' => $_POST['emailfrom'], ':fpp' => fechaParaGuardar($_POST['fpp']), ':nacimiento' => $_POST['nacimiento'], ':fechanacimiento' => $fechanacimiento, ':certificadonacimiento' => $certificadonacimiento, ':fecharegistro' => $fecharegistro, ':usuarioregistro' => $usuarioregistro, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion)))
		$dbh->commit();
		$pagina = "moduloPMI.php";
		header("Location: $pagina");
	} catch (PDOException $e) {
		$error = $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header($redire);
		exit(0);
	}
}
?>