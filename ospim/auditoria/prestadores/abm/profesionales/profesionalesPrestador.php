<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
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
		$("#practicas")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers:{2:{sorter:false}},
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
	
function activarDesactivar(accion, codigoprof, codigopresta) {
	if (accion == 0) {
		$.blockUI({ message: "<h1>Desactivando Profesional.<br> Espere por favor</h1>" });
	} else {
		$.blockUI({ message: "<h1>Activando Profesional.<br> Espere por favor</h1>" });
	}
	var pagina = "activarDesactivarProfesional.php?accion="+accion+"&codigoprof="+codigoprof+"&codigopresta="+codigopresta;
	location.href = pagina;
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
  		$sqlProf = "SELECT codigoprofesional, nombre, activo FROM profesionales WHERE codigoprestador = $codigo";
		$resProf = mysql_query($sqlProf,$db);
		$numProf  = mysql_num_rows($resProf);
		if ($numProf > 0) {
  ?>
	<table style="text-align:center; width:600px" id="practicas" class="tablesorter" >
			<thead>
			  <tr>
				<th>C&oacute;digo</th>
				<th>Nombre</th>
				<th>Accion</th>
			  </tr>
			</thead>
			<tbody>
			  <?php
			while($rowProf= mysql_fetch_array($resProf)) {
		?>
			  <tr>
				<td><?php echo $rowProf['codigoprofesional'];?></td>
				<td><?php echo $rowProf['nombre'];?></td>
				<td><?php if ($rowProf['activo'] == 0) { ?> 
							<input type="button" value="Activar" id="activar" onclick="activarDesactivar('1','<?php echo $rowProf['codigoprofesional']?>','<?php echo $codigo ?>')" />
					<?php } else { ?>
							<input type="button" value="Desactiar" id="desactivar" onclick="activarDesactivar('0','<?php echo $rowProf['codigoprofesional']?>','<?php echo $codigo ?>')" />
					<?php } ?>
				</td>
			  </tr>
			  <?php
			}
		?>
			</tbody>
  </table>
	<p>
		<input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" />
   </p>	  
	<?php } else { 	print("<p><div style='color:#FF0000'><b> ESTE PRESTADOR NO TIENE PROFESIONALES CARGADO </b></div></p>"); } ?>
	    
	
</div>
</body>
</html>