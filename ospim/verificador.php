<? session_save_path("sessiones");
session_start();

$datos = array_values($_POST);
$usuario = $datos [0];
$clave = $datos [1];
$host = "cronos";

$dbusuario =  mysql_connect($host,$usuario, $clave);
if (!$dbusuario) {
   echo "<p><strong><a href='index.htm'><font face='Verdana' size='2'><b>VOLVER AL LOGIN</b></font></a></strong></p>";
   die('No pudo conectarse: ' . mysql_error());
}

$_SESSION['host']= $host;
$_SESSION['usuario'] = $usuario;
$_SESSION['clave'] = $clave;
$_SESSION['aut'] = 1;
if ($usuario == "prueba") {
	$_SESSION['dbname'] = "maderaprueba";
} else {
	$_SESSION['dbname'] = "madera";
}

$_SESSION['ultimoAcceso'] = date("Y-n-j H:i:s");

header ('location:menu.php');
?>


