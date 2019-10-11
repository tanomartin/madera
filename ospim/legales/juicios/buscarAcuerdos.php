<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

if(isset($_POST['status'])) {
	$cuit = $_POST['nrcuit'];
	$status = $_POST['status'];
	$today = date('Y-m-j');
	$fechaVto = strtotime ('-6 month' , strtotime ($today)) ;
	$fechaVto = date( 'Y-m-j' , $fechaVto );
	
	$sqlAcuerdos = "SELECT c.nroacuerdo, t.descripcion, c.nroacta ,o.fechacuota from cabacuerdosospim c, 
						   cuoacuerdosospim o, tiposdeacuerdos t 
				     WHERE c.cuit = $cuit and c.estadoacuerdo = 1 and 
						   c.cuit = o.cuit and c.nroacuerdo = o.nroacuerdo and o.montopagada = 0 and 
						   c.tipoacuerdo = t.codigo group by c.nroacuerdo 
					 ORDER BY c.nroacuerdo ASC, o.fechacuota DESC";
	$resAcuerdos = mysql_query($sqlAcuerdos,$db);
	$i=0;
	$acuAbs = array();
	while($rowAcuerdos = mysql_fetch_assoc($resAcuerdos)) {
		if ($status != 2) {
			$fechacuota = $rowAcuerdos['fechacuota'];
			if ($fechacuota < $fechaVto) {
				$acuAbs[$i] = array('nroacu' => $rowAcuerdos['nroacuerdo'], 'tipo' => $rowAcuerdos['descripcion'], 'acta' => $rowAcuerdos['nroacta']);
				$i++;
			}
		} else {
			$acuAbs[$i] = array('nroacu' => $rowAcuerdos['nroacuerdo'], 'tipo' => $rowAcuerdos['descripcion'], 'acta' => $rowAcuerdos['nroacta']);
			$i++;
		}
	}

	
	if (sizeof($acuAbs) > 0) {
		$respuesta = "<tr>
		  					<td><b>Acuerdos a Absorver </b>
		  						[<input name='acuabs' type='radio' value='0' checked='checked' onchange='mostrarAcuerdos()' /> NO -
		  					    <input name='acuabs' type='radio' value='1' onchange='mostrarAcuerdos()' /> SI ]
		  					</td>
		  				</tr>
	     			 	<tr>
	        			<td>
							<div align='center' id='acuerdos' style='visibility:hidden'>
	            				<table> ";
		foreach($acuAbs as $acuerdo) {
				 $respuesta .= "<tr>
	                				<td><input name='nroacu' type='radio' value='".$acuerdo['nroacu']."' onclick='cargarPeriodosAbsorvidos(this.value)'/></td>
	                				<td align='left'>".$acuerdo['nroacu']." - ".$acuerdo['tipo']. " - Acta: ".$acuerdo['acta']."</td>
	             				</tr>";
			 }
		$respuesta .= "</table></div></td></tr>";
	} else { 
	  	$respuesta ="<tr>
	        			<td>
	        				<div align='center'>
	        					<b>Acuerdos a Absorver</b> [<input name='acuabs' type='radio' value='0' checked='checked'/> NO ]
	        				</div>
	        			</td>
	      			   </tr>
	      			   <tr>
	        			<td>
	        				<div align='center'>
	        					<b>No hay acuerdos posibles</b> <input name='nroacu' type='radio' value='0' checked='checked' style='visibility:hidden'/> 
	        				</div>
	        		    </td>
	        		  </tr>";
	}
	echo $respuesta;
}
?>
