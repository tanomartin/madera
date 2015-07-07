<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

if (isset($_POST['delegacion'])) { 
	$resultado = array();
	$i = 0;
	$sqlExpediente = "SELECT e.nroafiliado, e.nroorden FROM discapacitadoexpendiente e WHERE e.completo = 0 ";
	$resExpediente = mysql_query($sqlExpediente,$db);
	while ($rowExpediente = mysql_fetch_assoc($resExpediente)) {
		$arrayDelegacion = explode("-",$_POST['delegacion']);
		$delegacion = $arrayDelegacion[0];
		$tipoBeneficiario = "";
		if ($rowExpediente['nroorden'] == 0) { 
			$sqlBeneficiario = "SELECT apellidoynombre FROM titulares WHERE nroafiliado = ".$rowExpediente['nroafiliado']." and codidelega =".$delegacion;
			$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
			$canBeneficiario = mysql_num_rows($resBeneficiario);
			if($canBeneficiario == 0) {
				$sqlBeneficiario = "SELECT apellidoynombre FROM titularesdebaja WHERE nroafiliado = ".$rowExpediente['nroafiliado']." and codidelega =".$delegacion;
				$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
				$canBeneficiario = mysql_num_rows($resBeneficiario);
				if($canBeneficiario != 0) {
					$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
					$resultado[$i] = array('nroafiliado' => $rowExpediente['nroafiliado'], 'nroorden' => 0, 'nombre' => $rowBeneficiario['apellidoynombre'], "tipoBeneficiario" => "TITULAR INACTIVO", "activo" => 0);
					$i++;
				}
			} else {
				$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
				$resultado[$i] = array('nroafiliado' => $rowExpediente['nroafiliado'], 'nroorden' => 0, 'nombre' => $rowBeneficiario['apellidoynombre'], "tipoBeneficiario" => "TITULAR", "activo" => 1);
				$i++;
			}
		} else {
			$sqlBeneficiario = "SELECT f.apellidoynombre FROM familiares f, titulares t WHERE f.nroafiliado = ".$rowExpediente['nroafiliado']." and f.nroorden = ".$rowExpediente['nroorden']." and f.nroafiliado = t.nroafiliado and t.codidelega = ".$delegacion;
			$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
			$canBeneficiario = mysql_num_rows($resBeneficiario);
			if($canBeneficiario == 0) {
				$sqlBeneficiario = "SELECT f.apellidoynombre FROM familiaresdebaja f, titulares t WHERE f.nroafiliado = ".$rowExpediente['nroafiliado']." and f.nroorden = ".$rowExpediente['nroorden']." and f.nroafiliado = t.nroafiliado and t.codidelega = ".$delegacion;
				$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
				$canBeneficiario = mysql_num_rows($resBeneficiario);
				if($canBeneficiario == 0) {
					$sqlBeneficiario = "SELECT f.apellidoynombre FROM familiaresdebaja f, titularesdebaja t WHERE f.nroafiliado = ".$rowExpediente['nroafiliado']." and f.nroorden = ".$rowExpediente['nroorden']." and f.nroafiliado = t.nroafiliado and t.codidelega = ".$delegacion;
					$resBeneficiario = mysql_query($sqlBeneficiario,$db);	
					$canBeneficiario = mysql_num_rows($resBeneficiario);
					if($canBeneficiario != 0) {
						$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
						$resultado[$i] = array('nroafiliado' => $rowExpediente['nroafiliado'], 'nroorden' => $rowExpediente['nroorden'], 'nombre' => $rowBeneficiario['apellidoynombre'], "tipoBeneficiario" => "FAMILIAR INACTIVO", "activo" => 0);
						$i++;
					}
				} else {
					$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
					$resultado[$i] = array('nroafiliado' => $rowExpediente['nroafiliado'], 'nroorden' => $rowExpediente['nroorden'], 'nombre' => $rowBeneficiario['apellidoynombre'], "tipoBeneficiario" => "FAMILIAR INACTIVO", "activo" => 0);
					$i++;
				}
			} else {
				$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
				$resultado[$i] = array('nroafiliado' => $rowExpediente['nroafiliado'], 'nroorden' => $rowExpediente['nroorden'], 'nombre' => $rowBeneficiario['apellidoynombre'], "tipoBeneficiario" => "FAMILIAR", "activo" => 1);
				$i++;
			}	
		} 
	}
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Expedientes Incompletos :.</title>

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
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script language="javascript" type="text/javascript">

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
		});
	});


function validar(formulario) {
	$.blockUI({ message: "<h1>Generando Listado<br>Aguarde por favor...</h1>" });
	formulario.Submit.disabled = true;
	return true;
}


function abrirConsulta(dire){	
	window.open(dire,'consultaDiscapacitado','width=800, height=600, resizable=yes');
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloInformes.php'" /></p>
	<form  name="listadoExpedientes" id="listadoExpedientes" method="post" onsubmit="return validar(this)" action="expedientesIncompletos.php">
	<p><span class="Estilo2">Expendientes Incompletos </span></p>
	<table>
      <tr>
        <td><div align="left"> <strong>Delegaci&oacute;n</strong>
                <select name="delegacion" id="delegacion" class="nover">
                  <option value="0" selected="selected">Seleccione un Valor </option>
                  <?php 
							$sqlDele="select codidelega,nombre from delegaciones where codidelega not in (1000,1001,3500)";
							$resDele= mysql_query($sqlDele,$db);
							while ($rowDele=mysql_fetch_array($resDele)) { 	?>
                  <option value="<?php echo $rowDele['codidelega']."-".$rowDele['nombre'] ?>"><?php echo $rowDele['nombre']  ?></option>
                  <?php } ?>
                </select>
        </div></td>
      </tr>
    </table>
    <p> <input type="submit" name="Submit" value="Listar" class="nover"/> </p>
<?php if (isset($_POST['delegacion'])) { ?>
		<p><span class="Estilo2">Resultado Delegaci&oacute;n <?php echo $_POST['delegacion'] ?></span> </p>
<?php 	if (sizeof($resultado) != 0) { ?>
			<table style="text-align:center; width:800px" id="tabla" class="tablesorter" >
				<thead>
					<tr>
						<td>Nro. Afiliado</td>
						<td>Nombre y Apellido</td>
						<td class="filter-select" data-placeholder="Seleccione Tipo">Tipo Beneficiario</td>
						<td>Acciones</td>
					</tr>
				</thead>
				<tbody>
				<?php foreach($resultado as $bene) { ?> 
					<tr>
						<td><?php echo $bene['nroafiliado'] ?></td>
						<td><?php echo $bene['nombre'] ?></td>
						<td><?php echo $bene['tipoBeneficiario'] ?></td>
						<td><input type='button' name='consultar' value='Consultar' onclick="abrirConsulta('../abm/consultarDiscapacitado.php?nroafiliado=<?php echo $bene['nroafiliado'] ?>&nroorden=<?php echo $bene['nroorden'] ?>&activo=<?php echo $bene['activo'] ?>&nomostrar=1')" /></td>		
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<p> <input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
 <?php } else {
			echo "<font color='#FF0000'><b>No existen Expedientes Incompletos</b></font>";
 	   }
  } ?>
	</form>
</div>
</body>
</html>
