<?php include '../conect.php';
include '../session.php';
include 'check_login.php' ?>

<?

function selected( $value, $prev ){
  return $value==$prev ? ' selected="selected"' : ''; 
};

function my_file_get_contents( $site_url ){
	$ch = curl_init();
	$timeout = 10;
	curl_setopt ($ch, CURLOPT_URL, $site_url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	return $file_contents;
}



/*
status

assinatura_inativa
assinatura_reativada
boleto_a_vencer
boleto_compensado
cartao_autorizado
cartao_nao_autorizado
demo_a_vencer
demo_inativo
	 

mensagens
assinatura_inativa.html
assinatura_reativada.html
boleto_a_vencer.html
boleto_compensado.html
cartao_autorizado.html
cartao_nao_autorizado.html
demo_a_vencer.html
demo_inativo.html

listas de contatos
assinatura_inativa						57372
assinatura_reativada					57375
boleto_a_vencer								57368
boleto_compensado							57367
cartao_autorizado							57370
cartao_nao_autorizado					57371
demo_a_vencer									57369
demo_inativo									57373

*/

$hostName = 'emailmkt7';
$login 	  = 'contadoramigo1';
$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';

	// inclusao do cadastrado no emkt da Locaweb - lista ativos
	error_reporting(E_ALL);
	require_once '../emkt/lib/RepositorioContatos.php';
	// Esses valores podem ser obtidos na página de configurações do
	// Email Marketing
	
	$hostName = 'emailmkt7';
	$login 	  = 'contadoramigo1';
	$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
	$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);
$contatosCadastrar = array();
	
array_push($contatosCadastrar, array('email'=>strtolower('priscila.lessa@lessaproducoes.com.br'), 'nome'=>utf8_decode('Priscila')));

	
	$IDLista = array(57394);
	$status_login = array("'teste'");
	
	$repositorio->importar($contatosCadastrar, array($IDLista[0]));
	
	
	
	

?>