<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nrosolicitud=$_GET['nroSolicitud'];
setcookie($nrosolicitud, $_SESSION['usuario'], time() + (86400 * 7));
$sqlLeeSolicitud = "SELECT a.*, doc.*, d.nombre as delegacion, clasificamaterial.descripcion as tipomaterial
					FROM delegaciones d, autorizacionesdocoriginales doc, autorizaciones a
					LEFT JOIN clasificamaterial ON a.tipomaterial = clasificamaterial.codigo
					WHERE a.nrosolicitud = $nrosolicitud and 
						  a.nrosolicitud = doc.nrosolicitud and
						  a.codidelega = d.codidelega";
$resultLeeSolicitud = mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud = mysql_fetch_array($resultLeeSolicitud);

$nombre = $rowLeeSolicitud['apellidoynombre'];
$nroafiliado = "-"; 
if ($rowLeeSolicitud['nroafiliado'] != 0) { 
	$nroafiliado = $rowLeeSolicitud['nroafiliado']; 
}
$tipoAfiliado = "NO EMPADRONADO";
$tipoTitular = "-";
$nroorden = 0;
$naci = "-";
$edad = "-";

if ($rowLeeSolicitud['codiparentesco'] <= 0) {
	$sqlTipoTitular = "SELECT nroafiliado, apellidoynombre, descrip, cuil, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad, fechanacimiento 
						FROM titulares t, tipotitular p 
						WHERE t.cuil = ".$rowLeeSolicitud['cuil']." and t.situaciontitularidad = p.codtiptit";
	$resTipoTitular = mysql_query($sqlTipoTitular,$db);
	$canTipoTitular = mysql_num_rows($resTipoTitular);
	if ($canTipoTitular > 0) {
		$tipoAfiliado = "TITULAR";
		$rowTipoTitular = mysql_fetch_assoc($resTipoTitular);
		$tipoTitular = $rowTipoTitular['descrip'];
		$edad = $rowTipoTitular['edad'];
		$naci = $rowTipoTitular['fechanacimiento'];
		$nombre = $rowTipoTitular['apellidoynombre'];
		$nroafiliado = $rowTipoTitular['nroafiliado'];
	} else {
		$sqlTipoTitularBaja = "SELECT nroafiliado, apellidoynombre, descrip, cuil, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad, fechanacimiento
								FROM titularesdebaja t, tipotitular p
								WHERE t.cuil = ".$rowLeeSolicitud['cuil']." and t.situaciontitularidad = p.codtiptit";
		$resTipoTitularBaja = mysql_query($sqlTipoTitularBaja,$db);
		$canTipoTitularBaja = mysql_num_rows($resTipoTitularBaja);
		if ($canTipoTitularBaja > 0) {
			$tipoAfiliado = "TITULAR DE BAJA";
			$rowTipoTitularBaja = mysql_fetch_assoc($resTipoTitularBaja);
			$tipoTitular = $rowTipoTitularBaja['descrip'];
			$edad = $rowTipoTitularBaja['edad'];
			$naci = $rowTipoTitularBaja['fechanacimiento'];
			$nombre = $rowTipoTitularBaja['apellidoynombre'];
			$nroafiliado = $rowTipoTitularBaja['nroafiliado'];
		}
	}
}

if ($tipoAfiliado == "NO EMPADRONADO" && $rowLeeSolicitud['codiparentesco'] != 0) {
	$sqlFamiliar = "SELECT nroafiliado, apellidoynombre, nroorden, cuil, fechanacimiento, parentesco.descrip as paretensco,
						DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad
					 FROM familiares  
					 LEFT JOIN parentesco ON familiares.tipoparentesco = parentesco.codparent 
					 WHERE cuil = ".$rowLeeSolicitud['cuil'];
	$resFamiliar = mysql_query($sqlFamiliar,$db);
	$canFamiliar = mysql_num_rows($resFamiliar);
	if ($canFamiliar > 0) {
		$rowFamiliar = mysql_fetch_assoc($resFamiliar);
		$tipoAfiliado = "FAMILIAR - ".$rowFamiliar['paretensco'];
		$edad = $rowFamiliar['edad'];
		$naci = $rowFamiliar['fechanacimiento'];
		$nroorden = $rowFamiliar['nroorden'];
		$nombre = $rowFamiliar['apellidoynombre'];
		$nroafiliado = $rowFamiliar['nroafiliado'];
	} else {
		$sqlFamiliarBaja = "SELECT nroafiliado, apellidoynombre, nroorden, cuil, fechanacimiento, parentesco.descrip as paretensco,
								DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad
							FROM familiaresdebaja 
				  			LEFT JOIN parentesco ON familiaresdebaja.tipoparentesco = parentesco.codparent 
							WHERE cuil = ".$rowLeeSolicitud['cuil'];
		$resFamiliarBaja = mysql_query($sqlFamiliarBaja,$db);
		$canFamiliarBaja = mysql_num_rows($resFamiliarBaja);
		if ($canFamiliarBaja > 0) {
			$rowFamiliarBaja = mysql_fetch_assoc($resFamiliarBaja);
			$tipoAfiliado = "FAMILIAR DE BAJA - ".$rowFamiliarBaja['paretensco'];
			$edad = $rowFamiliarBaja['edad'];
			$naci = $rowFamiliarBaja['fechanacimiento'];
			$nroorden = $rowFamiliarBaja['nroorden'];
			$nombre = $rowFamiliarBaja['apellidoynombre'];
			$nroafiliado = $rowFamiliarBaja['nroafiliado'];
		}
	}
}

