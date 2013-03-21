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
		document.forms.atiendeAutorizacion.motivoRechazo.value="";
		document.forms.atiendeAutorizacion.motivoRechazo.disabled=true;
		document.forms.atiendeAutorizacion.apeSi.disabled=false;
		document.forms.atiendeAutorizacion.apeNo.disabled=false;
		document.forms.atiendeAutorizacion.prestaSi.disabled=false;
		document.forms.atiendeAutorizacion.prestaNo.disabled=false;
		document.forms.atiendeAutorizacion.emailPresta.disabled=false;
	} else {
		document.forms.atiendeAutorizacion.motivoRechazo.disabled=false;
		document.forms.atiendeAutorizacion.apeSi.checked=false;
		document.forms.atiendeAutorizacion.apeSi.disabled=true;
		document.forms.atiendeAutorizacion.apeNo.checked=false;
		document.forms.atiendeAutorizacion.apeNo.disabled=true;
		document.forms.atiendeAutorizacion.prestaSi.checked=false;
		document.forms.atiendeAutorizacion.prestaSi.disabled=true;
		document.forms.atiendeAutorizacion.prestaNo.checked=false;
		document.forms.atiendeAutorizacion.prestaNo.disabled=true;
		document.forms.atiendeAutorizacion.emailPresta.value="";
		document.forms.atiendeAutorizacion.emailPresta.disabled=true;
	}	
}

function mostrarEmail(habilita) {
	if (habilita == 1) {
		document.forms.atiendeAutorizacion.emailPresta.disabled=false;
	}
	else {
		document.forms.atiendeAutorizacion.emailPresta.value="";
		document.forms.atiendeAutorizacion.emailPresta.disabled=true;
	}
}

