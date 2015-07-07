<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

if(isset($_POST) && !empty($_POST)) {
//	var_dump($_POST);
	$nroafiliado = $_POST['nroafiliado'];
	$cuil = $_POST['cuil'];
	$tipodocumento = $_POST['selectTipDoc'];
	$nrodocumento = $_POST['nrodocumento'];

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
		$sqlLeeFamilia = "SELECT nroafiliado FROM familiares WHERE nroafiliado = $nroafiliado";
		$resLeeFamilia = mysql_query($sqlLeeFamilia,$db);
		$canLeeFamilia = mysql_num_rows($resLeeFamilia);
	
		$sqlLeeFamiliaDebaja = "SELECT nroafiliado FROM familiaresdebaja WHERE nroafiliado = $nroafiliado";
		$resLeeFamiliaDebaja = mysql_query($sqlLeeFamiliaDebaja,$db);
		$canLeeFamiliaDebaja = mysql_num_rows($resLeeFamiliaDebaja);
	
		$nroorden = ($canLeeFamilia + $canLeeFamiliaDebaja) + 1;
	
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
		
		$discapacidad = 0;
		$certificadodiscapacidad = 0;
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
	
		try {
			$hostname = $_SESSION['host'];
			$dbname = $_SESSION['dbname'];
			$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$dbh->beginTransaction();
		
			$sqlAgregaFamilia = "INSERT INTO familiares (nroafiliado, nroorden, tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, ddn, telefono, email, fechaobrasocial, discapacidad, certificadodiscapacidad, estudia, certificadoestudio, emisioncertificadoestudio, vencimientocertificadoestudio, cuil, emitecarnet, cantidadcarnet, fechacarnet, lote, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring) VALUES (:nroafiliado, :nroorden, :tipoparentesco, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :ddn, :telefono, :email, :fechaobrasocial, :discapacidad, :certificadodiscapacidad, :estudia, :certificadoestudio, :emisioncertificadoestudio, :vencimientocertificadoestudio, :cuil, :emitecarnet, :cantidadcarnet, :fechacarnet, :lote, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring)";
			$resAgregaFamilia = $dbh->prepare($sqlAgregaFamilia);
			if($resAgregaFamilia->execute(array(':nroafiliado' => $_POST['nroafiliado'], ':nroorden' => $nroorden, ':tipoparentesco' => $_POST['selectParentesco'],':apellidoynombre' => strtoupper($_POST['apellidoynombre']), ':tipodocumento' => $_POST['selectTipDoc'], ':nrodocumento' => $_POST['nrodocumento'], ':fechanacimiento' => fechaParaGuardar($_POST['fechanacimiento']), ':nacionalidad' => $_POST['selectNacion'], ':sexo' => $_POST['selectSexo'], ':ddn' => $ddn = $_POST['ddn'], ':telefono' => $_POST['telefono'], ':email' => strtolower($_POST['email']), ':fechaobrasocial' => fechaParaGuardar($_POST['fechaobrasocial']), ':discapacidad' => $discapacidad, ':certificadodiscapacidad' => $certificadodiscapacidad, ':estudia' => $estudia, ':certificadoestudio' => $certificadoestudio, ':emisioncertificadoestudio' => $emisioncertificadoestudio, ':vencimientocertificadoestudio' => $vencimientocertificadoestudio, ':cuil' => $_POST['cuil'], ':emitecarnet' => $_POST['selectEmiteCarnet'], ':cantidadcarnet' => $cantidadcarnet, ':fechacarnet' => $fechacarnet, ':lote' => $lote, ':tipocarnet' => $tipocarnet, ':vencimientocarnet' => $vencimientocarnet, ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':foto' => $foto, ':fecharegistro' => $fecharegistro, ':usuarioregistro' => $usuarioregistro, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':mirroring' => $mirroring))) {
			}
		
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
