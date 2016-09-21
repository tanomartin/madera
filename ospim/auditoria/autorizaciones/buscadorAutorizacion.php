<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$dato = $_POST['dato'];
$filtro = $_POST['filtro'];

if ($filtro == 0) {
	$cartel = "Resultados de Busqueda por Nro. Autorizacion <b>".$dato."</b>";
}
if ($filtro == 1) {
	$cartel = "Resultados de Busqueda por Fecha Autorizacion <b>".$dato."</b>";
}
if ($filtro == 2) {
	$cartel = "Resultados de Busqueda por C.U.I.L. <b>".$dato."</b>";
}
if ($filtro == 3) {
	$cartel = "Resultados de Busqueda por Delegación <b>".$dato."</b>";
}

$noExiste = 0;
$resultado = array();
if (isset($dato)) {
	if ($filtro == 0) { $sqlAutoriza = "SELECT a.nrosolicitud, a.fechasolicitud, a.codidelega, a.cuil, a.nroafiliado, a.codiparentesco, a.apellidoynombre, a.statusverificacion, a.usuarioverificacion, a.statusautorizacion, a.usuarioautorizacion, d.nombre from autorizaciones a, delegaciones d where a.nrosolicitud = $dato and a.codidelega = d.codidelega order by nrosolicitud DESC"; }
	if ($filtro == 1) { 
		$dato = fechaParaGuardar($dato);
		$sqlAutoriza = "SELECT a.nrosolicitud, a.fechasolicitud, a.codidelega, a.cuil, a.nroafiliado, a.codiparentesco, a.apellidoynombre, a.statusverificacion, a.usuarioverificacion, a.statusautorizacion, a.usuarioautorizacion, d.nombre from autorizaciones a, delegaciones d where a.fechasolicitud = '$dato' and a.codidelega = d.codidelega order by nrosolicitud DESC"; 
	}
	if ($filtro == 2) { $sqlAutoriza = "SELECT a.nrosolicitud, a.fechasolicitud, a.codidelega, a.cuil, a.nroafiliado, a.codiparentesco, a.apellidoynombre, a.statusverificacion, a.usuarioverificacion, a.statusautorizacion, a.usuarioautorizacion, d.nombre from autorizaciones a, delegaciones d where a.cuil = $dato and a.codidelega = d.codidelega order by nrosolicitud DESC"; }
	if ($filtro == 3) { $sqlAutoriza = "SELECT a.nrosolicitud, a.fechasolicitud, a.codidelega, a.cuil, a.nroafiliado, a.codiparentesco, a.apellidoynombre, a.statusverificacion, a.usuarioverificacion, a.statusautorizacion, a.usuarioautorizacion, d.nombre from autorizaciones a, delegaciones d where a.codidelega = $dato and a.codidelega = d.codidelega order by nrosolicitud DESC"; }
	if ($filtro == 4) { $sqlAutoriza = "SELECT a.nrosolicitud, a.fechasolicitud, a.codidelega, a.cuil, a.nroafiliado, a.codiparentesco, a.apellidoynombre, a.statusverificacion, a.usuarioverificacion, a.statusautorizacion, a.usuarioautorizacion, d.nombre from autorizaciones a, delegaciones d where a.nroafiliado = $dato and a.codidelega = d.codidelega order by nrosolicitud DESC"; }
	if ($filtro == 5) { $sqlAutoriza = "SELECT a.nrosolicitud, a.fechasolicitud, a.codidelega, a.cuil, a.nroafiliado, a.codiparentesco, a.apellidoynombre, a.statusverificacion, a.usuarioverificacion, a.statusautorizacion, a.usuarioautorizacion, d.nombre from autorizaciones a, delegaciones d where a.apellidoynombre like '%$dato%' and a.codidelega = d.codidelega order by nrosolicitud DESC"; }
	$resAutoriza = mysql_query($sqlAutoriza,$db); 
	$canAutoriza = mysql_num_rows($resAutoriza); 
	if ($canAutoriza == 0) {
		$noExiste = 1;
	}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo ospim :.</title>
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
		$("#listaResultado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{9:{sorter:false, filter:false}},
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
	if(formulario.dato.value == "") {
		alert("Debe colocar un dato de busqueda");
		return false;
	}
	if (formulario.filtro[0].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Nro. de autorizacion debe ser un numero entero positivo");
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
<form id="form1" name="form1" method="post" onSubmit="return validar(this)" action="buscadorAutorizacion.php">
  <div align="center" >
  <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloAutorizaciones.php'"/>
  <p align="center" class="Estilo1">M&oacute;dulo Buscador de Autorizaciones</p>
   <?php 
		if ($noExiste == 1) {
			print("<div style='color:#FF0000'><b> NO EXISTE AUTORIZACION CON ESTE FILTRO DE BUSQUEDA </b></div><br>");
		}
  ?>
  </div>
  <div align="center"> 
    <table style="width: 400; border: 0">
      <tr>
        <td rowspan="6"><div align="center"><strong>Buscar por </strong></div></td>
        <td><div align="left"><input type="radio" name="filtro"  value="0" checked="checked" /> Nro. Autorización </div></td>
      </tr>
      <tr>
        <td><div align="left"><input type="radio" name="filtro" value="1" /> Fecha Autorización</div></td>
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
	</table>
    <p><strong>Dato</strong> 
      <input name="dato" type="text" id="dato" size="14" />
    </p>
  </div>
  <p align="center">
    <label>
    <input type="submit" name="Buscar" value="Buscar" />
    </label>
  </p>
  <div align="center">
   <?php if ($noExiste == 0 and isset($dato)) { ?>
   <p><?php echo $cartel ?></p>
  <table id="listaResultado" class="tablesorter" style="text-align: center;">
	<thead>
		<tr>
			<th>Nro</th>
			<th>Fecha</th>
			<th class="filter-select" data-placeholder="Seleccione Delegación">Delegaci&oacute;n</th>
			<th>C.U.I.L.</th>
			<th>Afiliado</th>
			<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
			<th>Apellido y Nombre</th>
			<th class="filter-select" data-placeholder="Seleccione Estado">Verificaci&oacute;n</th>
			<th class="filter-select" data-placeholder="Seleccione Estado">Autorizaci&oacute;n</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
<?php
		while($rowLeeAutorizacion = mysql_fetch_array($resAutoriza)) {
?>
		<tr>
			<td><?php echo $rowLeeAutorizacion['nrosolicitud'];?></td>
			<td><?php echo invertirFecha($rowLeeAutorizacion['fechasolicitud']);?></td>
			<td><?php echo $rowLeeAutorizacion['codidelega']." - ".$rowLeeAutorizacion['nombre'];?></td>
			<td><?php echo $rowLeeAutorizacion['cuil'];?></td>
			<td><?php if($rowLeeAutorizacion['nroafiliado']==0) echo "-"; else echo $rowLeeAutorizacion['nroafiliado'];?></td>
			<td><?php if($rowLeeAutorizacion['codiparentesco']<0) echo "-"; else { if($rowLeeAutorizacion['codiparentesco']==0) echo "Titular"; else echo "Familiar ".$rowLeeAutorizacion['codiparentesco'];};?></td>
			<td><?php echo $rowLeeAutorizacion['apellidoynombre'];?></td>
			<td><?php	if($rowLeeAutorizacion['statusverificacion']==0) echo "No Verificada";
						if($rowLeeAutorizacion['statusverificacion']==1) echo "Aprobada - ".$rowLeeAutorizacion['usuarioverificacion'];
						if($rowLeeAutorizacion['statusverificacion']==2) echo "Rechazada - ".$rowLeeAutorizacion['usuarioverificacion'];
						if($rowLeeAutorizacion['statusverificacion']==3) echo "No Reverificada"; ?>
			</td>
			<td><?php	if($rowLeeAutorizacion['statusautorizacion']==0) echo "No Atendida";
						if($rowLeeAutorizacion['statusautorizacion']==1) echo "Aprobada - ".$rowLeeAutorizacion['usuarioautorizacion'];
						if($rowLeeAutorizacion['statusautorizacion']==2) echo "Rechazada - ".$rowLeeAutorizacion['usuarioautorizacion'];?>
			</td>
			<td><?php if($rowLeeAutorizacion['statusverificacion'] != 0 && $rowLeeAutorizacion['statusautorizacion'] != 0) {?>
				<a href="consultaAutorizacion.php?nroSolicitud=<?php echo $rowLeeAutorizacion['nrosolicitud']; ?>" target="popup" onClick="window.open(this.href, this.target, 'width=1100,height=800,scrollbars=yes'); return false;">Consultar Autorizacion</a><?php }?>
			</td>
		</tr>
<?php
		}
?>
	</tbody>
</table>
  <?php } ?>
  </div>
</form>
</body>
</html>
