<?php include 'header_restrita.php' ?>


<div class="principal">


<?

$sql_dados_cobranca = "SELECT sacado,endereco,bairro,cidade,cep,uf,forma_pagameto,h.status_pagamento, data_pagamento vencimento FROM dados_cobranca cob INNER JOIN historico_cobranca h ON cob.id = h.id WHERE h.id = '".$_SESSION["id_userSecaoMultiplo"]."' ORDER BY data_pagamento DESC LIMIT 1";
$resultado_dados_cobranca = mysql_query($sql_dados_cobranca) or die (mysql_error());
$linha_dados_cobranca_index=mysql_fetch_array($resultado_dados_cobranca);

//echo $sql_dados_cobranca;

$hoje = date('Y-m-d');
$formaPagamento = ($linha_dados_cobranca_index['forma_pagameto']);
$statusPagamento = ($linha_dados_cobranca_index['status_pagamento']);
//$dias_vencimento = $linha_dados_cobranca['dias_vencimento'];
$vencimento = $linha_dados_cobranca_index['vencimento'];
$diferenca = (strtotime($vencimento) - strtotime($hoje)) / (60*60*24);



// ***********************BALOM padrão ******************************

$informacaoBallon = '<div style="float:left; margin-top:30px"><img src="images/assistente.png" width="70" height="241" alt=""/></div><div class="bubble_left" style="width:320px; margin-left:14px; margin-right:24px; float:left;"><div style="padding:20px"><div class="saudacao"><div>Olá, ' . $_SESSION["nome_assinanteSecao"] . '</div></div><div style="font-size:13px"><strong>1.</strong> Confira ao lado as obrigações do mês e siga os tutoriais. Se optou pelo plano Premium, pule esta parte. As guias e declarações serão enviadas a você pelo Contador.<br /><br /><strong>2.</strong> Em <b>Meus pagamentos</b>, informe suas retiradas de pró-labore, distribuição de lucros e pagamentos a prestadores de serviços. Não sabe fazer isso? Fique tranquilo. As páginas são auto-explicativas. Assista também <a href="https://youtu.be/BnPCRLURuuE" target="_blank">este vídeo</a>.<br /><br /><strong>3.</strong> Mantenha o <b>livro-caixa</b> atualizado.<br /><br />Caso ocorra alguma alteração cadastral em sua empresa, lembre-se de atualizá-la aqui no Contador Amigo.</div></div></div>';


// ***********************BALOM padrão para demos ******************************

if($_SESSION['status_userSecao'] == 'demo'){

	$informacaoBallon = '<div style="float:left; margin-top:30px"><img src="images/assistente.png" width="70" height="241" alt=""/></div><div class="bubble_left" style="width:320px; margin-left:14px; margin-right:24px; float:left;"><div style="padding:20px"><div class="saudacao">Instruções:</div><div style="font-size:13px"><strong>1.</strong> Confira ao lado as obrigações do mês e siga os tutoriais. Se optar pelo plano Premium, pule esta parte. As guias e declarações serão enviadas a você pelo Contador.<br /><br /><strong>2.</strong> Em <b>Meus pagamentos</b>, informe suas retiradas de pró-labore, distribuição de lucros e pagamentos a prestadores de serviços. Não sabe fazer isso? Fique tranquilo. As páginas são auto-explicativas. Assista também <a href="https://youtu.be/BnPCRLURuuE" target="_blank">este vídeo</a>.<br /><br /><strong>3.</strong> Mantenha o <b>livro-caixa</b> atualizado.<br /><br /><a href="minha_conta.php" class="nulo"><div class="div_botao">Gostando do Contador Amigo?<br>Ative agora mesmo sua assinatura!</div></a></div></div></div>';
}


// *******************************ballon para ativos prestes a vencer que pagam por boleto**************

