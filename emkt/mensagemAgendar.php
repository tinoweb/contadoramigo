<?php 
include '../session.php';
include '../admin/check_login.php';
?>

<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/lib/RepositorioMensagens.php';

// Esses valores podem ser obtidos na página de configurações do
// Email Marketing
	$hostName = 'emailmkt7';
	$login 	  = 'contadoramigo1';
	$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
$repositorio= new RepositorioMensagens($hostName, $login, $chaveApi);

$arrAgendamento= array (
	"data_agendamento" => "2009-06-19 10:46:00",
	"todos_contatos" => "true"
);

$repositorio->agendarMensagem($arrAgendamento, '37');
print "Sua mensagem foi agendada com sucesso!";
?>
