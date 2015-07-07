<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
if (isset($_GET['fecha'])) {
	$fecha = $_GET['fecha'];
} else {
	$fecha = $_POST['fecha'];
}
$fechaBusqueda = fechaParaGuardar($fecha);

$sqlReque = "SELECT r.*, d.nombre, e.nombre as empresa from reqfiscalizospim r, delegaciones d, empresas e where fecharequerimiento = '$fechaBusqueda' and procesoasignado != 1 and requerimientoanulado = 0 and r.codidelega = d.codidelega and r.cuit = e.cuit";
$resReque = mysql_query($sqlReque,$db);
$canReque = mysql_num_rows($resReque);
if ($canReque == 0) {
	header ("Location: requerimientos.php?err=1&fecha=$fecha");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Requerimientos :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function redireccion(tipo ,nroreque, fecha, cuit) {
	var pagina = '';
	if (tipo == 'edicion') {
		pagina = "detalleRequerimiento.php?nroreq="+nroreque+"&fecha="+fecha+"&cuit="+cuit;
	}
	if (tipo == 'inspeccion') {
		pagina = "inspeccion.php?nroreq="+nroreque+"&fecha="+fecha+"&cuit="+cuit;
	}
	location.href=pagina;
}

function checkall(tipo, seleccion, formulario) {
 	var grupo = '';
	if (tipo == "anular") {
		grupo = formulario.anular;
	}
	if (tipo == "liquidar") {
		grupo = formulario.liquidar;
	}
	var total = grupo.length;
	if (total == null) {
		if (seleccion.checked) {
			grupo.checked = 1;
		} else {
			grupo.checked = 0;
		}
	}
	if (seleccion.checked) {
		 for (var i=0;i< grupo.length;i++) 
			 if(grupo[i].type == "checkbox")	
				 grupo[i].checked=1;  
	} else {
		 for (var i=0;i<grupo.length;i++) 
			 if(grupo[i].type == "checkbox")	
				 grupo[i].checked=0;  
	}
} 

function deshabilitarCheck(tipo,formulario) {
	if (tipo == 'liquidar') {
		grupo = formulario.anular;
	} 
	if (tipo == 'anular') {
		grupo = formulario.liquidar;
	}
	var total = grupo.length;
	if (total == null) {
		grupo.disabled = true;
	} else {
		for (var i = 0; i < total; i++) {
			grupo[i].disabled = true;
		}
	}
}

function validarCheck(tipo,formulario, fecha) {
	var grupo = '';
	var mensaje = ''; 
	if (tipo == 'liquidar') {
		grupo = formulario.liquidar;
		mensaje = "Debe seleccionar el o los requerimientos a liquidar";
		action = "liquidar.php?fecha="+fecha;
	} 
	if (tipo == 'anular') {
		grupo = formulario.anular;
		mensaje = "Debe seleccionar el o los requerimientos a anular";
		action = "anulaRequerimiento.php?fecha="+fecha;
	}
	var total = grupo.length;
	if (total == null) {
		if (!grupo.checked) {
			alert(mensaje);
			return false;
		}
	} else {
		var checkeados = 0; 
		for (var i = 0; i < total; i++) {
			if (grupo[i].checked) {
				checkeados++;
			}
		}
		if (checkeados == 0) {
			alert(mensaje);
			return false;
		}
	}
	if (tipo == 'liquidar') {
		$.blockUI({ message: "<h1>Generando Archivos de Fiscalizacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		deshabilitarCheck(tipo,formulario);
	}
	if (tipo == 'anular') {
		deshabilitarCheck(tipo,formulario);
	}
	formulario.selecAllAnular.disabled = true;
	formulario.selecAllLiquidar.disabled = true;
	formulario.anularboton.disabled = true;
	formulario.liquidarboton.disabled = true;
	formulario.action = action;
	formulario.submit();
}
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'requerimientos.php'" /></p>
  	<p class="Estilo2">Listado de  Requerimiento del d&iacute;a <?php echo $fecha ?>  </p>
	<form id="listadoReque" name="listadoReque" method="post">
	  <div class="grilla">
	  <table width="1000" border="1" align="center">
        <thead>
		<tr>
          <th>N&uacute;mero</th>
          <th>Origen</th>
          <th>Solicitante</th>
          <th>Motivo</th>
          <th>Empresa</th>
		  <th>Delegacion</th>
          <th>Detalle</th>
          <th>Acciones</th>
		  <th>Anular 
	      <input type="checkbox" name="selecAllAnular" id="selecAllAnular" onchange="checkall('anular', this, this.form)" /></th>
		  <th>Liquidar 
	      <input type="checkbox" name="selecAllLiquidar" id="selecAllLiquidar" onchange="checkall('liquidar', this, this.form)" /></th>
        </tr>
		</thead>
		<tbody>
        <?php while($rowReque = mysql_fetch_array($resReque)) { 
				if ($rowReque['origenrequerimiento'] == 1) { $origen = "Fiscalizaci&oacute;n";  }
				if ($rowReque['origenrequerimiento'] == 2) { $origen = "Afiliaciones"; }
				if ($rowReque['origenrequerimiento'] == 3) { $origen = "Prestaci&oacute;n"; } ?> 
				<tr>
				<td><?php echo $rowReque['nrorequerimiento'] ?></td> 
				<td><?php echo $origen ?></td> 
				<td><?php echo $rowReque['solicitarequerimiento'] ?></td>   
				<td><?php echo $rowReque['motivorequerimiento'] ?></td>
				<td><?php echo $rowReque['cuit']." - ".$rowReque['empresa'] ?></td>
				<td><?php echo $rowReque['nombre'] ?></td>
				<td><input type="button" value="Editar" onclick="redireccion('edicion','<?php echo $rowReque['nrorequerimiento'] ?>','<?php echo $fecha ?>','<?php echo $rowReque['cuit'] ?>')" /></td>
			<?php if ($rowReque['procesoasignado'] == 0) {	 ?>	
					<td><input type="button" value="Mandar a Inspección" onclick="redireccion('inspeccion','<?php echo $rowReque['nrorequerimiento'] ?>','<?php echo $fecha ?>','<?php echo $rowReque['cuit'] ?>')" /></td>
			<?php } else { 	
					$sqlInsp = "SELECT * from inspecfiscalizospim where nrorequerimiento = ".$rowReque['nrorequerimiento'];
					$resInsp = mysql_query($sqlInsp,$db);
					$rowInsp = mysql_fetch_array($resInsp);
					if ($rowInsp['inspeccionefectuada'] == 0) { ?>
						<td>Inspección En Curso</td>  
			<?php	}  	
				}?>
				<td><input type="checkbox" name="<?php echo $rowReque['nrorequerimiento'] ?>" id="anular" value="<?php echo $rowReque['nrorequerimiento'] ?>"/></td>   
				<td><input type="checkbox" name="<?php echo $rowReque['nrorequerimiento'] ?>" id="liquidar" value="<?php echo $rowReque['nrorequerimiento'] ?>"/></td>
			</tr>
	<?php	} ?>
			<tr>
				<td colspan='8'></td>
				<td><input type='button' name='anularboton' value='Anular' onclick="validarCheck('anular',this.form,'<?php echo $fecha ?>')"/></td>
				<td><input type='button' name='liquidarboton' value='Liquidar' onclick="validarCheck('liquidar',this.form,'<?php echo $fecha ?>')"/></td>
			</tr>
	  </tbody>
      </table>
	</div>
	</form>
</div>
</body>
</html>