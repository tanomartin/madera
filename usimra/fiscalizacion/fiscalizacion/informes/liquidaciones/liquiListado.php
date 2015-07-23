<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionUsimra.php");
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 

$consulta = 'c.'.$_POST['group1'];
$valor = $_POST['dato'];
if ($consulta == "c.fechaliquidacion") {
	$valor = fechaParaGuardar($valor);
}
if ($consulta == "c.cuit") {
	$consulta = 'e.'.$_POST['group1'];
}
$sqlLiqui = "SELECT c.*, e.cuit, e.nombre, d.nombre as delega
from cabliquiusimra c, reqfiscalizusimra r, empresas e, delegaciones d
WHERE $consulta = '$valor' and c.nrorequerimiento = r.nrorequerimiento and r.cuit = e.cuit and r.codidelega = d.codidelega ORDER BY c.nrorequerimiento DESC";
//print($sqlLiqui);
$resLiqui = mysql_query($sqlLiqui,$db);
$canLiqui = mysql_num_rows($resLiqui);	
if ($canLiqui == 0) {
	header ("Location: filtrosBusqueda.php?err=1");
}


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Liquidaciones :.</title>

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
			headers:{7:{sorter:false, filter:false}, 8:{sorter:false, filter:false}, 9:{sorter:false, filter:false}, 10:{sorter:false, filter:false}, 11:{sorter:false, filter:false}, 15:{sorter:false, filter:false}},
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
	
function abrirAcuInclu(dire) {
	a= window.open(dire,"InfoAcuIncluidos",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>
</head>

<body bgcolor="#B2A274">
<div align="center">
	 <input type="button" name="volver" value="Volver" onclick="location.href = 'filtrosBusqueda.php'"/>
	<p><span class="Estilo2">Liquidaciones</span></p>
	<table class="tablesorter" id="listado" style="width:1200px; font-size:14px">
	<thead>
		<tr>
			<th>Nro. Req.</th>
			<th>C.U.I.T.</th>
			<th>Razon Social</th>
			<th>Delegacion</th>
			<th>Fecha Liq.</th>
			<th>Hora Liq.</th>
			<th>Liquidación Origen</th>
			<th>Acu. Incluidos</th>
			<th style="width:80px">Fecha Inspección</th>
			<th>Deuda Nominal</th>
			<th>Intereses</th>
			<th>Gastos Admin.</th>
			<th>Total</th>
			<th>Resolución</th>
			<th>Certificado Deuda</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowLiqui = mysql_fetch_assoc($resLiqui)) {
		?>
			<tr align="center">
				<td><?php echo $rowLiqui['nrorequerimiento'];?></td>
				<td><?php echo $rowLiqui['cuit'];?></td>
				<td><?php echo $rowLiqui['nombre'];?></td>
				<td><?php echo $rowLiqui['delega'];?></td>
				<td style="width:80px"><?php echo $rowLiqui['fechaliquidacion'] ?></td>
				<td><?php echo $rowLiqui['horaliquidacion'] ?></td>
				<td><?php echo $rowLiqui['liquidacionorigen'];?></td>
				<td>
				<?php 
					$nroreque = $rowLiqui['nrorequerimiento'];	
					$cuit = $rowLiqui['cuit'];	
					$sqlAcuInc = "SELECT * from aculiquiusimra WHERE nrorequerimiento = $nroreque";
					$resAcuInc = mysql_query($sqlAcuInc,$db);
					$canAcuInc = mysql_num_rows($resAcuInc);
					if ($canAcuInc == 0) {
						echo "-";
					} else {
						print("<a href=javascript:abrirAcuInclu('infoAcuInlcu.php?req=".$nroreque."&cuit=".$cuit."')>".$canAcuInc." Acuer.</a>");
					}
				?></td>
				<td><?php if ($rowLiqui['fechainspeccion'] != NULL && $rowLiqui['fechainspeccion'] != "0000-00-00") { echo invertirFecha($rowLiqui['fechainspeccion']); } else { echo "-"; }?></td>
				<td><?php echo $rowLiqui['deudanominal'];?></td>
				<td><?php echo $rowLiqui['intereses'];?></td>
				<td><?php echo $rowLiqui['gtosadmin'];?></td>
				<td><?php echo $rowLiqui['totalliquidado'];?></td>
				<td><?php echo $rowLiqui['nroresolucioninspeccion'];?></td>
				<td><?php echo $rowLiqui['nrocertificadodeuda'];?></td>
				<td><?php if ($rowLiqui['liquidacionanulada'] == 0) { ?>
					<input name="anular" value="Anular" type="button" onclick="location.href='moduloEliminarLiquidacion.php?nroreq=<?php echo $nroreque ?>&dato=<?php echo $_POST['dato'] ?>&group1=<?php echo $_POST['group1'] ?>&cuit=<?php echo $cuit ?>'" />
					<?php } else { 
							print("ANULADA");
						 } ?>	
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
  </table>
    <table style="width: 245; border: 0;">
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
		      <option value="<?php echo $canLiqui;?>">Todos</option>
		      </select>
		    </p>
			<p align="center"><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
</div>
</body>
</html>