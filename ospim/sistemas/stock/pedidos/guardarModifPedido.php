<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 
$id = $_GET['id'];
$fechasoli = fechaParaGuardar($_POST['fecsoli']);
$idproveedor = $_POST['proveedor'];
$descripcion = $_POST['descripcion'];

$sqlUpdateCabPedido = "UPDATE stockcabpedidos SET fechasolicitud = '$fechasoli', idproveedor = $idproveedor, descripcion = '$descripcion', costototal = 0 WHERE id = $id";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlUpdateCabPedido."<br>");
	$dbh->exec($sqlUpdateCabPedido);
	$whereIn = "(";
	foreach($_POST as $key => $dato) {
		$pos = strpos($key, "idInsumo");
		if ($pos !== false) {
			$keyArray = explode("-",$key);
			$idinsumo = $dato;
			$indexCantidad = "cantidad".$idinsumo;
			$cantidad = $_POST[$indexCantidad];
			if ($cantidad != "") {
				$sqlInsuProd = "INSERT INTO stockdetpedidos VALUES($id,$idinsumo,'',$cantidad,DEFAULT,0,'0000-00-00')
				  				ON DUPLICATE KEY UPDATE cantidadpedido = $cantidad";
				//print($sqlInsuProd."<br>");
				$dbh->exec($sqlInsuProd);
			} else {
				$whereIn .= $idinsumo.",";
			}
		}
	}
	$whereIn = substr($whereIn, 0, -1);
	$whereIn .= ")";
	$sqlDeleteDetPedido = "DELETE FROM stockdetpedidos WHERE idpedido = $id AND idinsumo IN $whereIn";
	//print($sqlDeleteDetPedido."<br>");
	$dbh->exec($sqlDeleteDetPedido);
	
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