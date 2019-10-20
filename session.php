<?
//INI_Set('session.cookie_secure',true);
ini_set('session.cookie_secure',true);
session_start();

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// 3000 mins in seconds
$inactive = 180000;  
$session_life = time() - $_SESSION['timeout']; 
if( ($session_life > $inactive) && isset($_SESSION['timeout']) and ($_SESSION['manterConectado'] == false) ) 
{  
	session_destroy(); 
	session_start();
	$_SESSION["mensERRO"] = "Desconectado por inatividade.";
	
	$URI = explode($_SERVER['SCRIPT_URL'], $_SERVER['SCRIPT_URI']);
    header('Location: '.$URI[0].'/auto_login.php?logout' );
	
	//header("Location: https://www.contadoramigo.com.br/auto_login.php?logout");
	exit;
} 
else
{
	$_SESSION['timeout'] = time();
}

?>