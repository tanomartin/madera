<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor']) && isset($_POST['tipo'])) {
	$codigo=$_POST['valor'];
	$tipo = $_POST['tipo'];
	$nomenclador = $_POST['nomenclador'];
	$contrato = $_POST['contrato'];
	$respuesta = "<thead><tr>
         			 <th>C&oacute;digo</th>
					 <th>Descripciones</th>
					 <th>U. Honorarios</th>
					 <th>U. Honorarios<br>Especialista</th>
					 <th>U. Honorarios<br>Ayudante</th>
			 		 <th>U. Honorarios<br>Anestesista</th>
			  		 <th>U. Gastos / U.B.</th>
					 <th>Res. 650</th>
					 <th>Inter.</th>
					 <th>+Info</th>
       			</tr></thead><tbody>";
	if ($codigo == -1) {
		$sqlPractica="SELECT p.*, t.descripcion as complejidad FROM practicas p, tipocomplejidad t WHERE p.codigopractica not like '%.%' and p.codigopractica not like '%.%.%' and p.nomenclador = $nomenclador and p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad ORDER BY p.codigopractica";
	} else {
		$cantidaPuntos = substr_count($codigo,'.');
		if ($cantidaPuntos == 0) {
			$sqlPractica="SELECT p.*, t.descripcion as complejidad FROM practicas p, tipocomplejidad t WHERE p.codigopractica like '$codigo.%' and p.codigopractica not like '$codigo.%.%' and p.nomenclador = $nomenclador and p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad ORDER BY p.codigopractica";
		}
		if ($cantidaPuntos == 1) {
			$sqlPractica="SELECT p.*, t.descripcion as complejidad FROM practicas p, tipocomplejidad t WHERE p.codigopractica like '$codigo.%' and p.nomenclador = $nomenclador and p.tipopractica = $tipo and p.codigocomplejidad = t.codigocomplejidad ORDER BY p.codigopractica";
		}
	}
	
	$resPractica=mysql_query($sqlPractica,$db);
	$canPractica=mysql_num_rows($resPractica);
	while($rowPractica=mysql_fetch_assoc($resPractica)) {
		$practica = $rowPractica['idpractica'];
		$inter = "NO";
		if ($rowPractica['internacion'] == 1) { $inter = "SI"; }
		$respuesta.="<tr>
						<td>".$rowPractica['codigopractica']."</td>
						<td>".$rowPractica['descripcion']."</td>
						<td>".$rowPractica['unihonorario']."</td>
						<td>".$rowPractica['unihonorarioespecialista']."</td>
						<td>".$rowPractica['unihonorarioayudante']."</td>
						<td>".$rowPractica['unihonorarioanestesista']."</td>
						<td>".$rowPractica['unigastos']."</td>
						<td>".$rowPractica['complejidad']."</td>
						<td>".$inter."</td>";
		if ($contrato == 1) {
			$respuesta.= "<td><input name=\"contrato\" type=\"button\" value=\"Contratos\" onclick=\"abrirPantalla('../buscador/detallePracticasPresta.php?idpractica=$practica&nomenclador=$nomenclador')\"/></td></tr>";
		} else {
			$respuesta.= "<td><input name=\"resolucion\" type=\"button\" value=\"Resoluciones\" onclick=\"abrirPantalla('../buscador/detalleResoluciones.php?idpractica=$practica&nomenclador=$nomenclador')\"/></td></tr>";
		}
	}
	$respuesta.="</tbody>";
	
	if($canPractica == 0) {
		$respuesta = 0;
	}
	echo $respuesta;
}
?>