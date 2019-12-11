<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$codigo = $_POST['codigo'];
$cuit = $_POST['cuit'];
$nombre = addslashes($_POST['nombre']);
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
$tel1 = $_POST['telefono1'];
if ($tel1 == "") {
	$tel1 = "NULL";
} else {
	$tel1 = "'$tel1'";
}
$telfax = $_POST['telfax'];
if ($telfax == "") {
	$telfax = "NULL";
} else {
	$telfax = "'$telfax'";
}
$email = $_POST['email'];
if ($email == "") {
	$email = "NULL";
} else {
	$email = "'$email'";
}
$email1 = $_POST['email1'];
if ($email1 == "") {
	$email1 = "NULL";
} else {
	$email1 = "'$email1'";
}
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$sqlUpdatePresta = "UPDATE prestadores
SET 
nombre = '$nombre', 
cuit = '$cuit',
domicilio = '$domicilio',
codlocali = '$localidad',
codprovin = '$codProvin',
indpostal = '$indpostal', 
numpostal = '$codPos', 
alfapostal = $alfapostal, 
telefono1 = $tel, 
telefono2 = $tel1,
telefonofax = $telfax, 
email1 = $email,
email2 = $email1, 
fehamodificacion = '$fechamodificacion', 
usuariomodificacion = '$usuariomodificacion'
WHERE codigoprestador = $codigo";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlUpdatePresta."<br>");
	$dbh->exec($sqlUpdatePresta);

	$dbh->commit();
	$pagina = "prestador.php?codigo=$codigo";
	Header("Location: $pagina"); 
	
} catch (PDOException $e) {
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>
