<?php include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/fechas.php"); 

$fechamodificacion = date("Y-m-d H:m:s");
$usuariomodificacion  = $_SESSION['usuario'];

$coddelega = $_GET['coddelega'];

$datos = array_values($_POST);

$cuit = $datos[0];
$domicilio = $datos[1];
$domicilio = strtoupper($domicilio);
$indpostal = $datos[2];
$codpostal = $datos[3];
$alfapostal = $datos[4];
$localidad = $datos[5];
$provincia = $datos[6];
$codprovin = $datos[7];
$delegacion = $datos[8];
$ddn1 = $datos[9];
$telefono1 = $datos[10];
$contacto1 = $datos[11];
$email = $datos[12];
$disgdinero = $datos[13];

$sqlDeleteJuris = "DELETE from jurisdiccion where cuit = $cuit and codidelega = $coddelega";

$sqlInsertJurisNueva = "INSERT INTO jurisdiccion VALUES ('$cuit','$delegacion','$codprovin','$indpostal','$codpostal','$alfapostal','$localidad','$domicilio','$ddn1','$telefono1','$contacto1','$email','$disgdinero')";

//TODO: ADEMAS TENGO QUE UPDETEAR LAS DLEGACIONES DE LOS BENEFICIARIOS

/*print($sqlDeleteJuris); print("<br>");
print($sqlInsertJurisNueva);*/

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlDeleteJuris);
	$dbh->exec($sqlInsertJurisNueva);
	$dbh->commit();
	$pagina = "empresa.php?cuit=$cuit&origen=$origen";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>