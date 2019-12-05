<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor']) && isset($_POST['tipo']) && isset($_POST['nomenclador'])) {
	$codigo=$_POST['valor'];
	$cantidaPuntos = substr_count($codigo,'.');
	$tipo = $_POST['tipo'];
	$idcontrato = $_POST['contrato'];
	$nomenclador = $_POST['nomenclador'];
	$personeria = $_POST['personeria'];		
	$delete =  $_POST['eleminar'];		
	
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
		$sqlPractica="SELECT dc.*, p.codigopractica, p.descripcion, p.internacion, t.descripcion as complejidad, pc.descripcion as categoria
						FROM practicas p, tipocomplejidad t, practicascategorias pc, detcontratoprestador dc
						WHERE dc.idcontrato = $idcontrato and dc.idpractica = p.idpractica and
							  p.nomenclador = $nomenclador and p.idpadre is null and p.tipopractica = $tipo and 
							  p.codigopractica not like '%.%' and p.codigopractica not like '%.%.%' and 
							  p.codigocomplejidad = t.codigocomplejidad and dc.idcategoria = pc.id
						ORDER BY p.codigopractica";
	} else {
		$padre = $_POST['padre'];
		if ($cantidaPuntos == 0) {
			$sqlPractica="SELECT dc.*, p.codigopractica, p.descripcion, p.internacion, t.descripcion as complejidad, pc.descripcion as categoria
							FROM practicas p, tipocomplejidad t, practicascategorias pc, detcontratoprestador dc
							WHERE dc.idcontrato = $idcontrato and dc.idpractica = p.idpractica and
								  p.nomenclador = $nomenclador and p.idpadre = $padre and
								  p.codigopractica like '%.%' and p.codigopractica not like '%.%.%' and 
								  p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad and dc.idcategoria = pc.id
							ORDER BY p.codigopractica";
		}
		if ($cantidaPuntos == 1) {
			$sqlPractica="SELECT dc.*, p.codigopractica, p.descripcion, p.internacion, t.descripcion as complejidad, pc.descripcion as categoria
							FROM practicas p, tipocomplejidad t, practicascategorias pc, detcontratoprestador dc
						    WHERE dc.idcontrato = $idcontrato and dc.idpractica = p.idpractica and
						    	  p.nomenclador = $nomenclador and p.idpadre = $padre and
								  p.codigopractica like '%.%.%' and p.tipopractica = $tipo and 
								  p.codigocomplejidad = t.codigocomplejidad and dc.idcategoria = pc.id
							ORDER BY codigopractica";
		}
	}
	
	$respuesta = "<thead><tr>
	         		<th>Codigo</th>
					<th>Categoria</th>
					<th>Descripcion</th>
					<th>Complejidad</th>
					<th>Modulo Consul. / Valor General ($)</th>
					<th>Modulo Urgen. ($)</th>
					<th>G. Hono. ($)</th>
					<th>G. Hono. Especialista ($)</th>
					<th>G. Hono. Ayudante ($)</th>
					<th>G. Hono. Anestesista ($)</th>
					<th>G. Gastos ($)</th>
					<th>Coseguro ($)</th>
					<th>Inte.</th>";
	if ($delete == 1) {	$respuesta.= "<th></th>"; }
	     $respuesta .= "</tr></thead><tbody>";
	
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
						<td>".$rowPractica['categoria']."</td>
						<td>".$rowPractica['descripcion']."</td>		
						<td>".$rowPractica['complejidad']."</td>
						<td>".$rowPractica['moduloconsultorio']."</td>
						<td>".$rowPractica['modulourgencia']."</td>
						<td>".$rowPractica['galenohonorario']."</td>
						<td>".$rowPractica['galenohonorarioespecialista']."</td>
						<td>".$rowPractica['galenohonorarioayudante']."</td>
						<td>".$rowPractica['galenohonorarioanestesista']."</td>
						<td>".$rowPractica['galenogastos']."</td>
						<td>".$rowPractica['coseguro']."</td>
						<td>".$inte."</td>";
		if ($delete == 1) { $respuesta.= "<td><input type='checkbox' name='".$rowPractica["idpractica"]."' id='practicasactuales' value='".$rowPractica["idpractica"]."' /></td>";	}   
			$respuesta.= "</tr>";
		$i++;
	}
	$respuesta.="</tbody>";
	if($canPractica == 0) {
		$respuesta = 0;
	}
	echo $respuesta;
}
?>