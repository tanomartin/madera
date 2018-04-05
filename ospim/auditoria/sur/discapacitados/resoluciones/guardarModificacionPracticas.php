<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$idcabecera = $_GET['id'];
$arrayDatos = array();
$id = "";
$fechad = "";
$fechah = "";
$importe = "";

$sqlDelete = "DELETE FROM resoluciondetalle WHERE idresolucion = $idcabecera and idpractica in(";

foreach ($_POST as $key => $dato) {
	$pos = strpos($key, "id");
	if ($pos !== false) {
		$id = $dato;
		$sqlDelete .= $id.",";
	} else { 
		$pos = strpos($key, "fd");
		if ($pos !== false) {
			$fechad = fechaParaGuardar($dato);
		} else {
			$pos = strpos($key, "fh");
			if ($pos !== false) {
				if ($dato != "") {
					$fechah = fechaParaGuardar($dato);
				}
			} else {
				$pos = strpos($key, "imp");
				if ($pos !== false) {
					$importe = $dato;
					
					$arrayDatos[$id] = array('fechad' => $fechad, 'fechah' => $fechah ,'importeNuevo' => $importe);
					
					$id = "";
					$fechad = "";
					$fechah = "";
					$importe = "";
				}
			}
		}
	}
	
}
$sqlDelete = substr($sqlDelete, 0, -1);
$sqlDelete .= ");";
		
$arrayConflicto = array();
$arrayEjecuciones = array();

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$index = 0;
foreach ($arrayDatos as $idpractica => $datos) {
	$fechadesde = $datos['fechad'];
	$sqlAnterior = "SELECT
						c.nombre, p.codigopractica, p.descripcion, d.fechadesde, d.importe
						FROM resoluciondetalle d, resolucioncabecera c, practicas p
						WHERE 
								d.idresolucion != $idcabecera and 
								d.idpractica = $idpractica and 
								d.fechahasta is null and 
								d.fechadesde >= '$fechadesde' and d.idresolucion = c.id and d.idpractica = p.idpractica LIMIT 1";
	$resAnterior = mysql_query($sqlAnterior,$db);
	$canAnterior = mysql_num_rows($resAnterior);
	if ($canAnterior > 0) {
		$rowAnterior = mysql_fetch_assoc($resAnterior);
		$arrayConflicto[$idpractica] = $rowAnterior;
		$arrayConflicto[$idpractica] += $datos;
	} else {
		$fechahasta = strtotime ( '-1 day' , strtotime ( $fechadesde ) ) ;
		$fechahasta = date ( 'Y-m-j' , $fechahasta );
		$sqlUpdate = "UPDATE resoluciondetalle SET fechahasta = '$fechahasta' WHERE idpractica = $idpractica and idresolucion != $idcabecera and fechahasta is null";
			
		$arrayEjecuciones[$index] = $sqlUpdate;
		$index++;
		
		$fechahasta = $datos['fechah'];
		$importe = $datos['importeNuevo'];
		$sqlInsert = "INSERT INTO resoluciondetalle VALUE($idcabecera,$idpractica,'$fechadesde',NULL,$importe,'$fecharegistro','$usuarioregistro')";
		if ($fechahasta != "") {
			$sqlInsert = "INSERT INTO resoluciondetalle VALUE($idcabecera,$idpractica,'$fechadesde','$fechahasta',$importe,'$fecharegistro','$usuarioregistro')";
		}
		$arrayEjecuciones[$index] = $sqlInsert;
		$index++;
	}
}



if (sizeof($arrayConflicto) == 0) {
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//echo $sqlDelete."<br>";
		$dbh->exec($sqlDelete);
		
		foreach ($arrayEjecuciones as $idpractica => $sql) {
			//echo $sql."<br>";
			$dbh->exec($sql);
		}
	
		$dbh->commit();
		$pagina = "detalleResolucion.php?id=$idcabecera";
		Header("Location: $pagina");
	} catch (PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
} else {
	$sqlResolucion = "SELECT r.*, DATE_FORMAT(r.fecha, '%d-%m-%Y') as fecha FROM resolucioncabecera r WHERE r.id = $idcabecera ORDER BY id";
	$resResolucion = mysql_query($sqlResolucion,$db);
	$rowResolucion = mysql_fetch_assoc($resResolucion);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Detalle Resoluciones :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<style type="text/css" media="print">
.nover {display:none}
</style>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<p><input type="button" class="nover" name="volver" value="Volver" onclick="location.href = 'resoluciones.php'"/></p>
  	<h3>Detalle Resolución a Modificar</h3>
  	<div style="border: solid; width: 600px">
	  	<p><b>Nombre: </b> <?php echo $rowResolucion['nombre'] ?></p>
	  	<p><b>Emisor: </b> <?php echo $rowResolucion['emisor'] ?></p>
	    <p><b>Fecha Emisión: </b> <?php echo $rowResolucion['fecha'] ?></p>
	  	<p><b>Observación</b></p> 
	  	<p><?php echo $rowResolucion['observacion'] ?></p>
  	</div>
  	<h3>Detalle de Conflictos al Guardar la Resolución</h3>
  		<h4 style="color: red">A continuación los conflictos de fechas a resolver para poder guardar las practicas de la nueva resolución</h4> 
  		<div class="grilla">
	  		<table style="width: 900px">
	  			<thead>
	  				<tr>	
	  					<th>Resolución</th>
		  				<th>Código de Practica</th>
		  				<th>Nombre</th>
		  				<th>Fecha Desde</th>
		  				<th>Importe ($)</th>
		  				<th></th>
		  				<th>Fecha Desde Nueva</th>
		  				<th>Importe Nuevo ($)</th>
	  					</tr>
	  				</thead>
	  				<tbody>
			 	 <?php  foreach ($arrayConflicto as $idpractica => $datos) { ?>
			  			 	<tr>
			  			 		<td><?php echo $datos['nombre'] ?></td>
			  			 		<td><?php echo $datos['codigopractica'] ?></td>
			  			 		<td><?php echo $datos['descripcion'] ?></td>
			  			 		<td width="100px"><?php echo $datos['fechadesde'] ?></td>
			  			 		<td><?php echo number_format($datos['importe'],2,',','.') ?></td>
			  			 		<td width="18px">--></td>
			  			 		<td width="100px"><?php echo $datos['fechad'] ?></td>
			  			 		<td><?php echo number_format($datos['importeNuevo'],2,',','.') ?></td>
			  			 	</tr>
			  	  <?php } ?>
	  	  			</tbody>
	  	   		</table>
  	   		</div>
	<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>