<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre, nomenclador FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Contrato :.</title>
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
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#practicas")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers:{5:{sorter:false},6:{sorter:false, filter: false},7:{sorter:false, filter: false}},
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
		}),
		
		$("#practagregar")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers:{5:{sorter:false},6:{sorter:false, filter: false},7:{sorter:false, filter: false}},
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
	
	function habilitarValor(nomenclador,practica,check) {
		var idNomencaldor = "N"+nomenclador+practica
		tipoNomenclador = document.getElementById(idNomencaldor);
		if (check.checked) {
			tipoNomenclador.disabled = false;
		} else {
			tipoNomenclador.disabled = true;
		}
		
		if(nomenclador == 2) {
			var valor;
			var idValor = "valorNN2"+practica;
			valor = document.getElementById(idValor);
			valor.value = "";
			if (check.checked) {
				valor.disabled = false;	
			} else {
				valor.disabled = true;
			}
		}
	}
	
	function validar(formulario) {
		$.blockUI({ message: "<h1>Eliminando Practicas Seleccionadas</h1>" });
		return true;
	}
	
	function validarAdd(formulario) {
		var grupo = formulario.practicasagregar;
		var total = grupo.length;
		if (total == null) {
			check = formulario.practicasagregar;
			if (check.checked && check.accessKey == 2) {
				var name = check.name;
				var idValor = "valorNN"+name;
				var valor = document.getElementById(idValor).value;
				if (!isNumberPositivo(valor) || valor == "" || valor == 0) {
					alert("Al seleccionar una practica No Nomencladad debe colocarle el valor del prestador");
					document.getElementById(idValor).focus();
					return false;	
				}
			}
		} else {
			var checkeados = 0; 
			for (i = 0; i < total; i++) {
				if (grupo[i].checked && grupo[i].accessKey == 2) {
					var name = grupo[i].name;
					var idValor = "valorNN"+name;
					var valor = document.getElementById(idValor).value;
					if (!isNumberPositivo(valor) || valor == "" || valor == 0) {
						alert("Al seleccionar una practica No Nomencladad debe colocarle el valor del prestador");
						document.getElementById(idValor).focus();
						return false;	
					}
				}
			}
		}
		$.blockUI({ message: "<h1>Agregando Practicas Seleccionadas</h1>" });
		return true;
	}
	
