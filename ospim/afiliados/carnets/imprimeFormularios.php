<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$maquina = $_SERVER['SERVER_NAME'];

if(strcmp("localhost",$maquina)==0)
	$carpetaLote=$_SERVER['DOCUMENT_ROOT']."/madera/ospim/afiliados/carnets/lotesimpresion/";
else
	$carpetaLote="/home/sistemas/Documentos/Repositorio/LotesImpresion/";

$fechacierre = date("Y-m-d H:i:s");
$usuariocierre = $_SESSION['usuario'];
if(isset($_GET['lote']) && isset($_GET['usuario']) && isset($_GET['formulario']) && isset($_GET['azul']) && isset($_GET['bordo']) && isset($_GET['rojo']) && isset($_GET['verde']) && isset($_GET['listado']) && isset($_GET['nota']))
{
	$lote=$_GET['lote'];
	$usuario=$_GET['usuario'];
	$formulario=$_GET['formulario'];
	$azulcerrado=FALSE;
	$bordocerrado=FALSE;
	$rojocerrado=FALSE;
	$verdecerrado=FALSE;
	$listadocerrado=FALSE;
	$notacerrado=FALSE;
	$valor=0;
	$marcacierreimpresion=0;

	if(strcmp($formulario, "A") == 0 || $_GET['azul'] == 1) {
		$azulcerrado = TRUE;
	}

	if(strcmp($formulario, "B") == 0 || $_GET['bordo'] == 1) {
		$bordocerrado = TRUE;
	}

	if(strcmp($formulario, "R") == 0 || $_GET['rojo'] == 1) {
		$rojocerrado = TRUE;
	}

	if(strcmp($formulario, "V") == 0 || $_GET['verde'] == 1) {
		$verdecerrado = TRUE;
	}

	if(strcmp($formulario, "L") == 0 || $_GET['listado'] == 1) {
		$listadocerrado = TRUE;
	}

	if(strcmp($formulario, "N") == 0 || $_GET['nota'] == 1) {
		$notacerrado = TRUE;
	}

	if(strcmp($formulario, "A")==0) {
		$valor=1;
		$sqlActualizaImpresion = "UPDATE impresioncarnets SET marcaimpresionazul = :valor, marcacierreimpresion = :marcacierreimpresion, usuarioimpresion = :usuariocierre, fechaimpresion = :fechacierre WHERE lote = :lote AND usuarioemision = :usuario";
	}

	if(strcmp($formulario, "B")==0) {
		$valor=1;
		$sqlActualizaImpresion = "UPDATE impresioncarnets SET marcaimpresionbordo = :valor, marcacierreimpresion = :marcacierreimpresion, usuarioimpresion = :usuariocierre, fechaimpresion = :fechacierre WHERE lote = :lote AND usuarioemision = :usuario";
	}

	if(strcmp($formulario, "R")==0) {
		$valor=1;
		$sqlActualizaImpresion = "UPDATE impresioncarnets SET marcaimpresionrojo = :valor, marcacierreimpresion = :marcacierreimpresion, usuarioimpresion = :usuariocierre, fechaimpresion = :fechacierre WHERE lote = :lote AND usuarioemision = :usuario";
	}

	if(strcmp($formulario, "V")==0) {
		$valor=1;
		$sqlActualizaImpresion = "UPDATE impresioncarnets SET marcaimpresionverde = :valor, marcacierreimpresion = :marcacierreimpresion, usuarioimpresion = :usuariocierre, fechaimpresion = :fechacierre WHERE lote = :lote AND usuarioemision = :usuario";
	}

	if(strcmp($formulario, "L")==0) {
		$valor=1;
		$sqlActualizaImpresion = "UPDATE impresioncarnets SET marcaimpresionlistado = :valor, marcacierreimpresion = :marcacierreimpresion, usuarioimpresion = :usuariocierre, fechaimpresion = :fechacierre WHERE lote = :lote AND usuarioemision = :usuario";
	}

	if(strcmp($formulario, "N")==0) {
		$valor=1;
		$sqlActualizaImpresion = "UPDATE impresioncarnets SET marcaimpresionnota = :valor, marcacierreimpresion = :marcacierreimpresion, usuarioimpresion = :usuariocierre, fechaimpresion = :fechacierre WHERE lote = :lote AND usuarioemision = :usuario";
	}

	if($azulcerrado && $bordocerrado && $rojocerrado && $verdecerrado && $listadocerrado && $notacerrado) {
		$marcacierreimpresion=1;
	}

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$resActualizaImpresion = $dbh->prepare($sqlActualizaImpresion);
		if($resActualizaImpresion->execute(array(':valor' => $valor, ':marcacierreimpresion' => $marcacierreimpresion, ':usuariocierre' => $usuariocierre, ':fechacierre' => $fechacierre, ':lote' => $lote, ':usuario' => $usuario))) {
		}
		$dbh->commit();
	}
	catch (PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}

	$archivo=$carpetaLote.$formulario.$lote.$usuario.".pdf";
	$tamanio = filesize($archivo);
	header("Content-type: application/pdf");
	header("Content-Length: $tamanio");
	header("Content-Disposition: inline; filename=$archivo");
	$respuesta=readfile($archivo);
	echo $respuesta;
}
?>