<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

//var_dump($_POST);
$nroafiliado = $_POST['nroafiliado'];
$sqlTitular = "SELECT informesss, tipoinformesss, fechainformesss, usuarioinformesss FROM titulares WHERE nroafiliado = '$nroafiliado'";
$resTitular = mysql_query($sqlTitular,$db);
$rowTitular = mysql_fetch_array($resTitular);

if($rowTitular['informesss'] == 0) {
	$informesss = 1;
	$tipoinformesss = "M";
	$fechainformesss = "";
	$usuarioinformesss = "";
} else {
	$informesss = $rowTitular['informesss'];
	$tipoinformesss = $rowTitular['tipoinformesss'];
	$fechainformesss = $rowTitular['fechainformesss'];
	$usuarioinformesss = $rowTitular['usuarioinformesss'];
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

	$sqlActualizaTitular = "UPDATE titulares SET apellidoynombre = :apellidoynombre, tipodocumento = :tipodocumento, nrodocumento = :nrodocumento, fechanacimiento = :fechanacimiento, nacionalidad = :nacionalidad, sexo = :sexo, estadocivil = :estadocivil, domicilio = :domicilio, indpostal = :indpostal, numpostal = :numpostal, alfapostal = :alfapostal, codlocali = :codlocali, codprovin = :codprovin, ddn = :ddn, telefono = :telefono, email = :email, fechaobrasocial = :fechaobrasocial, tipoafiliado = :tipoafiliado, solicitudopcion = :solicitudopcion, situaciontitularidad = :situaciontitularidad, cuil = :cuil, cuitempresa = :cuitempresa, fechaempresa = :fechaempresa, codidelega = :codidelega, categoria = :categoria, emitecarnet = :emitecarnet, informesss = :informesss, tipoinformesss = :tipoinformesss,  fechainformesss = :fechainformesss, usuarioinformesss = :usuarioinformesss, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado";
	$resActualizaTitular = $dbh->prepare($sqlActualizaTitular);
	if($resActualizaTitular->execute(array(':nroafiliado' => $_POST['nroafiliado'], ':apellidoynombre' => strtoupper($_POST['apellidoynombre']), ':tipodocumento' => $_POST['selectTipDoc'], ':nrodocumento' => $_POST['nrodocumento'], ':fechanacimiento' => fechaParaGuardar($_POST['fechanacimiento']), ':nacionalidad' => $_POST['selectNacion'], ':sexo' => $_POST['selectSexo'], ':estadocivil' => $_POST['selectEstCiv'], ':domicilio' => strtoupper($_POST['domicilio']), ':indpostal' => $_POST['indpostal'], ':numpostal' => $_POST['numpostal'], ':alfapostal' => $_POST['alfapostal'], ':codlocali' => $_POST['selectLocalidad'], ':codprovin' => $_POST['codprovin'], ':ddn' => $_POST['ddn'], ':telefono' => $_POST['telefono'], ':email' => strtolower($_POST['email']), ':fechaobrasocial' => fechaParaGuardar($_POST['fechaobrasocial']), ':tipoafiliado' => $_POST['selectTipoAfil'], ':solicitudopcion' => $_POST['solicitudopcion'], ':situaciontitularidad' => $_POST['selectSitTitular'], ':cuil' => $_POST['cuil'], ':cuitempresa' => $_POST['cuitempresa'], ':fechaempresa' => fechaParaGuardar($_POST['fechaempresa']), ':codidelega' => $_POST['selectDelega'], ':categoria' => strtoupper($_POST['categoria']), ':emitecarnet' => $_POST['selectEmiteCarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion)))

	$dbh->commit();
	$pagina = "afiliado.php?nroAfi=$nroafiliado&estAfi=1";
	Header("Location: $pagina"); 
}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
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

<title>.: Afiliado :.</title>
</head>
<body bgcolor="#CCCCCC" > 
</body>
</html>
