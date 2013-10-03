<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 

$listadoSerializado=$_POST['empresas'];
$listadoEmpresas = unserialize(urldecode($listadoSerializado));

$datosSerializado=$_POST['datosReq'];
$listadoDatosReq = unserialize(urldecode($datosSerializado));


$solicitante=$listadoDatosReq['solicitante'];
$motivo = $listadoDatosReq['motivo'];
$origen = $listadoDatosReq['origen'];
print("DATOS FILSCALIZACION");
var_dump($listadoDatosReq);

print("DEUDA DE EMPRESAS FILSCALIZDAS<br><br>");
for($i=0; $i < sizeof($listadoEmpresas); $i++) {
	print("CUIT: ".$listadoEmpresas[$i]['cuit']);
	$deuda = $listadoEmpresas[$i]['deudas'];
	for($n=0; $n < sizeof($deuda); $n++) {
		$estado = $deuda[$n]['estado'];
		if ($estado != 'S') {
			if ($estado == 'A') {
				$deuda[$n]['deudaNominal'] = (float)($deuda[$n]['remu'] * $alicuota);
				$deuda[$n]['menor240'] = "CALCULAR";
			} else {
				$apagar = (float)($deuda[$n]['remu'] * 0.081);
				$deuda[$n]['deudaNominal'] = (float)($apagar - $deuda[$n]['importe']);
				$deuda[$n]['menor240'] = "CALCULAR";
			}
		} else {
			$deuda[$n]['remu'] = 0.00;
			$deuda[$n]['totper'] = 0;
			$deuda[$n]['deudaNominal'] = 0.00;
			$deuda[$n]['menor240'] = 0;
		}	
	}
	var_dump($deuda);
}

?>