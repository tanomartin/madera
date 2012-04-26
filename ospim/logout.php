<? session_save_path("sessiones");
session_start();
session_destroy();
if ($_SERVER['HTTP_REFERER'] == "http://cronos/ospim/menu.php") {
	header ('location:index.htm');
} else {
	header ('location:sesionCaducada.php');
}
?>


