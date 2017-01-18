<?php 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");  
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Anulacion de Boleta :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
.Estilo1 {	font-size: 18px;
	font-weight: bold;
}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
		$("#nroControl").mask("99999999999999");
});

function validar(formulario) {
	if (formulario.nroControl.value == "") {
		alert("Debe insertar numero de control");
		return false;
	}
	return true;
}
</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" />
  <p><span class="Estilo1">Consulta Movimiento Bancario</span> </p>
</div>
<form id="anulacion" name="anulacion" method="post" onsubmit="return validar(this)" action="movimientoBanco.php">
  <div align="center">
    <table width="371" border="0">
      <tr>
        <td><div align="center">
          <p class="Estilo1"><strong>Codigo de identificacion de boleta</strong></p>
          <p>
            <input name="nroControl" id="nroControl" type="text" size="17" />
            </p>
        </div></td>
      </tr>
      <tr>
        <td>
          <div align="center">
            <input type="submit" name="anular" value="Consultar" />
          </div>
        </td>
      </tr>
    </table>
	<?php 
		if(isset($_POST['nroControl'])) { 
			$nroControl = $_POST['nroControl'];?>
			<p><span class="Estilo1"><strong>Resultado Codigo de identificacion de boleta "<?php echo $nroControl ?>" </strong></span> </p>
			<table border="1" width="1000" style="text-align: center">
				<tr>
					<th>Fecha Recepción </th>
					<th>Fecha Acreditacion </th>
					<th>Tipo Movimiento</th>
					<th>Importe</th>
					<th>C.U.I.T. - Razón Social</th>
					<th>Tipo Pago</th>
					<th>Fecha Validacion</th>
					<th>Fecha Imputacion</th>
				</tr>
	<?php	$sqlBanco = "SELECT b.*, e.nombre as empresa FROM banacuerdosospim b, empresas e WHERE b.nrocontrol = $nroControl and b.cuit = e.cuit ORDER BY b.fecharecaudacion, b.fechaacreditacion";
			$resBanco = mysql_query($sqlBanco,$db); 
			$canBanco = mysql_num_rows($resBanco);
			if ($canBanco != 0) {
				while($rowBanco = mysql_fetch_array($resBanco)) {
					print("<tr>");
					print("<td>".invertirFecha($rowBanco['fecharecaudacion'])."</td>");
					if ($rowBanco['estadomovimiento'] != 'P') {
						print("<td>".invertirFecha($rowBanco['fechaacreditacion'])."</td>");
					} else {
						print("<td>-</td>");
					}	
					print("<td>".$rowBanco['estadomovimiento']."</td>");
					print("<td>".$rowBanco['importe']."</td>");
					print("<td>".$rowBanco['cuit']."<br>".$rowBanco['empresa']."</td>");
					if ($rowBanco['estadomovimiento'] == 'E') {
						print("<td>EFECTIVO</td>");
					} else {
						print("<td>CHEQUE Nº: ".$rowBanco['chequenro']."</td>");
					}	
					if ($rowBanco['estadomovimiento'] == 'P' or $rowBanco['estadomovimiento'] == 'E') {
						print("<td>".$rowBanco['fechavalidacion']."</td>");
					} else {
						print("<td>-</td>");
					}
					if ($rowBanco['estadomovimiento'] != 'P') {
						print("<td>".$rowBanco['fechaimputacion']."</td>");
					} else {
						print("<td>-</td>");
					}
					
					print("</tr>");
				}
			} else {
				print("<tr><td colspan='8' style='color:#FF0000'><b>No Existen movimientos para este código</b></td></tr>");
			} ?>
	</table>
	<p><input type='button' name='imprimir' value='Imprimir' onclick='window.print();'/></p>
    <?php } ?>
  </div>
</form>
<p>&nbsp;</p>
</body>
</html>
