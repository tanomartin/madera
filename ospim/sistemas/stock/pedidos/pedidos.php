<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Productos :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
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
			headers:{2:{sorter:false, filter: false}, 5:{sorter:false, filter: false}},
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
	
	function generarExcel(id,tipo) {
		if (tipo == "cotiza") {
			$.blockUI({ message: "<h1>Generando Archivo de Pedido de Cotizacion</h1>" });
			dire = "generarExcelPedidoCotizacion.php?id="+id;
		} else {
			$.blockUI({ message: "<h1>Generando Archivo de Pedido de Insumos</h1>" });
			dire = "generarExcelPedido.php?id="+id;
		}	
		location.href=dire;
	}
	
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onclick="location.href = '../menuStock.php'" /></p>
  <h3>Listado de Pedidos </h3>
  <input name="nuevo" type="button" id="nuevo" onclick="location.href = 'nuevoPedido.php'"  value="Nuevo" />
  <table class="tablesorter" id="listado" style="width:80%; font-size:14px; text-align: center">
	  <thead>
		<tr>
		  <th>Codigo</th>
		  <th>Fecha Solicitud</th>
		  <th>Proveedor</th>
		  <th>Descripcion</th>
		  <th>Costo Total</th>
		  <th>Fecha Cierre</th>
		  <th width="38%">Acciones</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlPedido = "SELECT c.*, p.nombre FROM stockcabpedidos c, stockproveedor p WHERE c.idproveedor = p.id order by c.id DESC";
			$resPedido = mysql_query($sqlPedido,$db);
			$canPedido = mysql_num_rows($resPedido);
			while ($rowPedido = mysql_fetch_assoc($resPedido)) { ?>
			<tr>
				<td><?php echo $rowPedido['id'] ?></td>
				<td><?php echo invertirFecha($rowPedido['fechasolicitud']) ?></td>
				<td><?php echo $rowPedido['nombre'] ?></td>
				<td><?php echo $rowPedido['descripcion'] ?></td>
				<td><?php echo number_format($rowPedido['costototal'],2,",","."); ?></td>
				<td><?php if($rowPedido['fechacierre'] != "0000-00-00") {echo invertirFecha($rowPedido['fechacierre']); } ?></td>
				<td><?php if($rowPedido['fechacierre'] == "0000-00-00") { ?>
							<input type="button" value="Modificar Pedido" onclick="location.href = 'modificarPedido.php?id=<?php echo $rowPedido['id'] ?>' "/>
							<input type="button" value="Datos Proveedor" onclick="location.href = 'cargarDatosProvedor.php?id=<?php echo $rowPedido['id'] ?>' "/>
							<input type="button" value="Cotizacion" onclick='generarExcel("<?php echo $rowPedido['id'] ?>","cotiza") '/>
							<input type="button" value="Pedido" onclick='generarExcel("<?php echo $rowPedido['id'] ?>","pedido") '/>
					 <?php } else { ?>
					 		<input type="button" value="Ver Detalle" onclick="location.href = 'verDetallePedido.php?id=<?php echo $rowPedido['id'] ?>' "/>
				  <?php } ?>
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
			  <img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
			  <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
		      <img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
		      <select name="select" class="pagesize">
			      <option selected="selected" value="10">10 por pagina</option>
			      <option value="20">20 por pagina</option>
			      <option value="30">30 por pagina</option>
			      <option value="<?php echo $canPedido;?>">Todos</option>
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

