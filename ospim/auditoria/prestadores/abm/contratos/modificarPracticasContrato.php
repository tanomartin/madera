<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$codigo = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
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
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">


	$(function() {
		$("#practicaencontrato")
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
		}),
		$.unblockUI(); 
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
					alert("Al seleccionar una practica No Nomenclada debe colocarle el valor del prestador");
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
						alert("Al seleccionar una practica No Nomenclada debe colocarle el valor del prestador");
						document.getElementById(idValor).focus();
						return false;	
					}
				}
			}
		}
		$.blockUI({ message: "<h1>Agregando Practicas Seleccionadas</h1>" });
		return true;
	}
	
jQuery(function($){	
	$("#tipo").change(function(){
		$("#capitulo").html("<option value='0'>Seleccione Capitulo</option>");
		$("#capitulo").prop("disabled",true);
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#subcapitulo").prop("disabled",true);
		$("#agregar").prop("disabled",true);
		$("#practicas").html("");
		var valor = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "getCapitulos.php",
			data: {valor:valor},
		}).done(function(respuesta){
			if (valor != 0) {
				if (respuesta != 0) {
					$("#capitulo").html(respuesta);
					$("#capitulo").prop("disabled",false);
				} else {
					var nomenclador = $("#prestanomenclador").val();
					$.ajax({
						type: "POST",
						dataType: 'html',
						url: "getPracticas.php",
						data: {valor:-1, tipo:valor, nomenclador:nomenclador},
					}).done(function(respuesta){
						if (respuesta != 0) {	
							$("#practicas").html(respuesta);
							$("#agregar").prop("disabled",false);
						}
					});
				}
			}
		});
	});
	
	$("#capitulo").change(function(){
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#subcapitulo").prop("disabled",true);
		$("#agregar").prop("disabled",true);	
		$("#practicas").html("");
		var valor = $(this).val();
		valor = valor.split('-');
		tipo = $("#tipo").val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "getSubCapitulos.php",
			data: {valor:valor[0]},
		}).done(function(respuesta){
			if (respuesta != 0) {
				$("#subcapitulo").html(respuesta);	
				$("#subcapitulo").prop("disabled",false);			
			}
			var nomenclador = $("#prestanomenclador").val();
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getPracticas.php",
				data: {valor:valor[1], tipo:tipo, nomenclador:nomenclador},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#practicas").html(respuesta);
					$("#agregar").prop("disabled",false);
				}
			});
		});
	});
	
	$("#subcapitulo").change(function(){
		$("#practicas").html("");
		$("#agregar").prop("disabled",true);
		tipo = $("#tipo").val();
		var valor = $(this).val();
		if (valor == 0) { 
			valor = $("#capitulo").val();
			valor = valor.split('-');
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getPracticas.php",
				data: {valor:valor[1], tipo:tipo},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#practicas").html(respuesta);
					$("#agregar").prop("disabled",false);
				}
			});
		} else {
			valor = valor.split('-');
			var nomenclador = $("#prestanomenclador").val();
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getPracticas.php",
				data: {valor:valor[1], tipo:tipo, nomenclador:nomenclador},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#practicas").html(respuesta);
					$("#agregar").prop("disabled",false);
				}
			});
		}
	});
});

	
</script>

<body bgcolor="#CCCCCC">
<script>
	$.blockUI({ message: "<h1>Cargando Contrato<br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" } );
