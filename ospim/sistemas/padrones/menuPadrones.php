<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Stock :.</title>


<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
.Estilo7 {font-weight: bold}
</style>
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
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'" />
  </span></p>
  <p><span class="Estilo2">Men&uacute; Padrones</span></p>
  <form id="form1" name="form1" method="post" action="generarPadrones.php" onsubmit="return validar(this)">
    <p><strong>Seleccionar Prestadore</strong>s</p>
    <table width="500" border="1">
	<?php 
		$resPresta = mysql_query("SELECT * FROM capitados", $db);
		while($rowPresta = mysql_fetch_array($resPresta)) {  
			$codigo = $rowPresta['codigo'];
			$nombre = $rowPresta['nombre'];
			$capitado = $rowPresta['capitado']; 
			$tipo = $rowPresta['tipopadron']?>
			<tr>
				<td align="left"><?php echo $codigo." - ".$nombre?></td>
				<td><input type="checkbox" id="prestadores" name="<?php echo $codigo ?>" value="<?php echo $codigo."-".$capitado."-".$tipo ?>"/></td>
			</tr>
	<?php } ?>	 
    </table>  
    <p class="Estilo7">Periodo 
      <label>
		  <?php  $mes = date("n");
		  		 $anio = date("Y");
		  		 if ($mes == 1) { $anio =  $anio - 1; $mes = 12; } else { $mes = $mes - 1; } ?>
		  <input name="periodo" readonly="readonly" style="background-color:#CCCCCC; width:60px; text-align:center"  value="<?php echo $mes."-".$anio  ?>" type="text" />
      </label>
    </p>
    <p><input type="submit" name="Submit" value="Generar Padrones" /> </p>
  </form>
  </div>
</body>
</html>
