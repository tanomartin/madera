<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$cuit = $_POST['nrcuit'];
$nroorden = $_POST['nroorden'];
$nrocerti = $_POST['nrocert'];
$status = $_POST['status'];
$fecExpe = fechaParaGuardar($_POST['fechaexp']);
$deudaHisto = number_format($_POST['deudaHistorica'],2,'.','');
$intereses = number_format($_POST['intereses'],2,'.','');
$duedaActual = number_format($_POST['deudaActual'],2,'.','');
$asesor = $_POST['asesor'];
$inspector = $_POST['inspector'];
$acuAbs = $_POST['acuabs'];
$usuarioejecutor = $_POST['ejecutor'];
$tramiteJudicial = $_POST['tramite'];

$fecharegistro = date("Y-m-d H:m:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

if ($acuAbs == 1) {
	$nroacuerdo = $_POST['nroacu'];
	$sqlCabObser = "SELECT observaciones FROM cabacuerdosospim WHERE cuit = '$cuit' and nroacuerdo = $nroacuerdo";
	$resCabObser = mysql_query($sqlCabObser,$db);
	$rowCabObser = mysql_fetch_assoc($resCabObser);
	$nuevaObser = $rowCabObser['observaciones']. " Acuerdo Absorvido por Juicio con Nro. de Orden $nroorden.";
	$sqlUpdateAcu = "UPDATE cabacuerdosospim SET estadoacuerdo = 4, observaciones = '$nuevaObser', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE cuit = '$cuit' and nroacuerdo = $nroacuerdo";
} else {
	$nroacuerdo = 0;
}	
$sqlCabecera = "INSERT INTO cabjuiciosospim VALUE($nroorden,'$cuit',$nrocerti,$status,'$fecExpe',$acuAbs,$nroacuerdo,$deudaHisto,$intereses,$duedaActual,$asesor,$inspector,'$usuarioejecutor',$tramiteJudicial,'$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";

$peridosHabili = $_POST['mostrar'];
$m = 0;
$n = 0;
for ($i = 0; $i <= $peridosHabili; $i++) {
	$idnombre = "id".$i;
	$mesnombre = "mes".$i;
	$anionombre = "anio".$i;
	$concepto = "concepto".$i;
	if ($_POST[$mesnombre] != "" && $_POST[$anionombre] != "") {
		$id = $_POST[$idnombre];
		$mes = $_POST[$mesnombre];
		$anio = $_POST[$anionombre];
		$concepto = $_POST[$concepto];
		if ($id == '') {
			$sqlInsert = "INSERT INTO detjuiciosospim VALUES($nroorden,'',$anio,$mes,0,'')"; 
		} else {
			$sqlInsert = "INSERT INTO detjuiciosospim VALUES($nroorden,$id,$anio,$mes,$nroacuerdo,'$concepto')"; 
		}
		if ($id != '') {
			$sqlDelete = "DELETE FROM detacuerdosospim WHERE cuit = '$cuit' and nroacuerdo = $nroacuerdo and idperiodo = $id";
			$sqlDelPer[$m] = $sqlDelete;
			$m++;
		}
		$sqlPeriodos[$n] = $sqlInsert;
		$n++;
	} 
}

if (sizeof($sqlPeriodos) > 0) {
	$listadoPeriodosJui = serialize($sqlPeriodos);
	$listadoPeriodosJuiSerializado = urlencode($listadoPeriodosJui);
}

if (sizeof($sqlDelPer) > 0) {
	$listadoPeriodosAcu = serialize($sqlDelPer);
	$listadoPeriodosAcuSerializado = urlencode($listadoPeriodosAcu);
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Juicios OSPIM :.</title>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	function formSubmit() {
		var tramite = <?php echo $tramiteJudicial ?>;
		if (tramite == 1) {
			document.forms.datosJuicio.action = "tramiteJudidical.php";
			$.blockUI({ message: "<h1>Preparando formulario de tramite judicial... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		} else {
			$.blockUI({ message: "<h1>Guardando datos del juicio... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		}
		document.getElementById("datosJuicio").submit();
	}
</script>

<body onload="formSubmit();">
<form action="guardarJuicio.php" id="datosJuicio" method="POST"> 
    <input name="cuit" type="hidden" value="<?php echo $cuit ?>">
	<input name="nroorden" type="hidden" value="<?php echo $nroorden ?>">
   	<input name="insertCabeceraJui" type="hidden" value="<?php echo $sqlCabecera ?>">
   	<input name="insertPeriodosJui" type="hidden" value="<?php echo $listadoPeriodosJuiSerializado ?>">
   	<input name="updateCabeceraAcu" type="hidden" value="<?php echo $sqlUpdateAcu ?>">
   	<input name="deletePeriodosAcu" type="hidden" value="<?php echo $listadoPeriodosAcuSerializado ?>">
</form> 
</body>