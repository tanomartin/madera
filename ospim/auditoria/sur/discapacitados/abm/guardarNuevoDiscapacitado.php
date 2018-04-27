<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

//var_dump($_GET);
//var_dump($_POST);
$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];
$fechaAlta = fechaParaGuardar($_POST['fechaAlta']);
$fechaEmision = fechaParaGuardar($_POST['fechaInicio']);
$fechaVto = fechaParaGuardar($_POST['fechaFin']);
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;


//var_dump($_FILES['certificado']);
$archivo = $_FILES['certificado']['tmp_name'];
$tamarchivo = $_FILES['certificado']['size'];
$fp = fopen ($archivo, 'rb');
if ($fp){
	$certificado = fread ($fp, $tamarchivo);
}
fclose($fp);
$sqlInsertDisca = "INSERT INTO discapacitados VALUE(:nroafiliado,:nroorden,1,:fechaalta,:fechaemision,:fechavto,:certificado)";
if ($nroorden == 0) { 
	$sqlUpdateBene = "UPDATE titulares SET discapacidad = 1, certificadodiscapacidad = 1, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado";
} else {
	$sqlUpdateBene = "UPDATE familiares SET discapacidad = 1, certificadodiscapacidad = 1, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";
} 

$sqlInserTipo = array();
while ($dato = current($_POST)) {
    if (strpos(key($_POST), 'tipodisca') !== false) {
		$tipoDisca = $dato['descripcion'];
        $sqlInserTipo[$dato['iddiscapacidad']] = "INSERT INTO discapacidadbeneficiario VALUE($nroafiliado,$nroorden,$tipoDisca)";
    }
    next($_POST);
}

$completo = 1;
$fechacierre = $fecharegistro;
reset($_POST);
foreach($_POST as $dato) {
	if ($dato == '0') {
		$completo = 0;
		$fechacierre = '';
	}
}

$sqlInsertExpediente = "INSERT INTO discapacitadoexpendiente VALUE(:idexpediente,:nroafiliado,:nroorden,:pedidomedico,:presupuesto,:presupuestotransporte,:registrosss,:resolucionsnr,:titulo,:plantratamiento,:historia,:planillafim,:consentimientotratamiento,:consentimientotransporte,:constancia,:adaptaciones,:acta,:certificadodisca,:dependencia,:recibo,:seguro,:evolutivoprimer,:evolutivosegundo,:admision,:observacion,:completo,:fechacierre,:fecharegistro,:usuarioregistro,:fechamodif,:usuariomodif)";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$resInsertDisca = $dbh->prepare($sqlInsertDisca);
	$resInsertDisca->execute(array(':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden, ':fechaalta' => $fechaAlta, ':fechaemision' => $fechaEmision, ':fechavto' => $fechaVto, ':certificado' => $certificado));
	//echo($sqlInsertDisca."<br>");
	
	$resUpdateBene = $dbh->prepare($sqlUpdateBene);
	if ($nroorden == 0) { 
		$resUpdateBene->execute(array(':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':nroafiliado' => $nroafiliado));
	} else {
		$resUpdateBene->execute(array(':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden));
	}
	//echo($sqlUpdateBene."<br>");
	
	foreach($sqlInserTipo as $insertTipo) {
		$dbh->exec($insertTipo);
		//print($insertTipo."<br>");
	}

	$resInsertExpediente = $dbh->prepare($sqlInsertExpediente);
	$resInsertExpediente->execute(array(':idexpediente' => 'DEFAULT',':nroafiliado'=> $nroafiliado,':nroorden'=>$nroorden,
										':pedidomedico' => $_POST['pedidomedico'],':presupuesto' => $_POST['presupuesto'],
										':presupuestotransporte' => $_POST['presupuestotrasnporte'],':registrosss' => $_POST['registrosss'],
										':resolucionsnr' => $_POST['resolucionsnr'],':titulo' => $_POST['titulo'],
										':plantratamiento' => $_POST['plantratamiento'],
										':historia' => $_POST['historia'],':planillafim' => $_POST['planillafim'],
										':consentimientotratamiento' => $_POST['consentimientotratamiento'],
										':consentimientotransporte' => $_POST['consentimientotransporte'],':constancia' => $_POST['constancia'],
										':adaptaciones' => $_POST['adaptaciones'],':acta' => $_POST['acta'],
										':certificadodisca' => $_POST['certificadodisca'],':dependencia' => $_POST['dependencia'],
										':recibo' => $_POST['recibo'],':seguro' => $_POST['seguro'],
										':evolutivoprimer' => $_POST['evolutivoprimer'],':evolutivosegundo' => $_POST['evolutivosegundo'],':admision' => $_POST['admision'],
										':observacion' => $_POST['observacion'],
										':completo'=>$completo,':fechacierre' => $fechacierre,':fecharegistro' => $fecharegistro, 
										':usuarioregistro' => $usuarioregistro, ':fechamodif' => $fechamodificacion,':usuariomodif'=>$usuariomodificacion));
	//echo($sqlInsertExpediente."<br>");
	
	$dbh->commit();
	$pagina = "consultarDiscapacitado.php?nroafiliado=$nroafiliado&nroorden=$nroorden&activo=1";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>