<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Beneficiarios por Localidad :.</title>
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
<script type="text/javascript">
function validar(formulario) {
	if (formulario.delegacion.value == 0) {
		alert("Debe elegir una Delegacion");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Informe Excel<br>Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  	<p><input type="reset" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloInformes.php'" /></p>
	<form  name="localidaddelegacion" id="localidaddelegacion" method="post" onsubmit="return validar(this)" action="totalBeneficiariosLocalidadDelegacionExcel.php">
  	<p><span class="Estilo2">Beneficiarios por Localidad por Delegacion</span></p>
  	<?php if (isset($_GET['error'])) { 
			if ($_GET['error'] == 0) {
				$delegacion = $_GET['delegacion'];
				print("<p><font color='#0000FF'><b> Se gener� correctamente el informe de la delegacion $delegacion.<br>Lo encontrara en la carpeta correspondiente </b></font></p>");
		 	} 
  	 } ?>
	<p><strong>Delegacion</strong>
		<select name="delegacion" id="delegacion" class="nover">
			<option value="-1" selected="selected">Seleccione un Valor</option>
				  <?php 
							$sqlDelegaciones="SELECT codidelega, nombre FROM delegaciones WHERE codidelega <= 3200";
							$resDelegaciones= mysql_query($sqlDelegaciones,$db);
							while ($rowDelegaciones=mysql_fetch_array($resDelegaciones)) { 	?>
				  <option value="<?php echo $rowDelegaciones['codidelega']?>"><?php echo $rowDelegaciones['codidelega'].' - '.utf8_encode($rowDelegaciones['nombre'])  ?></option>
				  <?php } ?>
		</select></p>	
	<p><input type="submit" name="Submit" value="Generar Informe" class="nover"/></p>
	</form>
</div>
</body>
</html>
