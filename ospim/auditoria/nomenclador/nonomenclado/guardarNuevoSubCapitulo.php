<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
//var_dump($_POST);
$codcapitulo = $_POST['codcapitulo'];
$idcapitulo = $_POST['idcapitulo'];
$codigo = $_POST['codigo'];
$codigo = str_pad($codigo,2,'0',STR_PAD_LEFT);
$descri = $_POST['descri'];
$codigoCompleto = $codcapitulo.".".$codigo;

$sqlExisteCodigo = "SELECT * FROM subcapitulosdepracticas WHERE codigo = '$codigoCompleto' and idcapitulo = $idcapitulo";
$resExisteCodigo = mysql_query($sqlExisteCodigo,$db);
$numExisteCodigo = mysql_num_rows($resExisteCodigo);
//print($sqlExisteCodigo."<br>");
if ($numExisteCodigo == 0) {	
	$sqlInsertSubCapitulo = "INSERT INTO subcapitulosdepracticas VALUES(DEFAULT,'$codigoCompleto',$idcapitulo,'$descri')";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//print($sqlInsertSubCapitulo."<br>");
		$dbh->exec($sqlInsertSubCapitulo);
		$dbh->commit();
		$pagina = "nuevaPractica.php";
		Header("Location: $pagina"); 
	}catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
} else {
	$rowExisteCodigo = mysql_fetch_assoc($resExisteCodigo);
	$id = $rowExisteCodigo['id'];
	$pagina = "existeSubCapitulo.php?id=$id";
	Header("Location: $pagina"); 
}
?>