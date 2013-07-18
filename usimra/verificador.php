<?php session_save_path("sessiones");
session_start();

$datos = array_values($_POST);
$usuario = $datos[0];
$clave = $datos[1];
$host = $_SERVER['SERVER_NAME'];
if ($_SESSION['usuario'] == $usuario) {
	header ('location:index.php?error=2');	
}
$dbusuario =  mysql_connect($host,$usuario, $clave);
if (!$dbusuario) {
  	header ('location:index.php?error=1');	
} else {
	$_SESSION['host']= $host;
	$_SESSION['usuario'] = $usuario;
	$_SESSION['clave'] = $clave;
	$_SESSION['aut'] = 1;
	$_SESSION['dbname'] = "madera";
	$_SESSION['ultimoAcceso'] = date("Y-n-j H:i:s");
	header ('location:menu.php');	
}
?>


