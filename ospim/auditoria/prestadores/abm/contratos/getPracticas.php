<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor']) && isset($_POST['tipo']) && isset($_POST['nomenclador'])) {
	$codigo=$_POST['valor'];
	$cantidaPuntos = substr_count($codigo,'.');
	$tipo = $_POST['tipo'];
	$nomenclador = $_POST['nomenclador'];
	$personeria = $_POST['personeria'];		
	
	$sqlCategoria = "select * from practicascategorias where (tipoprestador = 0 or tipoprestador = $personeria)";
	$resCategoria = mysql_query($sqlCategoria,$db);
	$canCategoria = mysql_num_rows($resCategoria);
	$arrayCategoria = array();
	if ($canCategoria > 0) {
		while($rowCategoria = mysql_fetch_assoc($resCategoria)) {
			$arrayCategoria[$rowCategoria['id']] = $rowCategoria['descripcion'];
		}
	}
	
	if (!isset($_POST['padre'])) {
		$sqlPractica="SELECT p.*, t.descripcion as complejidad
						FROM practicas p, tipocomplejidad t
						WHERE p.nomenclador = $nomenclador and p.idpadre is null and p.tipopractica = $tipo and 
							  p.codigopractica not like '%.%' and p.codigopractica not like '%.%.%' and 
							  p.codigocomplejidad = t.codigocomplejidad
						ORDER BY p.codigopractica";
	} else {
		$padre = $_POST['padre'];
		if ($cantidaPuntos == 0) {
			$sqlPractica="SELECT p.*, t.descripcion as complejidad
							FROM practicas p, tipocomplejidad t
							WHERE p.nomenclador = $nomenclador and p.idpadre = $padre and
								  p.codigopractica like '%.%' and p.codigopractica not like '%.%.%' and 
								  p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad 
							ORDER BY p.codigopractica";
		}
		if ($cantidaPuntos == 1) {
			$sqlPractica="SELECT p.*, t.descripcion as complejidad
							FROM practicas p, tipocomplejidad t
						    WHERE p.nomenclador = $nomenclador and p.idpadre = $padre and
								  p.codigopractica like '%.%.%' and p.tipopractica = $tipo and 
								  p.codigocomplejidad = t.codigocomplejidad 
							ORDER BY codigopractica";
		}
	}
	
	$respuesta = "<thead><tr>
	         		<th>Codigo</th>
					<th>Descripciones</th>
					<th>Clas.<br>Res. 650</th>
					<th>Inter.</th>
					<th>Categoria</th>
					<th></th>
					<th>Modulo Consul. / Valor General ($)</th>
					<th>Modulo Urgen. ($)</th>
					<th>G. Hono. ($)</th>
					<th>G. Hono. Especialista ($)</th>
					<th>G. Hono. Ayudante ($)</th>
					<th>G. Hono. Anestesista ($)</th>
					<th>G. Gastos ($)</th>
					<th>Coseguro ($)</th>
	       		</tr></thead><tbody>";
	
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
						<td>".$rowPractica['descripcion']."</td>		
						<td>".$rowPractica['complejidad']."</td>
						<td>".$inte."</td>";
		
		$respuesta.="<td><select id='categoria-".$i."' name='categoria-".$id."'>";
		foreach ($arrayCategoria as $idCatego => $categoria) {
			$respuesta.="<option value='".$idCatego."'>".$categoria."</option>";
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
						<td><input id='coseguro-".$i."' name='coseguro-".$id."' type='text' disabled=true size='7'/></td>
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