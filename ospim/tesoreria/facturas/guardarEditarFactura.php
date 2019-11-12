<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];
if(isset($_POST)) {
	//var_dump($_POST);
	$puntodeventa = substr($_POST['numero'],0,4);
	$nrocomprobante = substr($_POST['numero'], -8);

	if(isset($_POST['idCodigoautorizacion'])){
		$idcodigoautorizacion = $_POST['idCodigoautorizacion'];
	} else {
		$idcodigoautorizacion = NULL;
	}

	if(isset($_POST['idEstablecimiento'])) {
		$idestablecimiento = $_POST['idEstablecimiento'];
	} else {
		$idestablecimiento = 0;
	}

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlActualizaFactura = "UPDATE facturas SET fecharecepcion = :fecharecepcion, idPrestador = :idPrestador, idTipocomprobante = :idTipocomprobante, puntodeventa = :puntodeventa, nrocomprobante = :nrocomprobante, fechacomprobante = :fechacomprobante, idCodigoautorizacion = :idCodigoautorizacion, nroautorizacion = :nroautorizacion, fechacorreo = :fechacorreo, diasvencimiento = :diasvencimiento, fechavencimiento = :fechavencimiento, importecomprobante = :importecomprobante, idestablecimiento = :idestablecimiento, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE id = :id";
		$resActualizaFactura = $dbh->prepare($sqlActualizaFactura);
		if($resActualizaFactura->execute(array(':fecharecepcion' => fechaParaGuardar($_POST['fecharecepcion']), ':idPrestador' => $_POST['idPrestador'], ':idTipocomprobante' => $_POST['idTipocomprobante'], ':puntodeventa' => $puntodeventa, ':nrocomprobante' =>  $nrocomprobante, ':fechacomprobante' =>  fechaParaGuardar($_POST['fechacomprobante']), ':idCodigoautorizacion' => $idcodigoautorizacion, ':nroautorizacion' => $_POST['nroautorizacion'], ':fechacorreo' => fechaParaGuardar($_POST['fechacorreo']), ':diasvencimiento' => $_POST['diasvencimiento'], ':fechavencimiento' =>  fechaParaGuardar($_POST['fechavencimiento']), ':importecomprobante' => $_POST['importecomprobante'], ':idestablecimiento' => $idestablecimiento, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion, ':id' => $_POST['id'])))
		$dbh->commit();
		$pagina = "moduloFacturas.php";
		header("Location: $pagina");
	}
	catch (PDOException $e) {
		$error = $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header($redire);
		exit(0);
	}
}
?>