<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

$sqlTransfe = "SELECT * FROM transferenciasusimra order by idtransferencia DESC";
$resTransfe = mysql_query($sqlTransfe,$db);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/blue/style.css" type="text/css" id="" media="print, projection, screen" />
<link rel="stylesheet" href="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.css" type="text/css" id="" media="print, projection, screen" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Trasnferencia USIMRA :.</title>
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
		.tablesorter({widthFixed: true, headers:{5:{sorter:false}}})
		.tablesorterPager({container: $("#paginador")}); 
	});
</script>
<body bgcolor="#B2A274">
<div align="center">
	 <input type="reset" name="volver" value="Volver" onclick="location.href = 'documentosBancarios.php'" align="center"/>
	<p><span class="Estilo2">Transferencias Bancarias</span></p>
	<p>
	  <label>
	  <input type="submit" name="Submit" value="Cargar Nueva Transferencia" onclick="location.href = 'nuevaTransferencia.php'"/>
	  </label>
	</p>
	<table class="tablesorter" id="listado" style="width:800px">
	<thead>
		<tr>
			<th>Nro.</th>
			<th>Banco</th>
			<th>C.U.I.T.</th>
			<th>Fecha</th>
			<th>Monto</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowTransfe = mysql_fetch_assoc($resTransfe)) {
			$nroTrans = $rowTransfe['idtransferencia'];
		?>
		<tr>
			<td><?php echo $nroTrans;?></td>
			<td><?php echo $rowTransfe['banco'];?></td>
			<td><?php echo $rowTransfe['cuit'];?></td>
			<td><?php echo $rowTransfe['fecha'];?></td>
			<td><?php echo $rowTransfe['monto'];?></td>
			<td align="center"><a href="<?php echo "consultaTransferencia.php?nrotrans=$nroTrans" ?>">Consultar</a> - <a href="<?php echo "modificaTransferencia.php?nrotrans=$nroTrans" ?>">Modificar</a></td>
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
			  <img src="img/first.png" width="16" height="16" class="first"/> <img src="img/prev.png" width="16" height="16" class="prev"/>
			  <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
		    <img src="img/next.png" width="16" height="16" class="next"/> <img src="img/last.png" width="16" height="16" class="last"/>
		    <select name="select" class="pagesize">
		      <option selected="selected" value="10">10 por pagina</option>
		      <option value="20">20 por pagina</option>
		      <option value="30">30 por pagina</option>
		      <option value="<?php echo $totalLeeAutorizacion;?>">Todos</option>
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