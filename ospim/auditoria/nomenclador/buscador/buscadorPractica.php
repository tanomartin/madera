<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."funcionespracticas.php");

$dato = $_POST['dato'];
$filtro = $_POST['filtro'];

if ($filtro == 0) {
	$cartel = "Resultados de Busqueda por Código <b>".$dato."</b>";
}
if ($filtro == 1) {
	$cartel = "Resultados de Busqueda por Descripción <b>".$dato."</b>";
}

$noExiste = 0;
$resultado = array();
if (isset($dato)) {
	if ($filtro == 0) { $sqlPracticas = "SELECT p.*, t.descripcion as tipo, c.descripcion as complejidad, n.nombre as nombrenomenclador FROM practicas p, tipopracticas t, tipocomplejidad c, nomencladores n WHERE p.codigopractica = '$dato' and p.tipopractica = t.id and p.codigocomplejidad = c.codigocomplejidad and p.nomenclador = n.id order by codigopractica DESC";}
	if ($filtro == 1) { $sqlPracticas = "SELECT p.*, t.descripcion as tipo, c.descripcion as complejidad, n.nombre as nombrenomenclador FROM practicas p, tipopracticas t, tipocomplejidad c, nomencladores n WHERE p.descripcion like '%$dato%' and p.tipopractica = t.id and p.codigocomplejidad = c.codigocomplejidad and p.nomenclador = n.id order by codigopractica DESC"; }
	$resPracticas = mysql_query($sqlPracticas,$db);
	$numPracticas = mysql_num_rows($resPracticas);
	if ($numPracticas == 0) {
		$noExiste = 1;
	}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Buscador Practica :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
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
			headers:{5:{sorter:false},
					 6:{sorter:false, filter: false},
					 7:{sorter:false, filter: false},
					 8:{sorter:false, filter: false},
					 9:{sorter:false, filter: false},
					 10:{sorter:false, filter: false},
					 12:{sorter:false, filter: false},
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
		});
	});

function abrirPantalla(dire) {
	a= window.open(dire,"detallePresatadoresPracticas",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

function validar(formulario) {
	if(formulario.dato.value == "") {
		alert("Debe colocar un dato de busqueda");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="buscadorPractica.php">
  <div align="center" >
  <input type="button" name="volver" value="Volver" onclick="location.href = '../menuNomenclador.php'" />
  <p align="center" class="Estilo1">M&oacute;dulo Buscador de Pr&aacute;cticas </p>
   <?php 
		if ($noExiste == 1) {
			print("<div style='color:#FF0000'><b> NO EXISTE PRACTICA CON ESTE FILTRO DE BUSQUEDA </b></div><br>");
		}
  ?>
  </div>
  <div align="center"> 
    <table style="width: 238; border: 0">
      <tr>
        <td width="87" rowspan="2"><div align="center"><strong>Buscar por </strong></div></td>
        <td width="141"><div align="left"><input type="radio" name="filtro"  value="0" checked="checked" /> 
        C&oacute;digo </div></td>
      </tr>
      <tr>
        <td><div align="left"><input type="radio" name="filtro" value="1" /> 
        Descripci&oacute;n </div></td>
      </tr>
	</table>
    <p><strong>Dato</strong> 
		<?php if (isset($_GET['dato'])) { $valorDeInsert = $_GET['dato']; } else { $valorDeInsert = ""; } ?>
      <input name="dato" type="text" id="dato" size="14" value="<?php echo $valorDeInsert ?>"/>
    </p>
  </div>
  <p align="center">
    <label>
    <input type="submit" name="Buscar" value="Buscar" />
    </label>
  </p>
  <div align="center">
   <?php if ($noExiste == 0 and isset($dato)) { 
  ?>    <table style="text-align:center; width:1000px; font-size: 13px" id="practicas" class="tablesorter" >
     <thead>
       <tr>
         <th>C&oacute;digo</th>
		 <th class="filter-select" data-placeholder="Seleccione Nomenclador">Nomenclador</th>
		 <th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
	     <th class="filter-select" data-placeholder="Seleccione Capitulo">Capitulo</th>
	     <th class="filter-select" data-placeholder="Seleccione Subcapitulo">Subcapitulo</th>
         <th>Descripciones</th>
         <th>U. Honorarios</th>
         <th>U. Honorarios Especialista</th>
		 <th>U. Honorarios Ayudante</th>
		 <th>U. Honorarios Anestesista</th>
		 <th>U. Gastos</th>
		 <th>Complejidad</th>
		 <th>Acciones</th>
       </tr>
     </thead>
     <tbody>
       <?php
			while($rowPracticas = mysql_fetch_array($resPracticas)) {
				$descripPractica = descripcionPractica($rowPracticas['codigopractica'],$rowPracticas['tipopractica'],$db); ?>
		   <tr>
			 <td><?php echo $rowPracticas['codigopractica'];?></td>
			 <td><?php echo $rowPracticas['nombrenomenclador']; ?></td>
			 <td><?php echo $rowPracticas['tipo'] ?></td>
			 <td><?php echo $descripPractica['capitulo'] ?></td>
			 <td><?php echo $descripPractica['subcapitulo'] ?></td>
			 <td><?php echo $rowPracticas['descripcion'];?></td>
			 <td><?php echo $rowPracticas['unihonorario'];?></td>
			 <td><?php echo $rowPracticas['unihonorarioespecialista'];?></td>
			 <td><?php echo $rowPracticas['unihonorarioayudante'];?></td>
			 <td><?php echo $rowPracticas['unihonorarioanestesista'];?></td>
			 <td><?php echo $rowPracticas['unigastos'];?></td>
			 <td><?php echo $rowPracticas['complejidad']; ?></td>
			 <td>
			 	 <input name="prestadores" type="button" value="Prestadores" onclick="abrirPantalla('detallePracticasPresta.php?idpractica=<?php echo $rowPracticas['idpractica'] ?>')"/>
			 	 <input name="agregar" type="button" value="Agregar Prestador" onclick="abrirPantalla('agregarPracticaPrestador.php?idpractica=<?php echo $rowPracticas['idpractica'] ?>')"/>
			 </td>
		   </tr>
       <?php
			}
		?>
     </tbody>
   </table>
   <p>
     <?php } ?>
    </p>
  </div>
</form>
</body>
</html>
