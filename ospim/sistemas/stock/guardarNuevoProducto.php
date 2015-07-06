<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

//var_dump($_POST);

$arrayProducto = $_POST['producto'];
$nombre = $_POST['nombre'];
$nroserie = $_POST['nroserie'];
$descrip = $_POST['descrip'];
$valor = number_format($_POST['valor'],2,'.','');
$fecIni = fechaParaGuardar($_POST['fecIni']);
$ubicacion = $_POST['ubicacion'];
$sector = $_POST['sector'];
$usuario = $_POST['usuario'];
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];

$sqlInsertProducto = "INSERT INTO producto VALUE(DEFAULT,'$nombre','$nroserie',$valor,DEFAULT,1,'$descrip','$fecIni',DEFAULT,DEFAULT)";


$datos = array_values($_POST);
//var_dump($datos);
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
	
	for ($i = 8; $i < sizeof($datos); $i++) {
		$idInsumo = $datos[$i];
		$sqlInsuProd = "INSERT INTO insumoproducto VALUE($idInsumo,$idProd)";
		//print($sqlInsuProd."<br>");
		$dbh->exec($sqlInsuProd);
	}
	
	$dbh->commit();
	$pagina = "productos.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>