<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 
$datos = array_values($_POST);

$cuit = $datos[0];
//echo "CUIT: ".$cuit; echo "<br>";
$nroacuerdo = $datos[1];
//echo "NRO ACUERDO: ".$nroacuerdo; echo "<br>";
$tipoacuerdo = $datos[2];
//echo "TIPO ACUERDO: ".$tipoacuerdo; echo "<br>";

$fechaacuerdo = $datos[3];
$fechaacuerdo = fechaParaGuardar($fechaacuerdo);
//echo "FECHA: ".$fechaacuerdo; echo "<br>";

$nroacta = $datos[4];
//echo "ACTA: ".$nroacta; echo "<br>";
$gestoracuerdo = $datos[5];
//echo "GESTOR: ".$gestoracuerdo; echo "<br>";
$inspectorinterviene = $datos[6];
//echo "INSPECTOR: ".$inspectorinterviene; echo "<br>";
$requerimientoorigen = $datos[7];
$liquidacionorigen = $datos[8];
//echo "REQUERI: ".$requerimientoorigen; echo "<br>";
//echo "LIQUI: ".$liquidacionorigen; echo "<br>";
$montoacuerdo = $datos[9];
//echo "MONTO: ".$montoacuerdo; echo "<br>";
$gastosAdmi = $datos[10];
//echo "GASTOS ADMI: ".$gastosAdmi; echo "<br>";
$porcGastos = $datos[11];
if ($gastosAdmi == 0) {
 	$porcGastos = 0;
}
//echo "PORC GAST: ".$porcGastos; echo "<br>";
$observaciones = $datos[12];
//echo "OBSER: ".$observaciones; echo "<br>";
$estadoacuerdo = 1;
$cuotasapagar = $datos[13];
//echo "CUTAS A PAGAR: ".$cuotasapagar; echo "<br>";
$montoapagar = $montoacuerdo;
$cuotaspagadas = 0;
$montopagadas = 0;
$fechapagadas = "0000-00-00";
$saldoacuerdo = 0;

$peridosHabili =  $datos[15];
//echo "PERIDOS HABILI: ".$peridosHabili; echo "<br>";

//datos de control de usuario...
$fecharegistro = date("Y-m-d H:m:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

//Creo la sentencia SQL para cabecera.
$sqlCargaCabecera = "INSERT INTO cabacuerdosospim VALUES ('$cuit','$nroacuerdo','$tipoacuerdo','$fechaacuerdo','$nroacta','$gestoracuerdo','$porcGastos','$inspectorinterviene','$requerimientoorigen','$liquidacionorigen','$montoacuerdo','$observaciones','$estadoacuerdo','$cuotasapagar','$montoapagar','$cuotaspagadas','$montopagadas','$fechapagadas','$saldoacuerdo','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";
//echo $sqlCargaCabecera; echo("<br>");

//Creo los SQL para los periodos
$id = 1;
$finFor = 16 + ($peridosHabili * 3);
//echo "FIN FOR: ".$finFor;  echo("<br>");
for ($i = 16; $i <= $finFor; $i++) {
	if ($datos[$i] != "" and $datos[$i+1] != "") {
		$mes = $datos[$i];
		$anio = $datos[$i+1];
		$deuda = $datos[$i+2];
		$sql = "INSERT INTO detacuerdosospim VALUES('$cuit','$nroacuerdo','$id','$mes','$anio','$deuda')"; 
		$listaPeriodos[$id - 1] = $sql;
		$id = $id + 1;
		//echo($sql); echo("<br>");
	} 
	$i=$i+2;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	for (var i=0; i<=<?php echo $cuotasapagar ?>; i++) {
		$("#fecha"+i).mask("99-99-9999");
		$("#fcheque"+i).mask("99-99-9999");
	}
});

function verInfoCheques(tipo, amostrar){
	var nroCheque = "ncheque"+amostrar;
	var banco = "bcheque"+amostrar;
	var fechaCheque = "fcheque"+amostrar;
	if (tipo == 1 || tipo == 3) {
		document.getElementById(nroCheque).style.visibility="visible";
		document.getElementById(banco).style.visibility="visible";
		document.getElementById(fechaCheque).style.visibility="visible";
	} else {
		document.getElementById(nroCheque).style.visibility="hidden";
		document.getElementById(banco).style.visibility="hidden";
		document.getElementById(fechaCheque).style.visibility="hidden";
	}
}

function hayInfoCheque(id) {
	var NChe, FChe, BChe;
	NChe = document.getElementById("ncheque"+id).value;
	BChe = document.getElementById("bcheque"+id).value;
	FChe = document.getElementById("fcheque"+id).value;
	if (!esEnteroPositivo(NChe) || NChe == "") {
		alert("Error número de Cheque");
		document.getElementById("ncheque"+id).focus();
		return false;
	}
	if (BChe == "") {
		alert("Error en el Banco del Cheque");
		document.getElementById("bcheque"+id).focus();
		return false;
	}
	if (!esFechaValida(FChe)) {
		alert("La fecha no es valida");
		document.getElementById("fcheque"+id).focus();
		return false;
	}
	return true;
}

