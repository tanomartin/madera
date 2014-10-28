<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_POST['delegacion']))
{
	$codidelega = $_POST['delegacion'];
	$sqlTitulares="SELECT nroafiliado, apellidoynombre, tipoafiliado FROM titulares WHERE codidelega = '$codidelega' and emitecarnet = 1";
	$resTitulares=mysql_query($sqlTitulares,$db);
	if(mysql_num_rows($resTitulares)!=0) {
//		$respuesta.="<table class='tablesorter' id='listado'>";
		$respuesta.="<thead>";
		$respuesta.="<tr><th>Nro. de Afiliado</th><th>Apellido y Nombre</th><th>Regular [Azul]</th><th>Solo OSPIM [Bordo]</th><th>Opcion [Rojo]</th><th>USIMRA [Verde]</th><th>Imprime</th></tr>";
		$respuesta.="</thead>";
		$respuesta.="<tbody>";

		while($rowTitulares=mysql_fetch_assoc($resTitulares)) {
			$respuesta.="<tr align='center'><td>".$rowTitulares['nroafiliado']."</td><td>".$rowTitulares['apellidoynombre']."</td>";
	
			if(strcmp($rowTitulares['tipoafiliado'],"R")==0) {
				$respuesta.="<td>X</td><td>-</td><td>-</td><td>X</td>";
			}
			if(strcmp($rowTitulares['tipoafiliado'],"S")==0) {
				$respuesta.="<td>-</td><td>X</td><td>-</td><td>X</td>";
			}
			if(strcmp($rowTitulares['tipoafiliado'],"O")==0) {
				$respuesta.="<td>-</td><td>-</td><td>X</td><td>-</td>";
			}
	
			$respuesta.="<td><input type='checkbox' name='titularSeleccionado[]' value=".$rowTitulares['nroafiliado']." checked></td></tr>";
		}
		$respuesta.="</tbody>";
//		$respuesta.="</table> ";
	}
	echo $respuesta;
}
?>