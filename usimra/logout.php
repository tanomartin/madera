<?php session_save_path("sessiones");
session_start();
session_destroy();
//OJOOOOOOOOOOOOOOO camibar
if ($_SERVER['HTTP_REFERER'] == "http://localhost/usimra/menu.php") {
	header ('location:index.php');
} else {
	header ('location:sesionCaducada.php');
}
?>