if($diferenca <= 4 && (strtotime($hoje) <= (strtotime($vencimento)))){
	
	if(($_SESSION['status_userSecao'] == 'ativo') && ($formaPagamento == 'boleto')){
					
		$informacaoBallon = '<div style="float:left; margin-top:30px"><img src="images/assistente.png" width="70" height="241" alt=""/></div><div class="bubble_left" style="width:320px; margin-left:14px; margin-right:24px; float:left; min-height:320px"><div style="padding:20px; font-size:12px"><div class="saudacao"><div>Olá, ' . $_SESSION["nome_assinanteSecao"] . '</div></div><div>Sua mensalidade do <strong>Contador Amigo</strong> vence agora no dia <span style="color:#a61d00; font-weight:bold">' . date('d/m/Y',strtotime($vencimento)) . '</span>. Clique no botão abaixo para acessar sua conta e gerar o boleto.<br><br>Prefira sempre a <strong>cobrança por cartão</strong>. É automática e você não precisa se preocupar com a data de vencimento. Para alterar, vá em <a href="/minha_conta.php">dados da conta</a> e informe seus dados.<br><br>Lembramos que agora você dispõe do <strong>Plano Premium</strong>, nele um contador gera as guias e declarações para você.<br><br><center><input type="button" name="button2" id="button2" value="Acessar minha conta" onclick="'; 
		
		$informacaoBallon .= "window.location='minha_conta.php'"; 
		$informacaoBallon .= '" /></center></div></div></div>';
	}
					
					
//***********************Ballon para DEMO prestes a vencer************************************************					

	if($_SESSION['status_userSecao'] == 'demo'){

		$informacaoBallon = '<div style="float:left; margin-top:30px"><img src="images/assistente.png" width="70" height="241" alt=""/></div><div class="bubble_left" style="width:320px; margin-left:14px; margin-right:24px; float:left; min-height:320px"><div style="padding:20px; font-size:12px"><div class="saudacao"><div>Olá, ' . $_SESSION["nome_assinanteSecao"] . '</div></div><div>Sua assinatura teste do <strong>Contador Amigo</strong> vence agora no dia <span style="color:#a61d00; font-weight:bold">' . date('d/m/Y',strtotime($vencimento)) . '</span>. Esperamos que tenha gostado e possamos tê-lo como nosso assinante!<br><br>Clique no botão abaixo e selecione o plano mais conveniente para sua empresa.<br><br>Lembramos que agora você dispõe do <strong>Plano Premium</strong>, nele um contador gera as guias e declarações para você.<br><br>Aproveite também nossos descontos para a assinatura semestral ou anual.<br><br><center><input type="button" name="button2" id="button2" value="Escolher Planos" onclick="';
		
		$informacaoBallon .= "window.location='minha_conta.php';";
		$informacaoBallon .= '" /></center></div></div></div>';
	}
}

			//************************************Ballon para vencido***************************

$datas = new Datas();

$vencimentoAux = $vencimento;

//Verifica se a data do vencimento e um dia util.
if(!$datas->ifDiaUtil($vencimentoAux) || date('w', strtotime($vencimentoAux)) == 5){
	$vencimentoAux = $datas->somarDiasUteis($vencimentoAux,1);
}

if(strtotime($hoje) >= (strtotime($vencimentoAux))) {
				
	$informacaoBallon = '<div style="float:left; margin-top:30px"><img src="images/boneca_preocupada_index.png" width="70" height="239" alt=""/></div><div class="bubble_left" style="width:320px; margin-left:14px; margin-right:24px; float:left; min-height:320px"><div style="padding:20px; font-size:12px"><div class="saudacao"><div>Assinatura pendente</div></div><div>Sua mensalidade do <strong>Contador Amigo</strong> já venceu e sua conta ficará sujeita a desativação a partir de <span class="destaque"><strong>' . date('d/m/Y',(mktime(0,0,0,date('m',strtotime($vencimento)),date('d',strtotime($vencimento))+5,date('Y',strtotime($vencimento))))) . '</strong></span>. Clique no botão abaixo e renove sua assinatura.<br><br>Lembramos que agora você dispõe do <strong>Plano Premium</strong>, nele um contador gera as guias e declarações para você.<br><br>Prefira sempre a <strong>cobrança por cartão</strong>. É automática e você não precisa se preocupar com a data de vencimento. <br><br><center><input type="button" name="button2" id="button2" value="Renovar assinatura" onclick="';$informacaoBallon .= "window.location='minha_conta.php';";$informacaoBallon .= '"></center></div></div></div>';
} 
			

echo $informacaoBallon;
?>



<?php 
	$show_item = new Show();
	$agenda = new Agenda();
	$mes_atual = floatval(date("m"));
	$itens = $agenda->listarItensMes($mes_atual);
	$des_exibido = false;
?>



