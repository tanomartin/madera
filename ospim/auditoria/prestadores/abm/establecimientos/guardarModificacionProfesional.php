<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$codigopresta = $_GET['codigopresta'];
//var_dump($_POST);

$codigoprof = $_POST['codigo'];
$nombre = $_POST['nombre'];
$domicilio = $_POST['domicilio'];
$indpostal = $_POST['indpostal'];
$codPos = $_POST['codPos'];
$alfapostal = $_POST['alfapostal'];
if ($codPos == '') {
	$codPos = 'null';
}
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
$tratamiento = $_POST['selectTratamiento'];
$matriculaNac = $_POST['matriculaNac'];
$matriculaPro = $_POST['matriculaPro'];
$nroRegistro = $_POST['nroRegistro'];
$activo = $_POST['activo'];
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$sqlUpdateProf = "UPDATE profesionales 
SET 
nombre = '$nombre', 
domicilio = '$domicilio',
codlocali = '$localidad', 
codprovin = '$codProvin',
indpostal = '$indpostal', 
numpostal = $codPos, 
alfapostal = '$alfapostal', 
telefono1 = '$tel1', 
ddn1 = '$ddn1', 
telefono2 = '$tel2', 
ddn2 = '$ddn2', 
telefonofax = '$telfax', 
ddnfax = '$ddnfax', 
email = '$email', 
cuit = '$cuit', 
tratamiento = '$tratamiento',
matriculanacional = '$matriculaNac' ,
matriculaprovincial = '$matriculaPro', 
numeroregistrosss = '$nroRegistro',
activo = '$activo', 
fehamodificacion = '$fechamodificacion', 
usuariomodificacion = '$usuariomodificacion'
WHERE codigoprofesional = $codigoprof and codigoprestador = $codigopresta";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlUpdateProf."<br>");
	$dbh->exec($sqlUpdateProf);

	$dbh->commit();
	$pagina = "profesional.php?codigoprof=$codigoprof&codigopresta=$codigopresta";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>
