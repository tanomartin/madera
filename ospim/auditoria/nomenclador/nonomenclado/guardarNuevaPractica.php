<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
//var_dump($_POST);
$tipopractica = $_POST['tipopractica'];
$tipo = $_POST['tipo'];
$codigo = $_POST['codigo'];
$complejidad = $_POST['complejidad'];
$inte = $_POST['internacion'];
if($tipo != -1) {
	$codigo = str_pad($codigo,2,'0',STR_PAD_LEFT);
	$codigoCompleto = $tipo.".".$codigo;
	$padre = $_POST['padre'];
} else {
	$codigoCompleto = $codigo;
	$padre = 'NULL';
}
$descri = $_POST['descri'];

$sqlExisteCodigo = "SELECT * FROM practicas WHERE codigopractica = '$codigoCompleto' and descripcion = '$descri' and nomenclador = 2";
$resExisteCodigo = mysql_query($sqlExisteCodigo,$db);
$numExisteCodigo = mysql_num_rows($resExisteCodigo);
if ($numExisteCodigo == 0) {	
	$descri = strtoupper($descri);
	$sqlInsertPractica = "INSERT INTO practicas VALUES(DEFAULT,'$codigoCompleto',$padre,$tipopractica,$complejidad,2,'$descri',1,1,1,1,1,$inte)";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//print($sqlInsertPractica."<br>");
		$dbh->exec($sqlInsertPractica);
		$lastId = $dbh->lastInsertId();
		$dbh->commit();
		$pagina = "nuevaPractica.php?id=$lastId";
		Header("Location: $pagina"); 
	}catch (PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
} else {
	$pagina = "existePractica.php?codigo=$codigoCompleto";
	Header("Location: $pagina"); 
}

?>