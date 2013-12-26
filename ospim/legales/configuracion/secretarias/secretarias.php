<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Secretarias :.</title>
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
<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra","filter"], 
			headers:{4:{sorter:false, filter: false}},
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
</script>
<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuConfiguracion.php'" align="center"/>
</p>
  <p><span class="Estilo2">Secretarias</span></p>
  <input name="nuevo" type="button" id="nuevo" onclick="location.href = 'nuevaSecretaria.php'"  value="Nueva" />
  <table class="tablesorter" id="listado" style="width:1000px; font-size:14px">
	  <thead>
		<tr>
		  <th data-placeholder="Inserte Código">Cod Juzgado</th>
		  <th data-placeholder="Inserte Denominación">Denominación Juzgado</th>
		  <th data-placeholder="Inserte Código">Cod. Secretaría</th>
		  <th data-placeholder="Inserte Denominación">Denominación</th>
		  <th>Acciones</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlSecretaria = "select s.codigosecretaria , s.codigojuzgado, j.denominacion as njuzgado, s.denominacion as nsecretaria from secretarias s, juzgados j where s.codigojuzgado = j.codigojuzgado order by s.codigojuzgado";
			$resSecretaria = mysql_query($sqlSecretaria,$db); 
			$canSecretaria = mysql_num_rows($resSecretaria);
			while ($rowSecretaria= mysql_fetch_assoc($resSecretaria)) { ?>
			<tr align="center">
					<td><?php echo $rowSecretaria['codigojuzgado'] ?> </td>
					<td><?php echo $rowSecretaria['njuzgado']?></td>
					<td><?php echo $rowSecretaria['codigosecretaria'] ?></td>
					<td><?php echo $rowSecretaria['nsecretaria']?></td>
					<td><a href='modificarSecretaria.php?secre=<?php echo $rowSecretaria['codigosecretaria'] ?>&juz=<?php echo $rowSecretaria['codigojuzgado'] ?>'>Modificar</a></td>
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
		      <option value="<?php echo $canSecretaria;?>">Todos</option>
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
