<?php
//Considerações iniciais: Para que a página reconheça qual comando ela realizará, ao final do nome da página é acrescentado o nome do campo equivalente ao que contém dentro do "$_GET" de cada comando, como por exemplo, para realizar o login o nome da página será "auto_login.php?login". Esta página realiza o intermédio entre a página segura (https) e a não-segura (http), tendo comando exclusivos para cada estilo de página, conforme indicado.
//ini_set('session.cookie_secure',true);
// CÓDIGO para fazer o cookie funcionar no IE
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

//	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30));
//	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.com.br', 0);
//	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.websiteseguro.com', 0);
//	unset($_COOKIE['contadoramigoHTTPS']);
/*
// expressão regular para checar o domínio que está acessando do site
preg_match('#^(1[2,9][2,7]).([0]|[168])#',$_SERVER['HTTP_HOST'],$checkDominio);
*/

//$dominio_seguro = "https://contadoramigo.websiteseguro.com/";
$dominio_seguro = "https://www.contadoramigo.com.br/";
$dominio = $dominio_seguro;

// INCLUI ESTE IF ANTES DE TUDO PARA EXPIRAR O COOKIE DE MANEIRA CORRETA
//include 'session.php'; //Include para iniciar a sessão e estabelecer o timeout.
include 'conect.php'; //Include para estabelecer a conexão com o banco de dados.

if(isset($_REQUEST['admin'])){
	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30));
	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.com.br', 0);
	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.websiteseguro.com', 0);
	unset($_COOKIE['contadoramigoHTTPS']);
	
	$dadosCookie = explode(" ",$_COOKIE['contadoramigoADMIN']);
	$email = $dadosCookie[0];
	$senha = $dadosCookie[1];
	setcookie('contadoramigoHTTPS', $email . " " . $senha, time()+(120*120*24*30), '/', 'contadoramigo.com.br', 0);	
	session_start();
	session_destroy();
	header("location: ".$dominio."auto_login.php?login&cookie");
	exit;
}

//var_dump($_COOKIE['contadoramigoHTTPS']);

