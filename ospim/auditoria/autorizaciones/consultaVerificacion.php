<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nrosolicitud=$_GET['nroSolicitud'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Detalle Solicitud</title>
<style type="text/css">
<!--
.Estilo3 {
	font-family: Papyrus;
	font-weight: bold;
	color: #999999;
	font-size: 24px;
}
body {
	background-color: #CCCCCC;
}
.Estilo4 {
	color: #990000;
	font-weight: bold;
}
-->
</style>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function muestraArchivo(solicitud, archivo) {
	param = "nroSolicitud=" + solicitud;
	param += "&archivo=" + archivo;
	opciones = "top=50,left=50,width=1205,height=800,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=no,resizable=no"
	window.open ("mostrarArchivo.php?" + param, "", opciones);
}

function validar(formulario) {
	if (formulario.rechazada.checked == true) {
		if(document.getElementById("motivoRechazo").value == "") {
			alert("Debe especificar un Motivo de Rechazo de la Autorizacion");
			document.getElementById("motivoRechazo").focus();
			return false;
		}
	}

	if (formulario.reverificar.checked == true) {
		if(document.getElementById("motivoRechazo").value == "") {
			alert("Debe especificar el Motivo para Solicitar Reverificacion");
			document.getElementById("motivoRechazo").focus();
			return false;		
		}
	}
	
	$.blockUI({ message: "<h1>Guardando Autorizacion. Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>

<?php
$sqlLeeSolicitud="SELECT * FROM autorizaciones where nrosolicitud = $nrosolicitud";
$resultLeeSolicitud=mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud=mysql_fetch_array($resultLeeSolicitud);

$sqlLeeDeleg = "SELECT * FROM delegaciones where codidelega = $rowLeeSolicitud[codidelega]";
$resultLeeDeleg = mysql_query($sqlLeeDeleg,$db); 
$rowLeeDeleg = mysql_fetch_array($resultLeeDeleg);

if($rowLeeSolicitud['material']==1) {
	$sqlLeeMaterial = "SELECT * FROM clasificamaterial where codigo = $rowLeeSolicitud[tipomaterial]";
	$resultLeeMaterial = mysql_query($sqlLeeMaterial,$db); 
	$rowLeeMaterial = mysql_fetch_array($resultLeeMaterial);
}

?>

<body>
<form id="consultaVerificacion" name="consultaVerificacion" method="POST" action="guardaAutorizacionReverifica.php" onSubmit="return validar(this)" enctype="multipart/form-data" >
<table width="1100" border="0">
  <tr>
    <td width="92" scope="row"><div align="center"><span class="Estilo3"><img src="../img/logoSolo.jpg" width="92" height="81" /></span></div></td>
    <td colspan="2" scope="row"><div align="left">
      <p class="Estilo3">Solicitud N&uacute;mero <?php echo $nrosolicitud ?></p>
    </div></td>
    <td width="550"><div align="right">
      <table width="450" height="60" border="2">
        <tr>
          <td width="143" height="25"><div align="center"><strong>Fecha Solicitud</strong> </div></td>
          <td width="289"><div align="center"><?php echo invertirFecha($rowLeeSolicitud['fechasolicitud']);?></div></td>
        </tr>
        <tr>
          <td width="143" height="25"><div align="center"><strong>Delegaci&oacute;n</strong></div></td>
          <td width="289"><div align="center"><?php echo "".$rowLeeSolicitud['codidelega']." - ".$rowLeeDeleg['nombre'];?></div></td>
        </tr>
      </table>
    </div>
      <div align="right"></div></td>
  </tr>
</table>
<table width="1100" border="0">
  <tr>
    <td width="500" height="50"><h3 align="left" class="Estilo4">Informaci&oacute;n del Beneficiario</h3></td>
    <td width="600" height="50"><h3 align="left" class="Estilo4">Resultado de la Verificaci&oacute;n</h3></td>
  </tr>
  <tr>
    <td valign="top"><p><strong>N&uacute;mero de Afiliado:</strong> <?php if($rowLeeSolicitud['nroafiliado']!=0) echo $rowLeeSolicitud['nroafiliado']?></p>
        <p><strong>Apellido y Nombre: </strong><?php echo $rowLeeSolicitud['apellidoynombre']?></p>
        <p><strong>C.U.I.L.:</strong> <?php echo $rowLeeSolicitud['cuil'] ?></p>
        <p><strong>Tipo:</strong> <?php	if($rowLeeSolicitud['codiparentesco']>0) {	if($rowLeeSolicitud['codiparentesco']==0) echo "Titular"; else echo "Familiar ".$rowLeeSolicitud['codiparentesco'];	}?>
          <input id="solicitud" name="solicitud" value="<?php echo $nrosolicitud ?>" type="text" size="2" readonly="readonly"  style="visibility:hidden"/>	
      </p></td>
    <td valign="top"><p><strong>Consulta SSS:</strong> <?php if($rowLeeSolicitud['consultasssverificacion']!=NULL) {?><input type="button" name="consultasss" value="Ver" onClick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,9)" align="center"/><?php }?></p>
		<p><strong>Verificaci&oacute;n:</strong> <?php if($rowLeeSolicitud['statusverificacion']==1) echo "Aprobada"; else echo "Rechazada";?></p>
   	  <p><?php echo "".$rowLeeSolicitud['rechazoverificacion'];?></p></td>
  </tr>
  <tr>
    <td width="500" height="50"><h3 align="left" class="Estilo4">Documentaci&oacute;n de la Solicitud</h3></td>
    <td width="600" height="50"><h3 align="left" class="Estilo4">Autorizaci&oacute;n</h3></td>
  </tr>
  <tr>
    <td valign="top"><p><strong>Tipo:</strong> <?php if($rowLeeSolicitud['practica']==1) echo "Practica"; else { if($rowLeeSolicitud['material']==1) echo "Material - ".$rowLeeMaterial['descripcion']; else { if($rowLeeSolicitud['medicamento']==1) echo "Medicamento";}} ?></p>
      <p><strong>Pedido Medico:</strong> <?php if($rowLeeSolicitud['pedidomedico']!=NULL) {?><input type="button" name="pedidomedico" value="Ver" onClick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,1)" align="center"/><?php }?></p>
      <p><strong>Historia Cl&iacute;nica:</strong> <?php if($rowLeeSolicitud['resumenhc']!=NULL) {?><input type="button" name="historiaclinica" value="Ver" onClick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,2)" align="center"/><?php }?></p>
      <p><strong>Estudios:</strong> <?php if($rowLeeSolicitud['avalsolicitud']!=NULL) {?><input type="button" name="estudios" value="Ver" onClick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,3)" align="center"/><?php }?></p>
      <p><strong>Presupuestos:</strong></p>
      <p><?php if($rowLeeSolicitud['presupuesto1']!=NULL) {?><input type="button" name="presupuesto1" value="Ver" onClick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,4)" align="center"/><?php }?></p>
      <p><?php if($rowLeeSolicitud['presupuesto2']!=NULL) {?><input type="button" name="presupuesto2" value="Ver" onClick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,5)" align="center"/><?php }?></p>
      <p><?php if($rowLeeSolicitud['presupuesto3']!=NULL) {?><input type="button" name="presupuesto3" value="Ver" onClick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,6)" align="center"/><?php }?></p>
      <p><?php if($rowLeeSolicitud['presupuesto4']!=NULL) {?><input type="button" name="presupuesto4" value="Ver" onClick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,7)" align="center"/><?php }?></p>
      <p><?php if($rowLeeSolicitud['presupuesto5']!=NULL) {?><input type="button" name="presupuesto5" value="Ver" onClick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,8)" align="center"/><?php }?></p></td>
	<td valign="top">
	  <label><input name="autori" id="reverificar" type="radio" value="1" checked="checked"/>Solicitar Reverificacion</label><br />
      <label><input name="autori" id="rechazada" type="radio" value="2"/>Rechazada</label>
      <p>
        <textarea name="motivoRechazo" cols="80" rows="5" id="motivoRechazo"></textarea></p>
      </td>
  </tr>
  <tr>
    <td width="500"><div align="left"><input type="reset" name="volver" value="Volver" onclick="location.href = 'listarSolicitudes.php'"/></div></td>
    <td width="600"><div align="right"><input type="submit" name="guardar" id="guardar" value="Guardar"/></div></td>
  </tr>
</table>
</form>
</body>
</html>