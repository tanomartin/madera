<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

var_dump($_POST);echo"<br>";
$idSeguimiento = $_POST['id'];
$orden = $_POST['orden'];
$nroafil = $_POST['nroafil'];
$nombre = $_POST['nombre'];

$cambioEstado = $_POST['cambioEstado'];
if ($cambioEstado != 'SC') {
	$comentario = $_POST['comentario'];
}

$titulo = $_POST['titulo'];
$descri = $_POST['descripcion'];
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	
	$sqlModificarSeguimiento = "UPDATE seguimiento SET titulo = '$titulo', descripcion = '$descri', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE id = $idSeguimiento";
	//echo "<br>".$sqlModificarSeguimiento."<br>";
	$dbh->exec($sqlModificarSeguimiento);
	
	if ($cambioEstado != 'SC') {
		$sqlInsertEstado = "INSERT INTO seguimientoestado VALUES(DEFAULT, $idSeguimiento, '$cambioEstado', '$comentario','$fechamodificacion','$usuariomodificacion')";
		//echo "<br>".$sqlInsertEstado."<br>";
		$dbh->exec($sqlInsertEstado);
	}
	
	$dbh->commit();
	$pagina = "seguimientoDetalle.php?id=$idSeguimiento&nombre=$nombre";
	Header("Location: $pagina");
	
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>
