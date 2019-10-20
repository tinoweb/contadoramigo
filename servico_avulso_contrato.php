<?php 
//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	//inicia a sessão.
	session_start();

	// Cria a sessão para evitar manipulação da url pelo cliente.
	if(isset($_REQUEST['tipo']) && !empty($_REQUEST['tipo']) && isset($_REQUEST['valor']) && !empty($_REQUEST['valor']) && isset($_REQUEST['data']) && !empty($_REQUEST['data']) && isset($_REQUEST['id_user']) && !empty($_REQUEST['id_user'])){
		
		// Cria uma sessão.
		$_SESSION['tipo'] = $_REQUEST['tipo'];
		$_SESSION['valor'] = $_REQUEST['valor'];
		$_SESSION['data'] = $_REQUEST['data'];
		$_SESSION['id_user'] = $_REQUEST['id_user'];
		$_SESSION['contratoId'] = $_REQUEST['contratoId'];
		
		if(isset($_REQUEST['cartao'])) {
			$_SESSION['cartao'] = $_REQUEST['cartao'];
		}
		
		header('Location: /servico_avulso_contrato.php');
		die();
	}
	
	// Se não foi criado a sessão redireciona para a página de serviço para que o cliente possa selecionar novamente o serviço.
	if(!isset($_SESSION['tipo'])){
		
		// Redireciona para a pagina de contrato.
		header('Location: /servico-contador.php');
		die();
	}

	if (isset($_SESSION["id_userSecao"])) {		
		require_once('header_restrita.php');
	} else {
		require_once('header.php' );	
	}
	
	// Realiza a requisição da chamada do controle.
	require_once('Controller/servicos_assinatura_contador_contrato-controller.php');
	
	$dadosContrato = new ServicosAssinaturaContadorContrato();
	
	// Pega os dados do contador e do cliente para o contrato contrato.
	$dados = $dadosContrato->pegaDadosParaContrato($_SESSION['id_user']);
	
	// verifica se existe dados do contador
	if(isset($dados['contador']) && !empty($dados['contador'])) {
		$contador = $dados['contador'];
	}
	
	// Verifica se existe dados do cliente.
	if(isset($dados['cliente']) && !empty($dados['cliente'])) {
		$cliente = $dados['cliente'];
	}
	
	if($contador->getTipoDoc() == 'PJ'){
			$CONTRATADA = "</CONTRATADA/>: ".$contador->getNome().", pessoa jurídica de direito privado, inscrita no CNPJ nº ".$contador->getDocumento().", </contador_estabelecida/> na ".$contador->getEndereco()." - ".$contador->getBairro()." - ".  $contador->getCidade() ." - ". $contador->getUF().".";
	} else {
			$CONTRATADA = "</CONTRATADA/>: ".$contador->getNome().", </contador_Portador/> da cédula de identidade RG nº ".$contador->getDocumento2()." e do CPF nº ".$contador->getDocumento().", residente e </contador_domiciliada/> a ".$contador->getEndereco()." - ".$contador->getBairro()." - ".  $contador->getCidade() ." - ". $contador->getUF().".";
	}
		 
	if($cliente->getTipo() == 'PJ') {
			$CONTRATANTE = "CONTRATANTE: ".$cliente->getNome().", pessoa jurídica de direito privado, inscrita no CNPJ nº ".$cliente->getDocumento().", </cliente_estabelecida/> na ".$cliente->getEndereco()." - ".$cliente->getBairro()." - ".  $cliente->getCidade() ." - ". $cliente->getUF().".";          
	} else {
			$CONTRATANTE = "CONTRATANTE: ".$cliente->getNome().", </cliente_Portadora/> <!-- da cédula de identidade RG nº 9.212.738-1 e -->do CPF nº ".$cliente->getDocumento().", residente e <//domiciliado/> a ".$cliente->getEndereco()." - ".$cliente->getBairro()." - ".  $cliente->getCidade() ." - ". $cliente->getUF().".";                    	
	}
	
	//Contrato.
	$contrato = "<p>".$CONTRATADA."</p>
				<p>".$CONTRATANTE."</p>
				<p>
                    1. DO OBJETO
				</p><p>
                    O objeto do presente consiste na prestação pel</a CONTRATADA/> </à CONTRATANTE/>, dos seguintes serviços profissionais:
                    ".$dadosContrato->getServicoTexto()."
                </p><p>
                    2. DAS CONDIÇÕES DE EXECUÇÃO DOS SERVIÇOS
				</p><p>	
                    Os serviços serão executados com base nas informações prestadas pel</a CONTRATADA/> em sua área restrita no Portal Contador Amigo. 
                </p><p>
                    3. DOS DEVERES D</A CONTRATADA/>
                </p><p>
                    3.1. </A CONTRATADA/> desempenhará os serviços enumerados na cláusula 1 com todo zelo, diligência e honestidade, observada a legislação vigente, resguardando os interesses d</a CONTRATANTE/>, sem prejuízo da dignidade e independência profissionais, sujeitando-se, ainda, às normas do Código de Ética Profissional do Contabilista, aprovado pela Resolução N° 803/96 do Conselho Federal de Contabilidade.
                </p><p>
                    3.2. Responsabilizar-se-á </a CONTRATADA/> por todos os prepostos que atuarem nos serviços ora contratados, indenizando à CONTRATANTE, em caso de culpa ou dolo.
                </p><p>
                    3.3. </A CONTRATADA/> assume integral responsabilidade por eventuais multas fiscais decorrentes de imperfeições ou atrasos nos serviços ora contratados, excetuando-se os ocasionados por força maior ou caso fortuito, assim definidos em lei, depois de esgotados os procedimentos, de defesa administrativa
                </p><p>
                    3.4. Não se incluem na responsabilidade assumida pel</a CONTRATADA/> os juros e a correção monetária de qualquer natureza, visto que ambos não configuram parte da penalidade pela mora, mas sim recomposição e remuneração do valor não recolhido.
                    Responsabilizar-se-á </a CONTRATADA/> por todos os documentos a ela entregues pel</a CONTRATANTE/>, enquanto permanecerem sob sua guarda para a consecução do serviço pactuado, respondendo pelo seu mau uso, perda, extravio ou inutilização, salvo comprovado caso fortuito ou força maior, mesmo se tal ocorrer por ação ou omissão de seus prepostos ou quaisquer pessoas que a eles tenham acesso.
                </p><p>
                    3.5. </A CONTRATADA/> não assume nenhuma responsabilidade pelas consequências de informações, declarações ou documentação inidôneas, erradas ou incompletas que lhe forem apresentadas, bem como por omissões próprias d</a CONTRATANTE/> ou decorrentes do desrespeito à orientação prestada.
                </p><p>
                    4.  DOS DEVERES D</A CONTRATANTE/>
                </p><p>
                    4.1. Obriga-se </a CONTRATANTE/> a fornecer </à ONTRATADA/> todos os dados, documentos e informações que se façam necessários ao bom desempenho do serviço ora contratado, em tempo hábil, nenhuma responsabilidade cabendo à segunda acaso recebidos intempestivamente.
                </p><p>        
                    4.2. Para a execução dos serviços constantes da cláusula 1 </a CONTRATANTE/> pagará </à ONTRATADA/> os honorários profissionais correspondentes a ".$dadosContrato->getValorPagoContratado().". A cobrança será feita por intermédio da ferramenta de e-commerce do portal Contador Amigo.
                </p><p>
                    5. DA RESCISÃO
                </p><p>
                    5.2. No caso de desistência pel</a CONTRATANTE/> da execução do serviços, seja qual for a razão, esta não terá direito ao reembolso do valor pago.
                </p><p>
                    5.5. Considerar-se-á rescindido o presente contrato, independentemente de notificação judicial ou extrajudicial, caso qualquer das partes CONTRATANTES venha a infringir cláusula ora convencionada.
                </p><p>
                    6. DO FORO
				</p><p>	
                    Fica eleito o Foro da Cidade de ".$contador->getCidade().", com expressa renúncia a qualquer outro, por mais privilegiado que seja, para dirimir as questões oriundas da interpretação e execução do presente contrato.
                </p>";
	
	// Se o cliente for pessoa fisica passa para masculino.			
	if($cliente->getTipo() == 'PF'){
		$contrato = str_replace('</a CONTRATANTE/>','o CONTRATANTE',$contrato);
		$contrato = str_replace('</à CONTRATANTE/>','ao CONTRATANTE',$contrato);
		$contrato = str_replace('</A CONTRATANTE/>','O CONTRATANTE',$contrato);
		$contrato = str_replace('</cliente_Portadora/>','Portador',$contrato);
		$contrato = str_replace('</cliente_estabelecida/>','estabelecido',$contrato);
		$contrato = str_replace('</cliente_domiciliada/>','domiciliado',$contrato);
	} 
	
	// Se cliente for pessoa juridica passa para feminino.
	else {	
		$contrato = str_replace('</a CONTRATANTE/>','a CONTRATANTE',$contrato);
		$contrato = str_replace('</à CONTRATANTE/>','à CONTRATANTE',$contrato);
		$contrato = str_replace('</A CONTRATANTE/>','A CONTRATANTE',$contrato);
		$contrato = str_replace('</cliente_Portadora/>','Portadora',$contrato);
		$contrato = str_replace('</cliente_estabelecida/>','estabelecida',$contrato);
		$contrato = str_replace('</cliente_domiciliada/>','domiciliada',$contrato);
	}

	// Se o contador for pessoa fisica masculina passa para o masculino.
	if($contador->getSex() == 'M' && $contador->getTipoDoc() == 'PF') {
		$contrato = str_replace('</CONTRATADA/>','CONTRATADO',$contrato);
		$contrato = str_replace('</A CONTRATADA/>','O CONTRATADO',$contrato);
		$contrato = str_replace('</a CONTRATADA/>','o CONTRATADO',$contrato);
		$contrato = str_replace('</à CONTRATADA/>','ao CONTRATADO',$contrato);
		$contrato = str_replace('</contador_registrada/>','registrado',$contrato);
		$contrato = str_replace('</contador_estabelecida/>','estabelecido',$contrato);
		$contrato = str_replace('</contador_Portadora/>','Portador',$contrato);
		$contrato = str_replace('</contador_domiciliada/>','domiciliado',$contrato);		
	}
	
	// Se o contador for pessoa juridica ou pessoa fisica feminina passa para o femenino. 
	else 
	{
		$contrato = str_replace('</CONTRATADA/>','CONTRATADA',$contrato);
		$contrato = str_replace('</A CONTRATADA/>','A CONTRATADA',$contrato);
		$contrato = str_replace('</a CONTRATADA/>','a CONTRATADA',$contrato);
		$contrato = str_replace('</à CONTRATADA/>','à CONTRATADA',$contrato);
		$contrato = str_replace('</contador_registrada/>','registrada',$contrato);
		$contrato = str_replace('</contador_estabelecida/>','estabelecida',$contrato);
		$contrato = str_replace('</contador_Portadora/>','Portadora',$contrato);
		$contrato = str_replace('</contador_domiciliada/>','domiciliada',$contrato);	
	}
	
	// Pega o código do contrato de acordo com serviço selecionado.
	$contratoId = (isset($_SESSION['contratoId']) && !empty($_SESSION['contratoId']) ? $_SESSION['contratoId'] : '');

