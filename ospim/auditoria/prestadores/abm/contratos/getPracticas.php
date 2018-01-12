<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor']) && isset($_POST['tipo']) && isset($_POST['nomenclador'])) {
	$codigo=$_POST['valor'];
	$tipo = $_POST['tipo'];
	$nomenclador = $_POST['nomenclador'];
	$personeria = $_POST['personeria'];
	$respuesta = "<thead><tr>
	         		<th>Cod.</th>
					<th>Nomenclador</th>
					<th>Descripciones</th>
					<th>Clasificacion<br>Res. 650</th>
					<th>Internacion</th>
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
	$sqlPractica .= " and p.nomenclador = $nomenclador and p.nomenclador = n.id order by p.idpractica";

	$resPractica=mysql_query($sqlPractica,$db);
	$canPractica=mysql_num_rows($resPractica);
	$i= 0;
	while($rowPractica=mysql_fetch_assoc($resPractica)) {
		$id = $rowPractica['idpractica'];
		$inte = "NO";
		if ($rowPractica['internacion'] == 1) {
			$inte = "SI";
		}
		$respuesta.="<tr>
						<td>".$rowPractica['codigopractica']."</td>
						<td>".$rowPractica['nombrenomenclador']."</td>
						<td>".$rowPractica['descripcion']."</td>		
						<td>".$rowPractica['complejidad']."</td>
						<td>".$inte."</td>";
		$respuesta.="<td><select id='categoria-".$i."' name='categoria-".$id."'>";
		
		$sqlCategoria = "select * from practicascategorias where (tipoprestador = 0 or tipoprestador = $personeria)";
		$resCategoria = mysql_query($sqlCategoria,$db);
		while($rowCategoria = mysql_fetch_assoc($resCategoria)) { 
			$respuesta.="<option value='".$rowCategoria['id']."'>".$rowCategoria['descripcion']."</option>";
		}
		
		$respuesta.="</select></td>";
		$respuesta.=   "<td><select id='tipoCarga-".$i."' name='tipoCarga-".$id."' onchange=habilitarValores('".$i."',this.value)>
								<option value='0'>Tipo Carga</option>
								<option value='1'>Por Modulo</option>
								<option value='2'>Por Galeno</option>
							</select>
						</td>
						<td><input id='moduloConsultorio-".$i."' name='moduloConsultorio-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='moduloUrgencia-".$i."' name='moduloUrgencia-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='gHono-".$i."' name='gHono-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='gHonoEspe-".$i."' name='gHonoEspe-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='gHonoAyud-".$i."' name='gHonoAyud-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='gHonoAnes-".$i."' name='gHonoAnes-".$id."' type='text' disabled=true size='7'/></td>
						<td><input id='gGastos-".$i."' name='gGastos-".$id."' type='text' disabled=true size='7'/></td>
					</tr>";
		$i++;
	}
	$respuesta.="</tbody>";
	
	if($canPractica == 0) {
		$respuesta = 0;
	}
	echo $respuesta;
}
?>