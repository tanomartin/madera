<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$archivo_name = $_GET['path']; 
?>

<iframe src="<?php echo $archivo_name?>" width="100%" height="100%"></iframe>