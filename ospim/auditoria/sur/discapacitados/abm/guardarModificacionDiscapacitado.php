<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

//var_dump($_GET);
//var_dump($_POST);
$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];
$cuil = $_GET['cuil'];
$idexpediente = $_GET['idexpediente'];
$fechaAlta = fechaParaGuardar($_POST['fechaAlta']);
$fechaEmision = fechaParaGuardar($_POST['fechaInicio']);
$fechaVto = fechaParaGuardar($_POST['fechaFin']);
$codigoCert = strtoupper($_POST['codigocertificado']);
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

if ($nroorden == 0) {
	$sqlTitular = "SELECT informesss, tipoinformesss, fechainformesss, usuarioinformesss FROM titulares WHERE nroafiliado = '$nroafiliado'";
	$resTitular = mysql_query($sqlTitular,$db);
	$rowTitular = mysql_fetch_array($resTitular);
	
	if($rowTitular['informesss'] == 0) {
		$informesss = 1;
		$tipoinformesss = "M";
	} else {
		$informesss = $rowTitular['informesss'];
		$tipoinformesss = $rowTitular['tipoinformesss'];
	}
} else {
	$sqlFamilia = "SELECT informesss, tipoinformesss, fechainformesss, usuarioinformesss FROM familiares WHERE nroafiliado = '$nroafiliado' and nroorden = '$nroorden'";
	$resFamilia = mysql_query($sqlFamilia,$db);
	$rowFamilia = mysql_fetch_array($resFamilia);
	
	if($rowFamilia['informesss'] == 0) {
		$informesss = 1;
		$tipoinformesss = "M";
	} else {
		$informesss = $rowFamilia['informesss'];
		$tipoinformesss = $rowFamilia['tipoinformesss'];
	}
	
}


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
	$sqlUpdateDisca = "UPDATE discapacitados SET cuil = :cuil, fechaalta = :fechaalta, emisioncertificado = :fechaemision, vencimientocertificado = :fechavto, codigocertificado = :codigocertificado, documentocertificado = :certificado WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";
	if ($nroorden == 0) { 
		$sqlUpdateBene = "UPDATE titulares SET informesss = :informesss, tipoinformesss = :tipoinformesss, certificadodiscapacidad = 1, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado";
	} else {
		$sqlUpdateBene = "UPDATE familiares SET informesss = :informesss, tipoinformesss = :tipoinformesss, certificadodiscapacidad = 1, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";
	}	
} else {
	$sqlUpdateDisca = "UPDATE discapacitados SET cuil = :cuil, fechaalta = :fechaalta, emisioncertificado = :fechaemision, vencimientocertificado = :fechavto, codigocertificado = :codigocertificado WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";
	if ($nroorden == 0) {
		$sqlUpdateBene = "UPDATE titulares SET informesss = :informesss, tipoinformesss = :tipoinformesss, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado";
	} else {
		$sqlUpdateBene = "UPDATE familiares SET informesss = :informesss, tipoinformesss = :tipoinformesss, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";
	}
}

$sqlDeletTipo = "DELETE FROM discapacidadbeneficiario WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";

$sqlInserTipo = array();
while ($dato = current($_POST)) {
    if (strpos(key($_POST), 'tipodisca') !== false) {
		$tipoDisca = $dato['descripcion'];
        $sqlInserTipo[$dato['iddiscapacidad']] = "INSERT INTO discapacidadbeneficiario VALUE($nroafiliado,$nroorden,$tipoDisca)";
    }
    next($_POST);
}

$completo = 1;
$fechacierre = $fechamodificacion;
reset($_POST);
foreach($_POST as $dato) {
	if ($dato == '0') {
		$completo = 0;
		$fechacierre = '';
	}
}

$sqlUpdateExpediente = "UPDATE discapacitadoexpendiente 
							SET pedidomedico = :pedidomedico, presupuesto = :presupuesto, presupuestotransporte = :presupuestotransporte, 
								registrosss = :registrosss, resolucionsnr = :resolucionsnr, titulo = :titulo, plantratamiento = :plantratamiento, 
								resumenhistoria = :historia, planillafim = :planillafim, 
								consentimientotratamiento = :consentimientotratamiento, consentimientotransporte = :consentimientotransporte, 
								constanciaalumno = :constancia, adaptaciones = :adaptaciones, actaacuerdo = :acta, 
								certificadodiscapacidad = :certificadodisca, dependencia = :dependencia, recibosueldo = :recibo, 
								segurodesempleo = :seguro, evolutivoprimer = :evolutivoprimer, evolutivosegundo = :evolutivosegundo, admision = :admision,
								observaciones = :observacion, completo = :completo, fechacierre = :fechacierre, fechamodificacion = :fechamodif, 
								usuariomodificacion = :usuariomodif 
							WHERE idexpediente = :idexpediente";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$resUpdateDisca = $dbh->prepare($sqlUpdateDisca);
	if ($archivo != '') {
		$resUpdateDisca->execute(array(':cuil' => $cuil, ':fechaalta' => $fechaAlta, ':fechaemision' => $fechaEmision, ':fechavto' => $fechaVto, ':codigocertificado' => $codigoCert, ':certificado' => $certificado, ':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden ));
	} else {
		$resUpdateDisca->execute(array(':cuil' => $cuil, ':fechaalta' => $fechaAlta, ':fechaemision' => $fechaEmision, ':fechavto' => $fechaVto, ':codigocertificado' => $codigoCert, ':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden ));
	}
	
	$resUpdateBene = $dbh->prepare($sqlUpdateBene);
	if ($nroorden == 0) {
		$resUpdateBene->execute(array(':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion,':nroafiliado' => $nroafiliado));
	} else {
		$resUpdateBene->execute(array(':informesss' => $informesss, ':tipoinformesss' => $tipoinformesss, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion,':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden));
	}
	
	$resDeleteTipo = $dbh->prepare($sqlDeletTipo);
	$resDeleteTipo->execute(array(':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden));
	//echo($sqlDeletTipo."<br>");

	foreach($sqlInserTipo as $insertTipo) {
		$dbh->exec($insertTipo);
		//echo($insertTipo."<br>");
	}
	
	$resUpdateExpediente = $dbh->prepare($sqlUpdateExpediente);
	$resUpdateExpediente->execute(array(':pedidomedico' => $_POST['pedidomedico'],':presupuesto' => $_POST['presupuesto'],':presupuestotransporte' => $_POST['presupuestotrasnporte'],':registrosss' => $_POST['registrosss'],':resolucionsnr' => $_POST['resolucionsnr'],':titulo' => $_POST['titulo'],':plantratamiento' => $_POST['plantratamiento'],':historia' => $_POST['historia'],':planillafim' => $_POST['planillafim'],':consentimientotratamiento' => $_POST['consentimientotratamiento'],':consentimientotransporte' => $_POST['consentimientotransporte'],':constancia' => $_POST['constancia'],':adaptaciones' => $_POST['adaptaciones'],':acta' => $_POST['acta'],':certificadodisca' => $_POST['certificadodisca'],':dependencia' => $_POST['dependencia'],':recibo' => $_POST['recibo'],
										':seguro' => $_POST['seguro'],
										':evolutivoprimer' => $_POST['evolutivoprimer'],':evolutivosegundo' => $_POST['evolutivosegundo'],':admision' => $_POST['admision'],
										':observacion' => $_POST['observacion'],':completo'=>$completo,':fechacierre' => $fechacierre,':fechamodif' => $fechamodificacion,':usuariomodif'=>$usuariomodificacion,':idexpediente' => $idexpediente));
	//echo($sqlUpdateExpediente."<br>");

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