<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Empresas :.</title>
</head>
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

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script language="javascript" type="text/javascript">

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
	if (formulario.delegacion.value == 0) {
		alert("Debe elegir una Delegación");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Listado<br>Aguarde por favor...</h1>" });
	return true;
}

</script>

<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
  <p><input type="reset" name="volver" value="Volver" class="nover" onclick="location.href = '../menuEmpresa.php?origen=<?php echo $origen ?>'" align="center"/></p>
  	<form name="listadoEmpresa" id="listadoEmpresa" method="post" onSubmit="return validar(this)" action="listadoEmpresas.php?origen=<?php echo $origen ?>">
  	<p><span class="Estilo2">Empresas por Delegaci&oacute;n </span></p>
	<table>
		<tr>
			<td width="96" class="nover"><strong>Delegación</strong>	</td>
		  	<td width="177"><div align="left">
		    <select name="delegacion" id="delegacion" class="nover">
		      <option value="0" selected="selected">Seleccione un Valor </option>
		      <?php 
						$sqlDele="select codidelega,nombre from delegaciones where codidelega not in (1000,1001,3500)";
						$resDele= mysql_query($sqlDele,$db);
						while ($rowDele=mysql_fetch_array($resDele)) { 	?>
		      <option value="<?php echo $rowDele['codidelega'] ?>"><?php echo $rowDele['nombre']  ?></option>
		      <?php } ?>
	        </select>
	      	</div></td>
		</tr>
	</table>
	<p><input type="submit" name="Submit" value="Listar" class="nover"/></p>

<?php if (isset($_POST['delegacion'])) { 
		$codidelega = $_POST['delegacion'];
		$sqlDele="select nombre from delegaciones where codidelega = $codidelega";
		$resDele= mysql_query($sqlDele,$db);
		$rowDele=mysql_fetch_array($resDele); ?>

		<p><span class="Estilo2"><?php echo $rowDele['nombre'] ?></span></p>
		
		<table class="tablesorter" id="listado" style="width:1100px; font-size:14px">
			<thead>
				<tr>
				  <th>C.U.I.T.</th>
				  <th>Razón Social</th>
				  <th>Domicilio Legal</th>
				  <th class="filter-select" data-placeholder="Seleccione Localidad">Localidad Legal</th>
				  <th>Domicilio Real</th>
				  <th class="filter-select" data-placeholder="Seleccione Localidad">Localidad Real</th>
				  <th>Disg. Dinero</th>
				  <th>Otra Jurisdiccion (Disg. Dinero)</th>
				</tr>
		 	</thead>
			<tbody>
	  <?php $sqlEmpre = "select e.cuit, e.nombre, e.domilegal, e.numpostal as numlegal, l.nomlocali as localidad, j.domireal, j.numpostal as numreal, lreal.nomlocali as localidadReal, j.disgdinero from empresas e, jurisdiccion j, localidades l, localidades lreal where j.codidelega = $codidelega and j.cuit = e.cuit and e.codlocali = l.codlocali and j.codlocali = lreal.codlocali";
			$resEmpre = mysql_query($sqlEmpre,$db); 
			while ($rowEmpre = mysql_fetch_assoc($resEmpre)) { ?>
				<tr align="center">
					<td><?php echo $rowEmpre['cuit'] ?></td>
					<td><?php echo $rowEmpre['nombre'] ?></td>
					<td><?php echo $rowEmpre['domilegal']." [".$rowEmpre['numlegal']."]" ?></td>
					<td><?php echo $rowEmpre['localidad'] ?></td>
					<td><?php echo $rowEmpre['domireal']." [".$rowEmpre['numreal']."]" ?></td>
					<td><?php echo $rowEmpre['localidadReal'] ?></td>
					<td><?php echo $rowEmpre['disgdinero']."%" ?></td>
					<td><?php 
						$cuit =  $rowEmpre['cuit'];
						$sqlOtraJuris = "select d.nombre, j.disgdinero from jurisdiccion j, delegaciones d where cuit = $cuit and j.codidelega != $codidelega and j.codidelega not in (1000,1001,3500) and j.codidelega = d.codidelega";
						$resOtraJuris = mysql_query($sqlOtraJuris,$db); 
						$canOtraJuris = mysql_num_rows($resOtraJuris);
						if ($canOtraJuris > 0) { 
							while ($rowOtraJuris = mysql_fetch_assoc($resOtraJuris)) {  echo $rowOtraJuris['nombre']." (".$rowOtraJuris['disgdinero']."%)"."<br>"; } 
						} else { 
							echo "-";
						} ?></td>
				</tr>
	  <?php } ?>
	   	   </tbody>
  		</table> 
        <input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/>
<?php } ?>
</form>
</div>
</body>
</html>
