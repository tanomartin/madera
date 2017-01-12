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

// maximo 2 MB
$maxSize = 2097152;
$tipoPermitido = "application/pdf";
$archivoOk = 0;

$nombre_archivo_sss=$_FILES["consultaSSS"]["name"]; //Nombre del archivo
$tipo_archivo_sss=$_FILES["consultaSSS"]["type"]; //Tipo de archivo
$tamano_archivo_sss=$_FILES["consultaSSS"]["size"]; //Tamano de archivo
$archivo_sss=$_FILES["consultaSSS"]["tmp_name"];

$error_sss = "";
if ($nombre_archivo_sss!="") {
	if ($tamano_archivo_sss <= $maxSize) {
		if ($tipo_archivo_sss==$tipoPermitido) {
			$fp = fopen($archivo_sss,"rb");
			$contenido_sss = fread($fp,$tamano_archivo_sss);
			//$contenido_sss = addslashes($contenido_sss);
			fclose($fp);
		}
		else {
			$archivoOk = 1;
			$error_sss = "Tipo de Archivo no permitido para la Consulta SSS. Solo se permiten tipo PDF";
		}
	}
	else {
		$archivoOk = 1;
		$error_sss = "El tamaño del archivo excede el máximo permitido. Máximo permitido 2 MB.";
	}
}
//echo($nombre_archivo_sss); echo "<br>";
//echo($tipo_archivo_sss); echo "<br>";
//echo($tamano_archivo_sss); echo "<br>";
//echo ($archivo_sss); echo "<br>";

//Conexion local y remota.
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$hostremoto = "localhost";
else
	$hostremoto = $hostOspim;
	
$dbremota = $baseOspimIntranet;
$hostlocal = $_SESSION['host'];
$dblocal = $_SESSION['dbname'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Guarda Verificacion :.</title>
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
</head>
<body bgcolor="#CCCCCC">
<?php
if($archivoOk==0) {
	//Creacion de transaccion.
	try {
		$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
		//echo 'Connected to database local<br/>';
		$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbl->beginTransaction();

		$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota",$usuarioOspim,$claveOspim);
		//echo 'Connected to database remota<br/>';
	    $dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbr->beginTransaction();
		
		$sqlActualizaAuto="UPDATE autorizaciones SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, usuarioverificacion = :usuarioverificacion, rechazoverificacion = :rechazoverificacion, fechaemailautoriza = :fechaemailautoriza, consultasssverificacion = :consultasssverificacion WHERE nrosolicitud = :nrosolicitud";
		//echo $sqlActualizaAuto; echo "<br>";
		$resultActualizaAuto = $dbl->prepare($sqlActualizaAuto);
		if($resultActualizaAuto->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':usuarioverificacion' => $usuveri, ':rechazoverificacion' => $recveri, ':fechaemailautoriza' => $fecveri, ':consultasssverificacion' => $contenido_sss, ':nrosolicitud' => $nrosoli)))
		{
			$sqlActualizaProcesadas="UPDATE autorizacionprocesada SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, rechazoverificacion = :rechazoverificacion WHERE nrosolicitud = :nrosolicitud";
			//echo $sqlActualizaProcesadas; echo "<br>";
			$resultActualizaProcesadas = $dbr->prepare($sqlActualizaProcesadas);
			if($resultActualizaProcesadas->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':rechazoverificacion' => $recveri, ':nrosolicitud' => $nrosoli)))
			{
			}
		}
		
		$username ="verificaciones@ospim.com.ar";
		$subject = "Aviso de Verificacion de Solicitud de Autorizacion";
		$bodymail ="<body><br><br>Este es un mensaje de Aviso.<br><br>La Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, correspondiente a la delegacion <strong>".$rowLeeSolicitud['codidelega']." - ".$rowLeeDeleg['nombre']."</strong> <br>ha sido verificada el dia ".$fechamail." a las ".$horamail.".<br><br><br><br />Verificaciones<br />Depto. de Afiliaciones<br />O.S.P.I.M.<br /></body>";
		$address = "autorizaciones@ospim.com.ar";
		$modulo = "Verificaciones";
		guardarEmail($username, $subject, $bodymail, $address, $modulo, null);
	
		$dbl->commit();
		$dbr->commit();

		$pagina = "listarSolicitudes.php";
		Header("Location: $pagina");
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		$dbl->rollback();
		$dbr->rollback();
	}
}
else { ?>
	<p>&nbsp;</p>
	<table width="769" border="1" align="center">
	<tr align="center" valign="top">
    <td width="769"><div align="center" class="Estilo1"><?php echo $error_sss;?></div></td>
	</tr>
	</table>
	<p>&nbsp;</p>
	<table width="769" border="1" align="center">
	<tr align="center" valign="top">
    <td width="769" valign="middle"><input type="reset" name="volver" value="Volver" onclick="location.href = 'listarSolicitudes.php'"/>
	</td>
	</tr>
	</table>
<?php
}
?>
</body>
</html>