<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

var_dump($_POST);
$orden = $_POST['orden'];
$nroafil = $_POST['nroafil'];
$nombre = $_POST['nombre'];
$seguimiento = $_POST['seguimiento'];
if ($seguimiento == 1) {
	$comentario = $_POST['comentario'];
}
$titulo = $_POST['titulo'];
$descri = $_POST['descripcion'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	
	$sqlInsertSeguimiento = "INSERT INTO seguimiento VALUES(DEFAULT, $nroafil, $orden, $seguimiento,'$titulo','$descri','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";
	//echo "<br>".$sqlInsertSeguimiento."<br>";
	$dbh->exec($sqlInsertSeguimiento);
	$idSeguimiento = $dbh->lastInsertId();
	
	if ($seguimiento == 1) {
		$sqlInsertEstado = "INSERT INTO seguimientoestado VALUES(DEFAULT, $idSeguimiento, 'EN GESTION', '$comentario','$fecharegistro','$usuarioregistro')";
		//echo "<br>".$sqlInsertEstado."<br>";
		$dbh->exec($sqlInsertEstado);
	}
	
	$dbh->commit();
	$pagina = "seguimiento.php?nroafil=$nroafil&orden=$orden&nombre=$nombre";
	Header("Location: $pagina");
	
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>
