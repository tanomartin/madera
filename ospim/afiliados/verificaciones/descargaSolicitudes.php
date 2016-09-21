<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."claves.php");
$fechadescarga = date("Y-m-d H:m:s");
$usuariodescarga = $_SESSION['usuario'];

//Conexion local y remota.
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$hostremoto = "localhost";
else
	$hostremoto = $hostOspim;

$dbremota = $baseOspimIntranet;
$hostlocal = $_SESSION['host'];
$dblocal = $_SESSION['dbname'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Solicitudes de Autorizacion :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
}
</style>
</head>
<body bgcolor="#CCCCCC">
<?php
	//Creacion de transaccion.
	try{
		$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota",$usuarioOspim,$claveOspim);
		//echo 'Connected to database remota<br/>';
	    $dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbr->beginTransaction();
		
		$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
		//echo 'Connected to database local<br/>';
	    $dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbl->beginTransaction();
			
		$sqlControlPedidas="SELECT COUNT(*) FROM autorizacionpedida";
		$resultControlPedidas = $dbr->query($sqlControlPedidas);

		if (!$resultControlPedidas) {
			print ("<div align=center class=Estilo1>Error en la consulta de SOLICITUDES ONLINE. Comuniquese con el Depto. de Sistemas.</div>");
		}
		else {
			if ($resultControlPedidas->fetchColumn()==0) {
				print ("<div align=center class=Estilo1>No se encontraron nuevas solicitudes para descargar.</div>");
			}
			else {
				set_time_limit(0);

				$sqlLeePedidas="SELECT * FROM autorizacionpedida";
				$resultLeePedidas = $dbr->query($sqlLeePedidas);

				$totalpedidas=count($dbr->query($sqlLeePedidas)->fetchAll());
				//echo "Total Pedidas: ".$totalpedidas; echo "<br>";

				print ("<div align=center class=Estilo1>Se han descargado ".$totalpedidas." nuevas solicitudes.</div>");

				if (!$resultLeePedidas) {
					print ("<div align=center class=Estilo1>Error en la consulta de Solicitudes Pedidas. Comuniquese con el Depto. de Sistemas.</div>");
				}
				else {
					foreach ($resultLeePedidas as $pedidas) {
						$statusverifi=0;
						$statusautori=0;

						$sqlAddAutorizacion="INSERT INTO autorizaciones (nrosolicitud, codidelega, fechasolicitud, cuil, nroafiliado, codiparentesco, apellidoynombre, telefonoafiliado, movilafiliado, emailafiliado, practica, material, tipomaterial, medicamento, pedidomedico, resumenhc, avalsolicitud, presupuesto1, presupuesto2, presupuesto3, presupuesto4, presupuesto5, statusverificacion, statusautorizacion, usuariodescarga, fechadescarga) VALUES (:nrosolicitud, :codidelega, :fechasolicitud, :cuil, :nroafiliado, :codiparentesco, :apellidoynombre, :practica, :material, :tipomaterial, :medicamento, :pedidomedico, :resumenhc, :avalsolicitud, :presupuesto1, :presupuesto2, :presupuesto3, :presupuesto4, :presupuesto5, :statusverificacion, :statusautorizacion, :usuariodescarga, :fechadescarga)";
						$resultAddAutorizacion = $dbl->prepare($sqlAddAutorizacion);
						if ($resultAddAutorizacion->execute(array(':nrosolicitud' => $pedidas[nrosolicitud], ':codidelega' => $pedidas[delcod], ':fechasolicitud' => $pedidas[fechasolicitud], ':cuil' => $pedidas[nrcuil], ':nroafiliado' => $pedidas[nrafil], ':codiparentesco' => $pedidas[codpar], ':apellidoynombre' => $pedidas[nombre], ':telefonoafiliado' => $pedidas[telefono], ':movilafiliado' => $pedidas[movil], ':emailafiliado' => $pedidas[email],':practica' => $pedidas[practica], ':material' => $pedidas[material], ':tipomaterial' => $pedidas[tipomaterial], ':medicamento' => $pedidas[medicamento], ':pedidomedico' => $pedidas[pedidomedico], ':resumenhc' => $pedidas[resumenhc], ':avalsolicitud' => $pedidas[avalsolicitud], ':presupuesto1' => $pedidas[presupuesto1], ':presupuesto2' => $pedidas[presupuesto2], ':presupuesto3' => $pedidas[presupuesto3], ':presupuesto4' => $pedidas[presupuesto4], ':presupuesto5' => $pedidas[presupuesto5], ':statusverificacion' => $statusverifi, ':statusautorizacion' => $statusautori, ':usuariodescarga' => $usuariodescarga, ':fechadescarga' => $fechadescarga))) {
							//echo "AGREGA AUTORIZACIONES"; echo "<br>";

							$sqlBorraPedidas="DELETE FROM autorizacionpedida WHERE nrosolicitud = :nrosolicitud";
							$resultBorraPedidas = $dbr->prepare($sqlBorraPedidas);
							if ($resultBorraPedidas->execute(array(':nrosolicitud' => $pedidas[nrosolicitud]))) {
								//echo "BORRA AUTORIZACIONPEDIDA"; echo "<br>";
							}
						}
					}
				}
			}
		}

		$dbr->commit();
		$dbl->commit();
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		$dbr->rollback();
		$dbl->rollback();
	}
?>
	<p>&nbsp;</p>
	<table width="769" border="1" align="center">
	<tr align="center" valign="top">
    <td width="385" valign="middle">
	<div align="left">
	<input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAfiliados.php'" align="left"/>
	</div>
	</td>
    <td width="384" valign="middle">
	<div align="right">
    <input type="submit" name="listar" value="Listar Solicitudes" onclick="location.href = 'listarSolicitudes.php'" align="left"/>
    </div>
	</td>
	</tr>
	</table>
</body>
</html>