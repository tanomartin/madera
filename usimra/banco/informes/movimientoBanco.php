<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");  
include($libPath."fechas.php");  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
		$("#nroControl").mask("99999999999999");
});

function validar(formulario) {
	if (formulario.nroControl.value == "") {
		alert("Debe insertar numero de control");
		return false;
	}
	return true;
}

</script>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
.Estilo1 {	font-size: 18px;
	font-weight: bold;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Anulacion de Boleta :.</title>
</head>
<body bgcolor="#B2A274">
<div align="center">
  <input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'"/>
  <p><span class="Estilo1">Consulta Movimiento Bancario</span></p>
</div>
<form id="anulacion" name="anulacion" method="post" onsubmit="return validar(this)" action="movimientoBanco.php">
  <div align="center">
    <table width="371" border="0">
      <tr>
        <td><div align="center">
            <p class="Estilo1"><strong>Codigo de identificacion de boleta</strong></p>
            <p>
              <input name="nroControl" id="nroControl" type="text" size="17" style="text-align:center"/>
            </p>
          </div></td>
      </tr>
      <tr>
        <td><div align="center">
            <input type="submit" name="anular" value="Consultar" />
          </div></td>
      </tr>
    </table>
    <?php 
		if(isset($_POST['nroControl'])) { 
			$nroControl = $_POST['nroControl'];?>
		    <p><span class="Estilo1">Resultado Codigo de identificacion de boleta "<?php echo $nroControl ?>"</span></p>
		    <p><span class="Estilo1">Acuerdos</span></p>
		    <table border="1" width="1000" style="text-align:center">
				<tr>
					<th>Fecha Recepci�n </th>
			        <th>Fecha Acreditacion </th>
			        <th>Tipo Movimiento</th>
			        <th>Importe</th>
			        <th>C.U.I.T. - Raz�n Social</th>
			        <th>Tipo Pago</th>
			        <th>Fecha Validacion</th>
			        <th>Fecha Imputacion</th>
		        </tr>
		        <?php
			        //ACUERDOS
			        $sqlBanco = "SELECT b.*, e.nombre as empresa FROM banacuerdosusimra b, empresas e WHERE b.nrocontrol = $nroControl and b.cuit = e.cuit ORDER BY b.fecharecaudacion, b.fechaacreditacion";
			        $resBanco = mysql_query($sqlBanco,$db);
			        $canBanco = mysql_num_rows($resBanco);
					if ($canBanco != 0) {
						while($rowBanco = mysql_fetch_array($resBanco)) { ?>
							<tr>
								<td><?php echo invertirFecha($rowBanco['fecharecaudacion']) ?></td>
					<?php		if ($rowBanco['estadomovimiento'] != 'P') { ?>
									<td><?php echo invertirFecha($rowBanco['fechaacreditacion']) ?></td>
					<?php		} else {  ?>
									<td>-</td>
					<?php		}	?>
								<td><?php echo $rowBanco['estadomovimiento'] ?></td>
								<td><?php echo $rowBanco['importe'] ?></td>
								<td><?php echo $rowBanco['cuit']."<br>".$rowBanco['empresa']?> </td>
					<?php		if ($rowBanco['estadomovimiento'] == 'E') { ?>
									<td>EFECTIVO</td>
					<?php		} else { ?>
									<td>CHEQUE N�: <?php echo $rowBanco['chequenro'] ?></td>
					<?php		}	
								if ($rowBanco['estadomovimiento'] == 'P' or $rowBanco['estadomovimiento'] == 'E') { ?>
									<td><?php echo $rowBanco['fechavalidacion'] ?></td>
					<?php		} else { ?>
									<td>-</td>
					<?php		}
								if ($rowBanco['estadomovimiento'] != 'P') { ?>
									<td><?php echo $rowBanco['fechaimputacion'] ?></td>
					<?php		} else { ?>
									<td>-</td>
					<?php		} ?>
							</tr>
				<?php	}
					} else { ?>
						<tr><td colspan='8' style='color:#FF0000'><b>No Existen movimientos de acuerdos para este c�digo</b></td></tr>
			<?php	} ?>
		    </table>
    		
    		<?php 
    		//APORTES
			$sqlBanco = "SELECT b.*, e.nombre as empresa FROM banaportesusimra b, empresas e WHERE b.nrocontrol = $nroControl and b.cuit = e.cuit ORDER BY b.fecharecaudacion, b.fechaacreditacion";
			$resBanco = mysql_query($sqlBanco,$db);
			$canBanco = mysql_num_rows($resBanco); ?>
		    <p><span class="Estilo1">Aportes</span></p>
		    <table border="1" width="1000" style="text-align:center">
				<tr>
					<th>Fecha Recepci�n </th>
			        <th>Fecha Acreditacion </th>
			        <th>Tipo Movimiento</th>
			        <th>Importe</th>
			        <th>C.U.I.T. - Raz�n Social</th>
			        <th>Tipo Pago</th>
			        <th>Fecha Validacion</th>
			        <th>Fecha Imputacion</th>
		        </tr>
		        <?php	
					if ($canBanco != 0) {
						while($rowBanco = mysql_fetch_array($resBanco)) { ?>
							<tr>
								<td><?php echo invertirFecha($rowBanco['fecharecaudacion']) ?></td>
					<?php		if ($rowBanco['estadomovimiento'] != 'P') { ?>
									<td><?php echo invertirFecha($rowBanco['fechaacreditacion']) ?></td>
					<?php		} else {  ?>
									<td>-</td>
					<?php		}	?>
								<td><?php echo $rowBanco['estadomovimiento'] ?></td>
								<td><?php echo $rowBanco['importe'] ?></td>
								<td><?php echo $rowBanco['cuit']."<br>".$rowBanco['empresa']?> </td>
					<?php		if ($rowBanco['estadomovimiento'] == 'E') { ?>
									<td>EFECTIVO</td>
					<?php		} else { ?>
									<td>CHEQUE N�: <?php echo $rowBanco['chequenro'] ?></td>
					<?php		}	
								if ($rowBanco['estadomovimiento'] == 'P' or $rowBanco['estadomovimiento'] == 'E') { ?>
									<td><?php echo $rowBanco['fechavalidacion'] ?></td>
					<?php		} else { ?>
									<td>-</td>
					<?php		}
								if ($rowBanco['estadomovimiento'] != 'P') { ?>
									<td><?php echo $rowBanco['fechaimputacion'] ?></td>
					<?php		} else { ?>
									<td>-</td>
					<?php		} ?>
							</tr>
				<?php	}
					} else { ?>
						<tr><td colspan='8' style='color:#FF0000'><b>No Existen movimientos de aportes para este c�digo</b></td></tr>
			<?php	} ?>
		    </table>
    	<p><input type='button' name='imprimir' value='Imprimir' onclick='window.print();'/></p>
    <?php } ?>
  </div>
</form>
</body>
</html>
