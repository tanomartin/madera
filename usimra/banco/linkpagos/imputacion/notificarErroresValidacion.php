<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."claves.php");
include($libPath."fechas.php");
$fechanotificacion = date("Y-m-d H:i:s");
$usuarionotificacion = $_SESSION['usuario'];
try {
	$hostlocal = $_SESSION['host'];
	$dblocal = $_SESSION['dbname'];
	$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
    $dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbl->beginTransaction();

	$hostremoto = $hostaplicativo;
	$dbremoto = $baseUsimraNewAplicativo;
	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremoto",$usuarioaplicativo,$claveaplicativo);
	$dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbr->beginTransaction();

	$sqlLeeANotificar="SELECT * FROM linkaportesusimra WHERE notificacion = 1 AND tiponotificacion != 0";
	$resultLeeANotificar=$dbl->query($sqlLeeANotificar);
	foreach($resultLeeANotificar as $notificar) {
		$tipoerror="";
		$tiposolicitud="";
		$asuntonotificacion="DEPOSITO POR LINK PAGOS - Fecha: ".invertirFecha($notificar[fechadeposito])." Codigo: ".$notificar[cuit]." Nro. Liquidacion: ".$notificar[referencia];

		if($notificar[tiponotificacion]==1) {
			$tipoerror="una diferencia entre el importe pagado y el importe emitido por el Ticket correspondiente a ese Nro. de Liquidacion";
			$tiposolicitud=" a ese Nro. de Liquidacion";
		}
		if($notificar[tiponotificacion]==2) {
			$tipoerror="que el Ticket correspondiente a ese Nro. de Liquidacion ya fue utilizado con anterioridad para efectivizar otro pago";
			$tiposolicitud=" al importe que Ud. intento abonar";
		}		
		if($notificar[tiponotificacion]==3) {
			$tipoerror="que no existe ningun Ticket emitido correspondiente a ese Nro. de Liquidacion";
			$tiposolicitud=" al importe que Ud. a abonado";
		}

		$mensajenotificacion="Hemos recibido a traves del sistema Link Pagos un pago con fecha ".invertirFecha($notificar[fechadeposito])." de $ ".$notificar[importe])." cuyo Nro. de Liquidacion informado es ".$notificar[referencia].". Nuestros registros indican ".$tipoerror.", lo que nos imposibilita imputar correctamente en forma automatica en vuestra cuenta corriente el pago efectuado. Por esto, le solicitamos tenga la amabilidad de remitir por correo electronico al email linkpagos@usimra.com.ar, copia/s de la/s DDJJ/s objeto/s del pago y copia del Ticket emitido por el Aplicativo DDJJ Online correspondiente".$tiposolicitud.". La documentacion solicitada debera ser adjunta en formato PDF, consignando como Asunto, exactamente el mismo de esta notificacion. Gracias por su atencion.";

		$sqlAddNotificacionRemota="INSERT INTO notificaciones VALUES ('$notificar[cuit]',$notificar[tiponotificacion],'$fechanotificacion','$asuntonotificacion','$mensajenotificacion',DEFAULT,DEFAULT,DEFAULT)";
		$resultAddNotificacionRemota=$dbr->query($sqlAddNotificacionRemota);
		
		$sqlUpdNotificacionLocal="UPDATE linkaportesusimra SET fechanotificacion = '$fechanotificacion', usuarionotificacion = '$usuarionotificacion' WHERE fechaarchivo = '$notificar[fechaarchivo]' AND idmovimiento = $notificar[idmovimiento]"
		$resultUpdNotificacionLocal=$dbl->query($sqlUpdNotificacionLocal);

	}

	$dbr->commit();
	$dbl->commit();

	//$pagina = "validarTicketsLinkpagos.php";
	//Header("Location: $pagina");

} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbl->rollback();
	$dbr->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>