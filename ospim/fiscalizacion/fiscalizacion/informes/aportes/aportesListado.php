<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 

$cuit = $_POST['cuit'];
$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$canEmpresa = mysql_num_rows($resEmpresa);
if ($canEmpresa == 0) {
	header ("Location: aportesCuit.php?err=2");
} else {
	$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	$sqlAportes = "SELECT * FROM afipprocesadas where cuit = $cuit and concepto != 'REM' order by anopago DESC, mespago DESC";
	$resAportes = mysql_query($sqlAportes,$db);
	$canAportes = mysql_num_rows($resAportes);
	if ($canAportes == 0) {
		header ("Location: aportesCuit.php?err=1");
	}
}
	


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
	 <input type="reset" name="volver" value="Volver" onclick="location.href = 'aportesCuit.php'" align="center"/>
	<p><span class="Estilo2">Aportes Empresa "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $rowEmpresa['cuit'] ?> </span></p>
	<table class="tablesorter" id="listado" style="width:800px">
	<thead>
		<tr>
			<th>Año</th>
			<th>Mes</th>
			<th>Concepto</th>
			<th>Fecha de Pago</th>
			<th>D / C</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowAportes = mysql_fetch_assoc($resAportes)) {
		?>
		<tr>
			<td><?php echo $rowAportes['anopago'];?></td>
			<td><?php echo $rowAportes['mespago'];?></td>
			<td><?php echo $rowAportes['concepto'];?></td>
			<td><?php echo invertirFecha($rowAportes['fechapago']);?></td>
			<td><?php echo $rowAportes['debitocredito'];?></td>
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
		      <option value="<?php echo $canAportes;?>">Todos</option>
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