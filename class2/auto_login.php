<?php //Considerações iniciais: Para que a página reconheça qual comando ela realizará, ao final do nome da página é acrescentado o nome do campo equivalente ao que contém dentro do "$_GET" de cada comando, como por exemplo, para realizar o login o nome da página será "auto_login.php?login". Esta página realiza o intermédio entre a página segura (https) e a não-segura (http), tendo comando exclusivos para cada estilo de página, conforme indicado.

//Comando para efetuar o login do usuário. Esta página será aberta exclusivamente pelo modo seguro (https).
if(isset($_GET["login"])){
	include 'conect.php'; //Include para estabelecer a conexão com o banco de dados.
	include 'session.php'; //Include para iniciar a sessão e estabelecer o timeout.
	$email = $_POST['txtEmail']; //Pega o e-mail do usuário.
	$senha = $_POST['txtSenha']; //Pega a senha do usuário.
	
	
	if(isset($_GET["cookie"])){ //Condição: Se a página for acionada via cookie (no qual é reconhecido pelo campo cookie que há na barra de endereços)...
		$comando = 'SELECT id'
        . ' ,nome'
        . ' ,email'
        . ' ,status'
		. ' FROM login '
        . ' WHERE email = \''.$email.'\''
		. ' AND MD5(senha) = \''.$senha.'\''		
		. ' LIMIT 0, 1 '; //Variável para a consulta no banco de dados ciptografando a senha para caso exista cookie.	
	} else { //...Caso contrário...
		$comando = 'SELECT id'
        . ' ,nome'
        . ' ,email'
        . ' ,status'
		. ' FROM login '
        . ' WHERE email = \''.$email.'\''
		. ' AND senha = \''.$senha.'\''		
		. ' LIMIT 0, 1 '; //Variável para a consulta no banco de dados com a senha normal para caso não exista cookie.
	}
	
	mysql_query("SET NAMES 'utf8'");

	$recordSet = mysql_query($comando) or die(mysql_error()); //Realiza a consulta com o banco de dados.
	
	if (!$recordSet){ //Condição: Caso há problemas na consulta com o banco de dados...
		$_SESSION['mensERRO'] = "Erro na consulta ao banco de dados." . mysql_error(); //Mensagem de erro alertando o erro, não está sendo utilizada no momento.
		
		header('Location: /auto_login.php?logout' ); //Efetua o Logout do usuário.

	}else if(mysql_num_rows($recordSet)==0){ //...Caso não encontre nenhum usuário (e-mail ou senha errados)...
		$_SESSION['mensERRO'] = "Usuário não localizado." . mysql_error(); //Mensagem de erro alertando que não foi encontrado nenhum usuário, não está sendo utilizada no momento, ao invés disso o erro será enviado através do campo "erro" pelo link abaixo.
	
		header ('Location: /auto_login.php?killCookie&erro=erro1'); //Manda para a página de destruir cookie e, junto a ela, o campo "erro", no qual infomará na página principal a frase "Usuário ou senha incorreto".
		
	}else{ //...Caso encontre o usuário...

		$usuario=mysql_fetch_row($recordSet); //Extrai o conteúdo amarzenado no banco de dados

		$id_user		=	$usuario[0]; //Variável que armazenará o ID do usuário.
		$nome_user 		= 	$usuario[1]; //Variável que armazenará o nome do Assinante/usuário.
		$email_user 	= 	$usuario[2]; //Variável que armazenará o email do usuário.
		$status_user	=	$usuario[3]; //Variável que verificará o status do usuário.
		
		$_SESSION["nome_userSecao"]=$nome_user; //Armazena o nome do Assinante/usuário em uma sessão.
		$_SESSION["email_userSecao"]=$email_user; //Armazena o email do usuário em uma sessão.
		$_SESSION["id_userSecao"]=$id_user; //Armazena o id do usuário em uma sessão.
		$_SESSION['status_userSecao']=$status_user; //Armazena o status do usuário em uma sessão.
		$_SESSION["idSecao"]=session_id(); //Armazena o id da sessão em uma sessão.
		$_SESSION['timeout']=time();  //Armazena hora atual uma sessão para ser efetuado o timeout na "session.php".
		
		if(isset($_POST["cheConectado"])){ //Condição: Caso o usuário selecionar o checkbox de "manter conectado"...
			$_SESSION['manterConectado'] = true; //A sessão "manter conectado" passa a ser verdadeira, ignorado o timeout em "session.php".
		} else { //...Caso contrário...
			$_SESSION['manterConectado'] = false; //A sessão "manter conectado" passa a ser falsa, validando o timeout em "session.php".
		}
		
		if ($_SESSION['status_userSecao']=='inativo') { //Condição: Caso o status do usuário seja inativo...
			header ('Location: /cliente_inativo.php'); //Manda para página de cliente inativo.
		}
		
		else { //...Caso contrário...
			if((isset($_SESSION['url'])) and ($_SESSION['url'] != "")) { //Condição: Caso exista a sessão "url" e a mesma não esteja vazia...
				header('Location: ' . $_SESSION['url']); //O usuário será redirecionado para a página que o mesmo tentou acessar quando havia expirado a sessão. O link é registrado através da página "check_login.php".
				unset($_SESSION['url']); //Desativa a sessão após ser utilizada.
			}
			else { //Caso contrário...
				header('Location: /index_restrita.php' ); //O usuário será redirecionado para a "index_restrita.php".
			}
		}
	}
}
//Fim do comando para efetuar o login do usuário.

//Comando para efetuar o logout do usuário. Esta página será aberta exclusivamente pelo modo seguro (https).
if(isset($_GET["logout"])){
	session_start(); //Inicia a sessão.
	session_destroy(); 	//Destroi todas as seções do site.
	header ('Location: /auto_login.php?killCookie'); //Redireciona para a página de destruir cookie.
}
//Fim do comando para efetuar o logout do usuário.

//Comando para destruir o cookie. Esta página será aberta exclusivamente pelo modo não-seguro (http).
if(isset($_GET["killCookie"])){	
	setcookie('contadoramigo','',1); //Seta o tempo para expirar o cookie "Contador Amigo" para 1 segundo de duração, destruindo o cookie.
	
	if (isset($_GET["erro"])) { //Condição: Caso exista algum erro a ser exibido na barra de login...
		$erro = "?" . $_GET["erro"]; //Incluí qual erro a ser exibido no endereço final.
	}

	header ('Location: /index.php' . $erro); //Redireciona para a "index.php".
}
//Fim do comando para destruir o cookie.
?>