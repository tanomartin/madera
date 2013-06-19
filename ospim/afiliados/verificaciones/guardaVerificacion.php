<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
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

if($archivoOk==0) {
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
		$dbremota = "uv0471_intranet";
		//echo "$hostremoto"; echo "<br>";
		//echo "$dbremota"; echo "<br>";
		$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota","uv0471","bsdf5762");
		//echo 'Connected to database remota<br/>';
	    $dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbr->beginTransaction();
		
		$sqlActualizaAuto="UPDATE autorizaciones SET statusverificacion = :statusverificacion, fechaverificacion = :fechaverificacion, usuarioverificacion = :usuarioverificacion, rechazoverificacion = :rechazoverificacion, consultasssverificacion = :consultasssverificacion WHERE nrosolicitud = :nrosolicitud";
		//echo $sqlActualizaAuto; echo "<br>";
		$resultActualizaAuto = $dbl->prepare($sqlActualizaAuto);
		if($resultActualizaAuto->execute(array(':statusverificacion' => $staveri, ':fechaverificacion' => $fecveri, ':usuarioverificacion' => $usuveri, ':rechazoverificacion' => $recveri, ':consultasssverificacion' => $contenido_sss, ':nrosolicitud' => $nrosoli)))
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
		$pagina = "listarSolicitudes.php";
		Header("Location: $pagina");
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		$dbl->rollback();
		$dbr->rollback();
	}
}
else
{ ?>
	<p>&nbsp;</p>
	<table width="769" border="1" align="center">
	<tr align="center" valign="top">
    <td width="769"><div align="center" class="Estilo1"><?php echo $error_sss;?></div></td>
	</tr>
	</table>
	<p>&nbsp;</p>
	<table width="769" border="1" align="center">
	<tr align="center" valign="top">
    <td width="769" valign="middle"><input type="reset" name="volver" value="Volver" onClick="location.href = 'listarSolicitudes.php'" align="center"/>
	</td>
	</tr>
	</table>
<?php
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Guarda Verificacion :.</title></head>
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