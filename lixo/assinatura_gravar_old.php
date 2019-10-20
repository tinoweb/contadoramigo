<!-- Google Code for assinatura Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1067575546;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "TgP2CIWs51cQ-tGH_QM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1067575546/?label=TgP2CIWs51cQ-tGH_QM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<?php




include "conect.php";

/*
id_plano

1	=	plano comum
2 = plano premim
3 = plano multiplo
*/


if(isset($_POST["txtAssinante"])){
	
	$Assinante = mysql_real_escape_string($_POST["txtAssinante"]);
	$AssinanteEmPartes = explode(" ",$Assinante);
	$AssinantePrimeiroNome = $AssinanteEmPartes[0];
	$Email = $_POST["txtEmailAssina"];
	$Senha = $_POST["txtSenhaAssina"];
	$PrefixoTelefoneCobranca = $_POST["txtPrefixoTelefoneCobranca"];
	$TelefoneCobranca = $_POST["txtTelefoneCobranca"];
	$DataInclusao = date("Y-m-d");
	$dataPagamento = date('Y-m-d',(mktime(0,0,0,date('m'),date('d')+30,date('Y'))));
	
	//Gravar dados em login.
	$sql = "INSERT INTO login (nome, assinante, email, senha, status, id_plano) VALUES ('$RazaoSocial', '$Assinante', '$Email', '$Senha', 'demo', '1')";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	//Pegar o id de login para utilizar nas demais tabelas.
	$sql = "SELECT * FROM login WHERE email='" . $Email . "' ORDER BY id DESC LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$linha=mysql_fetch_array($resultado);
	$id = $linha["id"];

	//ATUALIZAR O ID USUARIO PAI COM O ID DO USUARIO RECEM CADASTRADO.
	$sql = "UPDATE login SET idUsuarioPai = id WHERE id = '" . $id . "'";
	$resultado = mysql_query($sql)
	or die (mysql_error());

	// LOG DE ACESSOS
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $id . ",'ASSINATURA: NOVO ASSINANTE MULTIPLO')");
	
	// INSERE NA TABELA DE METRICAS
	mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $id . ",'assinatura','" . date('Y-m-d') . "')");

	//Gravar dados em dados de cobrança.
	$sql = "INSERT INTO dados_cobranca (id, assinante, pref_telefone, telefone, data_inclusao, forma_pagameto, numero_cartao, codigo_seguranca, nome_titular, data_validade) VALUES ('$id', '$Assinante', '$PrefixoTelefoneCobranca', '$TelefoneCobranca', '$DataInclusao', 'boleto', NULL, NULL, NULL, NULL)";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	//Gravar dados em histórico de cobrança.
	$sql = "INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('$id', '$dataPagamento', 'a vencer')";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	//$Assinante = $assinante;
	//$AssinanteExplode = explode(" ", $Assinante);
	//$Razao = $razao_social;
	//$assuntoMail = "Inscrição confirmada";
	//$emailuser = "secretaria@contadoramigo.com.br";
	//$comcopiaoculta = "secretaria@contadoramigo.com.br";
	
	/* Montando a mensagem a ser enviada no corpo do e-mail. */
	//include "mensagens/inscricao_confirmada.php";
	//include 'mensagens/componente_envio.php';


	// inclusao do cadastrado no emkt da Locaweb - lista trial
	
	error_reporting(E_ALL);
		require_once dirname(__FILE__).'/emkt/lib/RepositorioContatos.php';
		// Esses valores podem ser obtidos na página de configurações do
		// Email Marketing
	
		$hostName = 'emailmkt7';
		$login 	  = 'contadoramigo1';
		$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
		$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);
	
	
		//print "\ninserir contatos\n";
	
		// Campos disponíveis: ,cep,cidade,datadenascimento,departamento,email,empresa,endereco,estado
		//                     htmlemail,nome,sexo,sobrenome
		//
		// Todos os campos são opcionais com a exceção do campo email:
		// array_push($contatos, array(''=>'',"cep"=>"", "cidade"=>"", "datadenascimento"=>"",
		//							"departamento"=>"","email"=>"campo obrigatorio","empresa"=>"","endereco"=>"",
		//							"estado"=>"", "htmlemail"=>"","nome"=>"","sexo"=>"","sobrenome"=>""));
	
		$contatos = array();
		// precisa acertar o charset do nome do assinante pois estava dando erro na listagem da newsletter utf8_decode()
		array_push($contatos, array('email'=>$Email, 'nome'=>utf8_decode($AssinantePrimeiroNome)));
	
		//Inserir contato na lista DEMO
		$repositorio->importar($contatos, array(38414));
	
	session_start();
	$_SESSION['emailAssinatura'] = $_POST["txtEmailAssina"];
	$_SESSION['senhaAssinatura'] = md5($_POST["txtSenhaAssina"]);
	
	/* Redirecionando para a página de sucesso */
//	header('Location: http://www.contadoramigo.com.br/assinatura_sucesso.php');
	//header('Location: http://www.contadoramigo.com.br/auto_login.php?login');
	header('Location: auto_login.php?login');
}else{
	/* Redirecionando para a página de assinatura */
	//header('Location: http://www.contadoramigo.com.br/assinatura.php');
	header('Location: assinatura.php');
}
?>

