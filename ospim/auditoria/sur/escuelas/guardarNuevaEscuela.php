<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$nombre = addslashes($_POST['nombre']);
$cue = $_POST['cue'];

$domicilio = strtoupper(addslashes($_POST['domicilio']));
if ($domicilio == '') {
	$domicilio = "NULL";
} else {
	$domicilio = "'$domicilio'";
}
$indpostal = $_POST['indpostal'];
if ($indpostal == '') {
	$indpostal = "NULL";
} else {
	$indpostal = "'$indpostal'";
}
$codPos = $_POST['codPos'];
if ($codPos == '') {
	$codPos = "NULL";
} else {
	$codPos = "'$codPos'";
}
$alfapostal = $_POST['alfapostal'];
if ($alfapostal == '') {
	$alfapostal = "NULL";
} else {
	$alfapostal = "'$alfapostal'";
}

$localidad = $_POST['selectLocali'];
$codProvin = $_POST['codprovin'];
if ($codProvin == "") {
	$codProvin = 0;
}

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

$sqlInsertEscuela = "INSERT INTO escuelas VALUES(DEFAULT,'$nombre','$cue',$codProvin,$indpostal,$codPos,$alfapostal,$localidad,$domicilio,$email,$tel,'$fecharegistro','$usuarioregistro',NULL,NULL)";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertEscuela."<br>");
	$dbh->exec($sqlInsertEscuela);
	$codigoNextEscuela = $dbh->lastInsertId();
	
	$dbh->commit();
	$pagina = "escuela.php?id=$codigoNextEscuela";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>