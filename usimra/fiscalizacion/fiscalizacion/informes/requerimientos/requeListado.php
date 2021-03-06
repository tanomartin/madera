<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionUsimra.php");
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 

$consulta = $_POST['group1'];

if ($consulta == "noatendidos") {
	$sqlReque = "SELECT r.*, e.cuit, e.nombre, d.nombre as delega
	from reqfiscalizusimra r, empresas e, delegaciones d
	WHERE r.procesoasignado = 0 and r.requerimientoanulado = 0 and r.cuit = e.cuit and r.codidelega = d.codidelega ORDER BY r.nrorequerimiento DESC";
} else {
	$valor = $_POST['dato'];
	if ($consulta == "fecharequerimiento") {
		$valor = fechaParaGuardar($valor);
	}
	$sqlReque = "SELECT r.*, e.cuit, e.nombre, d.nombre as delega
	from reqfiscalizusimra r, empresas e, delegaciones d
	WHERE r.$consulta = '$valor' and r.cuit = e.cuit and r.codidelega = d.codidelega ORDER BY r.nrorequerimiento DESC";
}
	
//print($sqlReque);
$resReque = mysql_query($sqlReque,$db);
$canReque = mysql_num_rows($resReque);	
if ($canReque == 0) {
	header ("Location: filtrosBusqueda.php?err=1");
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Requerimientos :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
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
			widgets: ["zebra","filter"],
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

	function detalleRequerimiento(dire) {
		c= window.open(dire,"Detalle Requerimiento","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
	}
</script>
</head>

<body bgcolor="#B2A274">
<div align="center">
	 <input type="button" class="nover" name="volver" value="Volver" onclick="location.href = 'filtrosBusqueda.php'" />
	<p><span class="Estilo2">Resultado de Busqueda de Requerimientos (U.S.I.M.R.A.) </span></p>
	<table class="tablesorter" id="listado" style="width:900px; font-size:14px">
	<thead>
		<tr>
			<th>Nro.</th>
			<th>Fecha</th>
			<th>C.U.I.T.</th>
			<th>Raz�n Social</th>
			<th>Delegaci�n</th>
			<th>Proceso Asignado</th>
			<th>U. Registro</th>
			<th>U. Modificaci�n</th>
			<th>Accion</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowReque = mysql_fetch_assoc($resReque)) {
			$linkDetalle = "requeDetalle.php?nroreq=".$rowReque['nrorequerimiento']."&cuit=".$rowReque['cuit']; ?>
			<tr align="center">
				<td><?php echo $rowReque['nrorequerimiento'];?></td>
				<td><?php echo invertirFecha($rowReque['fecharequerimiento']);?></td>
				<td><?php echo $rowReque['cuit'];?></td>
				<td><?php echo $rowReque['nombre'];?></td>
				<td><?php echo $rowReque['delega'];?></td>
				<td><?php 
						if ($rowReque['requerimientoanulado'] == 1) {
							echo "Anulado - ".$rowReque['motivoanulacion'];
						} else {	
							if ($rowReque['procesoasignado'] == 0) {
								echo "No Atendido";
							}
							if ($rowReque['procesoasignado'] == 1) {
								echo "Liquidado";
							}
							if ($rowReque['procesoasignado'] == 2) {
								echo "En Inspecci�n";
							}
							if ($rowReque['requerimientoanulado'] == 1) {
								echo "Anulado - ".$rowReque['motivoanulacion'];
							}	
						}	
					?>
				</td>
				<td><?php echo $rowReque['usuarioregistro'];?></td>
				<td><?php echo $rowReque['usuariomodificacion'];?></td>
				<td class="nover"><input type="button" value="Detalle" onclick="detalleRequerimiento('<?php echo $linkDetalle ?>')" /></td>
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
			  <option value="50">50 por pagina</option>
		      <option value="<?php echo $canReque;?>">Todos</option>
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