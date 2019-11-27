<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor']) && isset($_POST['tipo']) && isset($_POST['nomenclador'])) {
	$codigo=$_POST['valor'];
	$tipo = $_POST['tipo'];
	$nomenclador = $_POST['nomenclador'];
	$respuesta = "<thead><tr>
         			 <th>C&oacute;digo</th>
					 <th>Descripciones</th>
					 <th>U. Honorarios</th>
					 <th>U. Honorarios Especialista</th>
					 <th>U. Honorarios Ayudante</th>
			 		 <th>U. Honorarios Anestesista</th>
			  		 <th>U. Gastos</th>
					 <th>Clasificacion<br>Res. 650</th>
					 <th>Interancion</th>
       			</tr></thead><tbody>";
	if ($codigo == -1) {
		$sqlPractica="SELECT * FROM practicas WHERE `codigopractica` not like '%.%' and `codigopractica` not like '%.%.%' and nomenclador = $nomenclador and tipopractica = $tipo";
	} else {
		$cantidaPuntos = substr_count($codigo,'.');
		if ($cantidaPuntos == 0) {
			$sqlPractica="SELECT * FROM practicas WHERE `codigopractica` like '$codigo.%' and `codigopractica` not like '$codigo.%.%' and nomenclador = $nomenclador and tipopractica = $tipo";
		}
		if ($cantidaPuntos == 1) {
			$sqlPractica="SELECT * FROM practicas WHERE `codigopractica` like '$codigo.%' and nomenclador = $nomenclador and tipopractica = $tipo";
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
		if ($rowPractica['internacion'] == 0) {
			$selectNO = "selected";
			$selectSI = "";
		} else {
			$selectNO = "";
			$selectSI = "selected";
		}
		$respuesta.="<tr>
						<td><input name=\"codigopractica".$i."\" id=\"codigopractica".$i."\" type=\"text\" value=\"".$rowPractica['codigopractica']."\" size=\"5\" readonly=\"readonly\" style=\"background-color: #CCCCCC; text-align:center\"  /></td>
						<td>".$rowPractica['descripcion']."</td>
						<td><input name=\"unihonorariosolo".$i."\" id=\"unihonorariosolo".$i."\" type=\"text\" value=\"".$rowPractica['unihonorario']."\" size=\"10\"/></td>
						<td><input name=\"unihonorarioespecialista".$i."\" id=\"unihonorarioespecialista".$i."\" type=\"text\" value=\"".$rowPractica['unihonorarioespecialista']."\" size=\"10\"/></td>
						<td><input name=\"unihonorarioayudante".$i."\" id=\"unihonorarioayudante".$i."\" type=\"text\" value=\"".$rowPractica['unihonorarioayudante']."\" size=\"10\"/></td>
						<td><input name=\"unihonorarioanestesista".$i."\" id=\"unihonorarioanestesista".$i."\" type=\"text\" value=\"".$rowPractica['unihonorarioanestesista']."\" size=\"10\"/></td>
						<td><input name=\"unigastos".$i."\" id=\"unigastos".$i."\" type=\"text\" value=\"".$rowPractica['unigastos']."\" size=\"10\"/></td>
						<td><select name=\"complejidad".$i."\" id=\"complejidad".$i."\">";
						foreach ($tipoComplejidad as $key => $complejidad) {
							$selected = "";
							if ($key == $rowPractica['codigocomplejidad']) {
								$selected = "selected";
							}
							$respuesta.="<option value='$key' $selected>".$complejidad."</option>";
						}
		$respuesta.= "</select>
					  <td>
						<select name=\"internacion".$i."\" id=\"internacion".$i."\">
							<option value=0 $selectNO>NO</opction>
							<option value=1 $selectSI>SI</opction>
						</select>
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