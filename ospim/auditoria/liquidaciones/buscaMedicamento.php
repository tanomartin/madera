<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
if(isset($_GET)) {
	$busqueda = $_GET['getPrestacion'];
	$idprestador = $_GET['idPrestador'];
	$fechaprestacion = fechaParaGuardar($_GET['fechaPrestacion']);
	$noencontro = TRUE;
	$prestaciones = array();
	set_time_limit(0);
	$sqlBuscaMedicamento="SELECT m.codigo, m.tipo, m.nombre, m.presentacion, m.laboratorio, h.fechadesde, h.precio FROM medipreciohistorico h, medicamentos m WHERE h.fechadesde <= '$fechaprestacion' AND m.nombre like '%$busqueda%' AND h.codigomedicamento = m.codigo AND m.baja = 0 ORDER BY h.fechadesde, h.codigomedicamento";
	$resBuscaMedicamento=mysql_query($sqlBuscaMedicamento,$db);
	if(mysql_num_rows($resBuscaMedicamento)!=0) {
		while($rowBuscaMedicamento=mysql_fetch_array($resBuscaMedicamento)) {
			$noencontro = FALSE;
			$nombre = utf8_encode($rowBuscaMedicamento['nombre']);
			$presentacion = utf8_encode($rowBuscaMedicamento['presentacion']);
			$laboratorio = utf8_encode($rowBuscaMedicamento['laboratorio']);
			$prestaciones[$rowBuscaMedicamento['codigo']] = array(
				'label' => $nombre.' '.$presentacion.' '.$laboratorio.' | Codigo: '.$rowBuscaMedicamento['codigo'].' | Valor: '.$rowBuscaMedicamento['precio'].' desde '.invertirFecha($rowBuscaMedicamento['fechadesde']).' | Origen: ALFABETA ',
				'idpractica' => $rowBuscaMedicamento['codigo'],
				'tipopractica' => $rowBuscaMedicamento['tipo'],	
				'valor' => $rowBuscaMedicamento['precio'],
			);
		}
	}
	if($noencontro) {
		$prestaciones[] = array(
			'label' => 'No se encontraron resultados para la busqueda intentada',
			'idpractica' => NULL,
			'tipopractica' => NULL,
			'valor' => NULL,
		);
	}
	echo json_encode($prestaciones);
	return; 
}
?>