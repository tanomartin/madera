<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 

$tipo = $_POST['group1'];
if ($tipo == 0) {
	$titulo = "NO ATENDIDOS";
	$sqlReque = "SELECT * from reqfiscalizospim WHERE procesoasignado = 0";
}
if ($tipo == 1) {
	$titulo = "ATENDIDOS";
	$sqlReque = "SELECT * from reqfiscalizospim WHERE procesoasignado != 0";
}
if ($tipo == 2) {
	$titulo = "ATENDIDOS Y NO ATENDIDOS";
	$sqlReque = "SELECT * from reqfiscalizospim";
}

$resReque = mysql_query($sqlReque,$db);
$canReque = mysql_num_rows($resReque);	
if ($canReque == 0) {
	if ($tipo == 0) {
		header ("Location: filtrosBusqueda.php?err=1");
	}
	if ($tipo == 1) {
		header ("Location: filtrosBusqueda.php?err=2");
	}
	if ($tipo == 2) {
		header ("Location: filtrosBusqueda.php?err=3");
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/blue/style.css" type="text/css" id="" media="print, projection, screen" />
<link rel="stylesheet" href="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.css" type="text/css" id="" media="print, projection, screen" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Requerimientos :.</title>
</head>
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
<script type="text/javascript" src="/lib/jquery.tablesorter/jquery-latest.js"></script>
<script type="text/javascript" src="/lib/jquery.tablesorter/jquery.metadata.js"></script>
<script type="text/javascript" src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({widthFixed: true})
		.tablesorterPager({container: $("#paginador")}); 
	});
</script>
<body bgcolor="#CCCCCC">
<div align="center">
	 <input type="reset" class="nover" name="volver" value="Volver" onclick="location.href = 'filtrosBusqueda.php'" align="center"/>
	<p><span class="Estilo2">Requerimientos "<?php echo $titulo?>" </span></p>
	<table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	<thead>
		<tr>
			<th>Nro.</th>
			<th>Fecha</th>
			<th>C.U.I.T.</th>
			<th>Proceso Asignado</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowReque = mysql_fetch_assoc($resReque)) {
		?>
		<tr align="center">
			<td><?php echo $rowReque['nrorequerimiento'];?></td>
			<td><?php echo invertirFecha($rowReque['fecharequerimiento']);?></td>
			<td><?php echo $rowReque['cuit'];?></td>
			<td><?php 
					if ($rowReque['procesoasignado'] == 0) {
						echo "No Atendido";
					}
					if ($rowReque['procesoasignado'] == 1) {
						echo "Liquidado";
					}
					if ($rowReque['procesoasignado'] == 2) {
						echo "En Inspección";
					}	
				?>
			</td>
		</tr>
		<?php
		}
		?>
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