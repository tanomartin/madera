<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

if (isset($_POST['fechavto'])) {
	$fechavto = fechaParaGuardar($_POST['fechavto']);
	$sqlDiscacitados = "SELECT * FROM discapacitados WHERE vencimientocertificado < '$fechavto' order by vencimientocertificado ASC";
	$resDiscacitados = mysql_query($sqlDiscacitados,$db);
}

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

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
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

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloInformes.php'" align="center"/></p>
	<form  name="listadoEmpresa" id="listadoEmpresa" method="post" onSubmit="return validar(this)" action="certificadosPorVto.php">
  	<p><span class="Estilo2">Certificados  por Fecha de Vencimiento </span></p>
	<table>
		<tr>
		  	<td>
			  <div align="left" class="nover">
				  <strong>Fecha
				  <input type="text" name="fechavto" id="fechavto" size="8"/>
				  </strong>
			  </div>
			</td>
		</tr>
	</table>
	<p><input type="submit" name="Submit" value="Buscar" class="nover"/></p>
	</form>
<?php if (isset($_POST['fechavto'])) { ?>
	<p><span class="Estilo2">Resultado Certificados vencidos al <?php echo $_POST['fechavto'] ?> </span></p>
	<table style="text-align:center; width:800px" id="tabla" class="tablesorter" >
		<thead>
			<tr>
				<td>Nro. Afiliado</td>
				<td>Nombre y Apellido</td>
				<td class="filter-select" data-placeholder="Seleccione Tipo">Tipo Beneficiario</td>
				<td>Fecha Emisión</td>
				<td>Fecha Vto.</td>
			</tr>
		</thead>
		<?php while ($rowDiscapcitado = mysql_fetch_assoc($resDiscacitados)) {?>
			<tr>
				<td><?php echo $rowDiscapcitado['nroafiliado'] ?></td>	
				<td>
				<?php 
						$tipoBeneficiario = "";
						if ($rowDiscapcitado['nroorden'] == 0) { 
							$sqlBeneficiario = "SELECT apellidoynombre FROM titulares WHERE nroafiliado = ".$rowDiscapcitado['nroafiliado'];
							$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
							$canBeneficiario = mysql_num_rows($resBeneficiario);
							if($canBeneficiario == 0) {
								$sqlBeneficiario = "SELECT apellidoynombre FROM titularesdebaja WHERE nroafiliado = ".$rowDiscapcitado['nroafiliado'];
								$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
								$canBeneficiario = mysql_num_rows($resBeneficiario);
								if($canBeneficiario == 0) {
									echo ('No se encontro el Beneficiario');
									$tipoBeneficiario = "-";
								} else {
									$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
									echo ($rowBeneficiario['apellidoynombre']);
									$tipoBeneficiario = "TITULAR INACTIVO";
								}
							} else {
								$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
								echo ($rowBeneficiario['apellidoynombre']);
								$tipoBeneficiario = "TITULAR";
							}
					   } else {
					   		$sqlBeneficiario = "SELECT apellidoynombre FROM familiares WHERE nroafiliado = ".$rowDiscapcitado['nroafiliado']." and nroorden = ".$rowDiscapcitado['nroorden'];
							$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
							$canBeneficiario = mysql_num_rows($resBeneficiario);
							if($canBeneficiario == 0) {
								$sqlBeneficiario = "SELECT apellidoynombre FROM familiaresdebaja WHERE nroafiliado = ".$rowDiscapcitado['nroafiliado']." and nroorden = ".$rowDiscapcitado['nroorden'];
								$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
								$canBeneficiario = mysql_num_rows($resBeneficiario);
								if($canBeneficiario == 0) {
									echo ('No se encontro el Beneficiario');
									$tipoBeneficiario = "-";
								} else {
									$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
									echo ($rowBeneficiario['apellidoynombre']);
									$tipoBeneficiario = "FAMILIAR INACTIVO";
								}
							} else {
								$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
								echo ($rowBeneficiario['apellidoynombre']);
								$tipoBeneficiario = "FAMILIAR";
							}	
					  	 } ?>
				</td>
				<td><?php echo $tipoBeneficiario ?></td>		
				<td><?php echo invertirfecha($rowDiscapcitado['emisioncertificado']) ?></td>	
				<td><?php echo invertirfecha($rowDiscapcitado['vencimientocertificado']) ?></td>		
					
			</tr>
		<?php } ?>
		<tbody>
		</tbody>
	</table>
    <p>
      <input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/>
  </p>
 <?php } ?>
</div>
</body>
</html>
