<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
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
	$descri = strtoupper($descri);
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
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
} else {
	$rowExisteCodigo = mysql_fetch_assoc($resExisteCodigo);
	$id = $rowExisteCodigo['id'];
	$pagina = "existeSubCapitulo.php?id=$id";
	Header("Location: $pagina"); 
}
?>