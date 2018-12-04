<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."claves.php");
$maquina = $_SERVER ['SERVER_NAME'];

if(isset($_POST['periodo'])) {
	$periodo = explode('-',$_POST['periodo']);
	$mesPedido = $periodo[0];
	$mesPedido = str_pad($periodo[0],2,'0',STR_PAD_LEFT);
	$anioPedido = $periodo[1];
	$quinPedido = $periodo[2];
}

if(strcmp("localhost",$maquina)==0) {
	$hostOspim = "localhost"; //para las pruebas...
}
$dbInternet =  mysql_connect($hostOspim,$usuarioOspim,$claveOspim );
if (!$dbInternet) {
	die('No pudo conectarse a la base de OSPIM.COM.AR: ' . mysql_error());
}
mysql_select_db($baseOspimPrestadores);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Control Capitas</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>

<script language="javascript">

function validar(formulario) {
	if (formulario.periodo.value == 0){
		alert("Debe Seleccionar un Periodo");
		return false;
	}
	return true;
}

function abrirDetelle(dire) {
	a= window.open(dire,"InfoCapitas",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center"><input class="nover" type="reset" name="volver" value="Volver" onclick="location.href = '../menuTesoreria.php'" /></span></p>
  <h3>Informacion de Subida y Descarga de Padrones - Capitas </h3>
  <h3 class="nover">Seleccione Per&iacute;odo</h3>
  <?php $sqlPeriodos = "SELECT * FROM periodos ORDER BY anopad DESC, mespad DESC, quincena DESC LIMIT 6"; 
  		$resPeriodos = mysql_query($sqlPeriodos,$dbInternet); ?>
  <form name="controlCapitas" action="controlCapitas.php" method="post" onsubmit="return validar(this)">	 
	  <select class="nover" name="periodo" id="periodo">
	  <option selected="selected" value="0"> Seleccione Periodo </option>
		  <?php while($rowPeriodos=mysql_fetch_array($resPeriodos)) {
					$valor1 = $rowPeriodos['mespad']."-".$rowPeriodos['anopad']."-".$rowPeriodos['quincena']; ?>
					<option value="<?php echo $valor1?>"><?php echo $valor1 ?></option>
	   	  <?php } ?>
	  </select>
  <label><input class="nover" type="submit" name="Submit" value="Listar" /></label>
  </form>
  <?php
	if (isset($mesPedido) && isset($anioPedido)) {
		 $sqlPrestador = "select * from usuarios";
		 $resPrestador = mysql_query($sqlPrestador,$dbInternet); ?>
		 <?php $cartel = $quinPedido." º Quincena";
		 		if ($quinPedido == 0)  { $cartel = "MENSUAL"; } ?>
		 <h3>Periodo <?php echo "$mesPedido/$anioPedido - $cartel "?></h3>
		 <div class="grilla">
		 <table style="width: 900px">
			<thead>
				<tr>
				  <th>Prestador</th>
				  <th>Fecha de Subida</th>
				  <th>Primera Bajada</th>
				  <th>Cant. Titulares</th>
				  <th>Cant. Familiares</th>
				  <th>Total de Beneficiarios</th>
				  <th class="nover">Benef. por Deleg. </th>
				</tr>
			</thead>
			<tbody>
	  <?php while($rowPrestador=mysql_fetch_array($resPrestador)) {
				$presta = $rowPrestador['codigo'];
				$sql2 = "select * from subida where codigo = $presta and mespad = $mesPedido and anopad = $anioPedido and quincena = $quinPedido"  ;
				$result2 = mysql_query($sql2,$dbInternet);
				$row2=mysql_fetch_array($result2); 
				if (mysql_num_rows($result2)==0) {
					$subida="NO SUBIDO";
				} else {
					$subida=$row2['fecsub']." (".$row2['horsub'].")";
				}
				
				$sql3 = "select * from descarga where codigo = $presta and mespad = $mesPedido and anopad = $anioPedido and quincena = $quinPedido and estdes='S' order by codigo, anopad, mespad, nrodes LIMIT 1";
				$result3 = mysql_query($sql3,$dbInternet);
				$row3=mysql_fetch_array($result3); 
				if (mysql_num_rows($result3)==0) {
					$descarga="NUNCA";
				} else {
					$descarga=$row3['fecdes']." (".$row3['hordes'].")";
				}?>
				<tr>
					<td><?php echo "$presta - ".$rowPrestador['nombre'] ?></td>
					<td><?php echo $subida ?></td>
					<td><?php echo $descarga ?></td>
					<td><?php echo $row2['tottit'] ?></td>
					<td><?php echo $row2['totfam'] ?></td>
					<td><?php echo $row2['totben'] ?></td>
				
			<?php 	if ($subida=="NO SUBIDO") { ?>
						<td class='nover'><?php echo $subida ?></td>
			<?php	} else {
						$dire = "detalleCapitas.php?presta=$presta&ano=$anioPedido&mes=$mesPedido&quin=$quinPedido";  ?>
						<td><input class="nover" type="button" value="Ver Detalle"  onclick="javascript:abrirDetelle('<?php echo $dire ?>')" /></td>
			<?php	} ?>
				</tr>
	<?php	} ?>
        	</tbody>
		</table>
		</div>
		<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
  <?php	} ?> 
</div>
</body>
</html>