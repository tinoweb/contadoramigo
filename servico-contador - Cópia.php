<?php 
/**
 * Bom dia, boa tarde ou bora noite.
 *
 * Leia-me antes de editar este arquivo.
 *
 *		Em quanto não houver uma área de edição do serviços para incluir 
 * e editar será necessario fazer a alteração manual.
 *
 *		Quando for para realizar a inclusão de um serviço será necessario 
 * realizar a atualização resultado da ação e tipo de serviço no banco de
 * dados e nas páginas que serão informada a baixo.
 *
 * - Banco de dados:
 *
 * *contratos:
 *		No tabela contrato devera ser so informado no campo "contratoNome" o nome do serviço.
 *
 * *dados_servico_avulso: 
 *
 *	nome =  Nome do serviço
 *	nomeResumido = EXEMPLO(Gfip e GPS)
 *	valor = Valor do serviço
 *	tipo = EXEMPLO(Gfip_GPS)
 *	contratoId = Codigo da tabela de contrato.
 *
 * - Páginas:
 *
 * *servico_pagamento_cartao.php:
 *		No método RetornaResultadoAcao incluia no switch a condição com o tipo do serviço e o resultado da ação. Linha 185.
 *
 * *boleto.class.php
 *  	Existe dois método(getTiposBoleto, getTiposServico) que devera ser incluido o tipo do serviço localizada aproximada mente na linhas 97 e 100 :
 *
 * *cobranca.php
 *		Na cobrança devera ser incluida o resultado da ação:
 *		Ex: [ linha 2631 ] - case '11.4': $resultado_acao = ($linha["tipo_cobranca"] == 'boleto' ? 'Boleto com sucesso' : 'Cartão com sucesso'); break;
 *		EX: [ linha 2493] - $linha["resultado_acao"] == "11.2"
 *
 * *cobranca.class.php
 *		No método getResultadoAcao incluia no "else if" a condição com o tipo do serviço e o resultado da ação. Linha 289.
 * 
 *
 * *rps.php  
 *		Será necessário informar na Consulta do na condição(linha 177):
 *		cob.resultado_acao in ('1.2',...,'11.4')
 *
 * * contador/clientes_avulso-Controller.php
 *		inclua no switch da linha 50 o tipo do serviço com um apelido.
 *
 * * contador/listaPagamentos-Controller.php
 *		inclua no array da linha 290 o tipo do serviço com um apelido.
 *
 * * admin/clientes_avulso_contador-Controller
 *		inclua no switch da linha 52 o tipo do serviço com um apelido.
 *
 * * admin/listaPagamentoComissao-Controller.php
 *		inclua no array da linha 275 o tipo do serviço com um apelido. 
 */

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	session_start();

	if (isset($_SESSION["id_userSecao"])) 
	{
		require_once('header_restrita.php');
		
		$data = date("d/m/Y");
		$action = "servico_form_dados_usuario.php";
		$userId = $_SESSION['id_userSecaoMultiplo'];
		
	} else {

		$nome_meta = "servico-contador";
		
		require_once('header.php' );
		
		$data = date("d/m/Y");
		$action = "servico_informar_dados_contratar.php";
				
		$userId = '';
	}

	// Apaga a Sessão usadas para gerar o boleto.
	unset($_SESSION['tipo']);
	unset($_SESSION['valor']);
	unset($_SESSION['data']);
	unset($_SESSION['id_user']);
	unset($_SESSION['contratoId']);
	unset($_SESSION['idUserNew']);
?>

<style>
	.linha1 td {
		background-color: #FFF;	
		padding: 5px 20px;
	}
	body {
	    text-align: left;
	}
</style>

<div class="principal">

        <div class="titulo">Serviços Avulsos</div>
        
        <div style="margin-bottom: 30px">
          Excepcionalmente você pode precisar de serviços que requeiram a assinatura de um contador, ou simplesmente preferir que determinada tarefa seja realizada por um profissional da área. 
            Para casos como este, o <strong>Contador Amigo</strong> dispõe de uma rede de contabilistas parceiros. Seguindo uma tabela de preços fixos, você pode contratá-los diretamente aqui pelo nosso site.<br>
