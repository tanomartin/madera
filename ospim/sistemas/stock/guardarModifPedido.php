<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

//var_dump($_POST);
$id = $_GET['id'];
$fechasoli = fechaParaGuardar($_POST['fecsoli']);
$descripcion = $_POST['descripcion'];

$sqlUpdateCabPedido = "UPDATE cabpedidos SET fechasolicitud = '$fechasoli', descripcion = '$descripcion', costototal = 0 WHERE id = $id";
$sqlDeleteDetPedido = "DELETE FROM detpedidos WHERE idpedido = $id";

$datos = array_values($_POST);
//var_dump($datos);
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlUpdateCabPedido."<br>");
	$dbh->exec($sqlUpdateCabPedido);
	
	//print($sqlDeleteDetPedido."<br>");
	$dbh->exec($sqlDeleteDetPedido);
	
	for ($i = 2; $i < sizeof($datos); $i++) {
		$idinsumo = $datos[$i];
		$i++;
		$cantidad = $datos[$i];
		if ($cantidad != "") {
			$sqlInsuProd = "INSERT INTO detpedidos VALUE($id,$idinsumo,'',$cantidad,DEFAULT,0,'0000-00-00')";
			//print($sqlInsuProd."<br>");
			$dbh->exec($sqlInsuProd);
		}
	}
	
	$dbh->commit();
	$pagina = "pedidos.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>