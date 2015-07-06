<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
//var_dump($_POST);
$base = $_SESSION['dbname'];
$sqlBuscaNro = "SELECT AUTO_INCREMENT FROM information_schema.TABLES
WHERE TABLE_SCHEMA = '$base' AND TABLE_NAME = 'titulares'";
$resBuscaNro = mysql_query($sqlBuscaNro,$db);
$rowBuscaNro = mysql_fetch_array($resBuscaNro);

$nroafiliado = $rowBuscaNro['AUTO_INCREMENT'];
$cuil = $_POST['cuil'];
$tipodocumento = $_POST['selectTipDoc'];
$nrodocumento = $_POST['nrodocumento'];
$discapacidad = "0";
$certificadodiscapacidad = "0";
$cantidadcarnet = 0;
$fechacarnet = "";
$lote = "";
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

$noexiste = TRUE;

$sqlTitularCuil = "SELECT nroafiliado FROM titulares WHERE cuil = '$cuil'";
$resTitularCuil = mysql_query($sqlTitularCuil,$db);
if(mysql_num_rows($resTitularCuil)>0) {
	//echo $sqlTitularCuil;
	$noexiste = FALSE;
	$rowTitularCuil = mysql_fetch_array($resTitularCuil);
	$nroafiliado = $rowTitularCuil['nroafiliado'];
	$estadoafiliado = 1;
} else {
	$sqlTitularDocu = "SELECT nroafiliado FROM titulares WHERE tipodocumento = '$tipodocumento' AND nrodocumento = '$nrodocumento'";
	$resTitularDocu = mysql_query($sqlTitularDocu,$db);
	if(mysql_num_rows($resTitularDocu)>0) {
		//echo $sqlTitularDocu;
		$noexiste = FALSE;
		$rowTitularDocu = mysql_fetch_array($resTitularDocu);
		$nroafiliado = $rowTitularDocu['nroafiliado'];
		$estadoafiliado = 1;
	} else {
		$sqlBajatitCuil = "SELECT nroafiliado FROM titularesdebaja WHERE cuil = '$cuil'";
		$resBajatitCuil = mysql_query($sqlBajatitCuil,$db);
		if(mysql_num_rows($resBajatitCuil)>0) {
			//echo $sqlBajatitCuil;
			$noexiste = FALSE;
			$rowBajatitCuil = mysql_fetch_array($resBajatitCuil);
			$nroafiliado = $rowBajatitCuil['nroafiliado'];
			$estadoafiliado = 0;
		} else {
			$sqlBajatitDocu = "SELECT nroafiliado FROM titularesdebaja WHERE tipodocumento = '$tipodocumento' AND nrodocumento = '$nrodocumento'";
			$resBajatitDocu = mysql_query($sqlBajatitDocu,$db);
			if(mysql_num_rows($resBajatitDocu)>0) {
				//echo $sqlBajatitDocu;
				$noexiste = FALSE;
				$rowBajatitDocu = mysql_fetch_array($resBajatitDocu);
				$nroafiliado = $rowBajatitDocu['nroafiliado'];
				$estadoafiliado = 0;
			}
		}
	}
}

$sqlFamiliarCuil = "SELECT nroafiliado FROM familiares WHERE cuil = '$cuil'";
$resFamiliarCuil = mysql_query($sqlFamiliarCuil,$db);
if(mysql_num_rows($resFamiliarCuil)>0) {
	//echo $sqlFamiliarCuil;
	$noexiste = FALSE;
	$rowFamiliarCuil = mysql_fetch_array($resFamiliarCuil);
	$nroafiliado = $rowFamiliarCuil['nroafiliado'];
	$estadoafiliado = 1;
} else {
	$sqlFamiliarDocu = "SELECT nroafiliado FROM familiares WHERE tipodocumento = '$tipodocumento' AND nrodocumento = '$nrodocumento'";
	$resFamiliarDocu = mysql_query($sqlFamiliarDocu,$db);
	if(mysql_num_rows($resFamiliarDocu)>0) {
		//echo $sqlFamiliarDocu;
		$noexiste = FALSE;
		$rowFamiliarDocu = mysql_fetch_array($resFamiliarDocu);
		$nroafiliado = $rowFamiliarDocu['nroafiliado'];
		$estadoafiliado = 1;
	} else {
		$sqlBajafamCuil = "SELECT nroafiliado FROM familiaresdebaja WHERE cuil = '$cuil'";
		$resBajafamCuil = mysql_query($sqlBajafamCuil,$db);
		if(mysql_num_rows($resBajafamCuil)>0) {
			//echo $sqlBajafamCuil;
			$noexiste = FALSE;
			$rowBajafamCuil = mysql_fetch_array($resBajafamCuil);
			$nroafiliado = $rowBajafamCuil['nroafiliado'];
			$estadoafiliado = 0;
		} else {
			$sqlBajafamDocu = "SELECT nroafiliado FROM familiaresdebaja WHERE tipodocumento = '$tipodocumento' AND nrodocumento = '$nrodocumento'";
			$resBajafamDocu = mysql_query($sqlBajafamDocu,$db);
			if(mysql_num_rows($resBajafamDocu)>0) {
				//echo $sqlBajafamDocu;
				$noexiste = FALSE;
				$rowBajafamDocu = mysql_fetch_array($resBajafamDocu);
				$nroafiliado = $rowBajafamDocu['nroafiliado'];
				$estadoafiliado = 0;
			}
		}
	}
}

