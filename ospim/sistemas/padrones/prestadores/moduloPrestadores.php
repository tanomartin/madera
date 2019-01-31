<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$mes = date("n");
$anio = date("Y");
$dia = date("j");
$quincena = 2;
if ($dia > 15) {
	$quincena = 1;
} else {
	if ($mes == 1) {
		$anio =  $anio - 1; $mes = 12;
	} else {
		$mes = $mes - 1;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Stock :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validar(formulario) {
	var prestaCheck = 0;
	var grupo = formulario.prestadores;
	var total = grupo.length;
	if (total != null) {
		for (var x=0; x<total; x++) {
			if(grupo[x].checked) {
				prestaCheck = 1;
			}
		}
	}
	if (prestaCheck == 0) {
		alert("Debe Seleccionar por lo menos un Prestador");
		return(false);
	}
	if (formulario.periodo.value == 0) {
		alert("Debe Seleccionar un Período");
		return(false);
	}
	$.blockUI({ message: "<h1>Generando Padrones<br>Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuPadrones.php'" /></p>
  <h2>Menú Padrones</h2>
  <form id="form1" name="form1" method="post" action="generarPadrones.php" onsubmit="return validar(this)">
    <h3>Seleccionar Prestadore</h3>
    <table width="500" border="1">
  <?php $resPresta = mysql_query("SELECT * FROM capitados", $db);
		while($rowPresta = mysql_fetch_array($resPresta)) {  
			$codigo = $rowPresta['codigo'];
			$nombre = $rowPresta['nombre'];
			$capitado = $rowPresta['capitado']; 
			$tipo = $rowPresta['tipopadron'];
			$medicina = $rowPresta['medicina'];?>
			<tr>
				<td align="left"><?php echo $codigo." - ".$nombre?></td>
				<td><input type="checkbox" id="prestadores" name="<?php echo $codigo ?>" value="<?php echo $codigo."-".$capitado."-".$tipo."-".$medicina ?>" /></td>
			</tr>
	<?php } ?>	 
    </table>  
    <p><b>Periodo </b><input name="periodo" readonly="readonly" style="background-color:#CCCCCC; width:80px; text-align:center"  value="<?php echo $mes."-".$anio."-".$quincena  ?>" type="text" /></p>
    <p><input type="submit" name="Submit" value="GENERAR PADRONES" /></p>
  </form>
  </div>
</body>
</html>
