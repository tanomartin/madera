<?php session_save_path("sessiones");
session_start();

$datos = array_values($_POST);
$usuario = $datos [0];
$clave = $datos [1];
include("conexion.php");
$sql = "select * from usuarios where usuario = '$usuario' and clave = '$clave'";
$result = mysql_query($sql,$db);
$cant = mysql_num_rows($result);
if ($cant > 0) {	
	$row=mysql_fetch_array($result);
	$_SESSION['usuario'] = $row['id'];
	$_SESSION['aut'] = $row['nivel'];
	if ($_SESSION['aut'] == 2) {
		header ('location:acuerdosFisca.php');
	} else {
		header ('location:acuerdos.php');
	}
} else {
	header ('location:login2.htm');
}
?>


