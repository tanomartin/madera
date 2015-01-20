<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");

//var_dump($_GET);
//var_dump($_POST);
$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];
$fechaEmision = fechaParaGuardar($_POST['fechaInicio']);
$fechaVto = fechaParaGuardar($_POST['fechaFin']);

var_dump($_FILES['certificado']);
$archivo = $_FILES['certificado']['tmp_name'];
$tamarchivo = $_FILES['certificado']['size'];
$fp = fopen ($archivo, 'rb');
if ($fp){
	$certificado = fread ($fp, $tamarchivo);
}
fclose($fp);
$sqlInsertDisca = "INSERT INTO discapacitados VALUE(:nroafiliado,:nroorden,1,:fechaemision,:fechavto,:certificado)";
if ($nroorden == 0) { 
	$sqlUpdateBene = "UPDATE titulares SET discapacidad = 1, certificadodiscapacidad = 1 WHERE nroafiliado = :nroafiliado";
} else {
	$sqlUpdateBene = "UPDATE familiares SET discapacidad = 1, certificadodiscapacidad = 1 WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";
} 


try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$resInsertDisca = $dbh->prepare($sqlInsertDisca);
	$resInsertDisca->execute(array(':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden, ':fechaemision' => $fechaEmision, ':fechavto' => $fechaVto, ':certificado' => $certificado));
	
	$resUpdateBene = $dbh->prepare($sqlUpdateBene);
	if ($nroorden == 0) { 
		$resUpdateBene->execute(array(':nroafiliado' => $nroafiliado));
	} else {
		$resUpdateBene->execute(array(':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden));
	}

	$dbh->commit();
	$pagina = "consultarDiscapacitado.php?nroafiliado=$nroafiliado&nroorden=$nroorden&activo=1";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>