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

  <br>
<span class="tituloPeq">4. Obter um certificado digital</span><br />
<blockquote> 
O certificado digital é necessário para você emitir por conta própria as guias de seus impostos. Pode ser adquirido junto às agências certificadoras. Você precisará do <strong><br>
e-CNPJ
      modelo A1 ou A3. </strong>Veja as diferenças:<strong><br />
  <br />
      Modelo A1 
      - </strong>Tem validade de 1 ano  e não necessita de cartões ou tokens. É instalado diretamente  em sua máquina e você pode exportá-lo facilmente para outros computadores. <br />
  <br />
  <strong>O modelo A3</strong> - Tem  validade de 3 anos. Vem gravado num smartCard (tipo um cartão de crédito com chip),  ou token (parecido com um pen drive). Se optar pelo smartCard, você precisará adquirir  ainda a<strong> leitora do cartão</strong>. Se optar pelo <strong>token</strong>, terá que comprar o dispositivo junto com o certificado.<br />
 </blockquote>
  <br>

Para você, que é nosso assinante, temos um presente: <a href="http://www.digitalsigncertificadora.com.br/ardiscount/index/tipo/477TB5A8?pa=Report" target="_blank">adquira seu Certificado Digital com até 10% de desconto na Digital Sign!</a>. <br>
Recomendamos o E-CNPJ ME, EPP E MEI TOKEN, com duração de 18 meses<br /><br />
ATENCÃO: antes de concluir a compra do seu certificado digital, <a href="https://www.digitalsigncertificadora.com.br/pt/postos-de-atendimento">verifique se existe um Posto de Atendimento DigitalSign próximo da sua localidade</a> para a realizar a validação presencial. Não havendo, procure uma agência certificadora mais conveniente para você. Experimente a Serpro, comercializada pelos Correios, seguindo este <a href="certificado_digital.php">tutorial</a>.<br>
<br>
<center><input name="" type="button" value="Entrar" class="btProsseguirProcedimentosIniciais"/></center>
 

</div>

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
