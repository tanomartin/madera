<?php 
include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php"); 

$cuit=$_GET['cuit'];

$fechamodificacion = date("Y-m-d H:m:s");
$usuariomodificacion  = $_SESSION['usuario'];

//TODO: LAS TABLAS NO SON IGUALES VA A VER QUE HACERLO A MANO ******************************
$sqlReactivaEmpresa = "INSERT INTO empresas SELECT * FROM empresasdebaja where cuit = $cuit";
//******************************************************************************************

$sqlUpdateModficador = "UPDATE empresas set fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where cuit = $cuit";
$sqlDeleteEmpresaBaja = "DELETE from empresasdebaja where cuit = $cuit";

/*print($sqlReactivaEmpresa);print("<br>");
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