<br>

            <strong>Seu pagamento ficará retido no Contador Amigo até que o contador parceiro finalize a tarefa e você se dê por satisfeito.</strong> Só então o valor será repassado a ele. Assim você terá a certeza de que o serviço será executado da forma que precisa.
            
            Caso venha a desistir do pedido, você poderá receber o valor de volta, parcial ou integralmente, dependendo do estágio em que trabalho se encontra.<br>
<br>

            Veja a seguir quais são os serviços disponíveis e contrate-os agora mesmo:
        </div>

<!--box1-->       
<div id="box1" class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='4' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='A_empresa' />
		<div style="padding: 10px">
			<h3>Abertura ou Alteração ou Baixa</h3>
			<img src="images/abrir-empresa.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>Nosso contador parceiro prepara toda a documentação e a envia por e-mail. Você só precisará imprimi-la, assiná-la e entregar na Junta Comercial. Obs.: As taxas de abertura cobradas pela Junta ficam em torno de R$ 80 para empresa individual e R$ 150 para sociedade.
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="300" id="RadioGroup1_0">
				R$ 300 para empresário individual
			</label>
			<br>
			<label>
				<input type="radio" name="valor" value="400" id="RadioGroup1_1">
				R$ 400 para LTDA ou EIRELI
			</label>
			<br><br>
			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box1">
			</center>
		</div>
	</form>		
</div>
<!--fim box1 -->

<!--box2-->
<div id="box2"  class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='5' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='decore' />
		<div style="padding: 10px">
			<h3>Declaração de Rendimentos</h3>
			<img src="images/decore.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>Normalmente solicitada pelos bancos para aprovação de linhas de crédito. Pode ser tanto uma declaração de rendimentos do sócio/proprietário ou uma declaração das receitas da empresa. O documeto será assinado pelo nosso contador parceiro, após análise de suas contas.  
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="150" id="RadioGroup1_0">
				R$ 150 para firma individual, EIRELI ou LTDA.
			</label>
		  	<br><br><br>

			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box2">
			</center>
		</div>
	</form>
</div>
<!--fim box2 --> 
 
 <!--box3-->
<div id="box3" class="box_servico_contador" style="margin-right: 0px">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='14' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='MEI-ME' />
		<div style="padding: 10px">
			<h3>Transformar MEI em ME</h3>
			<img src="images/transformar-mei-em-me.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>Nosso contador parceiro prepara toda a documentação e a envia por e-mail. Você só precisará imprimi-la, assiná-la e entregar na Junta Comercial. Obs.: As taxas de abertura cobradas pela Junta ficam em torno de R$ 80 para empresa individual e R$ 150 para sociedade.
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="300" id="RadioGroup1_0">
				R$ 300 para empresário individual
			</label>
			<br>
			<label>
				<input type="radio" name="valor" value="400" id="RadioGroup1_1">
				R$ 400 para LTDA ou EIRELI
			</label>
			<br><br>
			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box3">
			</center>
		</div>
	</form>		
</div>
<!--fim box3 -->

 <!--box4-->
<div id="box4" class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='10' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='Rais_negativa' />
		<div style="padding: 10px">
			<h3>RAIS Negativa</h3>
			<img src="images/rais.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A Rais é uma declaração que informa ao Ministério do Trabalho o giro de funcionários dentro de sua empresa. Deve ser enviada anualmente. As empresas que não tem funcionários devem enviar a RAIS negativa. Não há nenhum pagamento atrelado, mas o atraso na entrega pode acarretar multas altas.
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="76" id="RadioGroup1_0">
				R$ 76 para firma individual, EIRELI ou LTDA.</label>
			  <br><br>
			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box4">
			</center>
		</div>
	</form>		
</div>
<!--fim box4 -->
		
 <!--box5-->
<div id="box5" class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='9' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='Defis' />
		<div style="padding: 10px">
			<h3>DEFIS</h3>
			<img src="images/defis.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A DEFIS -  Declaração de Informações Socioeconômicas e Fiscais - deve ser enviada anualmente. As informações lá prestadas serão cruzadas com com a declaração de IR do sócio/proprietário. Não há multa pelo atraso, mas você não conseguirá fazer as próximas apurações enquanto estiver pendente.
			<br/><br/>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="76" id="RadioGroup1_0">
				R$ 76 para firma individual, EIRELI ou LTDA.
			</label>
			<br/><br/>
			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box5">
			</center>
		</div>
	</form>
