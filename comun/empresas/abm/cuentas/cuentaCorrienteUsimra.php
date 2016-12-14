<?php

$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionUsimra.php");
include ($libPath . "fechas.php");
$cuit = $_GET ['cuit'];
include ($libPath . "cabeceraEmpresaConsulta.php");
$fechaInicio = $row ['iniobliusi'];
include ($libPath . "limitesTemporalesEmpresasUsimra.php");
set_time_limit ( 0 );
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {
	text-decoration: none;
	color: #0033FF
}

A:visited {
	text-decoration: none
}

A:hover {
	text-decoration: none;
	color: #00FFFF
}
</style>
<style type="text/css" media="print">
.nover {
	display: none
}
</style>

<style type="text/css">
<!--
.Estilo6 {
	font-size: 12px;
	font-weight: bold;
}

.Estilo7 {
	font-size: 14px
}
-->
</style>
<script language="javascript">
function abrirInfo(dire) {
	a= window.open(dire,"InfoPeriodoCuentaCorrienteEmpresa",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

function abrirInfoCuotas(dire) {
	a= window.open(dire,"InfoCuotasCuentaCorrienteEmpresa",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}


</script>
</head>
<?php
function estaVencido($fechaPago, $me, $ano) {
	if ($me == 12) {
		$mesvto = 1;
		$anovto = $ano + 1;
	} else {
		$mesvto = $me + 1;
		$anovto = $ano;
	}
	if ($mesvto < 10) {
		$mesvto = "0" . $mesvto;
	}
	$diavto = 15;
	$fechaStr = $anovto . '-' . $mesvto . '-' . $diavto;
	if (strcmp ( $fechaPago, $fechaStr ) > 0) {
		return (1);
	}
	return (0);
}

function reverificaPeriodo($estado, $ano, $me, $db) {
	global $cuit;
	global $arrayAcuerdos, $arrayJuicios, $arrayRequerimientos;
	
	$idArray = $ano . $me;
	// VEO LOS PERIODOS ABARCADOS POR ACUERDO
	if (array_key_exists ( $idArray, $arrayAcuerdos )) {
		$nroacuerdo = $arrayAcuerdos[$idArray]['nroacuerdo'];
		if ($arrayAcuerdos[$idArray]['estadoacuerdo'] == 0) {
			$des = "P. ACUER.-" . $nroacuerdo;
		} else {
			$des = "ACUER.-" . $nroacuerdo;
		}
		return ($des);
	} else {
		// VEO LOS JUICIOS
		if (array_key_exists ( $idArray, $arrayJuicios )) {
			$statusDeuda = $arrayJuicios[$idArray]['statusdeuda'];
			$nrocertificado = $arrayJuicios[$idArray]['nrocertificado'];
			$nroorden = $rowJuicio ['nroorden'];
			if ($statusDeuda == 1) {
				$des = "J.EJEC";
			}
			if ($statusDeuda == 2) {
				$des = "J.CONV";
			}
			if ($statusDeuda == 3) {
				$des = "J.QUIEB";
			}
			$des = $des . " (" . $nrocertificado . ")-" . $nroorden;
			return ($des);
		} else {
			// VEO LOS REQ DE FISC
			if (array_key_exists ( $idArray, $arrayRequerimientos )) {
				$nroreq = $arrayRequerimientos[$idArray]['nrorequerimiento'];
				$des = "REQ. (" . $nroreq . ")";
				return ($estado . "<br>" . $des);
			} // IF REQUERMINETOS
		} // ELSE JUICIOS
	} // ELSE ACUERDOS
	return ($estado);
}

function encuentroPagos($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	// CAMBIAR A USIMRA
	$sqlPagos = "select anopago, mespago, fechapago, remuneraciones, montopagado from seguvidausimra where cuit = $cuit and ((anopago > $anoinicio and anopago <= $anofin) or (anopago = $anoinicio and mespago >= $mesinicio)) group by anopago, mespago, fechapago";
	$resPagos = mysql_query ( $sqlPagos, $db );
	$CantPagos = mysql_num_rows ( $resPagos );
	if ($CantPagos > 0) {
		while ( $rowPagos = mysql_fetch_assoc ( $resPagos ) ) {
			$id = $rowPagos ['anopago'] . $rowPagos ['mespago'];
			$arrayPagos [$id] = array (
					'anio' => $rowPagos ['anopago'],
					'mes' => $rowPagos ['mespago'],
					'fechapago' => $rowPagos ['fechapago'],
					'remuneraciones' => $rowPagos ['remuneraciones'],
					'montopagado' => $rowPagos ['montopagado'] 
			);
		}
		$resPagos = array ();
		foreach ( $arrayPagos as $pago ) {
			$id = $pago ['anio'] . $pago ['mes'];
			$pagoExacto = $pago ['remuneraciones'] * 0.031;
			$diferencia = $pagoExacto - $pago ['montopagado'];
			if ($diferencia < - 1 || $diferencia > 1) {
				$resPagos [$id] = array (
						'anio' => $pago ['anio'],
						'mes' => $pago ['mes'],
						'estado' => 'P.M.' 
				);
			} else {
				if (estaVencido ( $pago ['fechapago'], $pago ['mes'], $pago ['anio'] )) {
					$resPagos [$id] = array (
							'anio' => $pago ['anio'],
							'mes' => $pago ['mes'],
							'estado' => 'P.F.T.' 
					);
				} else {
					$resPagos [$id] = array (
							'anio' => $pago ['anio'],
							'mes' => $pago ['mes'],
							'estado' => 'PAGO' 
					);
				}
			}
		}
		return ($resPagos);
	} else {
		return (0);
	}
}

function encuentroPagosExtraor($db, &$arrayPagos) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlPagosExt = "SELECT s.anopago, s.mespago, e.relacionmes, s.fechapago, s.remuneraciones, s.montopagado
					FROM seguvidausimra s, extraordinariosusimra e
					WHERE s.cuit = $cuit and s.anopago = e.anio and s.mespago = e.mes and
					 ((s.anopago > $anoinicio and s.anopago <= $anofin) or (s.anopago = $anoinicio and e.relacionmes >= $mesinicio))
					group by anopago, mespago, fechapago";
	$resPagosExt = mysql_query ( $sqlPagosExt, $db );
	$canPagosExt = mysql_num_rows ( $resPagosExt );
	if ($canPagosExt > 0) {
		while ( $rowPagosExt = mysql_fetch_assoc ( $resPagosExt ) ) {
			$id = $rowPagosExt ['anopago'] . $rowPagosExt ['relacionmes'];
			$arrayPagos [$id] ['estado'] = $arrayPagos [$id] ['estado'] . " | NR";
		}
	}
}

function encuentroPagosAnteriores($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlPagosAnteriores = "select anoanterior, mesanterior, mespago, anopago from periodosanterioresusimra where cuit = $cuit and ((anoanterior > $anoinicio and anoanterior <= $anofin) or (anoanterior = $anoinicio and mesanterior >= $mesinicio))";
	$resPagosAnteriores = mysql_query ( $sqlPagosAnteriores, $db );
	$canPagosAnteriores = mysql_num_rows ( $resPagosAnteriores );
	if ($canPagosAnteriores > 0) {
		while ( $rowPagosAnteriores = mysql_fetch_assoc ( $resPagosAnteriores ) ) {
			$id = $rowPagosAnteriores ['anoanterior'] . $rowPagosAnteriores ['mesanterior'];
			$arrayPagosAnteriores [$id] = array (
					'mespago' => ( int ) $rowPagosAnteriores ['mespago'],
					'anopago' => ( int ) $rowPagosAnteriores ['anopago']
			);
		}
		if ($arrayPagosAnteriores != 0) {
			return ($arrayPagosAnteriores);
		} else {
			return (0);
		}
	} else {
		return (0);
	}
}

function encuentroAcuerdos($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlAcuerdos = "select anoacuerdo, mesacuerdo, nroacuerdo from detacuerdosusimra where cuit = $cuit and ((anoacuerdo > $anoinicio and anoacuerdo <= $anofin) or (anoacuerdo = $anoinicio and mesacuerdo >= $mesinicio)) group by anoacuerdo, mesacuerdo order by anoacuerdo, mesacuerdo";
	// print($sqlAcuerdos);
	$resAcuerdos = mysql_query ( $sqlAcuerdos, $db );
	$canAcuerdos = mysql_num_rows ( $resAcuerdos );
	if ($canAcuerdos > 0) {
		while ( $rowAcuerdos = mysql_fetch_assoc ( $resAcuerdos ) ) {
			$id = $rowAcuerdos ['anoacuerdo'] . $rowAcuerdos ['mesacuerdo'];
			$arrayAcuerdos [$id] = array (
					'anio' => ( int ) $rowAcuerdos ['anoacuerdo'],
					'mes' => ( int ) $rowAcuerdos ['mesacuerdo'],
					'nroacuerdo' => $rowAcuerdos ['nroacuerdo'] 
			);
		}
	} else {
		return 0;
	}
	return ($arrayAcuerdos);
}

function encuentroJuicios($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlJuicios = "select d.anojuicio, d.mesjuicio, c.nrocertificado, c.statusdeuda, c.nroorden from cabjuiciosusimra c, detjuiciosusimra d  where c.cuit = $cuit and c.nroorden = d.nroorden and ((d.anojuicio > $anoinicio and d.anojuicio <= $anofin) or (d.anojuicio = $anoinicio and d.mesjuicio >= $mesinicio)) group by d.anojuicio, d.mesjuicio order by d.anojuicio, d.mesjuicio";
	// print($sqlJuicios);
	$resJuicios = mysql_query ( $sqlJuicios, $db );
	$canJuicios = mysql_num_rows ( $resJuicios );
	if ($canJuicios > 0) {
		while ( $rowJuicios = mysql_fetch_assoc ( $resJuicios ) ) {
			$id = $rowJuicios ['anojuicio'] . $rowJuicios ['mesjuicio'];
			$arrayJuicios [$id] = array (
					'anio' => ( int ) $rowJuicios ['anojuicio'],
					'mes' => ( int ) $rowJuicios ['mesjuicio'],
					'nrocertificado' => $rowJuicios ['nrocertificado'],
					'statusdeuda' => $rowJuicios ['statusdeuda'],
					'nroorden' => $rowJuicios ['nroorden'] 
			);
		}
	} else {
		return 0;
	}
	return ($arrayJuicios);
}

function encuentroRequerimientos($db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	$sqlRequerimientos = "select d.anofiscalizacion, d.mesfiscalizacion, r.nrorequerimiento from reqfiscalizusimra r, detfiscalizusimra d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and ((d.anofiscalizacion > $anoinicio and d.anofiscalizacion <= $anofin) or (d.anofiscalizacion = $anoinicio and d.mesfiscalizacion >= $mesinicio)) group by d.anofiscalizacion, d.mesfiscalizacion order by d.anofiscalizacion, d.mesfiscalizacion";
	// print($sqlRequerimientos);
	$resRequerimientos = mysql_query ( $sqlRequerimientos, $db );
	$canRequerimientos = mysql_num_rows ( $resRequerimientos );
	if ($canRequerimientos > 0) {
		while ( $rowRequerimientos = mysql_fetch_assoc ( $resRequerimientos ) ) {
			$id = $rowRequerimientos ['anofiscalizacion'] . $rowRequerimientos ['mesfiscalizacion'];
			$arrayRequerimientos [$id] = array (
					'anio' => ( int ) $rowRequerimientos ['anofiscalizacion'],
					'mes' => ( int ) $rowRequerimientos ['mesfiscalizacion'],
					'nrorequerimiento' => ( int ) $rowRequerimientos ['nrorequerimiento'] 
			);
		}
	} else {
		return 0;
	}
	return ($arrayRequerimientos);
}

function encuentroDdjj($db) {
	global $cuit, $anoinicio, $anofin;
	$sqlDdjj = "select perano, permes from ddjjusimra where nrcuit = $cuit and nrcuil = 99999999999 and perano >= $anoinicio and perano <= $anofin order by perano, permes, id ASC";
	$resDdjj = mysql_query ( $sqlDdjj, $db );
	$canDdjj = mysql_num_rows ( $resDdjj );
	if ($canDdjj > 0) {
		while ( $rowDdjj = mysql_fetch_assoc ( $resDdjj ) ) {
			$id = $rowDdjj ['perano'] . $rowDdjj ['permes'];
			$montopagar = $rowDdjj ['totapo'] + $rowDdjj ['recarg'];
			$arrayDdjj [$id] = array (
					'anio' => ( int ) $rowDdjj ['perano'],
					'mes' => ( int ) $rowDdjj ['permes'] 
			);
		}
	}
	
	$sqlDdjjValidas = "select anoddjj, mesddjj from cabddjjusimra where cuit = $cuit and anoddjj >= $anoinicio and anoddjj <= $anofin order by anoddjj, mesddjj, id ASC";
	$resDdjjValidas = mysql_query ( $sqlDdjjValidas, $db );
	$canDdjjValidas = mysql_num_rows ( $resDdjj );
	if ($canDdjjValidas > 0) {
		while ( $rowDdjjValidas = mysql_fetch_assoc ( $resDdjjValidas ) ) {
			$id = $rowDdjjValidas ['anoddjj'] . $rowDdjjValidas ['mesddjj'];
			$montopagar = $rowDdjjValidas ['totalaporte'] + $rowDdjjValidas ['recargo'];
			$arrayDdjj [$id] = array (
					'anio' => ( int ) $rowDdjjValidas ['anoddjj'],
					'mes' => ( int ) $rowDdjjValidas ['mesddjj'] 
			);
		}
	}
	
	if (sizeof ( $arrayDdjj ) == 0) {
		return 0;
	}
	return ($arrayDdjj);
}

function estado($ano, $me, $db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	global $arrayPagosAnteriores, $arrayAcuerdos, $arrayJuicios, $arrayRequerimientos, $arrayDdjj;
	// VEO QUE EL MES Y EL AÑO ESTEND DENTRO DE LOS PERIODOS A MOSTRAR
	if ($ano == $anoinicio) {
		if ($me < $mesinicio) {
			$des = "-";
			return ($des);
		}
	}
	if ($ano == $anofin) {
		if ($me > $mesfin) {
			$des = "-";
			return ($des);
		}
	}
	
	$idArray = $ano . $me;
	// VEO PAGO DIFERENCIADO ANTES DE ACUERDOS
	if (array_key_exists ( $idArray, $arrayPagosAnteriores )) {
		$perPago = $arrayPagosAnteriores [$idArray] ['mespago'] . "-" . $arrayPagosAnteriores [$idArray] ['anopago'];
		$des = "P. DIF. <br/>(" . $perPago . ")";
	} else {
		// VEO LOS PERIODOS ABARCADOS POR ACUERDO
		if (array_key_exists ( $idArray, $arrayAcuerdos )) {
			$nroacuerdo = $arrayAcuerdos [$idArray] ['nroacuerdo'];
			if ($arrayAcuerdos [$idArray] ['estadoacuerdo'] == 0) {
				$des = "P. ACUER.-" . $nroacuerdo;
			} else {
				$des = "ACUER.-" . $nroacuerdo;
			}
		} else {
			// VEO LOS JUICIOS
			if (array_key_exists ( $idArray, $arrayJuicios )) {
				$statusDeuda = $arrayJuicios [$idArray] ['statusdeuda'];
				$nrocertificado = $arrayJuicios [$idArray] ['nrocertificado'];
				$nroorden = $arrayJuicios [$idArray] ['nroorden'];
				if ($statusDeuda == 1) {
					$des = "J.EJEC";
				}
				if ($statusDeuda == 2) {
					$des = "J.CONV";
				}
				if ($statusDeuda == 3) {
					$des = "J.QUIEB";
				}
				$des = $des . " (" . $nrocertificado . ")-" . $nroorden;
			} else {
				// VEO LAS DDJJ REALIZADAS SIN PAGOS
				if (array_key_exists ( $idArray, $arrayDdjj )) {
					$des = "NO PAGO";
				} else {
					// NO HAY DDJJ SIN PAGOS
					$des = "S.DJ.";
				} // else DDJJ
			} // else JUICIOS
		} // else ACUERDOS
	} // else PAGO DIF
	return $des;
} // function

function imprimeTabla($periodo) {
	global $cuit;
	$estado = $periodo ['estado'];
	$ano = $periodo ['anio'];
	$me = $periodo ['mes'];
	print ("<td>") ;
	if ($estado == 'NO PAGO') {
		print ("<a href=javascript:abrirInfo('detalleDDJJUsimra.php?origen=" . $_GET ['origen'] . "&cuit=" . $cuit . "&anio=" . $ano . "&mes=" . $me . "')>" . $estado . "</a>") ;
	} else {
		if (strpos ( $estado, 'P.F.T.' ) !== false or strpos ( $estado, 'PAGO' ) !== false or strpos ( $estado, 'P.M.' ) !== false) {
			print ("<a href=javascript:abrirInfo('detallePagosUsimra.php?origen=" . $_GET ['origen'] . "&cuit=" . $cuit . "&anio=" . $ano . "&mes=" . $me . "')>" . $estado . "</a>") ;
		} else {
			$pacuerdo = explode ( '-', $estado );
			if ($pacuerdo [0] == 'P. ACUER.' or $pacuerdo [0] == 'ACUER.') {
				print ("<a href=javascript:abrirInfo('/madera/usimra/acuerdos/abm/consultaAcuerdo.php?cuit=" . $cuit . "&nroacu=" . $pacuerdo [1] . "&origen=empresa')>" . $estado . "</a>") ;
			} else {
				$juicioEstado = explode ( '-', $estado );
				$pjuicio = explode ( '(', $juicioEstado [0] );
				if ($pjuicio [0] == 'J.CONV ' or $pjuicio [0] == 'J.QUIEB ' or $pjuicio [0] == 'J.EJEC ') {
					$nroorden = $juicioEstado [1];
					print ("<a href=javascript:abrirInfo('/madera/usimra/legales/juicios/consultaJuicio.php?cuit=" . $cuit . "&nroorden=" . $nroorden . "&origen=empresa')>" . $juicioEstado [0] . "</a>") ;
				} else {
					print ($estado) ;
				}
			}
		}
	}
	print ("</td>") ;
}

?>
<title>.: Cuenta Corriente Empresa :.</title>
<body bgcolor="#B2A274">
	<div align="center">
	<?php if ($tipo == "activa") { ?>
			<input type="reset" class="nover" name="volver" value="Volver"
			onClick="location.href = '../empresa.php?origen=usimra&cuit=<?php echo $cuit ?>'" /> 
	<?php } else { ?>
			<input type="reset" class="nover" name="volver" value="Volver"
			onClick="location.href = '../empresaBaja.php?origen=usimra&cuit=<?php echo $cuit ?>'" /> 
	<?php } ?>
	 <p>
    <?php include ($libPath . "cabeceraEmpresa.php"); ?>
  </p>
		<p>
			<strong>Cuenta Corriente </strong>
		</p>
		<p>
			<strong>Inicio Actividad: <?php echo invertirFecha($fechaInicio) ?></strong>
		</p>
  	<?php if ($tipo == "baja") {?>
   		<p>
			<strong>Fecha Baja Empresa: <?php echo invertirFecha($fechaBaja) ?></strong>
		</p>
	<?php } ?>	
	
<table width="1132" border="1" style="text-align: center; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px">
			<tr>
				<td width='40' rowspan="2"><span class="Estilo6">A&Ntilde;OS</span></td>
				<td colspan="12"><span class="Estilo6">MESES</span></td>
			</tr>
			<tr>
				<td width="91" class="Estilo6">Enero</td>
				<td width="91" class="Estilo6">Febrero</td>
				<td width="91" class="Estilo6">Marzo</td>
				<td width="91" class="Estilo6">Abril</td>
				<td width="91" class="Estilo6">Mayo</td>
				<td width="91" class="Estilo6">Junio</td>
				<td width="91" class="Estilo6">Julio</td>
				<td width="91" class="Estilo6">Agosto</td>
				<td width="91" class="Estilo6">Setiembre</td>
				<td width="91" class="Estilo6">Octubre</td>
				<td width="91" class="Estilo6">Noviembre</td>
				<td width="91" class="Estilo6">Diciembre</td>
			</tr>
<?php

$arrayPagos = encuentroPagos ( $db );
if ($arrayPagos == 0) {
	$arrayPagos = array ();
}
encuentroPagosExtraor ( $db, $arrayPagos );

$arrayPagosAnteriores = encuentroPagosAnteriores ( $db );
if ($arrayPagosAnteriores == 0) {
	$arrayPagosAnteriores = array ();
}
$arrayAcuerdos = encuentroAcuerdos ( $db );
if ($arrayAcuerdos == 0) {
	$arrayAcuerdos = array ();
}
$arrayJuicios = encuentroJuicios ( $db );
if ($arrayJuicios == 0) {
	$arrayJuicios = array ();
}
$arrayRequerimientos = encuentroRequerimientos ( $db );
if ($arrayRequerimientos == 0) {
	$arrayRequerimientos = array ();
}
$arrayDdjj = encuentroDdjj ( $db );
if ($arrayDdjj == 0) {
	$arrayDdjj = array ();
}

while ( $ano <= $anofin ) { ?>
  	<tr><td><strong><?php echo $ano ?></strong></td>
<?php
for($i = 1; $i < 13; $i ++) {
		$idArray = $ano . $i;
		if (! array_key_exists ( $idArray, $arrayPagos )) {
			$estado = estado ( $ano, $i, $db );
			if ($estado == 'NO PAGO' || $estado == 'S.DJ.') {
				$resultado = reverificaPeriodo ( $estado, $ano, $i, $db );
			} else {
				$resultado = $estado;
			}
			$arrayPagos [$idArray] = array ('anio' => $ano,'mes' => $i,'estado' => $resultado );
		} else {
			$estado = $arrayPagos [$idArray] ['estado'];
			$resultado = reverificaPeriodo ( $estado, $ano, $i, $db );
			$arrayPagos [$idArray] = array ('anio' => $ano,'mes' => $i,'estado' => $resultado );
		}
		imprimeTabla ( $arrayPagos [$idArray] );
	} ?>
	</tr>
<?php
$ano ++;
} ?>
</table>
		<br>
		<table width="1130" border="0" style="font-size: 12px">
			<tr>
				<td>*PAGO = PAGO</td>
				<td>*P.F.T. = PAGO FUERA DE TERMINO</td>
				<td>*P.M.. = PAGO MENOR</td>
				<td>*X | NR = [PAGO | P.F.T. | P.M ] CON NO REMUNERATIVO</td>
			</tr>
			<tr>
				<td>*P. ACUER. = PAGO POR ACUERDO</td>
				<td>*P. DIF. (Per. Pago) = PAGO EN PERIODO POSTERIOR</td>
				<td>*ACUER. = EN ACUERDO</td>
				<td>*NO PAGO = NO PAGO CON DDJJ</td>
			</tr>
			<tr>
				<td>*S. DJ.= NO PAGO SIN DDJJ</td>
				<td>*REQ. (nro. req.) = FISCALIZADO</td>
				<td>*J.EJEC (nro. orden) = EN JUICIO EJECUCI&Oacute;N</td>
				<td>*J.CONV (nro. orden) = EN JUICIO CONVOCATORIA</td>
			</tr>
			<tr>
				<td>*J.QUIEB (nro. orden) = EN JUICIO QUIEBRA</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</table>
		<p><strong>Cuotas Excepcionales </strong></p>
		<table width="800" border="1"
			style="text-align: center; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px">
			<tr>
				<th>Cuota</th>
				<th>Fecha</th>
				<th>Personal</th>
				<th>Monto</th>
				<th>Recargo</th>
				<th>Total</th>
				<th>+Info</th>
			</tr>
	<?php   $sqlCuotasExcpecional = "SELECT e.mes, e.relacionmes, e.anio, e.mensaje, c.fechapago, c.cantidadaportantes, c.totalaporte, c.montorecargo, c.montopagado, p.descripcion
										FROM  cuotaextraordinariausimra c, extraordinariosusimra e, periodosusimra p 
										WHERE c.cuit = $cuit and e.anio = c.anopago and e.mes = c.mespago and e.anio = p.anio and e.mes = p.mes";
			$resCuotasExcpecional = mysql_query ( $sqlCuotasExcpecional, $db );
			$canCuotasExcpecional = mysql_num_rows ( $resCuotasExcpecional );
			if ($canCuotasExcpecional > 0) {
				while ( $rowCuotasExcpecional = mysql_fetch_assoc ( $resCuotasExcpecional ) ) { ?>
					<tr>
						<td><?php echo $rowCuotasExcpecional['relacionmes']."-". $rowCuotasExcpecional['anio']." | ".$rowCuotasExcpecional['mensaje']." - ".$rowCuotasExcpecional['descripcion']; ?></td>
						<td><?php echo invertirfecha($rowCuotasExcpecional['fechapago']) ?></td>
						<td><?php echo $rowCuotasExcpecional['cantidadaportantes'] ?></td>
						<td><?php echo $rowCuotasExcpecional['totalaporte'] ?></td>
						<td><?php echo $rowCuotasExcpecional['montorecargo'] ?></td>
						<td><?php echo $rowCuotasExcpecional['montopagado'] ?></td>
						<td><input type="button" value="DDJJ" onclick='javascript:abrirInfoCuotas("detalleCuotaUsimra.php?cuit=<?php echo $cuit?>&anio=<?php echo $rowCuotasExcpecional['anio'] ?>&mes=<?php echo $rowCuotasExcpecional['mes'] ?>")' /></td>
					</tr>
			<?php }
			} else { ?>
					<tr>
						<td colspan=7 align="center"><b>No tiene pagos de Cuotas Excepcionales</b></td>
					</tr>
		<?php  } ?>
		</table>
		<br>
		<input type="button" class="nover" name="imprimir" value="Imprimir" onClick="window.print();" />
	</div>
</body>
</html>
