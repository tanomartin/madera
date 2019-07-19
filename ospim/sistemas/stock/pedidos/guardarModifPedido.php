<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 
$id = $_GET['id'];
$fechasoli = fechaParaGuardar($_POST['fecsoli']);
$idproveedor = $_POST['proveedor'];
$descripcion = $_POST['descripcion'];

$sqlUpdateCabPedido = "UPDATE stockcabpedidos SET fechasolicitud = '$fechasoli', idproveedor = $idproveedor, descripcion = '$descripcion', costototal = 0 WHERE id = $id";
$sqlDeleteDetPedido = "DELETE FROM stockdetpedidos WHERE idpedido = $id";
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
	foreach($_POST as $key => $dato) {
		$pos = strpos($key, "idInsumo");
		if ($pos !== false) {
			$keyArray = explode("-",$key);
			$idinsumo = $dato;
			$indexCantidad = "cantidad".$idinsumo;
			$cantidad = $_POST[$indexCantidad];
			if ($cantidad != "") {
				$sqlInsuProd = "INSERT INTO stockdetpedidos VALUE($id,$idinsumo,'',$cantidad,DEFAULT,0,'0000-00-00')";
				//print($sqlInsuProd."<br>");
				$dbh->exec($sqlInsuProd);
			}
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