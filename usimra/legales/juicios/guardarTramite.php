<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$cuit = $_POST['cuit'];
$nroorden = $_POST['nroorden'];
$fechainicio = fechaParaGuardar($_POST['fechaInicio']);
$autocaso = $_POST['autocaso'];
$juzgado =  $_POST['juzgado'];
$secretaria = $_POST['secretaria'];
$expediente = $_POST['nroexpe'];
$bienes = $_POST['bienes'];
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

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlTramite = "INSERT INTO trajuiciosusimra VALUE($nroorden,'$fechainicio','$autocaso',$juzgado,$secretaria,'$expediente','$bienes',$estado,'$fechafin',$monto,'$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";
$updateCabe = "UPDATE cabjuiciosusimra SET tramitejudicial = 1, fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE nroorden = $nroorden and cuit = $cuit";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$dbh->exec($updateCabe);
	//print($updateCabe."<br>");
	$dbh->exec($sqlTramite);
	//print($sqlTramite."<br>");
	
	$dbh->commit();
	$pagina = "consultaJuicio.php?cuit=$cuit&nroorden=$nroorden";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}


?>