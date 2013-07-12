<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fechavalidacion = date("Y-m-d H:m:s");
$usuariovalidacion = $_SESSION['usuario'];

//conexion y creacion de transaccion.
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	print ("<table width=769 border=1 align=center>");
	print ("<tr>");
	print ("<td width=769><div align=center class=Estilo1>Resultados de la Validaci&oacute;n de Boletas</div></td>");
	print ("</tr>");
	print ("</table>");

	$sqlControlValidar="SELECT COUNT(*) FROM banacuerdosusimra WHERE fechavalidacion = '00000000000000' and estadomovimiento in ('P','E')";
	$sqlLeeAValidar="SELECT * FROM banacuerdosusimra WHERE fechavalidacion = '00000000000000' and estadomovimiento in ('P','E')";

	$resultControlValidar = $dbh->query($sqlControlValidar);

	if (!$resultControlValidar)
	{
		print ("<p>&nbsp;</p>\n");
		print ("<table width=769 border=1 align=center>");
		print ("<tr>");
		print ("<td width=769><div align=center class=Estilo1>Error en la consulta de BANACUERDOSUSIMRA. Comuniquese con el Depto. de Sistemas.</div></td>");
		print ("</tr>");
		print ("</table>");	
	}
	else
	{
		//Verifica si hay registros a validar
		if ($resultControlValidar->fetchColumn()==0)
		{
			$hayboleta=0;

			print ("<p>&nbsp;</p>\n");
			print ("<table width=769 border=1 align=center>");
			print ("<tr>");
			print ("<td width=769><div align=center class=Estilo1>No hay Boletas que deban ser Validadas.</div></td>");
			print ("</tr>");
			print ("</table>");
		}
		else
		{
			$hayboleta=1;

    		$resultLeeAValidar = $dbh->query($sqlLeeAValidar);
    		if (!$resultLeeAValidar)
			{
				print ("<p>&nbsp;</p>\n");
				print ("<table width=769 border=1 align=center>");
				print ("<tr>");
				print ("<td width=769><div align=center class=Estilo1>Error en la consulta de Informacion del Banco. Comuniquese con el Depto. de Sistemas.</div></td>");
				print ("</tr>");
				print ("</table>");
			}
			else
			{
				$cantvali=0;
				$cantnova=0;

				print ("<table width=769 border=1 align=center>");
				print ("<tr>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Id. Boleta</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>C.U.I.T.</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Acuerdo</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Cuota</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Importe</font></strong></div></td>");
				print ("<td><div align=center><strong><font size=1 face=Verdana>Status</font></strong></div></td>");
				print ("</tr>");

        		foreach ($resultLeeAValidar as $validar)
				{
					$control = $validar[nrocontrol];
					$estado = $validar[estadomovimiento];
					$importebanco = $validar[importe];
					$cuitbanco = $validar[cuit];
					$fechabanco = $validar[fechaacreditacion];

					$sqlControlaBoleta="SELECT * FROM anuladasusimra WHERE nrocontrol = :nrocontrol";
					$resultControlaBoleta = $dbh->prepare($sqlControlaBoleta);
					$resultControlaBoleta->execute(array(':nrocontrol' => $control));
					if($resultControlaBoleta)
					{
						foreach ($resultControlaBoleta as $anuladas)
						{
							$controlanulada = $anuladas[nrocontrol];
							$cantnova++;
							print ("<tr>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$controlanulada."</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>-</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>-</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>-</font></div></td>");
				    		print ("<td><div align=center><font size=1 face=Verdana>-</font></div></td>");
							print ("<td><div align=center><font size=1 face=Verdana>BOLETA ANULADA - No Validada</font></div></td>");
							print ("</tr>");
						}
					}

					$sqlBuscaBoleta="SELECT * FROM boletasusimra WHERE nrocontrol = :nrocontrol";
					//echo $sqlBuscaBoleta; echo "<br>";
					$resultBuscaBoleta = $dbh->prepare($sqlBuscaBoleta);
					$resultBuscaBoleta->execute(array(':nrocontrol' => $control));
					if ($resultBuscaBoleta)
					{
		        		foreach ($resultBuscaBoleta as $boletas)
						{
							$id = $boletas[idboleta];
							$cuitboleta = $boletas[cuit];
							$acuerdo = $boletas[nroacuerdo];
							$cuota = $boletas[nrocuota];
							$importeboleta = $boletas[importe];
							$control = $boletas[nrocontrol];
							$usuario = $boletas[usuarioregistro];
							
							print ("<tr>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$control."</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$cuitboleta."</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$acuerdo."</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$cuota."</font></div></td>");
						    print ("<td><div align=center><font size=1 face=Verdana>".$importeboleta."</font></div></td>");

							if($importebanco==$importeboleta)
							{
								if($cuitbanco==$cuitboleta)
								{
									$sqlAgregaValida="INSERT INTO validasusimra (idboleta, cuit, nroacuerdo, nrocuota, importe, nrocontrol, usuarioregistro) VALUES (:idboleta,:cuit,:nroacuerdo,:nrocuota,:importe,:nrocontrol,:usuarioregistro)";
									$resultAgregaValida = $dbh->prepare($sqlAgregaValida);
									//echo $sqlAgregaValida; echo "<br>";
									if ($resultAgregaValida->execute(array(':idboleta' => $id, ':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo, ':nrocuota' => $cuota, ':importe' => $importeboleta, ':nrocontrol' => $control, ':usuarioregistro' => $usuario)))
									{
										$cantvali++;
									    print ("<td><div align=center><font size=1 face=Verdana>Boleta Validada</font></div></td>");
									}
									else
									{
									    print ("<td><div align=center><font size=1 face=Verdana>ERROR CRV - Avise al Depto. Sistemas.</font></div></td>");
									}

									$sqlBorraBoleta="DELETE FROM boletasusimra WHERE nrocontrol = :nrocontrol";
									$resultBorraBoleta = $dbh->prepare($sqlBorraBoleta);
									//echo $sqlBorraBoleta; echo "<br>";
									if ($resultBorraBoleta->execute(array(':nrocontrol' => $control)))
									{
									    //print "<p>Registro Boleta borrado correctamente.</p>\n";
									}
									else
									{
									    //print "<p>Error al borrar el registro Boleta.</p>\n";
									}

									$sqlActualizaBanco="UPDATE banacuerdosusimra SET fechavalidacion = :fechavalidacion, usuariovalidacion = :usuariovalidacion WHERE nrocontrol = :nrocontrol and estadomovimiento = :estadomovimiento";
									$resultActualizaBanco = $dbh->prepare($sqlActualizaBanco);
									//echo $sqlActualizaBanco; echo "<br>";
									if ($resultActualizaBanco->execute(array(':fechavalidacion' => $fechavalidacion, ':usuariovalidacion' => $usuariovalidacion, ':nrocontrol' => $control, ':estadomovimiento' => $estado)))
									{
									    //print "<p>Registro Banco actualizado correctamente.</p>\n";
									}
									else
									{
									    //print "<p>Error al actualizar el registro Banco.</p>\n";
									}
								}
								else
								{
									$cantnova++;
									print ("<td><div align=center><font size=1 face=Verdana>CUIT BANCO ".$cuitbanco." Erroneo - No Validada</font></div></td>");
								}
							}
							else
							{
								$cantnova++;
								print ("<td><div align=center><font size=1 face=Verdana>IMPORTE BANCO ".$importebanco." Erroneo - No Validada</font></div></td>");
							}
							print ("</tr>");
						}
					}
        		}
				print ("</table>");

				$totabole=$cantvali+$cantnova;

				if($totabole!=0)
				{
					print ("<table width=769 border=1 align=center>");
					print ("<tr>");
					print ("<td width=769><div align=right class=Estilo1>TOTAL DE BOLETAS: ".$totabole." -- ".$cantvali." Validadas ".$cantnova." No Validadas</div></td>");
					print ("</tr>");
					print ("</table>");
				}
	   		}
		}
	}
	
	$dbh->commit();

	if($hayboleta==1) { 
	?>
		<p>&nbsp;</p>
		<table width="769" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="385" valign="middle">
		<div align="left">
		<input type="reset" name="volver" value="Volver" onClick="location.href = 'procesamientoRegistros.php'" align="left"/>
		</div>
		</td>
	    <td width="384" valign="middle">
		<div align="right">
        <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="left">
	    </div>
		</td>
		</tr>
		</table>
	<?php
	}
	else
	{ ?>
		<p>&nbsp;</p>
		<table width="769" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="769" valign="middle"><input type="reset" name="volver" value="Volver" onClick="location.href = 'procesamientoRegistros.php'" align="center"/>
		</td>
		</tr>
		</table>
	<?php
	}

}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title></head>
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
<body bgcolor="#B2A274">
</body>
</html>