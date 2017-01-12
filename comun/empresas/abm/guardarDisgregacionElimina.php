<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 
include($libPath."bandejaSalida.php");

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
	$dbh->commit();
	
	$username = "sistemas@ospim.com.ar";
	$subject = "Se ha efectuado una disgregación dineraria";
	$bodymail = "<body><br><br>Este es un mensaje de Aviso.<br><br>En el CUIT: <strong>".$cuit."</strong>, se ha efectuado un cambio en la disgregación dineraria por la eliminación de una jurisdicción.";
	$address = "jlgomez@usimra.com.ar";
	$modulo = 'Empresa';
	guardarEmail($username, $subject, $bodymail, $address, $modulo, null);
	
	$pagina = "empresa.php?cuit=$cuit&origen=$origen";
	Header("Location: $pagina"); 

}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}


?>