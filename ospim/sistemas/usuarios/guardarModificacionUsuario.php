<?php
$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspimSistemas.php");

$id = $_POST['id'];
$nombre = $_POST ['nombre'];
$depto = $_POST ['depto'];
$nombrepc = $_POST ['nombrepc'];
$usuariowin = $_POST ['usuariowin'];
$passwin = $_POST ['passwin'];
$usuariosistema = $_POST ['usuariosistema'];
$passsistema = $_POST ['passsistema'];
$puerto = $_POST ['puerto'];
$conector = $_POST ['conector'];

$fechamodificacion = date ( "Y-m-d H:i:s" );
$usuariomodificacion = $_SESSION ['usuario'];

$sqlModif = "UPDATE usuarios SET departamento = $depto, nombre = '$nombre', nombrepc = '$nombrepc', usuariowin = '$usuariowin', passwin = '$passwin', usuariosistema = '$usuariosistema', passsistema = '$passsistema', puerto = '$puerto', conector = '$conector', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where id = $id";

try {
	$hostname = $_SESSION ['host'];
	$dbname = $_SESSION ['dbname'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $_SESSION ['usuario'], $_SESSION ['clave'] );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	$dbh->exec ( $sqlModif );
	$dbh->commit ();
	$pagina = "usuarios.php";
	Header ( "Location: $pagina" );
} catch ( PDOException $e ) {
	echo $e->getMessage ();
	$dbh->rollback ();
}

?>