<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor']) && isset($_POST['tipo']) && isset($_POST['nomenclador'])) {
	$codigo=$_POST['valor'];
	$tipo = $_POST['tipo'];
	$nomenclador = $_POST['nomenclador'];
	$personeria = $_POST['personeria'];
	$respuesta = "<thead><tr>
	         		<th>C&oacute;digo</th>
					<th>Nomenclador</th>
					<th>Descripciones</th>
					<th>Complejidad</th>
					<th>Categoria</th>
					<th></th>
					<th>Modulo Consultorio / Valor General ($)</th>
					<th>Modulo Urgencia ($)</th>
					<th>G. Honorarios ($)</th>
					<th>G. Honorarios Especialista ($)</th>
					<th>G. Honorarios Ayudante ($)</th>
					<th>G. Honorarios Anestesista ($)</th>
					<th>G. Gastos ($)</th>
	       		</tr></thead><tbody>";
				
	if ($codigo == -1) {
		$sqlPractica="SELECT p.*, t.descripcion as complejidad, n.nombre as nombrenomenclador FROM practicas p, tipocomplejidad t, nomencladores n WHERE p.codigopractica not like '%.%' and p.codigopractica not like '%.%.%' and p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad";
	} else {
		$cantidaPuntos = substr_count($codigo,'.');
		if ($cantidaPuntos == 0) {
			$sqlPractica="SELECT p.*, t.descripcion as complejidad, n.nombre as nombrenomenclador FROM practicas p, tipocomplejidad t, nomencladores n WHERE p.codigopractica like '$codigo.%' and p.codigopractica not like '$codigo.%.%' and p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad";
		}
		if ($cantidaPuntos == 1) {
			$sqlPractica="SELECT p.*, t.descripcion as complejidad, n.nombre as nombrenomenclador FROM practicas p, tipocomplejidad t, nomencladores n WHERE p.codigopractica like '$codigo.%' and p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad";
		}
	}
	
	if ($nomenclador != 3) {
		$sqlPractica .= " and p.nomenclador = $nomenclador and p.nomenclador = n.id order by p.idpractica";
	} else {
		$sqlPractica .= " order by p.idpractica";
	}

	$resPractica=mysql_query($sqlPractica,$db);
	$canPractica=mysql_num_rows($resPractica);
	while($rowPractica=mysql_fetch_assoc($resPractica)) {
		$id = $rowPractica['idpractica'];
		$respuesta.="<tr>
						<td>".$rowPractica['codigopractica']."</td>
						<td>".$rowPractica['nombrenomenclador']."</td>
						<td>".$rowPractica['descripcion']."</td>
						<td>".$rowPractica['complejidad']."</td>";
		$respuesta.="<td><select id='categoria-".$id."' name='categoria-".$id."'>";
		if ($personeria == 3 || $personeria == 2) {
			$sqlCategoria = "select * from practicascategorias where (tipoprestador = 0 or tipoprestador = $personeria)";
			$resCategoria = mysql_query($sqlCategoria,$db);
			while($rowCategoria = mysql_fetch_assoc($resCategoria)) { 
				$respuesta.="<option value='".$rowCategoria['id']."'>".$rowCategoria['descripcion']."</option>";
			}
		} else {
			$respuesta.="<option value='0'>Sin Categoria</option>";
		}
		$respuesta.="</select></td>";
		$respuesta.=   "<td><select id='tipoCarga-".$id."' name='tipoCarga-".$id."' onchange=habilitarValores('".$id."',this)>
								<option value='0'>Tipo Carga</option>
								<option value='1'>Por Modulo</option>
								<option value='2'>Por Galeno</option>
							</select>
						</td>
						<td><input id='moduloConultorio-".$id."' name='moduloConultorio-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='moduloUrgencia-".$id."' name='moduloUrgencia-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='gHono-".$id."' name='gHono-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='gHonoEspe-".$id."' name='gHonoEspe-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='gHonoAyud-".$id."' name='gHonoAyud-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='gHonoAnes-".$id."' name='gHonoAnes-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='gGastos-".$id."' name='gGastos-".$id."' type='text' disabled=true size='7'/></td>
					</tr>";
	}
	$respuesta.="</tbody>";
	
	if($canPractica == 0) {
		$respuesta = 0;
	}
	echo $respuesta;
}
?>