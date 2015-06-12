<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 
include($libPath."fechas.php"); 

$datos = array_values($_POST);
$motivobaja = $datos[0];
$fechabaja = fechaParaGuardar($datos[1]);
$mirroring = "S";

$motivobajagrupo = "Baja de empresa";
$informesss = 1;
$tipoinformesss = "B";
$fechainformesss = "";
$usuarioinformesss = "";
$fechaefectivizacion = date("Y-m-d H:m:s");
$usuarioefectivizacion = $_SESSION['usuario'];

$cuit=$_GET['cuit'];
$sqlTomarDatos = "SELECT * FROM empresas where cuit = $cuit";
$resTomarDatos = mysql_query($sqlTomarDatos,$db); 
$rowTomarDatos = mysql_fetch_array($resTomarDatos);

/*************** DATOS EMPRESA A REACTIVAR *****************/
$cuit = $rowTomarDatos['cuit'];
$nombre = $rowTomarDatos['nombre'];
$codprovin = $rowTomarDatos['codprovin'];
$indpostal = $rowTomarDatos['indpostal'];
$codpostal = $rowTomarDatos['numpostal'];
$alfapostal = $rowTomarDatos['alfapostal'];
$localidad = $rowTomarDatos['codlocali'];
$domicilio = $rowTomarDatos['domilegal'];
$ddn1 = $rowTomarDatos['ddn1'];
$telefono1 = $rowTomarDatos['telefono1'];
$contacto1 = $rowTomarDatos['contactel1'];
$ddn2 = $rowTomarDatos['ddn2'];
$telefono2 = $rowTomarDatos['telefono2'];
$contacto2 = $rowTomarDatos['contactel2'];
$codigotipo = $rowTomarDatos['codigotipo'];
$peretenencia = $rowTomarDatos['codpertene'];
$actividad = $rowTomarDatos['actividad'];
$obsOspim = $rowTomarDatos['obsospim'];
$obsUsimra = $rowTomarDatos['obsusimra'];
$inicioOspim = $rowTomarDatos['iniobliosp'];
$inicioUsimra = $rowTomarDatos['iniobliusi'];
$email = $rowTomarDatos['email'];
$carpetaArchivo = $rowTomarDatos['carpetaarchivo'];
$fecharegistro = $rowTomarDatos['fecharegistro'];
$usuarioregistro = $rowTomarDatos['usuarioregistro'];
$fechamodificacion = $rowTomarDatos['fechamodificacion'];
$usuariomodificacion = $rowTomarDatos['usuariomodificacion'];
/************************************************************/

$sqlDesactivarEmpresa = "INSERT INTO empresasdebaja VALUES ('$cuit','$nombre','$codprovin','$indpostal','$codpostal','$alfapostal','$localidad','$domicilio','$ddn1','$telefono1','$contacto1','$ddn2','$telefono2','$contacto2','$codigotipo','$peretenencia','$actividad','$obsOspim','$obsUsimra','$inicioOspim','$inicioUsimra','$email','$carpetaArchivo','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion','$mirroring','$fechabaja','$motivobaja','$fechaefectivizacion','$usuarioefectivizacion')";

$sqlDeleteEmpresa = "DELETE from empresas where cuit = $cuit";

