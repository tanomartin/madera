<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 

$sqlOrdenes = "SELECT o.*, d.nombre as delegacion FROM ordenesconsulta o, delegaciones d
               WHERE o.autorizada = 0 and o.delcod = d.codidelega
               ORDER BY id DESC";
$resOrdenes = mysql_query($sqlOrdenes,$db);
$canOrdenes = mysql_num_rows($resOrdenes); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Historico Ordenes de Consulta :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#listaResultado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{9:{sorter:false, filter:false},9:{sorter:false, filter:false}},
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		}).tablesorterPager({container: $("#paginador")}); 
	});

    function muestraArchivo(id) {
    	param = "id=" + id;
    	opciones = "top=50,left=50,width=1205,height=800,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=no,resizable=no"
    	window.open ("mostrarArchivo.php?" + param, "", opciones);
    }

    function atender(id,accion,cuil) {
        if (accion == 3) {
            var todo = "APROBAR";
        } 
        if (accion == 2) {
        	var todo = "RECHAZAR";
        }
        var r = confirm("Desea "+todo+" la Orden de Consulta con 'ID "+id+"' - del CUIL "+cuil+"'");
        if (r == true) {
			console.log('accionar');
        }
        if (r == true) {
      		$.blockUI({ message: "<h1>Realizando accion pedida<br>Aguarde por favor...</h1>" });
			window.location.href = "atenderOrdenConsulta.php?id="+id+"&accion="+accion;
        }
    }

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onClick="location.href = 'menuOrdenesConsulta.php'"/></p>
  	<h3>Listado de Ordenes de Consulta a Autorizar</h3>
  	<?php if ($canOrdenes == 0) { ?>
			<div style='color:blue'><b>NO EXISTEN ORDENES DE CONSULTA QUE NECESITEN SER ATENDIDAS </b></div><br>
  	<?php } else { ?>
  			<table id="listaResultado" class="tablesorter" style="text-align: center;">
            	<thead>
            		<tr>
            			<th>Nro</th>
            			<th>Fecha</th>
            			<th>Fecha Vto</th>
            			<th class="filter-select" data-placeholder="Seleccione Delegación">Delegaci&oacute;n</th>
            			<th>C.U.I.L.</th>
            			<th>Afiliado</th>
            			<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
            			<th>Apellido y Nombre</th>
            			<th>C.U.I.L. Titular</th>
            			<th>+ INFO</th>
            			<th>Accion</th>
            		</tr>
            	</thead>
            	<tbody>
  			<?php while($rowOrdenes = mysql_fetch_array($resOrdenes)) { ?>
  				<tr>
        			<td><?php echo $rowOrdenes['id'];?></td>
        			<td><?php echo invertirFecha($rowOrdenes['fechaorden']);?></td>
        			<td><?php echo invertirFecha($rowOrdenes['fechavto']);?></td>
        			<td><?php echo $rowOrdenes['delcod']." - ".$rowOrdenes['delegacion'];?></td>
        			<td><?php echo $rowOrdenes['nrcuil']; ?></td>
        			<td><?php echo $rowOrdenes['nrafil']; ?></td>
        				<?php $codpar = $rowOrdenes['codpar'];
        			          if ($codpar == -1) { $codpar = 'RN'; } ?>
        			<td><?php if($rowOrdenes['codpar'] == 0) echo "Titular"; else echo "Familiar ".$codpar;?></td>
        			<td><?php echo $rowOrdenes['nombre'];?></td>
        			<td><?php echo $rowOrdenes['nrcuiltitular'];?></td>
        			<td width="5%">
        				<input type="button" value="Ver HC" onclick="muestraArchivo('<?php echo $rowOrdenes['id'] ?>')">
        			</td>
        			<td width="5%">
        				<img style="cursor: pointer" title="APROBAR" src="img/aprobar.png" width="30" height="30" border="0" onclick="atender('<?php echo $rowOrdenes['id'] ?>',3,'<?php echo $rowOrdenes['nrcuil'] ?>')"/><br>
        				<img style="cursor: pointer" title="RECHAZAR" src="img/rechazar.png" width="30" height="30" border="0" onclick="atender('<?php echo $rowOrdenes['id'] ?>',2,'<?php echo $rowOrdenes['nrcuil'] ?>')"/>
        			</td>
        		</tr>
        <?php } ?>
        	</tbody>
		</table>
		<table style="text-align: center; width: 245; border: 0">
        	<tr>
        		<td width="239">
        			<div id="paginador" class="pager">
        				<form>
        					<p>
        						<img src="img/first.png" width="16" height="16" class="first"/> <img src="img/prev.png" width="16" height="16" class="prev"/>
        						<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
        						<img src="img/next.png" width="16" height="16" class="next"/> <img src="img/last.png" width="16" height="16" class="last"/>
        						<select name="select" class="pagesize">
        							<option selected="selected" value="10">10 por pagina</option>
        							<option value="20">20 por pagina</option>
        							<option value="30">30 por pagina</option>
        							<option value="50">50 por pagina</option>
        							<option value="<?php echo $canOrdenes;?>">Todos</option>
        						</select>
        					</p>
        				</form>	
        			</div>
        		</td>
        	</tr>
        </table>
  	<?php } ?>
</div>
</body>
</html>
  	