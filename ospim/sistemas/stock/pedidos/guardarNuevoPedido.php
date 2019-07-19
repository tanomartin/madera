<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 
$fechasoli = fechaParaGuardar($_POST['fecsoli']);
$descripcion = $_POST['descripcion'];
$idProveedor = $_POST['proveedor'];
$sqlInsertCabPedido = "INSERT INTO stockcabpedidos VALUE(DEFAULT,'$fechasoli','$descripcion',DEFAULT,$idProveedor,'0000-00-00')";
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

	foreach($_POST as $key => $dato) {
		$pos = strpos($key, "idInsumo");
		if ($pos !== false) {
			$keyArray = explode("-",$key);
			$idinsumo = $dato;
			$indexCantidad = "cantidad".$idinsumo;
			$cantidad = $_POST[$indexCantidad];
			if ($cantidad != "") {
				$sqlInsuProd = "INSERT INTO stockdetpedidos VALUE($idPedido,$idinsumo,'',$cantidad,DEFAULT,0,'0000-00-00')";
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