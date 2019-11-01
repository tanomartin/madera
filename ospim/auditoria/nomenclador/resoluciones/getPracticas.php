<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['resolucion'])) {
	$reso=$_POST['resolucion'];
	$respuesta = "<thead><tr>
         			 <th>Codigo</th>
					 <th>Descripciones</th>
					 <th>Modulo</th>
       			</tr></thead><tbody>";

	
	$sqlPractica="SELECT pv.*, p.codigopractica, p.descripcion FROM practicasvaloresresolucion pv, practicas p 
					WHERE pv.idresolucion = $reso and pv.idpractica = p.idpractica";
	$resPractica=mysql_query($sqlPractica,$db);
	$canPractica=mysql_num_rows($resPractica);
	while($rowPractica=mysql_fetch_assoc($resPractica)) {
		$respuesta.="<tr>
						<td>".$rowPractica['codigopractica']."</td>
						<td>".preg_replace('/[^A-Za-z0-9\-]/', ' ',substr($rowPractica['descripcion'],0,200))."</td>
						<td>".$rowPractica['modulo']."</td>
					 </tr>";
	}
	$respuesta.="</tbody>";
	
	if($canPractica == 0) {
		$respuesta = 0;
	}
	echo $respuesta;
}
?>