<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$cuit = $_GET['cuit'];
$nroorden = $_POST['nroorden'];
$fechainicio = fechaParaGuardar($_POST['fechaInicio']);
$autocaso = $_POST['autocaso'];
$juzgado =  $_POST['juzgado'];
$secretaria = $_POST['secretaria'];
$expediente = $_POST['nroexpe'];
$bienes = $_POST['bienes'];
$observacion = $_POST['observacion'];
$estado = $_POST['estado'];
if (!empty($_POST['fechafinal'])) {
	$fechafin = fechaParaGuardar($_POST['fechafinal']);
} else {
	$fechafin = "";
}

if (!empty($_POST['montocobrado'])) {
	$monto = number_format($_POST['montocobrado'],2,'.','');
} else {
	$monto = 0;
}	
$fechamodificacion = date("Y-m-d H:m:s");
$usuariomodificacion =  $_SESSION['usuario'];

$sqlUpdateTramite = "UPDATE trajuiciosospim SET fechainicio = '$fechainicio', autoscaso = '$autocaso', codigojuzgado = $juzgado, codigosecretaria = $secretaria, nroexpediente = '$expediente', bienesembargados = '$bienes', observacion = '$observacion',estadoprocesal = $estado, fechafinalizacion = '$fechafin', montocobrado = $monto, fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE nroorden = $nroorden";
$resUpdateTramite = mysql_query($sqlUpdateTramite,$db); 

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$dbh->exec($sqlUpdateTramite);

	$dbh->commit();
	
	$pagina = "consultaJuicio.php?nroorden=$nroorden&cuit=$cuit";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>