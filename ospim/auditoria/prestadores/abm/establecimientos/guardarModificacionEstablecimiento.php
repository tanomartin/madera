<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$codigopresta = $_GET['codigopresta'];
//var_dump($_POST);echo "<br>";

$codigo = $_POST['codigo'];
$nombre = $_POST['nombre'];
$domicilio = strtoupper($_POST['domicilio']);
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
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$sqlUpdateEsta = "UPDATE establecimientos 
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
fehamodificacion = '$fechamodificacion', 
usuariomodificacion = '$usuariomodificacion'
WHERE codigo = $codigo and codigoprestador = $codigopresta";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlUpdateEsta."<br>");
	$dbh->exec($sqlUpdateEsta);

	$dbh->commit();
	$pagina = "establecimiento.php?codigo=$codigo&codigopresta=$codigopresta";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>