function validoMontos(formulario) {
	var monto = 0;
	for (var i=1; i<=<?php echo $cuotasapagar ?>; i++) {
		monto = monto + parseFloat(document.getElementById("monto"+i).value);
	}
	monto = Math.round(monto*100)/100;
	if (monto < <?php echo $montoapagar ?>) {
		alert("La suma del monto de las cuotas en inferior al monto a pagar");
		document.getElementById("monto1").focus();
		return false;
	}
	$.blockUI({ message: "<h1>Guardando Nuevo Acuerdo... <br>Esto puede tardar unos segundo.<br> Aguarde por favor</h1>" });
	return true
}

function validarYGuardar(formulario) {
	var nombreMonto, nombreFecha, nombreTipo;
	var monto, fecha, tipoCance;
	for (var i=1; i<=<?php echo $cuotasapagar ?>; i++) {
		nombreMonto = "monto"+i;
		monto = document.getElementById(nombreMonto).value;
		nombreFecha = "fecha"+i;
		fecha = document.getElementById(nombreFecha).value;
		nombreTipo = "tipo"+i;
		tipoCance = document.getElementById(nombreTipo).options[document.getElementById(nombreTipo).selectedIndex].value;
		if (!isNumberPositivo(monto)) {
			alert("Error en el Monto");
			document.getElementById(nombreMonto).focus();
			return false;
		}
		if (!esFechaValida(fecha)){
			alert("La fecha no es valida");
			document.getElementById(nombreFecha).focus();
			return false;
		}
		if (tipoCance == -1) {
			alert("Error en el tipo de Cancelacion");
			document.getElementById(nombreTipo).focus();
			return false;
		} else {
			if (tipoCance == 1 || tipoCance == 3) {
				if(!hayInfoCheque(i)){
					return false;
				} 
			}
		}
	}
	return(validoMontos(formulario));
}

</script>

<title>.: Carga Periodos y Cuotas :.</title>
</head>
<body bgcolor="#CCCCCC" >
<div align="center">
	<p><input type="button" name="volver" value="Volver" onClick="location.href = 'nuevoAcuerdo.php?cuit=<?php echo $cuit ?>'" /></p>
	<h3>Cuotas del Acuerdo </h3>
	<form id="cuotas" name="cuotas" onSubmit="return validarYGuardar(this)" method="POST" action="guardoAcuerdo.php?cuit=<?php echo $cuit?>&nroacu=<?php echo $nroacuerdo?>">
		<input name="sqlCabe" type="text" readonly="readonly" id="sqlCabe" style="display: none" value="<?php echo $sqlCargaCabecera ?>" size="2"> 
		<input name="canPer" type="text" readonly="readonly" id="sqlCabe" style="display: none" value="<?php echo $id-1 ?>" size="2"> 
		<?php if ($id != 1) { 
				for ($i = 0; $i < $id-1; $i++) {?>
				<input name="cantPer<?php echo $i ?>" id="sqlPeri<?php echo $i ?>" type="text" readonly="readonly"  style="visibility:hidden; position:absolute; z-index:inherit " value="<?php echo $listaPeriodos[$i] ?>" size="2">
		<?php 	} 
			} ?>
		<input name="canCuo" type="text" readonly="readonly" id="sqlCabe" style="display: none" value="<?php echo $cuotasapagar ?>" size="2"> 
   	 	<table width="800" border="1" style="text-align: center">
      		<tr>
		        <th>Cuota </th>
		        <th>Monto</th>
		        <th>Fecha</th>
		        <th>Cancelacion</th>
				<th>Nro Cheque </th>
				<th>Banco </th>
				<th>Fecha Cheque </th>
  	 		</tr>
    <?php for ( $i = 1 ; $i <= $cuotasapagar ; $i ++) { ?>
			<tr>
				<td><b><?php echo $i ?></b></td>
				<td><input name='monto<?php echo $i ?>' id='monto<?php echo $i ?>' type='text' size='10'></td>
				<td><input name='fecha<?php echo $i ?>' id='fecha<?php echo $i ?>' type='text' size='10'></td>
				<td>
					<select name=<?php echo("tipo".$i);?> id=<?php echo("tipo".$i);?> onChange="verInfoCheques(document.forms.cuotas.<?php print("tipo".$i."[selectedIndex]");?>.value ,<?php echo $i ?>)">
				      <option value=-1>Seleccione un valor </option>
		      	<?php	$query="select * from tiposcancelaciones";
						$result=mysql_query($query,$db);
						while ($rowtipos=mysql_fetch_array($result)) { ?>
		     		  	  <option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['codigo'].' - '.$rowtipos['descripcion']  ?></option>
		          <?php } ?>
		    		</select>
		    	</td>
				<td><input name='ncheque<?php echo $i ?>' id='ncheque<?php echo $i ?>' type='text' size='12' style='visibility: hidden' /> </td>
				<td><input name='bcheque<?php echo $i ?>' id='bcheque<?php echo $i ?>' type='text' size='12'  style='visibility: hidden' /> </td>
				<td><input name='fcheque<?php echo $i ?>' id='fcheque<?php echo $i ?>' type='text' size='12' style='visibility: hidden'/> </td>
			</tr>
			<tr>
				<td><b>Obs.</b></td>
				<td colspan='6'> <textarea name='obs<?php echo $i ?>' id='obs<?php echo $i ?>' cols='117' rows='2' ></textarea> </td>
			</tr>
	<?php } ?>
    	</table>
  		<p><input type="submit" id="Submit" name="Submit" value="Guardar" /></p>
	</form>
</div>
</body>
</html>

