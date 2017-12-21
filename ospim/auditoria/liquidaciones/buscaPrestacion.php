<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
if(isset($_GET)) {
	$busqueda = $_GET['getPrestacion'];
	$idprestador = $_GET['idPrestador'];
	$fechaprestacion = fechaParaGuardar($_GET['fechaPrestacion']);
	$tienecontrato = $_GET['contratoPrestador'];
	$tienesur =  $_GET['nomencladorSur'];
	$noencontro = TRUE;
	$prestaciones = array();
	if($tienecontrato == 1) {
		$sqlLeePracticasContrato="SELECT c.idcontrato, c.codigoprestador, c.fechainicio, c.fechafin, d.idpractica, p.codigopractica, SUBSTRING(p.descripcion,1,80) AS nombrepractica, d.idcategoria, t.descripcion AS nombrecategoria, d.moduloconsultorio, d.modulourgencia, ((d.galenohonorario*p.unihonorario)+(d.galenohonorarioespecialista*p.unihonorarioespecialista)+(d.galenohonorarioayudante*p.unihonorarioayudante)+(d.galenohonorarioanestesista*p.unihonorarioanestesista)+(d.galenogastos*p.unigastos)) AS valorgaleno FROM cabcontratoprestador c, detcontratoprestador d, practicas p, practicascategorias t WHERE c.codigoprestador = $idprestador AND (p.codigopractica like '%$busqueda%' OR p.descripcion like '%$busqueda%') AND c.idcontrato = d.idcontrato AND
d.idpractica = p.idpractica AND d.idcategoria = t.id";
		$resLeePracticasContrato=mysql_query($sqlLeePracticasContrato,$db);
		if(mysql_num_rows($resLeePracticasContrato)!=0) {
			while($rowLeePracticasContrato=mysql_fetch_array($resLeePracticasContrato)) {
				$fechainicontrato=$rowLeePracticasContrato['fechainicio'];
				if($rowLeePracticasContrato['fechafin']==NULL) {
					$fechafincontrato=date("Y-m-d");
				} else {
					$fechafincontrato=$rowLeePracticasContrato['fechafin'];
				}
				if(strcmp($fechainicontrato, $fechaprestacion) <= 0) {
					if(strcmp($fechafincontrato, $fechaprestacion) >= 0) {					
						if($rowLeePracticasContrato['moduloconsultorio']>=0.00) {
							$noencontro = FALSE;
							$prestaciones[] = array(
								'label' => $rowLeePracticasContrato['nombrepractica'].' | Codigo: '.$rowLeePracticasContrato['codigopractica'].' | Mod. Consultorio Valor: '.$rowLeePracticasContrato['moduloconsultorio'].' | Origen: Contrato '.$rowLeePracticasContrato['idcontrato'],
								'idpractica' => $rowLeePracticasContrato['idpractica'],
								'valor' => $rowLeePracticasContrato['moduloconsultorio'],
							);
						}
						if($rowLeePracticasContrato['modulourgencia']>=0.00) {
							$noencontro = FALSE;
							$prestaciones[] = array(
								'label' => $rowLeePracticasContrato['nombrepractica'].' | Codigo: '.$rowLeePracticasContrato['codigopractica'].' | Mod. Urgencia Valor: '.$rowLeePracticasContrato['modulourgencia'].' | Origen: Contrato '.$rowLeePracticasContrato['idcontrato'],
								'idpractica' => $rowLeePracticasContrato['idpractica'],
								'valor' => $rowLeePracticasContrato['modulourgencia'],
							);
						}
						if($rowLeePracticasContrato['valorgaleno']>=0.00) {
							$noencontro = FALSE;
							$prestaciones[] = array(
								'label' => $rowLeePracticasContrato['nombrepractica'].' | Codigo: '.$rowLeePracticasContrato['codigopractica'].' | Galeno Valor: '.$rowLeePracticasContrato['valorgaleno'].' | Origen: Contrato '.$rowLeePracticasContrato['idcontrato'],
								'idpractica' => $rowLeePracticasContrato['idpractica'],
								'valor' => $rowLeePracticasContrato['valorgaleno'],
							);
						}
					}
				}
			}
		}
	}
	if($tienesur == 1) {
		$sqlLeePracticasResolucion="SELECT r.fechadesde, r.fechahasta, p.idpractica, p.codigopractica, SUBSTRING(p.descripcion,1,80) AS nombrepractica, r.importe, c.nombre FROM resoluciondetalle r, resolucioncabecera c, practicas p WHERE (p.codigopractica like '%$busqueda%' OR p.descripcion like '%$busqueda%') AND r.idresolucion = c.id AND r.idpractica = p.idpractica";
		$resLeePracticasResolucion=mysql_query($sqlLeePracticasResolucion,$db);
		if(mysql_num_rows($resLeePracticasResolucion)!=0) {
			while($rowLeePracticasResolucion=mysql_fetch_array($resLeePracticasResolucion)) {
				$fechainiresolucion=$rowLeePracticasResolucion['fechadesde'];
				if($rowLeePracticasResolucion['fechahasta']==NULL) {
					$fechafinresolucion=date("Y-m-d");
				} else {
					$fechafinresolucion=$rowLeePracticasResolucion['fechahasta'];
				}
				if(strcmp($fechainiresolucion, $fechaprestacion) <= 0) {
					if(strcmp($fechafinresolucion, $fechaprestacion) >= 0) {

						if($rowLeePracticasResolucion['importe']>=0.00) {
							$noencontro = FALSE;
							$prestaciones[] = array(
								'label' => $rowLeePracticasResolucion['nombrepractica'].' | Codigo: '.$rowLeePracticasResolucion['codigopractica'].' | Valor: '.$rowLeePracticasResolucion['importe'].' | Origen: Resolucion '.$rowLeePracticasResolucion['nombre'],
								'idpractica' => $rowLeePracticasResolucion['idpractica'],
								'valor' => $rowLeePracticasResolucion['importe']
							);
						}
					}
				}
			}
		}
	}
	$sqlConsultaNomenclador = "SELECT codigonomenclador FROM prestadornomenclador WHERE codigoprestador = $idprestador AND codigonomenclador != 7";
	$resConsultaNomenclador = mysql_query($sqlConsultaNomenclador,$db);
	if(mysql_num_rows($resConsultaNomenclador)!=0) {
		$wherein = '';
		while($rowConsultaNomenclador = mysql_fetch_array($resConsultaNomenclador)) {
			$wherein .= $rowConsultaNomenclador['codigonomenclador'].',';
		}
		$wherein = substr($wherein, 0, -1);
	}
	$sqlLeePracticasNomenclador="SELECT p.idpractica, p.codigopractica, SUBSTRING(p.descripcion,1,80) AS nombrepractica, n.nombre FROM practicas p, nomencladores n WHERE p.nomenclador IN($wherein) AND (p.codigopractica like '%$busqueda%' OR p.descripcion like '%$busqueda%') AND p.nomenclador = n.id";
	$resLeePracticasNomenclador=mysql_query($sqlLeePracticasNomenclador,$db);
	if(mysql_num_rows($resLeePracticasNomenclador)!=0) {
		while($rowLeePracticasNomenclador = mysql_fetch_array($resLeePracticasNomenclador)) {
			$noencontro = FALSE;
			$prestaciones[] = array(
				'label' => $rowLeePracticasNomenclador['nombrepractica'].' | Codigo: '.$rowLeePracticasNomenclador['codigopractica'].' | NO VALORIZADA  | Origen: Nomenclador '.$rowLeePracticasNomenclador['nombre'],
				'idpractica' => $rowLeePracticasNomenclador['idpractica'],
				'valor' => '0.00',
			);
		}
	}
	if($noencontro) {
		$prestaciones[] = array(
			'label' => 'No se encontraron resultados para la busqueda intentada',
			'idpractica' => NULL,
			'valor' => NULL,
		);
	}
	echo json_encode($prestaciones);
	return; 
}
?>