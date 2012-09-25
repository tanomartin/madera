<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");
$nroacu=$_GET['nroacu'];
$cuit=$_GET['cuit'];

$sqlCabeViejo = "select * from cabacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
$resCabeViejo =  mysql_query($sqlCabeViejo,$db); 
$rowCabeViejo = mysql_fetch_array($resCabeViejo);
$actaVieja = $rowCabeViejo['nroacta'];

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
<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechaAcuerdo").mask("99-99-9999");
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
					document.forms.reemAcuerdo.nombreArcReq.value = "<?php echo $archivo ?>";
					enc = 1;
				}
 	 	<?php }
		  closedir($directorio);
		?>
	} else {
		document.forms.reemAcuerdo.nombreArcReq.value = "";
	}
	
	if (enc != 1) {
		document.forms.reemAcuerdo.nombreArcReq.value = "";
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
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Reemplazo de Acuerdo :.</title></head>

<body bgcolor="#CCCCCC">
<form id="reemAcuerdo" name="reemAcuerdo" method="post" action="reemplazoAcuerdEfectivo.php?cuit=<?php echo $cuit ?>" onsubmit="return validar(this)">
  <p align="center"><strong><a href="acuerdos.php?cuit=<?php echo $cuit ?>"><font face="Verdana" size="2">VOLVER</font></a></strong></p>
  <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/cabeceraEmpresa.php"); 
  ?>
  <p align="center"><strong>M&oacute;dulo de Reemplazo de Acuerdo </strong></p>
  <p align="center"><strong>ACUERDO NUMERO</strong>
      <input name="nroacu" type="text" id="nroacu" value="<?php echo $nroacu ?>" size="2" readonly="true">
      <strong> REEMPLAZADO POR ACUERDO NUMERO </strong>  
      <input name="nroacunuevo" type="text" id="nroacunuevo" value="<?php echo $nroacuNuevo ?>" size="2" readonly="true" />
  </p>
  <div align="center">
    <table width="1023" border="0">
      <tr>
        <td width="119" valign="bottom"><div align="left">Tipo de Acuerdo</div></td>
        <td width="247" valign="bottom"><div align="left">
            <select name="tipoAcuerdo" size="1" id="tipoAcuerdo">
              <option value=0 selected="selected">Seleccione un valor </option>
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
              <option value=0>No Especificado </option>
              <?php 
					$sqlInspec="select * from inspectores where codidelega = ".$rowDelEmp['codidelega'];
					$resInspec=mysql_query($sqlInspec,$db);
					while ($rowInspec=mysql_fetch_array($resInspec)) { ?>
              <option value="<?php echo $rowInspec['codigo'] ?>"><?php echo $rowInspec['apeynombre'] ?></option>
              <?php } ?>
            </select>
        </div></td>
        <td valign="bottom"><div align="left">Requerimiento de Origen</div></td>
        <td colspan="2" valign="bottom"><div align="left">
            <select name="requerimiento" id="requerimiento" onchange="cargarNombreReq(document.forms.nuevoAcuerdo.requerimiento[selectedIndex].value)">
              <option value=0>Seleccione un valor </option>
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
        <td valign="bottom"><label>
            <div align="left">Liquidacion Origen </div>
          </label></td>
        <td valign="bottom"><div align="left">
            <input name="nombreArcReq" type="text" id="nombreArcReq" size="40" readonly="readonly" />
        </div></td>
        <td valign="bottom"><div align="left">Monto Acuerdo </div></td>
        <td valign="bottom"><div align="left">
            <input id="monto" type="text" name="monto"/>
        </div></td>
        <td valign="bottom"><div align="left">Gastos Administrativos </div></td>
        <td width="62" valign="bottom"><label>
            <div align="left">
              <input name="gasAdmi" type="radio" value="0" checked="checked" onfocusout="cargarPor()"/>
              NO<br />
              <input name="gasAdmi" type="radio" value="1" onfocusout="cargarPor()"/>
              SI </div>
          </label></td>
        <td width="88" valign="bottom"><div align="left">
            <input name="porcentaje" type="text" id="porcentaje" size="5" readonly="readonly"/>
          %</div></td>
      </tr>
      <tr>
        <td valign="bottom"><div align="left">Obervaciones</div></td>
        <td colspan="6" valign="bottom"><div align="left">
            <textarea name="observaciones" cols="100" rows="5" id="observaciones">Reemplaza al Acuerdo <?php echo $nroacu ?> Número de Acta <?php echo $actaVieja  ?> </textarea>
        </div></td>
      </tr>
    </table>
  </div>
  <div align="center">
    <p><b>Cuotas</b></p>
    <table width="600" border="1">
      <tr>
        <td width="134"><div align="center">Cuota </div></td>
        <td width="107"><div align="center">Monto</div></td>
        <td width="116"><div align="center">Fecha</div></td>
        <td width="300"><div align="center">Cancelacion</div></td>
      </tr>
      <p>
        <?php
	$contadorCuotas = 0;
	while ($rowCuotas=mysql_fetch_array($resCuotas)) {
		if ($rowCuotas['montopagada'] == 0 && $rowCuotas['fechapagada'] == '0000-00-00') {
			
			$contadorCuotas = $contadorCuotas + 1;	
			
			print ("<td width=134>". $contadorCuotas."</td>");
			print ("<td width=107>".$rowCuotas['montocuota']."</td>");
			print ("<td width=116>".invertirFecha($rowCuotas['fechacuota'])."</td>");
			
			$query="select * from tiposcancelaciones where codigo = ".$rowCuotas['tipocancelacion'];
			$result=mysql_query($query,$db);
			$rowtipos=mysql_fetch_array($result);
			print ("<td width=300>".$rowtipos['descripcion']."</td>");
	
			print ("<tr>");
			print ("<td width=134 align='center'><font face=Verdana size=1>Obs.</font></td>");
			print ("<td colspan='6' align='left'>".$rowCuotas['observaciones']."</td>");
			print ("</tr>");
		} 
		} ?>
            </p>
        </table>
    <p><b>Per&iacute;odos </b> </p>
    <table width="468" height="29" border="1">
      <tr>
        <td width="113"><div align="center">Mes</div></td>
        <td width="105"><div align="center">A&ntilde;o</div></td>
        <td width="236"><div align="center">Concepto de deuda </div></td>
      </tr>
      <tr>
        <?php 
				$sqlPeridos = "select * from detacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
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
					print("<td height='11'>".$rowPeridos['mesacuerdo']."</td>");
					print("<td height='11'>".$rowPeridos['anoacuerdo']."</td>");
					if ($rowPeridos['conceptodeuda'] == "A") {
						print("<td height='11'>No Pago</td>");
					} else {
						print("<td height='11'>Fuera de Termino</td>");
					}
					print("</tr>");
					$i = $i + 1;
				} 
			} else {
				print("No hay periodos");
			}
			?>
      </tr>
    </table>
    <p align="center">
      <input type="submit" name="reemplazar" value="Reemplazar Acuerdo" sub="sub" />
    </p>
  </div>
</form>
</body>
</html>
