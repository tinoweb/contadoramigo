<?php 

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
            Para casos como este, o <strong>Contador Amigo</strong> dispõe de uma rede de contabilistas parceiros. Seguindo uma tabela de preços fixos, você pode contratá-los diretamente aqui pelo nosso site.
            Veja a seguir quais são os serviços disponíveis e contrate-os agora mesmo:
        </div>

        <div style="margin-top:30px;">
			<?php echo $tabelaServicoAvulso;?>
        </div>

<!--box1-->
<div  class="box_servico_contador">
	<form method="get" action="<?php echo $action;?>">
		<input type='hidden' name='contratoId' value='5' />
		<input type='hidden' name='id_user' value='<?php echo $userId; ?>' />
		<input type='hidden' name='data' value='<?php echo $data; ?>' />
		<input type='hidden' name='tipo' value='' />
		<div style="padding: 10px">
			<h3>Abertura, Alteração ou Baixa</h3>
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
				<input type="submit" class="botao" value="Contratar">
			</center>
		</div>
	</form>
</div>
<!--fim box1 -->

<!--box2-->
<div  class="box_servico_contador">
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
			R$ 150 para firma individual, EIRELI ou LTDA.</label>
		  <br><br><br>

			<center>
				<input type="submit" class="botao" value="Contratar">
			</center>
		</div>
	</form>
</div>
<!--fim box2 --> 
 
 <!--box3-->
<div  class="box_servico_contador" style="margin-right: 0px">
<div style="padding: 10px">
<h3>Transformar MEI em ME</h3>
<img src="images/transformar-mei-em-me.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>Nosso contador parceiro prepara toda a documentação e a envia por e-mail. Você só precisará imprimi-la, assiná-la e entregar na Junta Comercial. Obs.: As taxas de abertura cobradas pela Junta ficam em torno de R$ 80 para empresa individual e R$ 150 para sociedade.
<br><br>
<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
<label>
    <input type="radio" name="RadioGroup2" value="opção" id="RadioGroup1_0">
    R$ 300 para empresário individual</label>
  <br>
  <label>
    <input type="radio" name="RadioGroup1" value="opção" id="RadioGroup1_1">
    R$ 400 para LTDA ou EIRELI</label>
  <br><br>

	<center><input type="submit" class="botao" value="Contratar"></center>
	</div>
</div>
<!--fim box3 -->

 <!--box4-->
<div  class="box_servico_contador">
<div style="padding: 10px">
<h3>RAIS Negativa</h3>
<img src="images/rais.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A Rais é uma declaração que informa ao Ministério do Trabalho o giro de funcionários dentro de sua empresa. Deve ser enviada anualmente. As empresas que não tem funcionários devem enviar a RAIS negativa. Não há nenhum pagamento atrelado, mas o atraso na entrega pode acarretar multas altas.
<br><br>
<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
<label>
    <input type="radio" name="RadioGroup2" value="opção" id="RadioGroup1_0">
    R$ 76 para firma individual, EIRELI ou LTDA.</label>
  <br><br>

	<center><input type="submit" class="botao" value="Contratar"></center>
	</div>
</div>
<!--fim box4 -->
		
 <!--box5-->
<div  class="box_servico_contador">
<div style="padding: 10px">
<h3>DEFIS</h3>
<img src="images/defis.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A DEFIS -  Declaração de Informações Socioeconômicas e Fiscais - deve ser enviada anualmente. As informações lá prestadas serão cruzadas com com a declaração de IR do sócio/proprietário. Não há multa pelo atraso, mas você não conseguirá fazer as próximas apurações enquanto estiver pendente.
<br><br>
<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
<label>
    <input type="radio" name="RadioGroup2" value="opção" id="RadioGroup1_0">
    R$ 76 para firma individual, EIRELI ou LTDA.</label>
  <br><br>

	<center><input type="submit" class="botao" value="Contratar"></center>
	</div>
</div>
<!--fim box5 -->	

 <!--box6-->
<div  class="box_servico_contador" style="margin-right: 0px">
<div style="padding: 10px">
<h3>DIRF</h3>
<img src="images/dirf.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A DIRF deve ser enviada quando a empresa faz retenção de IR ao pagar pró-labores, salários, serviços prestados por autônomos, ou  aluguel. Nela, são registrados os totais retidos para cada beneficiários e as informações prestadas serão cruzadas com com as declaração de IR destes. 
<br><br>
<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
<label>
    <input type="radio" name="RadioGroup2" value="opção" id="RadioGroup1_0">
    R$ 76 para firma individual, EIRELI ou LTDA.</label>
  <br><br>

	<center><input type="submit" class="botao" value="Contratar"></center>
	</div>
