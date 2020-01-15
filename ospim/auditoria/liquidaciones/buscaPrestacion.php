<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
if(isset($_GET)) {
	$busqueda = $_GET['getPrestacion'];
	$idprestador = $_GET['idPrestador'];
	$fechaprestacion = fechaParaGuardar($_GET['fechaPrestacion']);
	$tienecontrato = $_GET['contratoPrestador'];
	$tieneresolucion =  $_GET['nomencladorResolucion'];
	$noencontro = TRUE;
	$prestaciones = array();
	set_time_limit(0);
	if($tienecontrato == 1) {
		$sqlLeeContratoRelacionado="SELECT idcontratotercero FROM cabcontratoprestador WHERE codigoprestador = $idprestador AND fechainicio <= '$fechaprestacion' AND (fechafin >= '$fechaprestacion' OR fechafin IS NULL ) AND idcontratotercero != 0";
		$resLeeContratoRelacionado=mysql_query($sqlLeeContratoRelacionado,$db);
		if(mysql_num_rows($resLeeContratoRelacionado)==1) {
			$rowLeeContratoRelacionado=mysql_fetch_array($resLeeContratoRelacionado);
			$sqlLeeContratoPadre="SELECT codigoprestador FROM cabcontratoprestador WHERE idcontrato = $rowLeeContratoRelacionado[idcontratotercero]";
			$resLeeContratoPadre=mysql_query($sqlLeeContratoPadre,$db);
			if(mysql_num_rows($resLeeContratoPadre)==1) {
				$rowLeeContratoPadre=mysql_fetch_array($resLeeContratoPadre);
				$idprestador = $rowLeeContratoPadre['codigoprestador'];
			}
		}
		$sqlLeePracticasContrato="SELECT c.idcontrato, c.codigoprestador, c.fechainicio, c.fechafin, d.idpractica, p.codigopractica, SUBSTRING(p.descripcion,1,45) AS nombrepractica, p.internacion, j.codigocomplejidad, j.descripcion AS complejidad, d.idcategoria, t.descripcion AS nombrecategoria, d.moduloconsultorio, d.modulourgencia, ROUND((d.galenohonorario*p.unihonorario),2) AS honorario, ROUND((d.galenohonorarioespecialista*p.unihonorarioespecialista),2) AS especialista, ROUND((d.galenohonorarioayudante*p.unihonorarioayudante),2) AS ayudante, ROUND((d.galenohonorarioanestesista*p.unihonorarioanestesista),2) AS anestesista, ROUND((d.galenogastos*p.unigastos),2) AS gastos, d.coseguro, (ROUND((d.galenohonorario*p.unihonorario),2)+ROUND((d.galenohonorarioespecialista*p.unihonorarioespecialista),2)+ROUND((d.galenohonorarioayudante*p.unihonorarioayudante),2)+ROUND((d.galenohonorarioanestesista*p.unihonorarioanestesista),2)+ROUND((d.galenogastos*p.unigastos),2)) AS valorgaleno FROM cabcontratoprestador c, detcontratoprestador d, practicas p, practicascategorias t, tipocomplejidad j WHERE c.codigoprestador = $idprestador AND (p.codigopractica like '%$busqueda%' OR p.descripcion like '%$busqueda%') AND c.idcontrato = d.idcontrato AND d.idpractica = p.idpractica AND d.idcategoria = t.id AND p.codigocomplejidad = j.codigocomplejidad";
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
						if($rowLeePracticasContrato['moduloconsultorio']>0.00) {
							$noencontro = FALSE;
							$nombrepractica = utf8_encode($rowLeePracticasContrato['nombrepractica']);
							$prestaciones[] = array(
								'label' => $nombrepractica.' | Codigo: '.$rowLeePracticasContrato['codigopractica'].' | Mod. Consultorio Valor: '.$rowLeePracticasContrato['moduloconsultorio'].' | Origen: Contrato '.$rowLeePracticasContrato['idcontrato'].' | Categoria: '.$rowLeePracticasContrato['nombrecategoria'].' | Clasif. Res. 650: '.$rowLeePracticasContrato['complejidad'],
								'idpractica' => $rowLeePracticasContrato['idpractica'],
								'valor' => $rowLeePracticasContrato['moduloconsultorio'],
								'galeno' => 0,
								'honorario' => NULL,
								'especialista' => NULL,
								'ayudante' => NULL,
								'anestesista' => NULL,
								'gastos' => NULL,
								'coseguro' => $rowLeePracticasContrato['coseguro'],
								'integracion' => 0,
								'complejidad' => $rowLeePracticasContrato['codigocomplejidad'],
								'internacion' => $rowLeePracticasContrato['internacion'],
							);
						}
						if($rowLeePracticasContrato['modulourgencia']>0.00) {
							$noencontro = FALSE;
							$nombrepractica = utf8_encode($rowLeePracticasContrato['nombrepractica']);
							$prestaciones[] = array(
								'label' => $nombrepractica.' | Codigo: '.$rowLeePracticasContrato['codigopractica'].' | Mod. Urgencia Valor: '.$rowLeePracticasContrato['modulourgencia'].' | Origen: Contrato '.$rowLeePracticasContrato['idcontrato'].' | Categoria: '.$rowLeePracticasContrato['nombrecategoria'].' | Clasif. Res. 650: '.$rowLeePracticasContrato['complejidad'],
								'idpractica' => $rowLeePracticasContrato['idpractica'],
								'valor' => $rowLeePracticasContrato['modulourgencia'],
								'galeno' => 0,
								'honorario' => NULL,
								'especialista' => NULL,
								'ayudante' => NULL,
								'anestesista' => NULL,
								'gastos' => NULL,
								'coseguro' => $rowLeePracticasContrato['coseguro'],
								'integracion' => 0,
								'complejidad' => $rowLeePracticasContrato['codigocomplejidad'],
								'internacion' => $rowLeePracticasContrato['internacion'],
							);
						}
						if($rowLeePracticasContrato['valorgaleno']>0.00) {
							$noencontro = FALSE;
							$nombrepractica = utf8_encode($rowLeePracticasContrato['nombrepractica']);
							$prestaciones[] = array(
								'label' => $nombrepractica.' | Codigo: '.$rowLeePracticasContrato['codigopractica'].' | Galeno Valor: '.$rowLeePracticasContrato['valorgaleno'].' | Origen: Contrato '.$rowLeePracticasContrato['idcontrato'].' | Categoria: '.$rowLeePracticasContrato['nombrecategoria'].' | Clasif. Res. 650: '.$rowLeePracticasContrato['complejidad'],
								'idpractica' => $rowLeePracticasContrato['idpractica'],
								'valor' => $rowLeePracticasContrato['valorgaleno'],
								'galeno' => 1,
								'honorario' => $rowLeePracticasContrato['honorario'],
								'especialista' => $rowLeePracticasContrato['especialista'],
								'ayudante' => $rowLeePracticasContrato['ayudante'],
								'anestesista' => $rowLeePracticasContrato['anestesista'],
								'gastos' => $rowLeePracticasContrato['gastos'],
								'coseguro' => $rowLeePracticasContrato['coseguro'],
								'integracion' => 0,
								'complejidad' => $rowLeePracticasContrato['codigocomplejidad'],
								'internacion' => $rowLeePracticasContrato['internacion'],
							);
						}
					}
				}
			}
		}
	}

	if($tieneresolucion != 0) {
		$sqlLeePracticasResolucion="SELECT n.fechainicio, n.fechafin, p.idpractica, p.codigopractica, SUBSTRING(p.descripcion,1,45) AS nombrepractica, p.internacion, j.codigocomplejidad, j.descripcion AS complejidad, r.modulo, n.nombre, CASE n.idnomenclador WHEN 7 THEN '1' ELSE '0' END AS integracion FROM practicasvaloresresolucion r, nomencladoresresolucion n, practicas p, tipocomplejidad j WHERE (p.codigopractica like '%$busqueda%' OR p.descripcion like '%$busqueda%') AND r.idresolucion = n.id AND n.idnomenclador = $tieneresolucion AND r.idpractica = p.idpractica AND p.codigocomplejidad = j.codigocomplejidad";
		$resLeePracticasResolucion=mysql_query($sqlLeePracticasResolucion,$db);
		if(mysql_num_rows($resLeePracticasResolucion)!=0) {
			while($rowLeePracticasResolucion=mysql_fetch_array($resLeePracticasResolucion)) {
				$fechainiresolucion=$rowLeePracticasResolucion['fechainicio'];
				if($rowLeePracticasResolucion['fechafin']==NULL) {
					$fechafinresolucion=date("Y-m-d");
				} else {
					$fechafinresolucion=$rowLeePracticasResolucion['fechafin'];
				}
				if(strcmp($fechainiresolucion, $fechaprestacion) <= 0) {
					if(strcmp($fechafinresolucion, $fechaprestacion) >= 0) {
						if($rowLeePracticasResolucion['modulo']>0.00) {
							$noencontro = FALSE;
							$nombrepractica = utf8_encode($rowLeePracticasResolucion['nombrepractica']);
							$prestaciones[] = array(
								'label' => $nombrepractica.' | Codigo: '.$rowLeePracticasResolucion['codigopractica'].' | Valor: '.$rowLeePracticasResolucion['modulo'].' | Origen: Resolucion '.$rowLeePracticasResolucion['nombre'].' | NO CATEGORIZA | Clasif. Res. 650: '.$rowLeePracticasResolucion['complejidad'],
								'idpractica' => $rowLeePracticasResolucion['idpractica'],
								'valor' => $rowLeePracticasResolucion['modulo'],
								'galeno' => 0,
								'honorario' => NULL,
								'especialista' => NULL,
								'ayudante' => NULL,
								'anestesista' => NULL,
								'gastos' => NULL,
								'coseguro' => 0.00,
								'integracion' => $rowLeePracticasResolucion['integracion'],
								'complejidad' => $rowLeePracticasResolucion['codigocomplejidad'],
								'internacion' => $rowLeePracticasResolucion['internacion'],
							);
						}
					}
				}
			}
		}
	}

	if($tienecontrato == 0 && $tieneresolucion == 0) {
		$sqlConsultaNomenclador = "SELECT codigonomenclador FROM prestadornomenclador WHERE codigoprestador = $idprestador AND codigonomenclador != 7";
		$resConsultaNomenclador = mysql_query($sqlConsultaNomenclador,$db);
		if(mysql_num_rows($resConsultaNomenclador)!=0) {
			$wherein = '';
			while($rowConsultaNomenclador = mysql_fetch_array($resConsultaNomenclador)) {
				$wherein .= $rowConsultaNomenclador['codigonomenclador'].',';
			}
			$wherein = substr($wherein, 0, -1);
		}
		$sqlLeePracticasNomenclador="SELECT p.idpractica, p.codigopractica, SUBSTRING(p.descripcion,1,45) AS nombrepractica, j.codigocomplejidad, j.descripcion AS complejidad, p.internacion, n.nombre FROM practicas p, nomencladores n, tipocomplejidad j WHERE p.nomenclador IN($wherein) AND (p.codigopractica like '%$busqueda%' OR p.descripcion like '%$busqueda%') AND p.nomenclador = n.id AND p.codigocomplejidad = j.codigocomplejidad";
		$resLeePracticasNomenclador=mysql_query($sqlLeePracticasNomenclador,$db);
		if(mysql_num_rows($resLeePracticasNomenclador)!=0) {
			while($rowLeePracticasNomenclador = mysql_fetch_array($resLeePracticasNomenclador)) {
				$noencontro = FALSE;
				$nombrepractica = utf8_encode($rowLeePracticasNomenclador['nombrepractica']);
				$valornomencladoretiqueta = 'NO VALORIZA';
				$prestaciones[] = array(
					'label' => $nombrepractica.' | Codigo: '.$rowLeePracticasNomenclador['codigopractica'].' | '.$valornomencladoretiqueta.'  | Origen: Nomenclador '.$rowLeePracticasNomenclador['nombre'].' | NO CATEGORIZA | Clasif. Res. 650: '.$rowLeePracticasNomenclador['complejidad'],
					'idpractica' => $rowLeePracticasNomenclador['idpractica'],
					'valor' => NULL,
					'galeno' => 0,
					'honorario' => NULL,
					'especialista' => NULL,
					'ayudante' => NULL,
					'anestesista' => NULL,
					'gastos' => NULL,
					'coseguro' => 0.00,
					'integracion' => 0,
					'complejidad' => $rowLeePracticasNomenclador['codigocomplejidad'],
					'internacion' => $rowLeePracticasNomenclador['internacion'],
				);
			}
		}
	}

	if($noencontro) {
		$prestaciones[] = array(
			'label' => 'No se encontraron resultados para la busqueda intentada',
			'idpractica' => NULL,
			'valor' => NULL,
			'galeno' => 0,
			'honorario' => NULL,
			'especialista' => NULL,
			'ayudante' => NULL,
			'anestesista' => NULL,
			'gastos' => NULL,
			'coseguro' => 0.00,
			'integracion' => 0,
			'complejidad' => NULL,
			'internacion' => 0,
		);
	}
	echo json_encode($prestaciones);
	return; 
}
?>