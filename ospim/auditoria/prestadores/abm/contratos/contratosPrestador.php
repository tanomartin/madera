<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre, nomenclador FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: ABM Contrato :.</title>
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
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">


	$(function() {
		$("#contratos")
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

	
</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
   <input type="reset" name="volver" value="Volver" onclick="location.href = '../prestador.php?codigo=<?php echo $codigo ?>'" align="center"/>
  </span></p>
  <p class="Estilo2">ABM de Contratos </p>
  <table width="500" border="1">
    <tr>
      <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Nombre / Raz&oacute;n Social</strong></div></td>
      <td><div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div></td>
    </tr>
  </table>
   <p><strong>Contratos</strong></p>
		<?php 
  		$sqlCabContrato = "SELECT c.* FROM cabcontratoprestador c  WHERE c.codigoprestador = $codigo";
		$resCabContrato = mysql_query($sqlCabContrato,$db);
		$numCabContrato = mysql_num_rows($resCabContrato);
		
		$today = date("Y-m-d");
		$sqlCabContratoAbiertos = "SELECT c.* FROM cabcontratoprestador c  WHERE c.codigoprestador = $codigo and (c.fechafin = '0000-00-00' or c.fechafin > '$today')";
		$resCabContratoAbiertos = mysql_query($sqlCabContratoAbiertos,$db);
		$numCabContratoAbiertos = mysql_num_rows($resCabContratoAbiertos);
		
		if ($numCabContratoAbiertos == 0) { ?>
			<p><input type="button" name="nuevoContrato" id="nuevoContrato" value="Nuevo Contrato" onclick="location.href='nuevoContrato.php?codigo=<?php echo $codigo ?>'"/></p>
  <?php } 
		
		if ($numCabContrato > 0) {
 		 ?>
        <table style="text-align:center; width:600px" id="contratos" class="tablesorter" >
          <thead>
            <tr>
             	<th>C&oacute;digo</th>
				<th>Fecha Inicio</th>
				<th>Fecha Fin</th>
				<th></th>
            </tr>
          </thead>
          <tbody>
            <?php
			while($rowCabContrato = mysql_fetch_array($resCabContrato)) { ?>
				<tr>
				<td><?php echo $rowCabContrato['idcontrato'];?></td>
				<td><?php echo invertirFecha($rowCabContrato['fechainicio']);?></td>
				<td><?php if($rowCabContrato['fechafin'] == "0000-00-00") {
							  echo "-";
						  } else {
						   	  echo invertirFecha($rowCabContrato['fechafin']);
						  }?></td>
				<td><?php if ($rowCabContrato['fechafin'] == "0000-00-00" || $rowCabContrato['fechafin'] > $today ) { ?> 
							<input type="button" value="Modificar Practicas" name="modifpracticas" id="modifpracticas" onclick="location.href='modificarPracticasContrato.php?idcontrato=<?php echo $rowCabContrato['idcontrato'] ?>&codigo=<?php echo $codigo ?>'"/> -
							<input type="button" value="Modificar Contrato" name="modifcontrato" id="modifcontrato" onclick="location.href='modificarContrato.php?idcontrato=<?php echo $rowCabContrato['idcontrato'] ?>&codigo=<?php echo $codigo ?>'"/>
					<?php } else { ?>
							<input type="button" value="Ver Practicas" name="verpracticas" id="verpracticas" />
					<?php }  ?>
				</td>
				</tr>
         <?php } ?>
          </tbody>
        </table>
        <p> 
        	<?php } else { 	print("<div style='color:#000099'><b> ESTE PRESTADOR NO TIENE CONTRATO CARGADO </b></div><br>"); } ?>	
			
		</p>

</div>
</body>
</html>