<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 

$ori = "O";
if ($_GET['origen'] == "usimra") {
	$ori = "U";
}
$modulo = $_GET['modulo'];
$nombreModulo = "id-$modulo";
$idmodulo = $_POST[$nombreModulo];

$nombreDato1 = "dato1-$modulo";
$dato1 = "NULL";
if (isset($_POST[$nombreDato1])) {
	$dato1 = "'$_POST[$nombreDato1]'";
}
$nombreDato2 = "dato2-$modulo";
$dato2 = "NULL";
if (isset($_POST[$nombreDato2])) {
	$dato2 = "'$_POST[$nombreDato2]'";
}
$nombreDato3 = "dato3-$modulo";
$dato3 = "NULL";
if (isset($_POST[$nombreDato3])) {
	$dato3 = "'$_POST[$nombreDato3]'";
}
$nombreDato4 = "dato4-$modulo";
$dato4 = "NULL";
if (isset($_POST[$nombreDato4])) {
	$dato4 = "'$_POST[$nombreDato4]'";
}
$nombreMotivo = "motivo-$modulo";
$idMotivo = $_POST[$nombreMotivo];
$nombreObs = "obs-$modulo";
$observacion = $_POST[$nombreObs];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$sqlInsertCorreccion = "INSERT INTO correcciones VALUES(DEFAULT,'$ori',$idmodulo,$dato1,$dato2,$dato3,$dato4,$idMotivo,'$observacion','$fecharegistro','$usuarioregistro',NULL,NULL,NULL,NULL,NULL)";
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertCorreccion."<br>");
	$dbh->exec($sqlInsertCorreccion);
	
	$dbh->commit();
	$pagina = "moduloCorrecciones.php?origen=$origen";
	Header("Location: $pagina");
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/".$_GET['origen']."/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}
?>