</div>
<!--fim box5 -->	

 <!--box6-->
<div id="box6" class="box_servico_contador" style="margin-right: 0px">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='11' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='Dirf' />
		<div style="padding: 10px">
			<h3>DIRF</h3>
			<img src="images/dirf.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A DIRF deve ser enviada quando a empresa faz retenção de IR ao pagar pró-labores, salários, serviços prestados por autônomos, ou  aluguel. Nela, são registrados os totais retidos para cada beneficiários e as informações prestadas serão cruzadas com com as declaração de IR destes. 
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="76" id="RadioGroup1_0">
				R$ 76 para firma individual, EIRELI ou LTDA.
			</label>
			<br><br>

			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box6">
			</center>
		</div>
	</form>
</div>
<!--fim box6 -->

<!--box7-->
<div id="box7" class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='8' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='Simples_DAS' />
		<div style="padding: 10px">
			<h3>Simples Nacional (DAS)</h3>
			<img src="images/simples-nacional.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A apuração do Simples deve ser entregue mensalmente, mesmo por empresas inativas. O atraso acarreta multa e pode levar ao desenquadramento do Simples Nacional. É preciso cuidado no preenchimento da declaração, pois as alíquotas do imposto variam de acordo com a atividade e faturamento. 
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="76" id="RadioGroup1_0">
				R$ 76 para firma individual, EIRELI ou LTDA.</label>
			  <br><br>

			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box7">
			</center>
		</div>
	</form>
</div>
<!--fim box7 -->

<!--box8-->
<div id="box8" class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='7' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='Gfip_GPS' />
		<div style="padding: 10px">
			<h3>Gfip</h3>
			<img src="images/gfip.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A Gfip deve ser enviada mensalmente. Nela são informados os valores pagos de pró-labores, salários e dos serviços prestados por autônomos. Se não foi efetuado nenhum pagamento do tipo no mês, será enviada a Gfip sem movimento. O atraso no envio, pdoe acarratar altas multas à empresa.
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
			<input type="radio" name="valor" value="76" id="RadioGroup1_0">
			R$ 76 para firma individual, EIRELI ou LTDA.</label>
			<br><br>

			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box8">
			</center>
		</div>
	</form>
</div>
<!--fim box8 -->
																													
<!--box9-->
<div id="box9" class="box_servico_contador" style="margin-right: 0px">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='15' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='CPOM' />	
		<div style="padding: 10px">
			<h3>Cadastro no CPOM</h3>
			<img src="images/cpom.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>Para não ter que pagar ISS duas vezes quando presta serviço a clientes de outro município, muitas vezes você precisará fazer o cadastro no CPOM. Se este for o seu caso, nosso contador parceiro irá preparar toda a documentação e enviará para o município em questão.
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
			<input type="radio" name="valor" value="150" id="RadioGroup1_0">
			R$ 150 para firma individual, EIRELI ou LTDA.</label>
			<br><br>

			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box9">
			</center>
		</div>
	</form>
</div>
<!--fim box9 -->
																													
<!--box10-->
<div id="box10" class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='6' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='DBE' />		
		<div style="padding: 10px">
			<h3>DBE</h3>
			<img src="images/dbe-avulso.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>O DBE é usado para alterar os dados de uma empresa junto ao CNPJ - Cadastro Nacional de Pessoas Jurídicas. Se você pretende alterar sua emrepsa, contrate o serviço de alteração. Use o DBE apenas para corrigir dados cadastrais que já estejam corretos na Junta Comercial.
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="76" id="RadioGroup1_0">
				R$ 76 para firma individual, EIRELI ou LTDA.
			</label>
			<br><br>

			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box10">
			</center>
		</div>
	</form>
</div>
<!--fim box10 -->	

<!--box11-->																											
<div id="box11" class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='16' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='funcionario_C_D' />
		<div style="padding: 10px">
			<h3>Contratação/Demissão funcionário</h3>
			<img src="images/trabalhador.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>O serviço inclui e preparação da papelada relativa aos processos admissional ou demissional, registro do CAGED e no FGTS. No caso de demissão, são feitos todos os cálculos para rescisão do contrato de trabalho (aviso prévio, férias e 13º) e geradas as guias de recolhimento dos impostos devidos.
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="200" id="RadioGroup1_0">
				R$ 200 por trabalhador admitido/demitido.
			</label>
			<br><br>

			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box11">
			</center>
		</div>
	</form>			
