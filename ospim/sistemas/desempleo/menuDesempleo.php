<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Subida Archivo A.N.S.E.S. :.</title>
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
  <p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'" /></p>
  <h3>Subida de Archivo de Desempleo de A.N.S.E.S.</h3>  
  <form name="seleccionPeriodo" action="subidaArchivoDesempleo.php" onsubmit="return validar(this)" method="post">	 
	  <?php if (isset($_GET['existe'])) { ?><h3 style="color: red">Período "<?php echo substr($_GET['existe'],4,2)."-".substr($_GET['existe'],0,4) ?>" ya procesado</h3> <?php } ?>
	  <?php if (isset($_GET['nocarpeta'])) { ?><h3 style="color: red">No existe la Carpeta "<?php echo $_GET['nocarpeta']?>"</h3>  <?php } ?>
	  <?php if (isset($_GET['noexiste'])) { ?><h3 style="color: red">No existe el archivo "<?php echo $_GET['noexiste']?>" del periodo "<?php echo substr($_GET['carpeta'],4,2)."-".substr($_GET['carpeta'],0,4) ?>"</h3>  <?php } ?>
	  <p class="nover"><b>Seleccione Período</b></p>
	  <select class="nover" name="periodo" id="periodo">
	  <option id="periodo" selected="selected" value="0"> Seleccione Periodo </option>
	  <?php for ($i = 0 ; $i < 3 ; $i++) {
				$valor = date("Ym", (strtotime ("-$i month")));
				$periodo = date("m-Y", (strtotime ("-$i month"))); ?>
				<option value='<?php echo $valor ?>'><?php echo $periodo ?></option>
	  <?php } ?>
	  </select>
  <p><input class="nover" type="submit" name="submit" value="IMPORTAR DESEMPLEO" /></p>
  </form>
</div>
</body>
</html>