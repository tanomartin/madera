<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
//var_dump($_POST);
$i=0;
foreach($_POST as $key => $value) {
	$resultado = strpos($key, "valor");
	if($resultado !== FALSE){
		$codigoPracticaArray = explode("-",$key);
		$codigoPractica = str_replace("_",".",$codigoPracticaArray[1]);
		$valorNacional = $_POST[$key];
		$sqlUpdateValorArray[$i] = "UPDATE practicas SET valornacional = '$valorNacional' WHERE codigopractica = '$codigoPractica' and nomenclador = 1";
		$i++;
	}
}
reset($_POST);
$i=0;
foreach($_POST as $key => $value) {
	$resultado = strpos($key, "complejidad");
	if($resultado !== FALSE){
		$codigoPracticaArray = explode("-",$key);
		$codigoPractica = str_replace("_",".",$codigoPracticaArray[1]);
		$codigoComplejidad = $_POST[$key];
		$sqlUpdateComplejidadArray[$i] = "UPDATE practicas SET codigocomplejidad = $codigoComplejidad WHERE codigopractica = '$codigoPractica'";
		$i++;
	}
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	foreach($sqlUpdateValorArray as $sqlUpdateValor) {
		//print($sqlUpdateValor."<br>");
		$dbh->exec($sqlUpdateValor);
	}
	foreach($sqlUpdateComplejidadArray as $sqlUpdateComplejidad) {
		//print($sqlUpdateComplejidad."<br>");
		$dbh->exec($sqlUpdateComplejidad);
	}
	
	$dbh->commit();
	$pagina = "listadorNacional.php";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>