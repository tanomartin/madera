<?php 
$libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Estados Contables :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script language="javascript">
function abrirExcel(dire) {
	a= window.open(dire,"InfoEstado",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input class="nover" type="reset" name="volver" value="Volver" onclick="location.href = '../menuContaduria.php'" /></p>
  <p class="Estilo2">Estados Contables</p>

		 <table width="1053" border="1" align="center" style="text-align: center;">
			<tr>
			  <td><strong>Período</strong></td>
			  <td><strong>Remuneración</strong></td>
			  <td><strong>Obligación</strong></td>
			  <td><strong>Pagos</strong></td>
			  <td><strong>Debito/Credito</strong></td>
			  <td></td>
			</tr>
		<?php 
			$sqlEstadosContables = "SELECT * FROM estadocontablecontrol ORDER BY anio DESC, mes DESC LIMIT 12";
			$resEstadosContables = mysql_query($sqlEstadosContables,$db);
			while($rowEstadoContable = mysql_fetch_array($resEstadosContables)) { ?>
				<tr>
				<td><?php echo $rowEstadoContable['mes']."-".$rowEstadoContable['anio'] ?></td>
				<td><?php echo number_format($rowEstadoContable['remuneracion'],2,',','.') ?></td>
				<td><?php echo number_format($rowEstadoContable['obligacion'],2,',','.')  ?></td>
				<td><?php echo number_format($rowEstadoContable['pagos'],2,',','.')  ?></td>
				<td><?php echo number_format($rowEstadoContable['diferencia'],2,',','.')  ?></td>
				<td>
					<?php 
						$maquina = $_SERVER ['SERVER_NAME'];
						$archivo_name = $rowEstadoContable['patharchivo'];
						$arrayName = explode("/", $archivo_name);
						$archivo_name = array_pop($arrayName);
						$archivo_name = 'archivosHtm/'.$archivo_name;
					?>
					<input type="button" value="Ver Archivo" onclick="javascript:abrirExcel('<?php echo $archivo_name ?>')" />
				</td>
				</tr>
        <?php	} ?>
		</table>
		<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>