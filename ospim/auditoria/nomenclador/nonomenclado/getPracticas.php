<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor']) && isset($_POST['tipo'])) {
	$codigo = $_POST['valor'];
	$cantidaPuntos = substr_count($codigo,'.');
	$tipo = $_POST['tipo'];
	$respuesta = "<thead><tr>
         			 <th>C&oacute;digo</th> 
					 <th>Descripciones</th>
					 <th>Complejidad</th>
					 <th>Internacion</th>
					 <th>Acciones</th>
       			</tr></thead><tbody>";
	if (!isset($_POST['padre'])) {
		$sqlPractica="SELECT p.*, t.descripcion as complejidad 
						FROM practicas p,tipocomplejidad t 
						WHERE p.idpadre is null and p.nomenclador = 2 and p.tipopractica = $tipo and 
						      p.codigopractica not like '%.%' and p.codigopractica not like '%.%.%' and
							  p.codigocomplejidad = t.codigocomplejidad
						ORDER BY p.codigopractica";
	} else {
		$padre = $_POST['padre'];
		if ($cantidaPuntos == 0) {
			$sqlPractica="SELECT p.*, t.descripcion as complejidad 
						FROM practicas p, tipocomplejidad t 
						WHERE p.idpadre = $padre and p.nomenclador = 2 and p.tipopractica = $tipo and 
							  p.codigopractica like '%.%' and p.codigopractica not like '%.%.%' and
							  p.codigocomplejidad = t.codigocomplejidad 
						ORDER BY p.codigopractica";
		}
		if ($cantidaPuntos == 1) {
			$sqlPractica="SELECT p.*, t.descripcion as complejidad 
						FROM practicas p, tipocomplejidad t 
						WHERE p.idpadre = $padre and p.nomenclador = 2 and p.tipopractica = $tipo and 
							  p.codigopractica like '%.%.%' and p.codigocomplejidad = t.codigocomplejidad 
						ORDER BY p.codigopractica";
		}
	}
	//echo $sqlPractica;
	$resPractica=mysql_query($sqlPractica,$db);
	$canPractica=mysql_num_rows($resPractica);
	while($rowPractica=mysql_fetch_assoc($resPractica)) {
		$practica = $rowPractica['idpractica'];
		$inter = "NO";
		if ($rowPractica['internacion'] == 1) { $inter = "SI"; }
		$respuesta.="<tr>
						<td>".$rowPractica['codigopractica']."</td>	
						<td>".$rowPractica['descripcion']."</td>
						<td>".$rowPractica['complejidad']."</td>
						<td>".$inter."</td>
						<td><input name=\"contrato\" type=\"button\" value=\"Prestadores\" onclick=\"abrirPantalla('../buscador/detallePracticasPresta.php?idpractica=$practica&nomenclador=2')\"/></td>
					</tr>";
	}
	$respuesta.="</tbody>";
	if($canPractica == 0) {
		$respuesta = 0;
	}
	echo $respuesta;
}
?>