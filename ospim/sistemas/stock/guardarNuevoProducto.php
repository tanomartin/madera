<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

//var_dump($_POST);

$arrayProducto = $_POST['producto'];
$arrayProducto = unserialize(urldecode($arrayProducto));
$nombre = $arrayProducto['nombre'];
$nroserie = $arrayProducto['nroserie'];
$descrip = $arrayProducto['descrip'];
$cantInsumos = $arrayProducto['cantInsumos'];
$valor = number_format($arrayProducto['valor'],2,'.','');
$fecIni = fechaParaGuardar($arrayProducto['fecIni']);
$ubicacion = $arrayProducto['ubicacion'];
$sector = $arrayProducto['sector'];
$usuario = $arrayProducto['usuario'];
$fechamodificacion = date("Y-m-d H:m:s");
$usuariomodif = $_SESSION['usuario'];

$sqlInsertProducto = "INSERT INTO producto VALUE(DEFAULT,'$nombre','$nroserie',$cantInsumos,$valor,1,'$descrip','$fecIni',DEFAULT,DEFAULT)";

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
	for ($i=1; $i<=$cantInsumos; $i++) {
		$campNombre = "nombre".$i;
		$nombreInsu = $_POST["$campNombre"];
		$campNombre = "nroserie".$i;
		$nroserie = $_POST["$campNombre"];
		$campNombre = "descrip".$i;
		$descrip = $_POST["$campNombre"];
		$campNombre = "ptoPedido".$i;
		$ptoPedido = $_POST["$campNombre"];
		$campNombre = "stockmin".$i;
		$stockmin = $_POST["$campNombre"];
		$campNombre = "ptoPromedio".$i;
		$ptopromedio = $_POST["$campNombre"];
		$sqlInsertInsumos = "INSERT INTO insumos VALUE (DEFAULT, $idProd, '$nombreInsu','$nroserie','$descrip',$ptoPedido,$stockmin,$ptopromedio)";
		//print($sqlInsertInsumos."<br>");
		$dbh->exec($sqlInsertInsumos);
		$idInsumo = $dbh->lastInsertId('id'); 
		$sqlInsertStock = "INSERT INTO stock VALUE ($idInsumo,0,'$fechamodificacion','$usuariomodif')";
		//print($sqlInsertStock."<br>");
		$dbh->exec($sqlInsertStock);
	}
	$dbh->commit();
	
	$pagina = "productos.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>