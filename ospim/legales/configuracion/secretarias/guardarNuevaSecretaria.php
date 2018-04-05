<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$datos = array_values($_POST);
$denomi = strtoupper($datos[0]);
$codigoJuz = $datos[1];
$rs = mysql_query("SELECT MAX(codigosecretaria) FROM secretarias where codigojuzgado = $codigoJuz");
if ($row = mysql_fetch_row($rs)) {
	$codigoSecre = trim($row[0]) + 1;
}

$sqlNuevaSecretaria = "INSERT INTO secretarias VALUES($codigoJuz, $codigoSecre, '$denomi')";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$dbh->exec($sqlNuevaSecretaria);
	$dbh->commit();
	
	$pagina = "secretarias.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>