<div class="obrigacoes_mes" style="width:530px; float:left; display:block; margin-bottom: 10px ">

	<div class="tituloVermelho" style="margin-bottom:10px">Suas obrigações para <?php echo $agenda->getTextoMes($mes_atual); ?></div>
	<?php while( $item = mysql_fetch_array($itens) ) { #percorre cada item e exibe as informações correnspondentes?>
		<?php if( $item['dia'] >= $des->getData($mes_atual) && !$des_exibido ){ ?>
			<?php 
				if( $des->getPrestados() && $des->getTomados() && $des->getTomados_outro_municipio() ) { 
					$des_exibido = true;
			?>
			<a href="des.php"  class="nulo">
				<div class="note">
					<div style="margin-bottom:5px; font-family:'Varela Round', sans-serif; color: #024a68; font-size:13px"><i class="fa fa-chevron-right"  style="color:#a61d00"></i> Dia <?php echo $des->getData($mes_atual); ?> - <?php echo $des->getNomeTexto(); ?></div>
					<div>Lançamento das notas de serviços prestados e tomados junto ao município. <span style="color:#336699; text-decoration:underline">Ver tutorial.</span></div>
				</div>
			</a>
			<?php } ?>

		<?php } ?>

		<?php if( $item['tipo'] == 5 ){ #verifica se é do tipo DCTF para verificação sobre a construção civil?>

			<?php if( $show_item->isConstrucaoCivil() ){#se for da construção civil, exibe os dados?>

				<?php $tipo = $agenda->getInfoTipo($item['tipo']); ?>
				<a href="<?php echo $tipo['pagina']; ?>" class="nulo">
					<div class="note">
						<div style="margin-bottom:4px; font-family:'Varela Round', sans-serif; color: #024a68; font-size:13px"><i class="fa fa-chevron-right"  style="color:#a61d00"></i> Dia <?php echo $item['dia']; ?> - <?php echo $tipo['frase']; ?></div>
						<div><?php echo $tipo['texto']; ?> <span style="color:#336699; text-decoration:underline">Ver tutorial.</span></div>
					</div>
				</a>

			<?php } ?>

		<?php } else{ ?>

			<?php $tipo = $agenda->getInfoTipo($item['tipo']); ?>
			<a href="<?php echo $tipo['pagina']; ?>" class="nulo">
				<div class="note">
					<div style="margin-bottom:4px; font-family:'Varela Round', sans-serif; color: #024a68; font-size:13px"><i class="fa fa-chevron-right"  style="color:#a61d00"></i> Dia <?php echo $item['dia']; ?> - <?php echo $tipo['frase']; ?></div>
					<div><?php echo $tipo['texto']; ?> <span style="color:#336699; text-decoration:underline">Ver tutorial.</span></div>
				</div>
			</a>

		<?php } ?>

	<?php } ?>

	<?php if( !$des_exibido ) {?>

		<?php if( $des->getPrestados() && $des->getTomados() && $des->getTomados_outro_municipio() ) { ?>
		<a href="des.php"  class="nulo">
			<div class="note">
				<div style="margin-bottom:5px; font-family:'Varela Round', sans-serif; color: #024a68; font-size:13px"><i class="fa fa-chevron-right"  style="color:#a61d00"></i> Dia <?php echo $des->getData($mes_atual); ?> - <?php echo $des->getNomeTexto(); ?></div>
				<div>Lançamento das notas de serviços prestados e tomados junto ao município. <span style="color:#336699; text-decoration:underline">Ver tutorial.</span></div>
			</div>
		</a>
		<?php } elseif(!$des->getCidade()) { ?>
		<a href="des.php"  class="nulo">
			<div class="note">
				<div style="margin-bottom:5px; font-family:'Varela Round', sans-serif; color: #024a68; font-size:13px"><i class="fa fa-chevron-right"  style="color:#a61d00"></i> Envio da DES - Declaração Eletrônica de Serviços</div>
				<div>Consulte nosso <a href="/suporte.php" target="_blank" >Help Desk</a> para saber se o seu município requer o envio desta declaração.</div>
			</div>
		</a>		
		<?php } ?>


	<?php } ?>
	
	<div style="clear: both; height: 10px"></div>

	<div>Na dúvida, consulte o <a href="suporte.php">Help Desk</a> ou reveja o <a href="javascript:abreDiv('video')">vídeo de orientações</a>.</div>
</div>

	<div style="clear: both; height: 20px"></div>


 <h2>Vídeos que você precisa assistir</h2>
<div id="box_home">
<h3>Como sacar dinheiro da empresa</h3>
 <iframe id=videos_sugeridos src="https://www.youtube.com/embed/BnPCRLURuuE?rel=0" frameborder="0" allowfullscreen></iframe>
</div>
 
 <div id="box_home">
 <h3>Obrigações mensais de uma ME</h3>
 <iframe id=videos_sugeridos src="https://www.youtube.com/embed/VJNZBVbFDSM?rel=0" frameborder="0" allowfullscreen></iframe>
 </div>
 
<div id="box_home" style="margin-right: 0px">
<h3>Simples em 2018</h3>
<iframe id=videos_sugeridos src="https://www.youtube.com/embed/A_kOKCh-xAI?rel=0" frameborder="0" allowfullscreen></iframe>

