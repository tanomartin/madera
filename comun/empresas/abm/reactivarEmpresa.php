<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php"); 

$cuit=$_GET['cuit'];
$fechamodificacionUpdate = date("Y-m-d H:m:s");
$usuariomodificacionUpdate  = $_SESSION['usuario'];

$sqlTomarDatos = "SELECT * FROM empresasdebaja where cuit = $cuit";
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
/************************************************************/

$sqlReactivaEmpresa = "INSERT INTO empresas VALUES ('$cuit','$nombre','$codprovin','$indpostal','$codpostal','$alfapostal','$localidad','$domicilio','$ddn1','$telefono1','$contacto1','$ddn2','$telefono2','$contacto2','$codigotipo','$peretenencia','$actividad','$obsOspim','$obsUsimra','$inicioOspim','$inicioUsimra','$email','$carpetaArchivo','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion','S')";

$sqlUpdateModficador = "UPDATE empresas set fechamodificacion = '$fechamodificacionUpdate', usuariomodificacion = '$usuariomodificacionUpdate' where cuit = $cuit";

$sqlDeleteEmpresaBaja = "DELETE from empresasdebaja where cuit = $cuit";

/*print($sqlTomarDatos);print("<br>");
print($sqlReactivaEmpresa);print("<br>");
print($sqlUpdateModficador);print("<br>");
print($sqlDeleteEmpresaBaja);*/

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlReactivaEmpresa);
	$dbh->exec($sqlUpdateModficador);
	$dbh->exec($sqlDeleteEmpresaBaja);
	$dbh->commit();
	$pagina = "empresa.php?cuit=$cuit&origen=$origen&reactiva=1";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>