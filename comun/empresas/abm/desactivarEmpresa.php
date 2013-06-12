<?php include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/fechas.php"); 

$datos = array_values($_POST);
$motivobaja = $datos[0];
$motivobajaAfiliado = "Baja de empresa";
$fechabaja = fechaParaGuardar($datos[1]);
$fechaefectivizacion = date("Y-m-d H:m:s");
$usuarioefectivizacion = $_SESSION['usuario'];
$informesss = 1;
$tipoinformesss = "B";
$mirroring = "S";

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
	
	
	$sqlTitulares = "SELECT * FROM titulares where cuitempresa = $cuit";
	$resTitulares = mysql_query($sqlTitulares,$db); 
	while($rowTitulares = mysql_fetch_array($resTitulares)) {
		$nroafiliado = $rowTitulares['nroafiliado'];
		$sqlFamiliares = "SELECT * FROM familiares where nroafiliado = $nroafiliado";
		$resFamiliares = mysql_query($sqlFamiliares,$db); 
		while($rowFamiliares = mysql_fetch_array($resFamiliares)) {
			$sqlBajaFamilia = "INSERT INTO familiaresdebaja (nroafiliado, nroorden, tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, ddn, telefono, email, fechaobrasocial, discapacidad, certificadodiscapacidad, estudia, certificadoestudio, cuil, emitecarnet, cantidadcarnet, fechacarnet, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring, fechabaja, motivobaja, fechaefectivizacion, usuarioefectivizacion) VALUES (:nroafiliado, :nroorden, :tipoparentesco, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :ddn, :telefono, :email, :fechaobrasocial, :discapacidad, :certificadodiscapacidad, :estudia, :certificadoestudio, :cuil, :emitecarnet, :cantidadcarnet, :fechacarnet, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring, :fechabaja, :motivobaja, :fechaefectivizacion, :usuarioefectivizacion)";
			$resBajaFamilia = $dbh->prepare($sqlBajaFamilia);
			if($resBajaFamilia->execute(array(':nroafiliado' => $rowFamiliares['nroafiliado'], ':nroorden' => $rowFamiliares['nroorden'], ':tipoparentesco' => $rowFamiliares['tipoparentesco'], ':apellidoynombre' => $rowFamiliares['apellidoynombre'], ':tipodocumento' => $rowFamiliares['tipodocumento'], ':nrodocumento' => $rowFamiliares['nrodocumento'], ':fechanacimiento' => $rowFamiliares['fechanacimiento'], ':nacionalidad' => $rowFamiliares['nacionalidad'], ':sexo' => $rowFamiliares['sexo'], ':ddn' => $rowFamiliares['ddn'], ':telefono' => $rowFamiliares['telefono'], ':email' => $rowFamiliares['email'], ':fechaobrasocial' => $rowFamiliares['fechaobrasocial'], ':discapacidad' => $rowFamiliares['discapacidad'], ':certificadodiscapacidad' => $rowFamiliares['certificadodiscapacidad'], ':estudia' => $rowFamiliares['estudia'], ':certificadoestudio' => $rowFamiliares['certificadoestudio'], ':cuil' => $rowFamiliares['cuil'], ':emitecarnet' => $rowFamiliares['emitecarnet'], ':cantidadcarnet' => $rowFamiliares['cantidadcarnet'], ':fechacarnet' => $rowFamiliares['fechacarnet'], ':tipocarnet' => $rowFamiliares['tipocarnet'], ':vencimientocarnet' => $rowFamiliares['vencimientocarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $rowFamiliares['fechainformesss'], ':usuarioinformesss' => $rowFamiliares['usuarioinformesss'], ':foto' => $rowFamiliares['foto'], ':fecharegistro' => $rowFamiliares['fecharegistro'], ':usuarioregistro' => $rowFamiliares['usuarioregistro'], ':fechamodificacion' => $rowFamiliares['fechamodificacion'], ':usuariomodificacion' => $rowFamiliares['usuariomodificacion'], ':mirroring' => $mirroring, ':fechabaja' => $fechabaja, ':motivobaja' => $motivobajaAfiliado, ':fechaefectivizacion' => $fechaefectivizacion, ':usuarioefectivizacion' => $usuarioefectivizacion)));
		}
		$sqlDeleteFamiliar = "DELETE from familiares where nroafiliado = $nroafiliado";
		//print($sqlDeleteFamiliar);print("<br>");print("<br>");
		$dbh->exec($sqlDeleteFamiliar);
		
		$sqlBajaTitular = "INSERT INTO titularesdebaja (nroafiliado, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento, nacionalidad, sexo, estadocivil, codprovin, indpostal, numpostal, alfapostal, codlocali, domicilio, ddn, telefono, email, fechaobrasocial, tipoafiliado, solicitudopcion, situaciontitularidad, discapacidad, certificadodiscapacidad, cuil, cuitempresa, fechaempresa, codidelega, categoria, emitecarnet, cantidadcarnet, fechacarnet, tipocarnet, vencimientocarnet, informesss, tipoinformesss, fechainformesss, usuarioinformesss, foto, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion, mirroring, fechabaja, motivobaja, fechaefectivizacion, usuarioefectivizacion) VALUES (:nroafiliado, :apellidoynombre, :tipodocumento, :nrodocumento, :fechanacimiento, :nacionalidad, :sexo, :estadocivil, :codprovin, :indpostal, :numpostal, :alfapostal, :codlocali, :domicilio, :ddn, :telefono, :email, :fechaobrasocial, :tipoafiliado, :solicitudopcion, :situaciontitularidad, :discapacidad, :certificadodiscapacidad, :cuil, :cuitempresa, :fechaempresa, :codidelega, :categoria, :emitecarnet, :cantidadcarnet, :fechacarnet, :tipocarnet, :vencimientocarnet, :informesss, :tipoinformesss, :fechainformesss, :usuarioinformesss, :foto, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion, :mirroring, :fechabaja, :motivobaja, :fechaefectivizacion, :usuarioefectivizacion)";
		$resBajaTitular = $dbh->prepare($sqlBajaTitular);
		if($resBajaTitular->execute(array(':nroafiliado' => $rowTitulares['nroafiliado'], ':apellidoynombre' => $rowTitulares['apellidoynombre'], ':tipodocumento' => $rowTitulares['tipodocumento'], ':nrodocumento' => $rowTitulares['nrodocumento'], ':fechanacimiento' => $rowTitulares['fechanacimiento'], ':nacionalidad' => $rowTitulares['nacionalidad'], ':sexo' => $rowTitulares['sexo'], ':estadocivil' => $rowTitulares['estadocivil'], ':codprovin' => $rowTitulares['codprovin'], ':indpostal' => $rowTitulares['indpostal'], ':numpostal' => $rowTitulares['numpostal'], ':alfapostal' => $rowTitulares['alfapostal'], ':codlocali' => $rowTitulares['codlocali'], ':domicilio' => $rowTitulares['domicilio'], ':ddn' => $rowTitulares['ddn'], ':telefono' => $rowTitulares['telefono'], ':email' => $rowTitulares['email'], ':fechaobrasocial' => $rowTitulares['fechaobrasocial'], ':tipoafiliado' => $rowTitulares['tipoafiliado'], ':solicitudopcion' => $rowTitulares['solicitudopcion'], ':situaciontitularidad' => $rowTitulares['situaciontitularidad'], ':discapacidad' => $rowTitulares['discapacidad'], ':certificadodiscapacidad' => $rowTitulares['certificadodiscapacidad'], ':cuil' => $rowTitulares['cuil'], ':cuitempresa' => $rowTitulares['cuitempresa'], ':fechaempresa' => $rowTitulares['fechaempresa'], ':codidelega' => $rowTitulares['codidelega'], ':categoria' => $rowTitulares['categoria'], ':emitecarnet' => $rowTitulares['emitecarnet'], ':cantidadcarnet' => $rowTitulares['cantidadcarnet'], ':fechacarnet' => $rowTitulares['fechacarnet'], ':tipocarnet' => $rowTitulares['tipocarnet'], ':vencimientocarnet' => $rowTitulares['vencimientocarnet'], ':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechainformesss' => $rowTitulares['fechainformesss'], ':usuarioinformesss' => $rowTitulares['usuarioinformesss'], ':foto' => $rowTitulares['foto'], ':fecharegistro' => $rowTitulares['fecharegistro'], ':usuarioregistro' => $rowTitulares['usuarioregistro'], ':fechamodificacion' => $rowTitulares['fechamodificacion'], ':usuariomodificacion' => $rowTitulares['usuariomodificacion'], ':mirroring' => $mirroring, ':fechabaja' => $fechabaja, ':motivobaja' => $motivobajaAfiliado, ':fechaefectivizacion' => $fechaefectivizacion, ':usuarioefectivizacion' => $usuarioefectivizacion)))
		
		$sqlDeleteTitular = "DELETE from titulares where nroafiliado = $nroafiliado";
		//print($sqlDeleteTitular);print("<br>");
		$dbh->exec($sqlDeleteTitular);	
	}
	
	$dbh->exec($sqlDesactivarEmpresa);
	$dbh->exec($sqlDeleteEmpresa);
	$dbh->commit();
	$pagina = "empresaBaja.php?cuit=$cuit&origen=$origen&reactiva=1";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>