<?
session_start();

if(isset($_SESSION["id_userSecao"]) && isset($_SESSION["email_userSecao"]) && isset($_SESSION["nome_assinanteSecao"])){

//	$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
//	$db = mysql_select_db("contadoramigo");
//	mysql_query("SET NAMES 'utf8'");
//	mysql_query('SET character_set_connection=utf8');
//	mysql_query('SET character_set_client=utf8');
//	mysql_query('SET character_set_results=utf8');

	// inclui o arquivo de conexão.
	require_once "conect.php";
	
	$nomedestinatario = $_REQUEST['nome'];
	$emaildestinatario = $_REQUEST['email'];
	$mensagem = $_REQUEST['mensagem'];

	mysql_query("INSERT INTO dados_indicacoes (idUser, nome_amigo, email_amigo) VALUES (".$_SESSION["id_userSecao"].",'".$nomedestinatario."','".$emaildestinatario."')") or die('erro');

	$Assinante = $_SESSION["nome_assinanteSecao"];
	$AssinanteExplode = explode(" ", $Assinante);
	
	$emailsender="'Contador Amigo'<secretaria@contadoramigo.com.br>"; 
	 
	/* Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.  */
	
	if(PATH_SEPARATOR == ";") $quebra_linha = "\r\n"; //Se for Windows
	else $quebra_linha = "\n"; //Se "nao for Windows"
	 
	// Passando os dados obtidos pelo formulário para as variáveis abaixo
	$nomeremetente     = "Contador Amigo";
	$emailremetente    = $emailsender;
	
	$assunto           = $AssinanteExplode[0] . " lhe indicou o Contador Amigo";

		
	include 'mensagens/indica_amigo.php';
		
	 
	/* Montando o cabecalho da mensagem */
	$headers = "MIME-Version: 1.1" .$quebra_linha;
	$headers .= "Content-type: text/html; charset=utf-8" .$quebra_linha;
	// Perceba que a linha acima contém "text/html", sem essa linha, a mensagem não chegará formatada.
	$headers .= "From: " . $emailsender.$quebra_linha;
	//$headers .= "Cc: " . $comcopia . $quebra_linha;
	
	if($comcopiaoculta != ""){
		$headers .= "Bcc: " . $comcopiaoculta . $quebra_linha;
	}
	
	$headers .= "Reply-To: " . $emailremetente . $quebra_linha;
	// Note que o e-mail do remetente será usado no campo Reply-To (Responder Para)

	/* Enviando a mensagem */
	//É obrigatório o uso do parâmetro -r (concatenação do "From na linha de envio"), aqui na Locaweb:
	if(!mail($emaildestinatario, $assunto, $mensagemHTML, $headers ,"-r".$emailsender)){ // Se for Postfix

		$headers .= "Return-Path: " . $emailsender . $quebra_linha; // Se "não for Postfix"
		
		mail($emaildestinatario, $assunto, $mensagemHTML, $headers );

	}

	echo "1";
	
	
}
?>