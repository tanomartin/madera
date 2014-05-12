<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

//var_dump($_POST);

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$nroserie = $_POST['nroserie'];
$descrip = $_POST['descrip'];
$valor = number_format($_POST['valor'],2,'.','');
$fecIni = fechaParaGuardar($_POST['fecIni']);
$ubicacion = $_POST['ubicacion'];
$sector = $_POST['sector'];
$usuario = $_POST['usuario'];
$activo = $_POST['activo'];
if ($activo == 0) {
	$fecBaja = fechaParaGuardar($_POST['fecBaja']);
	$inicioFor = 12;
} else {
	$fecBaja = '';
	$inicioFor = 11;
}
$fechamodificacion = date("Y-m-d H:i:s");

$sqlUpdateProducto = "UPDATE producto SET nombre = '$nombre', numeroserie = '$nroserie', valororiginal = $valor, activo = $activo, descripcion = '$descrip', fechainicio = '$fecIni', fechabaja = '$fecBaja', fechamodificacion = '$fechamodificacion ' WHERE id = $id";
$sqlUpdateUbicacion = "UPDATE ubicacionproducto SET pertenencia = '$ubicacion', departamento = $sector, usuario = '$usuario' WHERE id = $id"; 
$deleteInsumoPrducto = "DELETE from insumoproducto WHERE idproducto = $id";

$datos = array_values($_POST);
var_dump($datos);
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
	//print($deleteInsumoPrducto."<br>");
	$dbh->exec($deleteInsumoPrducto);
	
	for ($i = $inicioFor; $i < sizeof($datos); $i++) {
		$idInsumo = $datos[$i];
		$sqlInsuProd = "INSERT INTO insumoproducto VALUE($idInsumo,$id)";
		//print($sqlInsuProd."<br>");
		$dbh->exec($sqlInsuProd);
	}
	
	$dbh->commit();
	
	$pagina = "productos.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>