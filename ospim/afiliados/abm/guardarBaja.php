<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");

$datos = array_values($_POST);
echo $datos[0]; echo "<br>"; //nroafiliado
$nroafiliado = $datos[0];
echo $datos[1]; echo "<br>"; //apellidoynombre
echo $datos[2]; echo "<br>"; //tipafiliado
$tipafiliado = $datos[2];
echo $datos[3]; echo "<br>"; //ordafiliado
$ordafiliado = $datos[3];
echo $datos[4]; echo "<br>"; //fechabaja
$fechabaja = fechaParaGuardar($datos[4]);
echo $datos[5]; echo "<br>"; //motivobaja
$motivobaja = $datos[5];
$motivobajafami = "Baja del Beneficiario Titular";
$informesss = 1;
$tipoinformesss = "B";
$fechaefectivizacion = date("Y-m-d H:m:s");
$usuarioefectivizacion = $_SESSION['usuario'];

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	if($tipafiliado==1) {
		$sqlLeeTitular = "SELECT * FROM titulares WHERE nroafiliado = $nroafiliado";
		$resLeeTitular = mysql_query($sqlLeeTitular,$db);
		$rowLeeTitular = mysql_fetch_array($resLeeTitular);

		$sqlBajaTitular = "INSERT INTO titularesdebaja (nroafiliado, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, estadocivil, codprovin, indpostal, numpostal, alfapostal, codlocali, domicilio, ddn, telefono, email, fechaobrasocial, tipoafiliado, solicitudopcion, situaciontitularidad, discapacidad, certificadodiscapacidad, cuil, cuitempresa, fechaempresa, codidelega, categoria, emitecarnet, cantidadcarnet, fechacarnet, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring, fechabaja, motivobaja, fechaefectivizacion, usuarioefectivizacion) VALUES (:nroafiliado, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :estadocivil, :codprovin, :indpostal, :numpostal, :alfapostal, :codlocali, :domicilio, :ddn, :telefono, :email, :fechaobrasocial, :tipoafiliado, :solicitudopcion, :situaciontitularidad, :discapacidad, :certificadodiscapacidad, :cuil, :cuitempresa, :fechaempresa, :codidelega, :categoria, :emitecarnet, :cantidadcarnet, :fechacarnet, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring, :fechabaja, :motivobaja, :fechaefectivizacion, :usuarioefectivizacion)";
		$resBajaTitular = $dbh->prepare($sqlBajaTitular);
		if($resBajaTitular->execute(array(':nroafiliado' => $rowLeeTitular['nroafiliado'], ':apellidoynombre' => $rowLeeTitular['apellidoynombre'], ':tipodocumento' => $rowLeeTitular['tipodocumento'], ':nrodocumento' => $rowLeeTitular['nrodocumento'], ':fechanacimiento' => $rowLeeTitular['fechanacimiento'], ':nacionalidad' => $rowLeeTitular['nacionalidad'], ':sexo' => $rowLeeTitular['sexo'], ':estadocivil' => $rowLeeTitular['estadocivil'], ':codprovin' => $rowLeeTitular['codprovin'], ':indpostal' => $rowLeeTitular['indpostal'], ':numpostal' => $rowLeeTitular['numpostal'], ':alfapostal' => $rowLeeTitular['alfapostal'], ':codlocali' => $rowLeeTitular['codlocali'], ':domicilio' => $rowLeeTitular['domicilio'], ':ddn' => $rowLeeTitular['ddn'], ':telefono' => $rowLeeTitular['telefono'], ':email' => $rowLeeTitular['email'], ':fechaobrasocial' => $rowLeeTitular['fechaobrasocial'], ':tipoafiliado' => $rowLeeTitular['tipoafiliado'], ':solicitudopcion' => $rowLeeTitular['solicitudopcion'], ':situaciontitularidad' => $rowLeeTitular['situaciontitularidad'], ':discapacidad' => $rowLeeTitular['discapacidad'], ':certificadodiscapacidad' => $rowLeeTitular['certificadodiscapacidad'], ':cuil' => $rowLeeTitular['cuil'], ':cuitempresa' => $rowLeeTitular['cuitempresa'], ':fechaempresa' => $rowLeeTitular['fechaempresa'], ':codidelega' => $rowLeeTitular['codidelega'], ':categoria' => $rowLeeTitular['categoria'], ':emitecarnet' => $rowLeeTitular['emitecarnet'], ':cantidadcarnet' => $rowLeeTitular['cantidadcarnet'], ':fechacarnet' => $rowLeeTitular['fechacarnet'], ':tipocarnet' => $rowLeeTitular['tipocarnet'], ':vencimientocarnet' => $rowLeeTitular['vencimientocarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $rowLeeTitular['fechainformesss'], ':usuarioinformesss' => $rowLeeTitular['usuarioinformesss'], ':foto' => $rowLeeTitular['foto'], ':fecharegistro' => $rowLeeTitular['fecharegistro'], ':usuarioregistro' => $rowLeeTitular['usuarioregistro'], ':fechamodificacion' => $rowLeeTitular['fechamodificacion'], ':usuariomodificacion' => $rowLeeTitular['usuariomodificacion'], ':mirroring' => $rowLeeTitular['mirroring'], ':fechabaja' => $fechabaja, ':motivobaja' => $motivobaja, ':fechaefectivizacion' => $fechaefectivizacion, ':usuarioefectivizacion' => $usuarioefectivizacion)))

		$sqlLeeFamilia = "SELECT * FROM familiares WHERE nroafiliado = $nroafiliado";
		$resLeeFamilia = mysql_query($sqlLeeFamilia,$db);

		while($rowLeeFamilia = mysql_fetch_array($resLeeFamilia)) {
			$sqlBajaFamilia = "INSERT INTO familiaresdebaja (nroafiliado, nroorden, tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, ddn, telefono, email, fechaobrasocial, discapacidad, certificadodiscapacidad, estudia, certificadoestudio, cuil, emitecarnet, cantidadcarnet, fechacarnet, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring, fechabaja, motivobaja, fechaefectivizacion, usuarioefectivizacion) VALUES (:nroafiliado, :nroorden, :tipoparentesco, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :ddn, :telefono, :email, :fechaobrasocial, :discapacidad, :certificadodiscapacidad, :estudia, :certificadoestudio, :cuil, :emitecarnet, :cantidadcarnet, :fechacarnet, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring, :fechabaja, :motivobaja, :fechaefectivizacion, :usuarioefectivizacion)";
			$resBajaFamilia = $dbh->prepare($sqlBajaFamilia);
			if($resBajaFamilia->execute(array(':nroafiliado' => $rowLeeFamilia['nroafiliado'], ':nroorden' => $rowLeeFamilia['nroorden'], ':tipoparentesco' => $rowLeeFamilia['tipoparentesco'], ':apellidoynombre' => $rowLeeFamilia['apellidoynombre'], ':tipodocumento' => $rowLeeFamilia['tipodocumento'], ':nrodocumento' => $rowLeeFamilia['nrodocumento'], ':fechanacimiento' => $rowLeeFamilia['fechanacimiento'], ':nacionalidad' => $rowLeeFamilia['nacionalidad'], ':sexo' => $rowLeeFamilia['sexo'], ':ddn' => $rowLeeFamilia['ddn'], ':telefono' => $rowLeeFamilia['telefono'], ':email' => $rowLeeFamilia['email'], ':fechaobrasocial' => $rowLeeFamilia['fechaobrasocial'], ':discapacidad' => $rowLeeFamilia['discapacidad'], ':certificadodiscapacidad' => $rowLeeFamilia['certificadodiscapacidad'], ':estudia' => $rowLeeFamilia['estudia'], ':certificadoestudio' => $rowLeeFamilia['certificadoestudio'], ':cuil' => $rowLeeFamilia['cuil'], ':emitecarnet' => $rowLeeFamilia['emitecarnet'], ':cantidadcarnet' => $rowLeeFamilia['cantidadcarnet'], ':fechacarnet' => $rowLeeFamilia['fechacarnet'], ':tipocarnet' => $rowLeeFamilia['tipocarnet'], ':vencimientocarnet' => $rowLeeFamilia['vencimientocarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $rowLeeFamilia['fechainformesss'], ':usuarioinformesss' => $rowLeeFamilia['usuarioinformesss'], ':foto' => $rowLeeFamilia['foto'], ':fecharegistro' => $rowLeeFamilia['fecharegistro'], ':usuarioregistro' => $rowLeeFamilia['usuarioregistro'], ':fechamodificacion' => $rowLeeFamilia['fechamodificacion'], ':usuariomodificacion' => $rowLeeFamilia['usuariomodificacion'], ':mirroring' => $rowLeeFamilia['mirroring'], ':fechabaja' => $fechabaja, ':motivobaja' => $motivobajafami, ':fechaefectivizacion' => $fechaefectivizacion, ':usuarioefectivizacion' => $usuarioefectivizacion)));
		}
	}
	else {
		$sqlLeeFamilia = "SELECT * FROM familiares WHERE nroafiliado = $nroafiliado and nroorden = $ordafiliado";
		$resLeeFamilia = mysql_query($sqlLeeFamilia,$db);
		$rowLeeFamilia = mysql_fetch_array($resLeeFamilia);

		$sqlBajaFamilia = "INSERT INTO familiaresdebaja (nroafiliado, nroorden, tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, ddn, telefono, email, fechaobrasocial, discapacidad, certificadodiscapacidad, estudia, certificadoestudio, cuil, emitecarnet, cantidadcarnet, fechacarnet, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring, fechabaja, motivobaja, fechaefectivizacion, usuarioefectivizacion) VALUES (:nroafiliado, :nroorden, :tipoparentesco, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :ddn, :telefono, :email, :fechaobrasocial, :discapacidad, :certificadodiscapacidad, :estudia, :certificadoestudio, :cuil, :emitecarnet, :cantidadcarnet, :fechacarnet, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring, :fechabaja, :motivobaja, :fechaefectivizacion, :usuarioefectivizacion)";
		$resBajaFamilia = $dbh->prepare($sqlBajaFamilia);
		if($resBajaFamilia->execute(array(':nroafiliado' => $rowLeeFamilia['nroafiliado'], ':nroorden' => $rowLeeFamilia['nroorden'], ':tipoparentesco' => $rowLeeFamilia['tipoparentesco'], ':apellidoynombre' => $rowLeeFamilia['apellidoynombre'], ':tipodocumento' => $rowLeeFamilia['tipodocumento'], ':nrodocumento' => $rowLeeFamilia['nrodocumento'], ':fechanacimiento' => $rowLeeFamilia['fechanacimiento'], ':nacionalidad' => $rowLeeFamilia['nacionalidad'], ':sexo' => $rowLeeFamilia['sexo'], ':ddn' => $rowLeeFamilia['ddn'], ':telefono' => $rowLeeFamilia['telefono'], ':email' => $rowLeeFamilia['email'], ':fechaobrasocial' => $rowLeeFamilia['fechaobrasocial'], ':discapacidad' => $rowLeeFamilia['discapacidad'], ':certificadodiscapacidad' => $rowLeeFamilia['certificadodiscapacidad'], ':estudia' => $rowLeeFamilia['estudia'], ':certificadoestudio' => $rowLeeFamilia['certificadoestudio'], ':cuil' => $rowLeeFamilia['cuil'], ':emitecarnet' => $rowLeeFamilia['emitecarnet'], ':cantidadcarnet' => $rowLeeFamilia['cantidadcarnet'], ':fechacarnet' => $rowLeeFamilia['fechacarnet'], ':tipocarnet' => $rowLeeFamilia['tipocarnet'], ':vencimientocarnet' => $rowLeeFamilia['vencimientocarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $rowLeeFamilia['fechainformesss'], ':usuarioinformesss' => $rowLeeFamilia['usuarioinformesss'], ':foto' => $rowLeeFamilia['foto'], ':fecharegistro' => $rowLeeFamilia['fecharegistro'], ':usuarioregistro' => $rowLeeFamilia['usuarioregistro'], ':fechamodificacion' => $rowLeeFamilia['fechamodificacion'], ':usuariomodificacion' => $rowLeeFamilia['usuariomodificacion'], ':mirroring' => $rowLeeFamilia['mirroring'], ':fechabaja' => $fechabaja, ':motivobaja' => $motivobaja, ':fechaefectivizacion' => $fechaefectivizacion, ':usuarioefectivizacion' => $usuarioefectivizacion)));
	}

	$dbh->commit();
	
	if($tipafiliado==1)
		$pagina = "afiliado.php?nroAfi=$nroafiliado&estAfi=0";
	else
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

<title>.: Baja :.</title>
</head>
<body bgcolor="#CCCCCC" > 
</body>
</html>