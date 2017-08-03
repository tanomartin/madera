<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Titulares SSS por Delegacion :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	if (formulario.delegacion.value == 0) {
		alert("Debe elegir una Delegación");
		return false;
	}
	$.blockUI({ message: "<h1>Realizando Proceso Pedido... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<input type="reset" name="volver" value="Volver" class="nover" onclick="location.href = '../menuDescInfoTituSSS.php'" />
	<form name="titularesSSSAlta" id="titularesSSSAlta" method="post" onsubmit="return validar(this)" action="altaTitularesSSS.php">
  	<h2>Descarga Titulares SSS por Delegaci&oacute;n </h2>
	<table>
		<tr>
		  	<td>
				<div align="left">
				<strong>Delegación</strong>
				<select name="delegacion" id="delegacion" class="nover">
				  <option value="0" selected="selected">Seleccione un Valor </option>
				  <?php 
					$sqlDele="select codidelega,nombre from delegaciones where codidelega not in (3500,4000,4001)";
					$resDele= mysql_query($sqlDele,$db);
					while ($rowDele=mysql_fetch_array($resDele)) { 	?>
				  		<option value="<?php echo $rowDele['codidelega']."-".$rowDele['nombre'] ?>"><?php echo $rowDele['nombre']  ?></option>
				  <?php } ?>
				</select>
	      	</div>
			</td>
		</tr>
	</table>
	<p><input type="submit" name="Submit" value="Procesar" class="nover"/></p>
</form>
</div>
</body>
</html>
