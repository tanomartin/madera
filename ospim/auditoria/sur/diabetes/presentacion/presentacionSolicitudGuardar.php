<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$id = $_POST['id'];
$fechamodif = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];
$fecha = fechaParaGuardar($_POST['fecha']);
$solicitud = strtoupper(trim($_POST['solicitud']));
$cantidad =  $_POST['cantidad'];
$periodo = $_POST['periodo'];
$obs = $_POST['obs'];
if ($obs == "") {
	$obs = 'NULL';
} else {
	$obs = "'".strtoupper(trim($_POST['obs']))."'";
}

if ($_FILES['nota']['tmp_name'] != "") {
	$archivoNota = $_FILES['nota']['tmp_name'];
	try {
		$maquina = $_SERVER['SERVER_NAME'];
		if(strcmp("localhost",$maquina) == 0)
			$archivodestino="archivos/DIAB-$periodo-$solicitud.pdf";
		else
			$archivodestino="/home/sistemas/Documentos/Diabetes/DIAB-$periodo-$solicitud.pdf";
		copy($archivoNota, $archivodestino);
	} catch (Exception $e) {
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		Header($redire);
		exit -1;
	}
}

$presentacionSSS = "UPDATE diabetespresentacion 
						SET fechasolicitud = '$fecha', nrosolicitud = '$solicitud',  observacion = $obs,
							cantbenesolicitados = $cantidad, pathSolicitud = '$archivodestino',
							fechamodificacion = '$fechamodif', usuariomodificacion = '$usuariomodif'
						WHERE id = $id";


$sqlIdFinalizadaAnterior = "SELECT id FROM diabetespresentacion 
								WHERE fechasolicitud is not null and 
									  fechacancelacion is null 
								ORDER by id DESC limit 1";
//echo $sqlIdFinalizadaAnterior."<br>";
$resIdFinalizadaAnterior = mysql_query($sqlIdFinalizadaAnterior,$db);
$rowIdFinalizadaAnterior = mysql_fetch_assoc($resIdFinalizadaAnterior);
$idAnterior = $rowIdFinalizadaAnterior['id'];

$sqlDetalleAnterior = "SELECT * FROM diabetespresentaciondetalle WHERE 
						idpresentacion = $idAnterior and cuil not in (SELECT cuil FROM diabetespresentaciondetalle WHERE idpresentacion = $id)";
//echo $sqlDetalleAnterior."<br>";
$resDetalleAnterior = mysql_query($sqlDetalleAnterior,$db);
$sqlInsertDetalle = "INSERT INTO diabetespresentaciondetalle VALUES";
while ($rowDetalleAnterior = mysql_fetch_assoc($resDetalleAnterior)) {
	$sqlInsertDetalle .= "(".$id.", ".
						 $rowDetalleAnterior['cuil'].", ".
						 $rowDetalleAnterior['nroafiliado'].", ".
						 $rowDetalleAnterior['nroorden'].", ".
						 $rowDetalleAnterior['codidelega']."),";
}
$sqlInsertDetalle = substr($sqlInsertDetalle, 0, -1);
$sqlInsertDetalle .= ";";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $presentacionSSS."<br><br>";
	$dbh->exec($presentacionSSS);
	//echo $sqlInsertDetalle."<br><br>";
	$dbh->exec($sqlInsertDetalle);

	$dbh->commit();
	$redire = "moduloPresSSS.php";
	Header("Location: $redire");
	
} catch (PDOException $e) {
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>