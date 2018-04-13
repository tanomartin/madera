<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nroacu=$_GET['nroacu'];
$cuit=$_GET['cuit'];

$sqlacu = "select c.*, e.descripcion from cabacuerdosospim c, estadosdeacuerdos e where c.cuit = $cuit and c.nroacuerdo = $nroacu and  c.estadoacuerdo = e.codigo";
$resulacu=  mysql_query( $sqlacu,$db); 
$rowacu = mysql_fetch_array($resulacu);

$sqlPeridos = "select * from detacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
$resPeridos =  mysql_query( $sqlPeridos,$db);
$canPeridos = mysql_num_rows($resPeridos);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Modificacion de Acuerdos</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechaAcuerdo").mask("99-99-9999");
	for (var i=0; i<= 120; i++) {
		$("#mes"+i).mask("99");
		$("#anio"+i).mask("9999");
	}
});

function cargarLiqui(requerimiento) {
	var cargado = false;
	<?php 
		$sqlLiqui = "SELECT c.nrorequerimiento, c.liquidacionorigen FROM reqfiscalizospim r , cabliquiospim c where r.cuit = $cuit and r.nrorequerimiento = c.nrorequerimiento;";
		$resLiqui= mysql_query($sqlLiqui,$db); 
		$canLiqui = mysql_num_rows($resLiqui); 
		if ($canLiqui != 0) {
			while ($rowLiqui = mysql_fetch_assoc($resLiqui)) { ?>
				if (requerimiento == <?php echo $rowLiqui['nrorequerimiento'] ?> ) {
					document.getElementById("nombreArcReq").value = "<?php echo $rowLiqui['liquidacionorigen'] ?>";
					cargado = true;
				} 
	 <?php }
		}
	?>
	if (cargado == false) {
		document.getElementById("nombreArcReq").value = "";
	}
}

function validar(formulario) {
	if (!isNumberPositivo(formulario.nroacu.value)) {
		alert("Error en el numero de acuerdo");
		document.getElementById("nroacu").focus();
		return(false);
	}
	if (formulario.tipoAcuerdo.options[formulario.tipoAcuerdo.selectedIndex].value == 0) {
		alert("Error en el tipo de acuerdo");
		document.getElementById("tipoAcuerdo").focus();
		return(false);
	}
	if (!esFechaValida(formulario.fechaAcuerdo.value)) {
		alert("La fecha no es valida");
		document.getElementById("fechaAcuerdo").focus();
		return(false);
	} 
	if (!esEnteroPositivo(formulario.numeroActa.value) || formulario.numeroActa.value == "") {
		alert("Error Número de Acta");
		document.getElementById("numeroActa").focus();
		return(false);
	}
	if (!isNumberPositivo(formulario.monto.value)  || formulario.monto.value == ""){
		alert("Error en el monto");
		document.getElementById("monto").focus();
		return(false);
	}
	
	var totalPeriodos = parseInt(formulario.mostrar.value);
	var errorMes = "Error en la carga del mes";
	var errorAnio = "Error en la carga del año";
	for (var i=0; i<totalPeriodos; i++) {
		nombreMes = "mes" + i;
		nombreAnio = "anio" + i;
		valorMes = document.getElementById(nombreMes).value;
		valorAnio = document.getElementById(nombreAnio).value;
		if (valorMes == 0 && valorAnio != 0) {
			alert(errorMes);
			document.getElementById(nombreMes).focus();
			return (false);
		}
		if (valorMes != 0 && valorAnio == 0) {
			alert(errorAnio);
			document.getElementById(nombreAnio).focus();
			return (false);
		}
		if (valorAnio < 1000 && valorMes != 0) {
			alert(errorAnio);
			document.getElementById(nombreAnio).focus();
			return (false);
		}
	}
	$.blockUI({ message: "<h1>Guardando Modificacion Acuerdo... <br>Esto puede tardar unos segundo.<br> Aguarde por favor</h1>" });
	return true
}

function validoMes(id) {
	var nombreMes = "mes" + id;
	var valorMes = document.getElementById(nombreMes).value;
	var errorMes = "Error en la carga del mes. Mes " + valorMes + " no es posible";
	if (valorMes < 0 || valorMes > 12) {
		alert(errorMes);
		document.getElementById(nombreMes).value = "";
		document.getElementById(nombreMes).focus();
		return false;
	}
	return true;
}

function mostrarPeriodos() {
	if (parseInt(document.forms.modifAcuerdo.mostrar.value) < 120) {	
		var n = parseInt(document.forms.modifAcuerdo.mostrar.value);
		var o = 0;
		var f = 0;
		for (var i=0; i<=12; i++){
			o = parseInt(document.forms.modifAcuerdo.mostrar.value) + i;
			if (o < 120) {
				f = "fila"+ o;
				document.getElementById(f).style.display="table-row";
			}
		}
		document.forms.modifAcuerdo.mostrar.value = n + 12;
	} else { 
		alert("No se pueden superar los 120 períodos");
	}
}


