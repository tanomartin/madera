<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$nombre = $_POST['nombre'];
$domicilio = strtoupper($_POST['domicilio']);
$indpostal = $_POST['indpostal'];
$codPos = $_POST['codPos'];
$alfapostal = $_POST['alfapostal'];
$localidad = $_POST['selectLocali'];
$codProvin = $_POST['codprovin'];
$idBarrio = $_POST['selectBarrio'];
$ddn1 = $_POST['ddn1'];
$tel1 = $_POST['telefono1'];
$ddn2 = $_POST['ddn2'];
$tel2 = $_POST['telefono2'];
$ddnfax = $_POST['ddnfax'];
$telfax = $_POST['telefonofax'];
$email1 = $_POST['email1'];
$email2 = $_POST['email2'];
$cuit = $_POST['cuit'];
$personeria = $_POST['selectPersoneria'];
$tratamiento = $_POST['selectTratamiento'];
$matriculaNac = $_POST['matriculaNac'];
$matriculaPro = $_POST['matriculaPro'];
$nroRegistroSSS = $_POST['nroSSS'];
$vtoRegistroSSS = fechaParaGuardar($_POST['vtoSSS']);
if ($vtoRegistroSSS == "0000-00-00") { $vtoRegistroSSS = NULL; }
$nroRegistroSNR = $_POST['nroSNR'];
$vtoRegistroSNR = fechaParaGuardar($_POST['vtoSNR']);
if ($vtoRegistroSNR == "0000-00-00") { $vtoRegistroSNR = NULL; }
$capitado = $_POST['capitado'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlInsertPresta = "INSERT INTO prestadores VALUES(DEFAULT,'$nombre','$domicilio','$localidad','$idBarrio','$codProvin','$indpostal','$codPos','$alfapostal','$tel1','$ddn1','$tel2','$ddn2','$telfax','$ddnfax','$email1','$email2','$cuit','$personeria','$tratamiento','$matriculaNac','$matriculaPro','$nroRegistroSSS','$vtoRegistroSSS','$nroRegistroSNR','$vtoRegistroSNR','$capitado','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertPresta."<br>");
	$dbh->exec($sqlInsertPresta);
	$codigoNextPresta = $dbh->lastInsertId(); 
	
	foreach($_POST as $key => $value) {
		if (strpos($key ,'servicio') !== false) {
			$servicio = $_POST[$key];
			$sqlInsertServicio = "INSERT INTO prestadorservicio VALUE($codigoNextPresta, $servicio)";
			print($sqlInsertServicio."<br>");
			$dbh->exec($sqlInsertServicio);
		}
	}
	
	foreach($_POST as $key => $value) {
		if (strpos($key ,'nomenclador') !== false) {
			$nomenclador = $_POST[$key];
			$sqlInsertNomenclador = "INSERT INTO prestadornomenclador VALUE($codigoNextPresta, $nomenclador)";
			//print($sqlInsertNomenclador."<br>");
			$dbh->exec($sqlInsertNomenclador);
		}
	}
	
	foreach($_POST as $key => $value) {
		if (strpos($key ,'delegacion') !== false) {
			$delegacion = $_POST[$key];
			$sqlInsertJurisdiccion = "INSERT INTO prestadorjurisdiccion VALUE($codigoNextPresta, $delegacion)";
			//print($sqlInsertJurisdiccion."<br>");
			$dbh->exec($sqlInsertJurisdiccion);
		}
	}
	
	$dbh->commit();
	$pagina = "prestador.php?codigo=$codigoNextPresta";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>
