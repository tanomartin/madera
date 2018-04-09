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

$dia=date("j");
$mes=date("m");
$anio=date("Y");
$inicio=1;
$fin=3;

for ( $i = $inicio ; $i <= $fin ; $i++) {
	$perAux=$mes - $i;
	if ($perAux <= 0) {
		$anioArc[$i]=$anio-1;
		$mesArc[$i]= str_pad($perAux,2,'0',STR_PAD_LEFT);
	}
	else {
		$anioArc[$i]=$anio;
		$mesArc[$i]=str_pad($perAux,2,'0',STR_PAD_LEFT);
	}
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
  
  <form name="controlCapitas" action="controlCapitas.php" method="post" onsubmit="return validar(this)">	 
	  <select class="nover" name="periodo" id="periodo">
	  <option selected="selected" value="0"> Seleccione Periodo </option>
		  <?php for ($i=$inicio;$i<=$fin;$i++){
					$valor1 = $mesArc[$i]."-".$anioArc[$i]."-2";
					$valor2 = $mesArc[$i]."-".$anioArc[$i]."-1"; ?>
					<option value="<?php echo $valor1?>"><?php echo $valor1 ?></option>
					<option value="<?php echo $valor2?>"><?php echo $valor2 ?></option>
	   	  <?php } ?>
	  </select>
  <label><input class="nover" type="submit" name="Submit" value="Listar" /></label>
  </form>
  <?php
	if (isset($mesPedido) && isset($anioPedido)) {
		 $sqlPrestador = "select * from capitados";
		 $resPrestador = mysql_query($sqlPrestador,$db); ?>
		 <h3>Periodo <?php echo "$mesPedido/$anioPedido - $quinPedido º Quincena "?></h3>
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
				$sql2 = "select * from subidapadroncapitados where codigoprestador = $presta and mespadron = $mesPedido and anopadron = $anioPedido and quincenapadron = $quinPedido"  ;
				$result2 = mysql_query($sql2,$db);
				$row2=mysql_fetch_array($result2); 
				if (mysql_num_rows($result2)==0) {
					$subida="NO SUBIDO";
				} else {
					$subida=$row2['fechasubida']." (".$row2['horasubida'].")";
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
					<td><?php echo $row2['totaltitulares'] ?></td>
					<td><?php echo $row2['totalfamiliares'] ?></td>
					<td><?php echo $row2['totalbeneficiarios'] ?></td>
				
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