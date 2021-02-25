<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php"); 
$cuit = $_GET["cuit"];
$acuerdo = $_GET["acuerdo"];
$cuota = $_GET["cuota"];	

$sqlCuo = "select c.*, t.descripcion from cuoacuerdosospim c, tiposcancelaciones t where c.cuit = $cuit and c.nroacuerdo = $acuerdo and c.nrocuota = $cuota and c.tipocancelacion = t.codigo";
$resCuo = mysql_query($sqlCuo,$db); 
$rowCuo = mysql_fetch_array($resCuo);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Confirmar Cancelacion :.</title>
</head>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechapagada").mask("99-99-9999");
});

function validar(formulario) {
	var fecha = formulario.fechapagada.value;
	if (!esFechaValida(fecha)) {
		alert("La fecha no es valida");
		formulario.fechapagada.focus = true;
		return false;
	}
	$.blockUI({ message: "<h1>Cancelando cuota... <br>Esto puede tardar unos segundo.<br> Aguarde por favor</h1>" });
	return true
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'selecCanCuotas.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>'" /></p>
	 <?php 	
		include($libPath."cabeceraEmpresaConsulta.php");
		include($libPath."cabeceraEmpresa.php"); 
	  ?>
	<form id="formularioSeleCuotas" name="formularioSeleCuotas" method="post" action="cancelarCuota.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>&cuota=<?php echo $cuota ?>"  onSubmit="return validar(this)">
    	<p><b>Acuerdo Nº <?php echo $acuerdo ?> Cuota Nº<?php echo $cuota ?> </b></p>
	  	<table border="1" width="935" style="text-align: center">
			<tr>
   				<th>Monto</th>
    			<th>Fecha Vto.</th>
    			<th>Tipo Cancelacion</th>
				<th>Nro Cheque</th>
				<th>Banco</th>
				<th>Fecha Cheque</th>
			</tr>
			<tr>
				<td><?php echo $rowCuo['montocuota']?></td>
				<td><?php echo invertirFecha($rowCuo['fechacuota'])?></td>
				<td><?php echo $rowCuo['descripcion']?></td>
		<?php 	if ($rowCuo['chequenro'] == 0) { ?>
					<td>-</td>
					<td>-</td>
					<td>-</td>
	<?php		} else { ?>
					<td><?php echo $rowCuo['chequenro']?></td>
					<td><?php echo $rowCuo['chequebanco']?></td>
					<td><?php echo invertirFecha($rowCuo['chequefecha'])?></td>
	<?php		} ?>
			</tr> 
		</table>
	    <p><b>Fecha de Pago</b> <input name="fechapagada" type="text" id="fechapagada" size="8"></p>
	    <p><b>Observacion</b> <textarea name="textarea" cols="50" rows="4"><?php echo $rowCuo['observaciones'] ?></textarea></p>
	    <p><input type="submit" name="Submit" value="Cancelar Cuota"></p>
	</form>
</div>
</body>
</html>
