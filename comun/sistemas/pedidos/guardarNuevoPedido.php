<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 

$ori = "O";
if ($_GET['origen'] == "usimra") {
	$ori = "U";
}
$descripcion = $_POST["descripcion"];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$sqlInsertPedido = "INSERT INTO pedidos VALUES(DEFAULT,'$ori','$descripcion','PENDIENTE','$fecharegistro','$usuarioregistro',NULL,NULL,NULL,NULL)";
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertPedido."<br>");
	$dbh->exec($sqlInsertPedido);
	
	$dbh->commit();
	$pagina = "moduloPedidos.php?origen=$origen";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}
?>
