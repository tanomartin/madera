<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 

function desglosar ($dato) {
	$cont = 0;
	$a = "";
	$len_a = strlen($dato);
	$array = array();
	for ($cont = 0; $cont < $len_a; $cont++) {
		if (substr($dato, $cont, 1) != ",") {
			$a .= substr($dato, $cont, 1);
		}else{
			array_push ($array, $a);
			$a = "";
		}
		if ($cont == $len_a) {
			array_push ($array, $a);
		$a = "";
		}
	}
return $array;
}

function array_envia($array) { 
     $tmp = serialize($array); 
     $tmp = urlencode($tmp); 
     return $tmp;
}

$datos = $_POST['elegidos'];
$datosArrayEnvia = array_envia($datos);
$cantidad = sizeof($datos);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fechaCheque").mask("99-99-9999");
	for (var i=0; i<=<?php echo $cantidad ?>; i++) {
		$("#fechaResumen"+i).mask("99-99-9999");
	}
});

function validar(formulario) {
	if (!esEnteroPositivo(formulario.nroCheque.value) || formulario.nroCheque.value == "") {
		alert("Error número de Cheque");
		formulario.nroCheque.focus();
		return false;
	}
	if (!esFechaValida(formulario.fechaCheque.value)){
		alert("La fecha no es valida");
		formulario.fechaCheque.focus();
		return false;
	}

	var finfor = <?php echo $cantidad ?>;
	for (var i=0; i<finfor; i++) {
		idRes = "idResumen" + i;
		idFec = "fechaResumen" + i;
		resumen = document.getElementById(idRes).value;
		fecha = document.getElementById(idFec).value;
		if (resumen == "") {
			alert("Error identificación de resumen");
			document.getElementById(idRes).focus();
			return false;
		}
		if (!esFechaValida(fecha)){
			alert("La fecha no es valida");
			document.getElementById(idFec).focus();
			return false;
		}
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Carga Datos Cheque OSPIM :.</title>
<style type="text/css">
<!--
.Estilo2 {	font-weight: bold;
	font-size: 18px;
}
-->
</style>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'listadoValores.php'" /></p>
  <form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="guardoValorAlCobro.php">
  	<p><input type="hidden" value="<?php echo $datosArrayEnvia  ?>" name="datos" /></p>
  	<p><b>Informaci&oacute;n Cheque OSPIM</b></p>
  	<table width="660">
      <tr>
        <td width="334"><label>N&uacute;mero de Cheque
          <input name="nroCheque" type="text" id="nroCheque" />
        </label></td>
        <td width="316"><label>Fecha del Cheque
          <input name="fechaCheque" type="text" id="fechaCheque" size="8" />
        </label></td>
      </tr>
    </table>
  	<p><strong>Informaci&oacute;n Valores al Cobro </strong></p>
  	<div class="grilla">
  	<table>
      <tr>
        <td><div class="title">CUIT</div></td>
        <td><div class="title">Raz&oacute;n Social</div></td>
        <td><div class="title">Acuerdo</div></td>
        <td><div class="title">Cuota</div></td>
        <td><div class="title">Nro Cheque</div></td>
        <td><div class="title">Banco</div></td>
        <td><div class="title">Fecha Cheque</div></td>
        <td><div class="title">Monto</div></td>
		<td><div class="title">Id. Resumen</div></td>
		<td><div class="title">Fecha Resumen</div></td>
      </tr>
      <?php	
  	$suma = 0;	
	$i = 0;
	foreach ($datos as $array) {
		$info = desglosar($array);
		$cuit = $info[0];
		$nroacu = $info[1];
		$nrocuo = $info[2];
		$sqlCuota = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu and nrocuota = $nrocuo";
		$resCuota = mysql_query( $sqlCuota,$db); 
		$rowCuota = mysql_fetch_array($resCuota); 
		
		$sqlValor= "select * from valoresalcobro where cuit = $cuit and nroacuerdo = $nroacu and nrocuota = $nrocuo";
		$resValor = mysql_query( $sqlValor,$db); 
		$rowValor = mysql_fetch_array($resValor); 

		$sqlRazon = "select * from empresas where cuit = $cuit";
		$resRazon = mysql_query( $sqlRazon,$db); 
		$rowRazon = mysql_fetch_array($resRazon); ?>
		<tr>
			<td><?php echo $cuit ?></td>
			<td><?php echo $rowRazon['nombre'] ?></td>
			<td><?php echo $nroacu ?></td>
			<td><?php echo $nrocuo ?></td>
			<td><?php echo $rowValor['chequenro'] ?></td>
			<td><?php echo $rowValor['chequebanco'] ?></td>
			<td><?php echo invertirFecha($rowValor['chequefecha']) ?></td>
			<td><?php echo number_format($rowCuota['montocuota'],2,',','.') ?></td>
			<td><input name='idResumen<?php echo $i ?>' type='text' id='idResumen<?php echo $i ?>'/></td>
			<td><input name='fechaResumen<?php echo $i ?>' type='text' id='fechaResumen<?php echo $i ?>' size='8'/></td>
		</tr>	
		<?php 
		$suma = $suma + $rowCuota['montocuota'];
		$i = $i + 1;
	} ?>
	<tr>
		<td colspan="6"></td>
		<td><b>TOTAL</b></td>
		<td><b><?php echo number_format($suma,2,',','.') ?></b></td>
		<td colspan="2"></td>
	</tr> 
    </table>
    </div>
    <p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>
