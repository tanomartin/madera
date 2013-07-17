<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$datos = array_values($_POST);

$cuit = $datos[0];
//echo "CUIT: ".$cuit; echo "<br>";
$nroacuerdo = $datos[1];
//echo "NRO ACUERDO: ".$nroacuerdo; echo "<br>";
$tipoacuerdo = $datos[2];
//echo "TIPO ACUERDO: ".$tipoacuerdo; echo "<br>";

$fechaacuerdo = $datos[3];
$invert = explode("-",$fechaacuerdo); 
$fechaacuerdo = $invert[2]."-".$invert[1]."-".$invert[0]; 
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
$sqlCargaCabecera = "INSERT INTO cabacuerdosusimra VALUES ('$cuit','$nroacuerdo','$tipoacuerdo','$fechaacuerdo','$nroacta','$gestoracuerdo','$porcGastos','$inspectorinterviene','$requerimientoorigen','$liquidacionorigen','$montoacuerdo','$observaciones','$estadoacuerdo','$cuotasapagar','$montoapagar','$cuotaspagadas','$montopagadas','$fechapagadas','$saldoacuerdo','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";
echo $sqlCargaCabecera; echo("<br>");

//Creo los SQL para los periodos
$id = 1;
$finFor = 16 + ($peridosHabili * 3);
//echo "FIN FOR: ".$finFor;  echo("<br>");
for ($i = 16; $i <= $finFor; $i++) {
	if ($datos[$i] != "" and $datos[$i+1] != "") {
		$mes = $datos[$i];
		$anio = $datos[$i+1];
		$deuda = $datos[$i+2];
		$sql = "INSERT INTO detacuerdosusimra VALUES('$cuit','$nroacuerdo','$id','$mes','$anio','$deuda')"; 
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

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	for (i=0; i<=<?php echo $cuotasapagar ?>; i++) {
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
	if (!esEnteroPositivo(NChe)) {
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

function validoMontos() {
	var monto = 0;
	for (i=1; i<=<?php echo $cuotasapagar ?>; i++) {
		monto = monto + parseFloat(document.getElementById("monto"+i).value);
	}
	monto = Math.round(monto*100)/100;
	if (monto < <?php echo $montoapagar ?>) {
		alert("La suma del monto de las cuotas en inferior al monto a pagar");
		document.getElementById("monto1").focus();
		return false;
	}
	return true;
}

function validarYGuardar(formulario) {
	var nombreMonto, nombreFecha, nombreTipo;
	var monto, fecha, tipoCance;
	for (i=1; i<=<?php echo $cuotasapagar ?>; i++) {
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
	return(validoMontos());
}

</script>

<title>.: Carga Periodos y Cuotas :.</title>
</head>
<body bgcolor="#B2A274" >
<p  align="center"><strong><a href="formularioCarga.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $nroacu?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
<p  align="center"><strong>Cuotas del Acuerdo </strong></p>
<form id="cuotas" name="cuotas" onSubmit="return validarYGuardar(this)" method="POST" action="guardoAcuerdo.php?cuit=<?php echo $cuit?>&nroacu=<?php echo $nroacuerdo?>">

	<input name="sqlCabe" type="text" readonly="readonly" id="sqlCabe" style="visibility:hidden; position:absolute; z-index:inherit" value="<?php echo $sqlCargaCabecera ?>" size="2"> 
	<input name="canPer" type="text" readonly="readonly" id="sqlCabe" style="visibility:hidden; position:absolute; z-index:inherit" value="<?php echo $id-1 ?>" size="2"> 
	<?php if ($id != 1) { 
			for ($i = 0; $i < $id-1; $i++) {?>
			<input name="cantPer<?php echo $i ?>" id="sqlPeri<?php echo $i ?>" type="text" readonly="readonly"  style="visibility:hidden; position:absolute; z-index:inherit " value="<?php echo $listaPeriodos[$i] ?>" size="2">
	<?php 	} 
		}
	?>
	<input name="canCuo" type="text" readonly="readonly" id="sqlCabe" style="visibility:hidden; position:absolute; z-index:inherit" value="<?php echo $cuotasapagar ?>" size="2"> 

  <div align="center"></div>
  <div align="center">
    <table width="800" border="1">
      <tr>
        <td width="134"><div align="center">Cuota </div></td>
        <td width="107"><div align="center">Monto</div></td>
        <td width="116"><div align="center">Fecha</div></td>
        <td width="300"><div align="center">Cancelacion</div></td>
		<td width="200"><div align="center">Nro Cheque </div></td>
		<td width="212"><div align="center">Banco </div></td>
		<td width="212"><div align="center">Fecha Cheque </div></td>
  	 </tr>
  <p>
    <?php
	for ( $i = 1 ; $i <= $cuotasapagar ; $i ++) {
		print ("<td width=134 align='center'><font face=Verdana size=1>".$i."</font></td>");
		print ("<td width=107> <input name='monto".$i."' id='monto".$i."' type='text' size='10'></td>");
		print ("<td width=116> <input name='fecha".$i."' id='fecha".$i."' type='text' size='10'></td>");
		print ("<td width=212>"); ?>
	<select name=<?php print("tipo".$i);?> id=<?php print("tipo".$i);?> onChange="verInfoCheques(document.forms.cuotas.<?php print("tipo".$i."[selectedIndex]");?>.value ,<?php echo $i ?>)">
      <option value=-1>Seleccione un valor </option>
      <?php
					$query="select * from tiposcancelaciones";
					$result=mysql_query($query,$db);
					while ($rowtipos=mysql_fetch_array($result)) { ?>
      <option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['codigo'].' - '.$rowtipos['descripcion']  ?></option>
        <?php } ?>
    </select>
      <?php
	    print("</td>");
		print ("<div align='center' id='infoCheques".$i."'>");
			print ("<td width=212> <input name=ncheque".$i." id=ncheque".$i." type='text' size='12' style='visibility: hidden'> </td>");
			print ("<td width=212> <input name=bcheque".$i." id=bcheque".$i." type='text' size='12'  style='visibility: hidden'> </td>"); 
			print ("<td width=212> <input name=fcheque".$i." id=fcheque".$i." type='text' size='12' style='visibility: hidden'> </td>");
		print ("</div>");
		print ("</tr>");
		print ("<tr>");
		print ("<td width=134 align='center'><font face=Verdana size=1>Obs.</font></td>");
		print ("<td colspan='6'> <textarea name='obs".$i."' id='obs".$i."' cols='93' rows='2' ></textarea> </td>");
		print ("</tr>");
	}
  ?>
    </table>
  </div>
  </p>
  <p align="center"> 
  	<input type="submit" name="guardar" id="guardar" value="Guardar" sub />
	<label></label>
  </p>
</form>
</body>
</html>