function validar(formulario) {
	if (formulario.rechazada.checked == true) {
		if(document.getElementById("motivoRechazo").value == "") {
			alert("Debe especificar un Motivo de Rechazo de la Autorizacion");
			return false;
		}
	}

	if (formulario.aprobada.checked == true) {
		if(document.getElementById("apeSi").checked == false && document.getElementById("apeNo").checked == false) {
			alert("Debe especificar la Clasificacion APE");
			return false;
		}

		if(document.getElementById("prestaSi").checked == false && document.getElementById("prestaNo").checked == false) {
			alert("Debe especificar si envia o no el Email al Prestador");
			return false;
		}

		if (formulario.prestaSi.checked == true) {
			if(document.getElementById("emailPresta").value == "") {
				alert("Debe ingresar el correo electronico del prestador");
				return false;
			}
			else {
				//Valida Email Prestador
				object=document.getElementById("emailPresta");
				valueForm=object.value;
				var patron=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
				if(valueForm.search(patron)!=0) {
					//Email incorrecto
					alert("El correo electronico del prestador ingresado es incorrecto");
					return false;
				}
			}
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
<form id="atiendeAutorizacion" name="atiendeAutorizacion" method="POST" action="guardaAutorizacion.php" onSubmit="return validar(this)" enctype="multipart/form-data" >
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
        <p><strong>Tipo:</strong> <?php	if($rowLeeSolicitud['codiparentesco']!=0) {	if($rowLeeSolicitud['codiparentesco']==1) echo "Titular"; else echo "Familiar ".$rowLeeSolicitud['codiparentesco'];	}?>
          <input id="solicitud" name="solicitud" value="<?php echo $nrosolicitud ?>" type="text" size="2" readonly="readonly"  style="visibility:hidden"/>	
      </p></td>
    <td valign="top"><p><strong>Consulta SSS:</strong> <?php if($rowLeeSolicitud['consultasssverificacion']!=NULL) print "<a href='mostrarArchivo.php?nroSolicitud=".$rowLeeSolicitud['nrosolicitud']."&archivo=9' target='_blank'> Ver </a>"; ?></p>
		<p><strong>Verificaci&oacute;n:</strong> <?php if($rowLeeSolicitud['statusverificacion']==1) echo "Aprobada"; else echo "Rechazada";?></p>
   	  <p><?php echo "".$rowLeeSolicitud['rechazoverificacion'];?></p>		</td>
  </tr>
  <tr>
    <td width="500" height="50"><h3 align="left" class="Estilo4">Documentaci&oacute;n de la Solicitud</h3></td>
    <td width="600" height="50"><h3 align="left" class="Estilo4">Autorizaci&oacute;n</h3></td>
  </tr>
  <tr>
    <td valign="top"><p><strong>Tipo:</strong> <?php if($rowLeeSolicitud['practica']==1) echo "Practica"; else { if($rowLeeSolicitud['material']==1) echo "Material - ".$rowLeeMaterial['descripcion']; else { if($rowLeeSolicitud['medicamento']==1) echo "Medicamento";}} ?></p>
      <p><strong>Pedido Medico:</strong> <?php if($rowLeeSolicitud['pedidomedico']!=NULL) print "<a href='mostrarArchivo.php?nroSolicitud=".$rowLeeSolicitud['nrosolicitud']."&archivo=1' target='_blank'> Ver </a>"; ?></p>
      <p><strong>Historia Cl&iacute;nica:</strong> <?php if($rowLeeSolicitud['resumenhc']!=NULL) print "<a href='mostrarArchivo.php?nroSolicitud=".$rowLeeSolicitud['nrosolicitud']."&archivo=2' target='_blank'> Ver </a>"; ?></p>
      <p><strong>Estudios:</strong> <?php if($rowLeeSolicitud['avalsolicitud']!=NULL) print "<a href='mostrarArchivo.php?nroSolicitud=".$rowLeeSolicitud['nrosolicitud']."&archivo=3' target='_blank'> Ver </a>"; ?></p>
      <p><strong>Presupuestos:</strong></p>
      <p><?php if($rowLeeSolicitud['presupuesto1']!=NULL) { print "<a href='mostrarArchivo.php?nroSolicitud=".$rowLeeSolicitud['nrosolicitud']."&archivo=4' target='_blank'> Ver </a>"; print(" ===> Seleccione el Aprobado: <input type='checkbox' name='elige1'>");} ?></p>
      <p><?php if($rowLeeSolicitud['presupuesto2']!=NULL) { print "<a href='mostrarArchivo.php?nroSolicitud=".$rowLeeSolicitud['nrosolicitud']."&archivo=5' target='_blank'> Ver </a>"; print(" ===> Seleccione el Aprobado: <input type='checkbox' name='elige2'>");} ?></p>
      <p><?php if($rowLeeSolicitud['presupuesto3']!=NULL) { print "<a href='mostrarArchivo.php?nroSolicitud=".$rowLeeSolicitud['nrosolicitud']."&archivo=6' target='_blank'> Ver </a>"; print(" ===> Seleccione el Aprobado: <input type='checkbox' name='elige3'>");} ?></p>
      <p><?php if($rowLeeSolicitud['presupuesto4']!=NULL) { print "<a href='mostrarArchivo.php?nroSolicitud=".$rowLeeSolicitud['nrosolicitud']."&archivo=7' target='_blank'> Ver </a>"; print(" ===> Seleccione el Aprobado: <input type='checkbox' name='elige4'>");} ?></p>
      <p><?php if($rowLeeSolicitud['presupuesto5']!=NULL) { print "<a href='mostrarArchivo.php?nroSolicitud=".$rowLeeSolicitud['nrosolicitud']."&archivo=8' target='_blank'> Ver </a>"; print(" ===> Seleccione el Aprobado: <input type='checkbox' name='elige5'>");} ?></p>    </td>
	<td valign="top">
	  <label><input name="autori" id="aprobada" type="radio" value="1" onchange="mostrarMotivo(0)" checked="checked"/>Aprobada</label><br />
      <label><input name="autori" id="rechazada" type="radio" value="2" onchange="mostrarMotivo(1)"/>Rechazada</label>
      <p><textarea name="motivoRechazo" cols="80" rows="5" id="motivoRechazo" disabled="disabled"></textarea></p>
      <p>APE:<label><input name="ape" id="apeSi" type="radio" value="1"/>Si</label>
        <label><input name="ape" id="apeNo" type="radio" value="2"/>No</label></p>
      <p>Comunica al Prestador ?:
	         <label><input name="presta" id="prestaSi" type="radio" value="1" onchange="mostrarEmail(1)"/>Si</label>
             <label><input name="presta" id="prestaNo" type="radio" value="2" onchange="mostrarEmail(0)"/>No</label>
- Email             
<input name="emailPresta" type="text" id="emailPresta" size="50" maxlength="50" disabled="disabled"/>
      </p>	</td>
  </tr>
  <tr>
    <td width="500"><div align="left"><input type="reset" name="volver" value="Volver" onclick="location.href = 'listarSolicitudes.php'"/></div></td>
    <td width="600"><div align="right"><input type="submit" name="guardar" id="guardar" value="Guardar"/></div></td>
  </tr>
</table>
</form>
</body>
</html>