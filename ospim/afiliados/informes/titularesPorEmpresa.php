<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

if (isset($_POST['cuit'])) { 
	$cuit = $_POST['cuit']; 
} else {
	if (isset($_GET['cuit'])) {
		$cuit = $_GET['cuit']; 
	}
} 

if (isset($cuit)) {
	$sqlEmpresa ="select nombre, cuit from empresas where cuit = $cuit";
	$resEmpresa = mysql_query($sqlEmpresa,$db);
	$canEmpresa = mysql_num_rows($resEmpresa);	
	$rowEmpresa = mysql_fetch_array($resEmpresa);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Titulares Por Empresas :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script language="javascript" type="text/javascript">

function validar(formulario) {
	if (!verificaCuilCuit(formulario.cuit.value)){
		alert("C.U.I.T invalido");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

jQuery(function($){
	$("#cuit").mask("99999999999");
});

$(function() {
	$("#listado")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,	
		}		
	})
});

function validar(formulario) {
	if (!verificaCuilCuit(formulario.cuit.value)){
		alert("C.U.I.T invalido");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Listado<br>Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
  <?php if (!isset($_GET['cuit'])) { ?>
	  <input type="button" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloInformes.php'" /></p>
		<form name="listadoTitularesEmpresa" id="listadoTitularesEmpresa" method="post" onsubmit="return validar(this)" action="titularesPorEmpresa.php">
		<p><span class="Estilo2">Titulares Por Empresas </span></p>
	  <?php	if (isset($canEmpresa) && $canEmpresa == 0) {
				print("<p><font color='#FF0000'><b> No existe empresa registrada para el C.U.I.T. $cuit </b></font></p>");
			}
	  ?>
		<table>
			<tr>
				<td class="nover"><strong>C.U.I.T.</strong>	</td>
				<td class="nover"><div align="left">
				  <input name="cuit" id="cuit" type="text" size="13" />
				</div></td>
			</tr>
		</table>
		<p><input type="submit" name="Submit" value="Listar" class="nover"/></p>
<?php } ?>
<?php	if (isset($canEmpresa) && $canEmpresa > 0) { ?>
	
		<p><span class="Estilo2"><?php echo "TITULARES DE: ".$rowEmpresa['nombre']." - C.U.I.T.: ".$rowEmpresa['cuit'] ?></span></p>
	
	 <?php
	 	$sqlTitulares = "SELECT 
						t.nroafiliado, 
						t.apellidoynombre,
						td.descrip as tipodocumento, 
						t.nrodocumento, 
						t.cuil, 
						d.nombre as delegacion, 
						t.tipoafiliado
						FROM
						titulares t,
						tipodocumento td,
						delegaciones d
						WHERE 
						t.cuitempresa = '$cuit' and
						t.tipodocumento = td.codtipdoc and
						t.codidelega = d.codidelega";
		$resTitulares = mysql_query($sqlTitulares,$db); 
		$cantTitulares = mysql_num_rows($resTitulares); 
		if ($cantTitulares > 0) {
	?>	
		<table class="tablesorter" id="listado" style="width:1100px; font-size:14px">
			<thead>
				<tr>
				  <th>Nro. Afiliado</th>
				  <th>Nombre y Apellido</th>
				  <th>Tipo Doc.</th>
				  <th>Num. Doc.</th>
				  <th>C.U.I.L.</th>
				  <th>Jurisdiccion</th>
				  <th>Tipo Afialiado</th>
				</tr>
		 	</thead>
			<tbody>
	 
			<?php while ($rowTitulares = mysql_fetch_assoc($resTitulares)) { ?>
				<tr align="center">
					<td><?php echo $rowTitulares['nroafiliado'] ?></td>
					<td><?php echo $rowTitulares['apellidoynombre'] ?></td>
					<td><?php echo $rowTitulares['tipodocumento'] ?></td>
					<td><?php echo $rowTitulares['nrodocumento'] ?></td>
					<td><?php echo $rowTitulares['cuil'] ?></td>
					<td><?php echo $rowTitulares['delegacion'] ?></td>
					<td><?php 
							if ($rowTitulares['tipoafiliado'] == 'R') { echo "REGULAR"; } 
							if ($rowTitulares['tipoafiliado'] == 'S') { echo "SOLO OSPIM"; } 
							if ($rowTitulares['tipoafiliado'] == 'O') { echo "POR OPCIÓN"; } 
					?></td>
				</tr>
	  <?php } ?>
	   	   </tbody>
  		</table> 
		<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/>
	  <?php } else {
	  		  print("<p><font color='#FF0000'><b> No existen Titulares para el C.U.I.T. ingresado </b></font></p>");
	        } 
 		} ?>
</form>
</div>
</body>
</html>