?>

<div class="principal">
	<div style="width:966px" class="minHeight">
    
    	<div class="titulo">Serviços com Contador</div>
            
        <div id="contrato_premio" style="height:450px; width:100%; overflow-y: scroll; overflow-x: hidden; margin-bottom: 20px; background-color: white; border: 1px solid #ccc">
            <div style="padding:10px">
                <div style="text-align:center; font-weight:bold; margin-bottom:5px">CONTRATO DE PRESTAÇÃO DE SERVIÇOS</div>
                <div style="text-align: left"><?php echo $contrato; ?></div>
            </div>
        </div>
        
        <form id="formContrato" action="servico_avulso_contrato_aceito.php" method="post" style=" text-align:left; width:100%;">
            <label><input id="termoPremium" name="cheTermos" type="checkbox"></label>
            Li e concordo com os termos e condições de serviço.
            <br/>
            <br/>
            <input type="hidden" name="contadorId" value="<?php echo $contador->getId(); ?>">
            <input type="hidden" name="contratoId" value="<?php echo $contratoId; ?>">
            <?php echo $dadosContrato->geraLinkparaboleto(); ?>
            <?php if(isset($_SESSION['cartao'])):?>
            	<button id="btProsseguir"  type="button">Confirmar pagamento</button>
            <?php else:?>
            	<button id="btProsseguir"  type="button">Gera Boleto</button>
           	<?php endif;?>
        </form>
	</div>        
</div>

<script>
	$(function(){
		// Quando os termos forem aceitos realiza a chamado do boleto.	
		$('#btProsseguir').click(function() {
			
			if( $("#statusInput").val() == "OK" ) {
				if( $("#termoPremium").is(':checked') ) {
					$("#formContrato").submit();
				} else {
					alert('É necessário concordar com termos e condições de serviço.');	
				}
			} else {
				alert('Nao foi possivel gerar o boleto entre em contato com o administrador do sistema.');
			}
		});	
		
		$('.closeWindow').click(function(){
			window.close();
		});
	});	
</script>

<?php include 'rodape.php' ?>