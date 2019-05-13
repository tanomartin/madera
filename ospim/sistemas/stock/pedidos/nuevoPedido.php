<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
$sqlInsumo = "SELECT * FROM stockinsumo i, stock s WHERE i.id = s.id";
$resInsumo = mysql_query($sqlInsumo,$db);
$canInsumo = mysql_num_rows($resInsumo); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Productos :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	jQuery(function($){
		$("#fecsoli").mask("99-99-9999");
	});

	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra","filter"],
			headers:{2:{sorter:false, filter: false}, 9:{sorter:false, filter: false}},
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		});
	});
	
	function validar(formulario) {
		var cantidadInsumo = <?php echo $canInsumo ?>;
		if (formulario.fecsoli.value != "") {
			if (!esFechaValida(formulario.fecsoli.value)) {
				alert("Fecha de Inicio invalida");
				return false;
			}
		} else {
			alert("Debe colocar fecha de Solicitud del Pedido");
			return false;
		}
		var seleccion = false;
		for (var i=0; i<cantidadInsumo; i++) {
			var campo = "cantidad"+i;
			if (document.getElementById(campo).value != 0) {
				if (!esEnteroPositivo(document.getElementById(campo).value)) {
					alert("Debe ingresar una cantidad positiva");
					return false;
				} 
				seleccion = true;
			}
		}
		if(seleccion == false) {
			alert("Debe ingresar algun insumo al pedido");
			return false;
		}
		formulario.Submit.disabled = true;
		return true;
	}

	
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'pedidos.php'" /></p>
  <h3>Nuevo Pedido </h3>
  <form name="nuevoPedido" id="nuevoPedido" method="post" action="guardarNuevoPedido.php?cant=<?php echo  $canInsumo?>" onsubmit="return validar(this)">
	  <table width="800" border="0">
	    <tr>
	      <td><b>Fecha Solicitud</b></td>
	      <td><input name="fecsoli" type="text" id="fecsoli" size="9"/></td>
	      <td><b>Descripcion</b></td>
	      <td><textarea name="descripcion" cols="50" rows="3" id="descripcion"></textarea></td>
	    </tr>
	  </table>
	  <h3>Insumos</h3>  
	  <table class="tablesorter" id="listado" style="width:1000px; font-size:14px">
			  <thead>
				<tr>
				  <th>Codigo</th>
				  <th>Nombre</th>
				  <th>Descripcion</th>
				  <th>Producto</th>
				  <th class="filter-select" data-placeholder="Seleccion Estado">Estado</th>
				  <th>Pto. Promedio</th>
				  <th>Pto. Pedido</th>
				  <th>Stock Min.</th>
				  <th>Cantidad - Estado</th>
				  <th>Cantidad </th>
				</tr>
			 </thead>
			 <tbody>
				<?php	
					while ($rowInsumo = mysql_fetch_assoc($resInsumo)) { ?>
					<tr align="center">
						<td><?php echo $rowInsumo['id'] ?></td>
						<td><?php echo $rowInsumo['nombre']?></td>
						<td><?php echo $rowInsumo['descripcion'] ?></td>
						<td><?php $idInsumo = $rowInsumo['id'];
								  $sqlInsumoProducto = "SELECT p.activo as activo, p.nombre as prod, d.nombre as depto 
								  						FROM stockinsumoproducto i, stockproducto p, stockubicacionproducto u, departamentos d 
								  						WHERE i.idinsumo = $idInsumo and i.idproducto = p.id and p.id = u.id and u.departamento = d.id";
								  $resInsumoProducto = mysql_query($sqlInsumoProducto,$db);
							      $canInsumoProducto = mysql_num_rows($resInsumoProducto);
								  if ($canInsumoProducto == 0) {
										$activo = 1;
								  } else {
										$activo = 0;
										while ($rowInsumoProducto = mysql_fetch_assoc($resInsumoProducto)) {
											if ($rowInsumoProducto['activo'] == 0) {
												$color = "#FF0000";
												$activoTexto = "[INACTIVO]";
											} else {
												$color = "#000000";
												$activo += 1;
												$activoTexto = "";
											}
											print("<font color='$color'> * ".$rowInsumoProducto['prod']." (".$rowInsumoProducto['depto'].") ".$activoTexto."</font></br>");
										}
								  } ?>
						</td>
						<td><?php if ($activo == 0) { echo "INACTIVO"; } else { echo "ACTIVO"; } ?></td>
						<td><?php echo $rowInsumo['puntopromedio'] ?></td>
						<td><?php echo $rowInsumo['puntopedido'] ?></td>
						<td><?php echo $rowInsumo['stockminimo'] ?></td>
							<?php $color = "";
								$estado = "";
								if ($rowInsumo['cantidad'] <= $rowInsumo['puntopromedio']) {
									$color = "#CC9999";
									$estado = " - PUNTO PROMEDIO";
								}
								if ($rowInsumo['cantidad'] <= $rowInsumo['puntopedido']) {
									$color = "#CC33CC";
									$estado = " - PUNTO PEDIDO";
								}
								if ($rowInsumo['cantidad']  <= $rowInsumo['stockminimo']) {
									$color = "#FF0000";
									$estado = " - STOCK";
								}				
							?>
						<td style="color:<?php echo $color ?>"><?php echo $rowInsumo['cantidad'].$estado ?></td>
						<td><input style="display: none" name="idInsumo-<?php echo $rowInsumo['id'] ?>" id="idInsumo-<?php echo $rowInsumo['id'] ?>" size="4" value="<?php echo $rowInsumo['id'] ?>"/>
							<input name="cantidad<?php echo $rowInsumo['id'] ?>" id="cantidad<?php echo $rowInsumo['id'] ?>" size="4"/>
						</td>
					</tr>
			 <?php } ?>
			</tbody>
	  </table>
	  <p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>