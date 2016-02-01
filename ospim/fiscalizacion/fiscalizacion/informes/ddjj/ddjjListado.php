<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 

$cuit = $_POST['dato'];
$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$canEmpresa = mysql_num_rows($resEmpresa);
if ($canEmpresa == 0) {
	header ("Location: consultaddjj.php?err=2");
} else {
	$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	$sqlDdjj = "SELECT * FROM cabddjjospim where cuit = $cuit order by anoddjj DESC, mesddjj DESC";
	$resDdjj = mysql_query($sqlDdjj,$db);
	$canDdjj = mysql_num_rows($resDdjj);
	if ($canDdjj == 0) {
		header ("Location: consultaddjj.php?err=1");
	}
}
	


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de DDJJ por C.U.I.T. :.</title>

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
<script>
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra","filter"],
			headers:{5:{sorter:false, filter: false}},
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
	
	function detalleDdjj(dire) {
		c= window.open(dire,"Detalle DDJJ","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
	}
</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	 <input type="button" name="volver" class="nover" value="Volver" onclick="location.href = 'consultaddjj.php'" />
	<p><span class="Estilo2">D.D.J.J. Empresa "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $rowEmpresa['cuit'] ?> (O.S.P.I.M.) </span></p>
	<table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	<thead>
		<tr>
			<th class="filter-select" data-placeholder="Seleccion Año">Año</th>
			<th class="filter-select" data-placeholder="Seleccion Mes">Mes</th>
			<th>Personal</th>
			<th>Remuneracion</th>
			<th>Remu. Decreto</th>
			<th class="nover">Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowDdjj = mysql_fetch_assoc($resDdjj)) {
			$linkDetalle = "ddjjDetalle.php?anoddjj=".$rowDdjj['anoddjj']."&mesddjj=".$rowDdjj['mesddjj']."&cuit=".$rowDdjj['cuit'];
		?>
		<tr align="center">
			<td><?php echo $rowDdjj['anoddjj'];?></td>
			<td><?php echo $rowDdjj['mesddjj'];?></td>
			<td><?php echo $rowDdjj['totalpersonal'];?></td>
			<td><?php echo $rowDdjj['totalremundeclarada'];?></td>
			<td><?php echo $rowDdjj['totalremundecreto'];?></td>
			<td class="nover"><input type="button" value="Detalle" onclick="detalleDdjj('<?php echo $linkDetalle ?>')" /></td>
		</tr>
		<?php
		}
		?>
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
		      <option value="<?php echo $canDdjj;?>">Todos</option>
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