<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

var_dump($_POST);
$fechasoli = fechaParaGuardar($_POST['fecsoli']);
$descripcion = $_POST['descripcion'];

$sqlInsertCabPedido = "INSERT INTO cabpedidos VALUE(DEFAULT,'$fechasoli','$descripcion',DEFAULT,1,'0000-00-00')";


$datos = array_values($_POST);
var_dump($datos);
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertCabPedido."<br>");
	$dbh->exec($sqlInsertCabPedido);
	$idPedido = $dbh->lastInsertId('id'); 
	//print($idPedido."<br>");

	
	for ($i = 2; $i < sizeof($datos); $i++) {
		$idinsumo = $datos[$i];
		$i++;
		$cantidad = $datos[$i];
		if ($cantidad != "") {
			$sqlInsuProd = "INSERT INTO detpedidos VALUE($idPedido,$idinsumo,'',$cantidad,DEFAULT,0,'0000-00-00')";
			//print($sqlInsuProd."<br>");
			$dbh->exec($sqlInsuProd);
		}
	}
	
	$dbh->commit();
	$pagina = "pedidos.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>