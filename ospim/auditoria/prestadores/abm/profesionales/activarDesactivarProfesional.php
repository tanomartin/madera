<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigoprof = $_GET['codigoprof'];
$codigopresta = $_GET['codigopresta'];
$accion = $_GET['accion'];
//var_dump($_POST);
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$sqlActivarProf = "UPDATE profesionales SET activo = '$accion', fehamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion'
WHERE codigoprofesional = $codigoprof and codigoprestador = $codigopresta";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlActivarProf."<br>");
	$dbh->exec($sqlActivarProf);

	$dbh->commit();
	$pagina = "profesionalesPrestador.php?codigo=$codigopresta";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}


?>
