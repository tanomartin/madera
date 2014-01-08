<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$cuit = $_POST['nrcuit'];
$nroorden = $_POST['nroorden'];

$sqlJuicio = "select * from cabjuiciosusimra where cuit = $cuit and nroorden = $nroorden";
$resJuicio = mysql_query($sqlJuicio,$db); 
$rowJuicio = mysql_fetch_array($resJuicio); 

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

$fechamodificacion = date("Y-m-d H:m:s");
$usuariomodificacion =  $_SESSION['usuario'];

if ($rowJuicio['acuerdorelacionado'] == 1) {
	$nroAcuQuit = $rowJuicio['nroacuerdo'];
	$sqlDetalleQuitar = "SELECT * FROM detjuiciosusimra WHERE nroacuerdo = $nroAcuQuit";
	$resDetalleQuitar = mysql_query($sqlDetalleQuitar,$db);
	$per = 0;
	while ($rowDetalleQuitar=mysql_fetch_assoc($resDetalleQuitar)) {
		$mesAcu = $rowDetalleQuitar['mesjuicio'];
		$anoAcu = $rowDetalleQuitar['anojuicio'];
		$idAcu = $rowDetalleQuitar['idperiodo'];
		$concepto = $rowDetalleQuitar['conceptodeuda'];
		$sqlInsertDetAcuQuitado = "INSERT INTO detacuerdosusimra VALUE($cuit,$nroAcuQuit,$idAcu,$mesAcu,$anoAcu,'$concepto')";
		$listaDetAcuQui[$per] = $sqlInsertDetAcuQuitado;
		$per++;
	}
}

if ($rowJuicio['acuerdorelacionado'] == 1) {
	$nroAcuQuit = $rowJuicio['nroacuerdo'];
	$sqlCabObser = "SELECT observaciones FROM cabacuerdosusimra WHERE cuit = '$cuit' and nroacuerdo = $nroAcuQuit";
	$resCabObser = mysql_query($sqlCabObser,$db);
	$rowCabObser = mysql_fetch_assoc($resCabObser);
	$obs = $rowCabObser['observaciones'];
	$obsAquitar = "/ Acuerdo Absorvido por Juicio con Nro. de Orden $nroorden./";
	$nuevaObser = preg_replace($obsAquitar,"",$obs);
	if (isset($_POST['desabsorver'])) {
		$sqlUpdateAcuQuitado = "UPDATE cabacuerdosusimra SET estadoacuerdo = 1, observaciones = '$nuevaObser', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE cuit = '$cuit' and nroacuerdo = $nroAcuQuit";
	}
}

$sqlDeletePeriodos = "DELETE FROM detjuiciosusimra WHERE nroorden = $nroorden";

if ($acuAbs == 1) {
	$nroacuerdo = $_POST['nroacu'];
	$sqlCabObser = "SELECT observaciones FROM cabacuerdosusimra WHERE cuit = '$cuit' and nroacuerdo = $nroacuerdo";
	$resCabObser = mysql_query($sqlCabObser,$db);
	$rowCabObser = mysql_fetch_assoc($resCabObser);
	$nuevaObser = $rowCabObser['observaciones']. " Acuerdo Absorvido por Juicio con Nro. de Orden $nroorden.";
	$sqlUpdateAcuAbs = "UPDATE cabacuerdosusimra SET estadoacuerdo = 4, observaciones = '$nuevaObser', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE cuit = '$cuit' and nroacuerdo = $nroacuerdo";
} else {
	$nroacuerdo = 0;
}


