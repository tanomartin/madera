<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
$fechaExceptuar = explode("-",$_POST['fecha']);
$ano = $fechaExceptuar[2];
$mes = $fechaExceptuar[1];
$dia = $fechaExceptuar[0];
$origenExceptuar = $_POST['origen'];
$convenio = $_POST['selectConvenio'];
$motivo = $_POST['motivo'];
$fechaModif = date("Y-m-d H:i:s");
$usuarioModif = $_SESSION['usuario'];

if(strcmp("0000", $convenio)==0) {
	$sqlUpdateDia = "UPDATE diasbancousimra SET exceptuado = 1, observacion = '$motivo', fechamodificacion = '$fechaModif', usuariomodificacion = '$usuarioModif' WHERE nroconvenio IN('3617','5866','0XO0') AND ano = $ano AND mes = $mes AND dia = $dia AND procesado = 0";
} else {
	$sqlUpdateDia = "UPDATE diasbancousimra SET exceptuado = 1, observacion = '$motivo', fechamodificacion = '$fechaModif', usuariomodificacion = '$usuarioModif' WHERE nroconvenio = '$convenio' AND ano = $ano AND mes = $mes AND dia = $dia AND procesado = 0";
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	//print($sqlUpdateDia);
	$dbh->exec($sqlUpdateDia);
	$dbh->commit();
	if(strcmp("A", $origenExceptuar)==0) {
		$pagina = "../aportesacuerdos/archivos/procesamientoArchivosAportes.php";
	}
	if(strcmp("E", $origenExceptuar)==0) {
		$pagina = "../cuotaextraordinaria/archivos/procesamientoArchivosExtraordinarias.php";
	}
	if(strcmp("L", $origenExceptuar)==0) {
		$pagina = "../linkpagos/archivos/procesamientoArchivosLinkpagos.php";
	}
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}	

?>