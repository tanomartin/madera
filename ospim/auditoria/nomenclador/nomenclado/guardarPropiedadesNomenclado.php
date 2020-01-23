<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$i=0;
$sqlUpdatePractica = array();
foreach($_POST as $key => $value) {
	$resultado = strpos($key, "idpractica");
	if($resultado !== FALSE){
		$sqlUpdatePractica[$i] = "UPDATE practicas SET ";
		$idpractica = $value;
	}
	$resultado = strpos($key, "complejidad");
	if($resultado !== FALSE){
		$codigoComplejidad = $value;
		$sqlUpdatePractica[$i] .= "codigocomplejidad = $codigoComplejidad, ";
	}
	$resultado = strpos($key, "internacion");
	if($resultado !== FALSE){
		$interancion = $value;
		$sqlUpdatePractica[$i] .= "internacion = $interancion ";
		$sqlUpdatePractica[$i] .= "WHERE idpractica = '$idpractica'";
		$i++;
	}
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	foreach($sqlUpdatePractica as $sqlUpdate) {
		//print($sqlUpdate."<br>");
		$dbh->exec($sqlUpdate);
	}
	
	$dbh->commit();
	$pagina = "menuNomenclado.php";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>