<?php include 'header_restrita.php' ?>

<script>
$(document).ready(function(e) {
  $('.btProsseguirProcedimentosIniciais').click(function(e){
		e.preventDefault();
		var $botao = $(this);
		$botao.val('Aguarde...');
		$.ajax({
			url: "procedimentos_iniciais_atualiza_status.php"
			, data: "atualiza"
			, dataType: "json"
			, cache: false
			, async: true
			, success: function(retorno){
				if(retorno.status == true){
					location.href = "index_restrita.php";
				}else{
					$botao.val('Prosseguir');
				}
			}
		});
	});
});
</script>

<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Procedimentos Iniciais</div>
  <br />
    Parabéns por sua decisão!<br />
  <br />
<strong>Contador Amigo </strong>vai se provar uma ferramenta extremamente útil para sua microempresa. Em breve você estará familiarizado com todas as funções e precisará de poucos minutos ao mês para tratar de seus assuntos contábeis. Porém, antes de mais nada é necessário realizar alguns procedimentos iniciais:<br /><br />

<span class="tituloPeq">1. Cadastrar os dados de sua empresa e do(s) sócio(s) ou proprietário</span><br />
    <blockquote>Vá em <a href="https://www.contadoramigo.com.br/meus_dados_empresa.php?act=new">Meus Dados/Cadastro de Empresas</a> e <a href="https://www.contadoramigo.com.br/meus_dados_socio.php">MeusDados/Cadastro de Sócios</a> e preencha os cadastros. Mesmo se for o único proprietário, deve preencher também o cadastro de sócio. Quanto mais informações  incluir, mais fácil serão suas operações 
    fiscais realizadas através do site.</blockquote><br />
  
<span class="tituloPeq">2. Instalar o programa Sefip no seu computador</span><br />
  <blockquote>Este programa é necessário para o cumprimento de algumas obrigações junto à Previdência Social. Veja nossas orientações para <a href="configuracao_sefip.php">download e configuração</a>.</blockquote>
  <br />

 <span class="tituloPeq">3. Configurar o Java e o Internet Explorer</span><br />
  <blockquote>Para que você possa gerar as guias dos impostos e enviar suas declarações à Receita Federal, o <strong>plugin do Java</strong> e o navegador <strong>Internet Explorer</strong> precisam estar devidamente instalados e configurado em seu computador. Siga nossos tutoriais:<br />
   <ul>
  <li><a href="java_tutorial.php">Configuração do Java</a></li>
  <li><a href="ie_tutorial.php">Configuração do Internet Explorer</a></li>
  <li><a href="avast_avg_tutorial.php">Configuração do Antivírus</a></li>
   </ul>
  </blockquote>

  
<span class="tituloPeq">4. Obter um certificado digital ou chave de acesso</span><br />
<blockquote> 
  <p>Para gerar as guias de seus impostos e declarações, você precisará de um <strong>certificado digital</strong> ou <strong>chave de acesso</strong> (fornecida pela Caixa). O certificado digital é bem mais prático, mas é pago. A chave  de acesso é gratuita, porém há uma certa burocracia para obtê-la. Se você adora desafios e não quer pagar por um certificado digital, veja as <a href="simples_e_gfip_sem_certificado_restrita.php">instruções</a> para obter a chave de acesso.<br />
  <br />
    O certificado digital pode ser adquirido junto às agências certificadoras. As principais são os <a href="https://certificados.serpro.gov.br/arcorreiosrfb/#" target="_blank">Correios (Serpro)</a>, <a href="http://www.certisign.com.br/certificado-digital/para-empresa/ecnpj/comprar" target="_blank">Certisign</a> e  <a href="https://serasa.certificadodigital.com.br/produtos/e-cnpj/" target="_blank">Serasa Experian</a>. O mais barato é o dos Correios. Você precisará adquirir o <strong>e-CNPJ
      modelo A1 ou A3. </strong>Veja as diferenças:<strong><br />
  <br />
      Modelo A1
      - </strong>É o mais em conta. Tem validade de 1 ano  e não necessita de cartões ou tokens. É instalado diretamente  em sua máquina e você pode exportá-lo facilmente para outros computadores, se quiser. <br />
  <br />
  <strong>O modelo A3</strong> - Tem  validade de 3 anos   (existem também opções de 1 ano) e vem gravado num smartCard (tipo um cartão de crédito com chip),  ou token (parecido com um pen drive). Se optar pelo smartCard, você precisará adquirir  ainda a<strong> leitora do cartão</strong>. Se optar pelo <strong>token</strong>, terá que comprar o dispositivo junto com o certificado.<br />
  <br />
    <strong>O Contador Amigo recomenda o modelo A1 dos Correios </strong>e, para facilitar o seu trabalho, preparou um <a href="certificado_digital.php">passo-a-passo</a> de como obtê-lo.<br />
    <br />

<span class="destaque"><br />
Realizados todos os procedimentos acima, você estará pronto para usufruir plenamente as facilidades oferecidas pelo Contador Amigo!</span>
    <br /><br />
 
    
 

  <input name="" type="button" value="Prosseguir" class="btProsseguirProcedimentosIniciais"/>
 

</p></blockquote></div>

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
<?php include 'rodape.php' ?>
