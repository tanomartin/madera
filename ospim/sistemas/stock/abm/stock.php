<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>
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
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra","filter"],
			headers:{10:{sorter:false, filter: false}},
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
			alert("Debe ser un n�mero postivo");
			return false;
		}
		var pagina = "alta.php?idInsumo="+idInsumo+"&cantidad="+cantidad+"&stock="+stock;
		location.href=pagina;
	}

	function baja(confirma, id) {
		var redireccion = "cargarUsuarioBaja.php?idInsumo="+id;
		if (confirma == 1) {
			var r = confirm("Esta queriendo bajar el stock por debajo del minimo. Desea continuar?");
			if (r == true) {
				location.href = redireccion;
			}
		} else {
			location.href = redireccion;
		}	
	}
		
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onclick="location.href = '../menuStock.php'" /></p>
  <h3>STOCK</h3>
  <table class="tablesorter" id="listado" style="width:1000px; font-size:14px">
	  <thead>
		<tr>
		  <th>Codigo</th>
		  <th>Nombre</th>
		  <th>Descripcion</th>
		  <th width="300">Productos - Usuario</th>
		  <th class="filter-select" data-placeholder="Seleccion">Activo</th>
		  <th>Pto. Prom</th>
		  <th>Pto. Ped</th>
		  <th>Stock Min.</th>
		  <th>Stock Actual</th>
		  <th class="filter-select" data-placeholder="Seleccion">Estado</th>
		  <th>Acciones</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlInsumos = "SELECT i.*, s.* FROM stockinsumo i, stock s WHERE i.id = s.id";
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
						$sqlInsumoProducto = "SELECT p.activo as activo, p.nombre as prod, d.nombre as depto, s.nombre as usuario 
												FROM stockinsumoproducto i, stockproducto p, departamentos d, stockubicacionproducto u 
												LEFT OUTER JOIN usuarios s on u.idusuario = s.id
												WHERE i.idinsumo = $idInsumo and i.idproducto = p.id and p.id = u.id and u.departamento = d.id";
						$resInsumoProducto = mysql_query($sqlInsumoProducto,$db);		
						$canInsumoProducto = mysql_num_rows($resInsumoProducto);
						if ($canInsumoProducto == 0) {
							$activo = "SI";
						} else {
							$activo = "NO";
							while ($rowInsumoProducto = mysql_fetch_assoc($resInsumoProducto)) {
								$nombre = "";
								if ($rowInsumoProducto['activo'] == 0) {
									$colorProd = "#FF0000";
								} else {
									$colorProd = "#000000";
									$activo = "SI";
								}
								$nombre .= " * ".$rowInsumoProducto['prod']." (".$rowInsumoProducto['depto']."-".$rowInsumoProducto['usuario'].")"."</br>";
								print("<font color=".$colorProd.">".$nombre."</font>");	
							}
						}
					?>
					</td>
					<td><?php echo $activo ?></td>
					<td><?php echo $rowInsumos['puntopromedio'] ?></td>
					<td><?php echo $rowInsumos['puntopedido'] ?></td>
					<td><?php echo $rowInsumos['stockminimo'] ?></td>
					<?php 
						$color = "";
						$estado = "OK";
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
				  <?php if ($rowInsumos['cantidad'] > 0) {
				  			$confirma = 1;
					  		if ($rowInsumos['cantidad'] > $rowInsumos['stockminimo']) { 
					  			$confirma = 0;
							} ?> 
					  		<img src="../img/baja.png" width="20" height="20" border="0" onclick="baja(<?php echo $confirma ?>,<?php echo $rowInsumos['id']?>)"/><br>
				  <?php } ?>	
						<img src="../img/alta.png" width="20" height="20" border="0" onclick="alta(<?php echo $rowInsumos['id']?>,<?php echo $rowInsumos['cantidad']?>)"/>
					</td>
		</tr>
	 <?php } ?>
    </tbody>
  </table>
   <table style="width: 245; border: 0">
      <tr>
        <td width="239">
		<div id="paginador" class="pager">
		  <form>
			<p align="center">
			  <img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
			  <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
		      <img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
		      <select name="select" class="pagesize">
			      <option selected="selected" value="10">10 por pagina</option>
			      <option value="20">20 por pagina</option>
			      <option value="30">30 por pagina</option>
			      <option value="<?php echo $canInsumos;?>">Todos</option>
		      </select>
		    </p>
			<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
</div>
</body>
</html>

