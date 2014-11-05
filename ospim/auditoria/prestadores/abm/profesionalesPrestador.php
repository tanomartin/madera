<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Pofesionales Prestador :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#practicas")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
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
	});
	
function abrirPantalla(dire) {
	a= window.open(dire,'',
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

	
</script>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><strong>Profesionales del  Prestador</strong></p>
	  <table width="500" border="1">
        <tr>
          <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
          <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nombre / Raz&oacute;n Social</strong></div></td>
          <td><div align="left">
              <div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div>
          </div></td>
        </tr>
  </table>
  	  <?php 
  		$sqlProf = "SELECT codigoprofesional, nombre FROM profesionales WHERE codigoprestador = $codigo";
		$resProf = mysql_query($sqlProf,$db);
		$numProf  = mysql_num_rows($resProf);
		if ($numProf > 0) {
  ?>
	<p>
	<table style="text-align:center; width:600px" id="practicas" class="tablesorter" >
			<thead>
			  <tr>
				<th>C&oacute;digo</th>
				<th>Nombre</th>
			  </tr>
			</thead>
			<tbody>
			  <?php
			while($rowProf= mysql_fetch_array($resProf)) {
		?>
			  <tr>
				<td><?php echo $rowProf['codigoprofesional'];?></td>
				<td><?php echo $rowProf['nombre'];?></td>
			  </tr>
			  <?php
			}
		?>
			</tbody>
  </table>
  </p>
	<p>
		<input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/>
   </p>	  
	<?php } else { 	print("<p><div style='color:#FF0000'><b> ESTE PRESTADOR NO TIENE PROFESIONALES CARGADO </b></div></p>"); } ?>
	    
	
</div>
</body>
</html>