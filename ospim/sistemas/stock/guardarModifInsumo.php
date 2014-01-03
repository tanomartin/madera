<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

//var_dump($_POST);
$cantInsumos = $_POST['cantInsumos'];

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	for ($i=1; $i<=$cantInsumos; $i++) {
		$campNombre = "idInsumo".$i;
		$id = $_POST["$campNombre"];
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
		$sqlUpdateInsumos = "UPDATE insumos SET nombre = '$nombreInsu', numeroserie = '$nroserie', descripcion = '$descrip', puntopedido = $ptoPedido, stockminimo = $stockmin, puntopromedio = $ptopromedio WHERE id = $id";
		//print($sqlUpdateInsumos."<br>");
		$dbh->exec($sqlUpdateInsumos);
	}
	$dbh->commit();
	
	$pagina = "productos.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>