<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
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
<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function mostrarMotivo(muestra) {
	if (muestra != 1) {
		document.forms.verificaSolicitud.motivoRechazo.value="";
		document.forms.verificaSolicitud.motivoRechazo.disabled=true;
	} else {
		document.forms.verificaSolicitud.motivoRechazo.disabled=false;
	}	
}

function validar(formulario) {
	if (formulario.rechazada.checked == true)
	{
		if(document.getElementById("motivoRechazo").value == "") {
			alert("Debe especificar un Motivo de Rechazo de la Solicitud");
			return false;
		}
	}
	$.blockUI({ message: "<h1>Guardando Verificacion. Aguarde por favor...</h1>" });
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
?>

<body>
<form id="verificaSolicitud" name="verificaSolicitud" method="POST" action="guardaVerificacion.php" onSubmit="return validar(this)" enctype="multipart/form-data" >
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
    <td><p><strong>N&uacute;mero de Afiliado:</strong> <?php if($rowLeeSolicitud['nroafiliado']!=0) echo $rowLeeSolicitud['nroafiliado']?></p>
        <p><strong>Apellido y Nombre: </strong><?php echo $rowLeeSolicitud['apellidoynombre']?></p>
        <p><strong>C.U.I.L.:</strong> <?php echo $rowLeeSolicitud['cuil'] ?></p>
        <p><strong>Tipo:</strong> <?php	if($rowLeeSolicitud['codiparentesco']!=0) {	if($rowLeeSolicitud['codiparentesco']==1) echo "Titular"; else echo "Familiar ".$rowLeeSolicitud['codiparentesco'];	}?></p><input id="solicitud" name="solicitud" value="<?php echo $nrosolicitud ?>" type="text" size="2" readonly="readonly"  style="visibility:hidden"/>
	</td>
    <td><p><strong>Consulta SSS:</strong> 
        <input name="consultaSSS" type="file" id="consultaSSS" size="65" /> </p>
		<p><strong>Verificaci&oacute;n:</strong> </p>
      	<label><input name="veri" id="aprobada" type="radio" value="1" onchange="mostrarMotivo(0)" checked="checked"/>Aprobada</label>
      	<br />
      	<label><input name="veri" id="rechazada" type="radio" value="2" onchange="mostrarMotivo(1)"/>Rechazada</label>
      	<p>
          <textarea name="motivoRechazo" cols="80" rows="5" id="motivoRechazo" disabled></textarea>
    	</p>
	</td>
  </tr>
  <tr>
    <td width="500">
	<div align="left"><input type="reset" name="volver" value="Volver" onclick="location.href = 'listarSolicitudes.php'"/></div>
	</td>
    <td width="600">
	<div align="right"><input type="submit" name="guardar" id="guardar" value="Guardar"/></div>
	</td>
  </tr>
</table>
</form>
</body>
</html>