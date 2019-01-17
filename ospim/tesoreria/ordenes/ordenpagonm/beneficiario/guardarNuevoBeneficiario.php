<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$nombre = addslashes($_POST['nombre']);
$dirigidoa = addslashes($_POST['dirigidoa']);
$domicilio = strtoupper(addslashes($_POST['domicilio']));
$indpostal = $_POST['indpostal'];
$codPos = $_POST['codPos'];

$alfapostal = $_POST['alfapostal'];
if ($alfapostal == "") {
	$alfapostal = "NULL";
} else {
	$alfapostal = "'$alfapostal'";
}
$localidad = $_POST['selectLocali'];
$codProvin = $_POST['codprovin'];
$tel = $_POST['telefono'];
if ($tel == "") {
	$tel = "NULL";
} else {
	$tel = "'$tel'";
}
$email = $_POST['email'];
if ($email == "") {
	$email = "NULL";
} else {
	$email = "'$email'";
}
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlInsertPresta = "INSERT INTO prestadoresnm VALUES(DEFAULT,'$nombre','$dirigidoa','$domicilio','$localidad','$codProvin','$indpostal','$codPos',$alfapostal,$tel,$email,'$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertPresta."<br>");
	$dbh->exec($sqlInsertPresta);
	$codigoNextPresta = $dbh->lastInsertId(); 
	
	$dbh->commit();
	$pagina = "beneficiario.php?codigo=$codigoNextPresta";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>
