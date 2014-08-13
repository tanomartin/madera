<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php");
include($libPath."fechas.php");

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0) {
	$hostOspim = "localhost"; //para las pruebas...
}
$dbhInternet = new PDO("mysql:host=$hostOspim;dbname=$baseOspimIntranet",$usuarioOspim ,$claveOspim);
$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbhInternet->beginTransaction();
$sth = $dbhInternet->prepare("SELECT delcod, nombre, fechaactualizacion FROM usuarios where delcod >= 1002 and delcod <= 3101 order by delcod");
$sth->execute();
$resultado = $sth->fetchAll();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Stock :.</title>
</head>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
.Estilo7 {font-weight: bold}
</style>
<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{4:{sorter:false, filter:false}},
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
	
	function cartelEspere(nombre, dele) {
		var mensaje = "<h1>Actualizando Delegacion "+ nombre + " ("+dele+")"+" <br>Aguarde por favor...</h1>"
		var pagina = 'actualizarIntraDelegacion.php?delcod='+dele;
		location.href=pagina;
		$.blockUI({ message: mensaje });
	}
	
</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuActualizacionOspim.php'" align="center"/>
  </span></p>
  <p><span class="Estilo2">Men&uacute; Actualizacion Intranet O.S.P.I.M. </span></p>
    <p><strong>Seleccionar Delegación a Actualizar</strong></p>
    <table class="tablesorter" id="listado" style="width:600px; font-size:14px">
  	<thead align="center">
		<tr>
		  <th>Codigo</th>
		  <th>Nombre</th>
		  <th>Fecha Actualizacion</th>
		  <th>Timpo Trascurrido</th>
		  <th>Acción</th>
		</tr>
	 </thead>
	<tbody>
    <?php	
		foreach($resultado as $res) { 
			$fechaAct = $res['fechaactualizacion'];
			$today = date("Y-m-d");			
			$dias = (strtotime($fechaAct)-strtotime($today))/86400;
			$dias = abs($dias); 
			$dias = floor($dias);		
			
	 ?>
			<tr align="center">
				<td><?php echo $res['delcod'] ?></td>
				<td><?php echo $res['nombre'] ?></td>
				<td><?php echo invertirFecha($fechaAct) ?></td>
				<td><?php echo $dias." días" ?></td>
				<td><input name="actualizar" type="button" value="Actualizar" onclick="cartelEspere('<?php echo $res['nombre'] ?>','<?php echo $res['delcod'] ?>')"/></td>
			</tr> 
	<?php } ?>
  </table>
    <p>
      <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/>
    </p>
</div>
</body>
</html>