</script>
<div align="center">
  <p><span style="text-align:center">
   <input type="reset" name="volver" value="Volver" onclick="location.href = 'contratosPrestador.php?codigo=<?php echo $codigo ?>'" align="center"/>
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
  
  <!--******************************************************************************************************************************************************************** -->
 
  <form name="editarContrato" id="editarContrato" onSubmit="return validar(this)" method="post" action="eliminarPracticas.php?codigo=<?php echo $codigo ?>&idcontrato=<?php echo $idcontrato ?>" >
    <p><strong>Pr&aacute;cticas dentro del contrato </strong></p>
		<?php 
  		$sqlPracticas = "SELECT pr.*, p.valornonomenclado, t.descripcion as tipo, tc.descripcion as complejidad FROM cabcontratoprestador c, detcontratoprestador p, practicas pr, tipopracticas t, tipocomplejidad tc WHERE c.codigoprestador = $codigo and c.idcontrato = $idcontrato and c.idcontrato = p.idcontrato and p.codigopractica = pr.codigopractica and p.nomenclador = pr.nomenclador and pr.tipopractica = t.id and pr.codigocomplejidad = tc.codigocomplejidad";
		$resPracticas = mysql_query($sqlPracticas,$db);
		$numPracticas = mysql_num_rows($resPracticas);
		if ($numPracticas > 0) {
 		 ?>
        <table style="text-align:center; width:1000px" id="practicaencontrato" class="tablesorter" >
          <thead>
            <tr>
              <th>C&oacute;digo</th>
			  <th class="filter-select" data-placeholder="Seleccione Nomenclador">Nomenclador</th>
			  <th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
			  <th class="filter-select" data-placeholder="Seleccione Capitulo">Capitulo</th>
			  <th class="filter-select" data-placeholder="Seleccione Subcapitulo">Subcapitulo</th>
              <th>Descripciones</th>
			  <th>Complejidad</th>
			  <th>Valor ($)</th>
			  <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
			while($rowPracticas = mysql_fetch_array($resPracticas)) {
				$descripPractica = descripcionPractica($rowPracticas['codigopractica'],$rowPracticas['tipopractica'],$db); ?>
				<tr>
				  <td><?php echo $rowPracticas['codigopractica'];?></td>
				  <td><?php if ($rowPracticas['nomenclador'] == 1) { echo "NN"; } else { echo "NP"; }?></td>
				  <td><?php echo $rowPracticas['tipo'] ?></td>
				  <td><?php echo $descripPractica['capitulo'] ?></td>
				  <td><?php echo $descripPractica['subcapitulo'] ?></td>
				  <td><?php echo $rowPracticas['descripcion'];?></td>
				  <td><?php echo $rowPracticas['complejidad'];?></td>
				   <td><?php if ($rowPracticas['nomenclador'] == 1) { echo $rowPracticas['valornacional']; } else { echo $rowPracticas['valornonomenclado']; }?></td>
				  <td><input type='checkbox' name='<?php echo $rowPracticas["codigopractica"]; ?>' id='practicasactuales' value='<?php echo $rowPracticas["codigopractica"]; ?>'></td>	   
				</tr>
         <?php } ?>
          </tbody>
        </table>
        <p> 
			<input type="submit" name="eliminar" id="eliminar" value="Eliminar Seleccionados" />
        	<?php } else { 	print("<div style='color:#000099'><b> ESTE PRESTADOR NO TIENE CONTRATO CARGADO </b></div><br>"); } ?>
		</p>
    </form>
	
	<!--******************************************************************************************************************************************************************** -->	
	
	<form name="agregarContrato" id="agregarContrato" onSubmit="return validarAdd(this)" method="post" action="agregarPracticas.php?codigo=<?php echo $codigo ?>&idcontrato=<?php echo $idcontrato ?>" >
	  <input type="text" id="prestanomenclador" name="prestanomenclador" value="<?php echo $rowConsultaPresta['nomenclador'] ?>" style="display:none"/>
	  <p><strong>Pr&aacute;cticas para Agregar al contrato </strong></p>
	  <?php if(isset($_GET['error'])) { print("<div style='color:#FF0000'><b> ERROR: NO SE PUEDE COLOCAR EN EL MISMO CONTRATO DOS PRACTICAS CON EL MISMO CODIGO</b></div><br>");} ?>
	  <p>
        <select name="tipo" id="tipo">
          <option value="0">Seleccione Tipo de Practica</option>
          <?php $sqlTipos = "SELECT * FROM tipopracticas";
			  $resTipos = mysql_query($sqlTipos,$db);
			  while($rowTipos = mysql_fetch_assoc($resTipos)) { ?>
          <option value="<?php echo $rowTipos['id'] ?>"><?php echo $rowTipos['descripcion'] ?></option>
          <?php } ?>
        </select>
      </p>
	  <p>
        <select name="capitulo" id="capitulo" disabled="disabled">
          <option value="0">Seleccione Capitulo</option>
        </select>
      </p>
	  <p>
        <select name="subcapitulo" id="subcapitulo" disabled="disabled">
          <option value="0">Seleccione SubCapitulo</option>
        </select>
      </p>
	  <!-- LO USO PARA SABER CUANDO ARRANCAR EN EL POST A TOMAR DATOS -->
	  <input type="text" id="arranca" name="arranca" value="1" style="display:none"/>
	  <!-- ---------------------------------------------------------- -->
	 <table style="text-align:center; width:1000px" id="practicas" class="tablesorter" >
		 <thead>
		 </thead>
		 <tbody>
		 </tbody>
  	 </table>
     <p><input type='submit' name='agregar' id='agregar' value='Agregar Seleccionados' disabled="disabled"/></p>
	</form>
	
</div>
</body>
</html>