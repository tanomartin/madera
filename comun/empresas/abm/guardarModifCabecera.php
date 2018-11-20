<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 
include($libPath."fechas.php"); 

$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion  = $_SESSION['usuario'];

$datos = array_values($_POST);

$cuit = $datos[0];
$nombre = $datos[1];
$nombre = addslashes(strtoupper($nombre));
$domicilio = $datos[2];
$domicilio = addslashes(strtoupper($domicilio));
$indpostal = $datos[3];
$codpostal = $datos[4];
$alfapostal = $datos[5];
$alfapostal = strtoupper($alfapostal);
$localidad = $datos[6];
$provincia = $datos[7];
$codprovin = $datos[8];
$ddn1 = $datos[9];
$telefono1 = $datos[10];
$contacto1 = $datos[11];
$ddn2 = $datos[12];
$telefono2 = $datos[13];
$contacto2 = $datos[14];

$codigotipo = 0;
$peretenencia = 3;

$actividad = $datos[15];
$obsOspim = $datos[16];
$obsUsimra = $datos[17]; 
$inicioOspim = $datos[18];
$inicioOspim = fechaParaGuardar($inicioOspim); 
$inicioUsimra = $datos[19];
$inicioUsimra = fechaParaGuardar($inicioUsimra); 
$email = $datos[20];
$carpetaArchivo = $datos[21];

$sqlUpdateCabecera = "UPDATE empresas set nombre = '$nombre', codprovin = '$codprovin', indpostal = '$indpostal', numpostal = '$codpostal', alfapostal = '$alfapostal', codlocali = '$localidad', domilegal = '$domicilio', ddn1 = '$ddn1', telefono1 = '$telefono1', contactel1 = '$contacto1', ddn2 = '$ddn2', telefono2 = '$telefono2', contactel2 = '$contacto2', codigotipo = '$codigotipo', codpertene = '$peretenencia', actividad = '$actividad', obsospim = '$obsOspim', obsusimra = '$obsUsimra', iniobliosp = '$inicioOspim', iniobliusi = '$inicioUsimra', email = '$email ', carpetaenarchivo = '$carpetaArchivo', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where cuit = $cuit";

//print($sqlUpdateCabecera);

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlUpdateCabecera);
	$dbh->commit();
	$pagina = "empresa.php?cuit=$cuit&origen=$origen";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	$error = $e->getTraceAsString();
	if ($e->getCode() == 42000 && strpos($error, 'UPDATE')) {
		$sqlUpdateCabecera = "UPDATE empresas set carpetaenarchivo = '$carpetaArchivo', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where cuit = $cuit";
		try {
			$dbh->exec($sqlUpdateCabecera);
			$dbh->commit();
			$pagina = "empresa.php?cuit=$cuit&origen=$origen";
			Header("Location: $pagina"); 
		} catch (PDOException $e) {
			$error = $e->getMessage();
			$dbh->rollback();
			$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/".$origen."/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
			header ($redire);
			exit(0);
		} 
	} else {
		$error = $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/".$origen."/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
}

?>