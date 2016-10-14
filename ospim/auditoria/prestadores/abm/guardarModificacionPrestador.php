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
email1 = '$email1', 
email2 = '$email2', 
cuit = '$cuit', 
personeria = '$personeria', 
tratamiento = '$tratamiento', 
matriculanacional = '$matriculaNac' ,
matriculaprovincial = '$matriculaPro', 
numeroregistrosss = '$nroRegistroSSS', 
vtoregistrosss = '$vtoRegistroSSS',
numeroregistrosnr = '$nroRegistroSNR', 
vtoregistrosnr = '$vtoRegistroSNR',
capitado = '$capitado', 
fehamodificacion = '$fechamodificacion', 
usuariomodificacion = '$usuariomodificacion'
WHERE codigoprestador = $codigo";

$sqlDeleteNomenclador = "DELETE FROM prestadornomenclador WHERE codigoprestador = $codigo";
$sqlDeleteJurisdiccion = "DELETE FROM prestadorjurisdiccion WHERE codigoprestador = $codigo";
$sqlDeleteServicio = "DELETE FROM prestadorservicio WHERE codigoprestador = $codigo";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlDeleteNomenclador."<br>");
	$dbh->exec($sqlDeleteNomenclador);
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
		if (strpos($key ,'nomenclador') !== false) {
			$nomenclador = $_POST[$key];
			$sqlInsertNomenclador = "INSERT INTO prestadornomenclador VALUE($codigo, $nomenclador)";
			//print($sqlInsertNomenclador."<br>");
			$dbh->exec($sqlInsertNomenclador);
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
