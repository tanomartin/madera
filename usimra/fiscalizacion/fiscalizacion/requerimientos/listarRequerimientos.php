<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");
if (isset($_GET['fecha'])) {
	$fecha = $_GET['fecha'];
} else {
	$fecha = $_POST['fecha'];
}
$fechaBusqueda = fechaParaGuardar($fecha);

$sqlReque = "SELECT * from reqfiscalizusimra where fecharequerimiento = '$fechaBusqueda' and procesoasignado != 1 and requerimientoanulado = 0";
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


<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	var grupo = formulario.requerimientos;
	var total = grupo.length;
	if (total == null) {
		if (!formulario.requerimientos.checked) {
			alert("Debe seleccionar el o los requerimientos a liquidar");
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
			alert("Debe seleccionar el o los requerimientos a liquidar");
			return false;
		}
	}
	//$.blockUI({ message: "<h1>Generando Archivos de Fiscalizacion... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = 'requerimientos.php'"/>
  </span></p>
  	<p class="Estilo2">Listado de  Requerimiento del d&iacute;a <?php echo $fecha ?>  </p>
	<form id="listadoReque" name="listadoReque" method="post" onsubmit="return validar(this)" action="liquidar.php?fecha=<?php echo $fecha ?>">
	  <table width="999" border="1" align="center" style="text-align: center;">
        <tr>
          <th>N&uacute;mero</th>
          <th>Origen</th>
          <th>Solicitante</th>
          <th>Motivo</th>
          <th>Cuit</th>
          <th>Detalle</th>
          <th>Acciones</th>
		  <th>Liquidar</th>
        </tr>
        <?php while($rowReque = mysql_fetch_array($resReque)) { 
	        	if ($rowReque['origenrequerimiento'] == 1) {
	        		$origen = "Fiscalizaci&oacute;n";
	        	}
	        	if ($rowReque['origenrequerimiento'] == 2) {
	        		$origen = "Afiliaciones";
	        	}
	        	if ($rowReque['origenrequerimiento'] == 3) {
	        		$origen = "Prestaci&oacute;n";
	        	} ?>
				<tr>
				<td><?php echo $rowReque['nrorequerimiento'] ?></td>
				<td><?php echo $origen ?></td>   
				<td><?php echo $rowReque['solicitarequerimiento'] ?></td>   
				<td><?php echo $rowReque['motivorequerimiento'] ?></td>
				<td><?php echo $rowReque['cuit'] ?></td>
				<td><input type="button" onclick="location.href='detalleRequerimiento.php?nroreq=<?php echo $rowReque['nrorequerimiento'] ?>&fecha=<?php echo $fecha ?>&cuit=<?php echo $rowReque['cuit'] ?>'" value="Editar" /></td>
		<?php	if ($rowReque['procesoasignado'] == 0) {		?>
					<td><input type="button" onclick="location.href='inspeccion.php?nroreq=<?php echo $rowReque['nrorequerimiento'] ?>&fecha=<?php echo $fecha ?>&cuit=<?php echo $rowReque['cuit'] ?>'" value="Inspección" />
						-
						<input type="button" onclick="location.href='anulaRequerimiento.php?nroreq=<?php echo $rowReque['nrorequerimiento'] ?>&fecha=<?php echo $fecha ?>'" value="Anular" />
					</td> 
					<td><input type='checkbox' name='<?php echo $rowReque['nrorequerimiento'] ?>' id='requerimientos' value='<?php echo $rowReque['nrorequerimiento'] ?>'/></td>
		<?php	} else { 
					$sqlInsp = "SELECT * from inspecfiscalizusimra where nrorequerimiento = ".$rowReque['nrorequerimiento'];
					$resInsp = mysql_query($sqlInsp,$db);
					$rowInsp = mysql_fetch_array($resInsp); 
	        		if ($rowInsp['inspeccionefectuada'] == 0) { ?>
						<td>Inspección En Curso</td>  
						<td></td>
			<?php	} else { ?>
					<td><input type="button" onclick="location.href='anulaRequerimiento.php?nroreq=<?php echo $rowReque['nrorequerimiento'] ?>&fecha=<?php echo $fecha  ?>'" value="Anular" /></td> 
					<td><input type='checkbox' name='<?php echo $rowReque['nrorequerimiento'] ?>' id='requerimientos' value='<?php echo $rowReque['nrorequerimiento']  ?>'/></td>
		<?php		}
        		}   ?>      
				</tr>
	<?php	}  ?>
      </table>
        <table width="999" border="0">
          <tr>
            <td width="900">&nbsp;</td>
            <td width="100" style="text-align: center;">
                <input type="submit" name="Submit" value="Liquidar" />
             </td>
          </tr>
        </table>
	</form>
</div>
</body>
</html>