$canDisca = 0;
$canHIV = 0;
$canOnco = 0;
$canPMI = 0;
$canDiabetes = 0;
if ($tipoAfiliado != "NO EMPADRONADO") {
	//VEO SI ES DISCA
	$sqlDisca = "SELECT DATE_FORMAT(fechaalta,'%d/%m/%Y') as fechaalta, nroorden, nroafiliado,
					DATE_FORMAT(emisioncertificado,'%d/%m/%Y') as emisioncertificado,
					DATE_FORMAT(vencimientocertificado,'%d/%m/%Y') as vencimientocertificado
					FROM discapacitados
					WHERE cuil = ".$rowLeeSolicitud['cuil'];
	$resDisca = mysql_query($sqlDisca,$db);
	$canDisca = mysql_num_rows($resDisca);

	//VEO SI ES HIV
	$sqlHIV = "SELECT *
				FROM hivbeneficiarios
				WHERE nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and nroorden = $nroorden";
	$resHIV = mysql_query($sqlHIV,$db);
	$canHIV = mysql_num_rows($resHIV);

	//VEO SI ES ONCO
	$sqlOnco = "SELECT *
				FROM oncologiabeneficiarios o
				WHERE nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and nroorden = $nroorden";
	$resOnco = mysql_query($sqlOnco,$db);
	$canOnco = mysql_num_rows($resOnco);

	//VEO SI ES ESTA EN PMI
	$fechaLimite = date('Y-m-d',strtotime('-1 month',strtotime (date('Y-m-d'))));
	$sqlPMI = "SELECT * FROM pmibeneficiarios
				WHERE nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and
				nroorden = $nroorden and
				(fechanacimiento != '0000-00-00' and fechanacimiento >= '$fechaLimite'
				or fechanacimiento = '0000-00-00' and fpp >= '$fechaLimite')";
	$resPMI = mysql_query($sqlPMI,$db);
	$canPMI = mysql_num_rows($resPMI);

	//VEO SI ES DIABETES
	$sqlDiabetes = "SELECT * FROM diabetesbeneficiarios
					WHERE nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and nroorden = $nroorden";
	$resDiabetes = mysql_query($sqlDiabetes,$db);
	$canDiabetes = mysql_num_rows($resDiabetes);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Detalle Solicitud</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function muestraArchivo(solicitud, archivo) {
	param = "nroSolicitud=" + solicitud;
	param += "&archivo=" + archivo;
	opciones = "top=50,left=50,width=1205,height=800,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=no,resizable=no"
	window.open ("mostrarArchivo.php?" + param, "", opciones);
}

function verCertificado(dire){	
	window.open(dire,'Certificado de Discapacidad','width=800, height=500,resizable=yes');
}

function muestraHistoria(solicitud,cuil,nombre){	
	var dire = "historiaClinica.php?nrosol="+solicitud+"&cuil="+cuil+"&nombre="+nombre;
	window.open(dire,'Historia Clinica Autorizaciones','width=800, height=500,resizable=yes');
}

function mostrarMotivo(muestra) {
	if (muestra != 1) {
		document.forms.atiendeAutorizacion.motivoRechazo.value="";
		document.forms.atiendeAutorizacion.apeSi.disabled=false;
		document.forms.atiendeAutorizacion.apeNo.disabled=false;
		document.forms.atiendeAutorizacion.prestaSi.disabled=false;
		document.forms.atiendeAutorizacion.prestaNo.disabled=false;
		document.forms.atiendeAutorizacion.emailPresta.disabled=false;
		document.forms.atiendeAutorizacion.selectPatologia.disabled=false;
		document.forms.atiendeAutorizacion.montoCoseguro.disabled=false;
		document.forms.atiendeAutorizacion.monto.disabled=false;
		document.forms.atiendeAutorizacion.porcentaje.disabled=false;
		document.forms.atiendeAutorizacion.montoAutoriza.disabled=false;
		document.forms.atiendeAutorizacion.elige1.disabled=false;
		document.forms.atiendeAutorizacion.elige2.disabled=false;
		document.forms.atiendeAutorizacion.elige3.disabled=false;
		document.forms.atiendeAutorizacion.elige4.disabled=false;
		document.forms.atiendeAutorizacion.elige5.disabled=false;
	} else {
		document.forms.atiendeAutorizacion.motivoRechazo.value="";
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
		document.forms.atiendeAutorizacion.selectPatologia.selectedIndex = 0;
		document.forms.atiendeAutorizacion.selectPatologia.disabled=true;
		document.forms.atiendeAutorizacion.montoCoseguro.value="";
		document.forms.atiendeAutorizacion.montoCoseguro.disabled=true;
		document.forms.atiendeAutorizacion.monto.disabled=true;
		document.forms.atiendeAutorizacion.porcentaje.disabled=true;
		document.forms.atiendeAutorizacion.montoAutoriza.value="";
		document.forms.atiendeAutorizacion.montoAutoriza.disabled=true;
		document.forms.atiendeAutorizacion.elige1.checked=false;
		document.forms.atiendeAutorizacion.elige1.disabled=true;
		document.forms.atiendeAutorizacion.elegido1.disabled=true;
		document.forms.atiendeAutorizacion.elige2.checked=false;
		document.forms.atiendeAutorizacion.elige2.disabled=true;
		document.forms.atiendeAutorizacion.elegido2.disabled=true;
		document.forms.atiendeAutorizacion.elige3.checked=false;
		document.forms.atiendeAutorizacion.elige3.disabled=true;
		document.forms.atiendeAutorizacion.elegido3.disabled=true;
		document.forms.atiendeAutorizacion.elige4.checked=false;
		document.forms.atiendeAutorizacion.elige4.disabled=true;
		document.forms.atiendeAutorizacion.elegido4.disabled=true;
		document.forms.atiendeAutorizacion.elige5.checked=false;
		document.forms.atiendeAutorizacion.elige5.disabled=true;
		document.forms.atiendeAutorizacion.elegido5.disabled=true;
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

function controlaElige(eleccion) {
	if (eleccion == 1) {
		if(document.forms.atiendeAutorizacion.elige1.checked==true)
		{
			document.forms.atiendeAutorizacion.elegido1.value=1;
			document.forms.atiendeAutorizacion.elige2.checked=false;
			document.forms.atiendeAutorizacion.elige2.disabled=true;
			document.forms.atiendeAutorizacion.elegido2.disabled=true;
			document.forms.atiendeAutorizacion.elige3.checked=false;
			document.forms.atiendeAutorizacion.elige3.disabled=true;
			document.forms.atiendeAutorizacion.elegido3.disabled=true;
			document.forms.atiendeAutorizacion.elige4.checked=false;
			document.forms.atiendeAutorizacion.elige4.disabled=true;
			document.forms.atiendeAutorizacion.elegido4.disabled=true;
			document.forms.atiendeAutorizacion.elige5.checked=false;
			document.forms.atiendeAutorizacion.elige5.disabled=true;
			document.forms.atiendeAutorizacion.elegido5.disabled=true;
		}
		else
		{
			document.forms.atiendeAutorizacion.elegido1.value="";
			document.forms.atiendeAutorizacion.elige2.checked=false;
			document.forms.atiendeAutorizacion.elige2.disabled=false;
			document.forms.atiendeAutorizacion.elegido2.disabled=false;
			document.forms.atiendeAutorizacion.elige3.checked=false;
			document.forms.atiendeAutorizacion.elige3.disabled=false;
			document.forms.atiendeAutorizacion.elegido3.disabled=false;
			document.forms.atiendeAutorizacion.elige4.checked=false;
			document.forms.atiendeAutorizacion.elige4.disabled=false;
			document.forms.atiendeAutorizacion.elegido4.disabled=false;
			document.forms.atiendeAutorizacion.elige5.checked=false;
			document.forms.atiendeAutorizacion.elige5.disabled=false;
			document.forms.atiendeAutorizacion.elegido5.disabled=false;
		}
	}

	if (eleccion == 2) {
		if(document.forms.atiendeAutorizacion.elige2.checked==true)
		{
			document.forms.atiendeAutorizacion.elegido2.value=2;
			document.forms.atiendeAutorizacion.elige1.checked=false;
			document.forms.atiendeAutorizacion.elige1.disabled=true;
			document.forms.atiendeAutorizacion.elegido1.disabled=true;
			document.forms.atiendeAutorizacion.elige3.checked=false;
			document.forms.atiendeAutorizacion.elige3.disabled=true;
			document.forms.atiendeAutorizacion.elegido3.disabled=true;
			document.forms.atiendeAutorizacion.elige4.checked=false;
			document.forms.atiendeAutorizacion.elige4.disabled=true;
			document.forms.atiendeAutorizacion.elegido4.disabled=true;
			document.forms.atiendeAutorizacion.elige5.checked=false;
			document.forms.atiendeAutorizacion.elige5.disabled=true;
			document.forms.atiendeAutorizacion.elegido5.disabled=true;
		}
		else
		{
			document.forms.atiendeAutorizacion.elegido2.value="";
			document.forms.atiendeAutorizacion.elige1.checked=false;
			document.forms.atiendeAutorizacion.elige1.disabled=false;
			document.forms.atiendeAutorizacion.elegido1.disabled=false;
			document.forms.atiendeAutorizacion.elige3.checked=false;
			document.forms.atiendeAutorizacion.elige3.disabled=false;
			document.forms.atiendeAutorizacion.elegido3.disabled=false;
			document.forms.atiendeAutorizacion.elige4.checked=false;
			document.forms.atiendeAutorizacion.elige4.disabled=false;
			document.forms.atiendeAutorizacion.elegido4.disabled=false;
			document.forms.atiendeAutorizacion.elige5.checked=false;
			document.forms.atiendeAutorizacion.elige5.disabled=false;
			document.forms.atiendeAutorizacion.elegido5.disabled=false;
		}
	}

	if (eleccion == 3) {
		if(document.forms.atiendeAutorizacion.elige3.checked==true)
		{
			document.forms.atiendeAutorizacion.elegido3.value=3;
			document.forms.atiendeAutorizacion.elige1.checked=false;
			document.forms.atiendeAutorizacion.elige1.disabled=true;
			document.forms.atiendeAutorizacion.elegido1.disabled=true;
			document.forms.atiendeAutorizacion.elige2.checked=false;
			document.forms.atiendeAutorizacion.elige2.disabled=true;
			document.forms.atiendeAutorizacion.elegido2.disabled=true;
			document.forms.atiendeAutorizacion.elige4.checked=false;
			document.forms.atiendeAutorizacion.elige4.disabled=true;
			document.forms.atiendeAutorizacion.elegido4.disabled=true;
			document.forms.atiendeAutorizacion.elige5.checked=false;
			document.forms.atiendeAutorizacion.elige5.disabled=true;
			document.forms.atiendeAutorizacion.elegido5.disabled=true;
		}
		else
		{
			document.forms.atiendeAutorizacion.elegido3.value="";
			document.forms.atiendeAutorizacion.elige1.checked=false;
			document.forms.atiendeAutorizacion.elige1.disabled=false;
			document.forms.atiendeAutorizacion.elegido1.disabled=false;
			document.forms.atiendeAutorizacion.elige2.checked=false;
			document.forms.atiendeAutorizacion.elige2.disabled=false;
			document.forms.atiendeAutorizacion.elegido2.disabled=false;
			document.forms.atiendeAutorizacion.elige4.checked=false;
			document.forms.atiendeAutorizacion.elige4.disabled=false;
			document.forms.atiendeAutorizacion.elegido4.disabled=false;
			document.forms.atiendeAutorizacion.elige5.checked=false;
			document.forms.atiendeAutorizacion.elige5.disabled=false;
			document.forms.atiendeAutorizacion.elegido5.disabled=false;
		}
	}

	if (eleccion == 4) {
		if(document.forms.atiendeAutorizacion.elige4.checked==true)
		{
			document.forms.atiendeAutorizacion.elegido4.value=4;
			document.forms.atiendeAutorizacion.elige1.checked=false;
			document.forms.atiendeAutorizacion.elige1.disabled=true;
			document.forms.atiendeAutorizacion.elegido1.disabled=true;
			document.forms.atiendeAutorizacion.elige2.checked=false;
			document.forms.atiendeAutorizacion.elige2.disabled=true;
			document.forms.atiendeAutorizacion.elegido2.disabled=true;
			document.forms.atiendeAutorizacion.elige3.checked=false;
			document.forms.atiendeAutorizacion.elige3.disabled=true;
			document.forms.atiendeAutorizacion.elegido3.disabled=true;
			document.forms.atiendeAutorizacion.elige5.checked=false;
			document.forms.atiendeAutorizacion.elige5.disabled=true;
			document.forms.atiendeAutorizacion.elegido5.disabled=true;
		}
		else
		{
			document.forms.atiendeAutorizacion.elegido4.value="";
			document.forms.atiendeAutorizacion.elige1.checked=false;
			document.forms.atiendeAutorizacion.elige1.disabled=false;
			document.forms.atiendeAutorizacion.elegido1.disabled=false;
			document.forms.atiendeAutorizacion.elige2.checked=false;
			document.forms.atiendeAutorizacion.elige2.disabled=false;
			document.forms.atiendeAutorizacion.elegido2.disabled=false;
			document.forms.atiendeAutorizacion.elige3.checked=false;
			document.forms.atiendeAutorizacion.elige3.disabled=false;
			document.forms.atiendeAutorizacion.elegido3.disabled=false;
			document.forms.atiendeAutorizacion.elige5.checked=false;
			document.forms.atiendeAutorizacion.elige5.disabled=false;
			document.forms.atiendeAutorizacion.elegido5.disabled=false;
		}
	}

	if (eleccion == 5) {
		if(document.forms.atiendeAutorizacion.elige5.checked==true)
		{
			document.forms.atiendeAutorizacion.elegido5.value=5;
			document.forms.atiendeAutorizacion.elige1.checked=false;
			document.forms.atiendeAutorizacion.elige1.disabled=true;
			document.forms.atiendeAutorizacion.elegido1.disabled=true;
			document.forms.atiendeAutorizacion.elige2.checked=false;
			document.forms.atiendeAutorizacion.elige2.disabled=true;
			document.forms.atiendeAutorizacion.elegido2.disabled=true;
			document.forms.atiendeAutorizacion.elige3.checked=false;
			document.forms.atiendeAutorizacion.elige3.disabled=true;
			document.forms.atiendeAutorizacion.elegido3.disabled=true;
			document.forms.atiendeAutorizacion.elige4.checked=false;
			document.forms.atiendeAutorizacion.elige4.disabled=true;
			document.forms.atiendeAutorizacion.elegido4.disabled=true;
		}
		else
		{
			document.forms.atiendeAutorizacion.elegido5.value="";
			document.forms.atiendeAutorizacion.elige1.checked=false;
			document.forms.atiendeAutorizacion.elige1.disabled=false;
			document.forms.atiendeAutorizacion.elegido1.disabled=false;
			document.forms.atiendeAutorizacion.elige2.checked=false;
			document.forms.atiendeAutorizacion.elige2.disabled=false;
			document.forms.atiendeAutorizacion.elegido2.disabled=false;
			document.forms.atiendeAutorizacion.elige3.checked=false;
			document.forms.atiendeAutorizacion.elige3.disabled=false;
			document.forms.atiendeAutorizacion.elegido3.disabled=false;
			document.forms.atiendeAutorizacion.elige4.checked=false;
			document.forms.atiendeAutorizacion.elige4.disabled=false;
			document.forms.atiendeAutorizacion.elegido4.disabled=false;
		}
	}
}

function validar(formulario) {
	if (formulario.rechazada.checked == true) {
		if(document.getElementById("motivoRechazo").value == "") {
			alert("Debe especificar un Motivo de Rechazo de la Autorizacion");
			document.getElementById("motivoRechazo").focus();
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
				document.getElementById("emailPresta").focus();
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
					document.getElementById("emailPresta").focus();
					return false;
				}
			}
		}

		if(formulario.selectPatologia.options[formulario.selectPatologia.selectedIndex].value == "") {
			alert("Debe seleccionar una patologia");
			document.getElementById("selectPatologia").focus();
			return false;
		}

		if(document.getElementById("montoCoseguro").value == "" || !isNumber(document.getElementById("montoCoseguro").value)) {
			alert("Debe ingresar el Monto de Coseguro y debe ser un número");
			document.getElementById("montoCoseguro").focus();
			return false;
		}

		if(document.getElementById("montoAutoriza").value == "") {
			alert("Debe ingresar el monto autorizado");
			document.getElementById("montoAutoriza").focus();
			return false;
		} else {
			if (!isNumberPositivo(document.getElementById("montoAutoriza").value)) {
				alert("El monto autorizado ingresado es incorrecto");
				document.getElementById("montoAutoriza").focus();
				return false;
			}
			if (formulario.porcentaje.checked == true) {
				if (document.getElementById("montoAutoriza").value < 1 || document.getElementById("montoAutoriza").value > 100) {
					alert("Al seleccionar porcentaje el valor ingresado debe estar entre 1 y 100");
					document.getElementById("montoAutoriza").focus();
					return false;
				}
			}
		}

		var totalelige = 0;

		if(formulario.elige1){
			if(formulario.elige1.disabled==false){
				if(formulario.elige1.checked==true){
					totalelige++;
				}
			}
		}

		if(formulario.elige2){
			if(formulario.elige2.disabled==false){
				if(formulario.elige2.checked==true){
					totalelige++;
				}
			}
		}

		if(formulario.elige3){
			if(formulario.elige3.disabled==false){
				if(formulario.elige3.checked==true){
					totalelige++;
				}
			}
		}

		if(formulario.elige4){
			if(formulario.elige4.disabled==false){
				if(formulario.elige4.checked==true){
					totalelige++;
				}
			}
		}

		if(formulario.elige5){
			if(formulario.elige5.disabled==false){
				if(formulario.elige5.checked==true){
					totalelige++;
				}
			}
		}

		if(formulario.elige1 || formulario.elige2 || formulario.elige3 || formulario.elige4 ||formulario.elige5){
			if(totalelige==0){
				alert("Debe seleccionar un presupuesto");
				return false;
			}

			if(totalelige>1){
				alert("Debe seleccionar solamente un presupuesto");
				return false;
			}
		}
	}

	$.blockUI({ message: "<h1>Guardando Autorizacion. Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'listarSolicitudes.php'"/></p>
	<form id="atiendeAutorizacion" name="atiendeAutorizacion" method="post" action="guardaAutorizacion.php" onsubmit="return validar(this)" enctype="multipart/form-data" >
		<input id="solicitud" name="solicitud" value="<?php echo $nrosolicitud ?>" type="text" size="2" readonly="readonly" style="display: none"/>	
		<input id="delegacion" name="delegacion" value="<?php echo $rowLeeSolicitud['codidelega']." - ".$rowLeeSolicitud['delegacion']?>" type="text" readonly="readonly"  style="display: none"/>	
		<h3>Solicitud Nº <?php echo $nrosolicitud ?></h3>
		<table style="width: 50%; text-align: center" border="1">
	    	<tr>
	          <td><b>Fecha Solicitud</b></td>
	          <td><?php echo invertirFecha($rowLeeSolicitud['fechasolicitud']);?></td>
	        </tr>
	        <tr>
	          <td><b>Delegación</b></td>
	          <td><?php echo "".$rowLeeSolicitud['codidelega']." - ".$rowLeeSolicitud['delegacion'];?></td>
	        </tr>
	   	</table>
		
		<table width="100%" style="text-align: center">
		  	<tr>
		    	<td width="40%" valign="top">
		    		<p style="color: maroon;"><b>Información del Beneficiario</b></p>
		    		<p><b>Nº de Afiliado:</b> <?php echo $nroafiliado ?></p>
		       		<p><b>Clasificacion del Titular: </b> <?php echo $tipoTitular;?></p>
		        	<p><b>Apellido y Nombre: </b><?php echo $nombre ?></p>
		        	<p><b>Comentario: </b><?php echo $rowLeeSolicitud['comentario']?></p>
		        	<?php $color = ""; if ($tipoAfiliado == "NO EMPADRONADO") { $color = 'color="red"'; } ?>
	        		<p><b>Tipo: </b><font <?php echo $color ?>><?php echo $tipoAfiliado; ?></font></p>
		        	<p><b>Fecha Nacimiento:</b> <?php if ($naci != '-') { echo invertirFecha($naci); } else { echo $naci; } ?><strong> | Edad:</strong> <?php echo $edad ?></p>
		        	<p><b>C.U.I.L.:</b> <?php echo $rowLeeSolicitud['cuil'] ?></p>
		        	<p><b>Telefono:</b> <?php echo $rowLeeSolicitud['telefonoafiliado'] ?> </p>
		        	<p><b>Celular:</b> <?php echo $rowLeeSolicitud['movilafiliado'] ?></p>
		       	 	<p><b>Email:</b> <?php echo $rowLeeSolicitud['emailafiliado'] ?></p>		
		       	 	<p style="color: maroon;"><b>Informacion Medica</b></p>
		      <?php	if ($canDisca == 1) {
						$rowDisca = mysql_fetch_assoc($resDisca);  ?>
						<p><b>Disca.: SI </b>(FA: <?php echo $rowDisca['fechaalta'] ?> - FE: <?php echo $rowDisca['emisioncertificado'] ?> - FV: <?php echo $rowDisca['vencimientocertificado'] ?> ) 
						<input name="ver" type="button" id="ver" value="Ver Cert." onclick="verCertificado('../sur/discapacitados/abm/verCertificado.php?nroafiliado=<?php echo $rowDisca['nroafiliado'] ?>&nroorden=<?php echo $rowDisca['nroafiliado'] ?>')"/></p>
			<?php 	}
					if ($canHIV == 1) { ?>
						<p><b>H.I.V.:</b> SI </p>
			<?php 	}
					if ($canOnco == 1) { ?>
						<p><b>Oncológico:</b> SI </p>
			<?php 	}
					if ($canDiabetes == 1) { ?>
						<p><b>Diabético:</b> SI </p>
			<?php 	}
					if ($canPMI == 1) {
						$rowPMI = mysql_fetch_assoc($resPMI); ?>
						<p><b>P.M.I.:</b> SI (FPP: <?php echo $rowPMI['fpp'] ?> - FP: <?php if ($rowPMI['fechanacimiento'] != "00/00/0000") { echo $rowPMI['fechanacimiento']; } else { echo "Sin Dato"; } ?>) </p>
		  	 <?php  }
		  			if ($canDisca == 0 && $canHIV == 0 && $canOnco == 0 && $canPMI == 0 && $canDiabetes == 0) { ?>
		    			<p>Sin Información para mostrar</p>
		     <?php  }?>
		       	 	<p style="color: maroon;"><b>Historia Clinica Autorizaciones</b></p>
					<p><input type="button" value="Ver Historia" name="historia" id="historia" onclick="javascript:muestraHistoria(<?php echo  $rowLeeSolicitud['nrosolicitud'] ?>,<?php echo  $rowLeeSolicitud['cuil'] ?>,'<?php echo  $rowLeeSolicitud['apellidoynombre'] ?>')" /></p>
		      	</td>
		      	<td width="20%" valign="top">
		    		<p style="color: maroon;"><b>Documentación de la Solicitud</b></p>
		    		<p><b>Tipo:</b> <?php if($rowLeeSolicitud['practica']==1) echo "Practica"; else { if($rowLeeSolicitud['material']==1) echo "Material - ".$rowLeeSolicitud['tipomaterial']; else { if($rowLeeSolicitud['medicamento']==1) echo "Medicamento";}} ?></p>
			    	<p><b>Pedido Medico:</b> <?php if($rowLeeSolicitud['pedidomedico']!=NULL) {?><input type="button" name="pedidomedico" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,1)" /><?php }?></p>
			    	<p><b>Historia Clínica:</b> <?php if($rowLeeSolicitud['resumenhc']!=NULL) {?><input type="button" name="historiaclinica" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,2)" /><?php }?></p>
			    	<p><b>Estudios:</b> <?php if($rowLeeSolicitud['avalsolicitud']!=NULL) {?><input type="button" name="estudios" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,3)" /><?php }?></p>
			    	<p><b>Presupuestos:</b></p>
			    	<p><?php if($rowLeeSolicitud['presupuesto1']!=NULL) {?><input type="button" name="presupuesto1" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,4)" /><?php print(" Aprobado: <input type='checkbox' name='elige1' onchange='controlaElige(1)'> <input id='elegido1' name='elegido1' value='' type='text' size='1' readonly='readonly' style='visibility:hidden' />");} ?></p>
			    	<p><?php if($rowLeeSolicitud['presupuesto2']!=NULL) {?><input type="button" name="presupuesto2" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,5)" /><?php print(" Aprobado: <input type='checkbox' name='elige2' onchange='controlaElige(2)'> <input id='elegido2' name='elegido2' value='' type='text' size='1' readonly='readonly' style='visibility:hidden' />");} ?></p>
			    	<p><?php if($rowLeeSolicitud['presupuesto3']!=NULL) {?><input type="button" name="presupuesto3" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,6)" /><?php print(" Aprobado: <input type='checkbox' name='elige3' onchange='controlaElige(3)'> <input id='elegido3' name='elegido3' value='' type='text' size='1' readonly='readonly' style='visibility:hidden' />");} ?></p>
			    	<p><?php if($rowLeeSolicitud['presupuesto4']!=NULL) {?><input type="button" name="presupuesto4" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,7)" /><?php print(" Aprobado: <input type='checkbox' name='elige4' onchange='controlaElige(4)'> <input id='elegido4' name='elegido4' value='' type='text' size='1' readonly='readonly' style='visibility:hidden' />");} ?></p>
			    	<p><?php if($rowLeeSolicitud['presupuesto5']!=NULL) {?><input type="button" name="presupuesto5" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,8)" /><?php print(" Aprobado: <input type='checkbox' name='elige5' onchange='controlaElige(5)'> <input id='elegido5' name='elegido5' value='' type='text' size='1' readonly='readonly' style='visibility:hidden' />");} ?></p>
					
					<p style="color: maroon;"><b>Resultado de la Verificación</b></p>
		    		<p><b>Consulta SSS:</b> <?php if($rowLeeSolicitud['consultasssverificacion']!=NULL) {?><input type="button" name="consultasss" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,9)" /><?php }?></p>
					<p><b>Verificación:</b> <?php if($rowLeeSolicitud['statusverificacion']==1) echo "Aprobada"; else echo "Rechazada";?></p>
		   	  		<p><b>Observacion:</b> <?php echo "".$rowLeeSolicitud['rechazoverificacion'];?></p>
				</td>
		    	<td width="40%" valign="top">
		    		
		   			<p style="color: maroon;"><b>Autorización</b></p>
		   			<p>
		   				<input name="autori" id="aprobada" type="radio" value="1" onchange="mostrarMotivo(0)" checked="checked"/>Aprobada<br />
		      			<input name="autori" id="rechazada" type="radio" value="2" onchange="mostrarMotivo(1)"/>Rechazada
		      		</p>
		      		<p><b>Observacion / Motivo de Rechazo:</b></p>
		      		<p><textarea name="motivoRechazo" cols="60" rows="5" id="motivoRechazo"></textarea></p>
		      		<p><b>Historia Clinica Autorizaciones:</b></p>
		      		<p><textarea name="historiaClinica" cols="60" rows="5" id="historiaClinica"></textarea></p>
		      		<p><b>Expediente SUR :</b>
		        		<input name="ape" id="apeSi" type="radio" value="1"/>Si
		        		<input name="ape" id="apeNo" type="radio" value="0"/>No
		        	</p>
		      		<p><b>Comunica al Prestador ?:</b>
			        	<input name="presta" id="prestaSi" type="radio" value="1" onchange="mostrarEmail(1)"/>Si
		             	<input name="presta" id="prestaNo" type="radio" value="0" onchange="mostrarEmail(0)"/>No<br/>
						<b>Email: </b> <input name="emailPresta" type="text" id="emailPresta" size="50" maxlength="50" disabled="disabled"/>
		     		</p>
		      		<p><b>Clasificacion Patologia:</b>
				  		<select name="selectPatologia" id="selectPatologia">
			        		<option title="Seleccione un valor" value="">Seleccione un valor</option>
						<?php $sqlPatologia="SELECT * FROM patologiasautorizaciones order by descripcion";
							  $resPatologia=mysql_query($sqlPatologia,$db);
							  while($rowPatologia=mysql_fetch_array($resPatologia)) { ?>
								 <option title="<?php echo $rowPatologia[descripcion] ?>" value="<?php echo $rowPatologia[codigo] ?>"><?php echo $rowPatologia['descripcion'] ?></option>
						<?php } ?>
			        	</select>
			        </p>
			  <?php if ($canDisca == 0 && $canHIV == 0 && $canOnco == 0 && $canPMI == 0) { ?>
			        	<p><b>Monto Coseguro: </b><input name="montoCoseguro" type="text" id="montoCoseguro" size="10" maxlength="10" /></p>
			  <?php } else { ?>
			  			<p><b>Monto Coseguro: </b><input value="0.00" style="background-color: silver" name="montoCoseguro" type="text" id="montoCoseguro" size="10" maxlength="10" readonly="readonly"/></p>
			  <?php } ?>
		      		<p><b>Monto Autorizado</b></p>
		      		<p>
		      			<b>Monto: </b><input type="radio" id="monto" name="tipomonto" value="1" checked /> | 
		      			<b>Porcentaje: </b><input type="radio" id="porcentaje" name="tipomonto"  value="2"  /> | 
		      			<b>Valor: </b><input name="montoAutoriza" type="text" id="montoAutoriza" size="10" maxlength="10" />
		      		</p>
		   		</td>
		  	</tr>
		</table>
		<p><input type="submit" name="guardar" id="guardar" value="Guardar"/></p>
	</form>
</div>
</body>
</html>