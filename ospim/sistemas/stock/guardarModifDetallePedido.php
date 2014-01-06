<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

//var_dump($_POST);
$idPedido = $_GET['id'];
//print($id."<br>");
$cantinsumos = $_GET['cantinsumos'];
//print($cantinsumos."<br>");

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$totalPedido = 0;
	for ($i = 0; $i < $cantinsumos; $i++) {
		
		$campo = "id".$i;
		$id = $_POST[$campo];
		$campo = "cantidad".$i;
		$cantidad = $_POST[$campo];
		$campo = "costo".$i;
		$costo = $_POST[$campo];
		if($costo == "") {
			$costo = "DEFAULT";
		} else {
			$costo = number_format($costo,2,'.','');
			$totalPedido = $totalPedido + ($cantidad * $costo);
		}
		$campo = "entregado".$i;
		$entregado = $_POST[$campo];
		$campo = "descrip".$i;
		$descrip =$_POST[$campo];
		
		if($entregado == $cantidad)  {
			$fechacierre = date("Y-m-d");
		} else {
			$fechacierre = "DEFAULT";
		}
		$sqlUpdatePedido = "UPDATE detpedidos SET descripcion = '$descrip', costounitario = $costo, cantidadentregada = $entregado, fechacierre = '$fechacierre' WHERE idpedido = $idPedido and idinsumo = $id";
		//print($sqlUpdatePedido."<br>");
		$dbh->exec($sqlUpdatePedido);
	}
	
	$sqlVeoCierre = "SELECT * FROM detpedidos WHERE idpedido = $idPedido and fechacierre = '0000-00-00'";
	print($sqlVeoCierre."<br>");
	$resVeoCierre = mysql_query($sqlVeoCierre,$db);
	$canVeoCierre = mysql_num_rows($resVeoCierre);
	print($canVeoCierre."<br>");
	
	if ($canVeoCierre == 0) {
		$fechacierre = date("Y-m-d");
	} else {
		$fechacierre = "0000-00-00";
	}	
	
	$sqlCierrePedido = "UPDATE cabpedidos SET costototal = $totalPedido, fechacierre = '$fechacierre' WHERE id = $idPedido";
	//print($sqlCierrePedido."<br>");
	$dbh->exec($sqlCierrePedido);
	
	$dbh->commit();
	$pagina = "pedidos.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>