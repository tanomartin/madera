<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

function formatoPerido($per) {
	if ($per == 1) {
		return "01";
	}
	if ($per == 2) {
		return "02";
	}
	if ($per == 3) {
		return "03";
	}
	if ($per == 4) {
		return "04";
	}
	if ($per == 5) {
		return "05";
	}
	if (($per == 6) || ($per == -6)) {
		return "06";
	}
	if (($per == 7) || ($per == -5)) {
		return "07";
	}
	if (($per == 8) || ($per == -4)) {
		return "08";
	}
	if (($per == 9) || ($per == -3)) {
		return "09";
	}
	if (($per == 10) || ($per == -2)) {
		return "10";
	}
	if (($per == 11) || ($per == -1)) {
		return "11";
	}
	if (($per == 12) || ($per == 0)){
		return "12";
	}
}

$dia=date("j");
$mes=date("m");
$anio=date("Y");
$inicio=0;
$fin=2;

for ( $i = $inicio ; $i <= $fin ; $i++) {
	$perAux=$mes - $i;
	if ($perAux <= 0) {
		$anioArc[$i]=$anio-1;
		$mesArc[$i]=formatoPerido($perAux);
	}
	else {
		$anioArc[$i]=$anio;
		$mesArc[$i]=formatoPerido($perAux);
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Subida Archivo A.N.S.E.S. :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>


<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	var periodo = formulario.periodo.value;
	if (periodo == 0) {
		alert("Debe elegir un periodo para subir el archivo");
		return false;
	}
	$.blockUI({ message: "<h1>Subiendo Archivo de Desempleo<br>Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center"><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'" /></span></p>
  <p class="Estilo2">Subida de Archivo de Desempleo de A.N.S.E.S.</p>
  <p class="nover"><b>Seleccione Per&iacute;odo</b></p>
  
  <form name="seleccionPeriodo" action="subidaArchivoDesempleo.php" onsubmit="return validar(this)" method="post">	 
	  <?php if (isset($_GET['existe'])) { ?><div style="color: #FF0000"><b>Período "<?php echo substr($_GET['existe'],4,2)."-".substr($_GET['existe'],0,4) ?>" ya procesado</b></div> <?php } ?>
	  <?php if (isset($_GET['nocarpeta'])) { ?><div style="color:#FF0000"><b>No existe la Carpeta "<?php echo $_GET['nocarpeta']?>"</b></div>  <?php } ?>
	  <?php if (isset($_GET['noexiste'])) { ?><div style="color:#FF0000"><b>No existe el archivo "<?php echo $_GET['noexiste']?>" del periodo "<?php echo substr($_GET['carpeta'],4,2)."-".substr($_GET['carpeta'],0,4) ?>"</b></div>  <?php } ?>
	  <select class="nover" name="periodo" id="periodo">
	  <option id="periodo" selected="selected" value="0"> Seleccione Periodo </option>
		  <?php 
			for ($i=$inicio;$i<=$fin;$i++){
				$valor = $anioArc[$i].$mesArc[$i];
				$nombre = $mesArc[$i]."-".$anioArc[$i];
				print("<option value=$valor>$nombre</option>");	
			}	
		  ?>
	  </select>
  <p><input class="nover" type="submit" name="submit" value="Subir" /></p>
  </form>
  
</div>
</body>
</html>