<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 
include($libPath."fechas.php"); 

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$datos = array_values($_POST);

$cuit = $datos[0];
$nombre = $datos[1];
$nombre = strtoupper($nombre);
$domicilio = $datos[2];
$domicilio = strtoupper($domicilio);
$indpostal = $datos[3];
$codpostal = $datos[4];
$alfapostal = $datos[5];
$localidad = $datos[6];
$provincia = $datos[7];
$codprovin = $datos[8];
$ddn1 = $datos[9];
$telefono1 = $datos[10];
$contacto1 = $datos[11];
$ddn2 = $datos[12];
$telefono2 = $datos[13];
$contacto2 = $datos[14];

$codigotipo = 0;
$peretenencia = 3;

$actividad = $datos[15];
$delegacion = $datos[16];
$obsOspim = $datos[17];
$obsUsimra = $datos[18]; 
$inicioOspim = $datos[19];
$inicioOspim = fechaParaGuardar($inicioOspim); 
$inicioUsimra = $datos[20];
$inicioUsimra = fechaParaGuardar($inicioUsimra); 
$email = $datos[21];
$carpetaArchivo = $datos[22];
	
$disgDineraria = 100;

/*	print($cuit."<br>");print($nombre."<br>");print($domicilio."<br>");print($indpostal."<br>");print($codpostal."<br>");
	print($alfapostal."<br>");print($localidad."<br>");print($provincia."<br>");print($codprovin."<br>");
	print($ddn1."<br>");print($telefono1."<br>");print($contacto1."<br>");print($ddn2."<br>");print($telefono2."<br>");
	print($actividad."<br>");print($obsOspim."<br>");print($obsUsimra."<br>");print($inicioOspim."<br>");
	print($inicioUsimra."<br>");print($email."<br>");print($carpetaArchivo."<br>");print("<br><br>");*/

$sqlCargaEmpresa = "INSERT INTO empresas VALUES ('$cuit','$nombre','$codprovin','$indpostal','$codpostal','$alfapostal','$localidad','$domicilio','$ddn1','$telefono1','$contacto1','$ddn2','$telefono2','$contacto2','$codigotipo','$peretenencia','$actividad','$obsOspim','$obsUsimra','$inicioOspim','$inicioUsimra','$email','$carpetaArchivo','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion','N')";

$sqlJurisdiccion = "INSERT INTO jurisdiccion VALUES ('$cuit','$delegacion','$codprovin','$indpostal','$codpostal','$alfapostal','$localidad','$domicilio','$ddn1','$telefono1','$contacto1','$email','$disgDineraria')";

/*print($sqlCargaEmpresa);
print($sqlJurisdiccion);*/

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlCargaEmpresa);
	$dbh->exec($sqlJurisdiccion);
	$dbh->commit();
	$pagina = "empresa.php?cuit=$cuit&origen=$origen";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/".$origen."/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>