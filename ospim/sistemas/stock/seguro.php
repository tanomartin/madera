<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Productos Seguro :.</title>


<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script>

function actualizar() {
	var poliza = prompt("Ingrese Número de Poliza");
		if (poliza == null) {
			return false;
		}
		if (poliza == "") {
			alert("Debe ingrear el Número de Poliza");
			return false;
		}
		var pagina = "actualizarPoliza.php?poliza="+poliza;
		location.href=pagina;
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

<style type="text/css" media="print">
<!--
.nover {display:none}
-->

</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input class="nover" type="reset" name="volver" value="Volver" onclick="location.href = 'menuStock.php'" align="center"/>
</p>
  <p><span class="Estilo1">Listado Para el Seguro</span> </p>
  <p align="center"><input class="nover" type="button" name="actualizar" value="Actualizar Poliza" onclick="actualizar()"/></p>
  <table id="listado" style="width:800px; font-size:14px" border="1">
	  <thead>
		<tr>
		  <th>Nombre</th>
		  <th>Descripcion</th>
		  <th>Nro de Serie</th>
		  <th>Valor Original</th>
	    </tr>
	 </thead>
	 <tbody>
	 	
		<?php	
			$sqlProd = "SELECT * FROM producto p, ubicacionproducto u WHERE p.activo = 1 and p.id = u.id and u.pertenencia = 'O'";
			$resProd = mysql_query($sqlProd,$db);
			while ($rowProd = mysql_fetch_assoc($resProd)) { ?>		
			<tr align="center">
					<td><?php echo $rowProd['nombre']?></td>
					<td><?php echo $rowProd['descripcion'] ?></td>
					<td><?php echo $rowProd['numeroserie'] ?></td>
					<td><?php echo "$ ".$rowProd['valororiginal'] ?></td>
		</tr>
	 <?php } ?>
	 
    </tbody>
  </table>
   <p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
</div>
</body>
</html>

