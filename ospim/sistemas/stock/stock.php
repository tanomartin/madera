<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: STOCK :.</title>

<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script>
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra","filter"],
			headers:{9:{sorter:false, filter: false}},
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
			
		})
		.tablesorterPager({container: $("#paginador")}); 
	});
	
	function alta(idInsumo, stock) {
		var cantidad = prompt("Ingrese Cantidad: ");
		if (cantidad == null) {
			return false;
		}
		if (cantidad <= 0 || !esEnteroPositivo(cantidad)) {
			alert("Debe ser un número postivo");
			return false;
		}
		var pagina = "alta.php?idInsumo="+idInsumo+"&cantidad="+cantidad+"&stock="+stock;
		location.href=pagina;
	}
	
	function baja(idInsumo, stock) {
		var usuario = prompt("Ingrese Usuario que pidio el Insumo: ");
		if (usuario == null) {
			return false;
		}
		if (usuario == "") {
			alert("Debe ingrear el usuario que pidio el Insumo");
			return false;
		}
		var pagina = "baja.php?idInsumo="+idInsumo+"&usuario="+usuario+"&stock="+stock;
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
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuStock.php'" align="center"/>
</p>
  <p><span class="Estilo1">STOCK</span></p>
  <table class="tablesorter" id="listado" style="width:1000px; font-size:14px">
	  <thead>
		<tr>
		  <th>Codigo</th>
		  <th>Nombre</th>
		  <th>Descripcion</th>
		  <th>Productos - Usuario</th>
		  <th>Pto. Prom</th>
		  <th>Pto. Ped</th>
		  <th>Stock Min.</th>
		  <th>Stock Actual</th>
		  <th>Estado</th>
		  <th>Acciones</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlInsumos = "SELECT i.*, s.* FROM insumo i, stock s WHERE i.id = s.id";
			$resInsumos = mysql_query($sqlInsumos,$db);
			$canInsumos = mysql_num_rows($resInsumos);
			while ($rowInsumos = mysql_fetch_assoc($resInsumos)) { ?>
			<tr align="center">
					<td><?php echo $rowInsumos['id'] ?></td>
					<td><?php echo $rowInsumos['nombre'] ?></td>
					<td><?php echo $rowInsumos['descripcion'] ?></td>
					<td>
					<?php 
						$idInsumo = $rowInsumos['id'];
						$sqlInsumoProducto = "SELECT p.activo as activo, p.nombre as prod, d.nombre as depto, u.usuario FROM insumoproducto i, producto p, ubicacionproducto u, departamentos d WHERE i.idinsumo = $idInsumo and i.idproducto = p.id and p.id = u.id and u.departamento = d.id";
						$resInsumoProducto = mysql_query($sqlInsumoProducto,$db);
						while ($rowInsumoProducto = mysql_fetch_assoc($resInsumoProducto)) {
							if ($rowInsumoProducto['activo'] == 0) {
								$color = "#FF0000";
							} else {
								$color = "#000000";
							}
							print("<font color='$color'> * ".$rowInsumoProducto['prod']." (".$rowInsumoProducto['depto']." - ".$rowInsumoProducto['usuario'].")"."</font></br>");
						}
					?>
					</td>
					<td><?php echo $rowInsumos['puntopromedio'] ?></td>
					<td><?php echo $rowInsumos['puntopedido'] ?></td>
					<td><?php echo $rowInsumos['stockminimo'] ?></td>
					<?php 
						$color = "";
						$estado = " - ";
						if ($rowInsumos['cantidad'] <= $rowInsumos['puntopromedio']) {
							$color = "#CC9999";
							$estado = "PUNTO PROMEDIO";
						}
						if ($rowInsumos['cantidad'] <= $rowInsumos['puntopedido']) {
							$color = "#CC33CC";
							$estado = "PUNTO PEDIDO";
						}
						if ($rowInsumos['cantidad']  <= $rowInsumos['stockminimo']) {
							$color = "#FF0000";
							$estado = "STOCK";
						}				
					?>
					<td style="color:<?php echo $color ?>"><?php echo $rowInsumos['cantidad'] ?></td>
					<td style="color:<?php echo $color ?>"><?php echo $estado ?></td>
					<td>
				  <?php if ($rowInsumos['cantidad'] > $rowInsumos['stockminimo']) { ?>
						<img src="img/baja.png" width="20" height="20" border="0" alt="enviar" onclick="baja(<?php echo $rowInsumos['id']?>,<?php echo $rowInsumos['cantidad']?>)"/></br>
				  <?php } ?> 
						<img src="img/alta.png" width="20" height="20" border="0" alt="enviar" onclick="alta(<?php echo $rowInsumos['id']?>,<?php echo $rowInsumos['cantidad']?>)"/>
					</td>
		</tr>
	 <?php } ?>
    </tbody>
  </table>
   <table width="245" border="0">
      <tr>
        <td width="239">
		<div id="paginador" class="pager">
		  <form>
			<p align="center">
			  <img src="img/first.png" width="16" height="16" class="first"/> <img src="img/prev.png" width="16" height="16" class="prev"/>
			  <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
		    <img src="img/next.png" width="16" height="16" class="next"/> <img src="img/last.png" width="16" height="16" class="last"/>
		    <select name="select" class="pagesize">
		      <option selected="selected" value="10">10 por pagina</option>
		      <option value="20">20 por pagina</option>
		      <option value="30">30 por pagina</option>
		      <option value="<?php echo $canInsumos;?>">Todos</option>
		      </select>
		    </p>
			<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
</div>
</body>
</html>

