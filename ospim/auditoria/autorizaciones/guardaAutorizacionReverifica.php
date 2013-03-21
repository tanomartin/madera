<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");
$datos = array_values($_POST);
//echo "DATOS 0: "; echo $datos[0]; echo "<br>";
$nrosoli = $datos[0];
//echo "DATOS 1: "; echo $datos[1]; echo "<br>";
$staauto = $datos[1];
if($staauto == 1)
	$staauto = 3;
$recauto = $datos[2];
//echo "DATOS 2: "; echo $datos[2]; echo "<br>";
//echo "DATOS 3: "; echo $datos[3]; echo "<br>";
//echo "DATOS 4: "; echo $datos[4]; echo "<br>";
//echo "DATOS 5: "; echo $datos[5]; echo "<br>";
//echo "DATOS 6: "; echo $datos[6]; echo "<br>";
$fecauto = date("Y-m-d H:m:s");
//echo "FECHA REGISTRO: "; echo $fecveri; echo "<br>";
$usuauto = $_SESSION['usuario'];
//echo "USUARIO REGISTRO: "; echo $usuveri; echo "<br>";


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
		
	$sqlActualizaAuto="UPDATE autorizaciones SET statusverificacion = :statusverificacion, motivopidereverificacion = :motivopidereverificacion WHERE nrosolicitud = :nrosolicitud";
	//echo $sqlActualizaAuto; echo "<br>";
	$resultActualizaAuto = $dbl->prepare($sqlActualizaAuto);
	if($resultActualizaAuto->execute(array(':statusverificacion' => $staauto, ':motivopidereverificacion' => $recauto, ':nrosolicitud' => $nrosoli)))
	{
	}
	
	$dbl->commit();
	$pagina = "listarSolicitudes.php";
	Header("Location: $pagina");
}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbl->rollback();
}
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