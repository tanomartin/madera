<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

if(isset($_POST) && !empty($_POST)) {
	//var_dump($_POST);
	$nroafiliado = $_POST['nroafiliado'];
	$tipafiliado = $_POST['tipafiliado'];
	$ordafiliado = $_POST['nroorden'];
	$emitecarnet = 0;
	$informesss = 1;
	$tipoinformesss = "A";
	$fechainformesss = "";
	$usuarioinformesss = "";
	$fechamodificacion = date("Y-m-d H:i:s");
	$usuariomodificacion = $_SESSION['usuario'];
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
	
		if($tipafiliado==1) {
			$sqlLeeTitular = "SELECT * FROM titularesdebaja WHERE nroafiliado = '$nroafiliado'";
			$resLeeTitular = mysql_query($sqlLeeTitular,$db);
			$rowLeeTitular = mysql_fetch_array($resLeeTitular);
	
			$cuilafiliado = $rowLeeTitular['cuil'];

			$sqlLeeDDJJ = "SELECT cuit, anoddjj, mesddjj FROM detddjjospim WHERE cuil = '$cuilafiliado' ORDER BY anoddjj DESC, mesddjj DESC LIMIT 1";
			$resLeeDDJJ = mysql_query($sqlLeeDDJJ,$db);
			$cantddjj = mysql_num_rows($resLeeDDJJ);
			if($cantddjj > 0) {
				$rowLeeDDJJ = mysql_fetch_array($resLeeDDJJ);

				$cuitempresa = $rowLeeDDJJ['cuit'];

				if($rowLeeDDJJ['mesddjj'] < 10) {
					$mesddjj = "0".$rowLeeDDJJ['mesddjj'];
				} else {
					$mesddjj = $rowLeeDDJJ['mesddjj'];
				}

				$fechaempresa = $rowLeeDDJJ['anoddjj']."-".$mesddjj."-01";
			}
			else {
				$sqlLeeAportes = "SELECT cuit, anoddjj, mesddjj FROM afiptransferencias WHERE cuil = '$cuilafiliado' ORDER BY anoddjj DESC, mesddjj DESC LIMIT 1";
				$resLeeAportes = mysql_query($sqlLeeAportes,$db);
				$rowLeeAportes = mysql_fetch_array($resLeeAportes);

				$cuitempresa = $rowLeeAportes['cuit'];
	
				if($rowLeeAportes['mespago'] < 10) {
					$mespago = "0".$rowLeeAportes['mespago'];
				} else {
					$mespago = $rowLeeAportes['mespago'];
				}
	
				$fechaempresa = $rowLeeAportes['anopago']."-".$mespago."-01";
			}

			$sqlLeeJurisdiccion = "SELECT codidelega FROM jurisdiccion WHERE cuit = '$cuitempresa' order by disgdinero DESC LIMIT 1";
			$resLeeJurisdiccion = mysql_query($sqlLeeJurisdiccion,$db);
			$rowLeeJurisdiccion = mysql_fetch_array($resLeeJurisdiccion);

			$codidelega = $rowLeeJurisdiccion['codidelega'];

			$sqlBajaTitular = "INSERT INTO titulares (nroafiliado, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, estadocivil, codprovin, indpostal, numpostal, alfapostal, codlocali, domicilio, ddn, telefono, email, fechaobrasocial, tipoafiliado, solicitudopcion, situaciontitularidad, discapacidad, certificadodiscapacidad, cuil, cuitempresa, fechaempresa, codidelega, categoria, emitecarnet, cantidadcarnet, fechacarnet, lote, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring) VALUES (:nroafiliado, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :estadocivil, :codprovin, :indpostal, :numpostal, :alfapostal, :codlocali, :domicilio, :ddn, :telefono, :email, :fechaobrasocial, :tipoafiliado, :solicitudopcion, :situaciontitularidad, :discapacidad, :certificadodiscapacidad, :cuil, :cuitempresa, :fechaempresa, :codidelega, :categoria, :emitecarnet, :cantidadcarnet, :fechacarnet, :lote, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring)";
			$resBajaTitular = $dbh->prepare($sqlBajaTitular);
			if($resBajaTitular->execute(array(':nroafiliado' => $rowLeeTitular['nroafiliado'], ':apellidoynombre' => $rowLeeTitular['apellidoynombre'], ':tipodocumento' => $rowLeeTitular['tipodocumento'], ':nrodocumento' => $rowLeeTitular['nrodocumento'], ':fechanacimiento' => $rowLeeTitular['fechanacimiento'], ':nacionalidad' => $rowLeeTitular['nacionalidad'], ':sexo' => $rowLeeTitular['sexo'], ':estadocivil' => $rowLeeTitular['estadocivil'], ':codprovin' => $rowLeeTitular['codprovin'], ':indpostal' => $rowLeeTitular['indpostal'], ':numpostal' => $rowLeeTitular['numpostal'], ':alfapostal' => $rowLeeTitular['alfapostal'], ':codlocali' => $rowLeeTitular['codlocali'], ':domicilio' => $rowLeeTitular['domicilio'], ':ddn' => $rowLeeTitular['ddn'], ':telefono' => $rowLeeTitular['telefono'], ':email' => $rowLeeTitular['email'], ':fechaobrasocial' => $rowLeeTitular['fechaobrasocial'], ':tipoafiliado' => $rowLeeTitular['tipoafiliado'], ':solicitudopcion' => $rowLeeTitular['solicitudopcion'], ':situaciontitularidad' => $rowLeeTitular['situaciontitularidad'], ':discapacidad' => $rowLeeTitular['discapacidad'], ':certificadodiscapacidad' => $rowLeeTitular['certificadodiscapacidad'], ':cuil' => $rowLeeTitular['cuil'], ':cuitempresa' => $cuitempresa, ':fechaempresa' => $fechaempresa, ':codidelega' => $codidelega, ':categoria' => $rowLeeTitular['categoria'], ':emitecarnet' => $emitecarnet, ':cantidadcarnet' => $rowLeeTitular['cantidadcarnet'], ':fechacarnet' => $rowLeeTitular['fechacarnet'], ':lote' => $rowLeeTitular['lote'], ':tipocarnet' => $rowLeeTitular['tipocarnet'], ':vencimientocarnet' => $rowLeeTitular['vencimientocarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':foto' => $rowLeeTitular['foto'], ':fecharegistro' => $rowLeeTitular['fecharegistro'], ':usuarioregistro' => $rowLeeTitular['usuarioregistro'], ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':mirroring' => $mirroring))) {
				$sqlLeeFamilia = "SELECT * FROM familiaresdebaja WHERE nroafiliado = '$nroafiliado'";
				$resLeeFamilia = mysql_query($sqlLeeFamilia,$db);
				while($rowLeeFamilia = mysql_fetch_array($resLeeFamilia)) {
					$reactivafamiliar = 1;

					if($rowLeeFamilia['tipoparentesco'] > 2 && $rowLeeFamilia['tipoparentesco'] < 9) {
						list($ano,$mes,$dia) = explode("-",$rowLeeFamilia['fechanacimiento']);
						$edad = date("Y") - $ano;
						$mescalculo = date("m") - $mes;
						$diacalculo   = date("d") - $dia;
						if($diacalculo < 0 && $mescalculo <= 0) {
							$edad--;
						}
		
						if($rowLeeFamilia['tipoparentesco'] == 3 || $rowLeeFamilia['tipoparentesco'] == 5) {
							if($edad > 20) {
								$reactivafamiliar = 0;
							}
						}

						if($rowLeeFamilia['tipoparentesco'] == 4 || $rowLeeFamilia['tipoparentesco'] == 6) {
							$reactivafamiliar = 0;
						}

						if($rowLeeFamilia['tipoparentesco'] == 7) {
							if($edad > 17) {
								$reactivafamiliar = 0;
							}
						}

						if($rowLeeFamilia['tipoparentesco'] >= 8) {
							$reactivafamiliar = 0;
						}
					}

					if($reactivafamiliar) {
						$sqlBajaFamilia = "INSERT INTO familiares (nroafiliado, nroorden, tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, ddn, telefono, email, fechaobrasocial, discapacidad, certificadodiscapacidad, estudia, certificadoestudio, cuil, emitecarnet, cantidadcarnet, fechacarnet, lote, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring) VALUES (:nroafiliado, :nroorden, :tipoparentesco, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :ddn, :telefono, :email, :fechaobrasocial, :discapacidad, :certificadodiscapacidad, :estudia, :certificadoestudio, :cuil, :emitecarnet, :cantidadcarnet, :fechacarnet, :lote, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring)";
						$resBajaFamilia = $dbh->prepare($sqlBajaFamilia);
						if($resBajaFamilia->execute(array(':nroafiliado' => $rowLeeFamilia['nroafiliado'], ':nroorden' => $rowLeeFamilia['nroorden'], ':tipoparentesco' => $rowLeeFamilia['tipoparentesco'], ':apellidoynombre' => $rowLeeFamilia['apellidoynombre'], ':tipodocumento' => $rowLeeFamilia['tipodocumento'], ':nrodocumento' => $rowLeeFamilia['nrodocumento'], ':fechanacimiento' => $rowLeeFamilia['fechanacimiento'], ':nacionalidad' => $rowLeeFamilia['nacionalidad'], ':sexo' => $rowLeeFamilia['sexo'], ':ddn' => $rowLeeFamilia['ddn'], ':telefono' => $rowLeeFamilia['telefono'], ':email' => $rowLeeFamilia['email'], ':fechaobrasocial' => $rowLeeFamilia['fechaobrasocial'], ':discapacidad' => $rowLeeFamilia['discapacidad'], ':certificadodiscapacidad' => $rowLeeFamilia['certificadodiscapacidad'], ':estudia' => $rowLeeFamilia['estudia'], ':certificadoestudio' => $rowLeeFamilia['certificadoestudio'], ':cuil' => $rowLeeFamilia['cuil'], ':emitecarnet' => $emitecarnet, ':cantidadcarnet' => $rowLeeFamilia['cantidadcarnet'], ':fechacarnet' => $rowLeeFamilia['fechacarnet'], ':lote' => $rowLeeFamilia['lote'], ':tipocarnet' => $rowLeeFamilia['tipocarnet'], ':vencimientocarnet' => $rowLeeFamilia['vencimientocarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':foto' => $rowLeeFamilia['foto'], ':fecharegistro' => $rowLeeFamilia['fecharegistro'], ':usuarioregistro' => $rowLeeFamilia['usuarioregistro'], ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':mirroring' => $mirroring))) {
							$sqlBorraFamilia = "DELETE FROM familiaresdebaja WHERE nroafiliado = :nroafiliado AND nroorden = :nroorden";
							$resBorraFamilia = $dbh->prepare($sqlBorraFamilia);
							if($resBorraFamilia->execute(array(':nroafiliado' => $rowLeeFamilia['nroafiliado'], ':nroorden' => $rowLeeFamilia['nroorden']))) {
							}
						}
					}
				}

				$sqlBorraTitular = "DELETE FROM titularesdebaja WHERE nroafiliado = :nroafiliado";
				$resBorraTitular = $dbh->prepare($sqlBorraTitular);
				if($resBorraTitular->execute(array(':nroafiliado' => $rowLeeTitular['nroafiliado']))) {
				}
			}
		} else {
			$sqlLeeFamilia = "SELECT * FROM familiaresdebaja WHERE nroafiliado = '$nroafiliado' AND nroorden = '$ordafiliado'";
			$resLeeFamilia = mysql_query($sqlLeeFamilia,$db);
			$rowLeeFamilia = mysql_fetch_array($resLeeFamilia);
	
			$sqlBajaFamilia = "INSERT INTO familiares (nroafiliado, nroorden, tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, ddn, telefono, email, fechaobrasocial, discapacidad, certificadodiscapacidad, estudia, certificadoestudio, cuil, emitecarnet, cantidadcarnet, fechacarnet, lote, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring) VALUES (:nroafiliado, :nroorden, :tipoparentesco, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :ddn, :telefono, :email, :fechaobrasocial, :discapacidad, :certificadodiscapacidad, :estudia, :certificadoestudio, :cuil, :emitecarnet, :cantidadcarnet, :fechacarnet, :lote, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring)";
			$resBajaFamilia = $dbh->prepare($sqlBajaFamilia);
			if($resBajaFamilia->execute(array(':nroafiliado' => $rowLeeFamilia['nroafiliado'], ':nroorden' => $rowLeeFamilia['nroorden'], ':tipoparentesco' => $rowLeeFamilia['tipoparentesco'], ':apellidoynombre' => $rowLeeFamilia['apellidoynombre'], ':tipodocumento' => $rowLeeFamilia['tipodocumento'], ':nrodocumento' => $rowLeeFamilia['nrodocumento'], ':fechanacimiento' => $rowLeeFamilia['fechanacimiento'], ':nacionalidad' => $rowLeeFamilia['nacionalidad'], ':sexo' => $rowLeeFamilia['sexo'], ':ddn' => $rowLeeFamilia['ddn'], ':telefono' => $rowLeeFamilia['telefono'], ':email' => $rowLeeFamilia['email'], ':fechaobrasocial' => $rowLeeFamilia['fechaobrasocial'], ':discapacidad' => $rowLeeFamilia['discapacidad'], ':certificadodiscapacidad' => $rowLeeFamilia['certificadodiscapacidad'], ':estudia' => $rowLeeFamilia['estudia'], ':certificadoestudio' => $rowLeeFamilia['certificadoestudio'], ':cuil' => $rowLeeFamilia['cuil'], ':emitecarnet' => $emitecarnet, ':cantidadcarnet' => $rowLeeFamilia['cantidadcarnet'], ':fechacarnet' => $rowLeeFamilia['fechacarnet'], ':lote' => $rowLeeFamilia['lote'], ':tipocarnet' => $rowLeeFamilia['tipocarnet'], ':vencimientocarnet' => $rowLeeFamilia['vencimientocarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':foto' => $rowLeeFamilia['foto'], ':fecharegistro' => $rowLeeFamilia['fecharegistro'], ':usuarioregistro' => $rowLeeFamilia['usuarioregistro'], ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':mirroring' => $mirroring))) {
				$sqlBorraFamilia = "DELETE FROM familiaresdebaja WHERE nroafiliado = :nroafiliado AND nroorden = :nroorden";
				$resBorraFamilia = $dbh->prepare($sqlBorraFamilia);
				if($resBorraFamilia->execute(array(':nroafiliado' => $rowLeeFamilia['nroafiliado'], ':nroorden' => $rowLeeFamilia['nroorden']))) {
				}
			}
		}
	
		$dbh->commit();
		
		$pagina = "afiliado.php?nroAfi=$nroafiliado&estAfi=1";

		Header("Location: $pagina"); 
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
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

<title>.: Baja :.</title>
</head>
<body bgcolor="#CCCCCC" > 
</body>
</html>