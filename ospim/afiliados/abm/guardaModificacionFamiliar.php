<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$datos = array_values($_POST);

//echo $datos[0]; echo "<br>"; //nroafiliado (no guarda)
$nroafiliado = $datos[0];
//echo $datos[1]; echo "<br>"; //nroorden (no guarda)
$nroorden = $datos[1];
//echo $datos[2]; echo "<br>"; //apellidoynombre
$apellidoynombre = strtoupper($datos[2]);
//echo $datos[3]; echo "<br>"; //tipodocumento
$tipodocumento = $datos[3];
//echo $datos[4]; echo "<br>"; //nrodocumento
$nrodocumento = $datos[4];
//echo $datos[5]; echo "<br>"; //fechanacimiento
$fechanacimiento = fechaParaGuardar($datos[5]);
//echo $datos[6]; echo "<br>"; //nacionalidad
$nacionalidad = $datos[6];
//echo $datos[7]; echo "<br>"; //sexo
$sexo = $datos[7];
//echo $datos[8]; echo "<br>"; //ddn
$ddn = $datos[8];
//echo $datos[9]; echo "<br>"; //telefono
$telefono = $datos[9];
//echo $datos[10]; echo "<br>"; //email
$email = strtolower ($datos[10]);
//echo $datos[11]; echo "<br>"; //cuil
$cuil = $datos[11];
//echo $datos[12]; echo "<br>"; //tipoparentesco
$tipoparentesco = $datos[12];
//echo $datos[13]; echo "<br>"; //fechaobrasocial
$fechaobrasocial = fechaParaGuardar($datos[13]); 
//echo $datos[18]; echo "<br>"; //estudia
$estudia = $datos[18];
//echo $datos[19]; echo "<br>"; //certificadoestudio
$certificadoestudio = $datos[19];
//echo $datos[20]; echo "<br>"; //emitecarnet
$emitecarnet = $datos[20];
$informesss = 1;
$tipoinformesss = "M";
$fechamodificacion = date("Y-m-d H:m:s");
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

	$sqlActualizaFamilia = "UPDATE familiares SET apellidoynombre = :apellidoynombre, tipodocumento = :tipodocumento, nrodocumento = :nrodocumento, fechanacimiento = :fechanacimiento, nacionalidad = :nacionalidad, sexo = :sexo, ddn = :ddn, telefono = :telefono, email = :email, cuil = :cuil, tipoparentesco = :tipoparentesco, fechaobrasocial = :fechaobrasocial, estudia = :estudia, certificadoestudio = :certificadoestudio, emitecarnet = :emitecarnet, informesss = :informesss, tipoinformesss = :tipoinformesss, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";
	$resActualizaFamilia = $dbh->prepare($sqlActualizaFamilia);
	if($resActualizaFamilia->execute(array(':apellidoynombre' => $apellidoynombre, ':tipodocumento' => $tipodocumento, ':nrodocumento' => $nrodocumento, ':fechanacimiento' => $fechanacimiento, ':nacionalidad' => $nacionalidad, ':sexo' => $sexo, ':ddn' => $ddn, ':telefono' => $telefono, ':email' => $email, ':cuil' => $cuil, ':tipoparentesco' => $tipoparentesco, ':fechaobrasocial' => $fechaobrasocial, ':estudia' => $estudia, ':certificadoestudio' => $certificadoestudio, ':emitecarnet' => $emitecarnet, ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden)))

	$dbh->commit();
	$pagina = "fichaFamiliar.php?nroAfi=$nroafiliado&estAfi=1&estFam=1&nroOrd=$nroorden";
	Header("Location: $pagina"); 
}
catch (PDOException $e) {
//	echo $e->getMessage();
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

<title>.: Familiar :.</title>
</head>
<body bgcolor="#CCCCCC" > 
</body>
</html>
