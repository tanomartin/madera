<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php"); 

$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;
$cuit=$_GET['cuit'];
$cantJuris=$_GET['cantjuris'];

$datos = array_values($_POST);
$sqlNuevaJusris = $datos[0];

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlNuevaJusris);
	//print($sqlNuevaJusris);print("<br>");
	for ($i=1; $i<($cantJuris+1)*2; $i++) {
		$delega = $datos[$i];
		$i = $i+1;
		$disgdinero = $datos[$i];
		$sqlUpdateDisgregacion = "UPDATE jurisdiccion set disgdinero = '$disgdinero' where cuit = $cuit and codidelega = $delega";
		//print($sqlUpdateDisgregacion);print("<br>");
		$dbh->exec($sqlUpdateDisgregacion);
	}

	$dbh->commit();
	$pagina = "empresa.php?cuit=$cuit&origen=$origen";
	Header("Location: $pagina"); 

}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>