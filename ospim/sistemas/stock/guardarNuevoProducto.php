<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

var_dump($_POST);

$nombre = $_POST['nombre'];
$nroserie = $_POST['nroserie'];
$descrip = $_POST['descrip'];
$valor = number_format($_POST['valor'],2,'.','');
$fecIni = fechaParaGuardar($_POST['fecIni']);
$ubicacion = $_POST['ubicacion'];
$sector = $_POST['sector'];
$usuario = $_POST['usuario'];

$sqlInsertProducto = "INSERT INTO producto VALUE(DEFAULT,'$nombre','$nroserie',$valor,1,'$descrip','$fecIni',DEFAULT,DEFAULT)";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertProducto."<br>");
	$dbh->exec($sqlInsertProducto);
	$idProd = $dbh->lastInsertId('id'); 
	//print($idProd."<br>");
	$sqlInsertUbicacion = "INSERT INTO ubicacionproducto VALUE($idProd,$sector,'$ubicacion','$usuario')";
	//print($sqlInsertUbicacion."<br>");
	$dbh->exec($sqlInsertUbicacion);
	$dbh->commit();
	
	$pagina = "productos.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>