if ($rowJuicio['acuerdorelacionado'] == 1) {
	if (isset($_POST['desabsorver'])) {
		$sqlCabecera = "UPDATE cabjuiciosusimra SET nrocertificado = '$nrocerti', statusdeuda = $status, fechaexpedicion = '$fecExpe', acuerdorelacionado = $acuAbs, nroacuerdo = $nroacuerdo, deudahistorica = $deudaHisto, intereses = $intereses, deudaactualizada = $duedaActual, codasesorlegal = $asesor, codinspector = $inspector, usuarioejecutor = '$usuarioejecutor', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE nroorden = $nroorden and cuit = '$cuit'";
	} else {
		$sqlCabecera = "UPDATE cabjuiciosusimra SET nrocertificado = '$nrocerti', statusdeuda = $status, fechaexpedicion = '$fecExpe', deudahistorica = $deudaHisto, intereses = $intereses, deudaactualizada = $duedaActual, codasesorlegal = $asesor, codinspector = $inspector, usuarioejecutor = '$usuarioejecutor', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE nroorden = $nroorden and cuit = '$cuit'";
		$nroacuerdo = $rowJuicio['nroacuerdo'];
	}
} else {
	$sqlCabecera = "UPDATE cabjuiciosusimra SET nrocertificado = '$nrocerti', statusdeuda = $status, fechaexpedicion = '$fecExpe', acuerdorelacionado = $acuAbs, nroacuerdo = $nroacuerdo, deudahistorica = $deudaHisto, intereses = $intereses, deudaactualizada = $duedaActual, codasesorlegal = $asesor, codinspector = $inspector, usuarioejecutor = '$usuarioejecutor', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE nroorden = $nroorden and cuit = '$cuit'";
}


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
		if ($id == 0) {
			$sqlInsert = "INSERT INTO detjuiciosusimra VALUES($nroorden,0,$anio,$mes,0,'')"; 
		} else {
			$sqlInsert = "INSERT INTO detjuiciosusimra VALUES($nroorden,$id,$anio,$mes,$nroacuerdo,'$concepto')"; 
		}
		if ($id != 0) {
			$sqlDelete = "DELETE FROM detacuerdosusimra WHERE cuit = '$cuit' and nroacuerdo = $nroacuerdo and idperiodo = $id";
			$sqlDelPer[$m] = $sqlDelete;
			$m++;
		}
		$sqlPeriodos[$n] = $sqlInsert;
		$n++;
	} 
}

if (sizeof($listaDetAcuQui) > 0) {
	$listadoPeriodosAcuQuitado = serialize($listaDetAcuQui);
	$listadoPeriodosAcuQuitadoSerializado = urlencode($listadoPeriodosAcuQuitado);
}

if (sizeof($sqlDelPer) > 0) {
	$listadoPeriodosAcuAbs = serialize($sqlDelPer);
	$listadoPeriodosAcuAbsSerializado = urlencode($listadoPeriodosAcuAbs);
}

if (sizeof($sqlPeriodos) > 0) {
	$listadoPeriodosJui = serialize($sqlPeriodos);
	$listadoPeriodosJuiSerializado = urlencode($listadoPeriodosJui);
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Juicios :.</title>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	function formSubmit() {
		$.blockUI({ message: "<h1>Guardando datos del juicio... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		document.getElementById("datosJuicio").submit();
	}
</script>

<body bgcolor="#B2A274" onload="formSubmit();">
<form action="guardarModificarJuicio.php" id="datosJuicio" method="POST" style="visibility:hidden"> 
    <p>
      <input name="cuit" size='200' type="visible" value="<?php echo $cuit ?>">
  </p>
    <p>  
      <input name="nroorden" size='200' type="visible" value="<?php echo $nroorden ?>">
  </p>
    <p>
      <input name="insertPerAcuQuitado" size='200' type="visible" value="<?php echo $listadoPeriodosAcuQuitadoSerializado ?>">
  </p>
    <p>
      <input name="updateCabeceraAcuQuitado" size='200' type="visible" value="<?php echo $sqlUpdateAcuQuitado ?>">
  </p>
    <p>
      <input name="deletePeriodos" size='200' type="visible" value="<?php echo $sqlDeletePeriodos ?>">
  </p>
    <p>
      <input name="updateCabeceraAcuAbs" size='200' type="visible" value="<?php echo $sqlUpdateAcuAbs ?>">
  </p>
    <p>
      <input name="updateCabeceraJui" size='200' type="visible" value="<?php echo $sqlCabecera ?>">
  </p>
    <p>
      <input name="insertPeriodosJui" size='200' type="visible" value="<?php echo $listadoPeriodosJuiSerializado ?>">
  </p>
    <p>
      <input name="deletePeriodosAcuAbs" size='200' type="visible" value="<?php echo $listadoPeriodosAcuAbsSerializado ?>"> 
     </p>
</form> 
</body>