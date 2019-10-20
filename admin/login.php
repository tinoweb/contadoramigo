<?php 

session_start();

	// Classe responsavel por evitar sql injection atraves do GET, POST ou COOKIES
	class Anti_Sql_Injection{
		
		private function sql_injection($sql) {
			//Remove comandos SQL da string
		    $sql = preg_replace(sql_regcase("/(from|select|insert|delete|union|where| OR |OR | OR|drop table|show tables|--|\\\\)/"),"",$sql);
		    //Remove espacos do inicio o  fim da string
		    $sql = trim($sql);
		    //Remove tags html da string
		    $sql = strip_tags($sql);
		    //Adiciona \ (barra invertida) antes de ' ou "
		    $sql = addslashes($sql);
		    return $sql;
		}

		function execute(){
			$this->securityGET();
			$this->securityPOST();
			$this->securityCOOKIE();
		}
		private function securityGET(){
			foreach ($_GET as $key => $value) {
				//Aplica Anti SQL injection em todos os indices do GET
				$_GET[$key] = $this->sql_injection($_GET[$key]);
			}
		}
		private function securityPOST(){
			foreach ($_POST as $key => $value) {
				//Aplica Anti SQL injection em todos os indices do POST
				$_POST[$key] = $this->sql_injection($_POST[$key]);
			}
		}
		private function securityCOOKIE(){
			foreach ($_COOKIE as $key => $value) {
				//Aplica Anti SQL injection em todos os indices do COOKIE
				$_COOKIE[$key] = $this->sql_injection($_COOKIE[$key]);
			}
		}

	}

	$security = new Anti_Sql_Injection();
	$security->execute();

?>
<?php
// CÓDIGO para fazer o cookie funcionar no IE
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

include '../conect.php';

//$dominio_seguro = "http://www.contadoramigo.com.br/";
$URI = explode($_SERVER['SCRIPT_URL'], $_SERVER['SCRIPT_URI']);

$dominio_seguro = $URI[0];	

if($_POST){
	
	$manter = isset($_POST['manter']) && !empty($_POST['manter']) ? $_POST['manter'] : 0;

	setcookie('ContAmi',$_POST['txtEmail']." ".$_POST['txtSenha']." ".$manter, time()+(120*120*24*30),"/", str_replace('www.', '', $_SERVER['SERVER_NAME']),0);
	$email = $_POST['txtEmail'];
	$senha = $_POST['txtSenha'];
	$manter = $_POST['manter'];
	
}else{
	
	// Verifica se e para manter lohin anterior.
	$ContAmi = explode(" ", $_COOKIE["ContAmi"]);
	
	//$manter = $ContAmi[2];
	$contAdmMail = $ContAmi[0];
	$contAdmPass = $ContAmi[1];
	$manterCookie = $ContAmi[2];
	
	// Verefica se pode logar com o cookie
	if(isset($_SESSION["IsAdmAuthorized"])) {
		header('Location: '.$dominio_seguro.'/admin/clientes_lista.php');
		exit;die();
	} elseif($manterCookie == 0) {
		header('Location: '.$dominio_seguro.'/admin/index.php' );
		exit;
	}
	
	$email = $contAdmMail;
	$senha = $contAdmPass;	
}

include '../session.php';

$comando = "SELECT id
	,nome
	,email
	FROM admin 
	WHERE email = '".$email."'
	AND senha = '".$senha."'	
	LIMIT 0, 1 "; 

mysql_query("SET NAMES 'utf8'");

$recordSet = mysql_query($comando) or die(mysql_error());

if (!$recordSet){
	$_SESSION['mensERRO'] = "Erro na consulta ao banco de dados.";
//		echo '1';
//		exit;
	header('Location: '.$dominio_seguro.'/admin/index.php' );
	exit;

}

if(mysql_num_rows($recordSet)==0){

	$_SESSION['mensERRO'] = "Usuário não localizado.";

	// Remove os dados de login do cliente.
	setcookie('ContAmi','', time()+(120*120*24*30),"/", str_replace('www.', '', $_SERVER['SERVER_NAME']),0);
	
	header('Location: '.$dominio_seguro.'/admin/index.php' );
	exit;
	
}else{
	
	$usuario = mysql_fetch_row($recordSet);

	$id_user		=	$usuario[0];
	$nome_user 		= 	$usuario[1];
	$email_user 	= 	$usuario[2];
			
	$_SESSION["nomeAdm_userSecao"]=$nome_user;
	$_SESSION["emailAdm_userSecao"]=$email_user;
	$_SESSION["idAdm_userSecao"]=$id_user;
	$_SESSION["IsAdmAuthorized"]="ok";
	$_SESSION["idAdmSecao"]=session_id();

	$mensagem = "ok";

	$_SESSION['timeout']=time();
	if( $_SERVER['SCRIPT_URL'] != '' && strlen($_COOKIE['lastPage']) > 8 ){
		header('Location: '.$dominio_seguro.$_COOKIE['lastPage'] );
	}
	else{
		header('Location: '.$dominio_seguro.'/admin/clientes_lista.php');
	}
}
?>