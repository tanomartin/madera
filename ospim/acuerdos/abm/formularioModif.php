<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nroacu=$_GET['nroacu'];
$cuit=$_GET['cuit'];

$sql = "select e.*, l.nomlocali, p.descrip as nomprovin from empresas e, localidades l, provincia p where e.cuit = $cuit and e.codlocali = l.codlocali and e.codprovin = p.codprovin";
$result =  mysql_query( $sql,$db); 
$row = mysql_fetch_array($result); 

$sqlacu = "select * from cabacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
$resulacu=  mysql_query( $sqlacu,$db); 
$rowacu = mysql_fetch_array($resulacu);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Modificacion de Acuerdos</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
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
	
}

function mostrarPeriodos() {
	if (parseInt(document.forms.modifAcuerdo.mostrar.value) < 120) 
	{
		var n = parseInt(document.forms.modifAcuerdo.mostrar.value);
		var o = 0;
		var m = 0;
		var a = 0;
		var s = 0;
		for (var i=0; i<=12; i++){
			o = parseInt(document.forms.modifAcuerdo.mostrar.value) + i;
			if (o < 120) {
				m = "mes" + o;
				a = "anio" + o;
				s = "conDeuda" + o;
				document.getElementById(m).style.visibility="visible";
				document.getElementById(a).style.visibility="visible";
				document.getElementById(s).style.visibility="visible";
				document.getElementById(m).style.display="display";
				document.getElementById(a).style.display="display";
				document.getElementById(s).style.display="display";
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
   	<input name="nrcuit" type="text" id="nrcuit" size="4" readonly="readonly" style="visibility:hidden; position:absolute; z-index:1" value="<?php echo $cuit ?>" />
	<p align="center">
	   <input type="reset" name="volver" value="Volver" onclick="location.href = 'acuerdos.php?cuit=<?php echo $cuit ?>'" /></p>
	 <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/madera/lib/cabeceraEmpresa.php"); 
	?>
	<p align="center"><strong>M&oacute;dulo de Modificación</strong></p>
   	<p align="center"><strong>ACUERDO NUMERO</strong> 
   	  <input name="nroacu" type="text" id="nroacu" value="<?php echo $nroacu ?>" size="2" readonly="readonly" style="background-color:silver; text-align: center;" />
  </p>
   	<p align="center"><strong>ESTADO</strong> 
	<?php 
		$sqlEstado = "select * from estadosdeacuerdos where codigo = $rowacu[estadoacuerdo]";
		$resEstado=  mysql_query( $sqlEstado,$db); 
		$rowEstado = mysql_fetch_array($resEstado);
		echo $rowEstado['descripcion'];
	?>
	</p>
   	<div align="center">
   	  <table width="1059" border="0">
        <tr>
          <td width="118" valign="bottom"><div align="left">Tipo de Acuerdo</div></td>
          <td width="247" valign="bottom"><div align="left">
            <select name="tipoAcuerdo" size="1" id="tipoAcuerdo">
              <option value='0'>Seleccione un valor </option>
              <?php 
					$query="select * from tiposdeacuerdos";
					$result= mysql_query( $query,$db);
					while ($rowtipos=mysql_fetch_array($result)) { 	
						if ($rowtipos['codigo'] == $rowacu['tipoacuerdo']) {?>			
              <option value="<?php echo $rowtipos['codigo'] ?>" selected="selected"><?php echo $rowtipos['codigo'].' - '.$rowtipos['descripcion']  ?></option>
                  <?php } else { ?>
              <option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['codigo'].' - '.$rowtipos['descripcion']  ?></option>
                  <?php } ?>
              
              
                <?php } ?>
            </select>
          </div></td>
          <td width="163" valign="bottom"><div align="left">Fecha Acuerdo</div></td>
          <td width="154" valign="bottom"><div align="left">
            <input id="fechaAcuerdo" type="text" name="fechaAcuerdo" value="<?php echo invertirFecha($rowacu['fechaacuerdo']); ?>" />
          </div></td>
          <td width="160" valign="bottom"><div align="left">N&uacute;mero de Acta</div></td>
          <td width="191" valign="bottom"><div align="left">
            <input id="numeroActa" type="text" name="numeroActa" value="<?php echo $rowacu['nroacta'] ?>" />
          </div></td>
        </tr>
        <tr>
          <td valign="bottom"><div align="left">Gestor</div></td>
          <td valign="bottom"><div align="left">
           <select name="gestor" id="gestor" >
                <?php 
					$sqlGestor="select * from gestoresdeacuerdos";
					$resGestor= mysql_query( $sqlGestor,$db);
					while ($rowGestor=mysql_fetch_array($resGestor)) { 
						if ($rowGestor['codigo'] == $rowacu['gestoracuerdo'])  { ?>					
                 		 	<option value="<?php echo $rowGestor['codigo'] ?>" selected="selected"><?php echo $rowGestor['apeynombre'] ?></option>
				  <?php } else { ?>
				      		<option value="<?php echo $rowGestor['codigo'] ?>"><?php echo $rowGestor['apeynombre'] ?></option>
               	  <?php } ?>	
                <?php } ?>
            </select>
          </div></td>
          <td valign="bottom"><div align="left">Inpector</div></td>
          <td valign="bottom"><div align="left">
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
				  <?php } ?>
	          <?php } ?>
            </select>
          </div></td>
          <td valign="bottom"><div align="left">Requerimiento de Origen</div></td>
          <td valign="bottom"><div align="left">
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
			  <?php } ?>
	      <?php } ?>
            </select>
          </div></td>
        </tr>
        <tr>
          <td valign="bottom">
          <div align="left">Liquidacion Origen </div>
          </td>
          <td valign="bottom"><div align="left">
	  	    <input name="nombreArcReq"  value="<?php echo $rowacu['liquidacionorigen']?>"  type="text" id="nombreArcReq" size="40" readonly="readonly" style="background-color: silver;"/>
          </div></td>
          <td valign="bottom"><div align="left">Monto Acuerdo </div></td>
          <td valign="bottom"><div align="left">
            <input id="monto" type="text" name="monto" value="<?php echo $rowacu['montoacuerdo'] ?>"/>
          </div></td>
          <td valign="bottom"><div align="left">Gastos Administrativos </div></td>
          <td valign="bottom"><div align="left">
          <input name="porcentaje" type="text" id="porcentaje" size="5" value="<?php echo $rowacu['porcengastoadmin']?>" readonly="readonly" style="background-color: silver;"/>%</div></td>
        </tr>
        <tr>
          <td valign="bottom"> <div align="left">Obervaciones</div></td>
          <td colspan="5" valign="bottom"><div align="left">
            <textarea name="observaciones" cols="100" rows="5" id="observaciones"><?php echo $rowacu['observaciones'] ?></textarea>
          </div></td>
        </tr>
      </table>
   	</div>
	<div align="center">
    <p><b>Carga Períodos y Cuotas </b></p>
    <table width="905" border="0">
      <tr>
        <td width="399">
          <div align="left">
            <input type="button" name="modifcarCuotas" id="modifcarCuotas" value="Modificar Cuotas" onclick="location.href='modificarCuotas.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $nroacu ?>&cantAgregar=0'"/>
          </div></td>
        <td width="92"><div align="center">
          <input name="masPeridos" type="button" id="masPeridos" value="Mas Periodos"  onclick="mostrarPeriodos()"/>
        </div></td>
        <td width="400">
          <div align="right">
            <input type="submit" name="guardar2" value="Guardar Cambios" />
          </div></td>
      </tr>
    </table>
    <?php 
    	$sqlPeridos = "select * from detacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
		$resPeridos =  mysql_query( $sqlPeridos,$db);
		$canPeridos = mysql_num_rows($resPeridos); 
	?>
    <input  name="mostrar" type="text" id="mostrar" size="4" value="<?php echo $canPeridos?>" readonly="readonly" style="visibility:hidden"/>
    <table style="width: 468; height: 29" border="0">
        <tr>
          <td width="113" height="11"> <div align="center">Mes</div></td>
          <td width="105"><div align="center">A&ntilde;o</div></td>
          <td width="236"><div align="center">Concepto de deuda </div></td>
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
						<tr>
						<td height='11'><div align='center'><input name='mes<?php echo $i ?>' type='text' id='mes<?php echo $i ?>' value='<?php echo $mes ?>' size='2' onblur='validoMes("<?php echo $i ?>")'/></div></td>
						<td height='11'><div align='center'><input name='anio<?php echo $i ?>' type='text' id='anio<?php echo $i ?>' value='<?php echo $rowPeridos['anoacuerdo'] ?>' size='4' onblur='validoAnio("<?php echo $i ?>")' /></div></td>
				<?php	if ($rowPeridos['conceptodeuda'] == "A") {
							$selectedA = "selected='selected'";
							$selectedB = "";
						} 
						if ($rowPeridos['conceptodeuda'] == "B") {
							$selectedA = "";
							$selectedB = "selected='selected'";
						}
						
						 ?>
							<td height='11'><div align='center'>
								  <select id='conDeuda<?php echo $i ?>' name='conDeuda<?php echo $i ?>'>
										<option <?php echo $selectedA ?> value='A'>No Pago</option>
										<option <?php echo $selectedB ?> value='B'>Fuera de Termino</option>
								  </select>
							</div></td>	  
						</tr>
			<?php	$i = $i + 1;
					}
				} else {
					print("No hay periodos");
				}
				
				while ($i < 120 ) { ?>
					<tr>
					<td height='11'><div align='center'><input name='mes<?php echo $i ?>' id='mes<?php echo $i ?>' type='text' size='2' style='visibility:hidden;'  onblur='validoMes("<?php echo $i ?>")'/></div></td>
					<td height='11'><div align='center'><input name='anio<?php echo $i ?>' id='anio<?php echo $i ?>' type='text'  size='4' style='visibility:hidden;' onblur='validoAnio("<?php echo $i ?>")'/></div></td>
					<td height='11'><div align='center'>
						<select id='conDeuda<?php echo $i ?>' name='conDeuda<?php echo $i ?>' style='visibility:hidden;'>
							<option selected="selected" value='A'>No Pago</option>
							<option value='B'>Fuera de Termino</option>
						</select> 
					</div></td>
					</tr>
			<?php	$i = $i + 1;
				}	
			?>
			
    </table>
  </div>
</form>


</body>
</html>
