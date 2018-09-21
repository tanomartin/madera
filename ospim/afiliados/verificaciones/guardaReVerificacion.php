<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."claves.php");
include($libPath."fechas.php");
include($libPath."bandejaSalida.php");
$nrosoli = $_POST['solicitud'];
$staveri = $_POST['veri'];
$recveri = "";
if($staveri==2) {
	$recveri = $_POST['motivoRechazo'];
}
$fecveri = date("Y-m-d H:m:s");
$usuveri = $_SESSION['usuario'];
$fechamail=date("d/m/Y");
$horamail=date("H:m");

$sqlLeeSolicitud="SELECT codidelega FROM autorizaciones WHERE nrosolicitud = $nrosoli";
$resultLeeSolicitud=mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud=mysql_fetch_array($resultLeeSolicitud);

$sqlLeeDeleg = "SELECT nombre FROM delegaciones WHERE codidelega = $rowLeeSolicitud[codidelega]";
$resultLeeDeleg = mysql_query($sqlLeeDeleg,$db); 
$rowLeeDeleg = mysql_fetch_array($resultLeeDeleg);

//Conexion local y Remota y creacion de transaccion.
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("poseidon",$maquina)==0)
	$hostremoto = $hostOspim;
else
	$hostremoto = "localhost";
	
$dbremota = $baseOspimIntranet;
$hostlocal = $_SESSION['host'];
$dblocal = $_SESSION['dbname'];

try {
	$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
	$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbl->beginTransaction();

	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota",$usuarioOspim,$claveOspim);
    $dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbr->beginTransaction();
		
	$sqlActualizaAuto = "UPDATE autorizaciones SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, usuarioverificacion = :usuarioverificacion, rechazoverificacion = :rechazoverificacion WHERE nrosolicitud = :nrosolicitud";
	$resActualizaAuto = $dbl->prepare($sqlActualizaAuto);
	
	if($resActualizaAuto->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':usuarioverificacion' => $usuveri, ':rechazoverificacion' => $recveri, ':nrosolicitud' => $nrosoli))) {
		$sqlActualizaAuto = "UPDATE autorizaciones SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, usuarioverificacion = :usuarioverificacion, rechazoverificacion = :rechazoverificacion WHERE nrosolicitud = :nrosolicitud";
		$resActualizaAuto = $dbl->prepare($sqlActualizaAuto);
		if($resActualizaAuto->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':usuarioverificacion' => $usuveri, ':rechazoverificacion' => $recveri, ':nrosolicitud' => $nrosoli))) {
			$sqlActualizaAutoAte = "UPDATE autorizacionesatendidas SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, usuarioverificacion = :usuarioverificacion, rechazoverificacion = :rechazoverificacion WHERE nrosolicitud = :nrosolicitud";
			$resActualizaAutoAte = $dbl->prepare($sqlActualizaAutoAte);
			if($resActualizaAutoAte->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':usuarioverificacion' => $usuveri, ':rechazoverificacion' => $recveri, ':nrosolicitud' => $nrosoli))) {	
				$sqlActualizaProcesadas="UPDATE autorizacionprocesada SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, rechazoverificacion = :rechazoverificacion WHERE nrosolicitud = :nrosolicitud";
				$resultActualizaProcesadas = $dbr->prepare($sqlActualizaProcesadas);
				if($resultActualizaProcesadas->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':rechazoverificacion' => $recveri, ':nrosolicitud' => $nrosoli))) { }
			}
		}
	}
	
	$dbl->commit();
	$dbr->commit();

	$pagina = "listarSolicitudes.php";
	Header("Location: $pagina");		
}
catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbl->rollback();
	$dbr->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>
