<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$nombre = addslashes($_POST['nombre']);
$cuit = $_POST['cuit'];
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
$email2 = $_POST['email2'];
if ($email2 == "") {
	$email2 = "NULL";
} else {
	$email2 = "'$email2'";
}

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlInsertPresta = "INSERT INTO prestadores VALUES(DEFAULT,'$nombre','$domicilio','$localidad',0 ,'$codProvin','$indpostal','$codPos',$alfapostal,NULL , $tel ,NULL ,$tel1, NULL, $telfax, $email, $email2, $cuit, 0, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0,'','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";

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
	$pagina = "prestador.php?codigo=$codigoNextPresta";
	Header("Location: $pagina"); 
	
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>
