<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require_once($libPath."PHPMailer_5.2.2/class.phpmailer.php");
$datos = array_values($_POST);

$nrosoli = $datos[0];
//$staauto = $datos[1];
if($datos[1]=="on")
{
	echo "SI Viene presupuesto"; echo "<br>";
	$staauto = $datos[2];	
}
else
{
	echo "NO viene presupuesto"; echo "<br>";
	$staauto = $datos[1];
}

$fecauto = date("Y-m-d H:m:s");
$usuauto = $_SESSION['usuario'];

if($staauto==2)
{
	$estauto = "Rechazada";
	$recauto = $datos[2];
	$apeauto = "";
	$apefech = "";
	$presauto = "";
	$presmail = "";
	$presfech = "";	
	$montauto = "0.00";
}
else
{
	$estauto = "Aprobada";
	$recauto = "";

	if($datos[1]=="on")
		$apeauto = $datos[3];
	else
		$apeauto = $datos[2];

	if($apeauto==1)
		$apefech = date("Y-m-d H:m:s");
	else
		$apefech = "";

	if($datos[1]=="on")
		$presauto = $datos[4];
	else
		$presauto = $datos[3];

	if($presauto==1)
	{
		if($datos[1]=="on")
		{
			$presmail = $datos[5];
			$presfech =  date("Y-m-d H:m:s");
			$montauto = $datos[6];
		}
		else
		{
			$presmail = $datos[4];
			$presfech =  date("Y-m-d H:m:s");
			$montauto = $datos[5];
		}
	}
	else
	{
		$presmail = "";
		$presfech = "";	
		if($datos[1]=="on")
			$montauto = $datos[5];
		else
			$montauto = $datos[4];
	}
}
echo "Nro Solicitud: "; echo $nrosoli; echo "<br>";
echo "Autorizacion: "; echo $staauto; echo "<br>";
echo "Estado: "; echo $estauto; echo "<br>";
echo "Rechazo: "; echo $recauto; echo "<br>";
echo "APE: "; echo $apeauto; echo "<br>";
echo "Fecha Mail APE: "; echo $apefech; echo "<br>";
echo "Prestador: "; echo $presauto; echo "<br>";
echo "Mail Prestador: "; echo $presmail; echo "<br>";
echo "Fecha Mail Prestador: "; echo $presfech; echo "<br>";
echo "Monto Autorizado: "; echo $montauto; echo "<br>";

$fechamail=date("d/m/Y");
$horamail=date("H:m");

$sqlLeeSolicitud="SELECT codidelega FROM autorizaciones where nrosolicitud = $nrosoli";
$resultLeeSolicitud=mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud=mysql_fetch_array($resultLeeSolicitud);

$sqlLeeDeleg = "SELECT nombre FROM delegaciones where codidelega = $rowLeeSolicitud[codidelega]";
$resultLeeDeleg = mysql_query($sqlLeeDeleg,$db); 
$rowLeeDeleg = mysql_fetch_array($resultLeeDeleg);

//conexion y creacion de transaccion.
//try {
//	$hostlocal = $_SESSION['host'];
//	$dblocal = $_SESSION['dbname'];
	//echo "$hostlocal"; echo "<br>";
	//echo "$dblocal"; echo "<br>";
//	$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database local<br/>';
//	$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//	$dbl->beginTransaction();

//	$hostremoto = "ospim.com.ar";
//	$dbremota = "uv0471_intranet";
	//echo "$hostremoto"; echo "<br>";
	//echo "$dbremota"; echo "<br>";
//	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota","uv0471","bsdf5762");
	//echo 'Connected to database remota<br/>';
//	$dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//	$dbr->beginTransaction();
		
//	$sqlActualizaAuto="UPDATE autorizaciones SET statusautorizacion = :statusautorizacion, fechaautorizacion = :fechaautorizacion, usuarioautorizacion = :usuarioautorizacion, clasificacionape = :clasificacionape, fechaemailape = :fechaemailape, rechazoautorizacion = :rechazoautorizacion, fechaemaildelega = :fechaemaildelega, emailprestador = :emailprestador, fechaemailprestador = :fechaemailprestador, montoautorizacion = :montoautorizacion WHERE nrosolicitud = :nrosolicitud";
		//echo $sqlActualizaAuto; echo "<br>";
