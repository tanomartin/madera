<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$codigo = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
$sqlConsultaPresta = "SELECT * FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlConsuNomenclador = "SELECT * FROM prestadornomenclador WHERE codigoprestador = $codigo";
$resConsuNomenclador = mysql_query($sqlConsuNomenclador,$db);
$whereNom = "";
while ($rowConsuNomenclador = mysql_fetch_assoc($resConsuNomenclador)) {
	$whereNom .= $rowConsuNomenclador['codigonomenclador'].",";
}
$whereNom = substr($whereNom, 0, -1);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Contrato :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script type="text/javascript">

	$(function() {
		$("#practicaencontrato")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers:{	8:{sorter:false, filter: false},
				 		9:{sorter:false, filter: false},
				 		10:{sorter:false, filter: false},
				 		11:{sorter:false, filter: false},
				 		12:{sorter:false, filter: false},
				 		13:{sorter:false, filter: false},
				 		14:{sorter:false, filter: false},
				 		16:{sorter:false, filter: false},
				 	},
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
	
	function habilitarValores(posicion,opcion) {	
		var idname = "moduloConsultorio-"+posicion;
		var modCons = document.getElementById(idname);
		idname = "moduloUrgencia-"+posicion;
		var modUrge = document.getElementById(idname);
		idname = "gHono-"+posicion;
		var gHono = document.getElementById(idname);
		idname = "gHonoEspe-"+posicion;
		var gHonoEspe = document.getElementById(idname);
		idname = "gHonoAyud-"+posicion;
		var gHonoAyud = document.getElementById(idname);
		idname = "gHonoAnes-"+posicion;
		var gHonoAnes = document.getElementById(idname);
		idname = "gGastos-"+posicion;
		var gGastos = document.getElementById(idname);
		idname = "coseguro-"+posicion;
		var coseguro = document.getElementById(idname);
		gHono.value = '';
		gHonoEspe.value = '';
		gHonoAyud.value = '';
		gHonoAnes.value = '';
		gGastos.value = '';
		gHono.disabled = true;
		gHonoEspe.disabled = true;
		gHonoAyud.disabled = true;
		gHonoAnes.disabled = true;
		gGastos.disabled = true;
		modCons.value = '';
		modUrge.value = '';
		modCons.disabled = true;
		modUrge.disabled = true;
		coseguro.value = '';
		coseguro.disabled = true;
		if (opcion != 0) {
			coseguro.disabled = false;
			if (opcion == 1) {
				modCons.disabled = false;
				modUrge.disabled = false;	
			} else {
				gHono.disabled = false;
				gHonoEspe.disabled = false;
				gHonoAyud.disabled = false;
				gHonoAnes.disabled = false;
				gGastos.disabled = false;	
			}
		} 
	}
	
	function validarDelete(formulario) {
		$.blockUI({ message: "<h1>Eliminando Practicas Seleccionadas</h1>" });
		return true;
	}
	
	function validarAdd(formulario) {
		for (var i=0;i<formulario.elements.length;i++) {
			var elemento = formulario.elements[i];
			if (elemento.id.indexOf("tipoCarga") !== -1 && elemento.value != 0) {
				var idArray = elemento.id.split("-");
				if (elemento.value == 1) {
					var consultorioId = "moduloConsultorio-"+idArray[1];
					var urgenciaId = "moduloUrgencia-"+idArray[1];
					var moduloConsu = document.getElementById(consultorioId);
					var moduloUrgen = document.getElementById(urgenciaId);
					if (moduloConsu.value == 0 && moduloUrgen.value == 0) {
						alert("Debe ingresar un valor consultorio o general y/o valor modulo urgencia");
						moduloConsu.focus();
						return false;
					} else {
						if (!isNumberPositivo(moduloConsu.value) || !isNumberPositivo(moduloUrgen.value)) {
							alert("Los valores por modulo deben ser numeros positivos");
							moduloConsu.focus();
							return false;
						}
					}
				} else {
					var honoId = "gHono-"+idArray[1];
					var honoEspeId = "gHonoEspe-"+idArray[1];
					var honoAyudId = "gHonoAyud-"+idArray[1];
					var honoAnesId = "gHonoAnes-"+idArray[1];
					var honoGastosid = "gGastos-"+idArray[1];
					var hono = document.getElementById(honoId);
					var honoEspe = document.getElementById(honoEspeId);
					var honoAyud = document.getElementById(honoAyudId);
					var honoAnes = document.getElementById(honoAnesId);
					var honoGastos = document.getElementById(honoGastosid);
					if (hono.value == 0 && honoEspe.value == 0 && 
						honoAyud.value == 0 && honoAnes.value == 0 && 
						honoGastos.value == 0) {
						alert("Debe ingresar por lo menos un valor de galeno");
						hono.focus();
						return false;
					} else {	
						if (!isNumberPositivo(hono.value) || !isNumberPositivo(honoEspe.value) || 
							!isNumberPositivo(honoAyud.value) || !isNumberPositivo(honoAnes.value) || 
							!isNumberPositivo(honoGastos.value)) {
							alert("Los valores por galeno deben ser numeros positivos");
							hono.focus();
							return false;
						}
					}
				}
			}
		}
		formulario.cattotal.disabled = true;
		formulario.tipocargatotal.disabled = true;
		formulario.moduloConsultoriototal.disabled = true;
		formulario.moduloUrgenciatotal.disabled = true;
		formulario.gHonototal.disabled = true;
		formulario.gHonoEspetotal.disabled = true;
		formulario.gHonoAyudtotal.disabled = true;
		formulario.gHonoAnestotal.disabled = true;
		formulario.gHonoAnestotal.disabled = true;
		formulario.gGastostotal.disabled = true;
		formulario.cosegurototal.disabled = true;
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
			$("#cattotal").prop("disabled",true);
			$("#tipocargatotal").prop("disabled",true);
			limpiarCargaMasiva();
			$("#practicas").html("");
			$("#tituloPraticas").hide();
			var personeria = $("#personeria").val();
			var valor = $(this).val();
			var valores = valor.split("-");
			var nomenclador = valores[1];
			var tipo = valores[0];
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getCapitulos.php",
				data: {valor:tipo},
			}).done(function(respuesta){
				if (valor != 0) {
					if (respuesta != 0) {
						$("#capitulo").html(respuesta);
						$("#capitulo").prop("disabled",false);
					} else {
						$.ajax({
							type: "POST",
							dataType: 'html',
							url: "getPracticas.php",
							data: {valor:-1, tipo:tipo, nomenclador:nomenclador, personeria:personeria},
						}).done(function(respuesta){
							$("#tituloPraticas").show();
							if (respuesta != 0) {	
								$("#practicas").html(respuesta);
								$("#agregar").prop("disabled",false);
								$("#cattotal").prop("disabled",false);
								$("#tipocargatotal").prop("disabled",false);
							} else {
								$("#practicas").html("NO EXISTEN PRACTICAS");
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
			$("#cattotal").prop("disabled",true);
			$("#tipocargatotal").prop("disabled",true);
			limpiarCargaMasiva();
			$("#practicas").html("");
			$("#tituloPraticas").hide();
			var personeria = $("#personeria").val();
			var valor = $(this).val();
			valor = valor.split('-');
			tipo = $("#tipo").val();
			tipos = tipo.split('-');
			tipo = tipos[0];
			nomenclador = tipos[1];
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
				$.ajax({
					type: "POST",
					dataType: 'html',
					url: "getPracticas.php",
					data: {valor:valor[1], tipo:tipo, nomenclador:nomenclador, personeria:personeria},
				}).done(function(respuesta){
					if (respuesta != 0) {
						$("#practicas").html(respuesta);
						$("#tituloPraticas").show();
						$("#agregar").prop("disabled",false);
						$("#cattotal").prop("disabled",false);
						$("#tipocargatotal").prop("disabled",false);
					}
				});
			});
		});
		
		$("#subcapitulo").change(function(){
			$("#practicas").html("");
			$("#tituloPraticas").hide();
			$("#agregar").prop("disabled",true);
			$("#cattotal").prop("disabled",true);
			$("#tipocargatotal").prop("disabled",true);
			limpiarCargaMasiva();
			$("#practicas").html("");
			var personeria = $("#personeria").val();
			tipo = $("#tipo").val();
			tipos = tipo.split('-');
			tipo = tipos[0];
			nomenclador = tipos[1];
			var valor = $(this).val();
			if (valor == 0) { 
				valor = $("#capitulo").val();
				valor = valor.split('-');
				$.ajax({
					type: "POST",
					dataType: 'html',
					url: "getPracticas.php",
					data: {valor:valor[1], tipo:tipo, personeria:personeria},
				}).done(function(respuesta){
					if (respuesta != 0) {
						$("#practicas").html(respuesta);
						$("#tituloPraticas").show();
						$("#agregar").prop("disabled",false);
						$("#cattotal").prop("disabled",false);
						$("#tipocargatotal").prop("disabled",false);
					} 
				});
			} else {
				valor = valor.split('-');
				$.ajax({
					type: "POST",
					dataType: 'html',
					url: "getPracticas.php",
					data: {valor:valor[1], tipo:tipo, nomenclador:nomenclador, personeria:personeria},
				}).done(function(respuesta){
					if (respuesta != 0) {
						$("#practicas").html(respuesta);
						$("#tituloPraticas").show();
						$("#agregar").prop("disabled",false);
						$("#cattotal").prop("disabled",false);
						$("#tipocargatotal").prop("disabled",false);
					}
				});
			}
		});
	});

	function cambiarcat(catetotal) {
		var tabla = document.getElementById('practicas');
		var nombre = "";
		cantFilas = tabla.rows.length;
		cantFilas--;
		for (var i = 0; i < cantFilas; i++) {
			nombre = "categoria-" + i;
			selectElemento = document.getElementById(nombre);
			selectElemento.selectedIndex = catetotal;
		}
	}

	function limpiarCargaMasiva() {
		document.getElementById("cattotal").selectedIndex = 0;
		document.getElementById("tipocargatotal").selectedIndex = 0;
		limpiarCargaMasivaValores();
	}

	function limpiarCargaMasivaValores() {
		document.getElementById('moduloConsultoriototal').disabled = true;
		document.getElementById('moduloUrgenciatotal').disabled  = true;	
		document.getElementById('gHonototal').disabled = true;
		document.getElementById('gHonoEspetotal').disabled = true;
		document.getElementById('gHonoAyudtotal').disabled = true;
		document.getElementById('gHonoAnestotal').disabled = true;
		document.getElementById('gGastostotal').disabled = true;	
		document.getElementById('cosegurototal').disabled = true;
		
		document.getElementById('moduloConsultoriototal').value = "";
		document.getElementById('moduloUrgenciatotal').value  = "";	
		document.getElementById('gHonototal').value = "";
		document.getElementById('gHonoEspetotal').value = "";
		document.getElementById('gHonoAyudtotal').value = "";
		document.getElementById('gHonoAnestotal').value = "";
		document.getElementById('gGastostotal').value = "";	
		document.getElementById('cosegurototal').value = "";	
	}

	function habilitarValorestotales(opcion) {
		limpiarCargaMasivaValores();
		var tabla = document.getElementById('practicas');
		var nombre = "";
		cantFilas = tabla.rows.length;
		cantFilas--;
		for (var i = 0; i < cantFilas; i++) {
			nombre = "tipoCarga-" + i;
			selectElemento = document.getElementById(nombre);
			selectElemento.selectedIndex = opcion;	
			if (opcion != 0) {
				document.getElementById('cosegurototal').disabled = false;
				if (opcion == 1) {
					document.getElementById('moduloConsultoriototal').disabled = false;
					document.getElementById('moduloUrgenciatotal').disabled  = false;	
				} else {
					document.getElementById('gHonototal').disabled = false;
					document.getElementById('gHonoEspetotal').disabled = false;
					document.getElementById('gHonoAyudtotal').disabled = false;
					document.getElementById('gHonoAnestotal').disabled = false;
					document.getElementById('gGastostotal').disabled = false;	
				}
			} 
			habilitarValores(i,opcion);
		}	
	}

	function cambiarvalor(nombre,valor) {
		for (var i = 0; i < cantFilas; i++) {
			nombreelemento = nombre+"-"+i;
			selectElemento = document.getElementById(nombreelemento);
			selectElemento.value = valor;	
		}
	}
	
</script>
</head>

<body bgcolor="#CCCCCC">
<script>
	$.blockUI({ message: "<h1>Cargando Contrato<br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" } );
</script>
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'contratosPrestador.php?codigo=<?php echo $codigo ?>'" /></p>
  <h3>ABM de Contratos </h3>
  <table border="1">
    <tr>
      <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Raz&oacute;n Social</strong></div></td>
      <td>
      	<div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div>
      	<input type="hidden" id="personeria" value="<?php echo $rowConsultaPresta['personeria']?>" />
      </td>
    </tr>
  </table>
  
  <!--******************************************************************************************************************************************************************** -->
 
  <form name="editarContrato" id="editarContrato" onsubmit="return validarDelete(this)" method="post" action="eliminarPracticas.php?codigo=<?php echo $codigo ?>&idcontrato=<?php echo $idcontrato ?>" >
    <h3>Pr&aacute;cticas dentro del contrato </h3>
		<?php 
  		$sqlPracticas = "SELECT pr.*,
  								p.*, 
  								t.descripcion as tipo, 
  								tc.descripcion as complejidad, 
  								n.nombre as nombrenomenclador,
  								pc.descripcion as categoria
  								FROM 
  									cabcontratoprestador c, 
  									detcontratoprestador p, 
  									practicas pr, 
  									tipopracticas t, 
  									tipopracticasnomenclador tn, 
  									tipocomplejidad tc,
  									nomencladores n,
  									practicascategorias pc
  								WHERE 
  									c.codigoprestador = $codigo and 
  									c.idcontrato = $idcontrato and 
  									c.idcontrato = p.idcontrato and 
  									p.idcategoria = pc.id and
  									p.idpractica = pr.idpractica and 
  									pr.nomenclador = n.id and
  									pr.tipopractica = tn.id and 
  									pr.codigocomplejidad = tc.codigocomplejidad and
  									n.id = tn.codigonomenclador and
  									tn.idtipo = t.id";
  		$resPracticas = mysql_query($sqlPracticas,$db);
		$numPracticas = mysql_num_rows($resPracticas);
		if ($numPracticas > 0) { ?>
        <table style="text-align:center; width:1000px; font-size: 13px" id="practicaencontrato" class="tablesorter" >
          <thead>
            <tr>
              <th>C&oacute;digo</th>
              <th class="filter-select" data-placeholder="Seleccione Categoria">Categoria</th>
			  <th class="filter-select" data-placeholder="Seleccione Nomenclador">Nomenclador</th>
			  <th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
			  <th class="filter-select" data-placeholder="Seleccione Capitulo">Capitulo</th>
			  <th class="filter-select" data-placeholder="Seleccione Subcapitulo">Subcapitulo</th>
              <th>Descripciones</th>
			  <th class="filter-select" data-placeholder="Seleccione Complejidad">Complejidad</th>
			  <th>Modulo Consultorio / Valor General ($)</th>
			  <th>Modulo Urgencia ($)</th>
			  <th>G. Honorarios ($)</th>
			  <th>G. Honorarios Especialista ($)</th>
			  <th>G. Honorarios Ayudante ($)</th>
			  <th>G. Honorarios Anestesista ($)</th>
			  <th>G. Gastos ($)</th>
			  <th>Coseguro ($)</th>
			  <th class="filter-select" data-placeholder="Seleccione">Internacion</th>
			  <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
			while($rowPracticas = mysql_fetch_array($resPracticas)) {
				$descripPractica = descripcionPractica($rowPracticas['codigopractica'],$rowPracticas['tipopractica'],$db); ?>
				<tr>
				  <td><?php echo $rowPracticas['codigopractica'] ?></td>
				  <td><?php echo $rowPracticas['categoria'] ?></td>
				  <td><?php echo $rowPracticas['nombrenomenclador'] ?></td>
				  <td><?php echo $rowPracticas['tipo'] ?></td>
				  <td><?php echo $descripPractica['capitulo'] ?></td>
				  <td><?php echo $descripPractica['subcapitulo'] ?></td>
				  <td><?php echo $rowPracticas['descripcion'];?></td>
				  <td><?php echo $rowPracticas['complejidad'];?></td>
				  <td><?php echo $rowPracticas['moduloconsultorio'];?></td>
				  <td><?php echo $rowPracticas['modulourgencia'];?></td>
				  <td><?php echo $rowPracticas['galenohonorario'];?></td>
				  <td><?php echo $rowPracticas['galenohonorarioespecialista'];?></td>
				  <td><?php echo $rowPracticas['galenohonorarioayudante'];?></td>
				  <td><?php echo $rowPracticas['galenohonorarioanestesista'];?></td>
				  <td><?php echo $rowPracticas['galenogastos'];?></td>
				  <td><?php echo $rowPracticas['coseguro'];?></td>
				  <td><?php if ($rowPracticas['internacion'] == 0) { echo "NO"; } else { echo "SI"; }?></td>
				  <td><input type='checkbox' name='<?php echo $rowPracticas["idpractica"]; ?>' id='practicasactuales' value='<?php echo $rowPracticas["idpractica"]; ?>' /></td>	   
				</tr>
         <?php } ?>
          </tbody>
        </table>
			<p><input type="submit" name="eliminar" id="eliminar" value="Eliminar Seleccionados" /></p>
        	<?php } else { 	?>
        		<h4><font color='#000099'> ESTE CONTRATO NO TIENE PRACTICAS CARGADAS</font></h4>
			<?php } ?>
    </form>
	
	<!--******************************************************************************************************************************************************************** -->	
	
	<form name="agregarContrato" id="agregarContrato" onsubmit="return validarAdd(this)" method="post" action="agregarPracticas.php?codigo=<?php echo $codigo ?>&idcontrato=<?php echo $idcontrato ?>" >
	  <h3>Pr&aacute;cticas para Agregar al contrato </h3>
	  <?php if(isset($_GET['error'])) { print("<div style='color:#FF0000'><b> NO SE PUEDE COLOCAR EN EL MISMO CONTRATO DOS PRACTICAS DE LA MISMA CATEGORIA<br> CON EL MISMO CODIGO DEL MISMO NOMENCLADOR</b></div>");} ?>
	  <p>
	  <?php $sqlTipos = "SELECT tn.id, tn.codigonomenclador, n.nombre, t.descripcion
	  						FROM tipopracticas t, tipopracticasnomenclador tn, nomencladores n 
							WHERE tn.codigonomenclador in ($whereNom) and tn.codigonomenclador = n.id and n.id = tn.codigonomenclador and tn.idtipo = t.id order by tn.id"; 
	  		$resTipos = mysql_query($sqlTipos,$db);?>
        <select name="tipo" id="tipo">
          <option value="0">Seleccione Tipo de Practica</option>  
          <?php while($rowTipos = mysql_fetch_assoc($resTipos)) { ?>
          <option value="<?php echo $rowTipos['id']."-".$rowTipos['codigonomenclador'] ?>"><?php echo $rowTipos['nombre']." - ".$rowTipos['descripcion'] ?></option>
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
     
     <div id="tituloPraticas" style="display: none">
    	<h3>Aplica a todas las Prácticas en pantalla</h3>
     	<div class="grilla">
	     	<table style="width: 900px">
		     <thead>
		     	<tr>
		     		<th>Categoria</th>
					<th></th>
					<th>Modulo Consultorio / Valor General ($)</th>
					<th>Modulo Urgencia ($)</th>
					<th>G. Honorarios ($)</th>
					<th>G. Honorarios Especialista ($)</th>
					<th>G. Honorarios Ayudante ($)</th>
					<th>G. Honorarios Anestesista ($)</th>
					<th>G. Gastos ($)</th>
					<th>Coseguro ($)</th>
				</tr>
			 </thead>
			 <tbody>	
				<tr>
				    <td>
						<select name="cattotal" id="cattotal" disabled="disabled" onchange="cambiarcat(this.selectedIndex)">
						<?php $personeria = $rowConsultaPresta['personeria'];
							  $sqlCategoriaTotal = "select * from practicascategorias where (tipoprestador = 0 or tipoprestador = $personeria)";
							  $resCategoriaTotal = mysql_query($sqlCategoriaTotal,$db);
							  while($rowCategoriaTotal = mysql_fetch_assoc($resCategoriaTotal)) {  ?>
								<option value='<?php echo $rowCategoriaTotal['id']?>'><?php echo $rowCategoriaTotal['descripcion']?></option>
						<?php } ?>
						</select>
					</td>
					<td>
						<select id='tipocargatotal' name='tipocargatotal' onchange="habilitarValorestotales(this.selectedIndex)" disabled="disabled">
							<option value='0'>Tipo Carga</option>
							<option value='1'>Por Modulo</option>
							<option value='2'>Por Galeno</option>
						</select>
					</td>
					<td><input id='moduloConsultoriototal' name='moduloConsultoriototal' onchange="cambiarvalor('moduloConsultorio', this.value)" type='text' disabled="disabled" size='7'/></td>
					<td><input id='moduloUrgenciatotal' name='moduloUrgenciatotal' onchange="cambiarvalor('moduloUrgencia', this.value)" type='text' disabled="disabled" size='7'/></td>
					<td><input id='gHonototal' name='gHonototal' onchange="cambiarvalor('gHono', this.value)" type='text' disabled="disabled" size='7'/></td>
					<td><input id='gHonoEspetotal' name='gHonoEspetotal' onchange="cambiarvalor('gHonoEspe', this.value)" type='text' disabled="disabled" size='7'/></td>
					<td><input id='gHonoAyudtotal' name='gHonoAyudtotal' onchange="cambiarvalor('gHonoAyud', this.value)" type='text' disabled="disabled" size='7'/></td>
					<td><input id='gHonoAnestotal' name='gHonoAnestotal' onchange="cambiarvalor('gHonoAnes', this.value)" type='text' disabled="disabled" size='7'/></td>
					<td><input id='gGastostotal' name='gGastostotal' onchange="cambiarvalor('gGastos', this.value)" type='text' disabled="disabled" size='7'/></td>
					<td><input id='cosegurototal' name='cosegurototal' onchange="cambiarvalor('coseguro', this.value)" type='text' disabled="disabled" size='7'/></td>
				</tr>
			</tbody>
		 	</table>
	 	</div>
	 	<h3>Practicas</h3>
	 </div>
	
	 <table style="text-align:center; width:1000px; font-size: 13px" id="practicas" class="tablesorter" >
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