<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");

$datos = array_values($_POST);

//echo $datos[0]; echo "<br>"; //nroafiliado (no guarda)
$nroafiliado = $datos[0];
//echo $datos[1]; echo "<br>"; //apellidoynombre
$apellidoynombre = strtoupper($datos[1]);
//echo $datos[2]; echo "<br>"; //tipodocumento
$tipodocumento = $datos[2];
//echo $datos[3]; echo "<br>"; //nrodocumento
$nrodocumento = $datos[3];
//echo $datos[4]; echo "<br>"; //fechanacimiento
$fechanacimiento = fechaParaGuardar($datos[4]);
//echo $datos[5]; echo "<br>"; //nacionalidad
$nacionalidad = $datos[5];
//echo $datos[6]; echo "<br>"; //sexo
$sexo = $datos[6];
//echo $datos[7]; echo "<br>"; //estadocivil
$estadocivil = $datos[7];
//echo $datos[8]; echo "<br>"; //domicilio
$domicilio = strtoupper($datos[8]);
//echo $datos[9]; echo "<br>"; //indpostal
$indpostal = $datos[9];
//echo $datos[10]; echo "<br>"; //numpostal
$numpostal = $datos[10];
//echo $datos[11]; echo "<br>"; //alfapostal
$alfapostal = $datos[11];
//echo $datos[12]; echo "<br>"; //codlocali
$codlocali = $datos[12];
//echo $datos[13]; echo "<br>"; //codprovin
$codprovin = $datos[13];
//echo $datos[14]; echo "<br>"; //ddn
$ddn = $datos[14];
//echo $datos[15]; echo "<br>"; //telefono
$telefono = $datos[15];
//echo $datos[16]; echo "<br>"; //email
$email = strtolower ($datos[16]);
//echo $datos[17]; echo "<br>"; //fechaobrasocial
$fechaobrasocial = fechaParaGuardar($datos[17]); 
//echo $datos[18]; echo "<br>"; //tipoafiliado
$tipoafiliado = $datos[18];
//echo $datos[19]; echo "<br>"; //solicitudopcion
$solicitudopcion = $datos[19];
//echo $datos[20]; echo "<br>"; //situaciontitularidad
$situaciontitularidad = $datos[20];
//echo $datos[21]; echo "<br>"; //discapacidad (no guarda)
//echo $datos[22]; echo "<br>"; //certificadodiscapacidad (no guarda)
//echo $datos[23]; echo "<br>"; //emisiondiscapacidad (no guarda)
//echo $datos[24]; echo "<br>"; //vencimientodiscapacidad (no guarda)
//echo $datos[25]; echo "<br>"; //cuil
$cuil = $datos[25];
//echo $datos[26]; echo "<br>"; //cuitempresa
$cuitempresa = $datos[26];
//echo $datos[27]; echo "<br>"; //nombreempresa (no guarda)
//echo $datos[28]; echo "<br>"; //fechaempresa
$fechaempresa = fechaParaGuardar($datos[28]);
//echo $datos[29]; echo "<br>"; //codidelega
$codidelega = $datos[29];
//echo $datos[30]; echo "<br>"; //categoria
$categoria = strtoupper($datos[30]);
//echo $datos[31]; echo "<br>"; //emitecarnet
$emitecarnet = $datos[31];
//echo $datos[32]; echo "<br>"; //cantidadcarnet (no guarda)
//echo $datos[33]; echo "<br>"; //fechacarnet (no guarda)
//echo $datos[34]; echo "<br>"; //tipocarnet (no guarda)
//echo $datos[35]; echo "<br>"; //vencimientocarnet (no guarda)
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

	$sqlActualizaTitular = "UPDATE titulares SET apellidoynombre = :apellidoynombre, tipodocumento = :tipodocumento, nrodocumento = :nrodocumento, fechanacimiento = :fechanacimiento, nacionalidad = :nacionalidad, sexo = :sexo, estadocivil = :estadocivil, domicilio = :domicilio, indpostal = :indpostal, numpostal = :numpostal, alfapostal = :alfapostal, codlocali = :codlocali, codprovin = :codprovin, ddn = :ddn, telefono = :telefono, email = :email, fechaobrasocial = :fechaobrasocial, tipoafiliado = :tipoafiliado, solicitudopcion = :solicitudopcion, situaciontitularidad = :situaciontitularidad, cuil = :cuil, cuitempresa = :cuitempresa, fechaempresa = :fechaempresa, codidelega = :codidelega, categoria = :categoria, emitecarnet = :emitecarnet, informesss = :informesss, tipoinformesss = :tipoinformesss,  fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado";
	$resActualizaTitular = $dbh->prepare($sqlActualizaTitular);
	if($resActualizaTitular->execute(array(':nroafiliado' => $nroafiliado, ':apellidoynombre' => $apellidoynombre, ':tipodocumento' => $tipodocumento, ':nrodocumento' => $nrodocumento, ':fechanacimiento' => $fechanacimiento, ':nacionalidad' => $nacionalidad, ':sexo' => $sexo, ':estadocivil' => $estadocivil, ':domicilio' => $domicilio, ':indpostal' => $indpostal, ':numpostal' => $numpostal, ':alfapostal' => $alfapostal, ':codlocali' => $codlocali, ':codprovin' => $codprovin, ':ddn' => $ddn, ':telefono' => $telefono, ':email' => $email, ':fechaobrasocial' => $fechaobrasocial, ':tipoafiliado' => $tipoafiliado, ':solicitudopcion' => $solicitudopcion, ':situaciontitularidad' => $situaciontitularidad, ':cuil' => $cuil, ':cuitempresa' => $cuitempresa, ':fechaempresa' => $fechaempresa, ':codidelega' => $codidelega, ':categoria' => $categoria, ':emitecarnet' => $emitecarnet, ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion)))


	$dbh->commit();
	$pagina = "afiliado.php?nroAfi=$nroafiliado&estAfi=1";
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

<title>.: Afiliado :.</title>
</head>
<body bgcolor="#CCCCCC" > 
</body>
</html>
