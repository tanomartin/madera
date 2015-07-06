<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

//var_dump($_POST);

$codigo = $_POST['codigo'];
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
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$sqlUpdatePresta = "UPDATE prestadores 
SET 
nombre = '$nombre', 
domicilio = '$domicilio',
codlocali = '$localidad', 
codprovin = '$codProvin',
indpostal = '$indpostal', 
numpostal = '$codPos', 
alfapostal = '$alfapostal', 
telefono1 = '$tel1', 
ddn1 = '$ddn1', 
telefono2 = '$tel2', 
ddn2 = '$ddn2', 
telefonofax = '$telfax', 
ddnfax = '$ddnfax', 
email = '$email', 
cuit = '$cuit', 
personeria = '$personeria', 
tratamiento = '$tratamiento', 
matriculanacional = '$matriculaNac' ,
matriculaprovincial = '$matriculaPro', 
numeroregistrosss = '$nroRegistro', 
capitado = '$capitado', 
nomenclador = '$nomenclador', 
fehamodificacion = '$fechamodificacion', 
usuariomodificacion = '$usuariomodificacion'
WHERE codigoprestador = $codigo";

$sqlDeleteJurisdiccion = "DELETE FROM prestadorjurisdiccion WHERE codigoprestador = $codigo";
$sqlDeleteServicio = "DELETE FROM prestadorservicio WHERE codigoprestador = $codigo";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlUpdatePresta."<br>");
	$dbh->exec($sqlUpdatePresta);
	//print($sqlDeleteJurisdiccion."<br>");
	$dbh->exec($sqlDeleteJurisdiccion);
	//print($sqlDeleteServicio."<br>");
	$dbh->exec($sqlDeleteServicio);
	
	foreach($_POST as $key => $value) {
		if (strpos($key ,'servicio') !== false) {
			$servicio = $_POST[$key];
			$sqlInsertServicio = "INSERT INTO prestadorservicio VALUE($codigo, $servicio)";
			//print($sqlInsertServicio."<br>");
			$dbh->exec($sqlInsertServicio);
		}
	}
	
	foreach($_POST as $key => $value) {
		if (strpos($key ,'delegacion') !== false) {
			$delegacion = $_POST[$key];
			$sqlInsertJurisdiccion = "INSERT INTO prestadorjurisdiccion VALUE($codigo, $delegacion)";
			//print($sqlInsertJurisdiccion."<br>");
			$dbh->exec($sqlInsertJurisdiccion);
		}
	}

	$dbh->commit();
	$pagina = "prestador.php?codigo=$codigo";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>
