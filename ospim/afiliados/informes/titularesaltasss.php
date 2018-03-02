<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Titulares alta SSS por Delegacion :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	if (formulario.delegacion.value == 0) {
		alert("Debe elegir una Delegación");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Archivo<br>Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /></p>
  	
	<form  name="selectDelegacion" id="selectDelegacion" method="post" onsubmit="return validar(this)" action="titularesaltassspdf.php">
  	<h3>Listado Titulares Alta SSS por Delegaci&oacute;n </h3>
  	
  	<?php if (isset($_GET['error'])) { 
			if ($_GET['error'] == 0) {
				$nomdelega = $_GET['delega'];
				print("<p><font color='#0000FF'><b> Se generó correctamente el informe de la delegación $nomdelega.<br>Lo encontrara en la carpeta correspondiente </b></font></p>");
		 	} 
			if ($_GET['error'] == 1) {
				$descerror = $_GET['mensaje'];
				print("<p><font color='#FF0000'><b> Hubo un error. $descerror. Comuníquese con el Dpto. de Sistemas </b></font></p>");
			}
			if ($_GET['error'] == 2) {
				$nomdelega = $_GET['delega'];
				print("<p><font color='#0000FF'><b> No existen titulares de alta desde la SSS para la delegación $nomdelega.</b></font></p>");
			}
  	 } ?>
	<table>
		<tr>
		  	<td>
				<div align="left">
				<strong>Delegación</strong>
				<select name="delegacion" id="delegacion" class="nover">
				  <option value="0" selected="selected">Seleccione un Valor </option>
				  <?php 
							$sqlDele="select codidelega,nombre from delegaciones where codidelega not in (1000,1001,3500, 4000, 4001)";
							$resDele= mysql_query($sqlDele,$db);
							while ($rowDele=mysql_fetch_array($resDele)) { 	?>
				  <option value="<?php echo $rowDele['codidelega']."-".$rowDele['nombre'] ?>"><?php echo $rowDele['nombre']  ?></option>
				  <?php } ?>
				</select>
	      	</div>
			</td>
		</tr>
	</table>
	<p><input type="submit" name="Submit" value="Generar Archivo" class="nover"/></p>
</form>
</div>
</body>
</html>
