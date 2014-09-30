<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
if(isset($_POST['valor'])) {
	$codigo=$_POST['valor'];
	$respuesta = "<thead><tr>
         			 <th>C&oacute;digo</th>
					 <th>Descripciones</th>
					 <th>Valor ($)</th>
       			</tr></thead><tbody>";
	if ($codigo == 0) {
		$sqlPractica="SELECT * FROM practicas WHERE `codigopractica` not like '%.%' and `codigopractica` not like '%.%.%' and nomenclador = 1";
	} else {
		$cantidaPuntos = substr_count($codigo,'.');
		if ($cantidaPuntos == 0) {
			$sqlPractica="SELECT * FROM practicas WHERE `codigopractica` like '$codigo.%' and `codigopractica` not like '$codigo.%.%' and nomenclador = 1";
		}
		if ($cantidaPuntos == 1) {
			$sqlPractica="SELECT * FROM practicas WHERE `codigopractica` like '$codigo.%' and nomenclador = 1";
		}
	}
	$resPractica=mysql_query($sqlPractica,$db);
	while($rowPractica=mysql_fetch_assoc($resPractica)) {
		$respuesta.="<tr>
						<td>".$rowPractica['codigopractica']."</td>
						<td>".$rowPractica['descripcion']."</td>
						<td>".$rowPractica['valornacional']."</td>
					</tr>";
	}
	$respuesta.="</tbody>";
	echo $respuesta;
}
?>