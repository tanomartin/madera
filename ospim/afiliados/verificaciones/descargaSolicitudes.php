<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."claves.php");
$fechadescarga = date("Y-m-d H:m:s");
$usuariodescarga = $_SESSION['usuario'];

//Conexion local y remota.
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("poseidon",$maquina)==0)
	$hostremoto = $hostOspim;
else
	$hostremoto = "localhost";

$dbremota = $baseOspimIntranet;
$hostlocal = $_SESSION['host'];
$dblocal = $_SESSION['dbname'];

try{
	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota",$usuarioOspim,$claveOspim);
	$dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbr->beginTransaction();

	$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
	$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbl->beginTransaction();
		
	$sqlControlPedidas="SELECT COUNT(*) FROM autorizacionpedida";
	$resultControlPedidas = $dbr->query($sqlControlPedidas);

	$mensaje = "";
	if (!$resultControlPedidas) {
		$mensaje = "Error en la consulta de SOLICITUDES ONLINE. Comuniquese con el Depto. de Sistemas";
	} else {
		if ($resultControlPedidas->fetchColumn()==0) {
			$mensaje = "No se encontraron nuevas solicitudes para descargar";
		} else {
			set_time_limit(0);
			$sqlLeePedidas="SELECT * FROM autorizacionpedida";
			$resultLeePedidas = $dbr->query($sqlLeePedidas);
			$totalpedidas=count($dbr->query($sqlLeePedidas)->fetchAll());
			$mensaje = "Se han descargado $totalpedidas nuevas solicitudes";

			if (!$resultLeePedidas) {
				$mensaje .= "<br>Error en la consulta de Solicitudes Pedidas. Comuniquese con el Depto. de Sistemas";
			} else {
				foreach ($resultLeePedidas as $pedidas) {
					$statusverifi=0;
					$statusautori=0;
					$sqlAddAutorizacion = "INSERT INTO autorizaciones (nrosolicitud, codidelega, fechasolicitud, cuil, nroafiliado, codiparentesco, apellidoynombre, comentario, telefonoafiliado, movilafiliado, emailafiliado, practica, material, tipomaterial, medicamento, statusverificacion) 
											VALUES (:nrosolicitud, :codidelega, :fechasolicitud, :cuil, :nroafiliado, :codiparentesco, :apellidoynombre, :comentario, :telefonoafiliado, :movilafiliado, :emailafiliado, :practica, :material, :tipomaterial, :medicamento, :statusverificacion)";
					$resAddAutorizacion = $dbl->prepare($sqlAddAutorizacion);
					
					$telefono = NULL;
					if ($pedidas['telefono'] != "") {
						$telefono = $pedidas['telefono'];
					}
					$movil = NULL;
					if ($pedidas['movil'] != "") {
						$movil = $pedidas['movil'];
					}
					$email = NULL;
					if ($pedidas['email'] != "") {
						$email = $pedidas['email'];
					}
					
					if ($resAddAutorizacion->execute(array(':nrosolicitud' => $pedidas['nrosolicitud'], ':codidelega' => $pedidas['delcod'], ':fechasolicitud' => $pedidas['fechasolicitud'], 
														   ':cuil' => $pedidas['nrcuil'], ':nroafiliado' => $pedidas['nrafil'], ':codiparentesco' => $pedidas['codpar'], 
														   ':apellidoynombre' => $pedidas['nombre'], ':comentario' => $pedidas['comentario'], ':telefonoafiliado' => $telefono, 
														   ':movilafiliado' => $movil, ':emailafiliado' => $email,':practica' => $pedidas['practica'], 
														   ':material' => $pedidas['material'], ':tipomaterial' => $pedidas['tipomaterial'], ':medicamento' => $pedidas['medicamento'], 
														   ':statusverificacion' => $statusverifi))) {					
						$sqlAddDocuOriginal = "INSERT INTO autorizacionesdocoriginales (nrosolicitud, pedidomedico, resumenhc, avalsolicitud, presupuesto1, presupuesto2, presupuesto3, presupuesto4, presupuesto5)
												VALUES (:nrosolicitud, :pedidomedico, :resumenhc, :avalsolicitud, :presupuesto1, :presupuesto2, :presupuesto3, :presupuesto4, :presupuesto5)";
						$resAddDocuOriginal = $dbl->prepare($sqlAddDocuOriginal);
						
						$pedidomedico = NULL;
						if ($pedidas['pedidomedico'] != "") {
							$pedidomedico = $pedidas['pedidomedico'];
						}
						$resumenhc = NULL;
						if ($pedidas['resumenhc'] != "") {
							$resumenhc = $pedidas['resumenhc'];
						}
						$avalsolicitud = NULL;
						if ($pedidas['avalsolicitud'] != "") {
							$resumenhc = $pedidas['avalsolicitud'];
						}
						$presupuesto1 = NULL;
						if ($pedidas['presupuesto1'] != "") {
							$presupuesto1 = $pedidas['presupuesto1'];
						}
						$presupuesto2 = NULL;
						if ($pedidas['presupuesto2'] != "") {
							$presupuesto1 = $pedidas['presupuesto2'];
						}
						$presupuesto3 = NULL;
						if ($pedidas['presupuesto3'] != "") {
							$presupuesto1 = $pedidas['presupuesto3'];
						}
						$presupuesto4 = NULL;
						if ($pedidas['presupuesto4'] != "") {
							$presupuesto1 = $pedidas['presupuesto4'];
						}
						$presupuesto5 = NULL;
						if ($pedidas['presupuesto5'] != "") {
							$presupuesto1 = $pedidas['presupuesto5'];
						}
						
						if ($resAddDocuOriginal->execute(array(':nrosolicitud' => $pedidas['nrosolicitud'], ':pedidomedico' => $pedidomedico, ':resumenhc' => $resumenhc, 
															   ':avalsolicitud' => $avalsolicitud, ':presupuesto1' => $presupuesto1, ':presupuesto2' => $presupuesto2, 
															   ':presupuesto3' => $presupuesto3, ':presupuesto4' => $presupuesto4, ':presupuesto5' => $presupuesto5))) {
							$sqlAddAutorizacionAtendida = "INSERT INTO autorizacionesatendidas (nrosolicitud, codidelega, fechasolicitud, cuil, nroafiliado, codiparentesco, apellidoynombre, comentario, telefonoafiliado, movilafiliado, emailafiliado, practica, material, tipomaterial, medicamento, statusverificacion, statusautorizacion, usuariodescarga, fechadescarga)
															VALUES (:nrosolicitud, :codidelega, :fechasolicitud, :cuil, :nroafiliado, :codiparentesco, :apellidoynombre, :comentario, :telefonoafiliado, :movilafiliado, :emailafiliado, :practica, :material, :tipomaterial, :medicamento, :statusverificacion, :statusautorizacion, :usuariodescarga, :fechadescarga)";
							$resAddAutorizacionAtendida = $dbl->prepare($sqlAddAutorizacionAtendida);
							if ($resAddAutorizacionAtendida->execute(array(':nrosolicitud' => $pedidas['nrosolicitud'], ':codidelega' => $pedidas['delcod'], 
																		   ':fechasolicitud' => $pedidas['fechasolicitud'], ':cuil' => $pedidas['nrcuil'], 
																		   ':nroafiliado' => $pedidas['nrafil'], ':codiparentesco' => $pedidas['codpar'], 
																		   ':apellidoynombre' => $pedidas['nombre'], ':comentario' => $pedidas['comentario'], 
																		   ':telefonoafiliado' => $telefono, ':movilafiliado' => $movil, ':emailafiliado' => $email,
																		   ':practica' => $pedidas['practica'], ':material' => $pedidas['material'], 
																		   ':tipomaterial' => $pedidas['tipomaterial'], ':medicamento' => $pedidas['medicamento'], 
																		   ':statusverificacion' => $statusverifi, ':statusautorizacion' => $statusautori, 
																		   ':usuariodescarga' => $usuariodescarga, ':fechadescarga' => $fechadescarga))) {		
								$sqlBorraPedidas="DELETE FROM autorizacionpedida WHERE nrosolicitud = :nrosolicitud";
								$resultBorraPedidas = $dbr->prepare($sqlBorraPedidas);
								if ($resultBorraPedidas->execute(array(':nrosolicitud' => $pedidas['nrosolicitud']))) { }
							}
						}
					}
				}
			}
		}
	}

	$dbr->commit();
	$dbl->commit();
	
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbr->rollback();
	$dbl->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Solicitudes de Autorizacion :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAfiliados.php'" align="left"/></p>
	<h3>Descarga de Solicitudes</h3>
	<h3 style="color: blue"><?php echo $mensaje ?></h3>
    <p><input type="submit" name="listar" value="Listar Solicitudes" onclick="location.href = 'listarSolicitudes.php'" align="left"/></p>
</div>
</body>
</html>