//	$resultActualizaAuto = $dbl->prepare($sqlActualizaAuto);
//	if($resultActualizaAuto->execute(array(':statusautorizacion' => $staauto, ':fechaautorizacion' => $fecauto, ':usuarioautorizacion' => $usuauto, ':clasificacionape' => $apeauto, ':fechaemailape' => $apefech, ':rechazoautorizacion' => $recauto, ':fechaemaildelega' => $fecauto, ':emailprestador' => $presmail, ':fechaemailprestador' => $presfech, ':montoautorizacion' => $montauto, ':nrosolicitud' => $nrosoli)))
//	{
//		$sqlActualizaProcesadas="UPDATE autorizacionprocesada SET statusautorizacion = :statusautorizacion, fechaautorizacion = :fechaautorizacion, rechazoautorizacion = :rechazoautorizacion WHERE nrosolicitud = :nrosolicitud";
			//echo $sqlActualizaProcesadas; echo "<br>";
//		$resultActualizaProcesadas = $dbr->prepare($sqlActualizaProcesadas);
//		if($resultActualizaProcesadas->execute(array(':statusautorizacion' => $staauto, ':fechaautorizacion' => $fecauto, ':rechazoautorizacion' => $recauto, ':nrosolicitud' => $nrosoli)))
//		{
//		}
//	}

//	$dbl->commit();
//	$dbr->commit();

//	$mail=new PHPMailer();
	//$body="<body><br><br>Este es un mensaje de Aviso.<br><br>Su Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".<br>Verifique la situacion de la solicitud a traves del modulo INTRANET DELEGACIONES.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";
	
//	$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Su Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
//	if($staauto==2)
//	{	
//		$bodymail.="<br>Verifique la situacion de la solicitud a traves del modulo INTRANET DELEGACIONES.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";
//	}
//	else
//	{
//		$bodymail.="<br>Se envia adjunto documento PDF con los detalles de la Autorizacion.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";
//	}
//	$mail->IsSMTP();							// telling the class to use SMTP
//	$mail->Host="smtp.ospim.com.ar"; 			// SMTP server
//	$mail->SMTPAuth=true;						// enable SMTP authentication
//	$mail->Host="smtp.ospim.com.ar";			// sets the SMTP server
//	$mail->Port=25;								// set the SMTP port for the GMAIL server
//	$mail->Username="jcbolognese@ospim.com.ar";	// SMTP account username
//	$mail->Password="256512";					// SMTP account password
//	$mail->SetFrom("jcbolognese@ospim.com.ar", "Autorizaciones OSPIM");
//	$mail->AddReplyTo("jcbolognese@ospim.com.ar","Cozzi OSPIM");
//	$mail->Subject="AVISO!!! Solicitud de Autorizacion Atendida";
//	$mail->AltBody="Para ver este mensaje, por favor use un lector de correo compatible con HTML!"; // optional, comment out and test
//	$mail->MsgHTML($bodymail);
//	$address = "jcbolognese@ospim.com.ar";
//	$nameto = "Autorizaciones ".$rowLeeSolicitud['codidelega']." - ".$rowLeeDeleg['nombre'];
//	$mail->AddAddress($address, $nameto);
//	$mail->AddBCC("jcbolognese@usimra.com.ar", "Autorizaciones OSPIM");
//	$mail->Send();

//	if($apeauto==1)
//	{
//		TODO: Envia mail al departamento APE para comunicar que se trata de una autorizacion que incluye prestaciones APE
//		$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Su Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
//		$mail->Subject="AVISO!!! Autorizacion Aprobada incluye prestaciones APE";
//		$mail->MsgHTML($bodymail);
//		$address = "jcbolognese@ospim.com.ar";
//		$nameto = "APE OSPIM";
//		$mail->AddAddress($address, $nameto);
//	}

//	if($presauto==1)
//	{
//		TODO: Envia mail al prestador para avisarle que hay una prestacion autorizada
//		$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Su Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
//		$mail->Subject="AVISO!!! Autorizacion Aprobada incluye prestaciones APE";
//		$mail->MsgHTML($bodymail);
//		$address = $presmail;
//		$nameto = "Prestador OSPIM";
//		$mail->AddAddress($address, $nameto);
//	}

//	$pagina = "listarSolicitudes.php";
//	Header("Location: $pagina");
//}
//catch (PDOException $e) {
//	echo $e->getMessage();
//	$dbl->rollback();
//	$dbr->rollback();
//}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Guarda Autorizacion :.</title></head>
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