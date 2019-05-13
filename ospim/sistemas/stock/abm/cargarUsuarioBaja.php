<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$idInsumo = $_GET['idInsumo'];
$sqlInsumo = "SELECT i.*, s.* FROM stockinsumo i, stock s WHERE i.id = $idInsumo and i.id = s.id";
$resInsumo = mysql_query($sqlInsumo,$db);
$rowInsumo = mysql_fetch_assoc($resInsumo);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: STOCK :.</title>

<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script>

function cargoUsuario(sector) {
	document.forms.consumo.usuario.length = 0;
	var o = document.createElement("OPTION");
	o.text = 'Seleccione Usuario';
	o.value = '0';
	document.forms.consumo.usuario.options.add(o);

<?php	$sqlUsuario = "select * from usuarios";
		$resUsuario = mysql_query($sqlUsuario,$db); 
		while ($rowUsuario = mysql_fetch_array($resUsuario)) { ?> 
			o = document.createElement("OPTION");
			o.text = '<?php echo $rowUsuario["nombre"]; ?>';
			o.value = <?php echo $rowUsuario["id"]; ?>;
			if (sector == <?php echo $rowUsuario["departamento"]; ?>) {
				document.forms.consumo.usuario.options.add(o);
			}
<?php } ?> 
}

function validar(formulario) {
	if (formulario.usuario.value == 0) {
		alert("Debe Seleccionar un usuario");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
	  <p><input type="reset" name="volver" value="Volver" onclick="location.href = 'stock.php'" /></p>
	  <p><span class="Estilo1">Cargar Consumo</span></p>
	  <p><span class="Estilo1">"<?php echo $rowInsumo['nombre'] ?>" (<?php echo $rowInsumo['descripcion']?>)</span></p>
	  <p><span class="Estilo1">Stock</span></p>
	  <table border="1" style="width: 600px">
	  	<thead>
	  		<tr>
	  			<th>Punto Promedio</th>
	  			<th>Punto Pedido</th>
	  			<th>Stock Minimo</th>
	  			<th>Cantidad</th>
	  		</tr>
	  	</thead>
	  	<tbody style="text-align: center;">
	  		<tr>
	  			<td><?php echo $rowInsumo['puntopromedio'] ?></td>
	  			<td><?php echo $rowInsumo['puntopedido'] ?></td>
	  			<td><?php echo $rowInsumo['stockminimo'] ?></td>
	  			<td><?php echo $rowInsumo['cantidad'] ?></td>
	  		</tr>
	  	</tbody>
	  </table>
	  <form id="consumo" name="consumo" method="post" action="baja.php?idInsumo=<?php echo  $idInsumo?>&stock=<?php echo  $rowInsumo['cantidad'] ?>" onsubmit="return validar(this)">
	  		<p><span class="Estilo1">Usuario</span></p>
	  		<p><select name="depto" id="depto" onchange="cargoUsuario(document.forms.consumo.depto[selectedIndex].value)">
                <option value="0">Seleccione Sector</option>
                	<?php 
						$sqlDepto = "Select * from departamentos";
						$resDepto = mysql_query($sqlDepto,$db);
						while ($rowDepto = mysql_fetch_assoc($resDepto)) { ?>
                			<option value="<?php echo $rowDepto['id'] ?>"><?php echo $rowDepto['nombre'] ?></option>
                  <?php } ?>
                </select></p>
	  		<p><select name="usuario">
                      <option value="0">Seleccione Usuario</option>
					</select></p>
	  		<p><input type="submit" id="Submit" value="Guardar"/></p>
	  </form>
	</div>
</body>
</html>