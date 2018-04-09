<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$sqlLista = "select * from valoresalcobrousimra where chequenrousimra = '' order by cuit";
$resLista = mysql_query( $sqlLista,$db); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Valores :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{8:{sorter:false, filter:false}},
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
	if(!formulario.valores) {
		alert("No hay valores para realizar");
		return false;
	}
	var grupo = formulario.valores;
	var total = grupo.length;
	if (total == null) {
		if (!formulario.valores.checked) {
			alert("Debe seleccionar algún valor al cobro");
			return false;
		} else {
			return true;
		}
	}
	var checkeados = 0; 
	for (var i = 0; i < total; i++) {
		if (grupo[i].checked) {
			checkeados++;
		}
	}
	if (checkeados == 0) {
		alert("Debe seleccionar algún valor al cobro");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}
</script>
</head>

<body bgcolor="#B2A274">
<div align="center"> 
  	<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'menuValores.php'" /></p>
	<h3>Listado Valores al Cobro</h3>
  	<form id="listadoValoresUsimra" name="listadoValoresUsimra" onsubmit="return validar(this)" method="post" action="cargaInfoChequeUsimra.php">
	    <table width="935" border="0" style="margin: 15px">
	      <tr>
	        <td><div align="left"><input class="nover" type="submit" name="Submit" value="Valor de Depósito" /></div></td>
	        <td><div align="right"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="left" /></div></td>
	      </tr>
	    </table>
   
   		<table class="tablesorter" id="listado" style="width:935px; font-size:14px">
     		<thead>
	  			<tr>
					<th>CUIT</th>
					<th>Raz&oacute;n Social</th>
					<th>Acuerdo</th>
					<th>Cuota</th>
					<th>Monto</th>
					<th>Nro Cheque</th>
					<th>Banco</th>
					<th>Fecha Cheque</th>
					<th>Seleccionar</th>
      			</tr>
	 		</thead>
	 		<tbody>
      <?php while ($rowLista = mysql_fetch_array($resLista)) {
				$cuit = $rowLista['cuit'];
				$nroacuerdo = $rowLista['nroacuerdo'];
				$nrocuota = $rowLista['nrocuota'];
				$sqlCuota = "select c.*, e.nombre from cuoacuerdosusimra c, empresas e where c.cuit = $cuit and c.nroacuerdo = $nroacuerdo and c.nrocuota = $nrocuota and c.cuit = e.cuit";
				$resCuota = mysql_query($sqlCuota,$db); 
				$rowCuota = mysql_fetch_array($resCuota); 
				$valor = $cuit.",".$rowLista['nroacuerdo'].",".$rowLista['nrocuota'].","; ?>
				<tr align="center">
					<td><?php echo $cuit ?> </td>
					<td><?php echo $rowCuota['nombre'] ?></td>
				    <td><?php echo $nroacuerdo ?></td>
					<td><?php echo $nrocuota ?></td>	
					<td><?php echo $rowCuota['montocuota'] ?></td>
					<td><?php echo $rowLista['chequenro'] ?></td>
					<td><?php echo $rowLista['chequebanco'] ?></td>
					<td><?php echo $rowLista['chequefecha'] ?></td>	
					<td><input type='checkbox' name='elegidos[]' id='valores' value='<?php echo $valor ?>' /></td>
				</tr>
			
		<?php }?>
			</tbody>
    	</table>
  </form>
</div>
</body>
</html>
