<?php session_save_path("sessiones");
session_start();
session_destroy();
$cadena = $_SERVER['HTTP_REFERER'];
$buscar = "menu.php";
$resultado = strpos($cadena, $buscar);
if ($resultado == true) {
	header ('location:index.htm');
} else {
	header ('location:sesionCaducada.php');
}
?>


