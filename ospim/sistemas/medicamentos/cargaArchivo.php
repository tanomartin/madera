<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

function UltimoDiaHábil($anho,$mes){
	if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) {
		$dias_febrero = 29;
	} else {
		$dias_febrero = 28;
	}
	switch($mes) {
		case 1: $dia = 31; break;
		case 2: $dia = $dias_febrero; break;
		case 3: $dia = 31; break;
		case 4: $dia = 30; break;
		case 5: $dia = 31; break;
		case 6: $dia = 30; break;
		case 7: $dia = 31; break;
		case 8: $dia = 31; break;
		case 9: $dia = 30; break;
		case 10: $dia = 31; break;
		case 11: $dia = 30; break;
		case 12: $dia = 31; break;
	}

	$ultimoDia = $anho."-".$mes."-".$dia;
	$diaSemana = date ('N',strtotime($ultimoDia));
	while ($diaSemana > 5) {
		$dia -= 1;
		$ultimoDia = $anho."-".$mes."-".$dia;
		$diaSemana = date ('N',strtotime($ultimoDia));
	}
	return $ultimoDia;
}

$sqlUltimaActualizacion = "SELECT * FROM medicontrol WHERE tipo = '".$_GET['tipo']."' ORDER BY id DESC limit 1";
$resUltimaActualizacion = mysql_query($sqlUltimaActualizacion,$db);
$rowUltimaActualizacion = mysql_fetch_assoc($resUltimaActualizacion);

if ($_GET['tipo'] == 'M') {
	$nuevafecha = date('Y',strtotime($rowUltimaActualizacion['fechaarchivo']))."-".date('m',strtotime($rowUltimaActualizacion['fechaarchivo']))."-01";
	$nuevafecha = strtotime ('+1 month',strtotime($nuevafecha )) ;
	$nuevafecha = date ('Y-m-d',$nuevafecha);
	$ano = date('Y',strtotime($nuevafecha));
	$mes = date('m',strtotime($nuevafecha));
	$fechaArchivo = UltimoDiaHábil($ano,$mes);
} else {
	$sqlUltimaMensual = "SELECT * FROM medicontrol WHERE tipo = 'M' ORDER BY id DESC limit 1";
	$resUltimaMensual = mysql_query($sqlUltimaMensual,$db);
	$rowUltimaMensual = mysql_fetch_assoc($resUltimaMensual);
	if ($rowUltimaMensual['id'] > $rowUltimaActualizacion['id']) {
		$fechaArchivo = date ('Y-m-d',strtotime ('+1 day',strtotime($rowUltimaMensual['fechaarchivo'])));
		$diaSemana = date ('N',strtotime($fechaArchivo));
		while ($diaSemana != 5) {
			$fechaArchivo = date ('Y-m-d',strtotime ('+1 day',strtotime($fechaArchivo)));
			$diaSemana = date ('N',strtotime($fechaArchivo));
		}	
	} else {
		$fechaArchivo = date ('Y-m-d',strtotime ('+7 day',strtotime($rowUltimaActualizacion['fechaarchivo'])));
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Actualizacion Alfa Beta :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	var pathArchivo = formulario.archivo.value;
	if (pathArchivo == "") {
		alert("El archivo .ZIP es obligatorio");
		return false;
	}

	var arrayArchivo = pathArchivo.split("\\");
	var index = arrayArchivo.length - 1
	var nombreArchivo = arrayArchivo[index];
	var fecha = formulario.fechafile.value;
	var arrayControl = fecha.split("-");
	var control = arrayControl[0].concat(arrayControl[1],arrayControl[2]);
	var nombrecontrol = nombreArchivo.substring(0,8);

	if (control != nombrecontrol) {
		alert("El nombre del archivo .ZIP no es el correcto");
		return false;
	}
	
	$.blockUI({ message: "<h1>Cargando archivos. Aguarde por favor...</h1>" });
	return true;
}
</script>

</head>

<body bgcolor="#CCCCCC">
	<div align="center">
	 	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuMedicamentos.php'" /></p>
	 	<h3>Actualizacion Alfa Beta </h3>
	 	<h3><font color="blue"><?php if ($_GET['tipo'] == "M") { echo "MENSUAL"; } else { echo "SEMANAL"; }?></font></h3>
	 	
	 	<form enctype="multipart/form-data" method="post" onsubmit="return validar(this)" action="<?php echo "actualizacionArchivos.php?tipo=".$_GET['tipo']; ?>">
 			<p><b>Fecha Archivo: </b><input type="text" value="<?php echo $fechaArchivo?>" readonly="readonly" name="fechafile" id="fechafile" size="8" style="background-color: silver"/></p>
 			<h4>Cargar Archivo ZIP</h4>
 			<p><input type="file" name="archivo" id="archivo" accept=".zip" /></p>
 			<p><input type="submit" name="importar"  value="Actualizar"/></p>
 		</form>
	</div>
</body>
</html>