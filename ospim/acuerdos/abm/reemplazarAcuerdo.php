<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nroacu=$_GET['nroacu'];
$cuit=$_GET['cuit'];

$sqlCabeViejo = "select * from cabacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
$resCabeViejo =  mysql_query($sqlCabeViejo,$db); 
$rowCabeViejo = mysql_fetch_array($resCabeViejo);
$actaVieja = $rowCabeViejo['nroacta'];

$sql = "select e.*, l.nomlocali, p.descrip as nomprovin from empresas e, localidades l, provincia p where e.cuit = $cuit and e.codlocali = l.codlocali and e.codprovin = p.codprovin";
$result =  mysql_query( $sql,$db); 
$row = mysql_fetch_array($result); 

$sqlCuotas = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu order by fechacuota ASC";
$resCuotas = mysql_query($sqlCuotas,$db);
$canCuotas = mysql_num_rows($resCuotas);

$sqlacu =  "select * from cabacuerdosospim where cuit = $cuit order by nroacuerdo DESC";
$resulacu= mysql_query($sqlacu,$db); 
$rowacu = mysql_fetch_array($resulacu);
$nroacuNuevo = $rowacu['nroacuerdo'] + 1;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
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
			alert("Error N�mero de Acta");
			document.getElementById("numeroActa").focus();
			return(false);
	}
	if (!isNumberPositivo(formulario.monto.value)){
		alert("Error en el monto");
		document.getElementById("monto").focus();
		return(false);
	}
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Reemplazo de Acuerdo :.</title></head>

