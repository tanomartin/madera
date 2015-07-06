<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$codigopresta = $_GET['codigopresta'];

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
$tratamiento = $_POST['selectTratamiento'];
$matriculaNac = $_POST['matriculaNac'];
$matriculaPro = $_POST['matriculaPro'];
$nroRegistro = $_POST['nroRegistro'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlInsertProf = "INSERT INTO profesionales VALUES(DEFAULT,'$codigopresta','$nombre','$domicilio','$localidad','$codProvin','$indpostal','$codPos','$alfapostal','$tel1','$ddn1','$tel2','$ddn2','$telfax','$ddnfax','$email','$cuit','$tratamiento','$matriculaNac','$matriculaPro','$nroRegistro',DEFAULT,'$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertProf."<br>");
	$dbh->exec($sqlInsertProf);
	$codigoNextPresta = $dbh->lastInsertId();
	
	$dbh->commit();
	$pagina = "profesional.php?codigoprof=$codigoNextPresta&codigopresta=$codigopresta";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>
