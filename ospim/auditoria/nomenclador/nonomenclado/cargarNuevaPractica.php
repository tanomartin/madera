<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
if(isset($_POST['valor'])) {
	$codigo=$_POST['valor'];
	
	$respuesta = "<div><input type='text' value='$codigo'/>
				  <br><input type='submit' value='Guardar'>";
	echo $respuesta;
}
?>