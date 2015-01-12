<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
if(isset($_POST['valor']) && isset($_POST['tipo']) && isset($_POST['nomenclador'])) {
	$codigo=$_POST['valor'];
	$tipo = $_POST['tipo'];
	$nomenclador = $_POST['nomenclador'];
	$respuesta = "<thead><tr>
         			 <th>C&oacute;digo</th>
					 <th>Nomenclador</th>
					 <th>Descripciones</th>
					 <th>Complejidad</th>
					 <th>Valor ($)</th>
					 <th></th>
       			</tr></thead><tbody>";
				
	if ($codigo == -1) {
		$sqlPractica="SELECT p.*, t.descripcion as complejidad FROM practicas p, tipocomplejidad t WHERE p.codigopractica not like '%.%' and p.codigopractica not like '%.%.%' and p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad";
	} else {
		$cantidaPuntos = substr_count($codigo,'.');
		if ($cantidaPuntos == 0) {
			$sqlPractica="SELECT p.*, t.descripcion as complejidad FROM practicas p, tipocomplejidad t WHERE p.codigopractica like '$codigo.%' and p.codigopractica not like '$codigo.%.%' and p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad";
		}
		if ($cantidaPuntos == 1) {
			$sqlPractica="SELECT p.*, t.descripcion as complejidad FROM practicas p, tipocomplejidad t WHERE p.codigopractica like '$codigo.%' and p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad";
		}
	}
	
	if ($nomenclador != 3) {
		$sqlPractica .= " and p.nomenclador = $nomenclador order by p.idpractica";
	} else {
		$sqlPractica .= " order by p.idpractica";
	}
	
	$resPractica=mysql_query($sqlPractica,$db);
	$canPractica=mysql_num_rows($resPractica);
	while($rowPractica=mysql_fetch_assoc($resPractica)) {

		$id = $rowPractica['nomenclador'].$rowPractica['codigopractica'];
		if ($rowPractica['nomenclador'] == 1) { $descriNomenclador = "NN"; } else { $descriNomenclador = "NP"; }
		if ($rowPractica['nomenclador'] == 1) { 
			$valorPractica =  $rowPractica['valornacional']; 
		} else { 
			$valorPractica = "<input size='8' disabled='disabled' type='text' name='valorNN$id' id='valorNN$id'/>"; 
		}
		
		$respuesta.="<tr>
						<td>".$rowPractica['codigopractica']."</td>
						<td><input type='text' style='display:none' size='1' value='".$rowPractica['nomenclador']."' disabled='disabled' name='N".$id."' id='N".$id."' />".$descriNomenclador."</td>
						<td>".$rowPractica['descripcion']."</td>
						<td>".$rowPractica['complejidad']."</td>
						<td>".$valorPractica."</td>
						<td><input type='checkbox' name='".$id."' onchange=habilitarValor('".$rowPractica['nomenclador']."','".$rowPractica['codigopractica']."',this) accesskey='".$rowPractica["nomenclador"]."' id='practicasagregar' value='".$rowPractica["codigopractica"]."'></td>
					</tr>";
	}
	$respuesta.="</tbody>";
	
	if($canPractica == 0) {
		$respuesta = 0;
	}
	echo $respuesta;
}
?>