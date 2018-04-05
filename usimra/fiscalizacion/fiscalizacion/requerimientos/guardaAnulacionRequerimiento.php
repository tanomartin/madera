<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
$fecha = $_GET['fecha'];
$motivo = $_POST['motivo'];

$requerimientos = $_POST['requerimientos'];
$requerimientos = unserialize(urldecode($requerimientos));

$fechaanulacion = date("Y-m-d H:i:s");
$usuarioanulacion = $_SESSION['usuario'];

//print($motivo."<br>");
//var_dump($requerimientos);
foreach($requerimientos as $reque) {
	$wherein .= $reque.",";
}
$wherein = substr($wherein, 0, -1);
$sqlUpdateAnula = "UPDATE reqfiscalizusimra
					SET requerimientoanulado = 1, motivoanulacion = '$motivo', fechaanulacion = '$fechaanulacion', usuarioanulacion = '$usuarioanulacion'
					WHERE nrorequerimiento in ($wherein)";
//print($sqlUpdateAnula."<br>");

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlUpdateAnula);
	$dbh->commit();
	$pagina = "listarRequerimientos.php?fecha=$fecha";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>

