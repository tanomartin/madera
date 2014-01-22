<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 
$id = $_GET['id'];

$sqlDetPedido = "SELECT d.*, d.descripcion as descri, i.* FROM detpedidos d, insumo i where d.idpedido = $id and d.idinsumo = i.id";
$resDetPedido = mysql_query($sqlDetPedido,$db);
$canDetPedido = mysql_num_rows($resDetPedido);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle Pedido :.</title>


<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/lib/funcionControl.js" type="text/javascript"></script>

<script>
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{3:{sorter:false, filter: false}, 4:{sorter:false, filter: false},  5:{sorter:false, filter: false}, 6:{sorter:false, filter: false}, 7:{sorter:false, filter: false}},
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
	});
	
	function calcularTotalFila(fila, costo) {
		var campo = "cantidad"+fila;
		canti = document.getElementById(campo).value
		total = canti * costo;
		campo = "totalfila"+fila;
		document.getElementById(campo).value = total;
		calcularTotal();
	}
	
	function calcularTotal() {
		cantidadInsumo = <?php echo $canDetPedido ?>;
		var total = 0;
		for (i=0; i<cantidadInsumo; i++) {
			var campo = "totalfila"+i;
			totFila = document.getElementById(campo).value;
			total = parseFloat(total) + parseFloat(totFila);
		}
		document.getElementById("total").value = total;
	}
	
	function validar(formulario) {
		cantidadInsumo = <?php echo $canDetPedido ?>;
		for (i=0; i<cantidadInsumo; i++) {
			var campo = "costo"+i;
			costo = document.getElementById(campo).value;		
			if (costo != "") {
				if (costo == 0 || !isNumberPositivo(costo)) {
					alert("El costo debe ser un numero postivo");
					return false;
				}
			}
			campo = "entregado"+i;
			entregado = document.getElementById(campo).value;
			campo = "cantidad"+i;
			pedido = document.getElementById(campo).value;
			if (entregado != "") {
				if ((!isNumberPositivo(entregado)) || (parseInt(entregado) > parseInt(pedido))) {
					alert("La cantidad entregada debe ser un numero entero menor o igual al pedido");
					return false;
				}
			}
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
<style type="text/css" media="print">
<!--
.nover {display:none}
-->

</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input class="nover"  type="reset" name="volver" value="Volver" onclick="location.href = 'pedidos.php'" align="center"/>
</p>
  <p><span class="Estilo1">Detalle del Pedido <?php echo $id ?> </span></p>
  <form id="modifDetPedido" name="modifDetPedido"  method="POST" action="guardarModifDetallePedido.php?id=<?php echo  $id?>&cantinsumos=<?php echo $canDetPedido?>" onSubmit="return validar(this)">
  <table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	  <thead>
		<tr>
		  <th>Codigo</th>
		  <th>Nombre</th>
		  <th>Descripcion</th>
		  <th>Cant. Pedido</th>
		  <th>Costo Unitario</th>
		  <th>Cant. Entregado</th>
		  <th>Total</th>
		  <th>Descripcion</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$i = 0;
			while ($rowDetPedido = mysql_fetch_assoc($resDetPedido)) { ?>
				<tr align="center">
					<td><input name="id<?php echo $i ?>" id="id<?php echo $i ?>" type="text" size="4" value="<?php echo $rowDetPedido['id'] ?>" readonly="readonly" style="background-color:#CCCCCC"/></td>
					<td><?php echo $rowDetPedido['nombre'] ?></td>	
					<td><?php echo $rowDetPedido['descripcion'] ?></td>	
					<td><input name="cantidad<?php echo $i ?>" id="cantidad<?php echo $i ?>" type="text" size="5" value="<?php echo $rowDetPedido['cantidadpedido'] ?>" readonly="readonly" style="background-color:#CCCCCC"  /></td>
					<td><input name="costo<?php echo $i ?>" id="costo<?php echo $i ?>" type="text" size="10" value="<?php echo $rowDetPedido['costounitario'] ?>" onchange="calcularTotalFila('<?php echo $i ?>', this.value)"/></td>
					<td><input name="entregado<?php echo $i ?>" id="entregado<?php echo $i ?>" type="text" size="5" value="<?php echo $rowDetPedido['cantidadentregada'] ?>"/></td>
					<td>
						<?php $totalfila = $rowDetPedido['cantidadpedido'] * $rowDetPedido['costounitario']; $total = $total + $totalfila ;?>
						<input name="totalfila<?php echo $i ?>" id="totalfila<?php echo $i ?>" type="text" size="10" value="<?php echo  $totalfila ?>" readonly="readonly" style="background-color:#CCCCCC"/>					</td>
					<td><textarea name="descrip<?php echo $i ?>" id="descrip<?php echo $i ?>" cols="" rows=""><?php echo $rowDetPedido['descri'] ?></textarea></td>
		</tr>
	 <?php $i++;} ?>
	 	<tr>
			<td colspan="6"><div align="right"><strong>TOTAL</strong></div></td>
			<td><input name="total" id="total" type="text" size="10" value="<?php echo  $total ?>" readonly="readonly" style="background-color:#CCCCCC" /></td>
			<td>&nbsp;</td>
		</tr>
    </tbody>
  </table>
  <p>
    <input class="nover" type="submit" name="Submit" value="Guardar Cambios" sub="sub"/>
  </p>
  <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
  </form>
</div>
</body>
</html>

