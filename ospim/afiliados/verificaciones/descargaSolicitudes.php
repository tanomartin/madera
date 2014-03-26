<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
$fechadescarga = date("Y-m-d H:m:s");
$usuariodescarga = $_SESSION['usuario'];

//conexion remota y creacion de transaccion.
try{
	$hostremoto = "www.ospim.com.ar";
	$dbremota = "sistem22_intranet";
	//echo "$hostremoto"; echo "<br>";
	//echo "$dbremota"; echo "<br>";
	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota","sistem22_charly","bsdf5762");
	//echo 'Connected to database remota<br/>';
    $dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbr->beginTransaction();
	
	
	$hostlocal = $_SESSION['host'];
	$dblocal = $_SESSION['dbname'];
	//echo "$hostlocal"; echo "<br>";
	//echo "$dblocal"; echo "<br>";
	$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database local<br/>';
    $dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbl->beginTransaction();
		
	
	$sqlControlPedidas="SELECT COUNT(*) FROM autorizacionpedida";
	$resultControlPedidas = $dbr->query($sqlControlPedidas);

	if (!$resultControlPedidas) {
		print ("<p>&nbsp;</p>\n");
		print ("<table width=769 border=1 align=center>");
		print ("<tr>");
		print ("<td width=769><div align=center class=Estilo1>Error en la consulta de SOLICITUDES ONLINE. Comuniquese con el Depto. de Sistemas.</div></td>");
		print ("</tr>");
		print ("</table>");
	}
	else {
		if ($resultControlPedidas->fetchColumn()==0) {
			print ("<p>&nbsp;</p>\n");
			print ("<table width=769 border=1 align=center>");
			print ("<tr>");
			print ("<td width=769><div align=center class=Estilo1>No se encontraron nuevas solicitudes para descargar.</div></td>");
			print ("</tr>");
			print ("</table>");
		}
		else {
			set_time_limit(0);

			$sqlLeePedidas="SELECT * FROM autorizacionpedida";
			$resultLeePedidas = $dbr->query($sqlLeePedidas);

			$totalpedidas=count($dbr->query($sqlLeePedidas)->fetchAll());
			//echo "Total Pedidas: ".$totalpedidas; echo "<br>";

			print ("<p>&nbsp;</p>\n");
			print ("<table width=769 border=1 align=center>");
			print ("<tr>");
			print ("<td width=769><div align=center class=Estilo1>Se han descargado ".$totalpedidas." nuevas solicitudes.</div></td>");
			print ("</tr>");
			print ("</table>");

			if (!$resultLeePedidas) {
				print ("<p>&nbsp;</p>\n");
				print ("<table width=769 border=1 align=center>");
				print ("<tr>");
				print ("<td width=769><div align=center class=Estilo1>Error en la consulta de Solicitudes Pedidas. Comuniquese con el Depto. de Sistemas.</div></td>");
				print ("</tr>");
				print ("</table>");
			}
			else {
				foreach ($resultLeePedidas as $pedidas) {
					$statusverifi=0;
					$statusautori=0;

					$sqlAddAutorizacion="INSERT INTO autorizaciones (nrosolicitud, codidelega, fechasolicitud, cuil, nroafiliado, codiparentesco, apellidoynombre, practica, material, tipomaterial, medicamento, pedidomedico, resumenhc, avalsolicitud, presupuesto1, presupuesto2, presupuesto3, presupuesto4, presupuesto5, statusverificacion, statusautorizacion, usuariodescarga, fechadescarga) VALUES (:nrosolicitud, :codidelega, :fechasolicitud, :cuil, :nroafiliado, :codiparentesco, :apellidoynombre, :practica, :material, :tipomaterial, :medicamento, :pedidomedico, :resumenhc, :avalsolicitud, :presupuesto1, :presupuesto2, :presupuesto3, :presupuesto4, :presupuesto5, :statusverificacion, :statusautorizacion, :usuariodescarga, :fechadescarga)";
					$resultAddAutorizacion = $dbl->prepare($sqlAddAutorizacion);
					if ($resultAddAutorizacion->execute(array(':nrosolicitud' => $pedidas[nrosolicitud], ':codidelega' => $pedidas[delcod], ':fechasolicitud' => $pedidas[fechasolicitud], ':cuil' => $pedidas[nrcuil], ':nroafiliado' => $pedidas[nrafil], ':codiparentesco' => $pedidas[codpar], ':apellidoynombre' => $pedidas[nombre], ':practica' => $pedidas[practica], ':material' => $pedidas[material], ':tipomaterial' => $pedidas[tipomaterial], ':medicamento' => $pedidas[medicamento], ':pedidomedico' => $pedidas[pedidomedico], ':resumenhc' => $pedidas[resumenhc], ':avalsolicitud' => $pedidas[avalsolicitud], ':presupuesto1' => $pedidas[presupuesto1], ':presupuesto2' => $pedidas[presupuesto2], ':presupuesto3' => $pedidas[presupuesto3], ':presupuesto4' => $pedidas[presupuesto4], ':presupuesto5' => $pedidas[presupuesto5], ':statusverificacion' => $statusverifi, ':statusautorizacion' => $statusautori, ':usuariodescarga' => $usuariodescarga, ':fechadescarga' => $fechadescarga))) {
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
?>
	<p>&nbsp;</p>
	<table width="769" border="1" align="center">
	<tr align="center" valign="top">
    <td width="385" valign="middle">
	<div align="left">
	<input type="reset" name="volver" value="Volver" onClick="location.href = '../menuAfiliados.php'" align="left"/>
	</div>
	</td>
    <td width="384" valign="middle">
	<div align="right">
    <input type="submit" name="listar" value="Listar Solicitudes" onClick="location.href = 'listarSolicitudes.php'" align="left"/>
    </div>
	</td>
	</tr>
	</table>
<?php
}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbr->rollback();
	$dbl->rollback();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Solicitudes de Autorizacion :.</title>
</head>
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
<body bgcolor="#CCCCCC">
</body>
</html>