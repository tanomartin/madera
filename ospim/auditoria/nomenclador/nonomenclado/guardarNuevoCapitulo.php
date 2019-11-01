<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$tipo = $_POST['tipo'];
$codigo = $_POST['codigo'];
$codigo = str_pad($codigo,2,'0',STR_PAD_LEFT);
$descri = $_POST['descri'];

$sqlExisteCodigo = "SELECT * FROM capitulosdepracticas WHERE descripcion = '$descri' and idtipopractica = $tipo";
$resExisteCodigo = mysql_query($sqlExisteCodigo,$db);
$numExisteCodigo = mysql_num_rows($resExisteCodigo);
//print($sqlExisteCodigo."<br>");
if ($numExisteCodigo == 0) {
	$descri = strtoupper($descri);
	$sqlInsertCapitulo = "INSERT INTO capitulosdepracticas VALUES(DEFAULT,'$codigo',$tipo,'$descri')";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//print($sqlInsertCapitulo."<br>");
		$dbh->exec($sqlInsertCapitulo);
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
	$pagina = "existeCapitulo.php?id=$id";
	Header("Location: $pagina"); 
}

?>