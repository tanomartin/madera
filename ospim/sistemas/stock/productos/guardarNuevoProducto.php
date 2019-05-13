<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

$nombre = $_POST['nombre'];
$nroserie = $_POST['nroserie'];
$descrip = $_POST['descrip'];
$seguro = $_POST['seguro'];
$valor = "NULL";
if ($_POST['valor'] != "") { $valor = number_format($_POST['valor'],2,'.',''); }
$fecIni = fechaParaGuardar($_POST['fecini']);

$sisop = "NULL";
if ($_POST['sisop'] != "") { $sisop = "'".$_POST['sisop']."'"; }
$idsisop = "NULL";
if (isset($_POST['idsisop'])) { $idsisop = "'".$_POST['idsisop']."'"; }
$office = "NULL";
if ($_POST['office'] != "") { $office = "'".$_POST['office']."'"; }
$idoffice = "NULL";
if (isset($_POST['idoffice'])) { $idoffice = "'".$_POST['idoffice']."'"; }

$ubicacion = $_POST['ubicacion'];
$sector = $_POST['sector'];
$usuario = $_POST['usuario'];
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];

$sqlInsertProducto = "INSERT INTO stockproducto VALUE(DEFAULT,'$nombre','$nroserie',$seguro,$valor,DEFAULT,1,'$descrip',$sisop,$idsisop,$office,$idoffice,'$fecIni',DEFAULT,DEFAULT)";
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertProducto."<br>");
	$dbh->exec($sqlInsertProducto);
	$idProd = $dbh->lastInsertId('id'); 
	//print($idProd."<br>");
	$sqlInsertUbicacion = "INSERT INTO stockubicacionproducto VALUE($idProd,$sector,'$ubicacion',$usuario)";
	//print($sqlInsertUbicacion."<br>");
	$dbh->exec($sqlInsertUbicacion);
	
	foreach ($_POST as $key => $idInsumo) {
		$pos = strpos($key, "insumo");
		if ($pos !== false) {
			$sqlInsuProd = "INSERT INTO stockinsumoproducto VALUE($idInsumo,$idProd)";
			//print($sqlInsuProd."<br>");
			$dbh->exec($sqlInsuProd);
		}
	}
	
	$dbh->commit();
	$pagina = "productos.php";
	Header("Location: $pagina"); 

} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>