</div>
	


 <!--dicas-->
<h2>Dicas de amigo</h2>
<div id="box_home">
<h3>Certificado digital barato</h3>
	<a href="http://www.validcertificadora.com.br/e-CNPJ-A1.htm/388C546B-98DA-4A0E-B8F3-89BBA8FB5FEE/RD007797" target="_blank"><img src="images/valid_dica.png" width="300" height="71" alt="Valid Certificadora" style="margin-bottom: 10px; border: 0px"/></a><br>Para cumprir sozinho com suas obrigações fiscais, é indispensável dispor de um certificado digital, tipo E-CNPJ. Só por ter visitado nossa página, você já pode adquiri-lo pela Valid Certificadora com um super desconto: apenas R$ 189,75, em 3 x sem juros. <a href="http://www.validcertificadora.com.br/e-CNPJ-A1.htm/388C546B-98DA-4A0E-B8F3-89BBA8FB5FEE/RD007797" target="_blank">Solicite aqui o seu</a>. </div>
 
 <div id="box_home">
 <h3>Serviço de apoio às MPE</h3>
	 <a href="http://www.sebrae.com.br/sites/PortalSebrae" target="_blank"><img src="images/sebrae.png" width="300" height="71" alt="Sebrae" style="margin-bottom: 10px; border: 0px"/></a><br>O <a href="http://www.sebrae.com.br/sites/PortalSebrae" title="Sebrae" target="_blank">Serviço Brasileiro de Apoio às Micro e Pequenas Empresas</a> (Sebrae) é uma entidade privada que promove a competitividade e o desenvolvimento sustentável dos empreendimentos de micro e pequeno porte. Possui um série de cursos e serviços gratuitos que ajudarão seu negócio. </div>
 
<div id="box_home" style="margin-right: 0px">
<h3>Crédito com taxas menores</h3>
	<a href="http://www.desenvolvesp.com.br/" target="_blank"><img src="images/desenvolve_sp_dica.png" width="300" height="71" alt="Desenvolve SP" style="margin-bottom: 10px; border: 0px"/></a><br>
O <a href="http://www.desenvolvesp.com.br/" target="_blank">Banco Desenvolve SP</a> oferece linhas de crédito a juros baixo para micros e pequenas empresas. O Crédito Digital é uma modalidade rápida e totalmente online criada para desburocratizar o acesso das pequenas empresas ao financiamento de capital de giro, com aprovação em até dois dias úteis.</div>
	
<div style="clear: both"></div>

<!--video de boas vindas-->
<div id="video" class="box_visualizacao check_visualizacao x_visualizacao" style="border-style:solid; border-width:1px; border-color:#CCCCCC; position: absolute; left:50%; margin-left:-340px; top:0px; background-color:#fff; width:680px">
    <div style="padding:20px">
        <div class="titulo" style="text-align:left; margin-bottom:10px">Orientações Gerais</div>
        <video id="video1" width="640" height="360" controls>
        <source src="videos/orientacoes_gerais.mp4" type='video/mp4'> 
        </video>
    </div>
</div>
<!--fim do video de boas vindas-->

<!--janela promocao -->
<script type="text/javascript">
$(document).ready(function(e) {

	var msg1 = 'É necessário preencher o campo';
	
	$('#btnAbreIndica').click(function(e){
		e.preventDefault();
		$('#amigo_indica').val('');
		$('#email_amigo_indica').val('');
		$('#mensagem_indica').val('');
		$('#DivFormIndica').css('display','block');
		$('#DivMensagemIndica').css('display','none');
		$('#DivAguardeIndica').css('display','none');
		$('#promo').css('display','block');
	});
	
	$('#btnOutroIndica').click(function(e){
		e.preventDefault();
		$('#amigo_indica').val('');
		$('#email_amigo_indica').val('');
		$('#mensagem_indica').val('');
		$('#DivFormIndica').css('display','block');
		$('#DivMensagemIndica').css('display','none');
		$('#DivAguardeIndica').css('display','none');
	});

	$('#btnFechaIndica').click(function(e){
		e.preventDefault();
		$('#promo').css('display','none');
	});
	
    $('#btnEnviarIndica').click(function(e){
		e.preventDefault();
		if($('#amigo_indica').val() == ''){
			alert('Preencha o nome do amigo!');
			$('#amigo_indica').focus();
			return false;
		}else if($('#email_amigo_indica').val() == ''){
			alert('Preencha o e-mail do amigo!');
			$('#email_amigo_indica').focus();
			return false;
		}else if($('#mensagem_indica').val() == ''){
			alert('Deixe uma mensagem');
			$('#mensagem_indica').focus();
			return false;
		}else if(fnValidaEmail($('#email_amigo_indica').val()) == false){
			$('#email_amigo_indica').focus();
			return false;
		}

		$.ajax({
			url:"envia_indicacao.php",
			data: 'nome=' + $('#amigo_indica').val()+'&email=' + $('#email_amigo_indica').val()+'&mensagem=' + $('#mensagem_indica').val(),
			type: 'POST',
			async: true,
			cache: false,
			beforeSend: function(){
				$('#DivFormIndica').css('display','none');
				$('#DivAguardeIndica').css('display','block');
			},
			success: function(result){
				if(result == "1"){
					$('#DivAguardeIndica').css('display','none');
					$('#DivMensagemIndica').css('display','block');
				}else{
					$('#DivFormIndica').css('display','block');
					$('#DivAguardeIndica').css('display','none');
				}
			}
		});
	});
});

