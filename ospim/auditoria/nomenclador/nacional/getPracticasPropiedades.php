<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor']) && isset($_POST['tipo'])) {
	$codigo=$_POST['valor'];
	$tipo = $_POST['tipo'];
	$respuesta = "<thead><tr>
         			 <th>C&oacute;digo</th>
					 <th>Descripciones</th>
					 <th>U. Honorarios</th>
					 <th>U. Honorarios </br> Especialista</th>
					 <th>U. Honorarios </br> Ayudante</th>
			 		 <th>U. Honorarios </br> Anestesista</th>
			  		 <th>U. Gastos</th>
					 <th>Complejidad</th>
       			</tr></thead><tbody>";
	if ($codigo == -1) {
		$sqlPractica="SELECT * FROM practicas WHERE `codigopractica` not like '%.%' and `codigopractica` not like '%.%.%' and nomenclador = 1 and tipopractica = $tipo";
	} else {
		$cantidaPuntos = substr_count($codigo,'.');
		if ($cantidaPuntos == 0) {
			$sqlPractica="SELECT * FROM practicas WHERE `codigopractica` like '$codigo.%' and `codigopractica` not like '$codigo.%.%' and nomenclador = 1 and tipopractica = $tipo";
		}
		if ($cantidaPuntos == 1) {
			$sqlPractica="SELECT * FROM practicas WHERE `codigopractica` like '$codigo.%' and nomenclador = 1 and tipopractica = $tipo";
		}
	}
	$resPractica=mysql_query($sqlPractica,$db);
	$canPractica=mysql_num_rows($resPractica);
	$i = 0;
	
	$sqlComplejida = "SELECT * FROM tipocomplejidad";
	$resComplejida = mysql_query($sqlComplejida,$db);
	$tipoComplejidad = array();
	while($rowComplejida = mysql_fetch_assoc($resComplejida)) {
		$tipoComplejidad[$rowComplejida['codigocomplejidad']] = $rowComplejida['descripcion'];	
	}
	
	while($rowPractica=mysql_fetch_assoc($resPractica)) {
		$practica = $rowPractica['codigopractica'];
		$respuesta.="<tr>
						<td>".$rowPractica['codigopractica']."</td>
						<td>".$rowPractica['descripcion']."</td>
						<td><input name=\"unihonorario".$i."-".$rowPractica['codigopractica']."\" id=\"unihonorario".$i."\" type=\"text\" value=\"".$rowPractica['unihonorario']."\" size=\"10\"/></td>
						<td><input name=\"unihonorarioespecialista".$i."-".$rowPractica['codigopractica']."\" id=\"unihonorarioespecialista".$i."\" type=\"text\" value=\"".$rowPractica['unihonorarioespecialista']."\" size=\"10\"/></td>
						<td><input name=\"unihonorarioayudante".$i."-".$rowPractica['codigopractica']."\" id=\"unihonorarioayudante".$i."\" type=\"text\" value=\"".$rowPractica['unihonorarioayudante']."\" size=\"10\"/></td>
						<td><input name=\"unihonorarioanestesista".$i."-".$rowPractica['codigopractica']."\" id=\"unihonorarioanestesista".$i."\" type=\"text\" value=\"".$rowPractica['unihonorarioanestesista']."\" size=\"10\"/></td>
						<td><input name=\"unigastos".$i."-".$rowPractica['codigopractica']."\" id=\"unigastos".$i."\" type=\"text\" value=\"".$rowPractica['unigastos']."\" size=\"10\"/></td>
						<td><select name=\"complejidad".$i."-".$rowPractica['codigopractica']."\" id=\"complejidad".$i."\">";
						reset($tipoComplejidad);
						while ($complejidad = current($tipoComplejidad)) {
								if (key($tipoComplejidad) == $rowPractica['codigocomplejidad']) {
									$selected = "selected";
								} else {
									$selected = "";
								}
								$respuesta.="<option value=".key($tipoComplejidad)." $selected>".$complejidad."</option>";
								next($tipoComplejidad);
						}
		$respuesta.= "</select>
					</tr>";
		$i++;
	}
	$respuesta.="</tbody>";
	if ($canPractica == 0) {
		$respuesta = 0;
	}
	echo $respuesta;
}
?>