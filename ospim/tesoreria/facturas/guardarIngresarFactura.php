<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
if(isset($_POST)) {
	//var_dump($_POST);
	$puntodeventa = substr($_POST['numero'],0,4);
	$nrocomprobante = substr($_POST['numero'], -8);

	if(isset($_POST['idEstablecimiento']){
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

		$sqlIngresaFactura = "INSERT INTO facturas (id,fecharecepcion,idPrestador,idTipocomprobante,puntodeventa,nrocomprobante,fechacomprobante,idCodigoautorizacion,nroautorizacion,fechacorreo,diasvencimiento,fechavencimiento,importecomprobante,idestablecimiento,fechainicioliquidacion,usuarioliquidacion,fechacierreliquidacion,totalcredito,totaldebito,importeliquidado,totalretenciones,fecharetencion,totalpagado,restoapagar,fechapago,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion) VALUES(:id,:fecharecepcion,:idPrestador,:idTipocomprobante,:puntodeventa,:nrocomprobante,:fechacomprobante,:idCodigoautorizacion,:nroautorizacion,:fechacorreo,:diasvencimiento,:fechavencimiento,:importecomprobante,:idestablecimiento,:fechainicioliquidacion,:usuarioliquidacion,:fechacierreliquidacion,:totalcredito,:totaldebito,:importeliquidado,:totalretenciones,:fecharetencion,:totalpagado,:restoapagar,:fechapago,:fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion)";
		$resIngresaFactura = $dbh->prepare($sqlIngresaFactura);
		if($resIngresaFactura->execute(array(':id' => 'DEFAULT',':fecharecepcion' => fechaParaGuardar($_POST['fecharecepcion']),':idPrestador' => $_POST['idPrestador'],':idTipocomprobante' => $_POST['idTipocomprobante'],':puntodeventa' => $puntodeventa,':nrocomprobante' =>  $nrocomprobante,':fechacomprobante' =>  fechaParaGuardar($_POST['fechacomprobante']),':idCodigoautorizacion' => $_POST['idCodigoautorizacion'],':nroautorizacion' => $_POST['nroautorizacion'],':fechacorreo' => fechaParaGuardar($_POST['fechacorreo']),':diasvencimiento' => $_POST['diasvencimiento'],':fechavencimiento' =>  fechaParaGuardar($_POST['fechavencimiento']),':importecomprobante' => $_POST['importecomprobante'],':idestablecimiento' => $idestablecimiento,':fechainicioliquidacion' => 'DEFAULT',':usuarioliquidacion' => NULL,':fechacierreliquidacion' => 'DEFAULT',':totalcredito' => 'DEFAULT',':totaldebito' => 'DEFAULT',':importeliquidado' => 'DEFAULT',':totalretenciones' => 'DEFAULT',':fecharetencion' => 'DEFAULT',':totalpagado' => 'DEFAULT',':restoapagar' => 'DEFAULT',':fechapago' => 'DEFAULT',':fecharegistro' => $fecharegistro,':usuarioregistro' => $usuarioregistro,':fechamodificacion' => 'DEFAULT',':usuariomodificacion' => NULL)))
		$idUltimaFactura = $dbh->lastInsertId();
		$dbh->commit();
		$pagina = "consultarFactura.php?idfactura=$idUltimaFactura";
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