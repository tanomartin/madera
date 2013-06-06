<?php include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/fechas.php"); 

$datos = array_values($_POST);
$motivobaja = $datos[0];
$fechabaja = fechaParaGuardar($datos[1]);
$fechaefectivizacion = date("Y-m-d H:m:s");
$usuarioefectivizacion = $_SESSION['usuario'];

$cuit=$_GET['cuit'];
$sqlTomarDatos = "SELECT * FROM empresas where cuit = $cuit";
$resTomarDatos = mysql_query($sqlTomarDatos,$db); 
$rowTomarDatos = mysql_fetch_array($resTomarDatos);

/*************** DATOS EMPRESA A REACTIVAR *****************/
$cuit = $rowTomarDatos['cuit'];
$nombre = $rowTomarDatos['nombre'];
$codprovin = $rowTomarDatos['codprovin'];
$indpostal = $rowTomarDatos['indpostal'];
$codpostal = $rowTomarDatos['numpostal'];
$alfapostal = $rowTomarDatos['alfapostal'];
$localidad = $rowTomarDatos['codlocali'];
$domicilio = $rowTomarDatos['domilegal'];
$ddn1 = $rowTomarDatos['ddn1'];
$telefono1 = $rowTomarDatos['telefono1'];
$contacto1 = $rowTomarDatos['contactel1'];
$ddn2 = $rowTomarDatos['ddn2'];
$telefono2 = $rowTomarDatos['telefono2'];
$contacto2 = $rowTomarDatos['contactel2'];
$codigotipo = $rowTomarDatos['codigotipo'];
$peretenencia = $rowTomarDatos['codpertene'];
$actividad = $rowTomarDatos['actividad'];
$obsOspim = $rowTomarDatos['obsospim'];
$obsUsimra = $rowTomarDatos['obsusimra'];
$inicioOspim = $rowTomarDatos['iniobliosp'];
$inicioUsimra = $rowTomarDatos['iniobliusi'];
$email = $rowTomarDatos['email'];
$carpetaArchivo = $rowTomarDatos['carpetaarchivo'];
$fecharegistro = $rowTomarDatos['fecharegistro'];
$usuarioregistro = $rowTomarDatos['usuarioregistro'];
$fechamodificacion = $rowTomarDatos['fechamodificacion'];
$usuariomodificacion = $rowTomarDatos['usuariomodificacion'];
$mirroring = $rowTomarDatos['mirroring'];
/************************************************************/

$sqlDesactivarEmpresa = "INSERT INTO empresasdebaja VALUES ('$cuit','$nombre','$codprovin','$indpostal','$codpostal','$alfapostal','$localidad','$domicilio','$ddn1','$telefono1','$contacto1','$ddn2','$telefono2','$contacto2','$codigotipo','$peretenencia','$actividad','$obsOspim','$obsUsimra','$inicioOspim','$inicioUsimra','$email','$carpetaArchivo','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion','$mirroring','$fechabaja','$motivobaja','$fechaefectivizacion','$usuarioefectivizacion')";

$sqlDeleteEmpresa = "DELETE from empresas where cuit = $cuit";

/*print($sqlTomarDatos);print("<br>");
print($sqlDesactivarEmpresa);print("<br>");
print($sqlDeleteEmpresa);*/

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlDesactivarEmpresa);
	$dbh->exec($sqlDeleteEmpresa);
	$dbh->commit();
	$pagina = "empresaBaja.php?cuit=$cuit&origen=$origen&reactiva=1";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>