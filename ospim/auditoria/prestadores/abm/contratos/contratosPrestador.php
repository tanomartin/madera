<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlCabContrato = "SELECT c.*, prestadores.nombre, prestadores.codigoprestador
					FROM cabcontratoprestador c
					LEFT JOIN cabcontratoprestador ON cabcontratoprestador.idcontrato = c.idcontratotercero
					LEFT JOIN prestadores ON prestadores.codigoprestador = cabcontratoprestador.codigoprestador
					WHERE c.codigoprestador = $codigo";
$resCabContrato = mysql_query($sqlCabContrato,$db);
$numCabContrato = mysql_num_rows($resCabContrato);

$today = date("Y-m-d");
$sqlCabContratoAbiertos = "SELECT c.*, prestadores.nombre, prestadores.codigoprestador
							FROM cabcontratoprestador c 
							LEFT JOIN cabcontratoprestador ON cabcontratoprestador.idcontrato = c.idcontratotercero
							LEFT JOIN prestadores ON prestadores.codigoprestador = cabcontratoprestador.codigoprestador
							WHERE c.codigoprestador = $codigo and (c.fechafin is null or c.fechafin > '$today')";
$resCabContratoAbiertos = mysql_query($sqlCabContratoAbiertos,$db);
$numCabContratoAbiertos = mysql_num_rows($resCabContratoAbiertos); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: ABM Contrato :.</title>

<script src="/madera/lib/jquery.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
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

	function abrirPracticas(dire) {
		a= window.open(dire,"InfoPeriodoCuentaCorrienteEmpresa",
		"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
	}
	
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../prestador.php?codigo=<?php echo $codigo ?>'" /></p>
  <h3>ABM de Contratos </h3>
  <table width="500" border="1">
    <tr>
      <td width="100"><div align="right"><strong>Código</strong></div></td>
      <td width="400"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Razón Social</strong></div></td>
      <td><div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div></td>
    </tr>
  </table>
  <h3>Contratos</h3>
  <?php if ($numCabContratoAbiertos == 0) { ?>
			<p><input type="button" name="nuevoContrato" id="nuevoContrato" value="Nuevo Contrato" onclick="location.href='nuevoContrato.php?codigo=<?php echo $codigo ?>'"/></p>
  <?php } 
        if ($numCabContrato > 0) { ?>
        <table style="text-align:center; width:80%" id="contratos" class="tablesorter" >
          <thead>
            <tr>
             	<th>Código</th>
				<th>Fecha Inicio</th>
				<th>Fecha Fin</th>
				<th>Contrato de Tercero</th>
				<th>Contratos Asociados [ID - Prestador (codigo)]</th>
				<th>Acciones</th>
				<th>Acc. Cont. Abierto (propios)</th>
            </tr>
          </thead>
          <tbody>
      <?php while($rowCabContrato = mysql_fetch_array($resCabContrato)) { 
				$sqlContraRelacionados = "SELECT c.*, p.nombre, p.codigoprestador FROM cabcontratoprestador c, prestadores p
											WHERE c.idcontratotercero = ".$rowCabContrato['idcontrato']." and 
												  c.codigoprestador = p.codigoprestador";
 				$resContraRelacionados = mysql_query($sqlContraRelacionados,$db);
 				$numContraRelacionados = mysql_num_rows($resContraRelacionados); ?>
				<tr>
					<td><?php echo $rowCabContrato['idcontrato'];?></td>
					<td><?php echo invertirFecha($rowCabContrato['fechainicio']);?></td>
					<td><?php if($rowCabContrato['fechafin'] == NULL) {
								  echo "-";
							  } else {
							   	  echo invertirFecha($rowCabContrato['fechafin']);
							  }?></td>
					<td><?php if ($rowCabContrato['idcontratotercero'] == 0) { echo "-"; } else { echo $rowCabContrato['idcontratotercero']." - ".$rowCabContrato['nombre']. " (".$rowCabContrato['codigoprestador'].")"; } ?></td>
					<td><?php if ($numContraRelacionados == 0) { 
								echo "-"; 
							  } else {
								while($rowContraRelacionados = mysql_fetch_array($resContraRelacionados)) {
									echo $rowContraRelacionados['idcontrato']." - ".$rowContraRelacionados['nombre']." (".$rowContraRelacionados['codigoprestador'].")"."<hr>";
								}
	 						  } ?>
	 				</td>
					<td>
					
							<input type="button" value="Ver Practicas" name="verpracticas" id="verpracticas" onclick="javascript:abrirPracticas('consultaPracticasContrato.php?codigo=<?php echo $codigo?>&idcontrato=<?php echo $rowCabContrato['idcontrato']?>')" /></br>
					<?php if ($rowCabContrato['idcontratotercero'] == 0) { ?>	
							<input type="button" value="Agregar Practicas" name="addpracticas" id="addpracticas" onclick="location.href='agregarPracticas.php?idcontrato=<?php echo $rowCabContrato['idcontrato'] ?>&codigo=<?php echo $codigo ?>'"/>
				    <?php } ?>
					</td>
					<td>
					<?php  if (($rowCabContrato['fechafin'] == NULL || $rowCabContrato['fechafin'] > $today) and $rowCabContrato['idcontratotercero'] == 0) { ?> 
								<input type="button" value="Eliminar Practicas" name="eliminarPracticas" id="eliminarPracticas" onclick="location.href='eliminarPracticas.php?idcontrato=<?php echo $rowCabContrato['idcontrato'] ?>&codigo=<?php echo $codigo ?>'"/></br>
								<input type="button" value="Modificar Cabecera" name="modifcontrato" id="modifcontrato" onclick="location.href='modificarContrato.php?idcontrato=<?php echo $rowCabContrato['idcontrato'] ?>&codigo=<?php echo $codigo ?>'"/> </br>
								<input type="button" value="Duplicar Aum. %" name="aumentocontrato" id="modifcontrato" onclick="location.href='aumentoPorcentaje.php?idcontrato=<?php echo $rowCabContrato['idcontrato'] ?>&codigo=<?php echo $codigo ?>'"/>
					 <?php } ?>
					</td>
				</tr>
         <?php } ?>
          </tbody>
        </table>
        <?php } else { ?> 	
        			<h3><font color="red"> ESTE PRESTADOR NO TIENE CONTRATO CARGADO</font></h3>
        <?php } ?>	
</div>
</body>
</html>