//Comando para efetuar o login do usuário. Esta página será aberta exclusivamente pelo modo seguro (https).
if(isset($_REQUEST["login"])){



	if(isset($_POST["cheConectado"])){ //Condição: Caso o usuário selecionar o checkbox de "manter conectado"...
		setcookie('contadoramigoHTTPS', $_POST['txtEmail'] . " " . md5($_POST['txtSenha']), time()+(120*120*24*30), '/', 'contadoramigo.com.br', 0);

		session_start();
		$_SESSION['manterConectado'] = true; //A sessão "manter conectado" passa a ser verdadeira, ignorado o timeout em "session.php".

		
	} else { //...Caso contrário...
		session_start();
		$_SESSION['manterConectado'] = false; //A sessão "manter conectado" passa a ser falsa, validando o timeout em "session.php".
	}

	$email = $_POST['txtEmail']; //Pega o e-mail do usuário.
	$senha = $_POST['txtSenha']; //Pega a senha do usuário.
	
	if(isset($_GET['txtEmail']) || isset($_POST['txtEmail'])){
		
		session_destroy();
		session_start();
		
	}
	
	if($email == "" || $senha == ""){
		$email = $_GET['txtEmail']; //Pega o e-mail do usuário.
		$senha = $_GET['txtSenha']; //Pega a senha do usuário.
	}

	if($email == "" || $senha == ""){
		$email = $_SESSION['emailAssinatura']; //Pega o e-mail do usuário que acaba de assinar.
		$senha = $_SESSION['senhaAssinatura']; //Pega a senha do usuário que acaba de assinar.
	}
	
	
	if(isset($_GET["cookie"])){ //Condição: Se a página for acionada via cookie (no qual é reconhecido pelo campo cookie que há na barra de endereços)...

		$dadosCookie = explode(" ",$_COOKIE['contadoramigoHTTPS']);
		$email = $dadosCookie[0];
		$senha = $dadosCookie[1];

		//setcookie("contadoramigo", $email . " " . $senha, time()+(60*60*24*30), "/", "contadoramigo.com.br", 0);
		$comando = "
				SELECT id
					, nome
					, assinante
					, email
					, status
					, info_preliminar
					, id_plano
					, idUsuarioPai
				FROM 
					login
				WHERE 
					email = '" . $email . "'
					AND MD5(senha) = '" . $senha . "'	
					AND id = idUsuarioPai	
				LIMIT 0, 1 "; //Variável para a consulta no banco de dados ciptografando a senha para caso exista cookie.	
	} else { //...Caso contrário...
	
		if(isset($_SESSION['emailAssinatura'])){ // se o usuário está vindo da página de assinatura, as variaveis de email e senha estão vindo da SESSION e a senha deve ser consultada com MD5
			$comando = "
					SELECT id
						, nome
						, assinante
						, email
						, status
						, info_preliminar
						, id_plano
						, idUsuarioPai
					FROM 
						login
					WHERE 
						email = '" . $email . "'
						AND MD5(senha) = '" . $senha . "'		
						AND id = idUsuarioPai
					LIMIT 0, 1 "; //Variável para a consulta no banco de dados ciptografando a senha para caso exista cookie.	
		} else { 
			$comando = "
				SELECT id
					, nome
					, assinante
					, email
					, status
					, info_preliminar
					, id_plano
					, idUsuarioPai
				FROM 
					login 
				WHERE 
					email = '" . $email . "'
					AND senha = '" . $senha . "'
					AND id = idUsuarioPai
				LIMIT 0, 1 "; //Variável para a consulta no banco de dados com a senha normal para caso não exista cookie.
		}
	}

	mysql_query("SET NAMES 'utf8'");

	$recordSet = mysql_query($comando) or die(mysql_error()); //Realiza a consulta com o banco de dados.

	$usuario=mysql_fetch_row($recordSet); //Extrai o conteúdo amarzenado no banco de dados

	if (!$recordSet){ //Condição: Caso há problemas na consulta com o banco de dados...

		$_SESSION['mensERRO'] = "Erro na consulta ao banco de dados." . mysql_error(); //Mensagem de erro alertando o erro, não está sendo utilizada no momento.
		
		header('Location: '.$dominio.'auto_login.php?logout' ); //Efetua o Logout do usuário.
		exit;

	}else if(mysql_num_rows($recordSet)==0){ //...Caso não encontre nenhum usuário (e-mail ou senha errados)...

		$_SESSION['mensERRO'] = "Usuário não localizado." . mysql_error(); //Mensagem de erro alertando que não foi encontrado nenhum usuário, não está sendo utilizada no momento, ao invés disso o erro será enviado através do campo "erro" pelo link abaixo.
		
		header ('Location: '.$dominio.'auto_login.php?killCookie&erro=erro1'); //Manda para a página de destruir cookie e, junto a ela, o campo "erro", no qual infomará na página principal a frase "Usuário ou senha incorreto".
		exit;
		
	} else{ //...Caso encontre o usuário...

		session_start(); // INICIANDO A SESSAO
		
		$id_user								=	$usuario[0]; //Variável que armazenará o ID do usuário.

		//$rsDadosLoginMultiplo		= mysql_query("SELECT id, nome, assinante, email, status, info_preliminar, id_plano, idUsuarioPai FROM login WHERE id = " . $id_user);// retornando os dados dos registros vinculados ao usuario logado

		//$usuario								= mysql_fetch_row($rsDadosLoginMultiplo);

//echo "SELECT id, nome, assinante, email, status, info_preliminar, id_plano, idUsuarioPai FROM login WHERE id = " . $id_user . " AND id = idUsuarioPai";
//exit;

		$nome_user 							= $usuario[1]; //Variável que armazenará o nome da Empresa.
		$assinante_user					=	explode(" ", $usuario[2]); //Variável que armazenará o nome do Assinante/usuário.
		$email_user 						= $usuario[3]; //Variável que armazenará o email do usuário.
		$status_user						=	$usuario[4]; //Variável que verificará o status do usuário.		
		$info_preliminar				=	$usuario[5]; //Variável que verificará se o usuario esta acessando pela primeira vez.
		$id_plano								=	$usuario[6]; //Variável que verificará o plano do usuario
		$idUsuarioMultiplo			=	$usuario[7]; //Variável que guardará o id do usuario multiplo

		$rsEmpresasUsuario			= mysql_query("SELECT l.id FROM login l INNER JOIN dados_da_empresa d ON l.id = d.id WHERE l.idUsuarioPai = " . $id_user . " AND d.ativa = 1");
		
		$empresasVinculadas			= 0;



		$_SESSION["nome_userSecao"]					= $nome_user; //Armazena o nome da empresa em uma sessão.
		$_SESSION["nome_assinanteSecao"]		= $assinante_user[0]; //Armazena o nome do assinante/usuário em uma sessão.
		$_SESSION["email_userSecao"]				= $email_user; //Armazena o email do usuário em uma sessão.
		$_SESSION["id_userSecaoMultiplo"]		= $idUsuarioMultiplo; //Armazena o id do usuário em uma sessão.
		$_SESSION["id_userSecao"]						= $id_user; //Armazena o id da empresa em uma sessão.
		$_SESSION["id_PlanoUserSecao"]			= $id_plano; //Armazena o id do plano de assinatura do usuário.
		$_SESSION['status_userSecao']				= $status_user; //Armazena o status do usuário em uma sessão.

		$_SESSION['n_empresasVinculadas']		= $empresasVinculadas; //Armazena a quantidade de empresas vinculadas à conta logada

		$_SESSION["idSecao"]								= session_id(); //Armazena o id da sessão em uma sessão.
		$_SESSION['timeout']								= time();  //Armazena hora atual uma sessão para ser efetuado o timeout na "session.php".

		if((int)mysql_num_rows($rsEmpresasUsuario) > 0){ // SE O USUARIO POSSUIR PELO MENOS UMA EMPRESA CADASTRADA
	
			$empresasVinculadas			= mysql_num_rows($rsEmpresasUsuario);// - 1;

			$_SESSION['n_empresasVinculadas']		= $empresasVinculadas; //Armazena a quantidade de empresas vinculadas à conta logada

			if((int)mysql_num_rows($rsEmpresasUsuario) == 1){ // SE O USUARIO POSSUIR APENAS UMA EMPRESA CADASTRADA
			
				$rs = mysql_fetch_array($rsEmpresasUsuario);
				$id_unica_empresa = $rs['id'];
				
				$rsDadosEmpresaLogin			= mysql_query("SELECT l.id, l.nome, l.assinante, l.email, l.status, l.info_preliminar, l.id_plano, l.idUsuarioPai, emp.id, razao_social, cnpj, nome_fantasia, ativa, data_desativacao  FROM dados_da_empresa emp INNER JOIN login l ON emp.id = l.id  WHERE l.id = '" . $id_unica_empresa . "' AND emp.ativa = 1");
				
				if($rsDadosEmpresaLogin){
					
					$dadosEmpresaLogin			= mysql_fetch_row($rsDadosEmpresaLogin);
					
					$nome_user 							= $dadosEmpresaLogin[1]; //Variável que armazenará o nome da Empresa.
					$assinante_user					=	explode(" ", $dadosEmpresaLogin[2]); //Variável que armazenará o nome do Assinante/usuário.
					$email_user 						= $dadosEmpresaLogin[3]; //Variável que armazenará o email do usuário.
					$status_user						=	$dadosEmpresaLogin[4]; //Variável que verificará o status do usuário.		
					$info_preliminar				=	$dadosEmpresaLogin[5]; //Variável que verificará se o usuario esta acessando pela primeira vez.
					$id_plano								=	$dadosEmpresaLogin[6]; //Variável que verificará o plano do usuario
					$idUsuarioMultiplo			=	$dadosEmpresaLogin[7]; //Variável que guardará o id do usuario multiplo
					$empresa_ativa					= $dadosEmpresaLogin[12];
									
					$_SESSION["nome_userSecao"]					= $nome_user; //Armazena o nome da empresa em uma sessão.
					$_SESSION["nome_assinanteSecao"]		= $assinante_user[0]; //Armazena o nome do assinante/usuário em uma sessão.
					$_SESSION["id_userSecaoMultiplo"]		= $idUsuarioMultiplo; //Armazena o id do usuário em uma sessão.
					$_SESSION["id_userSecao"]						= $id_unica_empresa; //Armazena o id da empresa em uma sessão.
					$_SESSION["id_empresaSecao"]				= $id_unica_empresa; //CONTROLA SE O USUARIO SELECIONOU UMA EMPRESA PARA GERENCIAR.
					
					$_SESSION['statusEmpresaSelecionada']	= $empresa_ativa;
				}
			
			}

						
		}

//		$queryCheckQtdEmpresas 		= "SELECT id FROM login WHERE idUsuarioPai = " . $_SESSION["id_userSecaoMultiplo"];// retornando a quantidade de registros vinculados ao usuario logado
//		$rsCheckQtdEmpresas 			= mysql_query($queryCheckQtdEmpresas) or die(mysql_error()); //Realiza a consulta com o banco de dados.
//		$qtdEmpresas							=	mysql_num_rows($rsCheckQtdEmpresas); //Extrai o conteúdo amarzenado no banco de dados
		
		$_SESSION['qtdEmpresas']	= $empresasVinculadas;
		
		if (($_SESSION['status_userSecao']=='inativo') or ($_SESSION['status_userSecao']=='demoInativo') or ($_SESSION['status_userSecao']=='cancelado')) {

			header('Location: '.$dominio_seguro.'minha_conta.php');
			exit;

		} else { //...Caso contrário...


			if((isset($_SESSION['url'])) and ($_SESSION['url'] != "")) { //Condição: Caso exista a sessão "url" e a mesma não esteja vazia...


				$URL = $_SESSION['url'];
				unset($_SESSION['url']); //Desativa a sessão após ser utilizada.
				header('Location: '.$dominio_seguro.$URL);
				exit;

			} else { //Caso contrário...

				if ($info_preliminar === '0') {
/*					$sql = "UPDATE login SET info_preliminar='1' WHERE id='$id_user'";
					$resultado = mysql_query($sql)
					or die (mysql_error());*/

					header('Location: '.$dominio_seguro.'procedimentos_iniciais.php');
					exit;

				}			
				
				else {
			
//			echo "aqui<BR>";
//if($_SESSION["id_userSecaoMultiplo"] == 1581){
//			echo var_dump($_SESSION);
//			exit;
//}
			
					if($_SESSION['n_empresasVinculadas'] > 1){
						header('Location: '.$dominio_seguro.'gerenciar_empresa.php');
						exit;
					} elseif($_SESSION['n_empresasVinculadas'] == 1){
//						header('Location: '.$dominio_seguro.'index_restrita.php');
						header('Location: '.$dominio_seguro.'meus_dados_empresa_gerenciar.php?id=' . $id_unica_empresa);
						exit;
					} else {
//						header('Location: '.$dominio_seguro.'meus_dados_empresa.php?act=new');
							header('Location: '.$dominio_seguro.'index_restrita.php');
						exit;
					}
				} //O usuário será redirecionado para a "index_restrita.php".
			}
		}

	}
}
//Fim do comando para efetuar o login do usuário.



//Comando para efetuar o logout do usuário. Esta página será aberta exclusivamente pelo modo seguro (https).
if(isset($_GET["logout"])){
	
	session_start();

	$_SESSION = array();

	session_destroy();	

	header('Location: '.$dominio.'auto_login.php?killCookie'); //Redireciona para a página de destruir cookie.

}
//Fim do comando para efetuar o logout do usuário.

//Comando para destruir o cookie. Esta página será aberta exclusivamente pelo modo não-seguro (http).
if(isset($_GET["killCookie"])){	

	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30));
	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.com.br', 0);
	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.websiteseguro.com', 0);
	unset($_COOKIE['contadoramigoHTTPS']);

	if (isset($_GET["erro"])) { //Condição: Caso exista algum erro a ser exibido na barra de login...
		$erro = "?erro=" . $_GET["erro"]; //Incluí qual erro a ser exibido no endereço final.
	}

	header('Location: '.$dominio.'index.php' . $erro); //Redireciona para a "index.php".
	exit;
	
}
//Fim do comando para destruir o cookie.
?>