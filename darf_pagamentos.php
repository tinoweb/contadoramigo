<?php 

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	// inclui o cabeçalho da página
	require_once('header_restrita.php');

	// Faz a requisição do arquivo responsavel por gerenciar os dados da página.
	require_once('Controller/darf_pagamentos-controller.php');

	// Instancia da 
	$darfPagamento = new DARFPagamento();	
?>

<div  class="minHeight principal">
	<h1>Impostos e Obrigações</h1>
	<h2>DARF</h2>
	<div style="width:80%">

<?php
	// Verifica se  existe lista de pagamento 
	if(!empty($darfPagamento->MostraTabelasPagamentos)):	
?>
		Os pagamentos que você cadastrou no período selecionado estão relacionados abaixo. Verifique se faltou incluir algum pró-labore, funcionário, trabalhador autônomo ou pessoas jurídicas que lhe pestaram serviços durante este período. Caso necessário, vá na aba pagamentos e atualize estas informações. Se os pagamentos estiverem todos relacionados, clique em prosseguir.
		<div style="clear:both;margin-bottom:20px;"></div>
<?php 
	// Inclui as tabelas de pagameto.
	echo $darfPagamento->MostraTabelasPagamentos;

	// Caso não exista dados apresenta a mensagem de Atenção.
	else:
?>
		<div style="background-color:#FFFFFF; border-color:#CCC; border-style:solid; border-width:1px; padding:10px; margin-bottom:30px">
		<span class="destaque">ATENÇÃO:</span><br /><br />
		Não foi encontrado nenhum registro de pró-labore, pagamento a autonômo ou a pessoas jurídicas no período selecionado. Vá na aba pagamentos e registre estes valores, para que o sistema verifique se há Darf a ser recolhido.<br />
		<br />
		Caso não haja realmente nenhum pagamento no período, então não há Darf a ser recolhido.
		</div>

		<input type="button" id="btVoltar" value="Voltar" />

<?php
	// Fecha a condição para verifica se existe pagaemnto. 	
	endif;
?>

	</div>
   
	<div style="clear: both; height: 30px"></div>                        
	<div class="quadro_branco">
		<span class="destaque">Pagamento sem código de barras:</span> você pode quitar normalmente o DARF pela Internet. Para isso, acesse o site de seu banco, localize a opção Pagamento/Darf e preencha os dados de pagamento com as mesmas informações constantes na guia gerada aqui pelo Contador Amigo.
	</div>
</div>

<script>
	$(document).ready(function(e) {
        $('#btVoltar').bind('click',function(e){
			e.preventDefault();
			history.go(-1);
		});
        $('#btProsseguir').bind('click',function(e){
			e.preventDefault();
			location.href="darf_gerar.php";
		});
    });
</script>
<div style="clear:both; margin-bottom:10px"></div>

<?php 
	include 'rodape.php';
?>