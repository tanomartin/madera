<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
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
//echo $datos[7]; echo "<br>"; //ddn
$ddn = $datos[7];
//echo $datos[8]; echo "<br>"; //telefono
$telefono = $datos[8];
//echo $datos[9]; echo "<br>"; //email
$email = strtolower ($datos[9]);
//echo $datos[10]; echo "<br>"; //cuil
$cuil = $datos[10];
//echo $datos[11]; echo "<br>"; //tipoparentesco
$tipoparentesco = $datos[11];
//echo $datos[12]; echo "<br>"; //fechaobrasocial
$fechaobrasocial = fechaParaGuardar($datos[12]); 
//echo $datos[13]; echo "<br>"; //estudia
$estudia = $datos[13];
//echo $datos[14]; echo "<br>"; //certificadoestudio
$certificadoestudio = $datos[14];
//echo $datos[15]; echo "<br>"; //emitecarnet
$emitecarnet = $datos[15];
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
$archivo = '../img/Familiar sin Foto.jpg';
if ($archivo != "") {
	$fp = fopen($archivo, 'r');
	if ($fp){
		$foto = fread($fp, filesize($archivo));
		fclose($fp);
	}
}
$fecharegistro = date("Y-m-d H:m:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = "";
$usuariomodificacion = "";
$mirroring = "N";

$sqlLeeFamilia = "SELECT * FROM familiares WHERE nroafiliado = $nroafiliado";
$resLeeFamilia = mysql_query($sqlLeeFamilia,$db);
$canLeeFamilia = mysql_num_rows($resLeeFamilia);

$nroorden = $canLeeFamilia + 1;

//echo $nroorden; echo "<br>"; //nroorden

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$sqlAgregaFamilia = "INSERT INTO familiares (nroafiliado, nroorden, tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, ddn, telefono, email, fechaobrasocial, discapacidad, certificadodiscapacidad, estudia, certificadoestudio, cuil, emitecarnet, cantidadcarnet, fechacarnet, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring) VALUES (:nroafiliado, :nroorden, :tipoparentesco, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :ddn, :telefono, :email, :fechaobrasocial, :discapacidad, :certificadodiscapacidad, :estudia, :certificadoestudio, :cuil, :emitecarnet, :cantidadcarnet, :fechacarnet, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring)";
	$resAgregaFamilia = $dbh->prepare($sqlAgregaFamilia);
	if($resAgregaFamilia->execute(array(':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden, ':tipoparentesco' => $tipoparentesco,':apellidoynombre' => $apellidoynombre, ':tipodocumento' => $tipodocumento, ':nrodocumento' => $nrodocumento, ':fechanacimiento' => $fechanacimiento, ':nacionalidad' => $nacionalidad, ':sexo' => $sexo, ':ddn' => $ddn, ':telefono' => $telefono, ':email' => $email, ':fechaobrasocial' => $fechaobrasocial, ':discapacidad' => $discapacidad, ':certificadodiscapacidad' => $certificadodiscapacidad, ':estudia' => $estudia, ':certificadoestudio' => $certificadoestudio, ':cuil' => $cuil, ':emitecarnet' => $emitecarnet, ':cantidadcarnet' => $cantidadcarnet, ':fechacarnet' => $fechacarnet, ':tipocarnet' => $tipocarnet, ':vencimientocarnet' => $vencimientocarnet, ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':foto' => $foto, ':fecharegistro' => $fecharegistro, ':usuarioregistro' => $usuarioregistro, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':mirroring' => $mirroring,)))


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

<title>.: Familiar :.</title>
</head>
<body bgcolor="#CCCCCC" > 
</body>
</html>
