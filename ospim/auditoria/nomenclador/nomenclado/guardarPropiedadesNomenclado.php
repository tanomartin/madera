<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$i=0;
$idNomenclador = $_GET['codigo'];
foreach($_POST as $key => $value) {
	$resultado = strpos($key, "codigopractica");
	if($resultado !== FALSE){
		$sqlUpdatePractica[$i] = "UPDATE practicas SET ";
		$codigoPractica = $value;
	}
	$resultado = strpos($key, "unihonorariosolo");
	if($resultado !== FALSE){
		$unihonorario = $value;
		if ($unihonorario != '') { $sqlUpdatePractica[$i] .= "unihonorario = $unihonorario, "; }
	}
	$resultado = strpos($key, "unihonorarioespecialista");
	if($resultado !== FALSE){
		$unihonorarioespecialista = $value;
		if ($unihonorarioespecialista != '') { $sqlUpdatePractica[$i] .= "unihonorarioespecialista = $unihonorarioespecialista, "; }
	}
	$resultado = strpos($key, "unihonorarioayudante");
	if($resultado !== FALSE){
		$unihonorarioayudante = $value;
		if ($unihonorarioayudante != '') { $sqlUpdatePractica[$i] .= "unihonorarioayudante = $unihonorarioayudante, "; }
	}
	$resultado = strpos($key, "unihonorarioanestesista");
	if($resultado !== FALSE){
		$unihonorarioanestesista = $value;
		if ($unihonorarioanestesista != '') { $sqlUpdatePractica[$i] .= "unihonorarioanestesista = $unihonorarioanestesista, "; }
	}
	$resultado = strpos($key, "unigastos");
	if($resultado !== FALSE){
		$unigastos = $value;
		if ($unigastos != '') { $sqlUpdatePractica[$i] .= "unigastos = $unigastos, "; }
	}
	$resultado = strpos($key, "complejidad");
	if($resultado !== FALSE){
		$codigoComplejidad = $value;
		$sqlUpdatePractica[$i] .= "codigocomplejidad = $codigoComplejidad WHERE codigopractica = '$codigoPractica' and nomenclador = '$idNomenclador'";
		$i++;
	}
	
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	foreach($sqlUpdatePractica as $sqlUpdate) {
		//print($sqlUpdate."<br>");
		$dbh->exec($sqlUpdate);
	}
	
	$dbh->commit();
	$pagina = "menuNomenclado.php?codigo=".$idNomenclador;
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>