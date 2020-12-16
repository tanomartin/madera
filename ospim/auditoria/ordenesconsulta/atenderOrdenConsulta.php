<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."claves.php");

$id = $_GET['id'];
$accion = $_GET['accion'];
$fechaEstado = date ("Y-m-d H:i:s");

$sqlAtenderOrden = "UPDATE ordenesconsulta SET autorizada = $accion, fechaestado = '$fechaEstado' WHERE id = $id";
$sqlDeleteHCInte = "DELETE FROM ordenesconsultadoc WHERE id = $id";
try {
    $maquina = $_SERVER ['SERVER_NAME'];
    if(strcmp("localhost",$maquina)==0) {
        $hostOspim = "localhost"; //para las pruebas...
    }
    $dbhInternet = new PDO ( "mysql:host=$hostOspim;dbname=$baseOspimIntranet", $usuarioOspim, $claveOspim );
    $dbhInternet->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $dbhInternet->beginTransaction ();
    
    $hostname = $_SESSION ['host'];
    $dbname = $_SESSION ['dbname'];
    $dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $_SESSION ['usuario'], $_SESSION ['clave'] );
    $dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $dbh->beginTransaction();
   
    //echo $sqlAtenderOrden;
    $dbhInternet->exec ($sqlAtenderOrden);
    $dbh->exec ($sqlAtenderOrden);
    $dbhInternet->exec ($sqlDeleteHCInte);
    
    $dbhInternet->commit();
    $dbh->commit();
    
    $pagina = "listarOrdenesAutorizar.php";
    Header ( "Location: $pagina" );
} catch ( PDOException $e ) {
    $error =  $e->getMessage();
    $dbh->rollback();
    $dbhInternet->rollback();
    $redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
    header ($redire);
    exit(0);
}

?>