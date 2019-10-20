<?php

	session_start();

	//ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);
	
	// inclui o arquivo de conexão com o banco.
	require_once('conect.php');
	
	// Realiza a requisição da chamada do controle.
	require_once('Controller/ballon_contrato_premio-controller.php');
	
	// instancia a classe de controle.
	$dadosContrato = new BallonContratoPremioController();
	
	// Pega os dados do contador e do cliente para o contrato contrato.
	$dados = $dadosContrato->pegaDadosParaContrato($_SESSION['id_userSecaoMultiplo']);
	
	// verifica se existe dados do contador
	if(isset($dados['contador']) && !empty($dados['contador'])) {
		$contador = $dados['contador'];
	}
	
	// Verifica se existe dados do cliente.
	if(isset($dados['cliente']) && !empty($dados['cliente'])) {
		$cliente = $dados['cliente'];
	}
				
//	$cliente->setTipo('PJ');	
//	$contador->setTipoDoc('PJ');
//	$contador->setSex('M');
	
	// Se o contador for Psessoa Juridica.
	if($contador->getTipoDoc() == 'PJ'){
			$CONTRATADA = "</CONTRATADA/>: ".$contador->getNome().", pessoa jurídica de direito privado, inscrita no CNPJ nº ".$contador->getDocumento().", </contador_estabelecida/> na ".$contador->getEndereco()." - ".$contador->getBairro()." - ".  $contador->getCidade() ." - ". $contador->getUF().".";
	} 
	
	// Se o contador for pessoa fisica.
	else
	{
			$CONTRATADA = "</CONTRATADA/>: ".$contador->getNome().", </contador_Portadora/> da cédula de identidade RG nº ".$contador->getDocumento2()." e do CPF nº ".$contador->getDocumento().", residente e </contador_domiciliada/> a ".$contador->getEndereco()." - ".$contador->getBairro()." - ".  $contador->getCidade() ." - ". $contador->getUF().".";
	}
	
	// Se o cliente for Psessoa Juridica. 
	if($cliente->getTipo() == 'PJ') {
			$CONTRATANTE = "CONTRATANTE: ".$cliente->getNome().", pessoa jurídica de direito privado, inscrita no CNPJ nº ".$cliente->getDocumento().", </cliente_estabelecida/> na ".$cliente->getEndereco()." - ".$cliente->getBairro()." - ".  $cliente->getCidade() ." - ". $cliente->getUF().".";          
	} 
	
	//Se o cliente for pessoa fisica.
	else
	{
			$CONTRATANTE = "CONTRATANTE: ".$cliente->getNome().", </cliente_Portadora/> <!-- da cédula de identidade RG nº 9.212.738-1 e -->do CPF nº ".$cliente->getDocumento().", residente e </cliente_domiciliada/> a ".$cliente->getEndereco()." - ".$cliente->getBairro()." - ".  $cliente->getCidade() ." - ". $cliente->getUF().".";                    	
	}
	
	//Contrato.
	$contrato = "<p>".$CONTRATADA."</p>"
			   ."<p>".$CONTRATANTE."</p>"
			   ."<p>
                    1. DO OBJETO
				</p><p>
					O objeto do presente consiste na prestação pel</a CONTRATADA/> </à CONTRATANTE/>, dos seguintes serviços profissionais:<br/>
					a)	Orientação e controle da aplicação dos dispositivos legais vigentes, sejam federais, estaduais ou municipais relacionados à sua atividade empresarial, incluindo a Apuração do Simples, emissão da DAS, elaboração e transmissão da Gfip, emissão da GPS e DARF do IRRF, Envio da Defis, Rais e Dirf<br/>
					b)	Atendimento das demais exigências previstas em atos normativos, bem como de eventuais procedimentos de fiscalização tributária.
				</p><p>
					2. DAS CONDIÇÕES DE EXECUÇÃO DOS SERVIÇOS
				</p><p>	
					Os serviços serão executados com base nas informações prestadas pel</a CONTRATADA/> em sua área restrita no Portal Contador Amigo. 
				</p><p>
					As informações e documentação indispensável para o desempenho dos serviços arrolados na cláusula 1 serão carregados pel</a CONTRATANTE/>, no sistema do Portal Contador Amigo e consiste em:
				</p><p>
					2.1. Atualizar o livro Caixa
				</p><p>
					2.2. Anexar os documentos relativos aos lançamentos, tais como notas fiscais, recibos, e comprovantes bancários.
				</p><p>
					2.3. Cadastrar pagamentos efetuados sócios (pró-labore), autônomo, estagiários e pessoas jurídicas prestadoras de serviços
				</p><p>
					2.4. A documentação deverá ser cadastrada pel</a CONTRATANTE/> de forma nos seguintes prazos:
					Semanalmente, os documentos mencionados no item 2.1 acima, sendo que os relativos à última semana do mês, no 1° (primeiro) dia útil do mês seguinte;
					Até o encerramento do mês, os documentos relacionados nos itens 2.2 e 2.3, acima;
				</p><p>
					2.5. </A CONTRATADA/> compromete-se a cumprir todos os prazos estabelecidos na legislação de regência quanto aos serviços contratados, especificando-se, porém, os prazos abaixo:
					A entrega das guias de recolhimento de tributos e encargos trabalhistas </à CONTRATANTE/> se fará com antecedência de 2 (dois) dias do vencimento da obrigação.
				</p><p>
					3. DOS DEVERES D</A CONTRATADA/>
				</p><p>
					3.1. </A CONTRATADA/> desempenhará os serviços enumerados na cláusula 1 com todo zelo, diligência e honestidade, observada a legislação vigente, resguardando os interesses d</a CONTRATANTE/>, sem prejuízo da dignidade e independência profissionais, sujeitando-se, ainda, às normas do Código de Ética Profissional do Contabilista, aprovado pela Resolução N° 803/96 do Conselho Federal de Contabilidade.
				</p><p>
					3.2. Responsabilizar-se-á </a CONTRATADA/> por todos os prepostos que atuarem nos serviços ora contratados, indenizando </à CONTRATANTE/>, em caso de culpa ou dolo.
				</p><p>
					3.3. </A CONTRATADA/> assume integral responsabilidade por eventuais multas fiscais decorrentes de imperfeições ou atrasos nos serviços ora contratados, excetuando-se os ocasionados por força maior ou caso fortuito, assim definidos em lei, depois de esgotados os procedimentos, de defesa administrativa
				</p><p>
					3.4. Não se incluem na responsabilidade assumida pel</a CONTRATADA/> os juros e a correção monetária de qualquer natureza, visto que ambos não configuram parte da penalidade pela mora, mas sim recomposição e remuneração do valor não recolhido.
					Responsabilizar-se</-á/> </a CONTRATADA/> por todos os documentos a ela entregues pel</a CONTRATANTE/>, enquanto permanecerem sob sua guarda para a consecução dos serviços pactuados, respondendo pelo seu mau uso, perda, extravio ou inutilização, salvo comprovado caso fortuito ou força maior, mesmo se tal ocorrer por ação ou omissão de seus prepostos ou quaisquer pessoas que a eles tenham acesso.
				</p><p>
					3.5. </A CONTRATADA/> não assume nenhuma responsabilidade pelas consequências de informações, declarações ou documentação inidôneas, erradas ou incompletas que lhe forem apresentadas, bem como por omissões próprias d</a CONTRATANTE/> ou decorrentes do desrespeito à orientação prestada.
				</p><p>
					4. DOS DEVERES D</A CONTRATANTE/>
				</p><p>
					4.1. Obriga-se </a CONTRATANTE/> a fornecer </à CONTRATADA/> todos os dados, documentos e informações que se façam necessários ao bom desempenho dos serviços ora contratados, em tempo hábil, nenhuma responsabilidade cabendo à segunda acaso recebidos intempestivamente.
				</p><p>
					4.2. Para a execução dos serviços constantes da cláusula 1 </a CONTRATANTE/> pagará </à CONTRATADA/> os honorários profissionais correspondentes a <span class='ValorPagoContratado'>63 mensais</span> por empresa. A cobrança será feita por intermédio da ferramenta de e-commerce do portal Contador Amigo.
				</p><p>
					4.3. O pagamento dos honorários  após a data avençada no item 4.2  acarretará </à CONTRATANTE/> o acréscimo de multa de 2% (dois por cento), além de juros moratórios. Os honorários serão reajustados, anual e automaticamente, segundo a variação do IPCA.
				</p><p>
					4.4. Os serviços solicitados pel</a CONTRATANTE/> não especificados na cláusula 1 serão cobrados pel</a CONTRATADA/> em apartado, como extraordinários, segundo valor específico constante de orçamento previamente aprovado pel</a CONTRATADA/>, englobando nessa previsão toda e qualquer inovação da legislação relativamente ao regime tributário, trabalhista ou previdenciário.
				</p><p>
					4.5. São considerados serviços extraordinários ou para-contábeis, exemplificativamente, mas não limitados a estes;<br/> 
					1) alteração contratual; <br/>
					2) abertura de empresa ou filial; <br/>
					3) certidões negativas do INSS, FGTS, Federais, ICMS e ISS; <br/>
					4) declaração de ajuste do imposto de renda pessoa física; <br/>
					5) elaboração de escrituração contábil e Balanço Geral.
				</p><p>
					5. DA VIGÊNCIA E RESCISÃO
				</p><p>
					5.1. O presente contrato vigorará a partir de ".$dadosContrato->DataAtuala().", por prazo indeterminado, podendo a qualquer tempo ser rescindido a qualquer tempo, mediante solicitação efetuada através do portal Contador Amigo.
				</p><p>
					5.2. No caso de rescisão, a dispensa pel</a CONTRATANTE/> da execução de quaisquer serviços, seja qual for a razão, durante o prazo do pré-aviso, deverá ser feita por escrito, não a desobrigando do pagamento dos honorários integrais até o termo final do mês.
				</p><p>
					5.3. A falta de pagamento de qualquer parcela de honorários faculta </à CONTRATADA/> suspender imediatamente a execução dos serviços ora pactuados, bem como considerar rescindido o presente, independentemente de notificação judicial ou extrajudicial
				</p><p>
					5.4. A falência ou a concordata d</a CONTRATANTE/> facultará a rescisão do presente pel</a CONTRATADA/>, independentemente de notificação judicial ou extrajudicial, não estando incluídos nos serviços ora pactuados a elaboração das peças contábeis arroladas no artigo 159 do Decreto-Lei 7.661/45 e demais decorrentes.
				</p><p>
					5.5. Considerar-se-á rescindido o presente contrato, independentemente de notificação judicial ou extrajudicial, caso qualquer das partes CONTRATANTES venha a infringir cláusula ora convencionada.
				</p><p>
					5.6. A assistência d</a CONTRATADA/> </à CONTRATANTE/>, após a denúncia do contrato, ocorrerá no prazo de 30 (trinta) dias.
				</p><p>
					6. DO FORO
				</p><p>
					Fica eleito o foro da cidade de ".$contador->getCidade().", com expressa renúncia a qualquer outro, por mais privilegiado que seja, para dirimir as questões oriundas da interpretação e execução do presente contrato.
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
		$contrato = str_replace('</contador_estabelecida/>','estabelecida',$contrato);
		$contrato = str_replace('</contador_Portadora/>','Portadora',$contrato);
		$contrato = str_replace('</contador_domiciliada/>','domiciliada',$contrato);	
	}
		
