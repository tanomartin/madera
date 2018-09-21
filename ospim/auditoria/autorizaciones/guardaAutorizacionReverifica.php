<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."claves.php");
include($libPath."fechas.php");
include($libPath."bandejaSalida.php");

$nrosoli = $_POST['solicitud'];
if (isset($_COOKIE[$nrosoli])) {
    setcookie($nrosoli, $_SESSION['usuario'], time() - 3600); 
}

$staauto = $_POST['autori'];
$recauto = $_POST['motivoRechazo'];
$delegacion = explode($_POST['delegacion']);

if($staauto==1) {
	$staauto = 3;
}
if($staauto==2) {
	$estauto = "Rechazada";
	$apeauto = "";
	$apefech = "";
	$presauto = "";
	$presmail = "";
	$presfech = "";	
	$patoauto = "";
	$montauto = "0.00";
}
$fecauto = date("Y-m-d H:m:s");
$usuauto = $_SESSION['usuario'];
$fechamail=date("d/m/Y");
$horamail=date("H:m");

//Conexion local y remota.
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("poseidon",$maquina)==0)
	$hostremoto = $hostOspim;
else
	$hostremoto = "localhost";

$dbremota = $baseOspimIntranet;
$hostlocal = $_SESSION['host'];
$dblocal = $_SESSION['dbname'];


if($staauto == 3) {
	try {
		$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
		$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbl->beginTransaction();

		$sqlActualizaAuto="UPDATE autorizaciones SET statusverificacion = :statusverificacion, fechapidereverificacion = :fechapidereverificacion, usuariopidereverificacion = :usuariopidereverificacion, motivopidereverificacion = :motivopidereverificacion WHERE nrosolicitud = :nrosolicitud";
		$resultActualizaAuto = $dbl->prepare($sqlActualizaAuto);
		if($resultActualizaAuto->execute(array(':statusverificacion' => $staauto, ':fechapidereverificacion' => $fecauto, ':usuariopidereverificacion' => $usuauto,':motivopidereverificacion' => $recauto, ':nrosolicitud' => $nrosoli))) { }

		$dbl->commit();
		$pagina = "listarSolicitudes.php";
		Header("Location: $pagina");
	} catch (PDOException $e) {
		$error = $e->getMessage();
		$dbl->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
}

if($staauto == 2) {
	try {
		$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
		$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbl->beginTransaction();

		$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota","sistem22_charly","bsdf5762");
	    $dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbr->beginTransaction();
		
		$sqlActualizaAuto = "UPDATE autorizacionesatendidas SET statusautorizacion = :statusautorizacion, fechaautorizacion = :fechaautorizacion, usuarioautorizacion = :usuarioautorizacion, clasificacionape = :clasificacionape, fechaemailape = :fechaemailape, rechazoautorizacion = :rechazoautorizacion, fechaemaildelega = :fechaemaildelega, emailprestador = :emailprestador, fechaemailprestador = :fechaemailprestador, patologia = :patologia, montoautorizacion = :montoautorizacion WHERE nrosolicitud = :nrosolicitud";
		$resActualizaAuto = $dbl->prepare($sqlActualizaAuto);
		if($resActualizaAuto->execute(array(':statusautorizacion' => $staauto, ':fechaautorizacion' => $fecauto, ':usuarioautorizacion' => $usuauto, ':clasificacionape' => $apeauto, ':fechaemailape' => $apefech, ':rechazoautorizacion' => $recauto, ':fechaemaildelega' => $fecauto, ':emailprestador' => $presmail, ':fechaemailprestador' => $presfech, ':patologia' => $patoauto, ':montoautorizacion' => $montauto, ':nrosolicitud' => $nrosoli))) {
			$sqlActualizaProcesadas = "UPDATE autorizacionprocesada SET statusautorizacion = :statusautorizacion, fechaautorizacion = :fechaautorizacion, rechazoautorizacion = :rechazoautorizacion WHERE nrosolicitud = :nrosolicitud";
			$resActualizaProcesadas = $dbr->prepare($sqlActualizaProcesadas);
			if($resActualizaProcesadas->execute(array(':statusautorizacion' => $staauto, ':fechaautorizacion' => $fecauto, ':rechazoautorizacion' => $recauto, ':nrosolicitud' => $nrosoli))) { 
				$sqlDeleteAuto = "DELETE FROM autorizaciones WHERE nrosolicitud = :nrosolicitud";
				$resDeleteAuto = $dbl->prepare($sqlDeleteAuto);
				if ($resDeleteAuto->execute(array(':nrosolicitud' => $nrosoli))) {}
			}
		}
	
		$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Su Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
		$bodymail.="<br>Verifique la situacion de la solicitud a traves del modulo INTRANET DELEGACIONES.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";
		$username="autorizaciones@ospim.com.ar";
		$subject="AVISO: Solicitud de Autorizacion Atendida";
		$address = "autorizaciones".$rowLeeSolicitud['codidelega']."@ospim.com.ar";
		$modulo = "Autorizaciones";
		$idMailDelgaRechazo = guardarEmail($username, $subject, $bodymail, $address, $modulo, null);
		if ($idMailDelgaRechazo == -1) {
			throw new PDOException('Error al intentar guardar el correo electronico');
		} else {
			$sqlInsertAutoMail = "INSERT INTO autorizacionesemail VALUES(:nrosolicitud,:idmail)";
			$resInsertAutoMail = $dbl->prepare($sqlInsertAutoMail);
			$resInsertAutoMail->execute(array(':nrosolicitud' => $nrosoli, ':idmail' => $idMailDelgaRechazo));
		}
		
		$dbl->commit();
		$dbr->commit();
		
		$pagina = "listarSolicitudes.php";
		Header("Location: $pagina");
	} catch (PDOException $e) {
		$error = $e->getMessage();
		$dbl->rollback();
		$dbr->rollback();		
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
}
?>