/*print($sqlTomarDatos);print("<br>");print("<br>");
print($sqlDesactivarEmpresa);print("<br>");print("<br>");
print($sqlDeleteEmpresa);print("<br>");print("<br>");*/

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	
	$sqlLeeTitular = "SELECT * FROM titulares WHERE cuitempresa = '$cuit'";
	$resLeeTitular = mysql_query($sqlLeeTitular,$db);
	while($rowLeeTitular = mysql_fetch_array($resLeeTitular)) {
		$sqlBajaTitular = "INSERT INTO titularesdebaja (nroafiliado, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, estadocivil, codprovin, indpostal, numpostal, alfapostal, codlocali, domicilio, ddn, telefono, email, fechaobrasocial, tipoafiliado, solicitudopcion, situaciontitularidad, discapacidad, certificadodiscapacidad, cuil, cuitempresa, fechaempresa, codidelega, categoria, emitecarnet, cantidadcarnet, fechacarnet, lote, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring, fechabaja, motivobaja, fechaefectivizacion, usuarioefectivizacion) VALUES (:nroafiliado, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :estadocivil, :codprovin, :indpostal, :numpostal, :alfapostal, :codlocali, :domicilio, :ddn, :telefono, :email, :fechaobrasocial, :tipoafiliado, :solicitudopcion, :situaciontitularidad, :discapacidad, :certificadodiscapacidad, :cuil, :cuitempresa, :fechaempresa, :codidelega, :categoria, :emitecarnet, :cantidadcarnet, :fechacarnet, :lote, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring, :fechabaja, :motivobaja, :fechaefectivizacion, :usuarioefectivizacion)";
		$resBajaTitular = $dbh->prepare($sqlBajaTitular);
		if($resBajaTitular->execute(array(':nroafiliado' => $rowLeeTitular['nroafiliado'], ':apellidoynombre' => $rowLeeTitular['apellidoynombre'], ':tipodocumento' => $rowLeeTitular['tipodocumento'], ':nrodocumento' => $rowLeeTitular['nrodocumento'], ':fechanacimiento' => $rowLeeTitular['fechanacimiento'], ':nacionalidad' => $rowLeeTitular['nacionalidad'], ':sexo' => $rowLeeTitular['sexo'], ':estadocivil' => $rowLeeTitular['estadocivil'], ':codprovin' => $rowLeeTitular['codprovin'], ':indpostal' => $rowLeeTitular['indpostal'], ':numpostal' => $rowLeeTitular['numpostal'], ':alfapostal' => $rowLeeTitular['alfapostal'], ':codlocali' => $rowLeeTitular['codlocali'], ':domicilio' => $rowLeeTitular['domicilio'], ':ddn' => $rowLeeTitular['ddn'], ':telefono' => $rowLeeTitular['telefono'], ':email' => $rowLeeTitular['email'], ':fechaobrasocial' => $rowLeeTitular['fechaobrasocial'], ':tipoafiliado' => $rowLeeTitular['tipoafiliado'], ':solicitudopcion' => $rowLeeTitular['solicitudopcion'], ':situaciontitularidad' => $rowLeeTitular['situaciontitularidad'], ':discapacidad' => $rowLeeTitular['discapacidad'], ':certificadodiscapacidad' => $rowLeeTitular['certificadodiscapacidad'], ':cuil' => $rowLeeTitular['cuil'], ':cuitempresa' => $rowLeeTitular['cuitempresa'], ':fechaempresa' => $rowLeeTitular['fechaempresa'], ':codidelega' => $rowLeeTitular['codidelega'], ':categoria' => $rowLeeTitular['categoria'], ':emitecarnet' => $rowLeeTitular['emitecarnet'], ':cantidadcarnet' => $rowLeeTitular['cantidadcarnet'], ':fechacarnet' => $rowLeeTitular['fechacarnet'], ':lote' => $rowLeeTitular['lote'], ':tipocarnet' => $rowLeeTitular['tipocarnet'], ':vencimientocarnet' => $rowLeeTitular['vencimientocarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':foto' => $rowLeeTitular['foto'], ':fecharegistro' => $rowLeeTitular['fecharegistro'], ':usuarioregistro' => $rowLeeTitular['usuarioregistro'], ':fechamodificacion' => $rowLeeTitular['fechamodificacion'], ':usuariomodificacion' => $rowLeeTitular['usuariomodificacion'], ':mirroring' => $rowLeeTitular['mirroring'], ':fechabaja' => $fechabaja, ':motivobaja' => $motivobajagrupo, ':fechaefectivizacion' => $fechaefectivizacion, ':usuarioefectivizacion' => $usuarioefectivizacion))) {
			$nroafiliado = $rowLeeTitular['nroafiliado'];
			$sqlLeeFamilia = "SELECT * FROM familiares WHERE nroafiliado = '$nroafiliado'";
			$resLeeFamilia = mysql_query($sqlLeeFamilia,$db);
			while($rowLeeFamilia = mysql_fetch_array($resLeeFamilia)) {
				$sqlBajaFamilia = "INSERT INTO familiaresdebaja (nroafiliado, nroorden, tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, ddn, telefono, email, fechaobrasocial, discapacidad, certificadodiscapacidad, estudia, certificadoestudio, cuil, emitecarnet, cantidadcarnet, fechacarnet, lote, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring, fechabaja, motivobaja, fechaefectivizacion, usuarioefectivizacion) VALUES (:nroafiliado, :nroorden, :tipoparentesco, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :ddn, :telefono, :email, :fechaobrasocial, :discapacidad, :certificadodiscapacidad, :estudia, :certificadoestudio, :cuil, :emitecarnet, :cantidadcarnet, :fechacarnet, :lote, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring, :fechabaja, :motivobaja, :fechaefectivizacion, :usuarioefectivizacion)";
				$resBajaFamilia = $dbh->prepare($sqlBajaFamilia);
				if($resBajaFamilia->execute(array(':nroafiliado' => $rowLeeFamilia['nroafiliado'], ':nroorden' => $rowLeeFamilia['nroorden'], ':tipoparentesco' => $rowLeeFamilia['tipoparentesco'], ':apellidoynombre' => $rowLeeFamilia['apellidoynombre'], ':tipodocumento' => $rowLeeFamilia['tipodocumento'], ':nrodocumento' => $rowLeeFamilia['nrodocumento'], ':fechanacimiento' => $rowLeeFamilia['fechanacimiento'], ':nacionalidad' => $rowLeeFamilia['nacionalidad'], ':sexo' => $rowLeeFamilia['sexo'], ':ddn' => $rowLeeFamilia['ddn'], ':telefono' => $rowLeeFamilia['telefono'], ':email' => $rowLeeFamilia['email'], ':fechaobrasocial' => $rowLeeFamilia['fechaobrasocial'], ':discapacidad' => $rowLeeFamilia['discapacidad'], ':certificadodiscapacidad' => $rowLeeFamilia['certificadodiscapacidad'], ':estudia' => $rowLeeFamilia['estudia'], ':certificadoestudio' => $rowLeeFamilia['certificadoestudio'], ':cuil' => $rowLeeFamilia['cuil'], ':emitecarnet' => $rowLeeFamilia['emitecarnet'], ':cantidadcarnet' => $rowLeeFamilia['cantidadcarnet'], ':fechacarnet' => $rowLeeFamilia['fechacarnet'], ':lote' => $rowLeeFamilia['lote'], ':tipocarnet' => $rowLeeFamilia['tipocarnet'], ':vencimientocarnet' => $rowLeeFamilia['vencimientocarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $fechainformesss, ':usuarioinformesss' => $usuarioinformesss, ':foto' => $rowLeeFamilia['foto'], ':fecharegistro' => $rowLeeFamilia['fecharegistro'], ':usuarioregistro' => $rowLeeFamilia['usuarioregistro'], ':fechamodificacion' => $rowLeeFamilia['fechamodificacion'], ':usuariomodificacion' => $rowLeeFamilia['usuariomodificacion'], ':mirroring' => $rowLeeFamilia['mirroring'], ':fechabaja' => $fechabaja, ':motivobaja' => $motivobajagrupo, ':fechaefectivizacion' => $fechaefectivizacion, ':usuarioefectivizacion' => $usuarioefectivizacion))) {
					$sqlBorraFamilia = "DELETE FROM familiares WHERE nroafiliado = :nroafiliado AND nroorden = :nroorden";
					$resBorraFamilia = $dbh->prepare($sqlBorraFamilia);
					if($resBorraFamilia->execute(array(':nroafiliado' => $rowLeeFamilia['nroafiliado'], ':nroorden' => $rowLeeFamilia['nroorden']))) {
					}
				}
			}
			$sqlBorraTitular = "DELETE FROM titulares WHERE nroafiliado = :nroafiliado";
			$resBorraTitular = $dbh->prepare($sqlBorraTitular);
			if($resBorraTitular->execute(array(':nroafiliado' => $rowLeeTitular['nroafiliado']))) {
			}
		}
	}
	
	$dbh->exec($sqlDesactivarEmpresa);
	$dbh->exec($sqlDeleteEmpresa);
	$dbh->commit();
	$pagina = "empresaBaja.php?cuit=$cuit&origen=$origen";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>