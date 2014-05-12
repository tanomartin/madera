<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$cuit = $_POST['cuit'];
$nroorden = $_POST['nroorden'];
$sqlCabecera = $_POST['insertCabeceraJui'];
$listadoPeriodosSerializado = $_POST['insertPeriodosJui'];
$sqlUpdateAcu = $_POST['updateCabeceraAcu'];
$listadoPeriodosAcuSerializado = $_POST['deletePeriodosAcu'];

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlPeriodos = unserialize(urldecode($listadoPeriodosSerializado));
$sqlDelPer = unserialize(urldecode($listadoPeriodosAcuSerializado));


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
$sqlTramite = "INSERT INTO trajuiciosospim VALUE($nroorden,'$fechainicio','$autocaso',$juzgado,$secretaria,'$expediente','$bienes',$estado,'$fechafin',$monto,'$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	//print($sqlCabecera."<br>");
	$dbh->exec($sqlCabecera);
	if (!empty($sqlPeriodos)) {
		for($i=0; $i<sizeof($sqlPeriodos); $i++) {
			//print($sqlPeriodos[$i]."<br>");
			$dbh->exec($sqlPeriodos[$i]);
		}
	}
	
	//print($sqlTramite."<br>");
	$dbh->exec($sqlTramite);
	
	if (!empty($sqlUpdateAcu)) {
		//print($sqlUpdateAcu."<br>");
		$dbh->exec($sqlUpdateAcu);
		if (!empty($sqlDelPer)) {
			for($i=0; $i<sizeof($sqlDelPer); $i++) {
				//print($sqlDelPer[$i]."<br>");
				$dbh->exec($sqlDelPer[$i]);
			}
		}
	}
	
	$dbh->commit();
	$pagina = "consultaJuicio.php?cuit=$cuit&nroorden=$nroorden";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}


?>