?>


<div style="text-align:right; margin-right:12px; margin-top:0">
    <a id="btPremiumFecha">
        <img src="images/x.png" width="8" height="9" border="0" alt="fechar contrato" title="fechar contrato" />
    </a>
</div>
<div style="border:1px solid #CCC; overflow: auto; height: 309px; width: 94%; margin-top: 10px; margin-left: 10px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; padding: 9px 0px 9px 9px;">
<div style="text-align:center; font-weight:bold; margin-top: 10px; margin-bottom:5px">CONTRATO DE PRESTAÇÃO DE SERVIÇOS</div>
    <div style="text-align:left;">
        <?php echo $contrato; ?>                                              
    </div>
</div>
<div style='padding: 10px 15px; text-align:left;'>
    <input id="contador" type="hidden" value="<?php echo $contador->getId(); ?>" />
    <input id="termoPremium" name="cheTermos" type="checkbox">&nbsp;&nbsp;Li e concordo com os termos e condições de serviço.<br>
    <input id="termoSecundario" name="termoSecundario" type="checkbox">&nbsp;&nbsp;Compreendo que o plano o atende apenas empresas Prestadoras de Serviço.
    <br><br>
    <button id="btPremiumProsseguir" >Contratar</button>
</div>    

<script>
	$(function(){
		
		$('#btPremiumFecha').click(function(){
			$('#contrato_premio').hide();
		});

	
		$('#btPremiumProsseguir').click(function() {
			
			var statuschecked = false;
			
			// verifica se o termo esta foi confirmado.
			if($("#termoPremium").is(':checked')) {
				
				statuschecked = true;
				
				// verifica se o certificado digital foi confirmado.
				if($('#termoSecundario').is(':checked')) {
					statuschecked = true;
				} else {
					statuschecked = false;
					alert('O Plano Premium no momento não atende empresas do Comércio. Para prosseguir, marque o item "Compreendo que o plano o atende apenas empresas Prestadoras de Serviço".');
				}
				
			} else { 
				statuschecked = false;
				alert('É necessário concordar com termos e condições de serviço.');	
			}
	
			// Verifica se esta tudo certo para a contratação do plano. 
			if(statuschecked) {
				
				$('#formContador').val($('#contador').val());
				
				$('input:radio[name="radPlano"]').each(function(){
					if($(this).attr("checked")){
						assinatura = $(this).val();
					}
				});
		
				if( assinatura  == "mensalidade" ) {
					valorAssinatura = $('#id_mensalidade').val();
				}
				
				if( assinatura == "trimestral" ) {
					valorAssinatura = $('#id_trimestral').val();	
				}
				
				if( assinatura == "semestral" ) {
					valorAssinatura = $('#id_semestral').val();	
				}
				
				if( assinatura == "anual" ) {
					valorAssinatura = $('#id_anual').val();
				}
				
				$('#formValor').val(valorAssinatura);
				
				$('#atualizaplanoForm').submit();
			}
		});
	});
</script>        
        