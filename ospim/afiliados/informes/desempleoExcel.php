<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
set_time_limit(0);

$periodo = explode("-",$_GET['periodo']);
$sqlDesempleo = "SELECT d.cuilbeneficiario, d.apellidoynombre, 
							d.fechacobro, d.anofinrelacion, 
							d.mesfinrelacion, d.fechainformesss,
							titulares.nroafiliado as nrotitu, 
							titulares.situaciontitularidad as sitututi, 
							titulares.codidelega as deletitu, 
							titularesdebaja.nroafiliado as nrobaja, 
							titularesdebaja.situaciontitularidad as situbaja, 
							titularesdebaja.codidelega as delebaja
					FROM desempleosss d
					LEFT JOIN titulares on d.cuilbeneficiario = titulares.cuil
					LEFT JOIN titularesdebaja on d.cuilbeneficiario = titularesdebaja.cuil
					WHERE anodesempleo = ".$periodo[1]." and mesdesempleo = ".$periodo[0]." and parentesco = 0";	
$resDesempleo = mysql_query($sqlDesempleo,$db);
$canDesempleoTitu = mysql_num_rows($resDesempleo);
	
$sqlDesempleoFami = "SELECT d.cuilbeneficiario, d.apellidoynombre, 
								d.fechacobro, d.anofinrelacion,
								d.mesfinrelacion, d.fechainformesss, 
								d.cuiltitular,
								familiares.nroafiliado as nrotitu, 
								familiaresdebaja.nroafiliado as nrobaja,
								familiares.nroorden as ordentitu, 
								familiaresdebaja.nroorden as ordenbaja
						FROM desempleosss d
						LEFT JOIN familiares on d.cuilbeneficiario = familiares.cuil
						LEFT JOIN familiaresdebaja on d.cuilbeneficiario = familiaresdebaja.cuil
						WHERE anodesempleo = ".$periodo[1]." and mesdesempleo = ".$periodo[0]." and parentesco != 0";
$resDesempleoFami = mysql_query($sqlDesempleoFami,$db);
$canDesempleoFami = mysql_num_rows($resDesempleoFami);
	
$sqlTipoBene = "SELECT * FROM tipotitular";
$resTipoBene = mysql_query($sqlTipoBene,$db);
$arrayTipo = array();	
while ($rowTipoBene  = mysql_fetch_assoc($resTipoBene)) { 
	$arrayTipo[(int)$rowTipoBene['codtiptit']] = $rowTipoBene['descrip'];
}

$arrayListadoCompleto = array();
$arrayDele = array();
$i = 0;
while ($rowDesempleo = mysql_fetch_assoc($resDesempleo)) {
	$index = $rowDesempleo['nrotitu'].$rowDesempleo['nrobaja'];
	if ($index == "") {
		$index = "NP".$i;
		$i++;
	}
	$arrayDele[$index] = $rowDesempleo['deletitu'].$rowDesempleo['delebaja'];
	$estado = "NO EMPADRONADO";
	if ($rowDesempleo['nrotitu'] != null) { $estado = "ACTIVO"; }
	if ($rowDesempleo['nrobaja'] != null) { $estado = "DE BAJA"; }
	$situ = "SIN INFORMACION";
	if ($rowDesempleo['sitututi'] != null) { $situ = $arrayTipo[$rowDesempleo['sitututi']]; }
	if ($rowDesempleo['situbaja'] != null) { $situ = $arrayTipo[$rowDesempleo['situbaja']]; }
	
	$arrayListadoCompleto[$index."-0"] = array("nroafil" => $rowDesempleo['nrotitu'].$rowDesempleo['nrobaja'],
									  "tipo" => "TITULAR",
									  "delega" => $rowDesempleo['deletitu'].$rowDesempleo['delebaja'],
									  "estado" => $estado, "situ" => $situ, 
									  "cuilbeneficiario" => $rowDesempleo['cuilbeneficiario'],
									  "apellidoynombre" => $rowDesempleo['apellidoynombre'],
									  "fechacobro" => $rowDesempleo['fechacobro'],
									  "fechainformesss"	=> $rowDesempleo['fechainformesss']);
}

while ($rowDesempleoFami = mysql_fetch_assoc($resDesempleoFami)) {
	$orden = $rowDesempleoFami['ordentitu'].$rowDesempleoFami['ordenbaja'];
	$dele = "";
	$index = $rowDesempleoFami['nrotitu'].$rowDesempleoFami['nrobaja'];
	if ($index == "") {
		$index = "NP".$i;
		$i++;
	}
	if (array_key_exists($index, $arrayDele)) { $dele = $arrayDele[$index]; }
	$estado = "NO EMPADRONADO";
	if ($rowDesempleoFami['nrotitu'] != null) { $estado = "ACTIVO"; }
	if ($rowDesempleoFami['nrobaja'] != null) { $estado = "DE BAJA"; }
	
	$arrayListadoCompleto[$index."-".$orden] = array("nroafil" => $rowDesempleoFami['nrotitu'].$rowDesempleoFami['nrobaja'],
									  "tipo" => "FAMILIAR",
									  "delega" => $dele,
									  "estado" => $estado, "situ" => "",
									  "cuilbeneficiario" => $rowDesempleoFami['cuilbeneficiario'],
									  "apellidoynombre" => $rowDesempleoFami['apellidoynombre'],
									  "fechacobro" => $rowDesempleoFami['fechacobro'],
									  "fechainformesss"	=> $rowDesempleoFami['fechainformesss']);
}
ksort($arrayListadoCompleto);

$file= $_GET['periodo']."_DESEMPLEO.xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");
?>
<body>
<div align="center">
	<h3>Periodo Desempleo <?php echo $_GET['periodo'] ?></h3>
  	<table>
  		<thead>
	  		<tr>
	  			<td>Nro Afiliado</td>
	  			<td>Tipo Afiliado</td>
	  			<td>Dele</td>
	  			<td>Estado</td>
	  			<td>Situacion</td>
	  			<td>C.U.I.L.</td>
	  			<td>Apellido y Nombre</td>
	  			<td>Fecha Cobro</td>
	  			<td>Fecha Informe</td>
	  		</tr>
  		</thead>
  		<tbody>
  	<?php foreach ($arrayListadoCompleto as $datos) { ?>
  			<tr>
  				<td><?php echo $datos['nroafil'] ?></td>
  				<td><?php echo $datos['tipo'] ?></td>
  				<td><?php echo $datos['delega'] ?></td>
  				<td><?php echo $datos['estado'] ?></td>
  				<td><?php echo $datos['situ']  ?></td>
  				<td><?php echo $datos['cuilbeneficiario'] ?></td>
  				<td><?php echo $datos['apellidoynombre'] ?></td>
  				<td><?php echo $datos['fechacobro'] ?></td>
  				<td><?php echo $datos['fechainformesss'] ?></td>
  			</tr>
  	<?php } ?>
  		</tbody>
  	</table>
</div>
</body>
</html>