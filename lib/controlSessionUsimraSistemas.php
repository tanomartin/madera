<?php session_save_path($_SERVER['DOCUMENT_ROOT']."/madera/usimra/sessiones");
//si es necesario cambiar la config. del php.ini desde tu script 
ini_set("session.use_only_cookies","1"); 
ini_set("session.use_trans_sid","0"); 
//iniciamos la sesi�n 
session_start(); 
//session_set_cookie_params(0, "/", $HTTP_SERVER_VARS["HTTP_HOST"], 0); 
//cambiamos la duraci�n a la cookie de la sesi�n 
//antes de hacer los c�lculos, compruebo que el usuario est� logueado 
//utilizamos el mismo script que antes 

if(isset($_GET['XDEBUG_SESSION_START'])) {
	include_once ($_SERVER['DOCUMENT_ROOT'].'/madera/lib/generaSessionDebug.php');
}

$redire = "Location: http://".$_SERVER['SERVER_NAME']."/madera/usimra/sesionCaducada.php";
if ($_SESSION['aut'] != 1 || $_SESSION['usuario'] != 'sistemas') { 
    //si no est� logueado lo env�o a la p�gina de autentificaci�n 
	header($redire); 
	exit(0);
} else { 
    //sino, calculamos el tiempo transcurrido 
    $fechaGuardada = $_SESSION["ultimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 
    //comparamos el tiempo transcurrido 
    if($tiempo_transcurrido >= 1200) { 
       //si pasaron 10 minutos o m�s 
   	   header($redire); //env�o al usuario a la pag. de autenticaci�n 
	   exit(0);
      //sino, actualizo la fecha de la sesi�n 
 	}else { 
    	$_SESSION["ultimoAcceso"] = $ahora; 
  	} 
} 
$db =  mysql_connect($_SESSION['host'],$_SESSION['usuario'], $_SESSION['clave']);
if (!$db) {
    die('No pudo conectarse: ' . mysql_error());
    exit(0);
}
mysql_select_db($_SESSION['dbname']);
?>