<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/lib/envioMailGeneral.php"); 

$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;
$cuit=$_GET['cuit'];
$cantJuris=$_GET['cantjuris'];
$codidelega=$_GET['coddel'];

$datos = array_values($_POST);
$sqlDeleteJusris = $datos[0];

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	for ($i=1; $i<$cantJuris*2; $i++) {
		$delega = $datos[$i];
		$i = $i+1;
		$disgdinero = $datos[$i];
		$sqlUpdateDisgregacion = "UPDATE jurisdiccion set disgdinero = '$disgdinero' where cuit = $cuit and codidelega = $delega";
		//print($sqlUpdateDisgregacion);print("<br>");
		$dbh->exec($sqlUpdateDisgregacion);
	}
	//print($sqlDeleteJusris);print("<br>");
	$dbh->exec($sqlDeleteJusris);
	
	
	$username = "sistemas@ospim.com.ar";
	$passw = "pepepascual";
	$fromRepli = "Sistemas O.S.P.I.M.";
	$subject = "Se ha efectuado una disgregación dineraria";
	$bodymail = "<body><br><br>Este es un mensaje de Aviso.<br><br>En el CUIT: <strong>".$cuit."</strong>, se ha efectuado un cambio en la disgregación dineraria por la eliminación de una jurisdicción.";
	$address = "jlgomez@usimra.com.ar";
	envioMail($username, $passw, $fromRepli, $subject, $bodymail, $address);
	
	$dbh->commit();
	$pagina = "empresa.php?cuit=$cuit&origen=$origen";
	Header("Location: $pagina"); 

}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>