<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");

var_dump($_POST);

$nombre = $_POST['nombre'];
$domicilio = $_POST['domicilio'];
$indpostal = $_POST['indpostal'];
$codPos = $_POST['codPos'];
$alfapostal = $_POST['alfapostal'];
$localidad = $_POST['selectLocali'];
$codProvin = $_POST['codprovin'];
$ddn1 = $_POST['ddn1'];
$tel1 = $_POST['telefono1'];
$ddn2 = $_POST['ddn2'];
$tel2 = $_POST['telefono2'];
$ddnfax = $_POST['ddnfax'];
$telfax = $_POST['telefonofax'];
$email = $_POST['email'];
$cuit = $_POST['cuit'];
$personeria = $_POST['selectPersoneria'];
$tratamiento = $_POST['selectTratamiento'];
$matriculaNac = $_POST['matriculaNac'];
$matriculaPro = $_POST['matriculaPro'];
$nroRegistro = $_POST['nroRegistro'];
$capitado = $_POST['capitado'];
$nomenclador = $_POST['selectNomenclador'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlInsertPresta = "INSERT INTO prestadores VALUES(DEFAULT,'$nombre','$domicilio','$localidad','$codProvin','$indpostal','$codPos','$alfapostal','$tel1','$ddn1','$tel2','$ddn2','$telfax','$ddnfax','$email','$cuit','$personeria','$tratamiento','$matriculaNac','$matriculaPro','$nroRegistro','$capitado','$nomenclador','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	print($sqlInsertPresta."<br>");
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
		if (strpos($key ,'delegacion') !== false) {
			$delegacion = $_POST[$key];
			$sqlInsertJurisdiccion = "INSERT INTO prestadorjurisdiccion VALUE($codigoNextPresta, $delegacion)";
			print($sqlInsertJurisdiccion."<br>");
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
