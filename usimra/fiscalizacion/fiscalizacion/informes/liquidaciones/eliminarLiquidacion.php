<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionUsimra.php");
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 

var_dump($_POST);
$fechaanulacion = date("Y-m-d H:i:s");
$usuarioanulacion = $_SESSION['usuario'];

$sqlUpdateAnulaLiqui = "UPDATE cabliquiusimra SET liquidacionanulada = 1, motivoanulacion = '".$_POST['motivo']."', fechaanulacion = '$fechaanulacion', usuarioanulacion = '$usuarioanulacion' WHERE  nrorequerimiento = ".$_POST['nroreq'];

$sqlUpdateAnulaReque = "UPDATE reqfiscalizusimra SET requerimientoanulado = 1, motivoanulacion = '".$_POST['motivo']."', fechaanulacion = '$fechaanulacion', usuarioanulacion = '$usuarioanulacion' WHERE nrorequerimiento = ".$_POST['nroreq'];

$r = 0;
$sqlUpdateAcuerdo = array();
$sqlAcuerdos = "SELECT nroacuerdo FROM aculiquiusimra where nrorequerimiento = ".$_POST['nroreq'];
print($sqlAcuerdos."<br>");
$resAcuerdos = mysql_query($sqlAcuerdos,$db);
while ($rowAcuerdos = mysql_fetch_array($resAcuerdos)) {
	$nroacuActivar = $rowAcuerdos['nroacuerdo'];
	$sqlUpdateAcuerdo[$r] = "UPDATE cabacuerdosusimra SET estadoacuerdo = 1 WHERE cuit = ".$_POST['cuit']." and nroacuerdo = $nroacuActivar";
	$r++;
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	//print($sqlUpdateAnulaLiqui."<br>");
	$dbh->exec($sqlUpdateAnulaLiqui);
	
	foreach($sqlUpdateAcuerdo as $sqlAcu) {
		//print($sqlAcu."<br>");
		$dbh->exec($sqlAcu);
	}
	
	//print($sqlUpdateAnulaReque."<br>");
	$dbh->exec($sqlUpdateAnulaReque);
	$dbh->commit();
	
	$pagina = "filtrosBusqueda.php";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}


?>
