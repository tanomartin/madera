<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$row = mysql_fetch_array($result); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query($sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query($sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

$sqlJuicio =  "select * from cabjuiciosospim where cuit = $cuit order by nroorden DESC";
$resJuicio = mysql_query($sqlJuicio,$db); 
$canJuicio = mysql_num_rows($resJuicio); 
if ($canJuicio == 0) {
	$nroJuicioNuevo = 1;
} else {
	$rowJuicio = mysql_fetch_array($resJuicio);
	$nroJuicioNuevo = $rowJuicio['nroorden'] + 1;
}

//VEO LOS ACUERDOS QUE PUEDEN SER ABSORIVIDOS
$today = date('Y-m-j');
$fechaVto = strtotime ('-6 month' , strtotime ($today)) ;
$fechaVto = date( 'Y-m-j' , $fechaVto );								
$sqlAcuerdos = "select c.nroacuerdo, t.descripcion, c.nroacta ,o.fechacuota from cabacuerdosospim c, cuoacuerdosospim o, tiposdeacuerdos t where c.cuit = $cuit and c.estadoacuerdo = 1 and c.cuit = o.cuit and c.nroacuerdo = o.nroacuerdo and o.montopagada = 0 and c.tipoacuerdo = t.codigo group by c.nroacuerdo order by c.nroacuerdo ASC, o.fechacuota DESC";
$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
$i=0;
while($rowAcuerdos = mysql_fetch_assoc($resAcuerdos)) {
	$fechacuota = $rowAcuerdos['fechacuota'];
	if ($fechacuota <$fechaVto) { 
		$acuAbs[$i] = array('nroacu' => $rowAcuerdos['nroacuerdo'], 'tipo' => $rowAcuerdos['descripcion'], 'acta' => $rowAcuerdos['nroacta']);
		$i++;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function limpiarAcuerdos() {
	for (i=0; i< <?php echo sizeof($acuAbs) ?>; i++) {
		document.forms.nuevoJuicio.nroacu[i].checked = false;
	}
}

function mostrarAcuerdos() {
	limpiarAcuerdos();
	if (document.forms.nuevoJuicio.acuabs[0].checked) {
		document.getElementById("acuerdos").style.visibility="hidden";
	} else {
		document.getElementById("acuerdos").style.visibility="visible";
	}
}

function mostrarBotones() {
	if (document.forms.nuevoJuicio.tramite[0].checked) {
		document.getElementById("bguardar").disabled=false;
		document.getElementById("btramite").disabled=true;
	} else {
		document.getElementById("bguardar").disabled=true;
		document.getElementById("btramite").disabled=false;
	}
}

function cargoSecretarias(juzgado) {
	document.forms.nuevoJuicio.secretaria.length = 0;
	var o
	document.forms.nuevoJuicio.juzgado.disabled=true;
	o = document.createElement("OPTION");
	o.text = 'Seleccione Secretaria';
	o.value = 0;
	document.forms.nuevoJuicio.secretaria.options.add (o);
	<?php	
		$sqlSecretarias = "select * from secretarias";
		$resSecretarias = mysql_query($sqlSecretarias,$db); 
		while ($rowSecretarias = mysql_fetch_array($resSecretarias)) { ?> 
			if (juzgado == <?php echo $rowSecretarias["codigojuzgado"]; ?>) {
				o = document.createElement("OPTION");
				o.text = '<?php echo $rowSecretarias["denominacion"]; ?>';
				o.value = <?php echo $rowSecretarias["codigosecretaria"]; ?>;
				document.forms.nuevoJuicio.secretaria.options.add(o);
			}
<?php } ?> 
	document.forms.nuevoJuicio.juzgado.disabled=false;
}

function mostrarPeriodos() {
	var n = parseInt(document.forms.nuevoJuicio.mostrar.value);
	if (n < 120) {
		var o = 0;
		var m = 0;
		var a = 0;
		for (i=0; i<=12; i++){
			o = parseInt(document.forms.nuevoJuicio.mostrar.value) + i;
			m = "mes" + o;
			a = "anio" + o;
			document.getElementById(m).style.visibility="visible";
			document.getElementById(a).style.visibility="visible";
		}
		document.forms.nuevoJuicio.mostrar.value = n + 12;
	} else { 
		alert("No se pueden superar los 120 períodos");
	}
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Juicio :.</title>
</head>
<body bgcolor="#CCCCCC" >
<form id="nuevoJuicio" name="nuevoJuicio" method="POST" action="guardarJuicio.php"  onSubmit="return validar(this)" style="visibility:visible" >
  <div align="center">
    <input name="nrcuit" type="text" id="nrcuit" size="4" readonly="true" style="visibility:hidden; position:absolute; z-index:1" value="<?php echo $cuit ?>">
    <input type="reset" name="volver" value="Volver" onClick="location.href = 'juicios.php?cuit=<?php echo $cuit?>'" align="center"/>
    <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresa.php"); 
	?>
  </div>
  <p align="center"><strong>M&oacute;dulo de Carga - Nuevo Juicio </strong></p>
   	<p align="center"><strong>NRO ORDEN </strong>
      <input name="nroorden" type="text" id="nroorden" size="3" readonly="true" value="<?php echo $nroJuicioNuevo ?>" style="background-color:#CCCCCC; text-align:center">
</p>
   	<div align="center">
   	  <table width="1000" border="0" style="text-align:left">
        <tr>
          <td>Nro. Certificado</td>
          <td><input id="nrocert" type="text" name="nrocert"/></td>
          <td>Status Deuda</td>
          <td>
            <label>
            <select name="select">
			  <option value="0" selected>Seleccione Status</option>
              <option value="1">EJECUCION</option>
              <option value="2">CONVOCATORIA</option>
              <option value="3">QUIEBRA</option>
            </select>
          </label>          </td>
          <td width="125">Fecha Expedición</td>
          <td width="242"><input id="fechaexp" type="text" name="fechaexp"/></td>
        </tr>
       
	    <tr>
          <td>Deuda Histórica</td>
		  <td><input id="deudaHistorica" type="text" name="deudaHistorica"/></td>
		  <td>Intereses</td>
          <td><input name="intereses" type="text" id="intereses"/></td>
          <td>Deuda Actualizada</td>
          <td><input id="deudaActual" type="text" name="deudaActual"/></td>
        </tr>
		
        <tr>
      
          <td>Asesor Legal</td>
          <td>
		  	<select name="asesor" id="asesor">
                <option value=0 selected>Seleccione Asesor</option>
				<?php 
					$sqlAsesor ="select * from asesoreslegales";
					$resAsesor = mysql_query($sqlAsesor,$db);
					while ($rowAsesor=mysql_fetch_assoc($resAsesor)) { ?>
                  <option value="<?php echo $rowAsesor['codigo'] ?>"><?php echo $rowAsesor['apeynombre'] ?></option>
                <?php } ?>
            </select>		  </td>
		   <td>Inspector</td>
		 <td>
		 	<select name="inspector" id="inspector">
                <option value=0 selected>Seleccione Inspector</option>
		 <?php 
				$sqlJuris = "select codidelega from jurisdiccion where cuit = $cuit";
				$resJuris = mysql_query($sqlJuris,$db); 
				$sqlInsp = "select * from inspectores where codidelega in (";
				while ($rowJuris = mysql_fetch_assoc($resJuris)) {
					$sqlInsp = $sqlInsp.$rowJuris['codidelega'].",";
				}
				$sqlInsp = substr($sqlInsp,0, -1);
				$sqlInsp = $sqlInsp.")";
				
				$resInspe = mysql_query($sqlInsp,$db);
				while ($rowInspe=mysql_fetch_assoc($resInspe)) { ?>
                  <option value="<?php echo $rowInspe['codigo'] ?>"><?php echo $rowInspe['apeynombre'] ?></option>
	      <?php }?>
		  </select>		  </td>
        </tr>
		 
		   <tr>
		     <td height="70" colspan="3"><div align="center"><strong>PERÍODOS DEL JUICIO</strong></div></td>
			   <td height="70" colspan="3"><div align="center"><strong>TRAMITE JUDICIAL</strong> 
			   [<input name="tramite" type="radio" value="0" checked="checked" onchange="mostrarBotones()"/> NO -
			 <input name="tramite" type="radio" value="1" onchange="mostrarBotones()"/> SI ]</div></td>
		   </tr>
		   <tr>
		<?php if (sizeof($acuAbs) > 0) { ?>
			  <td colspan="3">
			  <div align="center">Acuerdos Absorbidos [
			  	    <label><input name="acuabs" type="radio" value="0" checked="checked" onchange="mostrarAcuerdos()"/>
			  	    NO - </label>
                    <label><input name="acuabs" type="radio" value="1" onchange="mostrarAcuerdos()"/>SI</label>
				]</div>
			  </td>
   	       </tr>
		   <tr>
			  <td colspan="3"><div align="center" id="acuerdos" style="visibility:hidden"> 
			   		<table>
					<?php foreach($acuAbs as $acuerdo) { ?> 
						   <tr>
						     <td><input name="nroacu" type="radio" value="<?php echo $acuerdo['nroacu']?>" /></td>
							 <td align="left"><?php echo $acuerdo['nroacu']." - ".$acuerdo['tipo']. " - Acta: ".$acuerdo['acta']?><td>			
						   </tr>
					<?php } ?>
					</table>
			   </div>
			  </td>
	<?php } else { ?>
			  <td colspan="3"> <div align="center">Acuerdos Absorbidos [
			       <label> <input name="acuabs" type="radio" value="0" checked="checked"/> NO ]</label> 
				   </div></td>
	    </tr>
			  <tr>
			     <td colspan="3"><div align="center"><b>No hay acuerdos posibles</b></div></td>
	<?php } ?>
				<td colspan="3"> 
				<div align="center">
					<input name="bguardar" type="button" id="bguardar" value="Guardar Juicio" /> <input name="btramite" type="button" id="btramite" value="Cargar Tramite" disabled="disabled"/>
					
				</div>
			    </td>
			</tr>
</table>
		  <table width="200" border="0">
<tr>
        		<td colspan="2"><div align="center"><input name="masPeridos" type="button" id="masPeridos" value="Mas Periodos"  onclick="mostrarPeriodos()"/></div></td>
      	  </tr>
		  <tr>
			  <td align="center">Mes</td>
			  <td align="center">A&ntilde;o</td>
		  </tr>
			<input name="mostrar" type="text" id="mostrar" size="4" value="12" readonly="readonly" style="visibility:hidden"/>
				<?php
				for ($i = 0 ; $i <= 120; $i ++) {
					if ($i < 12) {
					print("<tr>");
					print("<td><div align='center'><input name='mes".$i."' type='text' id='mes".$i."' size='2' onfocusout='validoMes(".$i.")'/></div></td>");
					print("<td><div align='center'><input name='anio".$i."' type='text' id='anio".$i."' size='4' onfocusout='validoAnio(".$i.")' /></div></td>");
					print("</tr>");
					 } else {
						print("<td><div align='center'><input name='mes".$i."' id='mes".$i."' type='text' size='2' style='visibility:hidden'  onfocusout='validoMes(".$i.")'/></div></td>");
						print("<td><div align='center'><input name='anio".$i."' id='anio".$i."' type='text'  size='4' style='visibility:hidden' onfocusout='validoAnio(".$i.")'/></div></td>");
						print("</tr>");					 
					 }
				}
				?>
  </table>
   
	  
</form>
</body>
</html>
