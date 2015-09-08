<?php
$sql = "select e.*, l.nomlocali, p.descrip as nomprovin from empresas e, localidades l, provincia p where e.cuit = $cuit and e.codlocali = l.codlocali and e.codprovin = p.codprovin";
$result = mysql_query($sql,$db);
$cant = mysql_num_rows($result);
if ($cant == 0) {
	$sql = "select e.*, l.nomlocali, p.descrip as nomprovin from empresasdebaja e, localidades l, provincia p where e.cuit = $cuit and e.codlocali = l.codlocali and e.codprovin = p.codprovin";
	$result = mysql_query($sql,$db);
	$cant = mysql_num_rows($result);
	if ($cant == 0) {
		$tipo = "noexiste";
	} else {
		$row = mysql_fetch_array($result); 
		$fechaBaja = $row['fechabaja'];
		$tipo = "baja";
	}
} else { 
	$row = mysql_fetch_array($result); 
	$tipo = "activa";
}
?>