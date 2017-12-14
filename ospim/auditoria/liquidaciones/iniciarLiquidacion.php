<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['idfactura'])) {
	$idcomprobante=$_GET['idfactura'];
	$fechainicioliquidacion = date("Y-m-d H:i:s");
	$usuarioliquidacion = $_SESSION['usuario'];
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlInicioLiquidacion = "UPDATE facturas SET fechainicioliquidacion = :fechainicioliquidacion, usuarioliquidacion = :usuarioliquidacion WHERE id = :id";
		$resInicioLiquidacion = $dbh->prepare($sqlInicioLiquidacion);
		if($resInicioLiquidacion->execute(array(':fechainicioliquidacion' => $fechainicioliquidacion, ':usuarioliquidacion' => $usuarioliquidacion, ':id' => $idcomprobante)))

		$dbh->commit();
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
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Liquidaciones:.</title>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
	function enviaFormulario() {
		$.blockUI({ message: "<h1>Inicializando Proceso de Liquidacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		document.getElementById("iniciarLiquidacion").submit();
	}
</script>
</head>
<body onLoad="enviaFormulario();">
<form id="iniciarLiquidacion" name="iniciarLiquidacion" method="post" action="continuarLiquidacion.php"> 
   <input name="idfactura" type="hidden" value="<?php echo $idcomprobante ?>"/>
   <input name="usuarioLiquidacion" type="hidden" value="<?php echo $usuarioliquidacion ?>"/>
   <input name="fechaInicioLiquidacion" type="hidden" value="<?php echo $fechainicioliquidacion ?>"/>
</form> 
</body>
</html>