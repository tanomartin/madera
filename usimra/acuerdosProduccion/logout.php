<?php session_save_path("sessiones");
session_start();
session_destroy();
header ('location:index.htm');
?>


