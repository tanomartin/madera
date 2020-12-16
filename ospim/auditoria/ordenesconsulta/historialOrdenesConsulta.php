<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$noExiste = 0;
$resultado = array();
if (isset($_POST['dato']) && isset($_POST['filtro'])) {
	$dato = $_POST['dato'];
	$filtro = $_POST['filtro'];
	
	if ($filtro == 0) {
		$cartel = "Resultados de Busqueda por Nro. de Orden de Consulta <b>".$dato."</b>";
	}
	if ($filtro == 1) {
		$cartel = "Resultados de Busqueda por Fecha de Orden de Consulta <b>".$dato."</b>";
	}
	if ($filtro == 2) {
		$cartel = "Resultados de Busqueda por C.U.I.L. <b>".$dato."</b>";
	}
	if ($filtro == 3) {
		$cartel = "Resultados de Busqueda por Delegación <b>".$dato."</b>";
	}
	if ($filtro == 4) {
		$cartel = "Resultados de Busqueda por Nro. Afiliado <b>".$dato."</b>";
	}
	if ($filtro == 5) {
		$cartel = "Resultados de Busqueda por Apellido y Nombre <b>".$dato."</b>";
	}
	if ($filtro == 6) {
	    $cartel = "Resultados de Busqueda por C.U.I.L. del Titular <b>".$dato."</b>";
	}

	if (isset($dato)) {
		if ($filtro == 0) { $sqlOrden = "SELECT o.*, d.nombre as delegacion 
                                            FROM ordenesconsulta o, delegaciones d 
                                            WHERE o.id = $dato and o.delcod = d.codidelega 
                                            ORDER BY id DESC"; }
		if ($filtro == 1) { 
			$dato = fechaParaGuardar($dato);
			$sqlOrden = "SELECT o.*, d.nombre as delegacion 
                            FROM ordenesconsulta o, delegaciones d 
                            WHERE o.fechaorden = '$dato' and o.delcod = d.codidelega 
                            ORDER BY o.id DESC"; 
		}
		if ($filtro == 2) { $sqlOrden = "SELECT o.*, d.nombre as delegacion 
                                            FROM ordenesconsulta o, delegaciones d 
                                            WHERE o.nrcuil = $dato and o.delcod = d.codidelega
                                            ORDER BY o.id DESC"; }
		if ($filtro == 3) { $sqlOrden = "SELECT o.*, d.nombre as delegacion 
                                            FROM ordenesconsulta o, delegaciones d 
                                            WHERE o.delcod = $dato and o.delcod = d.codidelega 
                                            ORDER BY o.id DESC"; }
		if ($filtro == 4) { $sqlOrden = "SELECT o.*, d.nombre as delegacion 
                                            FROM ordenesconsulta o, delegaciones d 
                                            WHERE o.nrafil = $dato and o.delcod = d.codidelega 
                                            ORDER BY o.id DESC"; }
		if ($filtro == 5) { $sqlOrden = "SELECT o.*, d.nombre as delegacion 
                                            FROM ordenesconsulta o, delegaciones d 
                                            WHERE o.nombre like '%$dato%' and o.delcod = d.codidelega 
                                            ORDER BY o.id DESC"; }
		
		if ($filtro == 6) { $sqlOrden = "SELECT o.*, d.nombre as delegacion
                                            FROM ordenesconsulta o, delegaciones d
                                            WHERE o.nrcuiltitular = $dato and o.delcod = d.codidelega
                                            ORDER BY o.id DESC"; }
		$resOrden = mysql_query($sqlOrden,$db); 
		$canOrden = mysql_num_rows($resOrden); 
		if ($canOrden == 0) {
			$noExiste = 1;
		}
	}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Historico Ordenes de Consulta :.</title>
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
		$("#listaResultado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{10:{sorter:false, filter:false}},
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		}).tablesorterPager({container: $("#paginador")}); 
	});

function muestraArchivo(id) {
	param = "id=" + id;
	opciones = "top=50,left=50,width=1205,height=800,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=no,resizable=no"
	window.open ("mostrarArchivo.php?" + param, "", opciones);
}
	
function validar(formulario) {
	if(formulario.dato.value == "") {
		alert("Debe colocar un dato de busqueda");
		return false;
	}
	if (formulario.filtro[0].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Nro. de Orden de Consulta debe ser un numero entero positivo");
			return false;
		} 
	}
	if (formulario.filtro[1].checked) {
		resultado = esFechaValida(formulario.dato.value);
		if (!resultado) {
			alert("Fecha no valida. Debe ingresar una fecha valida con el siguiente formato dd-mm-aaaa");
			return false;
		} 
	}
	if (formulario.filtro[2].checked) {
		if (!verificaCuilCuit(formulario.dato.value)) {
			alert("C.U.I.L. invalido");
			return false;
		}
	}
	if (formulario.filtro[3].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Código de Delegación debe ser un numero entero positivo");
			return false;
		} 
	}
	if (formulario.filtro[4].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Nro. de afiliado debe ser un numero entero positivo");
			return false;
		} 
	}
	
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<form id="buscadorOrdenes" name="buscadorOrdenes" method="post" onSubmit="return validar(this)" action="historialOrdenesConsulta.php">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onClick="location.href = 'menuOrdenesConsulta.php'"/></p>
  <h3>Módulo Buscador de Ordenes de Consulta</h3>
   <?php if ($noExiste == 1) { ?>
			<div style='color:#FF0000'><b>NO EXISTE ORDEN DE CONSULTA CON ESTE FILTRO DE BUSQUEDA </b></div><br>
  <?php  } ?>
    <table style="width: 400; border: 0">
      <tr>
        <td rowspan="7"><div align="center"><strong>Buscar por </strong></div></td>
        <td><div align="left"><input type="radio" name="filtro"  value="0" checked="checked" /> Nro. Orden de Consulta </div></td>
      </tr>
      <tr>
        <td><div align="left"><input type="radio" name="filtro" value="1" /> Fecha Orden de Consulta</div></td>
      </tr>
      <tr>
        <td><div align="left"><input type="radio" name="filtro" value="2" /> C.U.I.L. Afiliado</div></td>
      </tr>
	  <tr>
        <td><div align="left"><input type="radio" name="filtro" value="3" /> Delegación</div></td>
      </tr>  
	  <tr>
        <td><div align="left"><input type="radio" name="filtro" value="4" /> Nro. Afiliado</div></td>
      </tr>  
	  <tr>
        <td><div align="left"><input type="radio" name="filtro" value="5" /> Apellido y/o Nombre Afiliado</div></td>
      </tr> 
      <tr>
        <td><div align="left"><input type="radio" name="filtro" value="6" /> C.U.I.L. Titular</div></td>
      </tr>  
	</table>
   <p><b>Dato: </b> <input name="dato" type="text" id="dato" size="14" /> </p>
   <p><input type="submit" name="Buscar" value="Buscar" /></p>
   <?php if ($noExiste == 0 and isset($dato)) { ?>
   <p><?php echo $cartel ?></p>
   <table id="listaResultado" class="tablesorter" style="text-align: center;">
	<thead>
		<tr>
			<th>Nro</th>
			<th>Fecha</th>
			<th>Fecah Vto</th>
			<th class="filter-select" data-placeholder="Seleccione Delegación">Delegaci&oacute;n</th>
			<th>C.U.I.L.</th>
			<th>Afiliado</th>
			<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
			<th>Apellido y Nombre</th>
			<th>C.U.I.L. Titular</th>
			<th class="filter-select" data-placeholder="Seleccione Estado">Estado</th>
			<th>+ INFO</th>
		</tr>
	</thead>
	<tbody>
<?php while($rowOrdenes = mysql_fetch_array($resOrden)) { ?>
		<tr>
			<td><?php echo $rowOrdenes['id'];?></td>
			<td><?php echo invertirFecha($rowOrdenes['fechaorden']);?></td>
			<td><?php echo invertirFecha($rowOrdenes['fechavto']);?></td>
			<td><?php echo $rowOrdenes['delcod']." - ".$rowOrdenes['delegacion'];?></td>
			<td><?php echo $rowOrdenes['nrcuil'];?></td>
			<td><?php echo $rowOrdenes['nrafil']; ?></td>
			<?php $codpar = $rowOrdenes['codpar'];
			      if ($codpar == -1) { $codpar = 'RC'; } ?>
			<td><?php if($rowOrdenes['codpar'] == 0) echo "Titular"; else echo "Familiar ".$codpar;?></td>
			<td><?php echo $rowOrdenes['nombre'];?></td>
			<td><?php echo $rowOrdenes['nrcuiltitular'];?></td>
			<td><?php if($rowOrdenes['autorizada'] == 0) echo "Para Autorizar";
			          if($rowOrdenes['autorizada'] == 1) echo "Aprobada Libre";
			          if($rowOrdenes['autorizada'] == 2) echo "Rechazada";
			          if($rowOrdenes['autorizada'] == 3) echo "Aprobada"; ?>
			</td>
			<td width="8%">
			<?php if ($rowOrdenes['autorizada'] == 0 || $rowOrdenes['autorizada'] == 2 || $rowOrdenes['autorizada'] == 3) { ?>
				     <input type="button" value="Ver HC" onclick="muestraArchivo('<?php echo $rowOrdenes['id'] ?>')">
			<?php } ?>
			</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<table style="text-align: center; width: 245; border: 0">
	<tr>
		<td width="239">
			<div id="paginador" class="pager">
				<form>
					<p>
						<img src="img/first.png" width="16" height="16" class="first"/> <img src="img/prev.png" width="16" height="16" class="prev"/>
						<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
						<img src="img/next.png" width="16" height="16" class="next"/> <img src="img/last.png" width="16" height="16" class="last"/>
						<select name="select" class="pagesize">
							<option selected="selected" value="10">10 por pagina</option>
							<option value="20">20 por pagina</option>
							<option value="30">30 por pagina</option>
							<option value="50">50 por pagina</option>
							<option value="<?php echo $canOrden;?>">Todos</option>
						</select>
					</p>
				</form>	
			</div>
		</td>
	</tr>
</table>
<?php } ?>
  </div>
</form>
</body>
</html>
