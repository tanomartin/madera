<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require_once($libPath."PHPMailer_5.2.2/class.phpmailer.php");
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

//conexion y creacion de transaccion.
try {
	$hostlocal = $_SESSION['host'];
	$dblocal = $_SESSION['dbname'];
	//echo "$hostlocal"; echo "<br>";
	//echo "$dblocal"; echo "<br>";
	$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database local<br/>';
	$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbl->beginTransaction();
		
	$hostremoto = "ospim.com.ar";
	$dbremota = "sistem22_intranet";
	//echo "$hostremoto"; echo "<br>";
	//echo "$dbremota"; echo "<br>";
	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota","sistem22_charly","bsdf5762");
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
	
	$dbl->commit();
	$dbr->commit();

	$mail=new PHPMailer();
	$body="<body><br><br>Este es un mensaje de Aviso.<br><br>Ante el pedido de Reverificacion de la Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, correspondiente a la delegacion <strong>".$rowLeeSolicitud['codidelega']." - ".$rowLeeDeleg['nombre']."</strong>, <br>informamos que la misma ha sido procesada el dia ".$fechamail." a las ".$horamail.".<br><br><br><br />Verificaciones<br />Depto. de Afiliaciones<br />O.S.P.I.M.<br /></body>";
	$mail->IsSMTP();							// telling the class to use SMTP
	$mail->Host="smtp.ospim.com.ar"; 			// SMTP server
	$mail->SMTPAuth=true;						// enable SMTP authentication
	$mail->Host="smtp.ospim.com.ar";			// sets the SMTP server
	$mail->Port=25;								// set the SMTP port for the GMAIL server
	$mail->Username="verificaciones@ospim.com.ar";	// SMTP account username
	$mail->Password="yebu8691";					// SMTP account password
	$mail->SetFrom('verificaciones@ospim.com.ar', 'Verificaciones OSPIM');
	$mail->AddReplyTo("verificaciones@ospim.com.ar","Verificaciones OSPIM");
	$mail->Subject="Aviso de Reverificacion de Solicitud de Autorizacion";
	$mail->AltBody="Para ver este mensaje, por favor use un lector de correo compatible con HTML!"; // optional, comment out and test
	$mail->MsgHTML($body);
	$address = "autorizaciones@ospim.com.ar";
//	$mail->AddAddress($address, "Autorizaciones OSPIM");
	$mail->AddAddress($address, "");
	$mail->Send();

	$pagina = "listarSolicitudes.php";
	Header("Location: $pagina");
}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbl->rollback();
	$dbr->rollback();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Guarda Reverificacion :.</title></head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
}
</style>
<body bgcolor="#CCCCCC">
</body>
</html>