<body bgcolor="#CCCCCC">
<form id="reemAcuerdo" name="reemAcuerdo" method="post" action="reemplazoAcuerdEfectivo.php?cuit=<?php echo $cuit ?>" onsubmit="return validar(this)">
  <p align="center">
  <input type="reset" name="volver" value="Volver" onclick="location.href = 'acuerdos.php?cuit=<?php echo $cuit ?>'" />
  </p>
  <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/madera/lib/cabeceraEmpresa.php"); 
  ?>
  <p align="center"><strong>M&oacute;dulo de Reemplazo de Acuerdo </strong></p>
  <p align="center"><strong>ACUERDO NUMERO</strong>
      <input name="nroacu" type="text" id="nroacu" value="<?php echo $nroacu ?>" size="2" readonly="readonly"/>
      <strong> REEMPLAZADO POR ACUERDO NUMERO </strong>  
      <input name="nroacunuevo" type="text" id="nroacunuevo" value="<?php echo $nroacuNuevo ?>" size="2" readonly="readonly" />
  </p>
  <div align="center">
    <table width="1023" border="0">
      <tr>
        <td width="119" valign="bottom"><div align="left">Tipo de Acuerdo</div></td>
        <td width="247" valign="bottom"><div align="left">
            <select name="tipoAcuerdo" size="1" id="tipoAcuerdo">
              <option value="0" selected="selected">Seleccione un valor </option>
              <?php 
					$query="select * from tiposdeacuerdos";
					$result=mysql_query($query,$db);
					while ($rowtipos=mysql_fetch_array($result)) { ?>
              <option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['descripcion']  ?></option>
              <?php } ?>
            </select>
        </div></td>
        <td width="163" valign="bottom"><div align="left">Fecha Acuerdo</div></td>
        <td width="154" valign="bottom"><div align="left">
            <input id="fechaAcuerdo" type="text" name="fechaAcuerdo" size="8"/>
        </div></td>
        <td width="160" valign="bottom"><div align="left">N&uacute;mero de Acta</div></td>
        <td colspan="2" valign="bottom"><div align="left">
            <input id="numeroActa" type="text" name="numeroActa"/>
        </div></td>
      </tr>
      <tr>
        <td valign="bottom"><div align="left">Gestor</div></td>
        <td valign="bottom"><div align="left">
            <select name="gestor" id="gestor" >
              <?php 
					$sqlGestor="select * from gestoresdeacuerdos";
					$resGestor=mysql_query($sqlGestor,$db);
					while ($rowGestor=mysql_fetch_array($resGestor)) { ?>
              <option value="<?php echo $rowGestor['codigo'] ?>"><?php echo $rowGestor['apeynombre'] ?></option>
              <?php } ?>
            </select>
        </div></td>
        <td valign="bottom"><div align="left">Inpector</div></td>
        <td valign="bottom"><div align="left">
            <select name="inpector" id="inspector" >
              <option value="0">No Especificado </option>
              <?php 
					$sqlInspec = "select codigo, apeynombre from inspectores i, jurisdiccion j where j.cuit = $cuit and j.codidelega = i.codidelega";
					$resInspec=mysql_query($sqlInspec,$db);
					while ($rowInspec=mysql_fetch_array($resInspec)) { ?>
              <option value="<?php echo $rowInspec['codigo'] ?>"><?php echo $rowInspec['apeynombre'] ?></option>
              <?php } ?>
            </select>
        </div></td>
        <td valign="bottom"><div align="left">Requerimiento de Origen</div></td>
        <td colspan="2" valign="bottom"><div align="left">
            <select name="requerimiento" id="requerimiento" onchange="cargarLiqui(document.forms.reemAcuerdo.requerimiento[selectedIndex].value)">
              <option value="0">Seleccione un valor </option>
              <?php 
				$sqlNroReq = "select * from reqfiscalizospim where cuit = ".$cuit;
				$resNroReq = mysql_query($sqlNroReq,$db);
				while ($rowNroReq=mysql_fetch_array($resNroReq)) { ?>
              <option value="<?php echo $rowNroReq['nrorequerimiento'] ?>"><?php echo $rowNroReq['nrorequerimiento'] ?></option>
              <?php } ?>
            </select>
        </div></td>
      </tr>
      <tr>
        <td valign="bottom" align="left"><label> Liquidacion Origen </label></td>
        <td valign="bottom"><div align="left">
            <input name="nombreArcReq" type="text" id="nombreArcReq" size="40" readonly="readonly" />
        </div></td>
        <td valign="bottom"><div align="left">Monto Acuerdo </div></td>
        <td valign="bottom"><div align="left">
            <input id="monto" type="text" name="monto"/>
        </div></td>
        <td valign="bottom"><div align="left">Gastos Administrativos </div></td>
        <td width="62" valign="bottom" align="left"><label>
              <input name="gasAdmi" type="radio" value="0" checked="checked" onblur="cargarPor()"/>
              NO<br />
              <input name="gasAdmi" type="radio" value="1" onblur="cargarPor()"/>
              SI 
          </label></td>
        <td width="88" valign="bottom"><div align="left">
            <input name="porcentaje" type="text" id="porcentaje" size="5" readonly="readonly"/>
          %</div></td>
      </tr>
      <tr>
        <td valign="bottom"><div align="left">Obervaciones</div></td>
        <td colspan="6" valign="bottom"><div align="left">
            <textarea name="observaciones" cols="100" rows="5" id="observaciones">Reemplaza al Acuerdo <?php echo $nroacu ?> N�mero de Acta <?php echo $actaVieja  ?> </textarea>
        </div></td>
      </tr>
    </table>
  </div>
  <div align="center">
    <p><b>Cuotas</b></p>
    <table width="600" border="1" style="text-align: center;">
      <tr>
        <td width="134"><b>Cuota</b></td>
        <td width="107"><b>Monto</b></td>
        <td width="116"><b>Fecha</b></td>
        <td width="300"><b>Cancelacion</b></td>
      </tr>
        <?php
	$contadorCuotas = 0;
	while ($rowCuotas=mysql_fetch_array($resCuotas)) {
		if ($rowCuotas['montopagada'] == 0 && $rowCuotas['fechapagada'] == '0000-00-00') {
			
			$contadorCuotas = $contadorCuotas + 1;	 
			$query="select * from tiposcancelaciones where codigo = ".$rowCuotas['tipocancelacion'];
			$result=mysql_query($query,$db);
			$rowtipos=mysql_fetch_array($result);
			?>
			<tr>
				<td width='134'><?php echo $contadorCuotas ?></td>
				<td width='107'><?php echo $rowCuotas['montocuota'] ?></td>
				<td width='116'><?php echo invertirFecha($rowCuotas['fechacuota']) ?></td>
				<td width='300'><?php echo $rowtipos['descripcion'] ?></td>
			</tr>
			<tr>
				<td width='134'><b>Obs.</b></td>
				<td colspan='6' align='left'><?php echo $rowCuotas['observaciones'] ?></td>
			</tr>
<?php	} 
	} ?>
        </table>
    <p><b>Per&iacute;odos </b> </p>
    <input  name="mostrar" type="text" id="mostrar" size="4" value="<?php echo $canPeridos?>" readonly="readonly" style="visibility:hidden"/>
    <table style="width: 468; height: 29; text-align: center;" border="1">
      <tr>
        <td width="113"><div align="center"><b>Mes</b></div></td>
        <td width="105"><div align="center"><b>A&ntilde;o</b></div></td>
        <td width="236"><div align="center"><b>Concepto de deuda </b></div></td>
      </tr>
      
        <?php 
				$sqlPeridos = "select * from detacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
				$resPeridos =  mysql_query( $sqlPeridos,$db);
				$canPeridos = mysql_num_rows($resPeridos); 
				$i = 0;
				if ($canPeridos > 0) {
					while ($rowPeridos=mysql_fetch_array($resPeridos)) { 
						if ($rowPeridos['mesacuerdo'] < 10) {
							$mes = "0".$rowPeridos['mesacuerdo'];
						} else {
							$mes = $rowPeridos['mesacuerdo'];
						} ?>
						<tr>
						<td height='11'><?php echo $rowPeridos['mesacuerdo'] ?></td>
						<td height='11'><?php echo $rowPeridos['anoacuerdo'] ?></td>
		<?php			if ($rowPeridos['conceptodeuda'] == "A") { ?>
							<td height='11'>No Pago</td>
		<?php			} else { ?>
							<td height='11'>Fuera de Termino</td>
		<?php			} ?>
						</tr>
		<?php			$i = $i + 1;
					} 
				} else {
					print("No hay periodos");
				}
			?>
    </table>
    <p align="center">
      <input type="submit" name="reemplazar" value="Reemplazar Acuerdo" />
    </p>
  </div>
</form>
</body>
</html>
