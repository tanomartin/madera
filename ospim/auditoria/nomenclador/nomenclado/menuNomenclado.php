<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$sqlNomen = "SELECT * FROM nomencladores WHERE id != 2";
$resNomen = mysql_query($sqlNomen,$db); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Menu Nomencladorer Nomenclados :.</title>

<script>
	function redireccion(dire) {
		var nomenclador = document.getElementById("nomenclador");
		var valorArray = nomenclador.options[nomenclador.selectedIndex].value.split("-");
		var idNomen = valorArray[0];
		var contrato = valorArray[1];
		var nombre = nomenclador.options[nomenclador.selectedIndex].text;
		if (idNomen == 0) {
			alert("Debe seleccionar un nomenclador");
		} else {
			redire =  dire + "?codigo=" + idNomen + "&nombre=" + nombre + "&contrato=" + contrato;
			window.location = redire;
		}
	}
</script>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuNomenclador.php'" /></p>
  <h3>Men&uacute; Nomenclados</h3>
  <form id="menunomen" name="menunomen">
	  <p>
	  	<select id="nomenclador" name="nomenclador">
	  		<option value="">Seleccione Nomenclador</option>
	  		<?php while($rowNomen = mysql_fetch_assoc($resNomen)) { ?>
	  			<option value="<?php echo $rowNomen['id']."-".$rowNomen['contrato'] ?>"><?php echo $rowNomen['nombre']?></option>
	  		<?php }?>
	  	</select>
	  </p>
	  <table width="200" border="3" style="text-align: center">
	    <tr>
		  <td width="200">
		  	<p>LISTADOR </p>
	        <p><a href="#"><img src="img/listador.png" width="90" height="90" border="0" alt="enviar" onclick="redireccion('listadorNomenclado.php')"/></a></p>
	      </td>
	    </tr>
	  </table>
  </form>
</div>
</body>
</html>