</script>
</head>
<body  bgcolor="#CCCCCC" >
<form id="modifAcuerdo" name="modifAcuerdo" method="post" action="actualizarAcuerdo.php" onsubmit="return validar(this)"  style="visibility:visible">
   	<div align="center">
   		<input name="nrcuit" type="text" id="nrcuit" size="4" readonly="readonly" style="visibility:hidden; position:absolute; z-index:1" value="<?php echo $cuit ?>" />
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'acuerdos.php?cuit=<?php echo $cuit ?>'" /></p>
	 	<?php 	
			include($libPath."cabeceraEmpresaConsulta.php"); 
			include($libPath."cabeceraEmpresa.php"); 
		?>
		<p><b>M&oacute;dulo de Modificación</b></p>
   		<p><b>ACUERDO NUMERO </b><input name="nroacu" type="text" id="nroacu" value="<?php echo $nroacu ?>" size="2" readonly="readonly" style="background-color:silver; text-align: center;" /></p>
   		<p><b>ESTADO "<?php echo $rowacu['descripcion'] ?>"</b></p>

   	  	<table width="1059" border="0">
        	<tr>
          		<td width="118">Tipo</td>
          		<td width="247">
            		<select name="tipoAcuerdo" size="1" id="tipoAcuerdo">
              			<option value='0'>Seleccione un valor </option>
              	 <?php  $query="select * from tiposdeacuerdos";
						$result= mysql_query( $query,$db);
						while ($rowtipos=mysql_fetch_array($result)) { 	
							if ($rowtipos['codigo'] == $rowacu['tipoacuerdo']) {?>			
              					<option value="<?php echo $rowtipos['codigo'] ?>" selected="selected"><?php echo $rowtipos['codigo'].' - '.$rowtipos['descripcion']  ?></option>
                  	  <?php } else { ?>
             				    <option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['codigo'].' - '.$rowtipos['descripcion']  ?></option>
                  	  <?php }
					    } ?>
            		</select>
          		</td>
          		<td>Fecha</td>
          		<td><input id="fechaAcuerdo" type="text" name="fechaAcuerdo" value="<?php echo invertirFecha($rowacu['fechaacuerdo']); ?>" size="10"/></td>
          		<td>Nº de Acta</td>
          		<td><input id="numeroActa" type="text" name="numeroActa" value="<?php echo $rowacu['nroacta'] ?>" /></td>
        	</tr>
        	<tr>
          		<td>Gestor</td>
          		<td>
          			<select name="gestor" id="gestor" >
	              <?php $sqlGestor="select * from gestoresdeacuerdos";
						$resGestor= mysql_query( $sqlGestor,$db);
						while ($rowGestor=mysql_fetch_array($resGestor)) { 
							if ($rowGestor['codigo'] == $rowacu['gestoracuerdo'])  { ?>					
	                 		 	<option value="<?php echo $rowGestor['codigo'] ?>" selected="selected"><?php echo $rowGestor['apeynombre'] ?></option>
					  <?php } else { ?>
					      		<option value="<?php echo $rowGestor['codigo'] ?>"><?php echo $rowGestor['apeynombre'] ?></option>
	               	  <?php } 
						} ?>
            		</select>
         		</td>
          		<td>Inpector</td>
          		<td>
 					<select name="inpector" id="inspector" >
			          <?php if ($rowacu['inspectorinterviene'] == 0) { ?>
							  <option value='0' selected="selected">No Especificado </option>
						<?php } else { ?>
							  <option value='0'>No Especificado </option>
						<?php } 
						$sqlInspec="select codigo, apeynombre from inspectores i, jurisdiccion j where j.cuit = $cuit and j.codidelega = i.codidelega";
						$resInspec= mysql_query( $sqlInspec,$db);
						while ($rowInspec=mysql_fetch_array($resInspec)) { 
							if ($rowacu['inspectorinterviene'] == $rowInspec['codigo']) { ?>
			           			<option value="<?php echo $rowInspec['codigo'] ?>" selected="selected"><?php echo $rowInspec['apeynombre'] ?></option>
					  <?php } else {?>
					  			<option value="<?php echo $rowInspec['codigo'] ?>"><?php echo $rowInspec['apeynombre'] ?></option>
					  <?php }
						} ?>
            		</select>
  				</td>
          		<td>Req. Origen</td>
          		<td>
            		<select name="requerimiento" id="requerimiento" onchange="cargarLiqui(document.forms.modifAcuerdo.requerimiento[selectedIndex].value)">
				       <?php if ($rowacu['requerimientoorigen'] == 0) { ?>
								<option value="0" selected="selected">Seleccione un valor </option>
					   <?php } else { ?>
								  <option value="0">Seleccione un valor </option>
						<?php } 
						$sqlNroReq = "select * from reqfiscalizospim where cuit = $cuit and requerimientoanulado = 0";
						$resNroReq =  mysql_query( $sqlNroReq,$db);
						while ($rowNroReq=mysql_fetch_array($resNroReq)) { 
							if ($rowNroReq['nrorequerimiento'] == $rowacu['requerimientoorigen']) { ?>
				           		<option value="<?php echo $rowNroReq['nrorequerimiento'] ?>" selected="selected"><?php echo $rowNroReq['nrorequerimiento'] ?></option>
					  <?php } else {?>
					  			<option value="<?php echo $rowNroReq['nrorequerimiento'] ?>"><?php echo $rowNroReq['nrorequerimiento'] ?></option>
					  <?php } 
						} ?>
            		</select>
        		</td>
        	</tr>
        	<tr>
          		<td>Liq. Origen</td>
          		<td><input name="nombreArcReq"  value="<?php echo $rowacu['liquidacionorigen']?>"  type="text" id="nombreArcReq" size="40" readonly="readonly" style="background-color: silver;"/></td>
          		<td>Monto</td>
          		<td><input id="monto" type="text" name="monto" value="<?php echo $rowacu['montoacuerdo'] ?>"/></td>
          		<td>Gastos Admin.</td>
          		<td><input name="porcentaje" type="text" id="porcentaje" size="5" value="<?php echo $rowacu['porcengastoadmin']?>" readonly="readonly" style="background-color: silver;"/>%</td>
        	</tr>
        	<tr>
         		<td>Obervaciones</td>
          		<td colspan="5"><textarea name="observaciones" cols="100" rows="5" id="observaciones"><?php echo $rowacu['observaciones'] ?></textarea></td>
        	</tr>
      	</table>
      
    	<p><b>Carga Períodos y Cuotas </b></p>
	    <table width="905" border="0" style="text-align: center; margin-bottom: 15px">
	      <tr>
	        <td><input type="button" name="modifcarCuotas" id="modifcarCuotas" value="Modificar Cuotas" onclick="location.href='modificarCuotas.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $nroacu ?>&cantAgregar=0'"/></td>
	        <td><input name="masPeridos" type="button" id="masPeridos" value="Mas Periodos"  onclick="mostrarPeriodos()"/></td>
	        <td><input type="submit" name="submit" name="submit" value="Guardar Cambios" /></td>
	      </tr>
	    </table>
    	<input  name="mostrar" type="text" id="mostrar" size="4" value="<?php echo $canPeridos?>" readonly="readonly" style="display: none"/>
    	<table style="text-align: center">
	        <tr>
	          <th>Mes</th>
	          <th>A&ntilde;o</th>
	          <th>Concepto de deuda</th>
	        </tr>
			<?php 
				$i = 0;
				if ($canPeridos > 0) {
					while ($rowPeridos=mysql_fetch_array($resPeridos)) { 
						if ($rowPeridos['mesacuerdo'] < 10) {
							$mes = "0".$rowPeridos['mesacuerdo'];
						} else {
							$mes = $rowPeridos['mesacuerdo'];
						} ?>
						<tr id='fila<?php echo $i ?>'>
							<td><input name='mes<?php echo $i ?>' type='text' id='mes<?php echo $i ?>' value='<?php echo $mes ?>' size='2' onblur='validoMes("<?php echo $i ?>")'/></td>
							<td><input name='anio<?php echo $i ?>' type='text' id='anio<?php echo $i ?>' value='<?php echo $rowPeridos['anoacuerdo'] ?>' size='4' /></td>
							<td>
								<select id='conDeuda<?php echo  $i ?>' name='conDeuda<?php echo  $i ?>'>
							<?php 	$sqlConceptos = "SELECT * FROM conceptosdeudas";
									$resConceptos =  mysql_query($sqlConceptos,$db);
									while($rowConceptos=mysql_fetch_array($resConceptos)) {
										if ($rowConceptos['codigo'] == $rowPeridos['conceptodeuda']) {
											$selectOp = 'selected="selected"';
										} else {
											$selectOp = "";
										}?>
										<option value='<?php echo $rowConceptos['codigo'] ?>' <?php echo $selectOp ?>><?php echo $rowConceptos['descripcion'] ?></option>
							<?php	} ?>
								</select> 
							</td>	  
						</tr>
			<?php	$i = $i + 1;
					}
				} 
				while ($i < 120 ) { ?>
					<tr id='fila<?php echo $i ?>' style="display: none">
						<td><input name='mes<?php echo $i ?>' id='mes<?php echo $i ?>' type='text' size='2' onblur='validoMes("<?php echo $i ?>")'/></td>
						<td><input name='anio<?php echo $i ?>' id='anio<?php echo $i ?>' type='text'  size='4' /></td>
						<td>
							<select id='conDeuda<?php echo  $i ?>' name='conDeuda<?php echo  $i ?>'>
						<?php 	$sqlConceptos = "SELECT * FROM conceptosdeudas";
								$resConceptos =  mysql_query($sqlConceptos,$db);
								while($rowConceptos=mysql_fetch_array($resConceptos)) { ?>
									<option value='<?php echo $rowConceptos['codigo'] ?>' <?php echo $selectOp ?>><?php echo $rowConceptos['descripcion'] ?></option>
						<?php	} ?>
							</select> 
						</td>
					</tr>
			<?php	$i = $i + 1;
				}	
			?>	
    	</table>
 	</div>
</form>


</body>
</html>
