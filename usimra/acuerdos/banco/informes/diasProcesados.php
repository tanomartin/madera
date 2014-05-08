<?php 
include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionUsimra.php");  
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 

$diasArray = array("Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","S&aacute;bado");

$sqlPeriodos = "SELECT mes, ano from diasbancousimra GROUP BY ano, mes ORDER BY ano DESC, mes DESC";
$resPeriodos = mysql_query($sqlPeriodos,$db); 


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script type="text/javascript">

function validar(formulario) {
	if (formulario.periodo.value == 0) {
		alert("Debe Seleccionar un Período");
		return false;
	}
	return true;
}

</script>


<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
.Estilo1 {	font-size: 18px;
	font-weight: bold;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta dias procesos :.</title>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloInformes.php'" align="center"/>
  <p><span class="Estilo1">Consulta de d&iacute;as procesados por Per&iacute;odo </span> </p>
</div>
<form id="anulacion" name="anulacion" method="post" onSubmit="return validar(this)" action="diasProcesados.php">
  <div align="center">
    <table width="371" border="0">
      <tr>
        <td><div align="center">
            <label>
            <select name="periodo" id="periodo">
				<option value=0>Seleccione Período</option>
				<?php
					while($rowPeriodos = mysql_fetch_assoc($resPeriodos)) {
						$dato = $rowPeriodos['mes']."-".$rowPeriodos['ano'];
						print("<option value=$dato>$dato </option>");
					}
				?>
            </select>
            </label>
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
		if(isset($_POST['periodo'])) { 
			$periodo = $_POST['periodo'];
			$datossplit = explode('-',$periodo); 
			$ano = $datossplit[1];
			$mes = $datossplit[0];
			?>
			<p><span class="Estilo1">Resultado Per&iacute;odo "<?php echo $periodo ?>" </strong></span> </p>
			<table border="1" width="600">
				  	<th>D&iacute;a </th>
				    <th>Estado </th>
				    <th>Fecha Proceso </th>
					<th>Observación </th>
	  <?php	$sqlDias = "SELECT * FROM diasbancousimra WHERE ano = $ano and mes = $mes ORDER BY dia";
			$resDias = mysql_query($sqlDias,$db); 
			$canDias = mysql_num_rows($resDias);
			
			if ($canDias != 0) {
				while($rowDias = mysql_fetch_array($resDias)) {
					print("<tr>");
					$fecha = $ano."-".$mes."-".$rowDias['dia'];
					$diaSemana = $diasArray[date('N', strtotime($fecha))];
					print("<td>".$diaSemana." ".str_pad($rowDias['dia'],2,'0',STR_PAD_LEFT)."/".str_pad($mes,2,'0',STR_PAD_LEFT)."/".$ano."</td>");
					if ($rowDias['procesado'] == '1') {
						print("<td>Procesado</td>");
					}
					if ($rowDias['exceptuado'] == '1') {
						print("<td>Exceptuado</td>");
					}
					if ($rowDias['procesado'] == '0' && $rowDias['exceptuado'] == '0') {
						print("<td> Sin Procesar </td>");
					}
						
					if ($rowDias['procesado'] == '1' || $rowDias['exceptuado'] == '1') {
						print("<td>".$rowDias['fechamodificacion']."</td>");
					} else {
						print("<td>-</td>");
					}
					if ($rowDias['exceptuado'] == '1') {
						print("<td>".$rowDias['observacion']."</td>");
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
