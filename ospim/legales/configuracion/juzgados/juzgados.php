<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/blue/style.css" type="text/css" id="" media="print, projection, screen" />
<link rel="stylesheet" href="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.css" type="text/css" id="" media="print, projection, screen" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Juzgados :.</title>
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
		.tablesorter({widthFixed: true, headers:{3:{sorter:false}}})
		.tablesorterPager({container: $("#paginador")}); 
	});
</script>
<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuConfiguracion.php'" align="center"/>
</p>
  <p><span class="Estilo2">Juzgados</span></p>
  <input name="nuevo" type="button" id="nuevo" onclick="location.href = 'nuevoJuzgado.php'"  value="Nuevo" />
  <table class="tablesorter" id="listado" style="width:950px; font-size:14px">
	  <thead>
		<tr>
		  <th>Codigo</th>
		  <th>Denominación</th>
		  <th>Fueros</th>
		  <th>Acciones</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlJuzgados = "select * from juzgados";
			$resJuzgados = mysql_query($sqlJuzgados,$db); 
			$canJuzgados = mysql_num_rows($resJuzgados);
			while ($rowJuzgados = mysql_fetch_array($resJuzgados)) { ?>
			<tr align="center">
					<td><?php echo $rowJuzgados['codigojuzgado'] ?></td>
					<td><?php echo $rowJuzgados['denominacion']?></td>
					<td><?php echo $rowJuzgados['fueros']?></td>
					<td><a href='modificarJuzgado.php?codigo=<?php echo $rowJuzgados['codigojuzgado'] ?>'>Modificar</a></td>
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
		      <option value="<?php echo $canJuzgados;?>">Todos</option>
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
