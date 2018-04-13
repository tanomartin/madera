<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nroacu=$_GET['nroacu'];
$cuit=$_GET['cuit'];

$sqlCabeViejo = "select * from cabacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
$resCabeViejo =  mysql_query($sqlCabeViejo,$db); 
$rowCabeViejo = mysql_fetch_array($resCabeViejo);
$actaVieja = $rowCabeViejo['nroacta'];

$sqlCuotas = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu order by fechacuota ASC";
$resCuotas = mysql_query($sqlCuotas,$db);
$canCuotas = mysql_num_rows($resCuotas);

$sqlacu =  "select * from cabacuerdosospim where cuit = $cuit order by nroacuerdo DESC";
$resulacu= mysql_query($sqlacu,$db); 
$rowacu = mysql_fetch_array($resulacu);
$nroacuNuevo = $rowacu['nroacuerdo'] + 1;

$sqlPeriodos = "select * from detacuerdosospim d, conceptosdeudas c 
				WHERE d.cuit = $cuit and d.nroacuerdo = $nroacu and d.conceptodeuda = c.codigo";
$resPeriodos =  mysql_query( $sqlPeriodos,$db);
$canPeriodos = mysql_num_rows($resPeriodos);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechaAcuerdo").mask("99-99-9999");
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


function cargarPor(){
	<?php 
		$sqlPor = "select * from parametros where id = 1";
		$resPor= mysql_query($sqlPor,$db); 
		$rowPor = mysql_fetch_array($resPor);
	?>
	if (document.forms.reemAcuerdo.gasAdmi[1].checked) {
		document.forms.reemAcuerdo.porcentaje.value = "<?php echo $rowPor['valorgastoadmin']?>";
	} else {
		document.forms.reemAcuerdo.porcentaje.value ="";
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
	if (!esEnteroPositivo(formulario.numeroActa.value)) {
			alert("Error Número de Acta");
			document.getElementById("numeroActa").focus();
			return(false);
	}
	if (!isNumberPositivo(formulario.monto.value)){
		alert("Error en el monto");
		document.getElementById("monto").focus();
		return(false);
	}
	$.blockUI({ message: "<h1>Reemplazando Acuerdo... <br>Esto puede tardar unos segundo.<br> Aguarde por favor</h1>" });
	return true
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Reemplazo de Acuerdo :.</title></head>

<body bgcolor="#CCCCCC">
<div align="center">
	<form id="reemAcuerdo" name="reemAcuerdo" method="post" action="reemplazoAcuerdoEfectivo.php?cuit=<?php echo $cuit ?>" onsubmit="return validar(this)">
  		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'acuerdos.php?cuit=<?php echo $cuit ?>'" /></p>
	  	<?php 	
			include($libPath."cabeceraEmpresaConsulta.php"); 
			include($libPath."cabeceraEmpresa.php");
	  	?>
  		<p><b>Módulo de Reemplazo de Acuerdo </b></p>
 		<p>
 			<b>ACUERDO NUMERO</b>
      		<input name="nroacu" type="text" id="nroacu" value="<?php echo $nroacu ?>" size="2" readonly="readonly" style="background-color: silver; text-align: center"/>
      		<b> REEMPLAZADO POR ACUERDO NUMERO </b>  
      		<input name="nroacunuevo" type="text" id="nroacunuevo" value="<?php echo $nroacuNuevo ?>" size="2" readonly="readonly" style="background-color: silver; text-align: center"/>
  		</p>

    	<table width="1023">
      		<tr>
        		<td><div align="left">Tipo</div></td>
        		<td>
        			<div align="left">
            			<select name="tipoAcuerdo" size="1" id="tipoAcuerdo">
              				<option value="0" selected="selected">Seleccione un valor </option>
			              <?php $query="select * from tiposdeacuerdos";
								$result=mysql_query($query,$db);
								while ($rowtipos=mysql_fetch_array($result)) { ?>
			              <option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['descripcion']  ?></option>
			              <?php } ?>
			            </select>
        			</div>
        		</td>
        		<td><div align="left">Fecha</div></td>
        		<td><div align="left"><input id="fechaAcuerdo" type="text" name="fechaAcuerdo" size="8"/> </div></td>
        		<td><div align="left">Nº de Acta</div></td>
        		<td colspan="2"><div align="left"><input id="numeroActa" type="text" name="numeroActa"/></div></td>
      		</tr>
      		<tr>
        		<td><div align="left">Gestor</div></td>
        		<td>
        			<div align="left">
		            	<select name="gestor" id="gestor" >
		              <?php $sqlGestor="select * from gestoresdeacuerdos";
							$resGestor=mysql_query($sqlGestor,$db);
							while ($rowGestor=mysql_fetch_array($resGestor)) { ?>
		             			<option value="<?php echo $rowGestor['codigo'] ?>"><?php echo $rowGestor['apeynombre'] ?></option>
		              <?php } ?>
		            	</select>
        			</div>
        		</td>
        		<td><div align="left">Inpector</div></td>
        		<td>
        			<div align="left">
	            		<select name="inpector" id="inspector" >
			              <option value="0">No Especificado </option>
			              <?php $sqlInspec = "select codigo, apeynombre from inspectores i, jurisdiccion j where j.cuit = $cuit and j.codidelega = i.codidelega";
								$resInspec=mysql_query($sqlInspec,$db);
								while ($rowInspec=mysql_fetch_array($resInspec)) { ?>
			              <option value="<?php echo $rowInspec['codigo'] ?>"><?php echo $rowInspec['apeynombre'] ?></option>
			              <?php } ?>
			            </select>
        			</div>
        		</td>
        		<td><div align="left">Req. Origen</div></td>
        		<td colspan="2">
        			<div align="left">
            			<select name="requerimiento" id="requerimiento" onchange="cargarLiqui(document.forms.reemAcuerdo.requerimiento[selectedIndex].value)">
			              <option value="0">Seleccione un valor </option>
			              <?php 
							$sqlNroReq = "select * from reqfiscalizospim where cuit = ".$cuit;
							$resNroReq = mysql_query($sqlNroReq,$db);
							while ($rowNroReq=mysql_fetch_array($resNroReq)) { ?>
			              <option value="<?php echo $rowNroReq['nrorequerimiento'] ?>"><?php echo $rowNroReq['nrorequerimiento'] ?></option>
			              <?php } ?>
			            </select>
        			</div>
        		</td>
      		</tr>
      		<tr>
        		<td align="left">Liq. Origen</td>
        		<td><div align="left"><input name="nombreArcReq" type="text" id="nombreArcReq" size="40" readonly="readonly" style="background-color: silver" /></div></td>
        		<td><div align="left">Monto</div></td>
        		<td><div align="left"><input id="monto" type="text" name="monto"/></div></td>
        		<td><div align="left">Gastos Admin.</div></td>
        		<td align="left">
              		<input name="gasAdmi" type="radio" value="0" checked="checked" onclick="cargarPor()"/> NO<br />
              		<input name="gasAdmi" type="radio" value="1" onclick="cargarPor()" /> SI 
         		</td>
        		<td><div align="left"><input name="porcentaje" type="text" id="porcentaje" size="3" readonly="readonly" style="background-color: silver"/> %</div></td>
      		</tr>
      		<tr>
        		<td><div align="left">Obervaciones</div></td>
        		<td colspan="6">
        			<div align="left">
            			<textarea name="observaciones" cols="125" rows="5" id="observaciones">Reemplaza al Acuerdo <?php echo $nroacu ?> Número de Acta <?php echo $actaVieja  ?> </textarea>
        			</div>
        		</td>
      		</tr>
    	</table>

    	<p><b>Cuotas</b></p>
    	<table width="600" border="1" style="text-align: center;">
	    	<tr>
	        	<th>Cuota</th>
		        <th>Monto</th>
		        <th>Fecha</th>
		        <th>Cancelacion</th>
	      	</tr>
     <?php 	$contadorCuotas = 0;
			while ($rowCuotas=mysql_fetch_array($resCuotas)) {
				if ($rowCuotas['montopagada'] == 0 && $rowCuotas['fechapagada'] == '0000-00-00') {
					$contadorCuotas = $contadorCuotas + 1;	 
					$query="select * from tiposcancelaciones where codigo = ".$rowCuotas['tipocancelacion'];
					$result=mysql_query($query,$db);
					$rowtipos=mysql_fetch_array($result); ?>
					<tr>
						<td><?php echo $contadorCuotas ?></td>
						<td><?php echo $rowCuotas['montocuota'] ?></td>
						<td><?php echo invertirFecha($rowCuotas['fechacuota']) ?></td>
						<td><?php echo $rowtipos['descripcion'] ?></td>
					</tr>
					<tr>
						<td width='134'><b>Obs.</b></td>
						<td colspan='6' align='left'><?php echo $rowCuotas['observaciones'] ?></td>
					</tr>
		<?php	} 
			} ?>
        </table>
   		
   		<p><b>Per&iacute;odos </b> </p>
   		<input  name="mostrar" type="text" id="mostrar" size="4" value="<?php echo $canPeridos?>" readonly="readonly" style="display: none"/>
<?php 	if ($canPeriodos > 0) { ?>
	  		<table width="300" border="1" style="text-align: center">
				<tr>
			        <th>Mes</th>
			        <th>Año</th>
			        <th>Concepto de deuda </th>
			    </tr>
	<?php 	while ($rowPeriodos = mysql_fetch_array($resPeriodos)) { ?>
				<tr>
					<td><?php echo $rowPeriodos['mesacuerdo'] ?></td>
					<td><?php echo $rowPeriodos['anoacuerdo'] ?></td>
					<td><?php echo $rowPeriodos['descripcion'] ?></td>
				</tr>
	  <?php	}  ?>
	    </table>
<?php	} else { ?>
			<p style="color: blue">No hay periodos cargados en este acuerdo</p>		
<?php	}	?>	
	    <p><input type="submit" name="reemplazar" value="Reemplazar Acuerdo" /></p>
	</form>
</div>
</body>
</html>