</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
   <input type="reset" name="volver" value="Volver" onclick="location.href = 'prestador.php?codigo=<?php echo $codigo ?>'" align="center"/>
  </span></p>
  <p class="Estilo2">Modificaci&oacute;n de Contrato </p>
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
  
  <!--******************************************************************************************************************************************************************** -->
 
  <form name="editarContrato" id="editarContrato" onSubmit="return validar(this)" method="POST" action="eliminarPracticas.php?codigo=<?php echo $codigo ?>" >
    <p><strong>Pr&aacute;cticas dentro del contrato </strong></p>
		<?php 
  		$sqlPracticas = "SELECT pr.*, p.valornonomenclado FROM practicaprestador p, practicas pr WHERE p.codigoprestador = $codigo and p.codigopractica = pr.codigopractica and p.nomenclador = pr.nomenclador";
		$resPracticas = mysql_query($sqlPracticas,$db);
		$numPracticas = mysql_num_rows($resPracticas);
		if ($numPracticas > 0) {
 		 ?>
        <table style="text-align:center; width:1000px" id="practicas" class="tablesorter" >
          <thead>
            <tr>
              <th>C&oacute;digo</th>
			  <th class="filter-select" data-placeholder="Seleccione Nomenclador">Nomenclador</th>
			  <th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
			  <th class="filter-select" data-placeholder="Seleccione Capitulo">Capitulo</th>
			  <th class="filter-select" data-placeholder="Seleccione Subcapitulo">Subcapitulo</th>
              <th>Descripciones</th>
              <th>Valor ($)</th>
			  <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
			while($rowPracticas = mysql_fetch_array($resPracticas)) {
				$codigoArray = explode(".",$rowPracticas['codigopractica']);
				$practica = "";
				$capitulo = "";
				$subcapitulo = "";
				if (sizeof($codigoArray) == 1) {
					$tipoPractica = 1;
					$sqlTipoPractica = "SELECT descripcion FROM tipopracticas WHERE id = $tipoPractica";
					$resTipoPractica = mysql_query($sqlTipoPractica,$db);
					$rowTipoPractica = mysql_fetch_array($resTipoPractica);
					$practica = $rowTipoPractica['descripcion'];
				}
				if (sizeof($codigoArray) == 2) {
					$codCapitulo = $codigoArray[0];
					$sqlTipoPractica = "SELECT c.descripcion as cap, t.descripcion as tipoprac FROM tipopracticas t, capitulosdepracticas c WHERE c.codigo = '$codCapitulo' and c.idtipopractica != 2 and c.idtipopractica = t.id";
					$resTipoPractica = mysql_query($sqlTipoPractica,$db);
					$rowTipoPractica = mysql_fetch_array($resTipoPractica);
					$practica = $rowTipoPractica['tipoprac'];
					$capitulo = $rowTipoPractica['cap'];
				}
				if (sizeof($codigoArray) == 3) {
					$tipoPractica = 2;
					$codCapitulo = $codigoArray[0];
					$codSubCapitulo = $codigoArray[1];
					$sqlTipoPractica = "SELECT c.descripcion as cap, t.descripcion as tipoprac, s.descripcion as subcap FROM tipopracticas t, capitulosdepracticas c, subcapitulosdepracticas s WHERE s.codigo = '$codCapitulo.$codSubCapitulo' and s.idcapitulo = c.id and c.idtipopractica = t.id";
					$resTipoPractica = mysql_query($sqlTipoPractica,$db);
					$rowTipoPractica = mysql_fetch_array($resTipoPractica);
					$practica = $rowTipoPractica['tipoprac'];
					$capitulo = $rowTipoPractica['cap'];
					$subcapitulo = $rowTipoPractica['subcap'];
				}
			?>
            <tr>
              <td><?php echo $rowPracticas['codigopractica'];?></td>
			  <td><?php if ($rowPracticas['nomenclador'] == 1) { echo "NN"; } else { echo "NP"; }?></td>
			  <td><?php echo $practica ?></td>
			  <td><?php echo $capitulo ?></td>
			  <td><?php echo $subcapitulo ?></td></td>
              <td><?php echo $rowPracticas['descripcion'];?></td>
              <td align="left"><?php if ($rowPracticas['nomenclador'] == 1) { echo $rowPracticas['valornacional']; } else { echo $rowPracticas['valornonomenclado']; }?></td>
			  <td><input type='checkbox' name='<?php echo $rowPracticas["codigopractica"]; ?>' id='practicasactuales' value='<?php echo $rowPracticas["codigopractica"]; ?>'></td>
            </tr>
            <?php
			}
		?>
          </tbody>
        </table>
      <p>
        <input type="submit" name="eliminar" id="eliminar" value="Eliminar Seleccionados" />
        <?php } else { 	print("<div style='color:#FF0000'><b> ESTE PRESTADOR NO TIENE CONTRATO CARGADO </b></div><br>"); } ?></p>
    </form>
	
	<!--******************************************************************************************************************************************************************** -->	
	
	<form name="agregarContrato" id="agregarContrato" onSubmit="return validarAdd(this)" method="POST" action="agregarPracticas.php?codigo=<?php echo $codigo ?>" >
	  <p><strong>Pr&aacute;cticas para Agregar al contrato </strong></p>
		<?php 
		if ($rowConsultaPresta['nomenclador'] == 3) {
			$sqlPracticas = "SELECT pr.* FROM  practicas pr WHERE pr.codigopractica not in (select codigopractica from practicaprestador where codigoprestador = $codigo)";
		} else {
  			$sqlPracticas = "SELECT pr.* FROM  practicas pr, prestadores presta WHERE pr.codigopractica not in ( select codigopractica from practicaprestador where codigoprestador = $codigo) and presta.codigoprestador = $codigo and pr.nomenclador = presta.nomenclador";
		}
		$resPracticas = mysql_query($sqlPracticas,$db);
		$numPracticas = mysql_num_rows($resPracticas);
		if ($numPracticas > 0) {
 		?>
        <table style="text-align:center; width:1000px" id="practagregar" class="tablesorter" >
          <thead>
            <tr>
              <th>C&oacute;digo</th>
			  <th class="filter-select" data-placeholder="Seleccione Nomenclador">Nomenclador</th>
			  <th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
			  <th class="filter-select" data-placeholder="Seleccione Capitulo">Capitulo</th>
			  <th class="filter-select" data-placeholder="Seleccione Subcapitulo">Subcapitulo</th>
              <th>Descripciones</th>
              <th>Valor ($)</th>
			  <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
			while($rowPracticas = mysql_fetch_array($resPracticas)) {
				$codigo = explode(".",$rowPracticas['codigopractica']);
				$practica = "";
				$capitulo = "";
				$subcapitulo = "";
				if (sizeof($codigo) == 1) {
					$tipoPractica = 1;
					$sqlTipoPractica = "SELECT descripcion FROM tipopracticas WHERE id = $tipoPractica";
					$resTipoPractica = mysql_query($sqlTipoPractica,$db);
					$rowTipoPractica = mysql_fetch_array($resTipoPractica);
					$practica = $rowTipoPractica['descripcion'];
				}
				if (sizeof($codigo) == 2) {
					$codCapitulo = $codigo[0];
					$sqlTipoPractica = "SELECT c.descripcion as cap, t.descripcion as tipoprac FROM tipopracticas t, capitulosdepracticas c WHERE c.codigo = '$codCapitulo' and c.idtipopractica != 2 and c.idtipopractica = t.id";
					$resTipoPractica = mysql_query($sqlTipoPractica,$db);
					$rowTipoPractica = mysql_fetch_array($resTipoPractica);
					$practica = $rowTipoPractica['tipoprac'];
					$capitulo = $rowTipoPractica['cap'];
				}
				if (sizeof($codigo) == 3) {
					$tipoPractica = 2;
					$codCapitulo = $codigo[0];
					$codSubCapitulo = $codigo[1];
					$sqlTipoPractica = "SELECT c.descripcion as cap, t.descripcion as tipoprac, s.descripcion as subcap FROM tipopracticas t, capitulosdepracticas c, subcapitulosdepracticas s WHERE s.codigo = '$codCapitulo.$codSubCapitulo' and s.idcapitulo = c.id and c.idtipopractica = t.id";
					$resTipoPractica = mysql_query($sqlTipoPractica,$db);
					$rowTipoPractica = mysql_fetch_array($resTipoPractica);
					$practica = $rowTipoPractica['tipoprac'];
					$capitulo = $rowTipoPractica['cap'];
					$subcapitulo = $rowTipoPractica['subcap'];
				}
				
			?>
            <tr>
              <td><?php echo $rowPracticas['codigopractica'];?></td>
			  <td><input type="text" style="display:none" size="1" value="<?php echo $rowPracticas['nomenclador'] ?>" disabled="disabled" name="N<?php echo $rowPracticas['nomenclador'].$rowPracticas['codigopractica'] ?>" id="N<?php echo $rowPracticas['nomenclador'].$rowPracticas['codigopractica'] ?>"  />
			  	<?php if ($rowPracticas['nomenclador'] == 1) { echo "NN"; } else { echo "NP"; }?></td>
			  <td><?php echo $practica ?></td>
			  <td><?php echo $capitulo ?></td>
			  <td><?php echo $subcapitulo ?></td></td>
              <td><?php echo $rowPracticas['descripcion'];?></td>
              <td><?php 
					if ($rowPracticas['nomenclador'] == 1) { 
						echo $rowPracticas['valornacional']; 
					} else { 
						$codigopractia = $rowPracticas['codigopractica']; 
						echo "<input size='8' disabled='disabled' type='text' name='valorNN2$codigopractia' id='valorNN2$codigopractia'/>"; 
					}?>
			  </td>
			  <td><input type='checkbox' name='<?php echo $rowPracticas["nomenclador"].$rowPracticas["codigopractica"]; ?>' onchange="habilitarValor('<?php echo $rowPracticas['nomenclador']?>','<?php echo $rowPracticas['codigopractica']?>',this)" accesskey='<?php echo $rowPracticas["nomenclador"]; ?>' id='practicasagregar' value='<?php echo $rowPracticas["codigopractica"]; ?>'></td>
            </tr>
            <?php
			}
		?>
          </tbody>
        </table>
      <p>
        <input type="submit" name="agregar" id="agregar" value="Agregar Seleccionados" />
        <?php } else { 	print("<div style='color:#FF0000'><b> NO EXISTEN PRACTICAS POSIBLES DE SER AGREGADAS A ESTE PRESTADOR </b></div><br>"); } ?></p>
    </form>
	
</div>
</body>
</html>