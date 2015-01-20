<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");

//var_dump($_GET);
//var_dump($_POST);
$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];
$fechaEmision = fechaParaGuardar($_POST['fechaInicio']);
$fechaVto = fechaParaGuardar($_POST['fechaFin']);

//var_dump($_FILES['certificado']);
$archivo = $_FILES['certificado']['tmp_name'];
$tamarchivo = $_FILES['certificado']['size'];
if ($archivo != '') {
	$fp = fopen ($archivo, 'rb');
	if ($fp){
		$certificado = fread ($fp, $tamarchivo);
	}
	fclose($fp);
}
if ($archivo != '') {
	$sqlUpdateDisca = "UPDATE discapacitados SET emisioncertificado = :fechaemision, vencimientocertificado = :fechavto, documentocertificado = :certificado WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";
} else {
	$sqlUpdateDisca = "UPDATE discapacitados SET emisioncertificado = :fechaemision, vencimientocertificado = :fechavto WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$resUpdateDisca = $dbh->prepare($sqlUpdateDisca);
	if ($archivo != '') {
		$resUpdateDisca->execute(array(':fechaemision' => $fechaEmision, ':fechavto' => $fechaVto, ':certificado' => $certificado, ':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden ));
	} else {
		$resUpdateDisca->execute(array(':fechaemision' => $fechaEmision, ':fechavto' => $fechaVto, ':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden ));
	}

	$dbh->commit();
	$pagina = "consultarDiscapacitado.php?nroafiliado=$nroafiliado&nroorden=$nroorden&activo=1";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>