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

// maximo 2 MB
$maxSize = 2097152;
$tipoPermitido = "application/pdf";
$nombre_archivo_sss=$_FILES["consultaSSS"]["name"]; //Nombre del archivo
$tipo_archivo_sss=$_FILES["consultaSSS"]["type"]; //Tipo de archivo
$tamano_archivo_sss=$_FILES["consultaSSS"]["size"]; //Tamano de archivo
$archivo_sss=$_FILES["consultaSSS"]["tmp_name"];

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("poseidon",$maquina)==0)
	$hostremoto = $hostOspim;
else
	$hostremoto = "localhost";

try {
	$dbremota = $baseOspimIntranet;
	$hostlocal = $_SESSION['host'];
	$dblocal = $_SESSION['dbname'];
	
	$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
	$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbl->beginTransaction();
		
	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota",$usuarioOspim,$claveOspim);
	$dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbr->beginTransaction();
	
	if ($nombre_archivo_sss != "") {
		if ($tamano_archivo_sss <= $maxSize) {
			if ($tipo_archivo_sss == $tipoPermitido) {
				$fp = fopen($archivo_sss,"rb");
				$contenido_sss = fread($fp,$tamano_archivo_sss);
				fclose($fp);
			} else {
				throw new PDOException("Tipo de Archivo no permitido para la Consulta SSS. Solo se permiten tipo PDF");
			}
		} else {
			throw new PDOException("El tamaño del archivo excede el máximo permitido. Máximo permitido 2 MB");
		}
	}
				
	$sqlActualizaAuto = "UPDATE autorizaciones SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, usuarioverificacion = :usuarioverificacion, rechazoverificacion = :rechazoverificacion WHERE nrosolicitud = :nrosolicitud";
	$resActualizaAuto = $dbl->prepare($sqlActualizaAuto);
	if($resActualizaAuto->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':usuarioverificacion' => $usuveri, ':rechazoverificacion' => $recveri, ':nrosolicitud' => $nrosoli))) {
		$sqlActualizaAutoSSS ="UPDATE autorizacionesdocoriginales SET consultasssverificacion = :consultasssverificacion WHERE nrosolicitud = :nrosolicitud";
		$resActualizaAutoSSS = $dbl->prepare($sqlActualizaAutoSSS);
		if($resActualizaAutoSSS->execute(array(':consultasssverificacion' => $contenido_sss, ':nrosolicitud' => $nrosoli))) {
			$sqlActualizaAutoAtendida = "UPDATE autorizacionesatendidas SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, usuarioverificacion = :usuarioverificacion, rechazoverificacion = :rechazoverificacion WHERE nrosolicitud = :nrosolicitud";
			$resActualizaAutoAtendida = $dbl->prepare($sqlActualizaAutoAtendida);
			if($resActualizaAutoAtendida->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':usuarioverificacion' => $usuveri, ':rechazoverificacion' => $recveri, ':nrosolicitud' => $nrosoli))) {
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
	
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbl->rollback();
	$dbr->rollback();
	//$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	//header ($redire);
	exit(0);
} ?>
