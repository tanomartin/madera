<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$arrayPeriodoNOPermitodos = array();
$sqlPresSSSFinalizadas = "SELECT d.periodo
							FROM diabetespresentacion d
							WHERE d.fechadevolucion is not null order by id DESC";
$resPresSSSFinalizadas = mysql_query($sqlPresSSSFinalizadas,$db);
$canPresSSSFinalizadas = mysql_num_rows($resPresSSSFinalizadas);
if ($canPresSSSFinalizadas != 0) { 
	while ($rowPresSSSFinalizadas = mysql_fetch_assoc($resPresSSSFinalizadas)) {
		$arrayPeriodoNOPermitodos[$rowPresSSSFinalizadas['periodo']] = $rowPresSSSFinalizadas['periodo'];
	}
}

$sqlPresSSSActiva = "SELECT d.periodo
						FROM diabetespresentacion d
						WHERE d.fechacancelacion is null and d.fechadevolucion is null order by id DESC";
$resPresSSSActiva = mysql_query($sqlPresSSSActiva,$db);
$canPresSSSActiva = mysql_num_rows($resPresSSSActiva);
if ($canPresSSSActiva != 0) {
	while ($rowPresSSSActiva = mysql_fetch_assoc($resPresSSSActiva)) {
		$arrayPeriodoNOPermitodos[$rowPresSSSActiva['periodo']] = $rowPresSSSActiva['periodo'];
	}
}

$periodo = date("Ym");
$periodoPermitidos = array();
for ($i = 1; $i < 25; $i++) {
	$resta = "-$i month";
	$periodo = strtotime ( $resta , strtotime ( $periodo ) ) ;
	$periodo = date ( 'Ym' , $periodo );
	if (!array_key_exists($periodo, $arrayPeriodoNOPermitodos)) {
		$periodoPermitidos[$periodo] = $periodo;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Diabetes Presentacion S.S.S. :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.periodo.value == 0) {
		alert("Debe seleccionar un periodo para generar la presentación");
		formulario.periodo.focus();
		return(false);
	} 
	$.blockUI({ message: "<h1>Listado Beneficiarios entre las fechas dadas. Aguarde por favor...</h1>" });
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloPresSSS.php'" /></p>
	<h2>Nueva Presentacion Diabetes S.S.S.</h2>
	<form id="nuevaPresentacion" name="nuevaPresentacion" method="post" onsubmit="return validar(this)" action="nuevaPresentacionListado.php">
		<h3>Seleccione Periodo a Generar</h3>
		<p style="color: blue">(No se listaran periodos Finalizados o Activos)</p>
		<p>
			<b>Periodo: </b> 
			<select id="periodo" name="periodo">
				<option value="0">Seleccione Periodo</option>
			<?php foreach ($periodoPermitidos as $periodo) {?>
					<option value="<?php echo $periodo?>"><?php echo $periodo?></option>
			<?php } ?>
			</select>
		</p>
		<button type="submit" name="Submit">Listar Beneficiarios</button>
	</form>
</div>
</body>
</html>