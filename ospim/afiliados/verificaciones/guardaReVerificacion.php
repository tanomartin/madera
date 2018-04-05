<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."claves.php");
include($libPath."fechas.php");
include($libPath."bandejaSalida.php");

$datos = array_values($_POST);
//echo "DATOS 0: "; echo $datos[0]; echo "<br>";
$nrosoli = $datos[0];
//echo "DATOS 1: "; echo $datos[1]; echo "<br>";
$staveri = $datos[1];
//echo "DATOS 2: "; echo $datos[2]; echo "<br>";
if($staveri==2)
	$recveri = $datos[2];
else
	$recveri = "";
//echo "DATOS 3: "; echo $datos[3]; echo "<br>";
///echo "DATOS 4: "; echo $datos[4]; echo "<br>";
//echo "DATOS 5: "; echo $datos[5]; echo "<br>";
//echo "DATOS 6: "; echo $datos[6]; echo "<br>";
$fecveri = date("Y-m-d H:m:s");
//echo "FECHA REGISTRO: "; echo $fecveri; echo "<br>";
$usuveri = $_SESSION['usuario'];
//echo "USUARIO REGISTRO: "; echo $usuveri; echo "<br>";
$fechamail=date("d/m/Y");
$horamail=date("H:m");

$sqlLeeSolicitud="SELECT codidelega FROM autorizaciones where nrosolicitud = $nrosoli";
$resultLeeSolicitud=mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud=mysql_fetch_array($resultLeeSolicitud);

$sqlLeeDeleg = "SELECT nombre FROM delegaciones where codidelega = $rowLeeSolicitud[codidelega]";
$resultLeeDeleg = mysql_query($sqlLeeDeleg,$db); 
$rowLeeDeleg = mysql_fetch_array($resultLeeDeleg);

//Conexion local y Remota y creacion de transaccion.
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$hostremoto = "localhost";
else
	$hostremoto = $hostOspim;
	
$dbremota = $baseOspimIntranet;
$hostlocal = $_SESSION['host'];
$dblocal = $_SESSION['dbname'];

try {
	$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database local<br/>';
	$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbl->beginTransaction();

	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota",$usuarioOspim,$claveOspim);
	//echo 'Connected to database remota<br/>';
    $dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbr->beginTransaction();
		
	$sqlActualizaAuto="UPDATE autorizaciones SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, usuarioverificacion = :usuarioverificacion, rechazoverificacion = :rechazoverificacion, fechaemailautoriza = :fechaemailautoriza WHERE nrosolicitud = :nrosolicitud";
	//echo $sqlActualizaAuto; echo "<br>";
	$resultActualizaAuto = $dbl->prepare($sqlActualizaAuto);
	if($resultActualizaAuto->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':usuarioverificacion' => $usuveri, ':rechazoverificacion' => $recveri, ':fechaemailautoriza' => $fecveri, ':nrosolicitud' => $nrosoli)))
	{
		$sqlActualizaProcesadas="UPDATE autorizacionprocesada SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, rechazoverificacion = :rechazoverificacion WHERE nrosolicitud = :nrosolicitud";
		//echo $sqlActualizaProcesadas; echo "<br>";
		$resultActualizaProcesadas = $dbr->prepare($sqlActualizaProcesadas);
		if($resultActualizaProcesadas->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':rechazoverificacion' => $recveri, ':nrosolicitud' => $nrosoli)))
		{
		}
	}
	
	$username ="verificaciones@ospim.com.ar";
	$subject = "Aviso de Reverificacion de Solicitud de Autorizacion";
	$bodymail ="<body><br><br>Este es un mensaje de Aviso.<br><br>Ante el pedido de Reverificacion de la Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, correspondiente a la delegacion <strong>".$rowLeeSolicitud['codidelega']." - ".$rowLeeDeleg['nombre']."</strong>, <br>informamos que la misma ha sido procesada el dia ".$fechamail." a las ".$horamail.".<br><br><br><br />Verificaciones<br />Depto. de Afiliaciones<br />O.S.P.I.M.<br /></body>";
	$address = "autorizaciones@ospim.com.ar";
	$modulo = "Verificaciones";
	if (guardarEmail($username, $subject, $bodymail, $address, $modulo, null) == -1) {
		throw new PDOException('Error al intentar guardar el correo electronico');
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
