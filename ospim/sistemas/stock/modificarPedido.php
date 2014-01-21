<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

$id = $_GET['id'];

$sqlPedido = "SELECT * FROM cabpedidos WHERE id = $id";
$resPedido = mysql_query($sqlPedido,$db);
$rowPedido = mysql_fetch_assoc($resPedido);

$sqlInsumo = "SELECT * FROM insumo i, stock s WHERE i.id = s.id";
$resInsumo = mysql_query($sqlInsumo,$db);
$canInsumo = mysql_num_rows($resInsumo);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Productos :.</title>


<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
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
			headers:{2:{sorter:false, filter: false}, 7:{sorter:false, filter: false}},
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
	
	function validar(formulario) {
		cantidadInsumo = <?php echo $canInsumo ?>;
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
		for (i=0; i<cantidadInsumo; i++) {
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
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'pedidos.php'" align="center"/>
</p>
  <p><span class="Estilo1">Modificar Pedido </span></p>
  <form name="nuevoPedido" id="nuevoPedido" method="POST" action="guardarModifPedido.php?cant=<?php echo  $canInsumo?>&id=<?php echo $id ?>" onSubmit="return validar(this)">
  <table width="800" border="0">
    <tr>
      <td>Fecha Solicitud </td>
      <td>
        <input name="fecsoli" type="text" id="fecsoli" size="11" value="<?php echo invertirFecha($rowPedido['fechasolicitud']) ?> "/>     </td>
      <td>Descripcion</td>
      <td>
        <textarea name="descripcion" cols="50" rows="3" id="descripcion"><?php echo $rowPedido['descripcion'] ?></textarea>     </td>
    </tr>
  </table>
  <p class="Estilo1">Insumos</p>  
	  <table class="tablesorter" id="listado" style="width:800px; font-size:14px">
		  <thead>
			<tr>
			  <th>Codigo</th>
			  <th>Nombre</th>
			  <th>Descripcion</th>
			  <th>Pto. Promedio</th>
			  <th>Pto. Pedido</th>
			  <th>Stock Min.</th>
			  <th>Cantidad - Estado</th>
			  <th>Cantidad </th>
			</tr>
		 </thead>
		 <tbody>
			<?php	
				$i = 0;
				while ($rowInsumo = mysql_fetch_assoc($resInsumo)) { ?>
				<tr align="center">
						<td><?php echo $rowInsumo['id'] ?></td>
						<td><?php echo $rowInsumo['nombre']?></td>
						<td><?php echo $rowInsumo['descripcion'] ?></td>
						<td><?php echo $rowInsumo['puntopromedio'] ?></td>
						<td><?php echo $rowInsumo['puntopedido'] ?></td>
						<td><?php echo $rowInsumo['stockminimo'] ?></td>
						<?php 
							$color = "";
							$estado = " - ";
							if ($rowInsumo['cantidad'] <= $rowInsumo['puntopromedio']) {
								$color = "#CC9999";
								$estado = "PUNTO PROMEDIO";
							}
							if ($rowInsumo['cantidad'] <= $rowInsumo['puntopedido']) {
								$color = "#CC33CC";
								$estado = "PUNTO PEDIDO";
							}
							if ($rowInsumo['cantidad']  <= $rowInsumo['stockminimo']) {
								$color = "#FF0000";
								$estado = "STOCK";
							}				
						?>
						<td style="color:<?php echo $color ?>"><?php echo $rowInsumo['cantidad']." - ".$estado ?></td>
						<td> 
							<input style="visibility:hidden" name="idInsumo<?php echo $i ?>" id="idInsumo<?php echo $i ?>" size="4" value="<?php echo $rowInsumo['id'] ?>"/>
						<?php			
							$idinsumo = $rowInsumo['id'];				
							$sqlInsumoPedido = "SELECT * FROM cabpedidos c, detpedidos d WHERE id = $id and c.id = d.idpedido and d.idinsumo = $idinsumo";
							$resInsumoPedido = mysql_query($sqlInsumoPedido,$db);
							$canInsumoPedido = mysql_num_rows($resInsumoPedido);
							if ($canInsumoPedido == 1) {
								$rowInsumoPedido = mysql_fetch_assoc($resInsumoPedido);
								$cantidad = $rowInsumoPedido['cantidadpedido'];
							} else {
								$cantidad = "";
							}
						?>
							<input name="cantidad<?php echo $i ?>" id="cantidad<?php echo $i ?>" value="<?php echo $cantidad ?>"  size="4"/> 
						</td>
			</tr>
		 <?php $i++;} ?>
		</tbody>
	  </table>
      <p>
        <input type="submit" name="Submit" value="Modificar" sub="sub"/>
      </p>
  </form>
</div>
</body>
</html>