</div>
<!--fim box6 -->

 <!--box7-->
<div  class="box_servico_contador">
<div style="padding: 10px">
<h3>Simples Nacional (DAS)</h3>
<img src="images/simples-nacional.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A apuração do Simples deve ser entregue mensalmente, mesmo por empresas inativas. O atraso acarreta multa e pode levar ao desenquadramento do Simples Nacional. É preciso cuidado no preenchimento da declaração, pois as alíquotas do imposto variam de acordo com a atividade e faturamento. 
<br><br>
<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
<label>
    <input type="radio" name="RadioGroup2" value="opção" id="RadioGroup1_0">
    R$ 76 para firma individual, EIRELI ou LTDA.</label>
  <br><br>

	<center><input type="submit" class="botao" value="Contratar"></center>
	</div>
</div>

<!--fim box8 -->
																																							 <!--box7-->
<div  class="box_servico_contador">
<div style="padding: 10px">
<h3>Gfip</h3>
<img src="images/gfip.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>A Gfip deve ser enviada mensalmente. Nela são informados os valores pagos de pró-labores, salários e dos serviços prestados por autônomos. Se não foi efetuado nenhum pagamento do tipo no mês, será enviada a Gfip sem movimento. O atraso no envio, pdoe acarratar altas multas à empresa.
<br><br>
<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
<label>
    <input type="radio" name="RadioGroup2" value="opção" id="RadioGroup1_0">
    R$ 76 para firma individual, EIRELI ou LTDA.</label>
  <br><br>

	<center><input type="submit" class="botao" value="Contratar"></center>
	</div>
</div>
<!--fim box8 -->
																													
																													
<!--box9-->
<div  class="box_servico_contador" style="margin-right: 0px">
<div style="padding: 10px">
<h3>Cadastro no CPOM</h3>
<img src="images/cpom.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>Para não ter que pagar ISS duas vezes quando presta serviço a clientes de outro município, muitas vezes você precisará fazer o cadastro no CPOM. Se este for o seu caso, nosso contador parceiro irá preparar toda a documentação e enviará para o município em questão.
<br><br>
<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
<label>
    <input type="radio" name="RadioGroup2" value="opção" id="RadioGroup1_0">
    R$ 76 para firma individual, EIRELI ou LTDA.</label>
  <br><br>

	<center><input type="submit" class="botao" value="Contratar"></center>
	</div>
</div>
<!--fim box9 -->
																													
<!--box10-->
<div  class="box_servico_contador">
<div style="padding: 10px">
<h3>DBE</h3>
<img src="images/dbe-avulso.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>O DBE é usado para alterar os dados de uma empresa junto ao CNPJ - Cadastro Nacional de Pessoas Jurídicas. Se você pretende alterar sua emrepsa, contrate o serviço de alteração. Use o DBE apenas para corrigir dados cadastrais que já estejam corretos na Junta Comercial.
<br><br>
<div style="margin-bottom: 5px; font-weight: bold">Nosso preço:</div>
<label>
    <input type="radio" name="RadioGroup2" value="opção" id="RadioGroup1_0">
    R$ 76 para firma individual, EIRELI ou LTDA.</label>
  <br><br>

	<center><input type="submit" class="botao" value="Contratar"></center>
	</div>
</div>
<!--fim box10 -->																												

<!--box11-->
<div  class="box_servico_contador">
<div style="padding: 10px">

	<h3>Regularização de Empresa</h3>
	<img src="images/regularizacao.png" width="75" height="75" alt="" style="margin-right: 10px; float: left; border: 0px"/>Verificamos todas as pedências da sua empresa, solicitamos o parcelamento das dividas e cumprimos com as obrigações em atraso.<br>
	<br>
	Clique no botão abaixo para solicitar um orçamento sem compromisso. Um de nossos contadores parceiros entrará em contado para obter mais informações.Você terá um diagnóstico preciso e gratuito sobre suas pendências.
	<br><br>

	<center>
		<input type="submit" class="botao" value="Solicitar orçamento" onClick="location.href='/suporte.php'">
	</center>
	
	</div>
</div>
<!--fim box11 -->																													
																														
<div style="clear: both; height: 20px"></div>
</div>

<?php include 'rodape.php' ?>