<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$datos = array_values($_POST);

//echo $datos[0]; echo "<br>"; //nroafiliado
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
//echo $datos[13]; echo "<br>"; //nombreprovin (no guarda)
//echo $datos[14]; echo "<br>"; //codprovin
$codprovin = $datos[14];
//echo $datos[15]; echo "<br>"; //ddn
$ddn = $datos[15];
//echo $datos[16]; echo "<br>"; //telefono
$telefono = $datos[16];
//echo $datos[17]; echo "<br>"; //email
$email = strtolower ($datos[17]);
//echo $datos[18]; echo "<br>"; //fechaobrasocial
$fechaobrasocial = fechaParaGuardar($datos[18]); 
//echo $datos[19]; echo "<br>"; //tipoafiliado
$tipoafiliado = $datos[19];
//echo $datos[20]; echo "<br>"; //solicitudopcion
$solicitudopcion = $datos[20];
//echo $datos[21]; echo "<br>"; //situaciontitularidad
$situaciontitularidad = $datos[21];
//echo $datos[22]; echo "<br>"; //cuil
$cuil = $datos[22];
//echo $datos[23]; echo "<br>"; //cuitempresa
$cuitempresa = $datos[23];
//echo $datos[24]; echo "<br>"; //nombreempresa (no guarda)
//echo $datos[25]; echo "<br>"; //fechaempresa
$fechaempresa = fechaParaGuardar($datos[25]);
//echo $datos[26]; echo "<br>"; //codidelega
$codidelega = $datos[26];
//echo $datos[27]; echo "<br>"; //categoria
$categoria = strtoupper($datos[27]);
//echo $datos[28]; echo "<br>"; //emitecarnet
$emitecarnet = $datos[28];
$discapacidad = "0";
$certificadodiscapacidad = "0";
$cantidadcarnet = 0;
$fechacarnet = "";
$tipocarnet = "";
$vencimientocarnet = "";
$informesss = 1;
$tipoinformesss = "A";
$fechainformesss = "";
$usuarioinformesss = "";
$foto = "";
$archivo = '../img/Titular sin Foto.jpg';
if ($archivo != "") {
	$fp = fopen($archivo, 'r');
	if ($fp){
		$foto = fread($fp, filesize($archivo));
		fclose($fp);
	}
}
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = "";
$usuariomodificacion = "";
$mirroring = "N";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$sqlAgregaTitular = "INSERT INTO titulares (nroafiliado, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, estadocivil, codprovin, indpostal, numpostal, alfapostal, codlocali, domicilio, ddn, telefono, email, fechaobrasocial, tipoafiliado, solicitudopcion, situaciontitularidad, discapacidad, certificadodiscapacidad, cuil, cuitempresa, fechaempresa, codidelega, categoria, emitecarnet, cantidadcarnet, fechacarnet, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring) VALUES (:nroafiliado, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :estadocivil, :codprovin, :indpostal, :numpostal, :alfapostal, :codlocali, :domicilio, :ddn, :telefono, :email, :fechaobrasocial, :tipoafiliado, :solicitudopcion, :situaciontitularidad, :discapacidad, :certificadodiscapacidad, :cuil, :cuitempresa, :fechaempresa, :codidelega, :categoria, :emitecarnet, :cantidadcarnet, :fechacarnet, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring)";
	$resAgregaTitular = $dbh->prepare($sqlAgregaTitular);
	if($resAgregaTitular->execute(array(':nroafiliado' => $nroafiliado, ':apellidoynombre' => $apellidoynombre, ':tipodocumento' => $tipodocumento, ':nrodocumento' => $nrodocumento, ':fechanacimiento' => $fechanacimiento, ':nacionalidad' => $nacionalidad, ':sexo' => $sexo, ':estadocivil' => $estadocivil, ':codprovin' => $codprovin, ':indpostal' => $indpostal, ':numpostal' => $numpostal, ':alfapostal' => $alfapostal, ':codlocali' => $codlocali, ':domicilio' => $domicilio, ':ddn' => $ddn, ':telefono' => $telefono, ':email' => $email, ':fechaobrasocial' => $fechaobrasocial, ':tipoafiliado' => $tipoafiliado, ':solicitudopcion' => $solicitudopcion, ':situaciontitularidad' => $situaciontitularidad, ':discapacidad' => $discapacidad, ':certificadodiscapacidad' => $certificadodiscapacidad, ':cuil' => $cuil, ':cuitempresa' => $cuitempresa, ':fechaempresa' => $fechaempresa, ':codidelega' => $codidelega, ':categoria' => $categoria, ':emitecarnet' => $emitecarnet, ':cantidadcarnet' => $cantidadcarnet, ':fechacarnet' => $fechacarnet, ':tipocarnet' => $tipocarnet, ':vencimientocarnet' => $vencimientocarnet, ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':foto' => $foto, ':fecharegistro' => $fecharegistro, ':usuarioregistro' => $usuarioregistro, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':mirroring' => $mirroring)))

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
