<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 

$cuit = $_GET['cuit'];
$anoddjj = $_GET['anoddjj'];
$mesddjj = $_GET['mesddjj'];
$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	
$sqlDetalle = "SELECT * FROM detddjjospim FORCE INDEX (busqueda) where cuit = 30709033995 and anoddjj = 2012 and mesddjj = 4 ";
$resDetalle = mysql_query($sqlDetalle,$db);
$canDetalle = mysql_num_rows($resDetalle);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/blue/style.css" type="text/css" id="" media="print, projection, screen" />
<link rel="stylesheet" href="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.css" type="text/css" id="" media="print, projection, screen" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Aportes por C.U.I.T. :.</title>
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
  <p><span class="Estilo2">Detalle de DDJJ Empresa "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $rowEmpresa['cuit'] ?></span></p>
  <p><span class="Estilo2">Periodo: <?php echo $mesddjj ?>-<?php echo $anoddjj ?></span></p>
	<table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	<thead>
		<tr>
			<th>C.U.I.L.</th>
			<th>Remuneracion</th>
			<th>Adherentes</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowDetalle = mysql_fetch_assoc($resDetalle)) {
			$total = $total + $rowDetalle['remundeclarada'];
		?>
		<tr align="center">
			<td><?php echo $rowDetalle['cuil'];?></td>
			<td><?php echo $rowDetalle['remundeclarada'];?></td>
			<td><?php echo $rowDetalle['adherentes'];?></td>
		</tr>
		<?php
		}
		?>
		<tr align="center">
			<td></td>
			<td><b><?php echo number_format($total,2,',','.');?></b></td>
			<td></td>
		</tr>
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
		      <option value="<?php echo $canDetalle;?>">Todos</option>
		      </select>
		    </p>
			<p align="center"><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
</div>
</body>
</html>