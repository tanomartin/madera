<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

var_dump($_POST);

if(isset($_POST) && !empty($_POST)) {
	$nroafiliado = $_POST['nroafiliado'];
	$nroorden = $_POST['nroorden'];

	$sqlFamilia = "SELECT informesss, tipoinformesss, fechainformesss, usuarioinformesss FROM familiares WHERE nroafiliado = '$nroafiliado' and nroorden = '$nroorden'";
	$resFamilia = mysql_query($sqlFamilia,$db);
	$rowFamilia = mysql_fetch_array($resFamilia);

	if(isset($_POST['selectEstudia'])) {
		$estudia = $_POST['selectEstudia'];
	} else {
		$estudia = 0;
	}

	if(isset($_POST['selectCertificadoEstudio'])) {
		$certificadoestudio = $_POST['selectCertificadoEstudio'];
	} else {
		$certificadoestudio = 0;
	}

	if(isset($_POST['emisioncertificadoestudio'])) {
		$emisioncertificadoestudio = fechaParaGuardar($_POST['emisioncertificadoestudio']);
	} else {
		$emisioncertificadoestudio = "";
	}

	if(isset($_POST['vencimientocertificadoestudio'])) {
		$vencimientocertificadoestudio = fechaParaGuardar($_POST['vencimientocertificadoestudio']);
	} else {
		$vencimientocertificadoestudio = "";
	}


	if($rowFamilia['informesss'] == 0) {
		$informesss = 1;
		$tipoinformesss = "M";
		$fechainformesss = "";
		$usuarioinformesss = "";
	} else {
		$informesss = $rowFamilia['informesss'];
		$tipoinformesss = $rowFamilia['tipoinformesss'];
		$fechainformesss = $rowFamilia['fechainformesss'];
		$usuarioinformesss = $rowFamilia['usuarioinformesss'];
	}
	
	$fechamodificacion = date("Y-m-d H:i:s");
	$usuariomodificacion = $_SESSION['usuario'];

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		//echo "$hostname"; echo "<br>";
		//echo "$dbname"; echo "<br>";
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		//echo 'Connected to database<br/>';
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		$sqlActualizaFamilia = "UPDATE familiares SET apellidoynombre = :apellidoynombre, tipodocumento = :tipodocumento, nrodocumento = :nrodocumento, fechanacimiento = :fechanacimiento, nacionalidad = :nacionalidad, sexo = :sexo, ddn = :ddn, telefono = :telefono, email = :email, cuil = :cuil, tipoparentesco = :tipoparentesco, fechaobrasocial = :fechaobrasocial, estudia = :estudia, certificadoestudio = :certificadoestudio, emisioncertificadoestudio = :emisioncertificadoestudio, vencimientocertificadoestudio = :vencimientocertificadoestudio, emitecarnet = :emitecarnet, informesss = :informesss, tipoinformesss = :tipoinformesss, fechainformesss = :fechainformesss, usuarioinformesss = :usuarioinformesss, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";
		$resActualizaFamilia = $dbh->prepare($sqlActualizaFamilia);
		if($resActualizaFamilia->execute(array(':apellidoynombre' => strtoupper($_POST['apellidoynombre']), ':tipodocumento' => $_POST['selectTipDoc'], ':nrodocumento' => $_POST['nrodocumento'], ':fechanacimiento' => fechaParaGuardar($_POST['fechanacimiento']), ':nacionalidad' => $_POST['selectNacion'], ':sexo' => $_POST['selectSexo'], ':ddn' => $_POST['ddn'], ':telefono' => $_POST['telefono'], ':email' => strtolower($_POST['email']), ':cuil' => $_POST['cuil'], ':tipoparentesco' => $_POST['selectParentesco'], ':fechaobrasocial' => fechaParaGuardar($_POST['fechaobrasocial']), ':estudia' => $estudia, ':certificadoestudio' => $certificadoestudio, ':emisioncertificadoestudio' => $emisioncertificadoestudio, ':vencimientocertificadoestudio' => $vencimientocertificadoestudio, ':emitecarnet' => $_POST['selectEmiteCarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden))) {
		}
	
		$dbh->commit();
		$pagina = "fichaFamiliar.php?nroAfi=$nroafiliado&estAfi=1&estFam=1&nroOrd=$nroorden";
		Header("Location: $pagina"); 
	}
	catch (PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<title>.: Familiar :.</title>
</head>
<body bgcolor="#CCCCCC" > 
</body>
</html>
