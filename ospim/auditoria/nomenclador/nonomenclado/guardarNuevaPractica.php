<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
var_dump($_POST);
$tipopractica = $_POST['tipopractica'];
$tipo = $_POST['tipo'];
$codigo = $_POST['codigo'];
if($tipo != -1) {
	$codigo = str_pad($codigo,2,'0',STR_PAD_LEFT);
	$codigoCompleto = $tipo.".".$codigo;
} else {
	$codigo = str_pad($codigo,4,'0',STR_PAD_LEFT);
	$codigoCompleto = $codigo;
}
$descri = $_POST['descri'];

$sqlExisteCodigo = "SELECT * FROM practicas WHERE codigopractica = '$codigoCompleto'";
$resExisteCodigo = mysql_query($sqlExisteCodigo,$db);
$numExisteCodigo = mysql_num_rows($resExisteCodigo);
if ($numExisteCodigo == 0) {	
	$sqlInsertPractica = "INSERT INTO practicas VALUES(DEFAULT,'$codigoCompleto',$tipopractica,2,'$descri',0)";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//print($sqlInsertPractica."<br>");
		$dbh->exec($sqlInsertPractica);
		$dbh->commit();
		$pagina = "../buscador/buscadorPractica.php?dato=$codigoCompleto";
		Header("Location: $pagina"); 
	}catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
} else {
	$pagina = "existePractica.php?codigo=$codigoCompleto";
	Header("Location: $pagina"); 
}

?>