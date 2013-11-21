<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlReque = "SELECT * from reqfiscalizospim where procesoasignado = 2 and requerimientoanulado = 0 order by nrorequerimiento DESC";
$resReque = mysql_query($sqlReque,$db);
$canReque = mysql_num_rows($resReque);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/blue/style.css" type="text/css" id="" media="print, projection, screen" />
<link rel="stylesheet" href="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.css" type="text/css" id="" media="print, projection, screen" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Requerimientos en Inpsección:.</title>
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

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
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
	
jQuery(function($){
	$("#fecha").mask("99-99-9999");
});
</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuFiscalizaciones.php'" align="center"/>
  </span></p>
  	<p class="Estilo2">Listado de  Requerimiento en Inspecci&oacute;n  </p>
	<table class="tablesorter" id="listado" style="width:1000px; font-size:14px">
		<thead>
			  <tr>
				<th>Número</th>
				<th>Origen</th>
				<th>Solicitante</th>
				<th>Motivo</th>
				<th>Cuit</th>
				<th>Datos Inpección</th>
			  </tr>
		</thead>
		<tbody>
		  <?php while($rowReque = mysql_fetch_array($resReque)) { 
		  			if ($rowReque['origenrequerimiento'] == 1) {
						$origen = "Fiscalización";
					}
					if ($rowReque['origenrequerimiento'] == 2) {
						$origen = "Afiliaciones";
					}
					if ($rowReque['origenrequerimiento'] == 3) {
						$origen = "Prestación";
					} 
			?>
				  <tr align="center">
						<td><?php echo $rowReque['nrorequerimiento'] ?></td>
						<td><?php echo $origen ?></td>   
						<td><?php echo $rowReque['solicitarequerimiento'] ?></td>   
						<td><?php echo $rowReque['motivorequerimiento'] ?></td>   
						<td><?php echo $rowReque['cuit'] ?></td>   
						<td><a href='datosInspeccion.php?nroreq=<?php echo $rowReque['nrorequerimiento'] ?>'>Modificar</a></td>   
				  </tr>
		<?php }?>
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
		      <option value="<?php echo $canReque;?>">Todos</option>
		      </select>
		    </p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
</div>
</body>
</html>