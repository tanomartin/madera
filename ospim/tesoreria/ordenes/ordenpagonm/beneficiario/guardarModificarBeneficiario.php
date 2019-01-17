<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

//var_dump($_POST);

$codigo = $_POST['codigo'];
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
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$sqlUpdatePresta = "UPDATE prestadoresnm 
SET 
nombre = '$nombre', 
dirigidoa = '$dirigidoa',
domicilio = '$domicilio',
codlocali = '$localidad',
codprovin = '$codProvin',
indpostal = '$indpostal', 
numpostal = '$codPos', 
alfapostal = $alfapostal, 
telefono = $tel, 
email = $email, 
fehamodificacion = '$fechamodificacion', 
usuariomodificacion = '$usuariomodificacion'
WHERE codigo = $codigo";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlUpdatePresta."<br>");
	$dbh->exec($sqlUpdatePresta);

	$dbh->commit();
	$pagina = "beneficiario.php?codigo=$codigo";
	Header("Location: $pagina"); 
	
} catch (PDOException $e) {
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>
