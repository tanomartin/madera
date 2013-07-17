<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$nroacu=$_GET['nroacu'];
$cuit=$_GET['cuit'];

$sql = "select * from empresas where cuit = $cuit";
$result =  mysql_query( $sql,$db); 
$row = mysql_fetch_array($result); 

$sqlDelEmp = "select * from delegaempresa where cuit = $cuit";
$resDelEmp =  mysql_query( $sqlDelEmp,$db);
$rowDelEmp = mysql_fetch_array($resDelEmp); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad =  mysql_query( $sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi =  mysql_query( $sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

$sqlacu = "select * from cabacuerdosusimra where cuit = $cuit and nroacuerdo = $nroacu";
$resulacu=  mysql_query( $sqlacu,$db); 
$rowacu = mysql_fetch_array($resulacu);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Modificacion de Acuerdos</title>
</head>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechaAcuerdo").mask("99-99-9999");
	for (i=0; i<= 120; i++) {
		$("#mes"+i).mask("99");
		$("#anio"+i).mask("9999");
	}
});

function cargarNombreReq(nroReq) {
	var enc = 0;
	if (nroReq != 0) {
		 <?php
		  	//TODO: ver como resolvermos esto para probar...
			//$dir = "/home/sistemas/Documentos/Liquidaciones/Liquidaciones";
		  	$dir = "H:/Liquidaciones";
			$directorio=opendir($dir); 
			while ($archivo = readdir($directorio)) { 
				$nroRequerimiento = substr($archivo, -12, 8); 
				$ospim = substr($archivo, -13, 1); 
				$numReque = (int)$nroRequerimiento;
		  ?>
				if (nroReq == <?php echo $numReque ?> && "O" == "<?php echo $ospim ?>" ) {
					document.forms.modifAcuerdo.nombreArcReq.value = "<?php echo $archivo ?>";
					enc = 1;
				}
 	 	<?php }
		  closedir($directorio);
		?>
	} else {
		document.forms.modifAcuerdo.nombreArcReq.value = "";
	}
	
	if (enc != 1) {
		document.forms.modifAcuerdo.nombreArcReq.value = "";
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
	
	var totalPeriodos = parseInt(formulario.mostrar.value);
	var errorMes = "Error en la carga del mes";
	var errorAnio = "Error en la carga del año";
	for (i=0; i<totalPeriodos; i++) {
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
		document.forms.modifAcuerdo.mostrar.value = n;
		var o = 0;
		var m = 0;
		var a = 0;
		var s = 0;
		for (i=0; i<=12; i++){
			o = parseInt(document.forms.modifAcuerdo.mostrar.value) + i;
			m = "mes" + o;
			a = "anio" + o;
			s = "conDeuda" + o;
			document.getElementById(m).style.visibility="visible";
			document.getElementById(a).style.visibility="visible";
			document.getElementById(s).style.visibility="visible";
		}
		document.forms.modifAcuerdo.mostrar.value = n + 12;
	} else { 
		alert("No se pueden superar los 120 períodos");
	}
}


</script>

<body  bgcolor="#B2A274" >

<form id="modifAcuerdo" name="modifAcuerdo" method="POST" action="actualizarAcuerdo.php" onSubmit="return validar(this)"  style="visibility:visible">
   	<input name="nrcuit" type="text" id="nrcuit" size="4" readonly="true" style="visibility:hidden; position:absolute; z-index:1" value="<?php echo $cuit ?>" />
	<div align="center">
	<input type="reset" name="volver" value="Volver" onClick="location.href = 'acuerdos.php?cuit=<?php echo $cuit?>'" align="center"/>
	</div>
	 <?php 	
		include($libPath."cabeceraEmpresa.php"); 
	?>
	<p align="center"><strong>M&oacute;dulo de Modificación</strong></p>
   	<p align="center"><strong>ACUERDO NUMERO</strong> 
   	  <input name="nroacu" type="text" id="nroacu" value="<?php echo $nroacu ?>" size="2" readonly="true"  />
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
              <option value=0>Seleccione un valor </option>
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
						  <option value=0 selected="selected">No Especificado </option>
					<?php } else { ?>
						  <option value=0>No Especificado </option>
					<?php } 
					$sqlInspec="select * from inspectores where codidelega = ".$rowDelEmp['codidelega'];
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
            <select name="requerimiento" id="requerimiento" onchange="cargarNombreReq(document.forms.modifAcuerdo.requerimiento[selectedIndex].value)">
		         <?php if ($rowacu['requerimientoorigen'] == 0) { ?>
						<option value=0 selected="selected">Seleccione un valor </option>
			     <?php } else { ?>
						  <option value=0>Seleccione un valor </option>
				<?php } 
				$sqlNroReq = "select * from reqfiscalizusimra where cuit = ".$cuit;
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
          <td valign="bottom"><label>
          <div align="left">Liquidacion Origen </div>
          </label></td>
          <td valign="bottom"><div align="left">
	  	    <input name="nombreArcReq"  value="<?php echo $rowacu['liquidacionorigen']?>"  type="text" id="nombreArcReq" size="40" readonly="readonly" />
          </div></td>
          <td valign="bottom"><div align="left">Monto Acuerdo </div></td>
          <td valign="bottom"><div align="left">
            <input id="monto" type="text" name="monto" value="<?php echo $rowacu['montoacuerdo'] ?>"/>
          </div></td>
          <td valign="bottom"><div align="left">Gastos Administrativos </div></td>
          <td valign="bottom"><div align="left">
          <input name="porcentaje" type="text" id="porcentaje" size="5" value="<?php echo $rowacu['porcengastoadmin']?>" readonly="readonly"/>%</div></td>
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
    <p><b>Carga Períodos y Cuotas </b>      </p>
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
            <input type="submit" name="guardar2" value="Guardar Cambios" sub />
          </div></td>
      </tr>
    </table>
    <table width="468" height="29" border="0">
       
        <tr>
          <td width="113" height="11"> <div align="center">Mes</div></td>
          <td width="105"><div align="center">A&ntilde;o</div></td>
          <td width="236"><div align="center">Concepto de deuda </div></td>
        </tr>
       
	    <tr>
			<?php 
				$sqlPeridos = "select * from detacuerdosusimra where cuit = $cuit and nroacuerdo = $nroacu";
				$resPeridos =  mysql_query( $sqlPeridos,$db);
				$canPeridos = mysql_num_rows($resPeridos); 
			?>
			<input  name="mostrar" type="text" id="mostrar" size="4" value="<?php echo $canPeridos?>" readonly="readonly" style="visibility:hidden"/>
            <?php
			$i = 0;
			
			if ($canPeridos > 0) {
				while ($rowPeridos=mysql_fetch_array($resPeridos)) { 
					if ($rowPeridos['mesacuerdo'] < 10) {
						$mes = "0".$rowPeridos['mesacuerdo'];
					} else {
						$mes = $rowPeridos['mesacuerdo'];
					}
					print("<td height='11'><div align='center'><input name='mes".$i."' type='text' id='mes".$i."' value='".$mes."' size='2' onfocusout='validoMes(".$i.")'/></div></td>");
					print("<td height='11'><div align='center'><input name='anio".$i."' type='text' id='anio".$i."' value='".$rowPeridos['anoacuerdo']."' size='4' onfocusout='validoAnio(".$i.")' /></div></td>");
					
					//TODO: Ver de hacerlo mas prolijo....
					if ($rowPeridos['conceptodeuda'] == "A") {
						print("<td height='11'><div align='center'>
							  <select id='conDeuda".$i."' name='conDeuda".$i."'>
								<option selected value='A'>Período no Pagado</option>
								<option value='B'>Pagado Fuera de Término</option>
								<option value='C'>Aporte y Contribución 3.1%</option>
								<option value='D'>Aporte 1.5%</option>
								<option value='E'>Contribución 1.6%</option>
								<option value='F'>No Remunerativo</option>
								<option value='G'>Contribución 0.6%</option>
								<option value='H'>Aporte y Contribución 2.5%</option>
							  </select> </div></td>");
					} 
					if ($rowPeridos['conceptodeuda'] == "B") {
						print("<td height='11'><div align='center'>
							  <select id='conDeuda".$i."' name='conDeuda".$i."'>
									<option value='A'>Período no Pagado</option>
									<option selected value='B'>Pagado Fuera de Término</option>
									<option value='C'>Aporte y Contribución 3.1%</option>
									<option value='D'>Aporte 1.5%</option>
									<option value='E'>Contribución 1.6%</option>
									<option value='F'>No Remunerativo</option>
									<option value='G'>Contribución 0.6%</option>
									<option value='H'>Aporte y Contribución 2.5%</option>
							  </select> </div></td>");
					}
					if ($rowPeridos['conceptodeuda'] == "C") {
						print("<td height='11'><div align='center'>
							  <select id='conDeuda".$i."' name='conDeuda".$i."'>
									<option value='A'>Período no Pagado</option>
									<option value='B'>Pagado Fuera de Término</option>
									<option selected value='C'>Aporte y Contribución 3.1%</option>
									<option value='D'>Aporte 1.5%</option>
									<option value='E'>Contribución 1.6%</option>
									<option value='F'>No Remunerativo</option>
									<option value='G'>Contribución 0.6%</option>
									<option value='H'>Aporte y Contribución 2.5%</option>
							  </select> </div></td>");
					}
					if ($rowPeridos['conceptodeuda'] == "D") {
						print("<td height='11'><div align='center'>
							  <select id='conDeuda".$i."' name='conDeuda".$i."'>
									<option value='A'>Período no Pagado</option>
									<option value='B'>Pagado Fuera de Término</option>
									<option value='C'>Aporte y Contribución 3.1%</option>
									<option selected value='D'>Aporte 1.5%</option>
									<option value='E'>Contribución 1.6%</option>
									<option value='F'>No Remunerativo</option>
									<option value='G'>Contribución 0.6%</option>
									<option value='H'>Aporte y Contribución 2.5%</option>
							  </select> </div></td>");
					}
					if ($rowPeridos['conceptodeuda'] == "E") {
						print("<td height='11'><div align='center'>
							  <select id='conDeuda".$i."' name='conDeuda".$i."'>
									<option value='A'>Período no Pagado</option>
									<option value='B'>Pagado Fuera de Término</option>
									<option value='C'>Aporte y Contribución 3.1%</option>
									<option value='D'>Aporte 1.5%</option>
									<option selected value='E'>Contribución 1.6%</option>
									<option value='F'>No Remunerativo</option>
									<option value='G'>Contribución 0.6%</option>
									<option value='H'>Aporte y Contribución 2.5%</option>
							  </select> </div></td>");
					}
					if ($rowPeridos['conceptodeuda'] == "F") {
						print("<td height='11'><div align='center'>
							  <select id='conDeuda".$i."' name='conDeuda".$i."'>
									<option value='A'>Período no Pagado</option>
									<option value='B'>Pagado Fuera de Término</option>
									<option value='C'>Aporte y Contribución 3.1%</option>
									<option value='D'>Aporte 1.5%</option>
									<option value='E'>Contribución 1.6%</option>
									<option selected value='F'>No Remunerativo</option>
									<option value='G'>Contribución 0.6%</option>
									<option value='H'>Aporte y Contribución 2.5%</option>
							  </select> </div></td>");
					}
					if ($rowPeridos['conceptodeuda'] == "G") {
						print("<td height='11'><div align='center'>
							  <select id='conDeuda".$i."' name='conDeuda".$i."'>
									<option value='A'>Período no Pagado</option>
									<option value='B'>Pagado Fuera de Término</option>
									<option value='C'>Aporte y Contribución 3.1%</option>
									<option value='D'>Aporte 1.5%</option>
									<option value='E'>Contribución 1.6%</option>
									<option value='F'>No Remunerativo</option>
									<option selected value='G'>Contribución 0.6%</option>
									<option value='H'>Aporte y Contribución 2.5%</option>
							  </select> </div></td>");
					}
					if ($rowPeridos['conceptodeuda'] == "H") {
						print("<td height='11'><div align='center'>
							  <select id='conDeuda".$i."' name='conDeuda".$i."'>
									<option value='A'>Período no Pagado</option>
									<option value='B'>Pagado Fuera de Término</option>
									<option value='C'>Aporte y Contribución 3.1%</option>
									<option value='D'>Aporte 1.5%</option>
									<option value='E'>Contribución 1.6%</option>
									<option value='F'>No Remunerativo</option>
									<option value='G'>Contribución 0.6%</option>
									<option selected value='H'>Aporte y Contribución 2.5%</option>
							  </select> </div></td>");
					}
					//FIN TODO
					print("</tr>");
					$i = $i + 1;
				} 
			} else {
				print("No hay periodos");
			}
			
			while ($i < 120 ) {
				print("<td height='11'><div align='center'><input name='mes".$i."' id='mes".$i."' type='text' size='2' style='visibility:hidden'  onfocusout='validoMes(".$i.")'/></div></td>");
				print("<td height='11'><div align='center'><input name='anio".$i."' id='anio".$i."' type='text'  size='4' style='visibility:hidden' onfocusout='validoAnio(".$i.")'/></div></td>");
				print("<td height='11'><div align='center'>
							<select id='conDeuda".$i."' name='conDeuda".$i."' style='visibility:hidden'>
								<option selected value='A'>Período no Pagado</option>
								<option value='B'>Pagado Fuera de Término</option>
								<option value='C'>Aporte y Contribución</option>
								<option value='D'>Aporte</option>
								<option value='E'>Contribución</option>
								<option value='F'>No Remunerativo</option>
							</select> </div></td>");
				print("</tr>");
				$i = $i + 1;
			}	
			
			?>
    </table>
  
   	<p align="center">&nbsp;</p>
  </div>
</form>


</body>
</html>
