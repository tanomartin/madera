<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

var_dump($_POST);
echo "<br><br>";
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$nroserie = $_POST['nroserie'];
$descrip = $_POST['descrip'];
$valor = number_format($_POST['valor'],2,'.','');
$fecIni = fechaParaGuardar($_POST['fecIni']);

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
$activo = $_POST['activo'];
if ($activo == 0) {
	$fecBaja = fechaParaGuardar($_POST['fecBaja']);
} else {
	$fecBaja = '';
}
$fechamodificacion = date("Y-m-d H:i:s");

$sqlUpdateProducto = "UPDATE producto SET nombre = '$nombre', numeroserie = '$nroserie', valororiginal = $valor, activo = $activo, descripcion = '$descrip', sistemaoperativo = $sisop, idso = $idsisop, office = $office, idoffice = $idoffice, fechainicio = '$fecIni', fechabaja = '$fecBaja', fechamodificacion = '$fechamodificacion ' WHERE id = $id";
$sqlUpdateUbicacion = "UPDATE ubicacionproducto SET pertenencia = '$ubicacion', departamento = $sector, idusuario = $usuario WHERE id = $id"; 
$deleteInsumoPrducto = "DELETE from insumoproducto WHERE idproducto = $id";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	//print($sqlUpdateProducto."<br>");
	$dbh->exec($sqlUpdateProducto);
	//print($sqlUpdateUbicacion."<br>");
	$dbh->exec($sqlUpdateUbicacion);
	//print($deleteInsumoPrducto."<br><br>");
	$dbh->exec($deleteInsumoPrducto);

	foreach($_POST as $key => $idInsumo) {
		$pos = strpos($key, "insumo");
		if ($pos !== false) {
			$sqlInsuProd = "INSERT INTO insumoproducto VALUE($idInsumo,$id)";
			//print($sqlInsuProd."<br>");
			$dbh->exec($sqlInsuProd);
		}
	}
	
	$dbh->commit();
	$pagina = "productos.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>