</div>
<!--fim box11 -->																											

<!--box12-->
<div id="box12" class="box_servico_contador">
	<div style="padding: 10px">
		<h3>Regularização de Empresa</h3>
		<img src="images/regularizacao.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>Verificamos todas as pedências da sua empresa, solicitamos o parcelamento das dividas e cumprimos com as obrigações em atraso.<br>
		<br>
		Clique no botão abaixo para solicitar um orçamento sem compromisso. Um de nossos contadores parceiros entrará em contato para obter mais informações.Você terá um diagnóstico preciso e gratuito sobre suas pendências.
		<br><br>
		<center>
			<input type="button" value="Solicitar orçamento" onClick="location.href='/regularizacao.php'" style="margin-bottom: 5px">
		</center>
	</div>
</div>
<!--fim box12 -->	


<!--box13-->
<div id="box13" class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='18' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='DCTF' />		
		<div style="padding: 10px">
			<h3>DCTF</h3>			
			<img src="images/dctf.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A Declaração de Débitos e Créditos Tributários Federais, uma declaração mensal das empresas de lucro presumido. Serve para informar os impostos recolhidos à Receita Federal. Se você está devendo a DCTF relativa ao período em que sua empresa estava desenquadrada do Simples, deve regularizá-la o quanto antes.
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="110" id="RadioGroup1_0">
				R$ 110 para firma individual, EIRELI ou LTDA.
			</label>
			<br><br>

			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box13">
			</center>
		</div>
	</form>
</div>
<!--fim box13 -->	

<!--box14-->																											
<div id="box14" class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='19' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />	
		<input type='hidden' name='tipo' value='de_stda' />
		<div style="padding: 10px">
			<h3>DeSTDA</h3>
			<img src="images/de_stda.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A DeSTDA é uma declaração mensal obrigatória das empresas com inscrição estadual. Normalmente só o comércio tem inscrição estadual, mas algumas empresas de serviços, como transporte intermunicipal e radiodifusão, que recolhem ICMS, também possuem inscrição estadual e estão obrigadas à entrega desta declaração.
			<br><br>
			<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
			<label>
				<input type="radio" name="valor" value="76" id="RadioGroup1_0">
				R$ 76 para firma individual, EIRELI ou LTDA. 
			</label>
			<br><br>

			<center>
				<input type="button" class="botaoContratar" value="Contratar" data-box="box14">
			</center>
		</div>
	</form>			
</div>
<!--fim box14 -->																											

<!--box15-->
<div id="box15" class="box_servico_contador" style='min-height:278px;'>
	<div style="padding: 10px">
		<h3>Imposto de Renda Pessoa Física</h3>
		<img src="images/irpf.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A declaração de ajuste Imposto de Renda da Pessoa Física precisa estar coerente com as declarações de sua empresa, como a Defis e a Dirf. Já os profissionais liberais precisam cruzar seus rendimentos com as despesas declaradas por seus clientes. Aqueles que recebem alugueis devem informar o mesmo valor declarado por seus inquilinos. Se você está inseguro sobre como fazer sua declaração. Deixe isso por conta de nossos especialistas. Clique no botão abaixo para solicitar um orçamento sem compromisso.
		<br><br>
		<center>
			<input type="button"  value="Solicitar orçamento" onClick="location.href='/regularizacao.php'" style="margin-bottom: 5px">
		</center>
	</div>
</div>
<!--fim box15 -->

<div style="clear: both"></div>
</div>

<script>
	$(function(){
		
		$('.botaoContratar').click(function(){
			
			//pega o id do box
			var box = $(this).attr('data-box');
			
			var status = false;
			
			// Verifica submit
			$('#'+box+' input[type=radio]').each(function(){
				if($(this).attr('checked')){	
					
					status = true;
					
					$('#'+box+' form').submit();
				}
			});
			
			if(!status) {
				alert('Por favor, informe o serviço desejado');
			}
		});
	});
</script>

<?php include 'rodape.php' ?>