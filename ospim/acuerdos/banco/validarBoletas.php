<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
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

	$sqlControlValidar="SELECT COUNT(*) FROM banacuerdosospim WHERE fechavalidacion = '00000000000000' and estadomovimiento in ('P','E')";
	$sqlLeeAValidar="SELECT * FROM banacuerdosospim WHERE fechavalidacion = '00000000000000' and estadomovimiento in ('P','E')";

	$resultControlValidar = $dbh->query($sqlControlValidar);

	if (!$resultControlValidar)
    	print "<p>Error en la consulta de BANACUERDOSOSPIM.</p>\n";
	else
	{
		//Verifica si hay registros a validar
		if ($resultControlValidar->fetchColumn()==0)
    		print "<p>No hay boletas que deban ser validadas.</p>\n";
		else
		{
    		$resultLeeAValidar = $dbh->query($sqlLeeAValidar);
    		if (!$resultLeeAValidar)
      		  	print "<p>Error en la consulta de BANACUERDOSOSPIM.</p>\n";
			else
			{
        		foreach ($resultLeeAValidar as $validar)
				{
            		//print "<p>Movimiento: $validar[nromovimiento] - Sucursal: $validar[sucursalorigen] - Recaudacion: $validar[fecharecaudacion] - Acreditacion: $validar[fechaacreditacion] - Estado: $validar[estadomovimiento] - Control: $validar[nrocontrol]</p>\n";

					$control = $validar[nrocontrol];
					$estado = $validar[estadomovimiento];
					$importebanco = $validar[importe];
					$cuitbanco = $validar[cuit];
					$sqlBuscaBoleta="SELECT * FROM boletasospim WHERE nrocontrol = :nrocontrol";
					//echo $sqlBuscaBoleta; echo "<br>";
					$resultBuscaBoleta = $dbh->prepare($sqlBuscaBoleta);
					$resultBuscaBoleta->execute(array(':nrocontrol' => $control));
					if ($resultBuscaBoleta)
					{
		        		foreach ($resultBuscaBoleta as $boletas)
						{
							//print "<p>Boleta ID: $boletas[idboleta] - CUIT: $boletas[cuit] - Acuerdo: $boletas[nroacuerdo] - Cuota: $boletas[nrocuota] - Importe: $boletas[importe] - Control: $boletas[nrocontrol] - Usuario: $boletas[usuarioregistro]</p>\n";
							//print("<input type='submit' name='Submit' value='Enviar' />");

							$id = $boletas[idboleta];
							$cuitboleta = $boletas[cuit];
							$acuerdo = $boletas[nroacuerdo];
							$cuota = $boletas[nrocuota];
							$importeboleta = $boletas[importe];
							$control = $boletas[nrocontrol];
							$usuario = $boletas[usuarioregistro];

							if($importebanco=$importeboleta)
							{
								//print "<p>Importe Boleta : $importeboleta - Importe Banco: $importebanco</p>\n";

								if($cuitbanco=$cuitboleta)
								{
									//print "<p>CUIT Boleta : $cuitboleta - Cuit Banco: $cuitbanco</p>\n";
									$sqlAgregaValida="INSERT INTO validasospim (idboleta, cuit, nroacuerdo, nrocuota, importe, nrocontrol, usuarioregistro) VALUES (:idboleta,:cuit,:nroacuerdo,:nrocuota,:importe,:nrocontrol,:usuarioregistro)";
									$resultAgregaValida = $dbh->prepare($sqlAgregaValida);
									//echo $sqlAgregaValida; echo "<br>";
									if ($resultAgregaValida->execute(array(':idboleta' => $id, ':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo, ':nrocuota' => $cuota, ':importe' => $importeboleta, ':nrocontrol' => $control, ':usuarioregistro' => $usuario)))
									{
									    //print "<p>Registro creado correctamente.</p>\n";
									}
									else
									{
									    //print "<p>Error al crear el registro.</p>\n";
									}

									$sqlBorraBoleta="DELETE FROM boletasospim WHERE nrocontrol = :nrocontrol";
									$resultBorraBoleta = $dbh->prepare($sqlBorraBoleta);
									//echo $sqlBorraBoleta; echo "<br>";
									if ($resultBorraBoleta->execute(array(':nrocontrol' => $control)))
									{
									    //print "<p>Registro borrado correctamente.</p>\n";
									}
									else
									{
									    //print "<p>Error al borrar el registro.</p>\n";
									}

									$sqlActualizaBanco="UPDATE banacuerdosospim SET fechavalidacion = :fechavalidacion, usuariovalidacion = :usuariovalidacion WHERE nrocontrol = :nrocontrol and estadomovimiento = :estadomovimiento";
									$resultActualizaBanco = $dbh->prepare($sqlActualizaBanco);
									//echo $sqlActualizaBanco; echo "<br>";
									if ($resultActualizaBanco->execute(array(':fechavalidacion' => $fechavalidacion, ':usuariovalidacion' => $usuariovalidacion, ':nrocontrol' => $control, ':estadomovimiento' => $estado)))
									{
									    //print "<p>Registro actualizado correctamente.</p>\n";
									}
									else
									{
									    //print "<p>Error al actualizar el registro.</p>\n";
									}
								}
								else
								{


								}
							}
							else
							{
							}
						}
					}
        		}
    		}
		}
	}
	
	$dbh->commit();
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>
