<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlExpediente = "SELECT e.nroafiliado, e.nroorden FROM discapacitadoexpendiente e WHERE e.completo = 0 ";
$resExpediente = mysql_query($sqlExpediente,$db);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Certificados por Fecha Vto. :.</title>
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
<style type="text/css" media="print">
.nover {display:none}
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#fechavto").mask("99-99-9999");
});

	$(function() {
		$("#tabla")
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


function validar(formulario) {
	var fechavto = formulario.fechavto.value;
	if (fechavto == "") {
		alert("Debe ingresar un fecha de vencimiento de certificado");
		return(false)
	} else {
		if (!esFechaValida(fechavto)) {
			alert("La fecha de fecha de vencimiento no es valida");
			return(false);
		} 
	}
	formulario.Submit.disabled = true;
	return true;
}


function abrirConsulta(dire){	
	window.open(dire,'consultaDiscapacitado','width=800, height=500');
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloInformes.php'" align="center"/></p>
	<p><span class="Estilo2">Expendientes Incompletos </span></p>
	<table style="text-align:center; width:800px" id="tabla" class="tablesorter" >
		<thead>
			<tr>
				<td>Nro. Afiliado</td>
				<td>Nombre y Apellido</td>
				<td class="filter-select" data-placeholder="Seleccione Tipo">Tipo Beneficiario</td>
				<td>Acciones</td>
			</tr>
		</thead>
		<?php while ($rowExpediente = mysql_fetch_assoc($resExpediente)) {?>
			<tr>
				<td><?php echo $rowExpediente['nroafiliado'] ?></td>	
				<td>
				<?php 
						$tipoBeneficiario = "";
						if ($rowExpediente['nroorden'] == 0) { 
							$sqlBeneficiario = "SELECT apellidoynombre FROM titulares WHERE nroafiliado = ".$rowExpediente['nroafiliado'];
							$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
							$canBeneficiario = mysql_num_rows($resBeneficiario);
							if($canBeneficiario == 0) {
								$sqlBeneficiario = "SELECT apellidoynombre FROM titularesdebaja WHERE nroafiliado = ".$rowExpediente['nroafiliado'];
								$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
								$canBeneficiario = mysql_num_rows($resBeneficiario);
								if($canBeneficiario == 0) {
									echo ('No se encontro el Beneficiario');
									$tipoBeneficiario = "-";
								} else {
									$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
									echo ($rowBeneficiario['apellidoynombre']);
									$tipoBeneficiario = "TITULAR INACTIVO";
									$activo = 0;
								}
							} else {
								$activo = 1;
								$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
								echo ($rowBeneficiario['apellidoynombre']);
								$tipoBeneficiario = "TITULAR";
							}
					   } else {
					   		$sqlBeneficiario = "SELECT apellidoynombre FROM familiares WHERE nroafiliado = ".$rowExpediente['nroafiliado']." and nroorden = ".$rowExpediente['nroorden'];
							$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
							$canBeneficiario = mysql_num_rows($resBeneficiario);
							if($canBeneficiario == 0) {
								$sqlBeneficiario = "SELECT apellidoynombre FROM familiaresdebaja WHERE nroafiliado = ".$rowExpediente['nroafiliado']." and nroorden = ".$rowExpediente['nroorden'];
								$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
								$canBeneficiario = mysql_num_rows($resBeneficiario);
								if($canBeneficiario == 0) {
									echo ('No se encontro el Beneficiario');
									$tipoBeneficiario = "-";
								} else {
									$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
									echo ($rowBeneficiario['apellidoynombre']);
									$tipoBeneficiario = "FAMILIAR INACTIVO";
									$activo = 0;
								}
							} else {
								$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
								echo ($rowBeneficiario['apellidoynombre']);
								$tipoBeneficiario = "FAMILIAR";
								$activo = 1;
							}	
					  	 } ?>
				</td>
				<td><?php echo $tipoBeneficiario ?></td>		
				<td><?php if ($canBeneficiario != 0) { ?>
					<input type='button' name='consultar' value='Consultar' onclick="abrirConsulta('../abm/consultarDiscapacitado.php?nroafiliado=<?php echo $rowExpediente['nroafiliado'] ?>&nroorden=<?php echo $rowExpediente['nroorden'] ?>&activo=<?php echo $activo ?>&nomostrar=1')" /></td>		
					<?php } ?>
				</tr>
		<?php } ?>
		<tbody>
		</tbody>
	</table>
    <p>
      <input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/>
  </p>
</div>
</body>
</html>