function fnValidaEmail(v_email){

	var jSintaxe;
	var jArroba;
	var jPontos;

	var ExpReg = new RegExp('[^a-zA-Z0-9\.@_-]', 'g');

        jSintaxe = !ExpReg.test(v_email);
	if(jSintaxe == false){
            window.alert('Favor digitar o e-mail corretamente!');
            return false;
	}
	jPontos = (v_email.indexOf('.') > 0) && !(v_email.indexOf('..') > 0);
	if (jPontos == false){
            window.alert('Favor digitar o e-mail corretamente!');
            return false;
	}
	jArroba = (v_email.indexOf('@') > 0) && (v_email.indexOf('@') == v_email.lastIndexOf('@'));
	if (jArroba == false){
            window.alert('Favor digitar o e-mail corretamente!');
            return false;
	}

	return true;

}
</script>
<div class="layer_branco" style="display: none; top:200px; left:50%; width:330px; margin-left:-250px; z-index: 1001" id="promo">
	
    <div style="padding:15px; padding-top:10px">

		<div style="width:100%; text-align:right">
        	<a href="javascript:fechaDiv('promo')"><img src="images/x.png" width="8" height="9" border="0" /></a>
        </div>
		<div class="titulo" style="margin-bottom:20px; text-align: left">
        	Indique nosso portal
        </div>

		<div style="display: block;" id="DivFormIndica">
            <form name="promocao" id="promocao" action="enviar o email para o amigo e cadastrar no banco, incluir página pra visualizar o banco no admin" style="display:block">
                <div style="float:left; width:100px"><label for="amigo_indica">Nome do amigo:</label> </div>
                <div style="float:left"><input type="text" maxlenght="100" id="amigo_indica" name="amigo_indica" style="width:190px" /></div>
                <div style="clear:both; height:5px"></div>
                
                <div style="float:left; width:100px"><label for="email_amigo_indica">E-mail do amigo:</label> </div>
                <div style="float:left"><input type="text" maxlenght="100" id="email_amigo_indica" name="email_amigo_indica" style="width:190px" /></div>
                <div style="clear:both; height:15px"></div>
                
                <div style="margin-bottom:10px; text-align:left"><label for="mensagem_indica" style="margin-bottom:5px">Digite uma mensagem para ele:</label></div>
                <div style="margin-bottom:10px"><textarea name="mensagem_indica" id="mensagem_indica" style="width:98%; height:100px; "></textarea></div>
                <div style="text-align:center"><input type="submit" value="Enviar" id="btnEnviarIndica" /></div>
            </form>
		</div>

		<div style="display:none" id="DivAguardeIndica">
			<div class="tituloVermelhoLight" style="margin-bottom:10px">
            	Enviando...
            </div>
		</div>
        
		<div style="display:none" id="DivMensagemIndica">
			<div class="tituloVermelhoLight" style="margin-bottom:10px; text-align: left">
            	Indicação realizada com sucesso!
            </div>
			<div style="margin-bottom:20px; text-align: left">
            	Se, ao final do período gratuito, seu amigo efetivar a assinatura, você será creditado automaticamente de uma mensalidade no <strong>Contador Amigo</strong>.
            </div>
            <div style="text-align:center">
                <input name="btnOutroIndica" id="btnOutroIndica" type="button" value="Indicar outro amigo" />&nbsp;&nbsp;&nbsp;
                <input name="btnFechaIndica" id="btnFechaIndica" type="button" value="Fechar" onclick="javascript:fechaDiv('promo')" /></form>
            </div>
		</div>

	</div>

<div>
<!--fim da janela promocao -->
</div>
</div>

<?php include 'rodape.php' ?>