if($noexiste) {
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		//echo "$hostname"; echo "<br>";
		//echo "$dbname"; echo "<br>";
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		//echo 'Connected to database<br/>';
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		$sqlAgregaTitular = "INSERT INTO titulares (nroafiliado, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, estadocivil, codprovin, indpostal, numpostal, alfapostal, codlocali, domicilio, ddn, telefono, email, fechaobrasocial, tipoafiliado, solicitudopcion, situaciontitularidad, discapacidad, certificadodiscapacidad, cuil, cuitempresa, fechaempresa, codidelega, categoria, emitecarnet, cantidadcarnet, fechacarnet, lote, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring) VALUES (:nroafiliado, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :estadocivil, :codprovin, :indpostal, :numpostal, :alfapostal, :codlocali, :domicilio, :ddn, :telefono, :email, :fechaobrasocial, :tipoafiliado, :solicitudopcion, :situaciontitularidad, :discapacidad, :certificadodiscapacidad, :cuil, :cuitempresa, :fechaempresa, :codidelega, :categoria, :emitecarnet, :cantidadcarnet, :fechacarnet, :lote, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring)";
		$resAgregaTitular = $dbh->prepare($sqlAgregaTitular);
		if($resAgregaTitular->execute(array(':nroafiliado' => $rowBuscaNro['AUTO_INCREMENT'], ':apellidoynombre' => strtoupper($_POST['apellidoynombre']), ':tipodocumento' => $_POST['selectTipDoc'], ':nrodocumento' => $_POST['nrodocumento'], ':fechanacimiento' => fechaParaGuardar($_POST['fechanacimiento']), ':nacionalidad' => $_POST['selectNacion'], ':sexo' => $_POST['selectSexo'], ':estadocivil' => $_POST['selectEstCiv'], ':codprovin' => $_POST['codprovin'], ':indpostal' => $_POST['indpostal'], ':numpostal' => $_POST['numpostal'], ':alfapostal' => $_POST['alfapostal'], ':codlocali' => $_POST['selectLocalidad'], ':domicilio' => strtoupper($_POST['domicilio']), ':ddn' => $_POST['ddn'], ':telefono' => $_POST['telefono'], ':email' => strtolower($_POST['email']), ':fechaobrasocial' => fechaParaGuardar($_POST['fechaobrasocial']), ':tipoafiliado' => $_POST['selectTipoAfil'], ':solicitudopcion' => $_POST['solicitudopcion'], ':situaciontitularidad' => $_POST['selectSitTitular'], ':discapacidad' => $discapacidad, ':certificadodiscapacidad' => $certificadodiscapacidad, ':cuil' => $_POST['cuil'], ':cuitempresa' => $_POST['cuitempresa'], ':fechaempresa' => fechaParaGuardar($_POST['fechaempresa']), ':codidelega' => $_POST['selectDelega'], ':categoria' => strtoupper($_POST['categoria']), ':emitecarnet' => $_POST['selectEmiteCarnet'], ':cantidadcarnet' => $cantidadcarnet, ':fechacarnet' => $fechacarnet, ':lote' => $lote, ':tipocarnet' => $tipocarnet, ':vencimientocarnet' => $vencimientocarnet, ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':foto' => $foto, ':fecharegistro' => $fecharegistro, ':usuarioregistro' => $usuarioregistro, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':mirroring' => $mirroring)))
	
		$dbh->commit();
		$pagina = "afiliado.php?nroAfi=$nroafiliado&estAfi=1";
		Header("Location: $pagina");
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
} else {
	$pagina = "afiliadoExiste.php?nroAfi=$nroafiliado&estAfi=$estadoafiliado";
	Header("Location: $pagina"); 
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
